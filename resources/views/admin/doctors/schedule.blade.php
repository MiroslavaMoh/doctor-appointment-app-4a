@php
    $days = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    // Generate hours from 08:00 to 19:00 (last slot ends at 20:00)
    $hours = [];
    for ($h = 8; $h <= 19; $h++) {
        $slots = [];
        for ($m = 0; $m < 60; $m += 15) {
            $startH = $h;
            $endH   = ($m + 15 === 60) ? $h + 1 : $h;
            $endM   = ($m + 15 === 60) ? 0 : $m + 15;
            $slots[] = [
                'start' => sprintf('%02d:%02d', $startH, $m),
                'end'   => sprintf('%02d:%02d', $endH, $endM),
            ];
        }
        $hours[] = [
            'label' => sprintf('%02d:00:00', $h),
            'slots' => $slots,
        ];
    }
@endphp

<x-admin-layout title="Doctores | Horarios"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores',  'href' => route('admin.doctors.index')],
    ['name' => 'Horarios'],
]">

<x-slot name="title">
    Horarios
</x-slot>

<x-wire-card>

    {{-- Header --}}
    <x-wire-card class="mb-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ $doctor->user->profile_photo_url }}"
                     alt="{{ $doctor->user->name }}"
                     class="h-20 w-20 rounded-full object-cover object-center mr-4">
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</p>
                    <p class="text-sm text-gray-500">Licencia: {{ $doctor->medical_license_number ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray href="{{ route('admin.doctors.index') }}">Volver</x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-r-lg">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.doctors.schedule.update', $doctor) }}" method="POST">
        @csrf

        {{-- Table card --}}
        <x-wire-card>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Gestor de horarios</h2>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-calendar-check mr-2"></i>
                    Guardar horario
                </x-wire-button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    {{-- Column headers --}}
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-4 min-w-[110px]">
                                Día/Hora
                            </th>
                            @foreach($days as $dayNum => $dayName)
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-3 px-4 min-w-[160px]">
                                    {{ $dayName }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    @foreach($hours as $hourIndex => $hour)
                        @php
                            // Build initial state for Alpine for this hour block
                            $alpineData = [];
                            foreach ($days as $dayNum => $dayName) {
                                foreach ($hour['slots'] as $slot) {
                                    $alpineData[$dayNum][$slot['start']] =
                                        isset($scheduleMap[$dayNum][$slot['start']]);
                                }
                            }
                        @endphp

                        <tbody
                            x-data="{
                                days: {{ json_encode($alpineData) }},
                                isAllDay(day) {
                                    return Object.values(this.days[day]).every(v => v);
                                },
                                toggleDay(day) {
                                    const v = !this.isAllDay(day);
                                    Object.keys(this.days[day]).forEach(k => this.days[day][k] = v);
                                },
                                isAllRow() {
                                    return Object.keys(this.days).every(d => this.isAllDay(d));
                                },
                                toggleRow() {
                                    const v = !this.isAllRow();
                                    Object.keys(this.days).forEach(d =>
                                        Object.keys(this.days[d]).forEach(k => this.days[d][k] = v)
                                    );
                                }
                            }"
                            class="border-b border-gray-100 last:border-0"
                        >
                            {{-- Todos row --}}
                            <tr class="bg-gray-50">
                                <td rowspan="5" class="py-3 px-4 align-middle border-r border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            :checked="isAllRow()"
                                            @click="toggleRow()"
                                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 cursor-pointer"
                                        >
                                        <span class="font-semibold text-gray-700 text-xs whitespace-nowrap">
                                            {{ $hour['label'] }}
                                        </span>
                                    </div>
                                </td>
                                @foreach($days as $dayNum => $dayName)
                                    <td class="py-2 px-4">
                                        <label class="flex items-center gap-2 cursor-pointer select-none">
                                            <input
                                                type="checkbox"
                                                :checked="isAllDay({{ $dayNum }})"
                                                @click="toggleDay({{ $dayNum }})"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 cursor-pointer"
                                            >
                                            <span class="text-gray-600 text-xs">Todos</span>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Slot rows --}}
                            @foreach($hour['slots'] as $slot)
                                <tr>
                                    @foreach($days as $dayNum => $dayName)
                                        <td class="py-1 px-4">
                                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    name="slots[{{ $dayNum }}][{{ $slot['start'] }}]"
                                                    value="1"
                                                    x-model="days[{{ $dayNum }}]['{{ $slot['start'] }}']"
                                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 cursor-pointer"
                                                >
                                                <span class="text-gray-700 text-xs">
                                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                                </span>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    @endforeach
                </table>
            </div>
        </x-wire-card>

    </form>

</x-wire-card>

</x-admin-layout>
