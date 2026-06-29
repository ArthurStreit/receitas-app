<?php

namespace Tests\Feature;

use App\Models\Receita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceitaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticatedUserCanFilterReceitasByDataRegistro()
    {
        $user = User::factory()->create();

        $receitaFiltrada = Receita::factory()->create([
            'nome' => 'Bolo de Cenoura',
            'data_registro' => '2026-04-10',
            'status' => 'ativo',
        ]);

        $receitaNaoFiltrada = Receita::factory()->create([
            'nome' => 'Coxinha',
            'data_registro' => '2026-04-11',
            'status' => 'ativo',
        ]);

        $response = $this->actingAs($user)->get(route('receitas.index', [
            'data_registro' => '2026-04-10',
        ]));

        $response->assertOk();
        $response->assertSee($receitaFiltrada->nome);
        $response->assertDontSee($receitaNaoFiltrada->nome);
    }

    public function testAuthenticatedUserCanFilterReceitasByStatus()
    {
        $user = User::factory()->create();

        $receitaAtiva = Receita::factory()->create([
            'nome' => 'Pudim',
            'status' => 'ativo',
        ]);

        $receitaInativa = Receita::factory()->create([
            'nome' => 'Empada',
            'status' => 'inativo',
        ]);

        $response = $this->actingAs($user)->get(route('receitas.index', [
            'status' => 'ativo',
        ]));

        $response->assertOk();
        $response->assertSee($receitaAtiva->nome);
        $response->assertDontSee($receitaInativa->nome);
    }

    public function testAuthenticatedUserCanExportReceitasToPdf()
    {
        $user = User::factory()->create();

        Receita::factory()->create([
            'nome' => 'Lasanha',
            'status' => 'ativo',
        ]);

        $response = $this->actingAs($user)->get(route('receitas.exportar.pdf', [
            'status' => 'ativo',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', 'attachment; filename="receitas.pdf"');
    }
}
