<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de Cita Médica</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 22px;
            color: #2563eb;
            margin-bottom: 4px;
        }
        .header h2 {
            font-size: 15px;
            color: #555;
            font-weight: normal;
        }
        .badge {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            margin-top: 8px;
        }
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
        }
        table.info td {
            padding: 6px 8px;
            vertical-align: top;
        }
        table.info td.label {
            font-weight: bold;
            color: #555;
            width: 38%;
            background: #f8fafc;
        }
        table.info td.value {
            color: #333;
        }
        table.info tr:nth-child(even) td {
            background: #f1f5f9;
        }
        table.info tr:nth-child(even) td.label {
            background: #e8eef5;
        }
        .highlight-box {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 22px;
        }
        .highlight-box .row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .highlight-box .row:last-child {
            margin-bottom: 0;
        }
        .highlight-box .icon-label {
            display: table-cell;
            font-weight: bold;
            color: #1d4ed8;
            width: 40%;
        }
        .highlight-box .icon-value {
            display: table-cell;
            color: #333;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px dashed #ddd;
            padding-top: 12px;
        }
        .folio {
            text-align: right;
            font-size: 11px;
            color: #888;
            margin-bottom: 20px;
        }
        .watermark-note {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 12px;
            color: #854d0e;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sistema de Gestión Médica</h1>
        <h2>Comprobante de Cita Médica</h2>
        <span class="badge">CONFIRMADO</span>
    </div>

    <div class="folio">
        Folio: <strong>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="watermark-note">
        <strong>Aviso:</strong> Presente este comprobante al llegar a su cita. Si necesita cancelar o reagendar,
        comuníquese con antelación mínima de 24 horas.
    </div>

    {{-- Datos de la cita --}}
    <div class="section">
        <div class="section-title">Datos de la Cita</div>
        <div class="highlight-box">
            <div class="row">
                <span class="icon-label">Doctor:</span>
                <span class="icon-value">{{ $appointment->doctor->user->name }}</span>
            </div>
            <div class="row">
                <span class="icon-label">Especialidad:</span>
                <span class="icon-value">{{ $appointment->doctor->speciality->name ?? 'No especificada' }}</span>
            </div>
            <div class="row">
                <span class="icon-label">Fecha:</span>
                <span class="icon-value">{{ $appointment->date->format('d/m/Y') }}</span>
            </div>
            <div class="row">
                <span class="icon-label">Horario:</span>
                <span class="icon-value">{{ substr($appointment->start_time, 0, 5) }} – {{ substr($appointment->end_time, 0, 5) }}</span>
            </div>
            <div class="row">
                <span class="icon-label">Estado:</span>
                <span class="icon-value">
                    <span class="status-badge">{{ $appointment->status_label }}</span>
                </span>
            </div>
            @if($appointment->reason)
            <div class="row">
                <span class="icon-label">Motivo:</span>
                <span class="icon-value">{{ $appointment->reason }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Datos del paciente --}}
    <div class="section">
        <div class="section-title">Datos del Paciente</div>
        <table class="info">
            <tr>
                <td class="label">Nombre completo</td>
                <td class="value">{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Cédula / ID</td>
                <td class="value">{{ $appointment->patient->user->id_number ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <td class="label">Correo electrónico</td>
                <td class="value">{{ $appointment->patient->user->email }}</td>
            </tr>
            <tr>
                <td class="label">Teléfono</td>
                <td class="value">{{ $appointment->patient->user->phone ?? 'No registrado' }}</td>
            </tr>
        </table>
    </div>

    {{-- Datos del doctor --}}
    <div class="section">
        <div class="section-title">Datos del Médico</div>
        <table class="info">
            <tr>
                <td class="label">Nombre</td>
                <td class="value">{{ $appointment->doctor->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Especialidad</td>
                <td class="value">{{ $appointment->doctor->speciality->name ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <td class="label">Cédula profesional</td>
                <td class="value">{{ $appointment->doctor->medical_license_number ?? 'No registrada' }}</td>
            </tr>
            <tr>
                <td class="label">Correo</td>
                <td class="value">{{ $appointment->doctor->user->email }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Este comprobante fue generado automáticamente por el Sistema de Gestión Médica.</p>
        <p>Para soporte contacte a su clínica. &mdash; Folio #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

</body>
</html>