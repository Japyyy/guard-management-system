<div class="md:col-span-2">
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800">Company Information</h2>
        <p class="text-sm text-slate-500 mt-1">Enter the main details of the company or deployment location.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Company Name</label>
            <input type="text"
                   name="company_name"
                   value="{{ old('company_name', optional($company)->company_name) }}"
                   placeholder="Enter company name"
                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                   required>
            <p class="text-xs text-slate-400 mt-2">This will be shown in the company list and guard assignments.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
            <input type="text"
                   name="address"
                   value="{{ old('address', optional($company)->address) }}"
                   placeholder="Enter company address"
                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
            <p class="text-xs text-slate-400 mt-2">Optional, but useful for deployment reference.</p>
        </div>
    </div>
</div>

<div class="md:col-span-2 sticky bottom-0 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/80 pt-6 mt-8 border-t border-slate-200">
    <div class="flex flex-wrap items-center justify-end gap-3">
        <a href="{{ route('companies.index') }}"
           class="inline-flex items-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
            Cancel
        </a>

        <button type="submit"
                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
            Save Company
        </button>
    </div>
</div>