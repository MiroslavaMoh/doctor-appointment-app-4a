<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background:#f4f7fa; color:#333; margin:0; padding:0; }
        .wrapper { max-width:680px; margin:0 auto; padding:30px 16px; }
        .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); overflow:hidden; }
        .card-header { background:#1e40af; padding:28px 30px; text-align:center; }
        .card-header h1 { color:#fff; font-size:20px; margin:0 0 4px; }
        .card-header p { color:#bfdbfe; font-size:13px; margin:0; }
        .card-body { padding:28px 30px; }
        .greeting { font-size:15px; margin-bottom:18px; }
        .summary { background:#eff6ff; border-radius:6px; padding:14px 18px; margin-bottom:22px; font-size:14px; }
        .summary strong { color:#1d4ed8; font-size:22px; }
        table { width:100%; border-collapse:collapse; margin-top:8px; }
        thead th {
            background:#1e40af; color:#fff;
            padding:10px 12px; text-align:left;
            font-size:12px; text-transform:uppercase; letter-spacing:.4px;
        }
        tbody tr:nth-child(even) td { background:#f1f5f9; }
        tbody td { padding:9px 12px; font-size:13px; border-bottom:1px solid #e2e8f0; vertical-align:middle; }
        .badge {
            display:inline-block; padding:2px 10px; border-radius:12px;
            font-size:11px; font-weight:bold;
        }
        .badge-green  { background:#dcfce7; color:#166534; }
        .badge-blue   { background:#dbeafe; color:#1e40af; }
        .badge-red    { background:#fee2e2; color:#991b1b; }
        .no-data { text-align:center; padding:30px; color:#94a3b8; font-style:italic; }
        .footer { text-align:center; padding:16px 30px; font-size:11px; color:#94a3b8; background:#f8fafc; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="card-header">
            <h1>Sistema de Gestión Médica</h1>
            <p>
                @if($recipientType === 'doctor')
                    Reporte de pacientes agendados para hoy
                @else
                    Reporte diario de citas – Administrador
                @endif
            </p>
        </div>

        <div class="card-body">
            <p class="greeting">
                @if($recipientType === 'doctor')
                    Estimado/a <strong>Dr./Dra. {{ $recipientName }}</strong>,
                @else
                    Estimado/a <strong>{{ $recipientName }}</strong>,
                @endif
            </p>

            <p>A continuación se presenta el listado de citas médicas programadas para el día de hoy,
               <strong>{{ $reportDate }}</strong>:</p>

            <div class="summary">
                Total de citas agendadas hoy:
                <strong>{{ $appointments->count() }}</strong>
            </div>

            @if($appointments->isEmpty())
                <p class="no-data">No hay citas programadas para el día de hoy.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            @if($recipientType === 'admin')
                                <th>Doctor</th>
                                <th>Especialidad</th>
                            @endif
                            <th>Horario</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appt)
                        <tr>
                            <td>{{ str_pad($appt->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <strong>{{ $appt->patient->user->name }}</strong><br>
                                <small style="color:#64748b;">{{ $appt->patient->user->phone ?? 'Sin tel.' }}</small>
                            </td>
                            @if($recipientType === 'admin')
                                <td>{{ $appt->doctor->user->name }}</td>
                                <td>{{ $appt->doctor->speciality->name ?? '—' }}</td>
                            @endif
                            <td>
                                {{ substr($appt->start_time, 0, 5) }} – {{ substr($appt->end_time, 0, 5) }}
                            </td>
                            <td>{{ $appt->reason ?? '—' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($appt->status) {
                                        \App\Models\Appointment::STATUS_PROGRAMADO => 'badge-green',
                                        \App\Models\Appointment::STATUS_COMPLETADO => 'badge-blue',
                                        \App\Models\Appointment::STATUS_CANCELADO  => 'badge-red',
                                        default => ''
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $appt->status_label }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="footer">
            Reporte automático generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}.<br>
            Sistema de Gestión Médica &mdash; No responder a este correo.
        </div>
    </div>
</div>
</body>
</html>
