<?php

namespace Tests\Feature;

use App\Models\Persona;
use App\Models\User;
use Database\Seeders\PersonaSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PersonaTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_from_personas_to_login(): void
    {
        $response = $this->get('/personas');

        $response->assertRedirectToRoute('login');
    }

    public function test_authenticated_user_can_see_people_with_formatted_birth_dates(): void
    {
        $user = User::factory()->create(['name' => 'Usuario de práctica']);
        Persona::factory()->create([
            'dni' => '0801199012345',
            'nombre' => 'Ana López',
            'fecha_nacimiento' => '1990-04-23',
        ]);
        Persona::factory()->create(['nombre' => 'Carlos Pérez']);

        $response = $this->actingAs($user)->get('/personas');

        $response->assertOk()
            ->assertViewIs('personas.index')
            ->assertSee('Usuario de práctica')
            ->assertSee('0801199012345')
            ->assertSee('23/04/1990')
            ->assertSeeInOrder(['Ana López', 'Carlos Pérez']);

        $table = new \DOMDocument;
        @$table->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
        $xpath = new \DOMXPath($table);
        $this->assertSame(4, $xpath->query('//thead/tr/th')->length);
        $this->assertSame(0, $xpath->query('//tbody//th')->length);
        $this->assertSame(4, $xpath->query('//tbody/tr[1]/td')->length);
        $this->assertSame(4, $xpath->query('//tbody/tr[2]/td')->length);
    }

    public function test_empty_list_displays_a_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/personas');

        $response->assertOk()->assertSee('No hay personas registradas.');
    }

    public function test_person_name_is_escaped(): void
    {
        $user = User::factory()->create();
        Persona::factory()->create(['nombre' => '<script>alert(1)</script>']);

        $response = $this->actingAs($user)->get('/personas');

        $response->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_seeder_creates_ten_people_without_duplicates_when_repeated(): void
    {
        $this->seed(PersonaSeeder::class);
        $people = Persona::orderBy('id')->get()->toArray();

        $this->seed(PersonaSeeder::class);

        $this->assertDatabaseCount('personas', 10);
        $this->assertSame($people, Persona::orderBy('id')->get()->toArray());
    }
}
