<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class GuardLicensePaddleService
{
    public function scan(string $imagePath): array
    {
        $pythonExe = env('PYTHON_EXE', 'python');
        $scriptEnv = env('PADDLE_OCR_SCRIPT', 'python_ocr/ocr_guard_license_fast.py');

        $scriptPath = str_contains($scriptEnv, ':') || str_starts_with($scriptEnv, '/') || str_starts_with($scriptEnv, '\\')
            ? $scriptEnv
            : base_path($scriptEnv);

        if (! file_exists($pythonExe)) {
            throw new \RuntimeException('Python executable not found: ' . $pythonExe);
        }

        if (! file_exists($scriptPath)) {
            throw new \RuntimeException('PaddleOCR script not found: ' . $scriptPath);
        }

        if (! file_exists($imagePath)) {
            throw new \RuntimeException('Image file not found: ' . $imagePath);
        }

        $process = new Process([
            $pythonExe,
            $scriptPath,
            $imagePath,
        ]);

        // Set longer timeout for PaddleOCR (first run can take 30-60 seconds)
        $process->setTimeout(360);
        $process->setIdleTimeout(360);
        
        Log::info('Starting OCR process', [
            'python_exe' => $pythonExe,
            'script_path' => $scriptPath,
            'image_path' => $imagePath,
        ]);

        $process->run();

        $errorOutput = trim($process->getErrorOutput());
        $output = trim($process->getOutput());

        if (! $process->isSuccessful()) {
            $errorMsg = $errorOutput ?: $output;
            
            if (empty($errorMsg)) {
                $errorMsg = 'Python process exited with code ' . $process->getExitCode();
            }

            Log::error('Guard OCR Python process failed', [
                'exit_code' => $process->getExitCode(),
                'error_output' => $errorOutput,
                'output' => substr($output, 0, 500),
                'command' => $process->getCommandLine(),
            ]);

            throw new \RuntimeException('Python OCR failed: ' . $errorMsg);
        }

        if (empty($output)) {
            Log::error('Guard OCR Python produced no output', [
                'error_output' => $errorOutput,
                'command' => $process->getCommandLine(),
            ]);
            throw new \RuntimeException('OCR script produced no output. Check logs for details.');
        }

        $decoded = json_decode($output, true);

        if (! is_array($decoded)) {
            Log::error('Guard OCR invalid JSON output', [
                'output' => substr($output, 0, 500),
                'json_error' => json_last_error_msg(),
            ]);
            throw new \RuntimeException('Invalid OCR output format. Check storage/logs/laravel.log');
        }

        if (($decoded['success'] ?? false) !== true) {
            throw new \RuntimeException($decoded['message'] ?? 'OCR processing failed.');
        }

        Log::info('Guard OCR completed successfully', [
            'extracted_fields' => [
                'last_name' => $decoded['last_name'] ?? '',
                'first_name' => $decoded['first_name'] ?? '',
                'license_number' => $decoded['license_number'] ?? '',
            ]
        ]);

        return $decoded;
    }
}