<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guard extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'last_name',
        'first_name',
        'middle_name',
        'civil_status',
        'birthdate',
        'date_hired',
        'license_number',
        'license_validity_date',
        'sss_number',
        'philhealth_number',
        'pagibig_number',
        'tin_number',
        'nbi_clearance_date',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_hired' => 'date',
        'license_validity_date' => 'date',
        'nbi_clearance_date' => 'date',
    ];

    protected $appends = [
        'full_name',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->last_name . ', ' .
            $this->first_name .
            ($this->middle_name ? ' ' . $this->middle_name : '')
        );
    }

    public function getStatusAttribute(): string
    {
        $today = Carbon::today();

        if ($this->license_validity_date->lt($today)) {
            return 'Expired';
        }

        if ($this->license_validity_date->between($today, $today->copy()->addMonths(3))) {
            return 'Expiring';
        }

        return 'Active';
    }

    public function scopeActive($query)
    {
        return $query->whereDate('license_validity_date', '>=', Carbon::today());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('license_validity_date', '<', Carbon::today());
    }

    public function scopeExpiring($query)
    {
        return $query->whereBetween('license_validity_date', [
            Carbon::today(),
            Carbon::today()->copy()->addMonths(3),
        ]);
    }
}