<?php

namespace App\Http\Controllers;

use App\Services\GuardLicensePaddleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GuardLicenseOcrController extends Controller
{
    public function scan(Request $request, GuardLicensePaddleService $ocrService)
    {
        set_time_limit(120);

        try {
            $request->validate([
                'license_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp'],
            ]);

            $path = $request->file('license_image')->store('temp', 'local');
            $fullPath = Storage::disk('local')->path($path);

            if (! file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded image file was not found.',
                    'data' => [],
                ], 422);
            }

            $result = $ocrService->scan($fullPath);

            $data = [
                'last_name' => $result['last_name'] ?? '',
                'first_name' => $result['first_name'] ?? '',
                'middle_name' => $result['middle_name'] ?? '',
                'license_number' => $result['license_number'] ?? '',
                'license_validity_date' => $result['license_validity_date'] ?? '',
                'raw' => $result['raw'] ?? [],
            ];

            $filledCount = collect($data)
                ->except('raw')
                ->filter(fn ($value) => filled($value))
                ->count();

            return response()->json([
                'success' => true,
                'message' => $filledCount > 0
                    ? 'OCR scan completed.'
                    : 'OCR completed but no usable values were extracted.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('Guard OCR scan failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        } finally {
            if (isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
        }
    }
}
