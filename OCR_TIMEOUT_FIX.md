# OCR Timeout Fix Guide

## Problem: 504 Gateway Timeout

Your OCR request is getting a 504 Gateway Timeout error, which means the PHP/web server is timing out while waiting for the Python OCR process to complete.

**Root Cause**: PaddleOCR is a heavy ML library that takes 30-60 seconds on first run to initialize and load models. Nginx default timeout is 30 seconds.

## Solution

### Step 1: Increase PHP Timeout

If using **PHP-FPM**, modify `/etc/php/*/fpm/pool.d/www.conf` or your PHP config:

```ini
request_terminate_timeout = 300
max_execution_time = 300
max_input_time = 300
```

### Step 2: Increase Nginx Timeout

If using **Nginx**, modify `/etc/nginx/nginx.conf` or your site config:

```nginx
upstream fastcgi_backend {
    server 127.0.0.1:9000;
    keepalive 30;
}

server {
    ...
    
    location ~ \.php$ {
        fastcgi_pass fastcgi_backend;
        fastcgi_connect_timeout 300s;
        fastcgi_send_timeout 300s;
        fastcgi_read_timeout 300s;
        proxy_connect_timeout 300s;
        proxy_send_timeout 300s;
        proxy_read_timeout 300s;
    }
}
```

Then restart Nginx:
```bash
sudo systemctl restart nginx
```

### Step 3: Increase Laravel's PHP Timeout

The app already sets timeouts in the controller:
- `set_time_limit(120)` - PHP script timeout
- `$process->setTimeout(360)` - Python process timeout (6 minutes)

### Step 4: Test the Setup

Run the diagnostic test to see how long OCR takes:

```bash
# With a real license image
.venv-paddle\Scripts\python.exe python_ocr\test_ocr_direct.py <path_to_image>

# Example with the test image from debug output
.venv-paddle\Scripts\python.exe python_ocr\test_ocr_direct.py "storage/app/ocr-debug/card-cropped.png"
```

This will show detailed timing:
- Import time
- Image load time  
- PaddleOCR initialization time
- OCR prediction time

## Performance Notes

**First Run**: Will be slow (30-60 seconds) as PaddleOCR downloads and caches ML models to `C:\Users\<YourUser>\.paddlex\`

**Subsequent Runs**: Much faster (5-15 seconds) since models are cached locally

**Current Script**: Using `ocr_guard_license_fast.py` which:
- Disables unnecessary document classification
- Focuses pure on text extraction
- Should run in 10-20 seconds on average

## If Still Timing Out

1. Check browser console for error details
2. Check `storage/logs/laravel.log` for PHP/Laravel errors
3. Check system logs: `journalctl -u php-fpm.service` (Linux) or Event Viewer (Windows)
4. Try running the Python script directly from terminal to see if it completes
5. Verify disk space (PaddleOCR models need space to download)

## Fallback: Use System Python

If the virtual environment is slow, you can use system Python:

```bash
# Check if system Python has the right libraries
python -m pip list | findstr paddle

# Update .env
PYTHON_EXE=python
PADDLE_OCR_SCRIPT=python_ocr/ocr_guard_license_fast.py
```

## Next Steps

1. Apply the timeout changes above for your server setup
2. Restart your web server
3. Try uploading a license image again
4. Report timing info if it still fails
