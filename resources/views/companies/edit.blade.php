@extends('layouts.app')

@section('content')
    <div class="rounded-[34px] border border-white/80 bg-white/90 shadow-[0_20px_60px_rgba(15,23,42,0.08)] overflow-hidden">
        <div class="px-6 py-6 border-b border-slate-200 bg-[linear-gradient(180deg,#ffffff_0%,#f8fbff_100%)] flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-600">Update Client</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Edit Company</h2>
                <p class="mt-1 text-sm text-slate-500">Update company profile and deployment details.</p>
            </div>

            <a href="{{ route('companies.index') }}"
               class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>
        </div>

        <div class="p-6 md:p-8 bg-[linear-gradient(180deg,#fcfdff_0%,#f5f8fc_100%)]">
            <form action="{{ route('companies.update', $company) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                @csrf
                @method('PUT')
                @include('companies.partials.form', ['company' => $company])
            </form>
        </div>
    </div>
@endsection