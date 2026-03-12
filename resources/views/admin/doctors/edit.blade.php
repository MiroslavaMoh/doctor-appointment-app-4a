<x-admin-layout title="Doctores | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard',
    'href' => route('admin.dashboard')],
    ['name' => 'Doctores',
    'href' => route('admin.doctors.index')],

    ['name' => 'Editar'],
]">

<x-slot name="title">
    Editar Doctor
</x-slot>

  <x-wire-card>

        <form action="{{ route('admin.doctors.update',$doctor->id) }}" method="POST"> <!-- Funfact! Php solo tienen metodo get y post, lo demás es un truco entre estas -->

            @csrf <!--cross site request forgery, para proteger de ataques donde falsifican peticiones -->
            @method('PUT')

        {{-- Header con accion --}}
            <x-wire-card class="mb-8">
                <div class="flex justify-between items-center"> <!-- Justificacion por enmedio -->
                    <div class="flex items-center">
                        <img src="{{ $doctor->user->profile_photo_url }}" alt="{{ $doctor->user->name }}" class="h-20 w-20 rounded-full object-cover object-center mr-4">
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</p>
                            <p class="text-sm text-gray-500">Licencia: {{ $doctor->medical_license_number ?? 'N/A' }}</p> <!-- Si no tiene licencia, muestra N/A -->
                        </div>
                    </div>
                    <div class="flex space-x-3 mt-6 lg:mt-0">
                        <x-wire-button outline href="{{ route('admin.doctors.schedule', $doctor) }}">
                            <i class="fa-solid fa-calendar-days mr-1"></i>
                            Disponibilidad de horarios
                        </x-wire-button>
                        <x-wire-button type="submit">
                            <i class="fa-solid fa-check"></i>
                            Guardar cambios
                        </x-wire-button>
                    </div>
                </div>
            </x-wire-card>

            <x-wire-card>
                <div class="space-y-4">
                    <x-wire-native-select label="Especialidad" name="speciality_id">
                        <option value="">Seleccionar especialidad</option>
                        @foreach($specialities as $speciality)
                            <option value="{{ $speciality->id }}" {{ old('speciality_id', $doctor->speciality_id) == $speciality->id ? 'selected' : '' }}>
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </x-wire-native-select>

                    <x-wire-input label="Número de licencia médica" name="medical_license_number" value="{{ old('medical_license_number', $doctor->medical_license_number) }}" />

                    <x-wire-textarea label="Biografía" name="biography">
                        {{ old('biography', $doctor->biography) }}
                    </x-wire-textarea>
                </div>
            </x-wire-card>

        </form>

    </x-wire-card>

</x-admin-layout>
