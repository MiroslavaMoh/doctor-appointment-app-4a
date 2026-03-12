<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public bool $mine = false;

    public function mount(bool $mine = false): void
    {
        $this->mine = $mine;
    }

    public function builder(): Builder
    {
        $query = Appointment::query()->with('patient.user', 'doctor.user');

        if ($this->mine) {
            $patient = auth()->user()->patient;
            $query->where('patient_id', $patient?->id ?? 0);
        }

        return $query;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Paciente', 'patient.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Doctor', 'doctor.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y')),

            Column::make('Hora inicio', 'start_time')
                ->sortable()
                ->format(fn($value) => substr($value, 0, 5)),

            Column::make('Hora fin', 'end_time')
                ->sortable()
                ->format(fn($value) => substr($value, 0, 5)),

            Column::make('Estado', 'status')
                ->sortable()
                ->format(fn($value) => match ((int) $value) {
                    1 => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Programado</span>',
                    2 => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Completado</span>',
                    3 => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>',
                    default => $value,
                })
                ->html(),

            Column::make('Acciones')
                ->label(fn($row) => view('admin.appointments.actions', ['appointment' => $row])),
        ];
    }
}
