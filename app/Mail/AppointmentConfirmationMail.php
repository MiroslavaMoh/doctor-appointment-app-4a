<?php

namespace App\Mail;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Appointment  $appointment
     * @param  string       $recipientType  'patient' | 'doctor'
     */
    public function __construct(
        public Appointment $appointment,
        public string $recipientType = 'patient'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'doctor'
            ? 'Nueva cita agendada – ' . $this->appointment->patient->user->name
            : 'Confirmación de su cita médica';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.appointment-confirmation',
            with: [
                'appointment'   => $this->appointment,
                'recipientType' => $this->recipientType,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.appointment-confirmation', [
            'appointment' => $this->appointment,
        ]);

        $filename = 'comprobante-cita-' . str_pad($this->appointment->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}