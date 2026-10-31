<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UsuarioDemoSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuarioDemoSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeder_restores_demo_credentials_without_duplicate_users(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@clase.test',
            'name' => 'Nombre anterior',
        ]);

        $this->seed(UsuarioDemoSeeder::class);
        $this->seed(UsuarioDemoSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'admin@clase.test',
            'name' => 'Administrador de práctica',
        ]);
        $this->assertNotSame('Clase2026!', $user->fresh()->password);
        $this->assertTrue(Hash::check('Clase2026!', $user->fresh()->password));
    }
}
