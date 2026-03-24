<?php

namespace App\Console\Commands;

use App\Mail\DailyAppointmentsReportMail;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyAppointmentsReport extends Command
{
    protected $signature   = 'appointments:daily-report';
    protected $description = 'Envía el reporte diario de citas al administrador y a cada doctor con sus pacientes agendados para hoy.';

    public function handle(): int
    {
        $today = now()->toDateString();

        // Load all appointments for today with related data
        $todayAppointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->whereDate('date', $today)
            ->where('status', Appointment::STATUS_PROGRAMADO)
            ->orderBy('start_time')
            ->get();

        $this->info("Citas encontradas para hoy ({$today}): {$todayAppointments->count()}");

        // --- 1. Send report to admin(s) ---
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));

        if ($adminEmail) {
            Mail::to($adminEmail)->send(
                new DailyAppointmentsReportMail($todayAppointments, 'admin', 'Administrador')
            );
            $this->info("Reporte enviado al administrador: {$adminEmail}");
        } else {
            $this->warn('ADMIN_EMAIL no está configurado en .env – reporte de admin omitido.');
        }

        // Send to all users with role 'admin' found in DB
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if ($admin->email && $admin->email !== $adminEmail) {
                Mail::to($admin->email)->send(
                    new DailyAppointmentsReportMail($todayAppointments, 'admin', $admin->name)
                );
                $this->info("Reporte enviado a admin: {$admin->email}");
            }
        }

        // --- 2. Send report to each doctor with their own appointments ---
        $appointmentsByDoctor = $todayAppointments->groupBy('doctor_id');

        foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
            $doctor = $doctorAppointments->first()->doctor;
            $email  = $doctor->user->email ?? null;

            if (!$email) {
                $this->warn("Doctor ID {$doctorId} no tiene correo registrado – omitido.");
                continue;
            }

            Mail::to($email)->send(
                new DailyAppointmentsReportMail(
                    collect($doctorAppointments->values()->all()),
                    'doctor',
                    $doctor->user->name
                )
            );

            $this->info("Reporte enviado a Dr. {$doctor->user->name} ({$email}): {$doctorAppointments->count()} paciente(s)");
        }

        $this->info('Reporte diario enviado correctamente.');

        return self::SUCCESS;
    }
}
