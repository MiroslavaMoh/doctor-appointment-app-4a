<x-admin-layout title="Consulta | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Consulta'],
]">

<x-slot name="title">Consulta</x-slot>

@livewire('admin.consultation-manager', ['appointment' => $appointment])

</x-admin-layout>
