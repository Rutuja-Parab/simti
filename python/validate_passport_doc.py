import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

from pdf2image import convert_from_path
import cv2
import numpy as np
import json
import traceback
import fitz
import os
import tempfile
import easyocr
import re
import warnings
warnings.filterwarnings("ignore", message="'pin_memory' argument is set as true.*")
from datetime import datetime

def convert_pdf_to_image(pdf_path):
    try:
        doc = fitz.open(pdf_path)
        page = doc.load_page(0)
        pix = page.get_pixmap(dpi=300)
        temp_image_path = os.path.join(tempfile.gettempdir(), "passport_page1.jpg")
        pix.save(temp_image_path)
        doc.close()
        return temp_image_path
    except Exception as e:
        raise Exception(f"PDF conversion failed: {str(e)}")

def preprocess_image(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY) if len(image.shape) == 3 else image.copy()
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
    enhanced = clahe.apply(gray)
    denoised = cv2.GaussianBlur(enhanced, (3, 3), 0)
    return denoised

def extract_text_with_easyocr(image_path):
    reader = easyocr.Reader(['en'], verbose=False, gpu=False)
    img = cv2.imread(image_path)
    if img is None:
        raise Exception("Could not load image for OCR")
    processed_img = preprocess_image(img)
    results = reader.readtext(processed_img, detail=1, paragraph=False)

    high_conf_text = []
    all_text = []

    for (bbox, text, confidence) in results:
        all_text.append(text)
        if confidence > 0.5:
            high_conf_text.append(text)

    return {
        'all_text': " ".join(all_text),
        'high_conf_text': " ".join(high_conf_text),
        'raw_results': results
    }

def validate_passport_number(text):
    patterns = [
        r'\b[A-Z][0-9]{7,8}\b',
        r'\b[A-Z]{1,2}[0-9]{6,8}\b',
        r'\b[0-9]{8,9}\b',
        r'\bP[A-Z0-9]{7,8}\b',
    ]
    found_numbers = []
    for pattern in patterns:
        matches = re.findall(pattern, text.upper())
        found_numbers.extend(matches)

    unique_numbers = list(set(found_numbers))
    filtered_numbers = [num for num in unique_numbers if not re.match(r'^[0-9]{8}$', num) or not is_likely_date(num)]
    return len(filtered_numbers) > 0, filtered_numbers

def is_likely_date(text):
    if len(text) == 8 and text.isdigit():
        try:
            year = int(text[:4])
            month = int(text[4:6])
            day = int(text[6:8])
            return 1900 <= year <= 2050 and 1 <= month <= 12 and 1 <= day <= 31
        except:
            return False
    return False

def detect_passport_keywords(text):
    keywords = [
        'passport', 'passeport', 'passaporte', 'reisepass', 'passaporto',
        'republic', 'federal', 'government', 'ministry', 'department',
        'nationality', 'place of birth', 'date of birth', 'sex', 'surname',
        'given name', 'given names', 'date of issue', 'date of expiry',
        'authority', 'issuing', 'country code'
    ]
    text_lower = text.lower()
    return [keyword for keyword in keywords if keyword in text_lower]

def detect_face_enhanced(img):
    try:
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY) if len(img.shape) == 3 else img
        face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        faces = face_cascade.detectMultiScale(gray, scaleFactor=1.05, minNeighbors=3, minSize=(30, 30))

        if len(faces) > 0:
            return True, len(faces), faces

        # Fallback with DNN if Haar fails
        # Load OpenCV DNN model (if downloaded)
        # You must download deploy.prototxt and res10_300x300_ssd_iter_140000.caffemodel
        # and set the correct paths
        return False, 0, []
    except Exception as e:
        print("Face detection error:", e)
        return False, 0, []

