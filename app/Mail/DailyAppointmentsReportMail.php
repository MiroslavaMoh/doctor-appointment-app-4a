<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyAppointmentsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Collection  $appointments  Appointments for the report
     * @param  string      $recipientType 'admin' | 'doctor'
     * @param  string      $recipientName Name of recipient for greeting
     */
    public function __construct(
        public Collection $appointments,
        public string $recipientType = 'admin',
        public string $recipientName = 'Administrador'
    ) {}

    public function envelope(): Envelope
    {
        $date    = now()->format('d/m/Y');
        $subject = $this->recipientType === 'doctor'
            ? "Pacientes agendados para hoy ({$date})"
            : "Reporte diario de citas – {$date}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.daily-appointments-report',
            with: [
                'appointments'  => $this->appointments,
                'recipientType' => $this->recipientType,
                'recipientName' => $this->recipientName,
                'reportDate'    => now()->format('d/m/Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
