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

        <form action="{{ route('admin.users.update',$user->id) }}" method="POST"> <!-- Funfact! Php solo tienen metodo get y post, lo demás es un truco entre estas -->

            @csrf <!--cross site request forgery, para proteger de ataques donde falsifican peticiones -->
            @method('PUT')

            <div class="space-y-4">
            <div class="grid lg:grid-cols-2 gap-4">
            <x-wire-input
                label="Nombre" 
                name="name"
                placeholder="Nombre"
                :value="old('name', $user->name)"
                required
                
                autocomplete="name" />
            
            <x-wire-input
                label="Email"
                name="email"
                placeholder="usuario@email.com"
                :value="old('email', $user->email)"
                required
                autocomplete="email" />

            {{-- Contraseña opcional en edición --}}
            <x-wire-input
                label="Contraseña"
                name="password"
                placeholder="Dejar vacío para no cambiar"
                type="password"
                autocomplete="new-password" />

            <x-wire-input
                label="Confirmar contraseña"
                name="password_confirmation"
                placeholder="Confirmar contraseña"
                type="password"
                autocomplete="new-password" />

            <x-wire-input
                label="Teléfono"
                name="phone"
                placeholder="Teléfono"
                :value="old('phone', $user->phone)"
                required
                autocomplete="tel" />

            <x-wire-input
                label="Número de ID"
                name="id_number"
                placeholder="Ej. 123456"
                :value="old('id_number', $user->id_number)"
                required
                inputmode="numeric"
                autocomplete="off" />
            </div>

            <x-wire-input
                label="Dirección"
                name="adress"
                placeholder="Domicilio"
                :value="old('adress', $user->adress)"
                required
                autocomplete="street-address" />

            <div class="space-y-1">
                <x-wire-native-select name="role_id" label="Rol" required>
                    <option value="">Seleccione un rol</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>

            <p class="text-sm text-gray-500">
                Define los permisos y accesos del usuario
            </p>

            <div class="flex justify-end mt-4">
                <x-wire-button blue type="submit">
                    Actualizar
                </x-wire-button>
            </div>
        
          </div>
        </form>

    </x-wire-card>

</x-admin-layout>