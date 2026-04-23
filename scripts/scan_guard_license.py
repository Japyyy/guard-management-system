import json
import os
import re
import sys
import warnings
from datetime import datetime

import cv2
import numpy as np
from paddleocr import PaddleOCR


LABEL_NAME = "LASTNAMEFIRSTNAMEMIDDLENAMEQUALIFIER"
LABEL_LICENSE = "LICENSEIDNR"
LABEL_EXPIRY = "EXPIRYDATE"
LABEL_ADDRESS = "COMPLETEADDRESS"
STOP_LABELS = {
    LABEL_NAME,
    LABEL_LICENSE,
    LABEL_EXPIRY,
    LABEL_ADDRESS,
}
QUALIFIERS = {"JR", "SR", "II", "III", "IV", "V"}
OCR_INSTANCE = None
MIN_CARD_ASPECT_RATIO = 1.2
MAX_CARD_ASPECT_RATIO = 2.4
MIN_CARD_AREA_RATIO = 0.2
MAX_CARD_AREA_RATIO = 0.98
MAX_IMAGE_DIMENSION = 1600
DEBUG_DIR = os.environ.get("GUARD_OCR_DEBUG_DIR", "").strip()
NAME_NOISE_PARTS = (
    "INVEST",
    "VESTIG",
    "OFFICE",
    "PROFESSION",
    "SECURITY",
    "LICENSE",
    "CATEGORY",
    "PHILIPPINE",
    "REPUBLIC",
    "POLICE",
    "ADDRESS",
    "PRINTED",
    "ISSUED",
    "EXPIRY",
)
NAME_STOPWORDS = {
    "EFICE",
    "OFFICE",
    "FFICE",
    "FOR",
    "O",
    "OF",
    "SI",
    "AND",
    "THE",
    "FO",
    "D",
    "W",
}
ADDRESS_STOP_MARKERS = (
    "REPUBLIC OF THE PHILIPPINES",
    "LICENSE TO EXERCISE",
    "PHILIPPINE NATIONAL POLICE",
    "CATEGORY",
    "DATE PRINTED",
)

os.environ.setdefault("GLOG_minloglevel", "3")
os.environ.setdefault("FLAGS_logtostderr", "0")
warnings.filterwarnings("ignore")


def json_output(payload, exit_code=0):
    sys.stdout.write(json.dumps(payload, ensure_ascii=True))
    sys.exit(exit_code)


def debug_enabled():
    return bool(DEBUG_DIR)


def ensure_debug_dir():
    if debug_enabled():
        os.makedirs(DEBUG_DIR, exist_ok=True)


def write_debug_image(filename, image):
    if not debug_enabled() or image is None:
        return

    ensure_debug_dir()
    cv2.imwrite(os.path.join(DEBUG_DIR, filename), image)


def write_debug_report(payload):
    if not debug_enabled():
        return

    ensure_debug_dir()
    with open(os.path.join(DEBUG_DIR, "last-run.txt"), "w", encoding="utf-8") as handle:
        json.dump(payload, handle, indent=2, ensure_ascii=True)


def normalize_text(value):
    return re.sub(r"[^A-Z0-9]+", "", value.upper())


def clean_inline_value(line_text, label):
    pattern = re.compile(re.escape(label), re.IGNORECASE)
    cleaned = pattern.sub("", line_text, count=1)
    cleaned = re.sub(r"^[\s:;.\-]+", "", cleaned)
    return cleaned.strip()


def order_points(points):
    rect = np.zeros((4, 2), dtype="float32")
    s = points.sum(axis=1)
    diff = np.diff(points, axis=1)
    rect[0] = points[np.argmin(s)]
    rect[2] = points[np.argmax(s)]
    rect[1] = points[np.argmin(diff)]
    rect[3] = points[np.argmax(diff)]
    return rect


def polygon_area(points):
    reshaped = points.reshape((-1, 1, 2)).astype(np.float32)
    return abs(cv2.contourArea(reshaped))


