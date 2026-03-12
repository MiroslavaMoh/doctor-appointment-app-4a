<div>
    {{-- Search form --}}
    <x-wire-card>
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Buscar disponibilidad</h2>
            <p class="text-sm text-gray-500 mt-1">Encuentra el horario perfecto para tu cita.</p>
        </div>

        {{-- General error (no patient profile) --}}
        @error('general')
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-r-lg">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ $message }}
            </div>
        @enderror

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- Fecha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input
                    type="date"
                    wire:model="date"
                    min="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                @error('date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hora --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                <select
                    wire:model="time"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Selecciona una hora</option>
                    @foreach($timeSlots as $slot)
                        <option value="{{ $slot }}">{{ $slot }}</option>
                    @endforeach
                </select>
                @error('time')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Especialidad --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad (opcional)</label>
                <select
                    wire:model="speciality_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Selecciona una espec...</option>
                    @foreach($specialities as $speciality)
                        <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Botón buscar --}}
            <div>
                <button
                    wire:click="search"
                    wire:loading.attr="disabled"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold py-2 px-4 rounded-lg text-sm transition"
                >
                    <span wire:loading.remove wire:target="search">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i>
                        Buscar disponibilidad
                    </span>
                    <span wire:loading wire:target="search">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                        Buscando...
                    </span>
                </button>
            </div>
        </div>
    </x-wire-card>

    {{-- Search results --}}
    @if($searched)
        <x-wire-card class="mt-6">
            @if(count($availableDoctors) > 0)
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Médicos disponibles
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            {{ count($availableDoctors) }} resultado(s) para el {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} a las {{ $time }}
                        </span>
                    </h3>
                </div>

                <div class="space-y-3">
                    @foreach($availableDoctors as $doctor)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition">
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $doctor['photo'] }}"
                                    alt="{{ $doctor['name'] }}"
                                    class="h-12 w-12 rounded-full object-cover object-center"
                                >
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $doctor['name'] }}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fa-solid fa-stethoscope mr-1"></i>{{ $doctor['speciality'] }}
                                        &nbsp;·&nbsp;
                                        <i class="fa-solid fa-id-card mr-1"></i>Lic. {{ $doctor['license'] }}
                                    </p>
                                </div>
                            </div>
                            <button
                                wire:click="confirm({{ $doctor['id'] }})"
                                wire:loading.attr="disabled"
                                wire:target="confirm({{ $doctor['id'] }})"
                                class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-sm font-semibold py-2 px-5 rounded-lg transition"
                            >
                                <span wire:loading.remove wire:target="confirm({{ $doctor['id'] }})">
                                    <i class="fa-solid fa-calendar-check mr-1"></i>
                                    Confirmar cita
                                </span>
                                <span wire:loading wire:target="confirm({{ $doctor['id'] }})">
                                    <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                                    Confirmando...
                                </span>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center py-10 text-gray-500">
                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-gray-300"></i>
                    <p class="font-semibold text-gray-700">No hay médicos disponibles</p>
                    <p class="text-sm mt-1">No se encontraron médicos con disponibilidad para el horario seleccionado.</p>
                </div>
            @endif
        </x-wire-card>
    @endif
</div>
