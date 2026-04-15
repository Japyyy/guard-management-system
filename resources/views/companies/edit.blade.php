@extends('layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-4xl flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Edit Company</h2>

            <a href="{{ route('companies.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Back
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 md:p-5 shadow-sm">
            <form action="{{ route('companies.update', $company) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                @csrf
                @method('PUT')

                @include('companies.partials.form', ['company' => $company])
            </form>
        </div>
    </div>
@endsection