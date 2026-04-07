@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add Guard</h1>
            <p class="text-sm text-slate-500 mt-1">
                Fill in the guard's information below. Required fields should be completed before saving.
            </p>
        </div>

        <a href="{{ route('guards.index') }}"
           class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
            Back to List
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 md:p-6">
        <form action="{{ route('guards.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            @csrf
            @include('guards.partials.form', ['guard' => null])
        </form>
    </div>
@endsection