<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_open_the_login_form(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Iniciar sesión')
            ->assertSee('name="email"', false)
            ->assertSee('type="password"', false);
    }

    public function test_guest_is_redirected_from_inicio_to_login(): void
    {
        $response = $this->get('/inicio');

        $response->assertRedirectToRoute('login');
        $this->assertGuest();
    }

    public function test_demo_credentials_authenticate_the_seeded_user(): void
    {
        $this->seed();
        $user = User::where('email', 'admin@clase.test')->firstOrFail();

        $response = $this->post('/login', [
            'email' => 'admin@clase.test',
            'password' => 'Clase2026!',
        ]);

        $response->assertRedirectToRoute('inicio');
        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_preserves_only_email_and_does_not_authenticate(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrecta',
        ]);

        $response->assertRedirectToRoute('login')
            ->assertInvalid(['email' => 'Correo o contraseña incorrectos.'])
            ->assertSessionHasInput('email', $user->email)
            ->assertSessionMissingInput('password');
        $this->assertGuest();
    }

    public function test_email_and_password_are_required(): void
    {
        $response = $this->from('/login')->post('/login', []);

        $response->assertRedirectToRoute('login')->assertInvalid([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
        $this->assertGuest();
    }

    public function test_invalid_email_is_rejected(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'correo-invalido',
            'password' => 'Clase2026!',
        ]);

        $response->assertRedirectToRoute('login')->assertInvalid([
            'email' => 'The email field must be a valid email address.',
        ]);
        $this->assertGuest();
    }

    public function test_password_must_be_a_string(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@clase.test',
            'password' => ['incorrecta'],
        ]);

        $response->assertRedirectToRoute('login')->assertInvalid([
            'password' => 'The password field must be a string.',
        ]);
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login_to_inicio(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirectToRoute('inicio');
    }

    public function test_authenticated_user_can_see_inicio_and_their_name(): void
    {
        $user = User::factory()->create(['name' => 'Usuario de práctica']);

        $response = $this->actingAs($user)->get('/inicio');

        $response->assertOk()
            ->assertViewIs('inicio')
            ->assertSee('Ingresaste correctamente al sistema de personas.')
            ->assertSee('Usuario de práctica');
    }

    public function test_user_name_is_escaped_in_the_shared_layout(): void
    {
        $user = User::factory()->create(['name' => '<script>alert(1)</script>']);

        $response = $this->actingAs($user)->get('/inicio');

        $response->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_logout_removes_authentication_and_session_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['datoDePractica' => 'temporal'])
            ->post('/logout');

        $response->assertRedirectToRoute('login')
            ->assertSessionMissing('datoDePractica');
        $this->assertGuest();
        $this->get('/inicio')->assertRedirectToRoute('login');
    }

    public function test_seventh_login_attempt_in_one_minute_is_rate_limited(): void
    {
        $this->freezeTime();
        $user = User::factory()->create();
        $credentials = ['email' => $user->email, 'password' => 'incorrecta'];
        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->post('/login', $credentials)->assertInvalid(['email']);
        }

        $response = $this->post('/login', $credentials);

        $response->assertTooManyRequests();
        $this->assertGuest();
    }
}
