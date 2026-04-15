@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6 h-[calc(100vh-120px)]">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm flex flex-col min-h-0">
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Company List</h3>
                        <p class="mt-1 text-sm text-slate-500">All registered clients and deployment locations.</p>
                    </div>

                    <div class="hidden md:inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                        {{ $companies->count() }} compan{{ $companies->count() === 1 ? 'y' : 'ies' }}
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <form method="GET" action="{{ route('companies.index') }}" class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search company name"
                               class="w-full md:w-72 rounded-2xl border border-slate-300 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">

                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                            <circle cx="11" cy="11" r="6" />
                        </svg>
                    </form>

                    <a href="{{ route('companies.create') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-cyan-900/10 hover:opacity-95">
                        + Add Company
                    </a>
                </div>
            </div>

            @if(request('search'))
                <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 bg-slate-50 px-6 py-3 shrink-0">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        Search: {{ request('search') }}
                    </span>

                    <a href="{{ route('companies.index') }}"
                       class="ml-auto inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Clear Search
                    </a>
                </div>
            @endif

            <div class="flex-1 min-h-0 overflow-auto">
                <table class="min-w-full table-fixed text-sm">
                    <thead class="sticky top-0 z-10 bg-slate-50 border-b border-slate-200">
                        <tr class="text-left text-slate-600">
                            <th class="w-[32%] px-6 py-4 font-semibold">Company</th>
                            <th class="w-[30%] px-6 py-4 font-semibold">Address</th>
                            <th class="w-[18%] px-6 py-4 font-semibold">Assigned Guards</th>
                            <th class="w-[20%] px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($companies as $company)
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="px-6 py-4 align-middle">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $company->company_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">Deployment / Client</p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle text-slate-700">
                                    <div class="truncate">
                                        {{ $company->address ?: '—' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $company->guards_count }} Guard{{ $company->guards_count !== 1 ? 's' : '' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('companies.show', $company) }}"
                                           class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                            View
                                        </a>

                                        <a href="{{ route('companies.edit', $company) }}"
                                           class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? This will also delete assigned guards.')">
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
                                <td colspan="4" class="px-6 py-14 text-center text-slate-500">
                                    No companies found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection