<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserDataValidateTest extends TestCase
{
    // Este es un test donde se valida la creación de un usuario con datos válidos y normalitos
    use RefreshDatabase;
    public function test_usuario_autenticado_puede_crear_un_usuario_con_datos_validos()
    {
        // Arrange | Crear un usuario administrador y datos válidos para el nuevo usuario
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'test_role']);

        $userData = [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '123456789',
            'id_number' => '123456789',
            'adress' => 'Dirección de prueba',
            'role_id' => $role->id,
        ];

        // Act | Intentar crear el nuevo usuario
        $this->actingAs($admin);
        $response = $this->post(route('admin.users.store'), $userData);

        // Assert | Verificar que la creación sea exitosa
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@example.com',
            'name' => 'Nuevo Usuario',
        ]);
    }
}
