<?php

namespace App\Console\Commands;

use App\Mail\LicenseExpiryMail;
use App\Models\Guard;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLicenseExpiryEmails extends Command
{
    protected $signature = 'licenses:send-expiry-emails';
    protected $description = 'Send license expiry memo emails to HR for guards hitting 60-day and 30-day thresholds';

    public function handle(): int
    {
        $today = Carbon::today();
        $hrEmail = config('mail.hr_email');

        if (!$hrEmail) {
            $this->error('HR email is not configured. Set MAIL_HR_EMAIL in your .env file.');
            return self::FAILURE;
        }

        $this->info('HR Email: ' . $hrEmail);
        $this->info('Today: ' . $today->format('Y-m-d'));

        $guards = Guard::query()->get();

        if ($guards->isEmpty()) {
            $this->warn('No guards found in the database.');
            return self::SUCCESS;
        }

        $sentAny = false;

        foreach ($guards as $guard) {
            if (!$guard->license_validity_date) {
                $this->warn("Skipping {$guard->full_name}: no license_validity_date");
                continue;
            }

            $daysLeft = (int) $today->diffInDays($guard->license_validity_date, false);

            $this->line(
                "{$guard->full_name} | Expiry: {$guard->license_validity_date->format('Y-m-d')} | Days Left: {$daysLeft} | 60 Sent: " .
                ($guard->notified_60_days ? 'yes' : 'no') .
                " | 30 Sent: " .
                ($guard->notified_30_days ? 'yes' : 'no')
            );

            if ($daysLeft == 60 && !$guard->notified_60_days) {
                $this->info("Matched 60-day rule for {$guard->full_name}");

                Mail::to($hrEmail)->send(new LicenseExpiryMail($guard, 60));

                $guard->notified_60_days = true;
                $guard->save();

                $this->info("60-day memo sent for {$guard->full_name}");
                $sentAny = true;
            }

            if ($daysLeft == 30 && !$guard->notified_30_days) {
                $this->info("Matched 30-day rule for {$guard->full_name}");

                Mail::to($hrEmail)->send(new LicenseExpiryMail($guard, 30));

                $guard->notified_30_days = true;
                $guard->save();

                $this->info("30-day memo sent for {$guard->full_name}");
                $sentAny = true;
            }
        }

        if (!$sentAny) {
            $this->warn('No emails were sent. No guards matched exactly 60 or 30 days.');
        } else {
            $this->info('Finished sending expiry emails.');
        }

        return self::SUCCESS;
    }
}