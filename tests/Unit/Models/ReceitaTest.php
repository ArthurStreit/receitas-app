<?php

namespace Tests\Unit\Models;

use App\Models\Receita;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\TestCase;

class ReceitaTest extends TestCase
{
    public function test_has_factory()
    {
        $traits = class_uses_recursive(Receita::class);

        $this->assertContains(HasFactory::class, $traits);
    }

    public function test_tabela_receitas()
    {
        $receita = new Receita();

        $this->assertSame('receitas', $receita->getTable());
    }

    public function test_fillable_receita()
    {
        $receita = new Receita();

        $this->assertSame([
            'nome',
            'descricao',
            'data_registro',
            'custo',
            'tipo_receita',
            'status',
        ], $receita->getFillable());
    }

    public function test_campos_fillable()
    {
        $receita = new Receita();

        $this->assertTrue($receita->isFillable('nome'));
        $this->assertTrue($receita->isFillable('descricao'));
        $this->assertTrue($receita->isFillable('data_registro'));
        $this->assertTrue($receita->isFillable('custo'));
        $this->assertTrue($receita->isFillable('tipo_receita'));
        $this->assertTrue($receita->isFillable('status'));
    }

    public function test_campos_bloqueados()
    {
        $receita = new Receita();

        $this->assertFalse($receita->isFillable('id'));
        $this->assertFalse($receita->isFillable('created_at'));
        $this->assertFalse($receita->isFillable('updated_at'));
    }
}
