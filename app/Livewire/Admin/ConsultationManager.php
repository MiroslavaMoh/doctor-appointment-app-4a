<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Consultation;
use Livewire\Component;

class ConsultationManager extends Component
{
    public int $appointmentId;

    // Consulta tab
    public string $diagnosis = '';
    public string $treatment = '';
    public string $notes     = '';

    // Receta tab — each item: ['name', 'dose', 'frequency']
    public array $medications = [];

    // Modals
    public bool $showPastModal    = false;
    public bool $showHistoryModal = false;

    public function mount(Appointment $appointment): void
    {
        $this->appointmentId = $appointment->id;

        if ($appointment->consultation) {
            $this->diagnosis = $appointment->consultation->diagnosis ?? '';
            $this->treatment = $appointment->consultation->treatment ?? '';
            $this->notes     = $appointment->consultation->notes ?? '';

            $this->medications = $appointment->consultation->medications
                ->map(fn($m) => [
                    'name'      => $m->name,
                    'dose'      => $m->dose,
                    'frequency' => trim(implode(' ', array_filter([$m->frequency, $m->duration]))),
                ])
                ->toArray();
        }
    }

    public function addMedication(): void
    {
        $this->medications[] = ['name' => '', 'dose' => '', 'frequency' => ''];
    }

    public function removeMedication(int $index): void
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function saveConsultation(): mixed
    {
        $this->validate([
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes'     => 'nullable|string',
        ]);

        $appointment = Appointment::find($this->appointmentId);

        $consultation = Consultation::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'notes'     => $this->notes,
            ]
        );

        $consultation->medications()->delete();
        foreach ($this->medications as $med) {
            if (!empty($med['name'])) {
                $consultation->medications()->create([
                    'name'      => $med['name'],
                    'dose'      => $med['dose'] ?? '',
                    'frequency' => $med['frequency'] ?? '',
                    'duration'  => '',
                ]);
            }
        }

        $appointment->update(['status' => Appointment::STATUS_COMPLETADO]);

        session()->flash('success', 'Consulta guardada correctamente.');

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $appointment = Appointment::with([
            'patient.user',
            'patient.bloodType',
            'doctor.user',
            'consultation.medications',
        ])->find($this->appointmentId);

        $pastConsultations = Consultation::with(['appointment.doctor.user'])
            ->whereHas('appointment', fn($q) =>
                $q->where('patient_id', $appointment->patient_id)
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.consultation-manager', compact('appointment', 'pastConsultations'));
    }
}
