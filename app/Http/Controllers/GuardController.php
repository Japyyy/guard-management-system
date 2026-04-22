<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Guard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuardController extends Controller
{
    public function index(Request $request)
    {
        $query = Guard::with('company');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $today = now()->startOfDay()->toDateString();
            $plus30 = now()->startOfDay()->addDays(30)->toDateString();
            $plus31 = now()->startOfDay()->addDays(31)->toDateString();
            $plus60 = now()->startOfDay()->addDays(60)->toDateString();

            switch ($request->status) {
                case 'active':
                    $query->whereDate('license_validity_date', '>', $plus60);
                    break;

                case 'expired':
                    $query->whereDate('license_validity_date', '<', $today);
                    break;

                case 'expiring_30':
                    $query->whereDate('license_validity_date', '>=', $today)
                        ->whereDate('license_validity_date', '<=', $plus30);
                    break;

                case 'expiring_60':
                    $query->whereDate('license_validity_date', '>=', $plus31)
                        ->whereDate('license_validity_date', '<=', $plus60);
                    break;
            }
        }

        $guards = $query->latest()->get();
        $companies = Company::orderBy('company_name')->get();

        return view('guards.index', compact('guards', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('company_name')->get();

        return view('guards.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'date_hired' => ['required', 'date'],
            'license_number' => ['required', 'string', 'max:255', 'unique:guards,license_number'],
            'license_validity_date' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'nbi_clearance_date' => ['nullable', 'date'],
        ]);

        Guard::create($validated);

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard created successfully.');
    }

    public function show(Guard $guard)
    {
        $guard->load('company');

        return view('guards.show', compact('guard'));
    }

    public function edit(Guard $guard)
    {
        $companies = Company::orderBy('company_name')->get();

        return view('guards.edit', compact('guard', 'companies'));
    }

    public function update(Request $request, Guard $guard)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'date_hired' => ['required', 'date'],
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('guards', 'license_number')->ignore($guard->id),
            ],
            'license_validity_date' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'nbi_clearance_date' => ['nullable', 'date'],
        ]);

        $guard->update($validated);

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard updated successfully.');
    }

    public function destroy(Guard $guard)
    {
        $guard->delete();

        return redirect()
            ->route('guards.index')
            ->with('success', 'Guard deleted successfully.');
    }
}