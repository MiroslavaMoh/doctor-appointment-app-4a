<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita Médica</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background:#f4f7fa; color:#333; margin:0; padding:0; }
        .wrapper { max-width:600px; margin:0 auto; padding:30px 16px; }
        .card { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); overflow:hidden; }
        .card-header { background:#2563eb; padding:28px 30px; text-align:center; }
        .card-header h1 { color:#fff; font-size:20px; margin:0 0 4px; }
        .card-header p { color:#bfdbfe; font-size:13px; margin:0; }
        .card-body { padding:28px 30px; }
        .greeting { font-size:16px; margin-bottom:18px; }
        .info-box { background:#eff6ff; border-left:4px solid #2563eb; border-radius:4px; padding:16px 20px; margin:20px 0; }
        .info-row { display:flex; margin-bottom:10px; }
        .info-row:last-child { margin-bottom:0; }
        .info-label { font-weight:bold; color:#1d4ed8; min-width:130px; font-size:13px; }
        .info-value { color:#334155; font-size:13px; }
        .status-badge {
            display:inline-block; background:#dcfce7; color:#166534;
            padding:3px 12px; border-radius:20px; font-size:12px; font-weight:bold;
        }
        .divider { border:none; border-top:1px solid #e2e8f0; margin:22px 0; }
        .note { background:#fefce8; border:1px solid #fde047; border-radius:4px; padding:12px 16px; font-size:12px; color:#854d0e; margin-bottom:20px; }
        .attachment-note { font-size:12px; color:#64748b; margin-top:16px; }
        .footer { text-align:center; padding:18px 30px; font-size:11px; color:#94a3b8; background:#f8fafc; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="card-header">
            <h1>Sistema de Gestión Médica</h1>
            <p>
                @if($recipientType === 'doctor')
                    Nueva cita agendada en su agenda
                @else
                    Confirmación de su cita médica
                @endif
            </p>
        </div>

        <div class="card-body">
            @if($recipientType === 'doctor')
                <p class="greeting">
                    Estimado/a <strong>Dr./Dra. {{ $appointment->doctor->user->name }}</strong>,
                </p>
                <p>Se ha registrado una nueva cita en su agenda. A continuación los detalles:</p>
            @else
                <p class="greeting">
                    Estimado/a <strong>{{ $appointment->patient->user->name }}</strong>,
                </p>
                <p>Su cita médica ha sido registrada exitosamente. A continuación encontrará el resumen:</p>
            @endif

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Doctor:</span>
                    <span class="info-value">{{ $appointment->doctor->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Especialidad:</span>
                    <span class="info-value">{{ $appointment->doctor->speciality->name ?? 'No especificada' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Paciente:</span>
                    <span class="info-value">{{ $appointment->patient->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">{{ $appointment->date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Horario:</span>
                    <span class="info-value">
                        {{ substr($appointment->start_time, 0, 5) }} – {{ substr($appointment->end_time, 0, 5) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value">
                        <span class="status-badge">{{ $appointment->status_label }}</span>
                    </span>
                </div>
                @if($appointment->reason)
                <div class="info-row">
                    <span class="info-label">Motivo:</span>
                    <span class="info-value">{{ $appointment->reason }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">No. de cita:</span>
                    <span class="info-value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <hr class="divider">

            @if($recipientType === 'patient')
                <div class="note">
                    <strong>Recordatorio:</strong> Recibirá una notificación por WhatsApp el día anterior a su cita.
                    Si necesita cancelar o reagendar, comuníquese con al menos 24 horas de anticipación.
                </div>
            @endif

            <p class="attachment-note">
                Se adjunta a este correo el <strong>comprobante en formato PDF</strong> con todos los datos de la cita.
                Consérvelo para su registro.
            </p>
        </div>

        <div class="footer">
            Este correo fue generado automáticamente el {{ now()->format('d/m/Y H:i') }}.<br>
            Sistema de Gestión Médica &mdash; No responder a este correo.
        </div>
    </div>
</div>
</body>
</html>