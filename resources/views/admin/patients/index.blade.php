<x-admin-layout title="Pacientes | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 
    'href' => route('admin.dashboard')],
    
    ['name' => 'Pacientes']
    
]">

@livewire('admin.datatables.PatientsTable')

</x-admin-layout>