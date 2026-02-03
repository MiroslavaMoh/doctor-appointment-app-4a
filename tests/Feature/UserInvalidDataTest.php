<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserInvalidDataTest extends TestCase
{
    // Este es un test donde se valida la actualización de un usuario con datos inválidos
    use RefreshDatabase;
    
    public function test_actualizacion_falla_con_datos_invalidos()
    {
        // Arrange | Crear un usuario y datos inválidos
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $anotherUser = User::factory()->create(['email' => 'existing@example.com']); //Correo previo
        $role = Role::create(['name' => 'test_role']);
        $user->roles()->attach($role);

        $invalidUpdateData = [
            'name' => 'Usuario Actualizado',
            'email' => 'existing@example.com', // Email repetido
            'phone' => '987654321',
            'id_number' => '987654321',
            'adress' => 'Dirección actualizada',
            'role_id' => $role->id,
        ];

        // Act | Intentar actualizar con datos inválidos
        $this->actingAs($admin);
        $response = $this->put(route('admin.users.update', $user), $invalidUpdateData);

        // Assert | Verificar que la actualización falle
        $response->assertRedirect(); // Debería redirigir de vuelta con errores
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'existing@example.com',
        ]);
    }
}
