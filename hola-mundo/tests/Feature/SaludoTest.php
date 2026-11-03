<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SaludoTest extends TestCase
{
    public static function rutasDelSaludo(): array
    {
        return [
            'inicio' => ['/'],
            'hola' => ['/hola'],
            'bienvenida' => ['/bienvenida'],
        ];
    }

    #[DataProvider('rutasDelSaludo')]
    public function test_las_rutas_renderizan_el_saludo(string $ruta): void
    {
        $this->get($ruta)
            ->assertOk()
            ->assertViewIs('hola')
            ->assertSeeText('Bienvenidos a mi primera aplicación Laravel')
            ->assertSeeText('Hola, Fernando.')
            ->assertSeeText('ISC-305 Programación Web')
            ->assertSeeText('Ingeniería en Sistemas')
            ->assertDontSee('{{ $nombre }}', false)
            ->assertDontSee('<?php', false);
    }
}
