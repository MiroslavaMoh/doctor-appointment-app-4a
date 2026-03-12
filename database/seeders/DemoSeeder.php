<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDoctors();
        $this->seedPatients();
        $this->seedAppointments();
    }

    // ─────────────────────────────────────────────
    //  DOCTORS  (10 médicos con usuario + horarios)
    // ─────────────────────────────────────────────
    private function seedDoctors(): void
    {
        $doctorsData = [
            ['name' => 'Dr. Carlos Pérez',      'email' => 'carlos.perez@demo.com',     'speciality' => 'Cardiología',                 'license' => 'MED-0001'],
            ['name' => 'Dra. Ana García',        'email' => 'ana.garcia@demo.com',       'speciality' => 'Pediatría',                   'license' => 'MED-0002'],
            ['name' => 'Dra. María Rodríguez',   'email' => 'maria.rodriguez@demo.com',  'speciality' => 'Ginecología y Obstetricia',   'license' => 'MED-0003'],
            ['name' => 'Dr. José Martínez',      'email' => 'jose.martinez@demo.com',    'speciality' => 'Dermatología',                'license' => 'MED-0004'],
            ['name' => 'Dr. Luis Torres',        'email' => 'luis.torres@demo.com',      'speciality' => 'Neurología',                  'license' => 'MED-0005'],
            ['name' => 'Dra. Carmen López',      'email' => 'carmen.lopez@demo.com',     'speciality' => 'Psiquiatría',                 'license' => 'MED-0006'],
            ['name' => 'Dr. Pedro Hernández',    'email' => 'pedro.hernandez@demo.com',  'speciality' => 'Traumatología y Ortopedia',   'license' => 'MED-0007'],
            ['name' => 'Dra. Isabel Díaz',       'email' => 'isabel.diaz@demo.com',      'speciality' => 'Medicina Interna',            'license' => 'MED-0008'],
            ['name' => 'Dr. Roberto Sánchez',    'email' => 'roberto.sanchez@demo.com',  'speciality' => 'Cardiología',                 'license' => 'MED-0009'],
            ['name' => 'Dra. Elena Morales',     'email' => 'elena.morales@demo.com',    'speciality' => 'Pediatría',                   'license' => 'MED-0010'],
        ];

        foreach ($doctorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'id_number' => strtoupper('ID-' . substr($data['license'], 4)),
                    'phone'    => '999' . rand(1000000, 9999999),
                    'adress'   => 'Consultorio ' . $data['license'],
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('Doctor')) {
                $user->assignRole('Doctor');
            }

            $speciality = Speciality::where('name', $data['speciality'])->first();

            $doctor = Doctors::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'speciality_id'          => $speciality?->id,
                    'medical_license_number' => $data['license'],
                    'biography'              => "Especialista en {$data['speciality']} con amplia experiencia clínica.",
                ]
            );

            // Horario: Lunes a Viernes (1–5), 08:00 – 12:45 (20 slots × 5 días)
            $this->createSchedule($doctor, [1, 2, 3, 4, 5], '08:00', '12:45');
        }
    }

    private function createSchedule(Doctors $doctor, array $days, string $from, string $to): void
    {
        $start = Carbon::createFromFormat('H:i', $from);
        $end   = Carbon::createFromFormat('H:i', $to);

        $slots = [];
        $current = $start->copy();
        while ($current->lte($end)) {
            $slotEnd = $current->copy()->addMinutes(15);
            foreach ($days as $day) {
                $slots[] = [
                    'doctor_id'   => $doctor->id,
                    'day_of_week' => $day,
                    'start_time'  => $current->format('H:i:s'),
                    'end_time'    => $slotEnd->format('H:i:s'),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            $current->addMinutes(15);
        }

        // Insert ignoring duplicates
        foreach (array_chunk($slots, 100) as $chunk) {
            DoctorSchedule::upsert($chunk, ['doctor_id', 'day_of_week', 'start_time'], ['end_time']);
        }
    }

    // ─────────────────────────────────────────────
    //  PATIENTS  (20 pacientes con usuario)
    // ─────────────────────────────────────────────
    private function seedPatients(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = User::firstOrCreate(
                ['email' => "paciente.demo{$i}@demo.com"],
                [
                    'name'     => "Paciente Demo {$i}",
                    'password' => Hash::make('password'),
                    'id_number' => "PAC-{$i}",
                    'phone'    => '998' . rand(1000000, 9999999),
                    'adress'   => "Calle Demo #{$i}",
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('Paciente')) {
                $user->assignRole('Paciente');
            }

            Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id' => rand(1, 8),
                    'allergies'     => $i % 3 === 0 ? 'Penicilina' : null,
                    'observations'  => "Paciente de prueba #{$i}",
                ]
            );
        }
    }

    // ─────────────────────────────────────────────
    //  APPOINTMENTS  (50 citas)
    // ─────────────────────────────────────────────
    private function seedAppointments(): void
    {
        $doctors  = Doctors::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->warn('No hay doctores o pacientes. Ejecuta primero el seeder de doctores y pacientes.');
            return;
        }

        // Fechas de lunes a viernes alrededor de hoy
        $weekdays = $this->getWeekdays('2026-02-23', '2026-03-27');

        // Horas válidas dentro del horario 08:00–12:30 (matches schedule Mon-Fri)
        $times = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30'];

        // status: 1=Programado, 2=Completado, 3=Cancelado

        $created = 0;
        $attempts = 0;

        while ($created < 50 && $attempts < 500) {
            $attempts++;
            $doctor  = $doctors->random();
            $patient = $patients->random();
            $date    = $weekdays[array_rand($weekdays)];
            $time    = $times[array_rand($times)];

            $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;
            $startTime = $time . ':00';

            // Verify doctor has this slot in schedule
            $hasSlot = DoctorSchedule::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('start_time', $startTime)
                ->exists();

            if (!$hasSlot) {
                continue;
            }

            // Avoid exact duplicates (same doctor, date, start_time) for non-cancelled
            $conflict = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->where('start_time', $startTime)
                ->whereIn('status', [1, 2])
                ->exists();

            if ($conflict) {
                continue;
            }

            // Determine status based on date (1=Programado, 2=Completado, 3=Cancelado)
            $isPast = Carbon::parse($date)->isPast();
            if ($isPast) {
                $status = collect([2, 2, 3])->random();
            } else {
                $status = collect([1, 1, 1, 3])->random();
            }

            $reasons = [
                'Dolor de cabeza persistente',
                'Revisión general',
                'Control de presión arterial',
                'Fiebre y malestar general',
                'Dolor en articulaciones',
                'Seguimiento de tratamiento',
                'Examen preventivo',
                'Molestias digestivas',
            ];

            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id'  => $doctor->id,
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => Carbon::createFromFormat('H:i:s', $startTime)->addMinutes(30)->format('H:i:s'),
                'duration'   => 30,
                'reason'     => $reasons[array_rand($reasons)],
                'status'     => $status,
            ]);

            $created++;
        }

        $this->command->info("✓ {$created} citas de prueba creadas.");
    }

    private function getWeekdays(string $from, string $to): array
    {
        $dates = [];
        $current = Carbon::parse($from);
        $end = Carbon::parse($to);

        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $dates[] = $current->toDateString();
            }
            $current->addDay();
        }

        return $dates;
    }
}
