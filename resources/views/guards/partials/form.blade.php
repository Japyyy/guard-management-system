<div>
    <label for="company_id" class="mb-1.5 block text-sm font-medium text-slate-700">
        Company
    </label>
    <select name="company_id"
            id="company_id"
            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
            required>
        <option value="">Select Company</option>
        @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ (string) old('company_id', $guard->company_id ?? '') === (string) $company->id ? 'selected' : '' }}>
                {{ $company->company_name }}
            </option>
        @endforeach
    </select>
    @error('company_id')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="civil_status" class="mb-1.5 block text-sm font-medium text-slate-700">
        Civil Status
    </label>
    <input type="text"
           name="civil_status"
           id="civil_status"
           value="{{ old('civil_status', $guard->civil_status ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('civil_status')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="last_name" class="mb-1.5 block text-sm font-medium text-slate-700">
        Last Name
    </label>
    <input type="text"
           name="last_name"
           id="last_name"
           value="{{ old('last_name', $guard->last_name ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('last_name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="first_name" class="mb-1.5 block text-sm font-medium text-slate-700">
        First Name
    </label>
    <input type="text"
           name="first_name"
           id="first_name"
           value="{{ old('first_name', $guard->first_name ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('first_name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="middle_name" class="mb-1.5 block text-sm font-medium text-slate-700">
        Middle Name
    </label>
    <input type="text"
           name="middle_name"
           id="middle_name"
           value="{{ old('middle_name', $guard->middle_name ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('middle_name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="birthdate" class="mb-1.5 block text-sm font-medium text-slate-700">
        Birthdate
    </label>
    <input type="date"
           name="birthdate"
           id="birthdate"
           value="{{ old('birthdate', isset($guard->birthdate) ? $guard->birthdate->format('Y-m-d') : '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('birthdate')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="date_hired" class="mb-1.5 block text-sm font-medium text-slate-700">
        Date Hired
    </label>
    <input type="date"
           name="date_hired"
           id="date_hired"
           value="{{ old('date_hired', isset($guard->date_hired) ? $guard->date_hired->format('Y-m-d') : '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('date_hired')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="license_number" class="mb-1.5 block text-sm font-medium text-slate-700">
        License Number
    </label>
    <input type="text"
           name="license_number"
           id="license_number"
           value="{{ old('license_number', $guard->license_number ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('license_number')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="license_validity_date" class="mb-1.5 block text-sm font-medium text-slate-700">
        License Validity Date
    </label>
    <input type="date"
           name="license_validity_date"
           id="license_validity_date"
           value="{{ old('license_validity_date', isset($guard->license_validity_date) ? $guard->license_validity_date->format('Y-m-d') : '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('license_validity_date')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="sss_number" class="mb-1.5 block text-sm font-medium text-slate-700">
        SSS Number
    </label>
    <input type="text"
           name="sss_number"
           id="sss_number"
           value="{{ old('sss_number', $guard->sss_number ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('sss_number')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="philhealth_number" class="mb-1.5 block text-sm font-medium text-slate-700">
        PhilHealth Number
    </label>
    <input type="text"
           name="philhealth_number"
           id="philhealth_number"
           value="{{ old('philhealth_number', $guard->philhealth_number ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('philhealth_number')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="pagibig_number" class="mb-1.5 block text-sm font-medium text-slate-700">
        Pag-IBIG Number
    </label>
    <input type="text"
           name="pagibig_number"
           id="pagibig_number"
           value="{{ old('pagibig_number', $guard->pagibig_number ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('pagibig_number')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="tin_number" class="mb-1.5 block text-sm font-medium text-slate-700">
        TIN Number
    </label>
    <input type="text"
           name="tin_number"
           id="tin_number"
           value="{{ old('tin_number', $guard->tin_number ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('tin_number')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="nbi_clearance_date" class="mb-1.5 block text-sm font-medium text-slate-700">
        NBI Clearance Date
    </label>
    <input type="date"
           name="nbi_clearance_date"
           id="nbi_clearance_date"
           value="{{ old('nbi_clearance_date', isset($guard->nbi_clearance_date) && $guard->nbi_clearance_date ? $guard->nbi_clearance_date->format('Y-m-d') : '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
    @error('nbi_clearance_date')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="md:col-span-2 flex justify-end gap-2 pt-2">
    <a href="{{ route('guards.index') }}"
       class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
        Cancel
    </a>

    <button type="submit"
            class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        Update Guard
    </button>
</div>