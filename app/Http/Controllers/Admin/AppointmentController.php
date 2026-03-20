<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index', ['mine' => false]);
    }

    public function myAppointments()
    {
        return view('admin.appointments.index', ['mine' => true]);
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'duration'   => 'nullable|integer|min:1',
            'reason'     => 'nullable|string|max:1000',
        ]);

        $data['status'] = Appointment::STATUS_PROGRAMADO;

        if (empty($data['duration'])) {
            [$sh, $sm] = explode(':', $data['start_time']);
            [$eh, $em] = explode(':', $data['end_time']);
            $data['duration'] = ((int)$eh * 60 + (int)$em) - ((int)$sh * 60 + (int)$sm);
        }

        $appointment = Appointment::create($data);

        $this->sendConfirmation($appointment);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita registrada correctamente.');
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors  = Doctors::with('user')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'duration'   => 'nullable|integer|min:1',
            'reason'     => 'nullable|string|max:1000',
            'status'     => 'required|integer|in:1,2,3',
        ]);

        $appointment->update($data);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita eliminada correctamente.');
    }

    public function consult(Appointment $appointment)
    {
        $appointment->load('patient.user', 'doctor.user', 'consultation.medications');

        return view('admin.appointments.consult', compact('appointment'));
    }

    private function sendConfirmation(Appointment $appointment): void
    {
        $appointment->load('patient.user', 'doctor.user');

        $phone = $appointment->patient->user->phone ?? null;

        \Log::info('sendConfirmation llamado', [
            'appointment_id' => $appointment->id,
            'patient'        => $appointment->patient->user->name ?? 'NULL',
            'phone'          => $phone ?? 'NULL',
            'twilio_sid'     => config('services.twilio.sid') ? 'presente' : 'VACÍO',
            'twilio_token'   => config('services.twilio.token') ? 'presente' : 'VACÍO',
            'twilio_from'    => config('services.twilio.whatsapp_from') ?? 'VACÍO',
        ]);

        if (!$phone) {
            \Log::warning('sendConfirmation: no hay teléfono, mensaje no enviado.');
            return;
        }

        $patient   = $appointment->patient->user->name;
        $doctor    = $appointment->doctor->user->name;
        $date      = $appointment->date->format('d/m/Y');
        $startTime = $appointment->start_time;

        $message = "¡Hola {$patient}! Su cita médica ha sido registrada exitosamente.\n\n"
            . "🩺 *Doctor:* {$doctor}\n"
            . "📅 *Fecha:* {$date}\n"
            . "🕐 *Hora:* {$startTime}\n\n"
            . "Le enviaremos un recordatorio el día anterior a su cita.\n"
            . "Sistema de Gestión Médica";

        app(WhatsAppService::class)->send($phone, $message);
    }
}
