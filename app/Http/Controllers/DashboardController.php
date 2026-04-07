<?php

namespace App\Http\Controllers;

use App\Models\Guard;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuards = Guard::count();
        $activeGuards = Guard::active()->count();
        $expiredLicenses = Guard::expired()->count();
        $expiringLicenses = Guard::expiring()->count();

        return view('dashboard', compact(
            'totalGuards',
            'activeGuards',
            'expiredLicenses',
            'expiringLicenses'
        ));
    }
}