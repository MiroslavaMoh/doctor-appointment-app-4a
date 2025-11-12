<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

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

            Column::make("Fecha de creación", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('d/m/Y')),

            Column::make("Acciones")
                ->label(fn($row) => view('admin.users.actions', ['user' => $row])),
        ];
    }
}
