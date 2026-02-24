<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialities = ['Cardiología', 'Pediatría', 'Ginecología y Obstetricia', 'Dermatología', 'Neurología', 'Psiquiatría', 'Traumatología y Ortopedia', 'Medicina Interna'];

        foreach ($specialities as $speciality) {
            Speciality::create([
                'name' => $speciality,
            ]);
        }
    }
}
