<x-admin-layout title="Nueva cita | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Nuevo'],
]">

<x-slot name="title">Nuevo</x-slot>

<x-slot name="action">
    <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
        <i class="fa-solid fa-arrow-left mr-1"></i> Volver
    </x-wire-button>
</x-slot>

@livewire('admin.appointment-booking')

</x-admin-layout>
