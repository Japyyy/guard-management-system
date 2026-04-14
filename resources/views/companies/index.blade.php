@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Company Directory</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Manage client companies and oversee assigned personnel deployments.
                </p>
            </div>

            <a href="{{ route('companies.create') }}"
               class="inline-flex items-center rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-900/10 hover:opacity-95">
                + Add Company
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Company List</h3>
                    <p class="mt-1 text-sm text-slate-500">All registered clients and deployment locations.</p>
                </div>

                <div class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                    {{ $companies->total() }} compan{{ $companies->total() === 1 ? 'y' : 'ies' }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">Company</th>
                            <th class="px-6 py-4 font-semibold">Address</th>
                            <th class="px-6 py-4 font-semibold">Assigned Guards</th>
                            <th class="px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($companies as $company)
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $company->company_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">Deployment / Client</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $company->address ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $company->guards_count }} Guard{{ $company->guards_count !== 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
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

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
@endsection