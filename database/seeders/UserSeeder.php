<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios base
        $users = [
            [
                'name' => 'Miros Admin',
                'email' => 'mirosmiros@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Administrador',
            ],
            [
                'name' => 'Dr. Freddy',
                'email' => 'doctorfreddy@example.com',
                'password' => Hash::make('1234'),
                'role' => 'Doctor',
            ],
            [
                'name' => 'Recepcionista Adriana',
                'email' => 'recepcionmediccare@example.com',
                'password' => Hash::make('1234'),
                'role' => 'Recepcionista',
            ],
            [
                'name' => 'Paciente Demo',
                'email' => 'paciente@example.com',
                'password' => Hash::make('paciente123'),
                'role' => 'Paciente',
            ],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->assignRole($data['role']); // asignar rol con Spatie
        }
    }
}
