<x-admin-layout title="Citas médicas | Onigiri-san"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Editar cita'],
]">

<x-slot name="title">Editar cita</x-slot>

<x-slot name="action">
    <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
        <i class="fa-solid fa-arrow-left mr-1"></i> Volver
    </x-wire-button>
</x-slot>

<x-wire-card>
    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Paciente --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Paciente <span class="text-red-500">*</span>
                </label>
                <select name="patient_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('patient_id') border-red-500 @enderror">
                    <option value="">Selecciona un paciente</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                            {{ $patient->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Doctor <span class="text-red-500">*</span>
                </label>
                <select name="doctor_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('doctor_id') border-red-500 @enderror">
                    <option value="">Selecciona un doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                            {{ $doctor->user->name }}{{ $doctor->speciality ? ' — ' . $doctor->speciality->name : '' }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" name="date" value="{{ old('date', $appointment->date->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('date') border-red-500 @enderror">
                @error('date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Duración --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duración (minutos)</label>
                <input type="number" name="duration" value="{{ old('duration', $appointment->duration) }}" min="15" step="15"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Hora inicio --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Hora de inicio <span class="text-red-500">*</span>
                </label>
                <input type="time" name="start_time" value="{{ old('start_time', substr($appointment->start_time, 0, 5)) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('start_time') border-red-500 @enderror">
                @error('start_time')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hora fin --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Hora de fin <span class="text-red-500">*</span>
                </label>
                <input type="time" name="end_time" value="{{ old('end_time', substr($appointment->end_time, 0, 5)) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('end_time') border-red-500 @enderror">
                @error('end_time')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="1" @selected(old('status', $appointment->status) == 1)>Programado</option>
                    <option value="2" @selected(old('status', $appointment->status) == 2)>Completado</option>
                    <option value="3" @selected(old('status', $appointment->status) == 3)>Cancelado</option>
                </select>
            </div>

        </div>

        {{-- Motivo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la consulta</label>
            <textarea name="reason" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('reason', $appointment->reason) }}</textarea>
        </div>

        <div class="flex justify-end pt-2">
            <x-wire-button type="submit">
                <i class="fa-solid fa-check mr-1"></i> Guardar cambios
            </x-wire-button>
        </div>

    </form>
</x-wire-card>

</x-admin-layout>
