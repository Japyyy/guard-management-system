@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Companies</h1>
            <p class="text-gray-600">Manage client companies and deployments.</p>
        </div>

        <a href="{{ route('companies.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Company
        </a>
    </div>

    <div class="bg-white border rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Company Name</th>
                        <th class="px-4 py-3 text-left">Address</th>
                        <th class="px-4 py-3 text-left">Assigned Guards</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $company->company_name }}</td>
                            <td class="px-4 py-3">{{ $company->address ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $company->guards_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('companies.show', $company) }}" class="px-3 py-1 bg-slate-600 text-white rounded hover:bg-slate-700">View</a>
                                    <a href="{{ route('companies.edit', $company) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                    <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? This will also delete assigned guards.')">
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
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $companies->links() }}
        </div>
    </div>
@endsection