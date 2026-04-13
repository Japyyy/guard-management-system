@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Company Details</h1>
            <p class="text-sm text-slate-500 mt-1">
                View the company profile and all assigned security guards.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('companies.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>

            <a href="{{ route('companies.edit', $company) }}"
               class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Edit Company
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <div class="xl:col-span-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 h-full">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $company->company_name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Deployment / Client Company</p>
                    </div>

                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        {{ $company->guards->count() }} Guard{{ $company->guards->count() !== 1 ? 's' : '' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Company Name</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $company->company_name }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Assigned Guards</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $company->guards->count() }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 md:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $company->address ?: '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="rounded-3xl border border-red-200 bg-red-50 p-6">
                <h3 class="text-sm font-semibold text-red-800 mb-3">Danger Zone</h3>
                <p class="text-sm text-red-700 mb-4">
                    Deleting this company will also remove all guards assigned to it if your foreign key uses cascade delete.
                </p>

                <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? This may also delete assigned guards.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                        Delete Company
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Assigned Guards</h3>
                <p class="text-sm text-slate-500 mt-1">
                    All security guards currently assigned to this company.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-6 py-4 font-semibold">Full Name</th>
                        <th class="px-6 py-4 font-semibold">Civil Status</th>
                        <th class="px-6 py-4 font-semibold">Birthdate</th>
                        <th class="px-6 py-4 font-semibold">Date Hired</th>
                        <th class="px-6 py-4 font-semibold">License Number</th>
                        <th class="px-6 py-4 font-semibold">License Validity</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($company->guards as $guard)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $guard->full_name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $guard->civil_status }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $guard->birthdate->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $guard->date_hired->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $guard->license_number }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $guard->license_validity_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                @if($guard->status === 'Active')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Active
                                    </span>
                                @elseif($guard->status === 'Expired')
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Expiring
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                No guards assigned to this company yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div
@endsection