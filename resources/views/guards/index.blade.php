@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6 h-[calc(100vh-120px)]">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm flex flex-col min-h-0">
            {{-- HEADER --}}
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Personnel List</h3>
                        <p class="mt-1 text-sm text-slate-500">Comprehensive list of all registered security guards.</p>
                    </div>

                    <div class="hidden md:inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                        {{ $guards->count() }} record{{ $guards->count() !== 1 ? 's' : '' }}
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <form method="GET" action="{{ route('guards.index') }}" class="relative">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="company_id" value="{{ request('company_id') }}">

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search name or license no."
                               class="w-full md:w-72 rounded-2xl border border-slate-300 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">

                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                            <circle cx="11" cy="11" r="6" />
                        </svg>
                    </form>

                    <a href="{{ route('guards.create') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-cyan-900/10 hover:opacity-95">
                        + Add Guard
                    </a>
                </div>
            </div>

            {{-- ACTIVE FILTER CHIPS --}}
            @if(request('search') || request('status') || request('company_id'))
                <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 bg-slate-50 px-6 py-3 shrink-0">
                    @if(request('search'))
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            Search: {{ request('search') }}
                        </span>
                    @endif

                    @if(request('status'))
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Status:
                            @switch(request('status'))
                                @case('active') Active @break
                                @case('expired') Expired @break
                                @case('expiring_30') Expiring in 30 Days @break
                                @case('expiring_60') Expiring in 60 Days @break
                                @default {{ request('status') }}
                            @endswitch
                        </span>
                    @endif

                    @if(request('company_id'))
                        @php
                            $selectedCompany = $companies->firstWhere('id', request('company_id'));
                        @endphp
                        <span class="inline-flex items-center rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                            Company: {{ $selectedCompany?->company_name ?? 'Selected Company' }}
                        </span>
                    @endif

                    <a href="{{ route('guards.index') }}"
                       class="ml-auto inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Clear Filters
                    </a>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="flex-1 min-h-0 overflow-auto">
                <table class="min-w-full table-fixed text-sm">
                    <thead class="sticky top-0 z-20 bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-slate-600">
                            <th class="w-[19%] px-6 py-4 font-semibold">Guard</th>

                            <th class="w-[18%] px-6 py-4 font-semibold">
                                <details class="relative">
                                    <summary class="flex cursor-pointer list-none items-center gap-2 select-none hover:text-slate-900">
                                        <span>Company</span>

                                        @if(request('company_id'))
                                            <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                                        @endif

                                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                                        </svg>
                                    </summary>

                                    <div class="absolute left-0 top-full mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'status' => request('status'),
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            All Companies
                                        </a>

                                        @foreach($companies as $company)
                                            <a href="{{ route('guards.index', array_filter([
                                                'search' => request('search'),
                                                'status' => request('status'),
                                                'company_id' => $company->id,
                                            ])) }}"
                                               class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50 {{ (string) request('company_id') === (string) $company->id ? 'bg-violet-50 font-semibold text-violet-700' : 'text-slate-700' }}">
                                                {{ $company->company_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </details>
                            </th>

                            <th class="w-[14%] px-6 py-4 font-semibold">License No.</th>
                            <th class="w-[13%] px-6 py-4 font-semibold">Validity Date</th>

                            <th class="w-[16%] px-6 py-4 font-semibold">
                                <details class="relative">
                                    <summary class="flex cursor-pointer list-none items-center gap-2 select-none hover:text-slate-900">
                                        <span>Status</span>

                                        @if(request('status'))
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        @endif

                                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                                        </svg>
                                    </summary>

                                    <div class="absolute left-0 top-full mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'company_id' => request('company_id'),
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            All Status
                                        </a>

                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'company_id' => request('company_id'),
                                            'status' => 'active',
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50 {{ request('status') === 'active' ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-700' }}">
                                            Active
                                        </a>

                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'company_id' => request('company_id'),
                                            'status' => 'expired',
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50 {{ request('status') === 'expired' ? 'bg-red-50 font-semibold text-red-700' : 'text-slate-700' }}">
                                            Expired
                                        </a>

                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'company_id' => request('company_id'),
                                            'status' => 'expiring_30',
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50 {{ request('status') === 'expiring_30' ? 'bg-amber-50 font-semibold text-amber-700' : 'text-slate-700' }}">
                                            Expiring in 30 Days
                                        </a>

                                        <a href="{{ route('guards.index', array_filter([
                                            'search' => request('search'),
                                            'company_id' => request('company_id'),
                                            'status' => 'expiring_60',
                                        ])) }}"
                                           class="block rounded-xl px-3 py-2 text-sm hover:bg-slate-50 {{ request('status') === 'expiring_60' ? 'bg-yellow-50 font-semibold text-yellow-700' : 'text-slate-700' }}">
                                            Expiring in 60 Days
                                        </a>
                                    </div>
                                </details>
                            </th>

                            <th class="w-[20%] px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($guards as $guard)
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="px-6 py-4 align-middle">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $guard->full_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $guard->civil_status }}</p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle text-slate-700">
                                    <div class="truncate">
                                        {{ $guard->company?->company_name ?? '—' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle text-slate-700">
                                    <div class="truncate">
                                        {{ $guard->license_number }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle text-slate-700">
                                    {{ $guard->license_validity_date?->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    @if($guard->status === 'Active')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Active
                                        </span>
                                    @elseif($guard->status === 'Expired')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Expired
                                        </span>
                                    @elseif($guard->status === 'Expiring in 30 Days')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Expiring in 30 Days
                                        </span>
                                    @elseif($guard->status === 'Expiring in 60 Days')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Expiring in 60 Days
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $guard->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center justify-center gap-2">
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
        </div>
    </div>
@endsection