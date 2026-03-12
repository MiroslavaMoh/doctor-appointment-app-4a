<?php

namespace App\Livewire\Admin\DataTables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Doctors;

class DoctorTable extends DataTableComponent
{
    protected $model = Doctors::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            /*Column::make("User id", "user_id")
                ->sortable(),
            Column::make("Speciality id", "speciality_id")
                ->sortable(),
            Column::make("Medical license number", "medical_license_number")
                ->sortable(),
            Column::make("Biography", "biography")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),*/

            Column::make("Nombre del doctor", "user.name")
                ->sortable(),

            Column::make("Especialidad", "speciality.name")
                ->sortable()
                ->format(fn($value) => $value ?? 'N/A'), //Logica pequeña de campo
            Column::make("Número de licencia médica", "medical_license_number")
                ->sortable()
                ->format(fn($value) => $value ?? 'N/A'),


            /*Column::make("Email del doctor", "user.email")
                ->sortable(),
            Column::make("Número de id", "user.id_number")
                ->sortable(),
            Column::make("Teléfono", "user.phone")
                ->sortable(),*/
            
             Column::make("Acciones")
                ->label(fn($row) => view('admin.doctors.actions', ['doctor' => $row])),  
        ];
    }
}
