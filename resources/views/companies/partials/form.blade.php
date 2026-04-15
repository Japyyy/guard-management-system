<div class="md:col-span-2">
    <label for="company_name" class="mb-1.5 block text-sm font-medium text-slate-700">
        Company Name
    </label>
    <input type="text"
           name="company_name"
           id="company_name"
           value="{{ old('company_name', $company->company_name ?? '') }}"
           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
           required>
    @error('company_name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="md:col-span-2">
    <label for="address" class="mb-1.5 block text-sm font-medium text-slate-700">
        Address
    </label>
    <textarea name="address"
              id="address"
              rows="4"
              class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">{{ old('address', $company->address ?? '') }}</textarea>
    @error('address')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="md:col-span-2 flex justify-end gap-2 pt-2">
    <a href="{{ route('companies.index') }}"
       class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
        Cancel
    </a>

    <button type="submit"
            class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        Update Company
    </button>
</div>