def detect_signature_below_face(img, face_boxes):
    """Try to detect signature below the face region"""
    if face_boxes is None or len(face_boxes) == 0:
        return False, "No face region to infer signature position"

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    for (x, y, w, h) in face_boxes:
        sig_y1 = y + h + 10
        sig_y2 = sig_y1 + h // 2
        sig_x1 = x
        sig_x2 = x + w

        signature_region = gray[sig_y1:sig_y2, sig_x1:sig_x2]
        if signature_region.size == 0:
            continue

        _, thresh = cv2.threshold(signature_region, 180, 255, cv2.THRESH_BINARY_INV)
        non_white_ratio = np.sum(thresh > 0) / (thresh.shape[0] * thresh.shape[1])

        if non_white_ratio > 0.02:
            return True, None

    return False, "No inked area detected below the face"

def analyze_image_quality(img):
    quality_issues = []
    height, width = img.shape[:2]

    if height < 800 or width < 600:
        quality_issues.append(f"Low resolution: {width}x{height} (recommended: min 600x800)")

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY) if len(img.shape) == 3 else img
    brightness = np.mean(gray)
    if brightness < 50:
        quality_issues.append("Image is too dark")
    elif brightness > 300:
        quality_issues.append("Image is too bright/overexposed")

    contrast = np.std(gray)
    if contrast < 20:
        quality_issues.append("Low contrast - image appears flat")

    blur_score = cv2.Laplacian(gray, cv2.CV_64F).var()
    if blur_score < 90:
        quality_issues.append("Image appears blurry")

    white_ratio = np.sum(gray > 240) / (width * height)
    if white_ratio > 0.9:
        quality_issues.append(f"Excessive white background - possible overexposure {white_ratio}")

    return quality_issues

def validate_passport_document(image_path):
    result = {"errors": []}

    if not image_path.lower().endswith('.pdf'):
        result["errors"].append("Only PDF files are allowed for passport validation")
        return result

    if not os.path.exists(image_path):
        result["errors"].append("File does not exist")
        return result

    try:
        image_path_jpg = convert_pdf_to_image(image_path)
    except Exception as e:
        result["errors"].append(f"PDF conversion failed: {str(e)}")
        return result

    try:
        img = cv2.imread(image_path_jpg)
        if img is None:
            result["errors"].append("Could not read image file")
            return result
    except Exception as e:
        result["errors"].append(f"Image loading failed: {str(e)}")
        return result

    quality_issues = analyze_image_quality(img)
    if quality_issues:
        result["errors"].extend(quality_issues)

    try:
        ocr_results = extract_text_with_easyocr(image_path_jpg)
        all_text = ocr_results['all_text']
        if len(all_text) < 50:
            result["errors"].append("Very little text detected - document may be unclear")
    except Exception as e:
        result["errors"].append(f"Text extraction failed: {str(e)}")
        return result

    if not detect_passport_keywords(all_text):
        result["errors"].append("No passport-related keywords detected")

    has_passport_num, found_numbers = validate_passport_number(all_text)
    if not has_passport_num:
        result["errors"].append("No valid passport number pattern detected")

    face_detected, face_count, face_boxes = detect_face_enhanced(img)
    if not face_detected:
        result["errors"].append("No face detected in passport photo area")
    else:
        signature_found, sig_msg = detect_signature_below_face(img, face_boxes)
        if not signature_found:
            result["errors"].append("Signature not detected below the photo" + (f": {sig_msg}" if sig_msg else ""))

    try:
        if os.path.exists(image_path_jpg):
            os.remove(image_path_jpg)
    except:
        pass

    return result

if __name__ == "__main__":
    try:
        if len(sys.argv) != 2:
            print(json.dumps({"error": "Usage: python script.py <passport_pdf_path>"}))
            sys.exit(1)

        image_path = sys.argv[1]
        result = validate_passport_document(image_path)

        if result["errors"]:
            print(json.dumps(result, indent=2, ensure_ascii=False))
            sys.exit(1)
        else:
            print(json.dumps({"status": "Perfect"}))
            sys.exit(0)

    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "trace": traceback.format_exc()
        }, indent=2))
        sys.exit(1)
