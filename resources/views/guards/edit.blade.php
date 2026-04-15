@extends('layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-5xl flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Edit Guard</h2>

            <a href="{{ route('guards.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Back
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 md:p-5 shadow-sm">
            <form action="{{ route('guards.update', $guard) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                @csrf
                @method('PUT')

                @include('guards.partials.form', ['guard' => $guard, 'companies' => $companies])
            </form>
        </div>
    </div>
@endsection