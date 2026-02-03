<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    // Este es un test donde se valida la actualización de un usuario con datos válidos
    use RefreshDatabase;
    public function test_usuario_autenticado_puede_actualizar_un_usuario()
    {
        // Arrange | Crear un usuario y datos para actualizar
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $role = Role::create(['name' => 'test_role']); // Crear un rol de prueba
        $user->roles()->attach($role);

        $updateData = [
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@example.com',
            'phone' => '987654321',
            'id_number' => '987654321',
            'adress' => 'Dirección actualizada',
            'role_id' => $role->id,
        ];

        // Act |  Intentar actualizar el usuario
        $this->actingAs($admin);
        $response = $this->put(route('admin.users.update', $user), $updateData);

        // Assert | Verificar que la actualización sea exitosa
        $response->assertRedirect(route('admin.users.edit', $user->id));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'actualizado@example.com',
            'name' => 'Usuario Actualizado',
        ]);
    }

}
