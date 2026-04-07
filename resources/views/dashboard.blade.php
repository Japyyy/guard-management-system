@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Overview of security guard records and license status.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border">
            <h2 class="text-sm font-medium text-gray-500">Total Guards</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalGuards }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border">
            <h2 class="text-sm font-medium text-gray-500">Active Guards</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $activeGuards }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border">
            <h2 class="text-sm font-medium text-gray-500">Expired Licenses</h2>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $expiredLicenses }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border">
            <h2 class="text-sm font-medium text-gray-500">Expiring in 3 Months</h2>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $expiringLicenses }}</p>
        </div>
    </div>
@endsection