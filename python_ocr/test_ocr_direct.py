#!/usr/bin/env python3
"""
Test the OCR script directly with timing information
"""
import sys
import json
import time
from pathlib import Path

# Add parent directory to path so we can import the OCR module
sys.path.insert(0, str(Path(__file__).parent))

def test_ocr_with_timing():
    """Test the OCR script with detailed timing"""
    if len(sys.argv) < 2:
        print(json.dumps({
            "success": False,
            "message": "Usage: test_ocr_direct.py <image_path>"
        }))
        return False
    
    image_path = sys.argv[1]
    
    try:
        print(f"[TEST] Starting OCR test at {time.time()}")
        print(f"[TEST] Image path: {image_path}")
        
        # Test imports
        print("[TEST] Importing libraries...")
        start = time.time()
        
        import cv2
        import numpy as np
        from paddleocr import PaddleOCR
        
        import_time = time.time() - start
        print(f"[TEST] Imports took {import_time:.2f} seconds")
        
        # Load image
        print("[TEST] Loading image...")
        start = time.time()
        image_bgr = cv2.imread(image_path)
        if image_bgr is None:
            raise ValueError(f"Could not read image: {image_path}")
        
        load_time = time.time() - start
        print(f"[TEST] Image load took {load_time:.2f} seconds")
        print(f"[TEST] Image shape: {image_bgr.shape}")
        
        # Initialize OCR (this is often where it hangs)
        print("[TEST] Initializing PaddleOCR (this may take time)...")
        start = time.time()
        
        ocr = PaddleOCR(
            use_doc_orientation_classify=True,
            use_doc_unwarping=True,
            use_textline_orientation=True,
            lang="en",
        )
        
        init_time = time.time() - start
        print(f"[TEST] PaddleOCR init took {init_time:.2f} seconds")
        
        # Run OCR
        print("[TEST] Running OCR prediction...")
        start = time.time()
        
        results = ocr.ocr(image_path)
        
        ocr_time = time.time() - start
        print(f"[TEST] OCR prediction took {ocr_time:.2f} seconds")
        print(f"[TEST] OCR results: {len(results)} pages detected")
        
        total_lines = sum(len(page) if page else 0 for page in results)
        print(f"[TEST] Total lines: {total_lines}")
        
        total = time.time() - start
        print(f"[TEST] Total OCR time: {total:.2f} seconds")
        
        # Return timing info
        result = {
            "success": True,
            "message": "OCR test completed successfully",
            "timing": {
                "imports": import_time,
                "image_load": load_time,
                "ocr_init": init_time,
                "ocr_predict": ocr_time,
            },
            "image_info": {
                "shape": str(image_bgr.shape),
                "total_lines": total_lines
            }
        }
        
        print(json.dumps(result))
        return True
        
    except Exception as e:
        print(f"[TEST] ERROR: {e}", file=sys.stderr)
        result = {
            "success": False,
            "message": str(e),
            "error_type": type(e).__name__
        }
        print(json.dumps(result))
        return False

if __name__ == "__main__":
    success = test_ocr_with_timing()
    sys.exit(0 if success else 1)
