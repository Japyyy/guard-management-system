@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add Company</h1>
            <p class="text-sm text-slate-500 mt-1">
                Create a new company or deployment location for assigning security guards.
            </p>
        </div>

        <a href="{{ route('companies.index') }}"
           class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
            Back to List
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 md:p-6">
        <form action="{{ route('companies.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            @csrf
            @include('companies.partials.form', ['company' => null])
        </form>
    </div>
@endsection