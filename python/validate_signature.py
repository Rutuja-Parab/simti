import sys
import cv2
import numpy as np
import json
import traceback

def check_signature_guidelines(image_path):
    result = {
        "errors": []
    }

    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"error": f"Could not read image: {image_path}"}))
        return

    height, width = img.shape[:2]

    # if not (300 <= width <= 600 and 100 <= height <= 250):
    #     result["errors"].append("Signature dimensions should be approximately 3.5cm x 1.5cm (300–600 x 100–250 px).")

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    white_ratio = np.sum(gray > 230) / (width * height)
    if white_ratio < 0.6:
        result["errors"].append("Background should be clean white (at least 60% white pixels).")

    if height > width:
        result["errors"].append("Signature must be horizontal, not vertical.")

    dark_ratio = np.sum(gray < 60) / (width * height)
    if dark_ratio < 0.01:
        result["errors"].append("Signature ink appears too light or not in black ink.")

    if len(result["errors"]) == 0:
        print(json.dumps({"status": "Perfect"}))
    else:
        print(json.dumps(result, indent=2))


if __name__ == "__main__":
    try:
        path = sys.argv[1]
        check_signature_guidelines(path)
    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "trace": traceback.format_exc()
        }))
