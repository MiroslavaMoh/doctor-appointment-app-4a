<x-admin-layout title="Create | Onigiri-san"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Nuevo']
    ]">

    <x-wire-card>

        <form action="{{ route('admin.users.store') }}" method="POST"> <!-- Funfact! Php solo tienen metodo get y post, lo demás es un truco entre estas -->

            @csrf <!--cross site request forgery, para proteger de ataques donde falsifican peticiones -->

            <div class="space-y-4">
            <div class="grid lg:grid-cols-2 gap-4">
            <x-wire-input
                label="Nombre" 
                name="name"
                placeholder="Nombre"
                value="{{ old('name') }}"
                required
                autocomplete="name" />
            
            <!--<x-wire-input
                label="Nombre" // nombre que ve el usuario
                name="name" // nombre de identificacion
                placeholder="Nombre" // Es un texto temporal en el campo, usualmente tiene indicacione o el nombre
                value="{{ old('name') }}" // old recupera datos anteriores
                required // indica que es requerido
                autocomplete="name" /> // activa autocompletado
                inputmode= url // cambia el tipo de teclado segun modalidades
                type="password" //esconde contraseña
                -->

            <x-wire-input
                label="Email"
                name="email"
                placeholder="usuario@email.com"
                value="{{ old('email') }}"
                required
                autocomplete="email" />

            <x-wire-input
                label="Contraseña"
                name="password"
                placeholder="Mínimo 8 caracteres"
                required
                type="password"
                autocomplete="new-password" />

            <x-wire-input
                label="Confirmar contraseña"
                name="password_confirmation"
                placeholder="Confirmar caracteres"
                required
                type="password"
                autocomplete="new-password" />

            <x-wire-input
                label="Teléfono"
                name="phone"
                placeholder="Teléfono"
                value="{{ old('phone') }}"
                required
                autocomplete="tel" />

            <x-wire-input
                label="Número de ID"
                name="id_number"
                placeholder="Ej. 123456"
                value="{{ old('id_number') }}"
                required
                inputmode="numeric"
                autocomplete="off" />
            </div>

            <x-wire-input
                label="Dirección"
                name="adress"
                placeholder="Domicilio"
                value="{{ old('adress') }}"
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
                    Guardar
                </x-wire-button>
            </div>
        
          </div>
        </form>

    </x-wire-card>
</x-admin-layout>
