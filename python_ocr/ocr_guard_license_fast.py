#!/usr/bin/env python3
"""
Simplified, fast OCR guard license handler focusing on text extraction
"""
import sys
import os
import json
import re
import datetime
from pathlib import Path

import cv2
import numpy as np
from paddleocr import PaddleOCR

# Initialize OCR with caching enabled (faster on subsequent runs)
print("INFO: Initializing PaddleOCR...", file=sys.stderr, flush=True)
OCR = PaddleOCR(
    use_doc_orientation_classify=False,  # Disable to speed up
    use_doc_unwarping=False,  # Disable to speed up
    use_textline_orientation=False,  # Disable to speed up
    lang="en",
)
print("INFO: PaddleOCR initialized", file=sys.stderr, flush=True)

MONTH_PATTERN = r"(January|February|March|April|May|June|July|August|September|October|November|December)"
DATE_REGEX = re.compile(rf"{MONTH_PATTERN}\s+\d{{1,2}},\s+\d{{4}}", re.I)
LICENSE_REGEX = re.compile(r"R[0O]?7[-–—]?\d{8,14}", re.I)


def clean_text(value: str) -> str:
    """Clean whitespace from text"""
    return re.sub(r"\s+", " ", (value or "").strip()).strip()


def norm(value: str) -> str:
    """Normalize to lowercase"""
    return clean_text(value).lower()


def normalize_for_compare(value: str) -> str:
    """Remove all non-alphanumeric for comparison"""
    return re.sub(r"[^a-z0-9]", "", norm(value))


def is_name_like(text: str) -> bool:
    """Check if text looks like a name (has comma and is not a label)"""
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


def split_name(text: str) -> dict:
    """Parse name from 'LASTNAME, FIRSTNAME MIDDLENAME' format"""
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


def normalize_license_number(text: str) -> str:
    """Normalize license number format"""
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


def parse_date_to_ymd(text: str):
    """Convert date string to YYYY-MM-DD format"""
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
    """Find and parse date from text"""
    m = DATE_REGEX.search(text)
    if not m:
        return None
    return parse_date_to_ymd(m.group(0))


def is_expiry_label(text: str) -> bool:
    """Check if text is an expiry date label"""
    t = norm(text)
    return (
        "expiry date" in t or
        "expire date" in t or
        "exp date" in t or
        ("expiry" in t and "date" in t) or
        ("exp" in t and "date" in t)
    )


def run_ocr_on_image(image_path: str) -> list:
    """Run OCR on image and return extracted text with positions"""
    print(f"INFO: Running OCR on {image_path}", file=sys.stderr, flush=True)
    
    # PaddleOCR.ocr() returns a list of pages, each page is a list of (bbox, (text, confidence)) tuples
    results = OCR.ocr(image_path)
    
    lines = []
    for result_page in results:
        if not result_page:
            continue
        for line_item in result_page:
            try:
                bbox, (text, confidence) = line_item
            except (ValueError, TypeError):
                continue
            
            if not isinstance(text, str) or not text.strip():
                continue
            
            text = clean_text(text)
            if not text:
                continue
            
            try:
                xs = [p[0] for p in bbox]
                ys = [p[1] for p in bbox]
                cx = sum(xs) / len(xs) if xs else 0
                cy = sum(ys) / len(ys) if ys else 0
                x1 = min(xs) if xs else 0
                y1 = min(ys) if ys else 0
                x2 = max(xs) if xs else 0
                y2 = max(ys) if ys else 0
            except Exception:
                cx = cy = x1 = y1 = x2 = y2 = 0
            
            lines.append({
                "text": text,
                "confidence": float(confidence) if isinstance(confidence, (int, float)) else 0.0,
                "cx": float(cx),
                "cy": float(cy),
                "x1": float(x1),
                "y1": float(y1),
                "x2": float(x2),
                "y2": float(y2),
            })
    
    lines.sort(key=lambda item: (item["cy"], item["cx"]))
    print(f"INFO: Extracted {len(lines)} text lines", file=sys.stderr, flush=True)
    return lines


def extract_name_parts(lines: list) -> dict:
    """Extract name from OCR results"""
    for line in lines:
        if is_name_like(line["text"]):
            return split_name(line["text"])
    
    return {
        "last_name": "",
        "first_name": "",
        "middle_name": "",
    }


def extract_license_number(lines: list) -> str:
    """Extract license number from OCR results"""
    for line in lines:
        m = LICENSE_REGEX.search(line["text"])
        if m:
            return normalize_license_number(m.group(0))
    
    return ""


def extract_expiry_date(lines: list) -> str:
    """Extract expiry date from OCR results"""
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
    """Main entry point"""
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "message": "Missing image path"}))
        return False

    image_path = sys.argv[1]

    try:
        print(f"INFO: Processing image: {image_path}", file=sys.stderr, flush=True)
        
        if not os.path.exists(image_path):
            raise FileNotFoundError(f"Image file not found: {image_path}")

        # Create debug directory
        project_root = Path(__file__).resolve().parents[1]
        debug_dir = str(project_root / "storage" / "app" / "ocr-debug")
        os.makedirs(debug_dir, exist_ok=True)

        # Run OCR
        lines = run_ocr_on_image(image_path)

        # Extract data
        name_data = extract_name_parts(lines)
        license_number = extract_license_number(lines)
        expiry_iso = extract_expiry_date(lines)

        print(f"INFO: Extraction complete", file=sys.stderr, flush=True)

        # Save debug info
        debug_payload = {
            "lines": lines,
            "extracted": {
                "last_name": name_data["last_name"],
                "first_name": name_data["first_name"],
                "middle_name": name_data["middle_name"],
                "license_number": license_number,
                "license_validity_date": expiry_iso,
            }
        }

        debug_file = os.path.join(debug_dir, "last-run.txt")
        with open(debug_file, "w", encoding="utf-8") as f:
            f.write(json.dumps(debug_payload, indent=2, ensure_ascii=False))

        # Return result
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
        return True

    except FileNotFoundError as e:
        print(json.dumps({"success": False, "message": str(e)}))
        return False
    except Exception as e:
        import traceback
        traceback.print_exc(file=sys.stderr)
        print(json.dumps({
            "success": False,
            "message": f"OCR processing failed: {str(e)}"
        }))
        return False


if __name__ == "__main__":
    try:
        success = main()
        sys.exit(0 if success else 1)
    except Exception as e:
        import traceback
        print(f"FATAL: {e}", file=sys.stderr, flush=True)
        traceback.print_exc(file=sys.stderr)
        print(json.dumps({
            "success": False,
            "message": f"Fatal error: {str(e)}"
        }))
        sys.exit(1)
