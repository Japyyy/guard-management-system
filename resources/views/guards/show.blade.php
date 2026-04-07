@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Guard Details</h1>
            <p class="text-sm text-slate-500 mt-1">
                View the complete profile and employment information of this guard.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guards.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>

            <a href="{{ route('guards.edit', $guard) }}"
               class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Edit Guard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $guard->full_name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ $guard->company->company_name }}</p>
                    </div>

                    @if($guard->status === 'Active')
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                    @elseif($guard->status === 'Expired')
                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Expired</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Expiring</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->last_name }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->first_name }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Middle Name</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->middle_name ?: '—' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Civil Status</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->civil_status }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Birthdate</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->birthdate->format('M d, Y') }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date Hired</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->date_hired->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-5">License Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">License Number</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->license_number }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">License Validity Date</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->license_validity_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-5">Government Details</h3>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">SSS Number</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->sss_number ?: '—' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">PhilHealth Number</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->philhealth_number ?: '—' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pag-IBIG Number</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->pagibig_number ?: '—' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN Number</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ $guard->tin_number ?: '—' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NBI Clearance Date</p>
                        <p class="mt-2 text-sm font-medium text-slate-900">
                            {{ $guard->nbi_clearance_date ? $guard->nbi_clearance_date->format('M d, Y') : '—' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-red-200 bg-red-50 p-6">
                <h3 class="text-sm font-semibold text-red-800 mb-4">Danger Zone</h3>

                <form action="{{ route('guards.destroy', $guard) }}" method="POST" onsubmit="return confirm('Delete this guard?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                        Delete Guard
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection