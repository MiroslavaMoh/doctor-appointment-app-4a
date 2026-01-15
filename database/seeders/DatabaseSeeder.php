<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //Llamar al Roleseeder creado
        $this->call([
            RoleSeeder::class, //Nesecitamos que se creen los roles para luego asignarlos a los usuarios
            UserSeeder::class
        ]);

    
    }
}
