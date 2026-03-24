<?php

namespace App\Livewire\Admin;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\Patient;
use App\Models\Speciality;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class AppointmentBooking extends Component
{
    // Search criteria
    public string $searchDate      = '';
    public string $searchTimeStart = '08:00';
    public string $searchTimeEnd   = '09:00';
    public ?int   $searchSpecialityId = null;

    // Search results: [{doctor_id, name, speciality, initials, slots}]
    public array $results  = [];
    public bool  $searched = false;

    // Selected slot
    public ?int   $selectedDoctorId   = null;
    public string $selectedDoctorName = '';
    public string $selectedTime       = '';

    // Confirmation form
    public ?int   $patientId = null;
    public string $reason    = '';

    public function mount(): void
    {
        $this->searchDate = now()->format('Y-m-d');
    }

    public function search(): void
    {
        $this->validate(['searchDate' => 'required|date']);

        $date      = Carbon::parse($this->searchDate);
        $dayOfWeek = $date->dayOfWeekIso; // 1=Mon … 7=Sun

        $query = Doctors::with(['user', 'speciality',
            'schedules' => fn($q) => $q->where('day_of_week', $dayOfWeek),
        ])->whereHas('schedules', fn($q) => $q->where('day_of_week', $dayOfWeek));

        if ($this->searchSpecialityId) {
            $query->where('speciality_id', $this->searchSpecialityId);
        }

        $doctors = $query->get();

        // Booked start_times for this date, grouped by doctor_id
        $bookedSlots = Appointment::where('date', $this->searchDate)
            ->get(['doctor_id', 'start_time'])
            ->groupBy('doctor_id')
            ->map(fn($appts) => $appts->pluck('start_time')
                ->map(fn($t) => substr($t, 0, 5))
                ->toArray()
            );

        $this->results = [];

        foreach ($doctors as $doctor) {
            $slots = [];

            foreach ($doctor->schedules as $schedule) {
                $cursor = Carbon::parse($schedule->start_time);
                $end    = Carbon::parse($schedule->end_time);

                while ($cursor < $end) {
                    $slotStr = $cursor->format('H:i');

                    $inRange = $slotStr >= $this->searchTimeStart
                               && $slotStr < $this->searchTimeEnd;

                    $booked = isset($bookedSlots[$doctor->id])
                              && in_array($slotStr, $bookedSlots[$doctor->id]);

                    if ($inRange && !$booked) {
                        $slots[] = $slotStr;
                    }

                    $cursor->addMinutes(15);
                }
            }

            if (!empty($slots)) {
                $words    = explode(' ', $doctor->user->name);
                $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));

                $this->results[] = [
                    'doctor_id'  => $doctor->id,
                    'name'       => $doctor->user->name,
                    'speciality' => $doctor->speciality?->name ?? 'Sin especialidad',
                    'initials'   => $initials,
                    'slots'      => $slots,
                ];
            }
        }

        $this->searched = true;
        // Reset selection when searching again
        $this->selectedDoctorId   = null;
        $this->selectedDoctorName = '';
        $this->selectedTime       = '';
    }

    public function selectSlot(int $doctorId, string $doctorName, string $time): void
    {
        $this->selectedDoctorId   = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->selectedTime       = $time;
    }

    public function confirm(): void
    {
        $this->validate([
            'selectedDoctorId' => 'required|integer|exists:doctors,id',
            'selectedTime'     => 'required|string',
            'searchDate'       => 'required|date|after_or_equal:today',
            'patientId'        => 'required|integer|exists:patients,id',
            'reason'           => 'nullable|string|max:1000',
        ]);

        $start = Carbon::parse($this->selectedTime);
        $end   = $start->copy()->addMinutes(15);

        $appointment = Appointment::create([
            'patient_id' => $this->patientId,
            'doctor_id'  => $this->selectedDoctorId,
            'date'       => $this->searchDate,
            'start_time' => $start->format('H:i'),
            'end_time'   => $end->format('H:i'),
            'duration'   => 15,
            'reason'     => $this->reason,
            'status'     => Appointment::STATUS_PROGRAMADO,
        ]);

        $this->sendWhatsAppConfirmation($appointment);
        $this->sendEmailConfirmation($appointment);

        session()->flash('success', 'Cita registrada correctamente.');

        $this->redirect(route('admin.appointments.index'), navigate: true);
    }

    private function sendWhatsAppConfirmation(Appointment $appointment): void
    {
        $appointment->load('patient.user', 'doctor.user');

        $phone = $appointment->patient->user->phone ?? null;
        if (!$phone) return;

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

    private function sendEmailConfirmation(Appointment $appointment): void
    {
        $appointment->load('patient.user', 'doctor.user', 'doctor.speciality');

        $patientEmail = $appointment->patient->user->email ?? null;
        if ($patientEmail) {
            try {
                Mail::to($patientEmail)->send(new AppointmentConfirmationMail($appointment, 'patient'));
            } catch (\Throwable $e) {
                \Log::error('Error enviando correo al paciente (Livewire)', ['error' => $e->getMessage()]);
            }
        }

        $doctorEmail = $appointment->doctor->user->email ?? null;
        if ($doctorEmail) {
            try {
                Mail::to($doctorEmail)->send(new AppointmentConfirmationMail($appointment, 'doctor'));
            } catch (\Throwable $e) {
                \Log::error('Error enviando correo al doctor (Livewire)', ['error' => $e->getMessage()]);
            }
        }
    }

    public function render()
    {
        $specialities = Speciality::orderBy('name')->get();
        $patients     = Patient::with('user')->get();

        // Generate 1-hour time-range options from 06:00 to 20:00
        $timeRanges = [];
        $cursor = Carbon::createFromTime(6, 0);
        $limit  = Carbon::createFromTime(20, 0);
        while ($cursor < $limit) {
            $next = $cursor->copy()->addHour();
            $timeRanges[$cursor->format('H:i')] = $cursor->format('H:i:s') . ' - ' . $next->format('H:i:s');
            $cursor->addHour();
        }

        return view('livewire.admin.appointment-booking',
            compact('specialities', 'patients', 'timeRanges')
        );
    }
}
