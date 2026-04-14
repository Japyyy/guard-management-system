@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Guard Directory</h2>
                <p class="mt-1 text-sm text-slate-500">
                    View, search, and manage all personnel records in one place.
                </p>
            </div>

            <a href="{{ route('guards.create') }}"
               class="inline-flex items-center rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/10 hover:opacity-95">
                + Add Guard
            </a>
        </div>

        <form method="GET" action="{{ route('guards.index') }}"
              class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Name or license number"
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</label>
                    <select name="status"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="expiring_30" {{ request('status') === 'expiring_30' ? 'selected' : '' }}>Expiring in 30 Days</option>
                        <option value="expiring_60" {{ request('status') === 'expiring_60' ? 'selected' : '' }}>Expiring in 60 Days</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Company</label>
                    <select name="company_id"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ (string) request('company_id') === (string) $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                        Apply
                    </button>
                    <a href="{{ route('guards.index') }}"
                       class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Personnel List</h3>
                    <p class="mt-1 text-sm text-slate-500">Comprehensive list of all registered security guards.</p>
                </div>

                <div class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                    {{ $guards->total() }} record{{ $guards->total() !== 1 ? 's' : '' }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">Guard</th>
                            <th class="px-6 py-4 font-semibold">Company</th>
                            <th class="px-6 py-4 font-semibold">License No.</th>
                            <th class="px-6 py-4 font-semibold">Validity Date</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($guards as $guard)
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $guard->full_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $guard->civil_status }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->company?->company_name ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->license_number }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->license_validity_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($guard->status === 'Active')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                    @elseif($guard->status === 'Expired')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Expired</span>
                                    @elseif($guard->status === 'Expiring in 30 Days')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Expiring in 30 Days</span>
                                    @elseif($guard->status === 'Expiring in 60 Days')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Expiring in 60 Days</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $guard->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('guards.show', $guard) }}"
                                           class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                            View
                                        </a>
                                        <a href="{{ route('guards.edit', $guard) }}"
                                           class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('guards.destroy', $guard) }}" method="POST" onsubmit="return confirm('Delete this guard?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-xl bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center text-slate-500">
                                    No guards found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $guards->links() }}
            </div>
        </div>
    </div>
@endsection