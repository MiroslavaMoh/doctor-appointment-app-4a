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
            RoleSeeder::class,
            UserSeeder::class
        ]);

        //Crea un usuario de prueba cada que ejecuto migraciones
        User::factory()->create([
            'name' => 'Miroslava Moheno',
            'email' => 'mirosmiros@gmail.com',
            'password' => bcrypt('1234')
        ]);
    }
}
