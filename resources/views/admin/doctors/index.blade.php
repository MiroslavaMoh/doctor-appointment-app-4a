<x-admin-layout title="Doctores | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 
    'href' => route('admin.dashboard')],
    
    ['name' => 'Doctores']
    
]">

@livewire('admin.datatables.DoctorTable')

</x-admin-layout>