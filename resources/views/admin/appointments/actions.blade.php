<div class="flex items-center space-x-2">
    {{-- Atender cita --}}
    <x-wire-button href="{{ route('admin.appointments.consult', $appointment) }}" indigo xs title="Atender cita">
        <i class="fa-solid fa-stethoscope"></i>
    </x-wire-button>

    {{-- Editar --}}
    <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}" blue xs title="Editar">
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Imprimir (no funcional aún) --}}
    <x-wire-button green xs disabled title="Próximamente">
        <i class="fa-solid fa-print"></i>
    </x-wire-button>
</div>
