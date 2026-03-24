<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Envía recordatorios de citas por WhatsApp un día antes, todos los días a las 8:00 AM
Schedule::command('appointments:send-reminders')->dailyAt('08:00');

// Envía el reporte diario de citas al administrador y a los doctores todos los días a las 8:00 AM
Schedule::command('appointments:daily-report')->dailyAt('08:00');
