<x-admin-layout title="Citas médicas | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas'],
]">

<x-slot name="title">
    {{ $mine ? 'Mis citas' : 'Citas' }}
</x-slot>

<x-slot name="action">
    <div class="flex space-x-2">
        @if($mine)
            <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                <i class="fa-solid fa-list mr-1"></i>
                Todas las citas
            </x-wire-button>
        @else
            <x-wire-button outline href="{{ route('admin.appointments.my') }}">
                <i class="fa-solid fa-calendar-check mr-1"></i>
                Mis citas
            </x-wire-button>
        @endif
        <x-wire-button href="{{ route('admin.appointments.create') }}">
            <i class="fa-solid fa-plus mr-1"></i>
            Nuevo
        </x-wire-button>
    </div>
</x-slot>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-r-lg">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
@endif

@livewire('admin.datatables.appointment-table', ['mine' => $mine])

</x-admin-layout>
