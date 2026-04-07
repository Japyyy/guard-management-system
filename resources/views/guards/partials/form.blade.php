<div class="md:col-span-2">
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800">Assignment Information</h2>
        <p class="text-sm text-slate-500 mt-1">Select the assigned company and basic civil details.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Company</label>
            <select name="company_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                    required>
                <option value="">Select company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}"
                        {{ old('company_id', optional($guard)->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-2">Choose where this guard is currently deployed.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Civil Status</label>
            @php $civilStatus = old('civil_status', optional($guard)->civil_status); @endphp
            <select name="civil_status"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                    required>
                <option value="">Select civil status</option>
                <option value="Single" {{ $civilStatus === 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ $civilStatus === 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Widowed" {{ $civilStatus === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="Separated" {{ $civilStatus === 'Separated' ? 'selected' : '' }}>Separated</option>
            </select>
        </div>
    </div>
</div>

<div class="md:col-span-2 mt-4">
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800">Personal Information</h2>
        <p class="text-sm text-slate-500 mt-1">Enter the guard’s full name and birth details.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', optional($guard)->last_name) }}"
                   placeholder="Enter last name"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">First Name</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', optional($guard)->first_name) }}"
                   placeholder="Enter first name"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Middle Name</label>
            <input type="text" name="middle_name"
                   value="{{ old('middle_name', optional($guard)->middle_name) }}"
                   placeholder="Enter middle name"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Birthdate</label>
            <input type="date" name="birthdate"
                   value="{{ old('birthdate', optional($guard)?->birthdate?->format('Y-m-d')) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Date Hired</label>
            <input type="date" name="date_hired"
                   value="{{ old('date_hired', optional($guard)?->date_hired?->format('Y-m-d')) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
        </div>
    </div>
</div>

<div class="md:col-span-2 mt-4">
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800">License Information</h2>
        <p class="text-sm text-slate-500 mt-1">These fields are used for active, expired, and expiring license status.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">License Number</label>
            <input type="text" name="license_number"
                   value="{{ old('license_number', optional($guard)->license_number) }}"
                   placeholder="Enter license number"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">License Validity Date</label>
            <input type="date" name="license_validity_date"
                   value="{{ old('license_validity_date', optional($guard)?->license_validity_date?->format('Y-m-d')) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
            <p class="text-xs text-slate-400 mt-2">This date determines whether the guard is active, expiring, or expired.</p>
        </div>
    </div>
</div>

<div class="md:col-span-2 mt-4">
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800">Government Numbers</h2>
        <p class="text-sm text-slate-500 mt-1">Fill in the required government reference numbers if available.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">SSS Number</label>
            <input type="text" name="sss_number"
                   value="{{ old('sss_number', optional($guard)->sss_number) }}"
                   placeholder="Enter SSS number"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">PhilHealth Number</label>
            <input type="text" name="philhealth_number"
                   value="{{ old('philhealth_number', optional($guard)->philhealth_number) }}"
                   placeholder="Enter PhilHealth number"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pag-IBIG Number</label>
            <input type="text" name="pagibig_number"
                   value="{{ old('pagibig_number', optional($guard)->pagibig_number) }}"
                   placeholder="Enter Pag-IBIG number"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">TIN Number</label>
            <input type="text" name="tin_number"
                   value="{{ old('tin_number', optional($guard)->tin_number) }}"
                   placeholder="Enter TIN number"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">NBI Clearance Date</label>
            <input type="date" name="nbi_clearance_date"
                   value="{{ old('nbi_clearance_date', optional($guard)?->nbi_clearance_date?->format('Y-m-d')) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
        </div>
    </div>
</div>

<div class="md:col-span-2 sticky bottom-0 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/80 pt-6 mt-8 border-t border-slate-200">
    <div class="flex flex-wrap items-center justify-end gap-3">
        <a href="{{ route('guards.index') }}"
           class="inline-flex items-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
            Cancel
        </a>

        <button type="submit"
                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
            Save Guard
        </button>
    </div>
</div>