import sys
import cv2
import json
import numpy as np
import traceback

def check_passport_guidelines(image_path):
    result = {
        "errors": []
    }

    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"error": f"Could not read image: {image_path}"}))
        return

    height, width = img.shape[:2]

    if not (800 <= width <= 860 and 800 <= height <= 860):
        result["errors"].append("Image should be 3.5cm x 3.5cm (approx. 828x828 pixels at 600 DPI).")

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    white_pixels_ratio = np.sum(gray > 230) / (width * height)
    if white_pixels_ratio < 0.4:
        result["errors"].append("Background should be plain white (at least 70% white pixels).")

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
            result["errors"].append("Face must occupy approximately 70% of the photo (detected face ratio: {:.2f})".format(face_ratio))

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
