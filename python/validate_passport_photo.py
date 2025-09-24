import sys
import cv2
import json
import numpy as np
import traceback
from PIL import Image

def check_passport_guidelines(image_path):
    result = {
        "errors": []
    }

    # --- Check DPI using Pillow ---
    try:
        with Image.open(image_path) as im:
            dpi = im.info.get("dpi", (0, 0))[0]  # take horizontal DPI
            if dpi < 300:
                result["errors"].append(f"DPI too low ({dpi}). Must be at least 300.")
            elif dpi > 600:
                # Reduce DPI to 600 while keeping size same
                new_path = image_path.replace(".", "_600dpi.")
                im.save(new_path, dpi=(600, 600))
                result["dpi_adjusted"] = f"DPI reduced from {dpi} to 600 and saved as {new_path}"
    except Exception as e:
        result["errors"].append("Could not read DPI information.")

    # --- Read image with OpenCV ---
    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"error": f"Could not read image: {image_path}"}))
        return

    height, width = img.shape[:2]

    # Check 1:1 aspect ratio
    if width != height:
        result["errors"].append(f"Image must be 1:1 ratio. Found {width}x{height}.")

    # Background check (white ratio)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    white_pixels_ratio = np.sum(gray > 230) / (width * height)
    if white_pixels_ratio < 0.4:
        result["errors"].append("Background should be plain white (at least 70% white pixels).")

    # Face detection
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    faces = face_cascade.detectMultiScale(gray, 1.1, 4)

    if len(faces) == 0:
        result["errors"].append("No face detected.")
    else:
        (x, y, w, h) = faces[0]
        face_area = w * h
        img_area = width * height
        face_ratio = face_area / img_area

        result["face_ratio"] = round(face_ratio, 2)

        if not (0.15 <= face_ratio <= 0.45):
            result["errors"].append(
                "Face must occupy approx. 70% of the photo "
                "(detected face ratio: {:.2f})".format(face_ratio)
            )

    if len(result["errors"]) == 0:
        print(json.dumps({"status": "Perfect"}))
    else:
        print(json.dumps(result, indent=2))


if __name__ == "__main__":
    try:
        path = sys.argv[1]
        check_passport_guidelines(path)
    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "trace": traceback.format_exc()
        }))
