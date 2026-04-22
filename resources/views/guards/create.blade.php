@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Add Guard</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Create a new personnel record and assign the guard to a deployment company.
                </p>
            </div>

            <a href="{{ route('guards.index') }}"
               class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>
        </div>

        <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 md:p-6 shadow-sm">
            <form action="{{ route('guards.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                @csrf
                @include('guards.partials.form', ['guard' => null])
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scanButton = document.getElementById('scan_license_btn');
            const fileInput = document.getElementById('license_image');
            const status = document.getElementById('ocr_status');

            if (!scanButton || !fileInput || !status) {
                return;
            }

            function setStatus(message, type = 'default') {
                status.textContent = message;

                if (type === 'error') {
                    status.className = 'mt-3 text-sm text-red-600';
                    return;
                }

                if (type === 'success') {
                    status.className = 'mt-3 text-sm text-green-600';
                    return;
                }

                if (type === 'warning') {
                    status.className = 'mt-3 text-sm text-amber-600';
                    return;
                }

                status.className = 'mt-3 text-sm text-slate-500';
            }

            function setValue(id, value) {
                const el = document.getElementById(id);

                if (!el || value === null || value === undefined || value === '') {
                    return false;
                }

                if (el.type === 'date') {
                    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                        el.value = value;
                        return true;
                    }
                    return false;
                }

                el.value = value;
                return true;
            }

            scanButton.addEventListener('click', async function () {
                if (!fileInput.files.length) {
                    setStatus('Please choose a license image first.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('license_image', fileInput.files[0]);

                scanButton.disabled = true;
                scanButton.textContent = 'Scanning...';
                setStatus('Scanning license. Please wait...');

                try {
                    const response = await fetch("{{ route('guards.ocr.scan') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const rawText = await response.text();

                    let result = null;

                    if (contentType.includes('application/json')) {
                        result = JSON.parse(rawText);
                    } else {
                        throw new Error('Server returned HTML instead of JSON: ' + rawText.substring(0, 300));
                    }

                    if (!response.ok || result.success === false) {
                        throw new Error(result.message || 'OCR scan failed.');
                    }

                    const data = result.data || {};

                    const filled = {
                        last_name: setValue('last_name', data.last_name),
                        first_name: setValue('first_name', data.first_name),
                        middle_name: setValue('middle_name', data.middle_name),
                        license_number: setValue('license_number', data.license_number),
                        license_validity_date: setValue('license_validity_date', data.license_validity_date),
                    };

                    const filledCount = Object.values(filled).filter(Boolean).length;

                    if (filledCount === 0) {
                        setStatus('OCR completed but no usable values were extracted. Check storage/app/ocr-debug/last-run.txt.', 'error');
                        return;
                    }

                    if (filledCount < 5) {
                        setStatus('Scan complete. Some fields were filled. Review everything before saving.', 'warning');
                        return;
                    }

                    setStatus('Scan complete. Review the fields before saving.', 'success');
                } catch (error) {
                    console.error(error);
                    setStatus(error.message || 'OCR scan failed.', 'error');
                } finally {
                    scanButton.disabled = false;
                    scanButton.textContent = 'Scan License';
                }
            });
        });
    </script>
@endsection