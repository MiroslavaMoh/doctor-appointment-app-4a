<x-admin-layout title="Create | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Nuevo']
]">

<x-wire-card>

    <form action="{{route('admin.users.store')}}" method="POST">
        @csrf
        <x-wire-input 
            label="Nombre" 
            name="name" 
            placeholder="Nombre de rol" 
            value="{{old('name')}}"
            required></x-wire-input> 
    
    <div class="flex justify-end mt-4">
        <x-wire-button blue type="submit">Guardar</x-wire-button>
    </div>
    </form>
</x-wire-card>

</x-admin-layout>