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

            $uploadedFile = $request->file('license_image');
            $path = $uploadedFile->store('temp', 'local');
            $fullPath = Storage::disk('local')->path($path);

            Log::info('License OCR scan started', [
                'file_name' => $uploadedFile->getClientOriginalName(),
                'file_size' => $uploadedFile->getSize(),
                'stored_path' => $path,
                'full_path' => $fullPath,
            ]);

            if (! file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded image file was not saved correctly.',
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

            Log::info('License OCR scan completed successfully', [
                'fields_filled' => $filledCount,
                'extracted_data' => [
                    'last_name' => $data['last_name'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'license_number' => $data['license_number'],
                    'license_validity_date' => $data['license_validity_date'],
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => $filledCount > 0
                    ? 'OCR scan completed.'
                    : 'OCR completed but no usable values were extracted.',
                'data' => $data,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image file. Please upload a valid JPG, PNG, or WebP image.',
                'data' => [],
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Guard OCR scan failed', [
                'message' => $e->getMessage(),
                'exception_class' => get_class($e),
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
