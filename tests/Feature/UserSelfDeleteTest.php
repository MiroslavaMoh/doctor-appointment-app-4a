<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserSelfDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_no_puede_eliminar_su_propia_cuenta()
    {
        // Arrange | Crear un usuario
        $user = User::factory()->create();

        // Act | Intentar eliminar su propia cuenta
        $this->actingAs($user);
        $response = $this->delete(route('admin.users.destroy', $user));

        // Assert | Verificar que la respuesta sea 403 Forbidden
        $response->assertStatus(403);

        //Check | Verificar que el usuario aún existe en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    
}




