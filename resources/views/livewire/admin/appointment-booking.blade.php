<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column: search + results --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Search panel --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Buscar disponibilidad</h2>
            <p class="text-sm text-gray-500 mb-5">Encuentra el horario perfecto para tu cita.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input
                        type="date"
                        wire:model="searchDate"
                        min="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                {{-- Hora --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                    <select
                        wire:model="searchTimeStart"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        x-on:change="$wire.searchTimeEnd = $el.options[$el.selectedIndex + 1]?.value ?? $wire.searchTimeEnd"
                    >
                        @foreach($timeRanges as $start => $label)
                            <option value="{{ $start }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Especialidad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad (opcional)</label>
                    <select
                        wire:model="searchSpecialityId"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">Todas las especialidades</option>
                        @foreach($specialities as $speciality)
                            <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    wire:click="search"
                    wire:loading.attr="disabled"
                    class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold py-2 px-6 rounded-lg text-sm transition"
                >
                    <span wire:loading.remove wire:target="search">Buscar disponibilidad</span>
                    <span wire:loading wire:target="search"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Buscando...</span>
                </button>
            </div>
        </div>

        {{-- Results --}}
        @if($searched)
            @if(count($results) > 0)
                <div class="space-y-4">
                    @foreach($results as $result)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ $result['initials'] }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $result['name'] }}</p>
                                    <p class="text-sm text-indigo-600">{{ $result['speciality'] }}</p>
                                </div>
                            </div>

                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Horarios disponibles:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($result['slots'] as $slot)
                                    <button
                                        wire:click="selectSlot({{ $result['doctor_id'] }}, '{{ addslashes($result['name']) }}', '{{ $slot }}')"
                                        @class([
                                            'py-1.5 px-4 rounded-lg text-sm font-medium border transition',
                                            'bg-indigo-600 text-white border-indigo-600' => $selectedDoctorId === $result['doctor_id'] && $selectedTime === $slot,
                                            'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100' => !($selectedDoctorId === $result['doctor_id'] && $selectedTime === $slot),
                                        ])
                                    >
                                        {{ $slot }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-10 flex flex-col items-center text-gray-400">
                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">No hay disponibilidad para los criterios seleccionados.</p>
                </div>
            @endif
        @endif

    </div>

    {{-- Right column: summary --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">Resumen de la cita</h3>

            @if($selectedDoctorId)
                <div class="space-y-2 text-sm mb-5">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Doctor:</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $selectedDoctorName }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-semibold text-gray-900">{{ $searchDate }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Horario:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $selectedTime }}:00 – {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('H:i') }}:00
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Duración:</span>
                        <span class="font-semibold text-gray-900">15 minutos</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-4">
                    {{-- Paciente --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                        <select
                            wire:model="patientId"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('patientId') border-red-500 @enderror"
                        >
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patientId')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita</label>
                        <textarea
                            wire:model="reason"
                            rows="3"
                            placeholder="Chequeo de medicamentos..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                        ></textarea>
                    </div>

                    <button
                        wire:click="confirm"
                        wire:loading.attr="disabled"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold py-2.5 rounded-lg text-sm transition"
                    >
                        <span wire:loading.remove wire:target="confirm">Confirmar cita</span>
                        <span wire:loading wire:target="confirm"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Confirmando...</span>
                    </button>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-solid fa-hand-pointer text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Selecciona un horario disponible para ver el resumen.</p>
                </div>
            @endif
        </div>
    </div>

</div>
