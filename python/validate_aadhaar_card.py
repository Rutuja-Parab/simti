import sys
import cv2
import numpy as np
import json
import traceback

def check_aadhaar_card(image_path):
    result = {"errors": []}

    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"error": f"Could not read image: {image_path}"}))
        return

    height, width = img.shape[:2]

    if height < 500 or width < 800:
        result["errors"].append("Aadhaar card image resolution is too low. Must be at least 800x500 pixels.")

    if height > width:
        result["errors"].append("Aadhaar card must be in landscape orientation.")

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    white_ratio = np.sum(gray > 230) / (width * height)
    if white_ratio < 0.5:
        result["errors"].append("Background should be plain white or light (at least 50% white pixels).")

    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edged = cv2.Canny(blurred, 50, 150)
    contours, _ = cv2.findContours(edged, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    card_detected = False
    for cnt in contours:
        approx = cv2.approxPolyDP(cnt, 0.02 * cv2.arcLength(cnt, True), True)
        area = cv2.contourArea(cnt)
        if len(approx) == 4 and area > 50000:
            card_detected = True
            break

    if not card_detected:
        result["errors"].append("No Aadhaar card-like rectangle detected in the image.")

    if not result["errors"]:
        print(json.dumps({"status": "Valid Aadhaar image"}))
    else:
        print(json.dumps(result, indent=2))


if __name__ == "__main__":
    try:
        path = sys.argv[1]
        check_aadhaar_card(path)
    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "trace": traceback.format_exc()
        }))
