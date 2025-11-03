<x-admin-layout title="Edit | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 
    'href' => route('admin.dashboard')],
    ['name' => 'Roles',
    'href' => route('admin.roles.index')],
    
    ['name' => 'Edit'],
]">

<x-slot name="title">
    Editar
</x-slot>

</x-admin-layout>