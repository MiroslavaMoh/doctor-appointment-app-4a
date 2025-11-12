<x-admin-layout title="Edit | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 
    'href' => route('admin.dashboard')],
    ['name' => 'Usuarios',
    'href' => route('admin.users.index')],
    
    ['name' => 'Edit'],
]">


<x-slot name="title">
    Editar
</x-slot>

<x-wire-card>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <x-wire-input 
            label="Nombre" 
            name="name" 
            placeholder="Nombre de rol" 
            value="{{old('name',$user->name)}}"
            required></x-wire-input> 
    
    <div class="flex justify-end mt-4">
        <x-wire-button blue type="submit">Actualizar</x-wire-button>
    </div>
    </form>
</x-wire-card>

</x-admin-layout>