@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Add Company</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Create a new company or deployment location for personnel assignment.
                </p>
            </div>

            <a href="{{ route('companies.index') }}"
               class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>
        </div>

        <div class="rounded-[30px] border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 md:p-6 shadow-sm">
            <form action="{{ route('companies.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                @csrf
                @include('companies.partials.form', ['company' => null])
            </form>
        </div>
    </div>
@endsection