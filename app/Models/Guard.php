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
        'address',
        'license_number',
        'license_validity_date',
        'sss_number',
        'philhealth_number',
        'pagibig_number',
        'tin_number',
        'nbi_clearance_date',
        'notified_60_days',
        'notified_30_days',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_hired' => 'date',
        'license_validity_date' => 'date',
        'nbi_clearance_date' => 'date',
        'notified_60_days' => 'boolean',
        'notified_30_days' => 'boolean',
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
        $validity = $this->license_validity_date;

        if (!$validity) {
            return 'No License Date';
        }

        if ($validity->lt($today)) {
            return 'Expired';
        }

        if ($validity->lte($today->copy()->addDays(30))) {
            return 'Expiring in 30 Days';
        }

        if ($validity->lte($today->copy()->addDays(60))) {
            return 'Expiring in 60 Days';
        }

        return 'Active';
    }

    public function scopeActive($query)
    {
        return $query->whereDate('license_validity_date', '>', Carbon::today()->copy()->addDays(60));
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('license_validity_date', '<', Carbon::today());
    }

    public function scopeExpiringIn30Days($query)
    {
        return $query->whereBetween('license_validity_date', [
            Carbon::today(),
            Carbon::today()->copy()->addDays(30),
        ]);
    }

    public function scopeExpiringIn60Days($query)
    {
        return $query->whereBetween('license_validity_date', [
            Carbon::today()->copy()->addDays(31),
            Carbon::today()->copy()->addDays(60),
        ]);
    }

    public function scopeExpiring($query)
    {
        return $query->whereBetween('license_validity_date', [
            Carbon::today(),
            Carbon::today()->copy()->addDays(60),
        ]);
    }
}
