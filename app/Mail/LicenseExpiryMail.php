<?php

namespace App\Mail;

use App\Models\Guard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LicenseExpiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public Guard $guard;
    public int $type;
    public string $date;
    public string $expirationDate;

    public function __construct(Guard $guard, int $type)
    {
        $this->guard = $guard;
        $this->type = $type;
        $this->date = now()->format('F d, Y');
        $this->expirationDate = $guard->license_validity_date->format('F d, Y');
    }

    public function build()
    {
        $subject = $this->type === 30
            ? 'URGENT: Security Guard License Expiring in 30 Days'
            : 'Notice: Security Guard License Expiring in 60 Days';

        $logoPath = public_path('images/logo.png');

        $pdf = Pdf::loadView('pdf.license-memo', [
            'guard' => $this->guard,
            'date' => $this->date,
            'expiration_date' => $this->expirationDate,
            'logoPath' => file_exists($logoPath) ? $logoPath : null,
        ])->setPaper('a4', 'portrait');

        return $this->subject($subject)
            ->view('emails.license-expiry-message')
            ->with([
                'guard' => $this->guard,
                'type' => $this->type,
                'date' => $this->date,
                'expirationDate' => $this->expirationDate,
            ])
            ->attachData(
                $pdf->output(),
                'License-Memo-' . $this->guard->last_name . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
