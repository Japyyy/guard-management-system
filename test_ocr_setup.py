#!/usr/bin/env python3
"""
Diagnostic script to test PaddleOCR setup
"""
import sys
import json
from pathlib import Path

def test_imports():
    """Test if required imports work"""
    print("Testing imports...")
    
    try:
        import cv2
        print("✓ cv2 (OpenCV) imported successfully")
    except ImportError as e:
        print(f"✗ cv2 import failed: {e}")
        return False
    
    try:
        import numpy as np
        print("✓ numpy imported successfully")
    except ImportError as e:
        print(f"✗ numpy import failed: {e}")
        return False
    
    try:
        from paddleocr import PaddleOCR
        print("✓ paddleocr imported successfully")
    except ImportError as e:
        print(f"✗ paddleocr import failed: {e}")
        return False
    
    return True

def test_paddleocr():
    """Test if PaddleOCR initializes"""
    print("\nInitializing PaddleOCR...")
    
    try:
        from paddleocr import PaddleOCR
        ocr = PaddleOCR(
            use_doc_orientation_classify=True,
            use_doc_unwarping=True,
            use_textline_orientation=True,
            lang="en",
        )
        print("✓ PaddleOCR initialized successfully")
        return True
    except Exception as e:
        print(f"✗ PaddleOCR initialization failed: {e}")
        return False

def main():
    print("=" * 60)
    print("PaddleOCR Setup Diagnostic")
    print("=" * 60)
    
    success = test_imports()
    
    if success:
        success = test_paddleocr()
    
    print("\n" + "=" * 60)
    if success:
        print("✓ All tests passed! Setup looks good.")
        result = {"success": True, "message": "All tests passed"}
    else:
        print("✗ Some tests failed. Check errors above.")
        result = {"success": False, "message": "Some tests failed"}
    
    print(json.dumps(result))
    sys.exit(0 if success else 1)

if __name__ == "__main__":
    main()
