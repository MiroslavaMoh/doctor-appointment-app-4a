<div>
    {{-- Patient header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $appointment->patient->user->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">DNI: {{ $appointment->patient->user->dni ?? '—' }}</p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button
                wire:click="$set('showHistoryModal', true)"
                class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-4 rounded-lg transition"
            >
                <i class="fa-solid fa-file-medical text-gray-500"></i>
                Ver Historia
            </button>
            <button
                wire:click="$set('showPastModal', true)"
                class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-4 rounded-lg transition"
            >
                <i class="fa-solid fa-clock-rotate-left text-gray-500"></i>
                Consultas Anteriores
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'consulta' }" class="bg-white rounded-xl border border-gray-200 p-6">

        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button
                    @click="tab = 'consulta'"
                    :class="tab === 'consulta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors"
                >
                    <i class="fa-solid fa-stethoscope"></i> Consulta
                </button>
                <button
                    @click="tab = 'receta'"
                    :class="tab === 'receta' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors"
                >
                    <i class="fa-solid fa-prescription-bottle-medical"></i> Receta
                </button>
            </nav>
        </div>

        {{-- Tab: Consulta --}}
        <div x-show="tab === 'consulta'" x-cloak>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                    <textarea
                        wire:model="diagnosis"
                        rows="4"
                        placeholder="Describa el diagnóstico del paciente aquí..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                    <textarea
                        wire:model="treatment"
                        rows="4"
                        placeholder="Describa el tratamiento recomendado aquí..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea
                        wire:model="notes"
                        rows="3"
                        placeholder="Agregue notas adicionales sobre la consulta..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                    ></textarea>
                </div>
            </div>
        </div>

        {{-- Tab: Receta --}}
        <div x-show="tab === 'receta'" x-cloak>
            @if(count($medications) > 0)
                <div class="grid grid-cols-12 gap-3 text-xs font-semibold text-gray-500 uppercase mb-2 px-1">
                    <div class="col-span-4">Medicamento</div>
                    <div class="col-span-3">Dosis</div>
                    <div class="col-span-4">Frecuencia / Duración</div>
                    <div class="col-span-1"></div>
                </div>
            @endif

            <div class="space-y-2">
                @foreach($medications as $i => $med)
                    <div class="grid grid-cols-12 gap-3 items-center" wire:key="med-{{ $i }}">
                        <input
                            type="text"
                            wire:model="medications.{{ $i }}.name"
                            placeholder="Medicamento"
                            class="col-span-4 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <input
                            type="text"
                            wire:model="medications.{{ $i }}.dose"
                            placeholder="Dosis"
                            class="col-span-3 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <input
                            type="text"
                            wire:model="medications.{{ $i }}.frequency"
                            placeholder="Ej. cada 8 horas por 7 días"
                            class="col-span-4 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <div class="col-span-1 flex justify-center">
                            <button
                                wire:click="removeMedication({{ $i }})"
                                class="bg-red-500 hover:bg-red-600 text-white rounded-lg w-9 h-9 flex items-center justify-center transition"
                            >
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button
                wire:click="addMedication"
                class="mt-4 inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-600 text-sm font-medium py-2 px-4 rounded-lg transition"
            >
                <i class="fa-solid fa-plus"></i> Añadir Medicamento
            </button>
        </div>

        {{-- Save button (shared) --}}
        <div class="flex justify-end mt-8 pt-4 border-t border-gray-100">
            <button
                wire:click="saveConsultation"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold py-2.5 px-6 rounded-lg text-sm transition"
            >
                <span wire:loading.remove wire:target="saveConsultation">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar Consulta
                </span>
                <span wire:loading wire:target="saveConsultation">
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...
                </span>
            </button>
        </div>

    </div>

    {{-- Modal: Historia Médica --}}
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Historia médica del paciente</h3>
                    <button wire:click="$set('showHistoryModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipo de sangre:</p>
                            <p class="font-bold text-gray-900">{{ $appointment->patient->bloodType?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Alergias:</p>
                            <p class="font-bold text-gray-900">{{ $appointment->patient->allergies ?: 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Enfermedades crónicas:</p>
                            <p class="font-bold text-gray-900">{{ $appointment->patient->chronic_conditions ?: 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Antecedentes quirúrgicos:</p>
                            <p class="font-bold text-gray-900">{{ $appointment->patient->surgical_history ?: 'No registradas' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.patients.edit', $appointment->patient) }}"
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        Ver / Editar Historia Médica
                    </a>
                </div>

            </div>
        </div>
    @endif

    {{-- Modal: Consultas Anteriores --}}
    @if($showPastModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Consultas Anteriores</h3>
                    <button wire:click="$set('showPastModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-4 space-y-4">
                    @forelse($pastConsultations as $past)
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-indigo-500"></i>
                                        {{ $past->appointment->date->format('d/m/Y') }} a las {{ substr($past->appointment->start_time, 0, 5) }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        Atendido por: Dr(a). {{ $past->appointment->doctor->user->name }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.appointments.consult', $past->appointment) }}"
                                   class="flex-shrink-0 border border-gray-300 text-gray-600 hover:bg-gray-50 text-xs font-medium py-1.5 px-3 rounded-lg transition">
                                    Consultar Detalle
                                </a>
                            </div>
                            <div class="bg-gray-50 rounded-lg px-4 py-3 text-sm space-y-1">
                                <p><span class="font-semibold">Diagnóstico:</span> {{ $past->diagnosis ?: '—' }}</p>
                                <p><span class="font-semibold">Tratamiento:</span> {{ $past->treatment ?: '—' }}</p>
                                @if($past->notes)
                                    <p><span class="font-semibold">Notas:</span> {{ $past->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center py-10 text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-2 text-gray-300"></i>
                            <p class="text-sm">No hay consultas anteriores registradas.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    @endif
</div>
