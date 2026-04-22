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


{{-- 🔷 PRIORITY DETAILS (TOP ALIGNED CLEAN ROW) --}}
<div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mt-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

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

        <div>
            <label class="mb-1.5 block text-sm font-semibold text-slate-700">License Number</label>
            <input type="text" name="license_number" id="license_number"
                   value="{{ old('license_number', $guard->license_number ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                   required>
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
            <input type="text" name="civil_status" id="civil_status"
                   value="{{ old('civil_status', $guard->civil_status ?? '') }}"
                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
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

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Expiry Date</label>
            <input type="date" name="license_validity_date" id="license_validity_date"
                   value="{{ old('license_validity_date', isset($guard->license_validity_date) ? $guard->license_validity_date->format('Y-m-d') : '') }}"
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