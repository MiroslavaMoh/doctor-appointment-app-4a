<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientsTable extends DataTableComponent
{
    //protected $model = Patient::class;

    //metodo define el modelo
    public function builder(): Builder{
        return Patient::query()
            ->with('user','bloodType');
    }


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
            Column::make("Blood type", "blood_type_id")
                ->sortable(),
            Column::make("Allergies", "allergies")
                ->sortable(),
            Column::make("Chronic conditions", "chronic_conditions")
                ->sortable(),
            Column::make("Family history", "family_history")
                ->sortable(),
            Column::make("Surgical history", "surgical_history")
                ->sortable(),
            Column::make("Observations", "observations")
                ->sortable(),
            Column::make("Emergency contact", "emergency_contact")
                ->sortable(),
            Column::make("Emergency contact relationship", "emergency_contact_relationship")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),*/
            Column::make("Nombre del paciente", "user.name")
                ->sortable(),
            Column::make("Email del paciente", "user.email")
                ->sortable(),
            Column::make("Número de id", "user.id_number")
                ->sortable(),
            Column::make("Teléfono", "user.phone")
                ->sortable(),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.patients.actions', ['patient' => $row])),  
        ];
    }
}
