<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\Process\Process;

class GuardController extends Controller
{
    public function index(Request $request)
    {
        $query = Guard::with('company');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $today = now()->startOfDay()->toDateString();
            $plus30 = now()->startOfDay()->addDays(30)->toDateString();
            $plus31 = now()->startOfDay()->addDays(31)->toDateString();
            $plus60 = now()->startOfDay()->addDays(60)->toDateString();

            switch ($request->status) {
                case 'active':
                    $query->whereDate('license_validity_date', '>', $plus60);
                    break;

                case 'expired':
                    $query->whereDate('license_validity_date', '<', $today);
                    break;

                case 'expiring_30':
                    $query->whereDate('license_validity_date', '>=', $today)
                        ->whereDate('license_validity_date', '<=', $plus30);
                    break;

                case 'expiring_60':
                    $query->whereDate('license_validity_date', '>=', $plus31)
                        ->whereDate('license_validity_date', '<=', $plus60);
                    break;
            }
        }

        $guards = $query->latest()->get();
        $companies = Company::orderBy('company_name')->get();

        return view('guards.index', compact('guards', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('company_name')->get();
        return view('guards.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'date_hired' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:guards,license_number'],
            'license_validity_date' => ['required', 'date'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'nbi_clearance_date' => ['nullable', 'date'],
        ]);

        Guard::create($validated);

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard created successfully.');
    }

    public function show(Guard $guard)
    {
        $guard->load('company');
        return view('guards.show', compact('guard'));
    }

    public function edit(Guard $guard)
    {
        $companies = Company::orderBy('company_name')->get();
        return view('guards.edit', compact('guard', 'companies'));
    }

    public function update(Request $request, Guard $guard)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'date_hired' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('guards', 'license_number')->ignore($guard->id),
            ],
            'license_validity_date' => ['required', 'date'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'nbi_clearance_date' => ['nullable', 'date'],
        ]);

        $guard->update($validated);

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard updated successfully.');
    }

    public function scanLicense(Request $request): JsonResponse
    {
        @ini_set('max_execution_time', '300');
        @set_time_limit(300);

        $validated = $request->validate([
            'license_image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $tempPath = null;

        try {
            $tempPath = $this->storeTemporaryLicenseImage($validated['license_image']);
            $process = new Process([
                $this->pythonBinary(),
                $this->ocrScriptPath(),
                $tempPath,
            ], base_path(), [
                'GUARD_OCR_DEBUG_DIR' => $this->ocrDebugDirectory(),
            ]);

            $process->setTimeout($this->ocrTimeoutSeconds());
            $process->run();

            if (! $process->isSuccessful()) {
                $decodedError = json_decode($process->getOutput(), true);
                $message = is_array($decodedError) && ! empty($decodedError['error'])
                    ? (string) $decodedError['error']
                    : 'Unable to extract license details';

                Log::warning('License OCR process failed.', [
                    'exit_code' => $process->getExitCode(),
                    'error_output' => $process->getErrorOutput(),
                    'output' => $process->getOutput(),
                ]);

                return response()->json([
                    'error' => $message,
                ], 422);
            }

            $decoded = json_decode($process->getOutput(), true);

            if (! is_array($decoded)) {
                Log::warning('License OCR returned invalid JSON.', [
                    'output' => $process->getOutput(),
                ]);

                return response()->json([
                    'error' => 'Unable to extract license details',
                ], 422);
            }

            if (! empty($decoded['error'])) {
                return response()->json([
                    'error' => (string) $decoded['error'],
                ], 422);
            }

            $payload = $this->normalizeScanPayload($decoded);

            if (count(array_filter($payload)) === 0) {
                return response()->json([
                    'error' => 'Unable to extract license details: OCR completed, but no supported fields were extracted.',
                ], 422);
            }

            return response()->json($payload);
        } catch (\Throwable $exception) {
            Log::error('License scan failed.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'error' => 'Unable to extract license details: ' . $exception->getMessage(),
            ], 422);
        } finally {
            if ($tempPath && File::exists($tempPath)) {
                File::delete($tempPath);
            }
        }
    }

    public function destroy(Guard $guard)
    {
        $guard->delete();

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard deleted successfully.');
    }

    private function storeTemporaryLicenseImage(UploadedFile $file): string
    {
        $directory = storage_path('app/temp/license-scans');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('license_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return $directory . DIRECTORY_SEPARATOR . $filename;
    }

    private function pythonBinary(): string
    {
        $configuredBinary = env('GUARD_OCR_PYTHON_BINARY');

        if (is_string($configuredBinary) && $configuredBinary !== '' && File::exists($configuredBinary)) {
            return $configuredBinary;
        }

        $legacyConfiguredBinary = env('PYTHON_EXE');

        if (is_string($legacyConfiguredBinary) && $legacyConfiguredBinary !== '' && File::exists($legacyConfiguredBinary)) {
            return $legacyConfiguredBinary;
        }

        $bundledBinary = base_path('.venv-paddle/Scripts/python.exe');

        if (File::exists($bundledBinary)) {
            return $bundledBinary;
        }

        return 'python';
    }

    private function ocrScriptPath(): string
    {
        $configuredScript = env('GUARD_OCR_SCRIPT');

        if (is_string($configuredScript) && $configuredScript !== '' && File::exists($configuredScript)) {
            return $configuredScript;
        }

        $legacyConfiguredScript = env('PADDLE_OCR_SCRIPT');

        if (is_string($legacyConfiguredScript) && $legacyConfiguredScript !== '' && File::exists($legacyConfiguredScript)) {
            return $legacyConfiguredScript;
        }

        return base_path('scripts/scan_guard_license.py');
    }

    private function ocrTimeoutSeconds(): int
    {
        $configuredTimeout = (int) env('GUARD_OCR_TIMEOUT_SECONDS', 300);

        return max(60, $configuredTimeout);
    }

    private function ocrDebugDirectory(): string
    {
        $directory = storage_path('app/ocr-debug');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return $directory;
    }

    private function normalizeScanPayload(array $decoded): array
    {
        return [
            'last_name' => trim((string) ($decoded['last_name'] ?? '')),
            'first_name' => trim((string) ($decoded['first_name'] ?? '')),
            'middle_name' => trim((string) ($decoded['middle_name'] ?? '')),
            'license_number' => trim((string) ($decoded['license_number'] ?? '')),
            'license_validity_date' => trim((string) ($decoded['license_validity_date'] ?? '')),
        ];
    }
}
