@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Guards</h1>
            <p class="text-gray-600">Manage all security guards.</p>
        </div>

        <a href="{{ route('guards.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Guard
        </a>
    </div>

    <form method="GET" action="{{ route('guards.index') }}" class="bg-white border rounded-xl shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name or license no."
                   class="w-full rounded-lg border-gray-300">

            <select name="status" class="w-full rounded-lg border-gray-300">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring</option>
            </select>

            <select name="company_id" class="w-full rounded-lg border-gray-300">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900">
                    Filter
                </button>
                <a href="{{ route('guards.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Full Name</th>
                        <th class="px-4 py-3 text-left">Company</th>
                        <th class="px-4 py-3 text-left">License No.</th>
                        <th class="px-4 py-3 text-left">License Validity</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guards as $guard)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $guard->full_name }}</td>
                            <td class="px-4 py-3">{{ $guard->company->company_name }}</td>
                            <td class="px-4 py-3">{{ $guard->license_number }}</td>
                            <td class="px-4 py-3">{{ $guard->license_validity_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($guard->status === 'Active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                                @elseif($guard->status === 'Expired')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Expired</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Expiring</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('guards.show', $guard) }}" class="px-3 py-1 bg-slate-600 text-white rounded hover:bg-slate-700">View</a>
                                    <a href="{{ route('guards.edit', $guard) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                    <form action="{{ route('guards.destroy', $guard) }}" method="POST" onsubmit="return confirm('Delete this guard?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No guards found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $guards->links() }}
        </div>
    </div>
@endsection