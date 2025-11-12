<x-admin-layout title="Usuarios | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 
    'href' => route('admin.dashboard')],
    
    ['name' => 'Usuarios',],
]">

<x-slot name="action">
    <x-wire-button blue href="{{route('admin.users.create')}}">
        <i class="fa-solid fa-plus mr-2"></i>
        Nuevo
    </x-wire-button>
</x-slot>
@livewire('admin.datatables.users-table')

</x-admin-layout>