<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function builder(): Builder
    {
        return User::query()->with('roles');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable(),

            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),

            Column::make("Correo", "email")
                ->sortable()
                ->searchable(),

            Column::make("Número ID", "id_number")
                ->sortable(),

            Column::make("Teléfono", "phone")
                ->sortable(),

            Column::make("Rol")
                ->label(fn($row) => $row->roles->first()?->name ?? 'Sin Rol'),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.users.actions', ['user' => $row])),
        ];
    }
}
