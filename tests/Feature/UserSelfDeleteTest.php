<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSelfDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_no_puede_eliminar_su_propia_cuenta()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $this->actingAs($user);
        $response = $this->delete(route('admin.users.destroy', $user));

        // Assert
        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}

/*
class UserSelfDeleteTest extends TestCase
{
    use Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_un_usuario_no_puede_eliminarse_a_si_mismo()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}*/


