<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crea un usuario de prueba cada que ejecuto migraciones
        User::factory()->create([
            'name' => 'Miroslava Moheno',
            'email' => 'mirosmiros@gmail.com',
            'password' => bcrypt('1234'),
            'id_number' => '1234567890',
            'phone' => '9991234567',
            'adress'=> 'Mi casa',

        ])->assignRole('Doctor');
    }
}
