<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\DoctorSchedule;
use App\Models\Speciality;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentCreate extends Component
{
    public string $date = '';
    public string $time = '';
    public string $speciality_id = '';

    public bool $searched = false;
    public array $availableDoctors = [];

    protected $rules = [
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required|date_format:H:i',
    ];

    protected $messages = [
        'date.required'         => 'La fecha es obligatoria.',
        'date.date'             => 'Ingresa una fecha válida.',
        'date.after_or_equal'   => 'La fecha debe ser hoy o en el futuro.',
        'time.required'         => 'La hora es obligatoria.',
        'time.date_format'      => 'Ingresa una hora válida.',
    ];

    public function search(): void
    {
        $this->validate();

        $dayOfWeek = Carbon::parse($this->date)->dayOfWeekIso; // 1=Lun, 7=Dom
        $startTime = $this->time . ':00';

        // Doctors with a schedule slot at that day + time
        $scheduledDoctorIds = DoctorSchedule::where('day_of_week', $dayOfWeek)
            ->where('start_time', $startTime)
            ->pluck('doctor_id');

        // Doctors already booked at that date + time (non-cancelled)
        $busyDoctorIds = Appointment::where('appointment_date', $this->date)
            ->where('start_time', $startTime)
            ->whereIn('status', ['programado', 'completado'])
            ->pluck('doctor_id');

        $query = Doctors::whereIn('id', $scheduledDoctorIds)
            ->whereNotIn('id', $busyDoctorIds)
            ->with('user', 'speciality');

        if ($this->speciality_id) {
            $query->where('speciality_id', $this->speciality_id);
        }

        $this->availableDoctors = $query->get()->map(fn($d) => [
            'id'         => $d->id,
            'name'       => $d->user->name,
            'speciality' => $d->speciality?->name ?? 'Sin especialidad',
            'photo'      => $d->user->profile_photo_url,
            'license'    => $d->medical_license_number ?? 'N/A',
        ])->toArray();

        $this->searched = true;
    }

    public function confirm(int $doctorId): mixed
    {
        $patient = auth()->user()->patient;

        if (!$patient) {
            $this->addError('general', 'Tu cuenta no tiene un perfil de paciente asociado.');
            return null;
        }

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', Carbon::parse($this->date)->dayOfWeekIso)
            ->where('start_time', $this->time . ':00')
            ->first();

        $appointment = Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $doctorId,
            'appointment_date' => $this->date,
            'start_time'       => $this->time . ':00',
            'end_time'         => $schedule->end_time ?? $this->time . ':00',
            'status'           => 'programado',
        ]);

        $this->sendWhatsAppConfirmation($appointment);

        session()->flash('success', 'Cita creada correctamente.');

        return redirect()->route('admin.appointments.index');
    }

    private function sendWhatsAppConfirmation(Appointment $appointment): void
    {
        $appointment->load('patient.user', 'doctor.user');

        $phone = $appointment->patient->user->phone ?? null;
        if (!$phone) return;

        $patient   = $appointment->patient->user->name;
        $doctor    = $appointment->doctor->user->name;
        $date      = Carbon::parse($appointment->appointment_date ?? $appointment->date)->format('d/m/Y');
        $startTime = $appointment->start_time;

        $message = "¡Hola {$patient}! Su cita médica ha sido registrada exitosamente.\n\n"
            . "🩺 *Doctor:* {$doctor}\n"
            . "📅 *Fecha:* {$date}\n"
            . "🕐 *Hora:* {$startTime}\n\n"
            . "Le enviaremos un recordatorio el día anterior a su cita.\n"
            . "Sistema de Gestión Médica";

        app(WhatsAppService::class)->send($phone, $message);
    }

    public function render()
    {
        $specialities = Speciality::orderBy('name')->get();

        // Generate 15-minute time slots 08:00 – 19:45
        $timeSlots = collect(range(8, 19))->flatMap(
            fn($h) => collect([0, 15, 30, 45])->map(fn($m) => sprintf('%02d:%02d', $h, $m))
        );

        return view('livewire.admin.appointment-create', compact('specialities', 'timeSlots'));
    }
}
