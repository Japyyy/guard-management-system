<?php

namespace App\Http\Controllers;

use App\Models\Guard;

class DashboardController extends Controller
{
    public function index()
    {
        $guards = Guard::with('company')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($guard) {
                return [
                    'id' => $guard->id,
                    'full_name' => $guard->full_name,
                    'birthdate' => optional($guard->birthdate)->format('Y-m-d'),
                    'license_validity_date' => optional($guard->license_validity_date)->format('Y-m-d'),
                    'company_name' => optional($guard->company)->company_name,
                    'notified_60_days' => (bool) $guard->notified_60_days,
                    'notified_30_days' => (bool) $guard->notified_30_days,
                ];
            });

        return view('dashboard', [
            'guardsJson' => $guards->values()->toJson(),
            'totalGuards' => $guards->count(),
        ]);
    }
}
