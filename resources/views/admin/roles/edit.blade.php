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

<x-wire-card>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <x-wire-input 
            label="Nombre" 
            name="name" 
            placeholder="Nombre de rol" 
            value="{{old('name',$role->name)}}"
            required></x-wire-input> 
    
    <div class="flex justify-end mt-4">
        <x-wire-button blue type="submit">Actualizar</x-wire-button>
    </div>
    </form>
</x-wire-card>

</x-admin-layout>