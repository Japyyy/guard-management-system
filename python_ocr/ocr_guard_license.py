import sys
import os
import json
import re
import glob
import tempfile
import datetime
from pathlib import Path

import cv2
import numpy as np
from paddleocr import PaddleOCR

OCR = PaddleOCR(
    use_doc_orientation_classify=True,
    use_doc_unwarping=True,
    use_textline_orientation=True,
    lang="en",
)

MONTH_PATTERN = r"(January|February|March|April|May|June|July|August|September|October|November|December)"
DATE_REGEX = re.compile(rf"{MONTH_PATTERN}\s+\d{{1,2}},\s+\d{{4}}", re.I)

# Expected format like: R07-202410000386 or R07-202602000184
LICENSE_REGEX = re.compile(r"R[0O]?7[-–—]?\d{8,14}", re.I)


def ensure_dir(path: str) -> None:
    os.makedirs(path, exist_ok=True)


def clean_text(value: str) -> str:
    return re.sub(r"\s+", " ", (value or "").strip()).strip()


def norm(value: str) -> str:
    return clean_text(value).lower()


def normalize_for_compare(value: str) -> str:
    return re.sub(r"[^a-z0-9]", "", norm(value))


def order_points(pts):
    rect = np.zeros((4, 2), dtype="float32")
    s = pts.sum(axis=1)
    rect[0] = pts[np.argmin(s)]
    rect[2] = pts[np.argmax(s)]

    diff = np.diff(pts, axis=1)
    rect[1] = pts[np.argmin(diff)]
    rect[3] = pts[np.argmax(diff)]
    return rect


def four_point_transform(image, pts):
    rect = order_points(pts)
    (tl, tr, br, bl) = rect

    width_a = np.linalg.norm(br - bl)
    width_b = np.linalg.norm(tr - tl)
    max_width = max(int(width_a), int(width_b))

    height_a = np.linalg.norm(tr - br)
    height_b = np.linalg.norm(tl - bl)
    max_height = max(int(height_a), int(height_b))

    dst = np.array([
        [0, 0],
        [max_width - 1, 0],
        [max_width - 1, max_height - 1],
        [0, max_height - 1]
    ], dtype="float32")

    M = cv2.getPerspectiveTransform(rect, dst)
    warped = cv2.warpPerspective(image, M, (max_width, max_height))
    return warped


def detect_and_crop_card(image_bgr):
    original = image_bgr.copy()
    h, w = original.shape[:2]

    scale = 1200 / max(h, w) if max(h, w) > 1200 else 1.0
    resized = cv2.resize(original, (int(w * scale), int(h * scale)), interpolation=cv2.INTER_AREA)

    gray = cv2.cvtColor(resized, cv2.COLOR_BGR2GRAY)
    blur = cv2.GaussianBlur(gray, (5, 5), 0)
    edged = cv2.Canny(blur, 60, 180)

    kernel = np.ones((5, 5), np.uint8)
    edged = cv2.dilate(edged, kernel, iterations=1)
    edged = cv2.erode(edged, kernel, iterations=1)

    contours, _ = cv2.findContours(edged, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)

    best_quad = None
    image_area = resized.shape[0] * resized.shape[1]

    for c in contours[:20]:
        peri = cv2.arcLength(c, True)
        approx = cv2.approxPolyDP(c, 0.02 * peri, True)

        area = cv2.contourArea(c)
        if len(approx) == 4 and area > image_area * 0.15:
            best_quad = approx.reshape(4, 2)
            break

    if best_quad is None:
        return original

    best_quad = best_quad / scale
    warped = four_point_transform(original, best_quad)

    if warped.shape[0] > warped.shape[1]:
        warped = cv2.rotate(warped, cv2.ROTATE_90_CLOCKWISE)

    return warped


def build_variants(card_bgr):
    variants = []

    base = card_bgr.copy()
    h, w = base.shape[:2]

    target_width = 1800
    if w < target_width:
        scale = target_width / w
        base = cv2.resize(base, (int(w * scale), int(h * scale)), interpolation=cv2.INTER_CUBIC)

    variants.append(("color", base))

    gray = cv2.cvtColor(base, cv2.COLOR_BGR2GRAY)
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8)).apply(gray)
    gray_bgr = cv2.cvtColor(clahe, cv2.COLOR_GRAY2BGR)
    variants.append(("gray_clahe", gray_bgr))

    thresh = cv2.adaptiveThreshold(
        clahe, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 31, 11
    )
    thresh_bgr = cv2.cvtColor(thresh, cv2.COLOR_GRAY2BGR)
    variants.append(("adaptive_thresh", thresh_bgr))

    sharp = cv2.addWeighted(clahe, 1.6, cv2.GaussianBlur(clahe, (0, 0), 3), -0.6, 0)
    sharp_bgr = cv2.cvtColor(sharp, cv2.COLOR_GRAY2BGR)
    variants.append(("sharp", sharp_bgr))

    return variants


def parse_ocr_json(tmpdir: str):
    json_files = sorted(
        glob.glob(os.path.join(tmpdir, "**", "*.json"), recursive=True),
        key=os.path.getmtime
    )

    lines = []

    for jf in json_files:
        try:
            with open(jf, "r", encoding="utf-8") as f:
                data = json.load(f)
        except Exception:
            continue

        payload = data.get("res", data)
        rec_texts = payload.get("rec_texts", [])
        dt_polys = payload.get("dt_polys", [])

        for i, text in enumerate(rec_texts):
            if not isinstance(text, str):
                continue

            text = clean_text(text)
            if not text:
                continue

            cx = 0
            cy = 0
            x1 = y1 = x2 = y2 = 0

            if i < len(dt_polys):
                poly = dt_polys[i]
                try:
                    xs = [p[0] for p in poly]
                    ys = [p[1] for p in poly]
                    cx = sum(xs) / len(xs)
                    cy = sum(ys) / len(ys)
                    x1 = min(xs)
                    y1 = min(ys)
                    x2 = max(xs)
                    y2 = max(ys)
                except Exception:
                    pass

            lines.append({
                "text": text,
                "cx": cx,
                "cy": cy,
                "x1": x1,
                "y1": y1,
                "x2": x2,
                "y2": y2,
            })

    lines.sort(key=lambda item: (item["cy"], item["cx"]))
    return lines


def run_ocr_on_image(image_bgr):
    with tempfile.NamedTemporaryFile(suffix=".png", delete=False) as tmp:
        temp_image = tmp.name

    with tempfile.TemporaryDirectory() as tmpdir:
        try:
            cv2.imwrite(temp_image, image_bgr)
            results = OCR.predict(temp_image)

            for res in results:
                try:
                    res.save_to_json(save_path=tmpdir)
                except Exception:
                    pass

            return parse_ocr_json(tmpdir)
        finally:
            try:
                os.remove(temp_image)
            except OSError:
                pass


def dedupe_lines(lines):
    deduped = []
    seen = set()

    for line in lines:
        text_key = normalize_for_compare(line["text"])
        if not text_key:
            continue

        bucket_x = int(line["cx"] // 20) if line["cx"] else 0
        bucket_y = int(line["cy"] // 12) if line["cy"] else 0
        key = (text_key, bucket_x, bucket_y)

        if key in seen:
            continue

        seen.add(key)
        deduped.append(line)

    deduped.sort(key=lambda item: (item["cy"], item["cx"]))
    return deduped


def run_multi_variant_ocr(card_bgr):
    variants = build_variants(card_bgr)
    all_lines = []
    by_variant = {}

    for name, variant_img in variants:
        lines = run_ocr_on_image(variant_img)
        by_variant[name] = lines
        all_lines.extend(lines)

    merged = dedupe_lines(all_lines)
    return merged, by_variant


def looks_like_name(text: str) -> bool:
    t = clean_text(text)
    nt = norm(t)

    if "," not in t:
        return False

    blocked = [
        "license", "expiry", "issued", "address", "category",
        "security guard", "date printed", "complete address",
        "philippine national police", "lastname", "firstname",
        "middlename", "qualifier"
    ]
    if any(word in nt for word in blocked):
        return False

    return True


def split_name(text: str):
    text = clean_text(text)
    text = re.sub(r"(?i)LASTNAME\.?\s*,?\s*FIRSTNAME\s+MIDDLENAME\s+QUALIFIER", "", text)
    text = clean_text(text)

    last_name = ""
    first_name = ""
    middle_name = ""

    m = re.match(r"^\s*([^,]+),\s*(.+)$", text)
    if m:
        last_name = clean_text(m.group(1))
        remaining = clean_text(m.group(2))
        parts = remaining.split()

        if parts:
            first_name = parts[0]

        if len(parts) > 1:
            middle_name = " ".join(parts[1:])

    return {
        "last_name": clean_text(last_name),
        "first_name": clean_text(first_name),
        "middle_name": clean_text(middle_name),
    }


def find_header_line(lines, keywords):
    for idx, line in enumerate(lines):
        t = norm(line["text"])
        if all(keyword in t for keyword in keywords):
            return idx, line
    return None, None


def extract_name_parts(lines):
    header_idx, header_line = find_header_line(lines, ["lastname", "firstname", "middlename"])

    if header_line:
        candidates = []

        for j in range(header_idx + 1, min(header_idx + 10, len(lines))):
            candidate = lines[j]

            if candidate["cy"] <= header_line["cy"]:
                continue

            if candidate["cy"] - header_line["cy"] > 150:
                break

            if looks_like_name(candidate["text"]):
                candidates.append(candidate)

        if candidates:
            candidates.sort(key=lambda item: (item["cy"], -(item["x2"] - item["x1"])))
            return split_name(candidates[0]["text"])

    for line in lines:
        if looks_like_name(line["text"]):
            return split_name(line["text"])

    return {
        "last_name": "",
        "first_name": "",
        "middle_name": "",
    }


def normalize_license_number(text: str) -> str:
    text = clean_text(text)
    text = text.replace("–", "-").replace("—", "-")
    text = re.sub(r"[^A-Za-z0-9\-]", "", text)
    text = text.upper()

    text = text.replace("RO7", "R07")
    text = text.replace("R0T", "R07")
    text = text.replace("ROT", "R07")
    text = text.replace("R7-", "R07-")

    if text.startswith("RO7-"):
        text = "R07-" + text[4:]

    if text.startswith("R07") and "-" not in text and len(text) > 3:
        text = "R07-" + text[3:]

    return clean_text(text)


def extract_license_number(lines):
    for idx, line in enumerate(lines):
        t = norm(line["text"])

        if "license id" in t or "license id nr" in t:
            same_line_match = LICENSE_REGEX.search(line["text"])
            if same_line_match:
                return normalize_license_number(same_line_match.group(0))

            for j in range(idx + 1, min(idx + 4, len(lines))):
                nxt = lines[j]["text"]
                nxt_match = LICENSE_REGEX.search(nxt)
                if nxt_match:
                    return normalize_license_number(nxt_match.group(0))

    for line in lines:
        m = LICENSE_REGEX.search(line["text"])
        if m:
            return normalize_license_number(m.group(0))

    return ""


def parse_date_to_ymd(text: str):
    text = clean_text(text)
    if not text:
        return None

    formats = [
        "%B %d, %Y",
        "%B %d %Y",
        "%b %d, %Y",
        "%b %d %Y",
        "%m/%d/%Y",
        "%m-%d-%Y",
        "%Y-%m-%d",
    ]

    for fmt in formats:
        try:
            return datetime.datetime.strptime(text, fmt).strftime("%Y-%m-%d")
        except Exception:
            pass

    return None


def extract_date_from_text(text: str):
    m = DATE_REGEX.search(text)
    if not m:
        return None
    return parse_date_to_ymd(m.group(0))


def is_expiry_label(text: str) -> bool:
    t = norm(text)
    return (
        "expiry date" in t or
        "expire date" in t or
        "exp date" in t or
        ("expiry" in t and "date" in t) or
        ("exp" in t and "date" in t)
    )


def extract_expiry_date(lines):
    for idx, line in enumerate(lines):
        if is_expiry_label(line["text"]):
            same = extract_date_from_text(line["text"])
            if same:
                return same

            for j in range(idx + 1, min(idx + 4, len(lines))):
                nxt = extract_date_from_text(lines[j]["text"])
                if nxt:
                    return nxt

    return ""


def main():
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "message": "Missing image path"}))
        sys.exit(1)

    image_path = sys.argv[1]

    try:
        project_root = Path(__file__).resolve().parents[1]
        debug_dir = str(project_root / "storage" / "app" / "ocr-debug")
        ensure_dir(debug_dir)

        if not os.path.exists(image_path):
            raise FileNotFoundError(f"Image file not found: {image_path}")

        image_bgr = cv2.imread(image_path)
        if image_bgr is None:
            raise ValueError(f"Could not read image: {image_path}")

        card = detect_and_crop_card(image_bgr)
        card_path = os.path.join(debug_dir, "card-cropped.png")
        cv2.imwrite(card_path, card)

        lines, by_variant = run_multi_variant_ocr(card)

        name_data = extract_name_parts(lines)
        license_number = extract_license_number(lines)
        expiry_iso = extract_expiry_date(lines)

        debug_payload = {
            "lines": lines,
            "variant_lines": by_variant,
            "extracted": {
                "last_name": name_data["last_name"],
                "first_name": name_data["first_name"],
                "middle_name": name_data["middle_name"],
                "license_number": license_number,
                "license_validity_date": expiry_iso,
            }
        }

        with open(os.path.join(debug_dir, "last-run.txt"), "w", encoding="utf-8") as f:
            f.write(json.dumps(debug_payload, indent=2, ensure_ascii=False))

        payload = {
            "success": True,
            "last_name": name_data["last_name"],
            "first_name": name_data["first_name"],
            "middle_name": name_data["middle_name"],
            "license_number": license_number,
            "license_validity_date": expiry_iso,
            "raw": debug_payload,
            "debug_dir": debug_dir,
        }

        print(json.dumps(payload, ensure_ascii=False))

    except FileNotFoundError as e:
        print(json.dumps({
            "success": False,
            "message": str(e)
        }))
        sys.exit(1)
    except ValueError as e:
        print(json.dumps({
            "success": False,
            "message": str(e)
        }))
        sys.exit(1)
    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"OCR processing error: {str(e)}"
        }))
        sys.exit(1)


if __name__ == "__main__":
    main()