def is_plausible_card(points, image_shape):
    image_height, image_width = image_shape[:2]
    image_area = float(image_height * image_width)

    if image_area <= 0:
        return False

    rect = order_points(points.astype("float32"))
    width_a = np.linalg.norm(rect[2] - rect[3])
    width_b = np.linalg.norm(rect[1] - rect[0])
    height_a = np.linalg.norm(rect[1] - rect[2])
    height_b = np.linalg.norm(rect[0] - rect[3])
    max_width = max(width_a, width_b)
    max_height = max(height_a, height_b)

    if max_width <= 0 or max_height <= 0:
        return False

    aspect_ratio = max_width / max_height
    area_ratio = polygon_area(points) / image_area

    return (
        MIN_CARD_ASPECT_RATIO <= aspect_ratio <= MAX_CARD_ASPECT_RATIO
        and MIN_CARD_AREA_RATIO <= area_ratio <= MAX_CARD_AREA_RATIO
    )


def detect_card(image):
    ratio = image.shape[0] / 1000.0
    resized = cv2.resize(image, (int(image.shape[1] / ratio), 1000))
    gray = cv2.cvtColor(resized, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edged = cv2.Canny(blurred, 50, 150)
    contours, _ = cv2.findContours(edged, cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)[:10]

    for contour in contours:
        perimeter = cv2.arcLength(contour, True)
        approximation = cv2.approxPolyDP(contour, 0.02 * perimeter, True)

        if len(approximation) == 4:
            points = approximation.reshape(4, 2) * ratio
            if not is_plausible_card(points, image.shape):
                continue
            return four_point_transform(image, points)

    return image


def four_point_transform(image, points):
    rect = order_points(points.astype("float32"))
    (top_left, top_right, bottom_right, bottom_left) = rect

    width_a = np.linalg.norm(bottom_right - bottom_left)
    width_b = np.linalg.norm(top_right - top_left)
    max_width = max(int(width_a), int(width_b))

    height_a = np.linalg.norm(top_right - bottom_right)
    height_b = np.linalg.norm(top_left - bottom_left)
    max_height = max(int(height_a), int(height_b))

    if max_width <= 0 or max_height <= 0:
        return image

    destination = np.array(
        [
            [0, 0],
            [max_width - 1, 0],
            [max_width - 1, max_height - 1],
            [0, max_height - 1],
        ],
        dtype="float32",
    )

    matrix = cv2.getPerspectiveTransform(rect, destination)
    return cv2.warpPerspective(image, matrix, (max_width, max_height))


def preprocess_single_image(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    clahe = cv2.createCLAHE(clipLimit=2.5, tileGridSize=(8, 8))
    enhanced = clahe.apply(gray)
    threshold = cv2.threshold(
        enhanced,
        0,
        255,
        cv2.THRESH_BINARY + cv2.THRESH_OTSU,
    )[1]
    adaptive = cv2.adaptiveThreshold(
        enhanced,
        255,
        cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY,
        31,
        15,
    )
    denoised = cv2.fastNlMeansDenoising(enhanced, None, 10, 7, 21)
    return image, gray, enhanced, threshold, adaptive, denoised


def resize_image(image, max_dimension=MAX_IMAGE_DIMENSION):
    height, width = image.shape[:2]
    longest_side = max(height, width)

    if longest_side <= max_dimension:
        return image

    scale = max_dimension / float(longest_side)
    resized_width = max(1, int(width * scale))
    resized_height = max(1, int(height * scale))
    return cv2.resize(image, (resized_width, resized_height), interpolation=cv2.INTER_AREA)


def preprocess_image(image):
    image = resize_image(image)
    variants = list(preprocess_single_image(image))
    detected = detect_card(image)

    if detected.shape != image.shape or not np.array_equal(detected, image):
        variants.extend(preprocess_single_image(detected))

    # Keep the OCR passes limited so Windows requests do not time out while still
    # preserving a few high-signal variants for difficult photos.
    selected_indexes = [2, 3]

    if len(variants) > 6:
        selected_indexes.append(8)

    return [variants[index] for index in selected_indexes if index < len(variants)]


def get_ocr():
    global OCR_INSTANCE

    if OCR_INSTANCE is None:
        OCR_INSTANCE = PaddleOCR(
            lang="en",
            use_doc_orientation_classify=False,
            use_doc_unwarping=False,
            use_textline_orientation=False,
            text_det_limit_side_len=960,
            text_det_limit_type="max",
            text_rec_score_thresh=0.25,
        )

    return OCR_INSTANCE


def image_to_lines(image):
    if len(image.shape) == 2:
        image = cv2.cvtColor(image, cv2.COLOR_GRAY2BGR)
    elif len(image.shape) == 3 and image.shape[2] == 4:
        image = cv2.cvtColor(image, cv2.COLOR_BGRA2BGR)

    ocr = get_ocr()
    result = ocr.predict(image)
    entries = []

    if not result:
        return entries

    for page in result:
        if not page:
            continue

        page_data = page if isinstance(page, dict) else dict(page)
        texts = page_data.get("rec_texts", [])
        polys = page_data.get("rec_polys", [])
        scores = page_data.get("rec_scores", [])

        for index, text in enumerate(texts):
            text = str(text).strip()
            score = float(scores[index]) if index < len(scores) else 0.0
            if index >= len(polys):
                continue

            if not text:
                continue

            points = np.array(polys[index], dtype=np.float32)
            x_values = points[:, 0]
            y_values = points[:, 1]

            entries.append(
                {
                    "text": text,
                    "score": score,
                    "x": float(np.mean(x_values)),
                    "y": float(np.mean(y_values)),
                    "top": float(np.min(y_values)),
                    "bottom": float(np.max(y_values)),
                }
            )

    if not entries:
        return []

    entries.sort(key=lambda item: (item["y"], item["x"]))
    average_height = np.mean([item["bottom"] - item["top"] for item in entries]) if entries else 20
    tolerance = max(18.0, average_height * 0.75)

    rows = []
    for entry in entries:
        matched = None
        for row in rows:
            if abs(row["y"] - entry["y"]) <= tolerance:
                matched = row
                break

        if matched is None:
            rows.append({"y": entry["y"], "items": [entry]})
            continue

        matched["items"].append(entry)
        matched["y"] = float(np.mean([item["y"] for item in matched["items"]]))

    lines = []
    for row in rows:
        row["items"].sort(key=lambda item: item["x"])
        combined = " ".join(item["text"] for item in row["items"]).strip()
        if combined:
            lines.append(combined)

    return lines


def deduplicate_lines(lines):
    seen = set()
    unique = []

    for line in lines:
        cleaned = re.sub(r"\s+", " ", line).strip()
        normalized = normalize_text(cleaned)

        if not cleaned or normalized in seen:
            continue

        seen.add(normalized)
        unique.append(cleaned)

    return unique


def next_non_empty(lines, start_index):
    for index in range(start_index, len(lines)):
        candidate = lines[index].strip()
        if candidate:
            return candidate
    return ""


def collect_following_lines(lines, start_index, max_lines=3):
    collected = []
    for index in range(start_index, len(lines)):
        candidate = lines[index].strip()
        if not candidate:
            continue
        candidate_normalized = normalize_text(candidate)
        if candidate_normalized in STOP_LABELS:
            break
        if any(marker in candidate.upper() for marker in ADDRESS_STOP_MARKERS):
            break
        collected.append(candidate)
        if len(collected) >= max_lines:
            break
    return collected


def extract_value_for_label(lines, label_text, value_type):
    normalized_label = normalize_text(label_text)

    for index, line in enumerate(lines):
        normalized_line = normalize_text(line)
        if normalized_label not in normalized_line:
            continue

        inline_value = clean_inline_value(line, label_text)

        if value_type == "license":
            value = extract_license_number(inline_value)
            if value:
                return value
            return extract_license_number(next_non_empty(lines, index + 1))

        if value_type == "date":
            value = normalize_date_string(inline_value)
            if value:
                return value
            return normalize_date_string(next_non_empty(lines, index + 1))

        if value_type == "address":
            values = []
            if inline_value:
                values.append(inline_value)
            values.extend(collect_following_lines(lines, index + 1, max_lines=3))
            return clean_address(" ".join(values))

        if value_type == "name":
            name_line = next_non_empty(lines, index + 1)
            return parse_name(name_line)

    if value_type == "name":
        return fallback_name(lines)

    if value_type == "license":
        return fallback_license(lines)

    if value_type == "date":
        return fallback_date(lines)

    if value_type == "address":
        return fallback_address(lines)

    return ""


def extract_license_number(value):
    if not value:
        return ""

    cleaned = re.sub(r"[^A-Z0-9\-]+", " ", value.upper()).strip()
    match = re.search(r"\b[A-Z0-9][A-Z0-9\-]{4,}\b", cleaned)
    return match.group(0) if match else ""


def normalize_date_string(value):
    if not value:
        return ""

    cleaned = re.sub(r"[\|]", " ", value).strip()
    cleaned = re.sub(r"\s+", " ", cleaned)

    direct_formats = [
        "%B %d, %Y",
        "%b %d, %Y",
        "%B %d %Y",
        "%b %d %Y",
        "%m/%d/%Y",
        "%m-%d-%Y",
        "%Y-%m-%d",
    ]

    for fmt in direct_formats:
        try:
            return datetime.strptime(cleaned, fmt).strftime("%Y-%m-%d")
        except ValueError:
            continue

    match = re.search(
        r"((January|February|March|April|May|June|July|August|September|October|November|December|"
        r"Jan|Feb|Mar|Apr|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec)\s+\d{1,2},?\s+\d{4})",
        cleaned,
        re.IGNORECASE,
    )
    if match:
        matched = match.group(1).replace("Sept", "Sep")
        for fmt in ("%B %d, %Y", "%b %d, %Y", "%B %d %Y", "%b %d %Y"):
            try:
                return datetime.strptime(matched, fmt).strftime("%Y-%m-%d")
            except ValueError:
                continue

    digit_corrected = re.sub(r"(?<=\d)[Oo]|[Oo](?=\d)", "0", cleaned)
    numeric = re.search(r"(\d{1,2}[/-]\d{1,2}[/-]\d{4})", digit_corrected)
    if numeric:
        for fmt in ("%m/%d/%Y", "%m-%d-%Y"):
            try:
                return datetime.strptime(numeric.group(1), fmt).strftime("%Y-%m-%d")
            except ValueError:
                continue

    return ""


def clean_address(value):
    if not value:
        return ""

    cleaned = re.sub(r"\s+", " ", value).strip(" ,.-")
    cleaned = re.sub(r"^[Cc]omplete\s+[Aa]ddress[:\s-]*", "", cleaned)
    return cleaned.strip()


def sanitize_name_part(value):
    if not value:
        return ""

    tokens = [token for token in value.split() if token]

    while tokens and any(part in tokens[0] for part in NAME_NOISE_PARTS):
        tokens.pop(0)

    while tokens and any(part in tokens[-1] for part in NAME_NOISE_PARTS):
        tokens.pop()

    filtered_tokens = [
        token
        for token in tokens
        if token not in NAME_STOPWORDS and not any(part in token for part in NAME_NOISE_PARTS)
    ]

    if filtered_tokens:
        tokens = filtered_tokens

    if len(tokens) > 1:
        tokens = tokens[-1:]

    return " ".join(tokens).strip()


def parse_name(value):
    payload = {
        "last_name": "",
        "first_name": "",
        "middle_name": "",
    }

    if not value:
        return payload

    cleaned = re.sub(r"[^A-Z,\s\-'.]", " ", value.upper())
    cleaned = re.sub(r"\s+", " ", cleaned).strip(" ,")
    cleaned = re.sub(r"\b(SECURITY|GUARD|LICENSE)\b", "", cleaned, flags=re.IGNORECASE).strip(" ,")

    trailing_name_match = re.search(r"([A-Z][A-Z\s\-']*,\s*[A-Z][A-Z\s\-']+)$", cleaned)
    if trailing_name_match:
        cleaned = trailing_name_match.group(1).strip(" ,")

    qualifier = ""
    tokens = cleaned.split()
    if tokens and tokens[-1].upper().rstrip(".") in QUALIFIERS:
        qualifier = tokens.pop(-1)
        cleaned = " ".join(tokens)

    if "," in cleaned:
        last_name, remainder = [part.strip() for part in cleaned.split(",", 1)]
        last_name = sanitize_name_part(last_name)
        remainder_tokens = remainder.split()
    else:
        parts = cleaned.split()
        if not parts:
            return payload
        last_name = parts[0]
        remainder_tokens = parts[1:]

    if qualifier:
        last_name = f"{last_name} {qualifier}".strip()

    first_name = remainder_tokens[0] if remainder_tokens else ""
    middle_name = " ".join(remainder_tokens[1:]) if len(remainder_tokens) > 1 else ""

    payload["last_name"] = last_name.upper()
    payload["first_name"] = first_name.upper()
    payload["middle_name"] = middle_name.upper()
    return payload


def fallback_name(lines):
    pattern = re.compile(r"^[A-Z][A-Z\s\-']+,\s*[A-Z][A-Z\s\-']+$")
    for line in lines:
        if pattern.match(line.upper()):
            return parse_name(line)
    return {
        "last_name": "",
        "first_name": "",
        "middle_name": "",
    }


def fallback_license(lines):
    for line in lines:
        value = extract_license_number(line)
        if value:
            return value
    return ""


def fallback_date(lines):
    for line in lines:
        value = normalize_date_string(line)
        if value:
            return value
    return ""


def fallback_address(lines):
    for index, line in enumerate(lines):
        normalized = normalize_text(line)
        if "ADDRESS" in normalized:
            values = [clean_inline_value(line, "Complete Address")]
            values.extend(collect_following_lines(lines, index + 1, max_lines=3))
            return clean_address(" ".join(values))
    return ""


def extract_fields(lines):
    name_payload = extract_value_for_label(lines, "LASTNAME FIRSTNAME MIDDLENAME QUALIFIER", "name")
    license_number = extract_value_for_label(lines, "LICENSE ID NR", "license")
    license_validity_date = extract_value_for_label(lines, "Expiry Date", "date")

    return {
        "last_name": name_payload["last_name"],
        "first_name": name_payload["first_name"],
        "middle_name": name_payload["middle_name"],
        "license_number": license_number,
        "license_validity_date": license_validity_date,
    }


def debug_variant_map(variants):
    labels = [
        "original-or-cropped.png",
        "gray.png",
        "enhanced.png",
        "threshold.png",
        "adaptive.png",
        "denoised.png",
        "detected-card.png",
        "detected-gray.png",
        "detected-enhanced.png",
        "detected-threshold.png",
        "detected-adaptive.png",
        "detected-denoised.png",
    ]

    return {
        labels[index]: variant
        for index, variant in enumerate(variants)
        if index < len(labels)
    }


def main():
    if len(sys.argv) != 2:
        json_output({"error": "Unable to extract license details: missing image path."}, 1)

    image_path = sys.argv[1]
    if not os.path.isfile(image_path):
        json_output({"error": "Unable to extract license details: image file was not found."}, 1)

    image = cv2.imread(image_path)
    if image is None:
        json_output({"error": "Unable to extract license details: uploaded image could not be read."}, 1)

    write_debug_image("uploaded-image.png", image)
    variants = preprocess_image(image)
    for filename, variant in debug_variant_map(variants).items():
        write_debug_image(filename, variant)

    all_lines = []

    for variant in variants:
        all_lines.extend(image_to_lines(variant))

    lines = deduplicate_lines(all_lines)
    payload = extract_fields(lines)
    debug_payload = {
        "image_path": image_path,
        "lines": lines,
        "extracted": payload,
    }

    if not any(payload.values()):
        if lines:
            debug_payload["error"] = "Unable to extract license details: OCR found text, but no license fields matched the expected format."
            write_debug_report(debug_payload)
            json_output({"error": "Unable to extract license details: OCR found text, but no license fields matched the expected format."}, 1)
        debug_payload["error"] = "Unable to extract license details: no readable text was detected from the image."
        write_debug_report(debug_payload)
        json_output({"error": "Unable to extract license details: no readable text was detected from the image."}, 1)

    write_debug_report(debug_payload)
    json_output(payload)


if __name__ == "__main__":
    try:
        main()
    except Exception as exception:
        message = str(exception).strip() or "unexpected OCR failure."
        write_debug_report({
            "error": f"Unable to extract license details: {message}",
        })
        json_output({"error": f"Unable to extract license details: {message}"}, 1)
