<div class="md:col-span-2">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Company Information</h3>
            <p class="mt-1 text-sm text-slate-500">Main profile details for the client or deployment site.</p>
        </div>
        <div class="hidden md:inline-flex rounded-2xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
            Company Form
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Company Name</label>
            <input type="text"
                   name="company_name"
                   value="{{ old('company_name', optional($company)->company_name) }}"
                   placeholder="Enter company name"
                   class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none"
                   required>
            <p class="mt-2 text-xs text-slate-400">This will appear in company lists and guard assignments.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
            <input type="text"
                   name="address"
                   value="{{ old('address', optional($company)->address) }}"
                   placeholder="Enter company address"
                   class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none">
            <p class="mt-2 text-xs text-slate-400">Optional, but recommended for deployment tracking.</p>
        </div>
    </div>
</div>

<div class="md:col-span-2 sticky bottom-0 bg-white/90 backdrop-blur border-t border-slate-200 pt-6 mt-8">
    <div class="flex flex-wrap items-center justify-end gap-3">
        <a href="{{ route('companies.index') }}"
           class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            Cancel
        </a>

        <button type="submit"
                class="inline-flex items-center rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/10 hover:opacity-95 transition">
            Save Company
        </button>
    </div>
</div>