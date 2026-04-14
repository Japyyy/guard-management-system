@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Company Profile</h2>
                <p class="mt-1 text-sm text-slate-500">
                    View company details and the personnel currently assigned to this deployment.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('companies.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Back to List
                </a>

                <a href="{{ route('companies.edit', $company) }}"
                   class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Edit Company
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-8">
                <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm h-full">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-600">Company Record</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $company->company_name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">Deployment / Client Company</p>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-xs font-semibold text-blue-700">
                            {{ $company->guards->count() }} Guard{{ $company->guards->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Company Name</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $company->company_name }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assigned Guards</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $company->guards->count() }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 md:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $company->address ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-4">
                <div class="rounded-[30px] border border-red-200 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-red-700">Danger Zone</h3>
                    <p class="mt-3 text-sm text-red-700">
                        Deleting this company may also delete its assigned guards if cascade delete is enabled.
                    </p>

                    <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? This may also delete assigned guards.')" class="mt-5">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                            Delete Company
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Assigned Guards</h3>
                    <p class="mt-1 text-sm text-slate-500">Personnel currently assigned to this company.</p>
                </div>

                <div class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                    {{ $company->guards->count() }} record{{ $company->guards->count() !== 1 ? 's' : '' }}
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
                            <tr class="hover:bg-slate-50/90 transition">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $guard->full_name }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->civil_status }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->birthdate?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->date_hired?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->license_number }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $guard->license_validity_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($guard->status === 'Active')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>
                                    @elseif($guard->status === 'Expired')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Expired
                                        </span>
                                    @elseif($guard->status === 'Expiring in 30 Days')
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Expiring in 30 Days
                                        </span>
                                    @elseif($guard->status === 'Expiring in 60 Days')
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Expiring in 60 Days
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $guard->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-slate-500">
                                    No guards assigned to this company yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection