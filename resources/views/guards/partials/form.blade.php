@php
    $isEditing = isset($guard) && $guard;
    $civilStatusOptions = ['Single', 'Married', 'Widowed', 'Separated'];
@endphp

@unless($isEditing)
    {{-- LICENSE UPLOAD --}}
    <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">

            <div class="flex-1">
                <label for="license_image" class="mb-1.5 block text-sm font-semibold text-slate-700">
                    Guard License Image
                </label>

                <div class="flex items-center gap-3">
                    <input type="file"
                           id="license_image"
                           accept=".jpg,.jpeg,.png,.webp"
                           data-scan-url="{{ route('guards.scan-license') }}"
                           class="block w-full text-sm text-slate-600
                                  file:mr-4 file:rounded-lg file:border-0
                                  file:bg-cyan-600 file:px-4 file:py-2
                                  file:text-sm file:font-medium
                                  file:text-white hover:file:bg-cyan-700
                                  cursor-pointer"/>

                    <button type="button"
                            id="scan_license_btn"
                            class="whitespace-nowrap rounded-xl bg-cyan-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                        Scan
                    </button>
                </div>

                <p class="mt-2 text-xs text-slate-500">
                    Upload a license image to auto-fill guard details.
                </p>
            </div>
        </div>

        <p id="ocr_status" class="mt-3 text-sm text-slate-500"></p>
    </div>
@endunless


{{-- 🔷 PRIORITY DETAILS (CLEAN GROUPED LAYOUT) --}}
<div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mt-4">

    {{-- NAME ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Last Name</label>
            <input type="text" name="last_name" id="last_name"
                   value="{{ old('last_name', $guard->last_name ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                   required>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">First Name</label>
            <input type="text" name="first_name" id="first_name"
                   value="{{ old('first_name', $guard->first_name ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                   required>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name"
                   value="{{ old('middle_name', $guard->middle_name ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
        </div>
    </div>

    {{-- LICENSE ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">License Number</label>
            <input type="text" name="license_number" id="license_number"
                   value="{{ old('license_number', $guard->license_number ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                   required>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Expiry Date</label>
            <input type="date" name="license_validity_date" id="license_validity_date"
                   value="{{ old('license_validity_date', isset($guard->license_validity_date) ? $guard->license_validity_date->format('Y-m-d') : '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
        </div>
    </div>

</div>


{{-- OTHER DETAILS --}}
<div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mt-4">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Company</label>
            <select name="company_id" id="company_id"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ (string) old('company_id', $guard->company_id ?? '') === (string) $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Civil Status</label>
            <select name="civil_status" id="civil_status"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                <option value="">Select Civil Status</option>
                @foreach ($civilStatusOptions as $status)
                    <option value="{{ $status }}" {{ old('civil_status', $guard->civil_status ?? '') === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Birthdate</label>
            <input type="date" name="birthdate" id="birthdate"
                   value="{{ old('birthdate', isset($guard->birthdate) ? $guard->birthdate->format('Y-m-d') : '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Date Hired</label>
            <input type="date" name="date_hired" id="date_hired"
                   value="{{ old('date_hired', isset($guard->date_hired) ? $guard->date_hired->format('Y-m-d') : '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
        </div>

    </div>
</div>


{{-- ADDRESS --}}
<div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mt-4">
    <label class="mb-1.5 block text-sm font-medium text-slate-700">Address</label>
    <input type="text" name="address" id="address"
           value="{{ old('address', $guard->address ?? '') }}"
           class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
</div>


{{-- GOVERNMENT IDS --}}
<div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mt-4">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <input type="text" name="sss_number" placeholder="SSS Number"
               value="{{ old('sss_number', $guard->sss_number ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">

        <input type="text" name="philhealth_number" placeholder="PhilHealth Number"
               value="{{ old('philhealth_number', $guard->philhealth_number ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">

        <input type="text" name="pagibig_number" placeholder="Pag-IBIG Number"
               value="{{ old('pagibig_number', $guard->pagibig_number ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">

        <input type="text" name="tin_number" placeholder="TIN Number"
               value="{{ old('tin_number', $guard->tin_number ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">

    </div>
</div>


{{-- ACTIONS --}}
<div class="md:col-span-2 flex justify-end gap-3 pt-4">
    <a href="{{ route('guards.index') }}"
       class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
        Cancel
    </a>

    <button type="submit"
            class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
        {{ isset($guard) && $guard ? 'Update Guard' : 'Save Guard' }}
    </button>
</div>

@unless($isEditing)
    <script>
        (() => {
            const imageInput = document.getElementById('license_image');
            const scanButton = document.getElementById('scan_license_btn');
            const statusText = document.getElementById('ocr_status');

            if (!imageInput || !scanButton || !statusText) {
                return;
            }

            const fieldMap = {
                last_name: document.getElementById('last_name'),
                first_name: document.getElementById('first_name'),
                middle_name: document.getElementById('middle_name'),
                license_number: document.getElementById('license_number'),
                license_validity_date: document.getElementById('license_validity_date'),
            };

            const setStatus = (message, type = 'default') => {
                statusText.textContent = message;
                statusText.classList.remove('text-slate-500', 'text-red-600', 'text-emerald-600');

                if (type === 'error') {
                    statusText.classList.add('text-red-600');
                    return;
                }

                if (type === 'success') {
                    statusText.classList.add('text-emerald-600');
                    return;
                }

                statusText.classList.add('text-slate-500');
            };

            const setFieldValue = (field, value) => {
                if (!field || !value) {
                    return false;
                }

                field.value = value;
                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
                return true;
            };

            scanButton.addEventListener('click', async () => {
                const file = imageInput.files[0];
                const scanUrl = imageInput.dataset.scanUrl;

                if (!file) {
                    setStatus('Please select a license image first.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('license_image', file);

                scanButton.disabled = true;
                setStatus('Scanning license image. Please wait...');

                try {
                    const response = await fetch(scanUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(data.error || 'Unable to extract license details');
                    }

                    const updatedFields = Object.entries(fieldMap)
                        .filter(([key, field]) => setFieldValue(field, data[key] || ''))
                        .map(([key]) => key);

                    if (updatedFields.length === 0) {
                        throw new Error('Unable to extract license details');
                    }

                    const labelMap = {
                        last_name: 'Last Name',
                        first_name: 'First Name',
                        middle_name: 'Middle Name',
                        license_number: 'License Number',
                        license_validity_date: 'Expiry Date',
                    };

                    setStatus(
                        `Scan completed. Updated: ${updatedFields.map((key) => labelMap[key]).join(', ')}.`,
                        'success'
                    );
                } catch (error) {
                    setStatus(error.message || 'Unable to extract license details', 'error');
                } finally {
                    scanButton.disabled = false;
                }
            });
        })();
    </script>
@endunless
