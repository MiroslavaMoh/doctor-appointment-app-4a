<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature   = 'appointments:send-reminders';
    protected $description = 'Envía recordatorios de citas por WhatsApp un día antes de la cita';

    public function handle(WhatsAppService $whatsApp): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('status', Appointment::STATUS_PROGRAMADO)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No hay citas programadas para {$tomorrow}.");
            return;
        }

        foreach ($appointments as $appointment) {
            $phone = $appointment->patient->user->phone ?? null;

            if (!$phone) {
                $this->warn("Paciente ID {$appointment->patient_id} no tiene teléfono registrado. Omitido.");
                continue;
            }

            $message = $this->buildReminderMessage($appointment);
            $whatsApp->send($phone, $message);

            $this->info("Recordatorio enviado a {$appointment->patient->user->name} ({$phone}).");
        }
    }

    private function buildReminderMessage(Appointment $appointment): string
    {
        $patient    = $appointment->patient->user->name;
        $doctor     = $appointment->doctor->user->name;
        $date       = $appointment->date->format('d/m/Y');
        $startTime  = $appointment->start_time;

        return "Hola {$patient}, le recordamos que mañana tiene una cita médica programada:\n\n"
            . "🩺 *Doctor:* {$doctor}\n"
            . "📅 *Fecha:* {$date}\n"
            . "🕐 *Hora:* {$startTime}\n\n"
            . "Por favor confirme su asistencia o contáctenos si necesita reprogramar.\n"
            . "Sistema de Gestión Médica";
    }
}
