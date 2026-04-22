<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class GuardLicensePaddleService
{
    public function scan(string $imagePath): array
    {
        $pythonExe = env('PYTHON_EXE', 'python');
        $scriptEnv = env('PADDLE_OCR_SCRIPT', 'python_ocr/ocr_guard_license.py');

        $scriptPath = str_contains($scriptEnv, ':') || str_starts_with($scriptEnv, '/') || str_starts_with($scriptEnv, '\\')
            ? $scriptEnv
            : base_path($scriptEnv);

        if (! file_exists($scriptPath)) {
            throw new \RuntimeException('PaddleOCR script not found: ' . $scriptPath);
        }

        $process = new Process([
            $pythonExe,
            $scriptPath,
            $imagePath,
        ]);

        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                'Python OCR failed: ' . trim($process->getErrorOutput() ?: $process->getOutput())
            );
        }

        $output = trim($process->getOutput());
        $decoded = json_decode($output, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid OCR JSON output: ' . $output);
        }

        if (($decoded['success'] ?? false) !== true) {
            throw new \RuntimeException($decoded['message'] ?? 'OCR failed.');
        }

        return $decoded;
    }
}