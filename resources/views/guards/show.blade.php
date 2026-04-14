@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Guard Profile</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Complete personnel information, license details, and government references.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('guards.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Back to List
                </a>

                <a href="{{ route('guards.edit', $guard) }}"
                   class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Edit Guard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-8 space-y-6">
                <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-600">Personnel Record</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $guard->full_name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $guard->company?->company_name ?? '—' }}</p>
                        </div>

                        <div>
                            @if($guard->status === 'Active')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-4 py-1.5 text-xs font-semibold text-emerald-700">Active</span>
                            @elseif($guard->status === 'Expired')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-4 py-1.5 text-xs font-semibold text-red-700">Expired</span>
                            @elseif($guard->status === 'Expiring in 30 Days')
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-4 py-1.5 text-xs font-semibold text-amber-700">Expiring in 30 Days</span>
                            @elseif($guard->status === 'Expiring in 60 Days')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-4 py-1.5 text-xs font-semibold text-yellow-700">Expiring in 60 Days</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-1.5 text-xs font-semibold text-slate-700">{{ $guard->status }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->last_name }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->first_name }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Middle Name</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->middle_name ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Civil Status</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->civil_status }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Birthdate</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->birthdate?->format('M d, Y') ?? '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date Hired</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->date_hired?->format('M d, Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-white to-blue-50/40 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">License Information</h3>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">License Number</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->license_number }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">License Validity Date</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->license_validity_date?->format('M d, Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-4 space-y-6">
                <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Government Details</h3>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">SSS Number</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->sss_number ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">PhilHealth Number</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->philhealth_number ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pag-IBIG Number</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->pagibig_number ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN Number</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->tin_number ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NBI Clearance Date</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $guard->nbi_clearance_date ? $guard->nbi_clearance_date->format('M d, Y') : '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[30px] border border-red-200 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-red-700">Danger Zone</h3>
                    <p class="mt-3 text-sm text-red-700">
                        Deleting this guard will permanently remove the personnel record from the system.
                    </p>

                    <form action="{{ route('guards.destroy', $guard) }}" method="POST" onsubmit="return confirm('Delete this guard?')" class="mt-5">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                            Delete Guard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection