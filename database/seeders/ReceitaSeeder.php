<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receita;

class ReceitaSeeder extends Seeder
{
    public function run(): void
    {
        $receitas = [
            [
                'nome' => 'Brigadeiro',
                'descricao' => 'Doce tradicional com leite condensado e chocolate.',
                'data_registro' => now()->toDateString(),
                'custo' => 12.50,
                'tipo_receita' => 'doce',
            ],
            [
                'nome' => 'Coxinha',
                'descricao' => 'Salgado recheado com frango.',
                'data_registro' => now()->toDateString(),
                'custo' => 18.00,
                'tipo_receita' => 'salgada',
            ],
            [
                'nome' => 'Beijinho',
                'descricao' => 'Doce com coco e leite condensado.',
                'data_registro' => now()->toDateString(),
                'custo' => 10.00,
                'tipo_receita' => 'doce',
            ],
            [
                'nome' => 'Pastel',
                'descricao' => 'Pastel frito recheado.',
                'data_registro' => now()->toDateString(),
                'custo' => 15.00,
                'tipo_receita' => 'salgada',
            ],
            [
                'nome' => 'Quindim',
                'descricao' => 'Doce com gema e coco.',
                'data_registro' => now()->toDateString(),
                'custo' => 14.00,
                'tipo_receita' => 'doce',
            ],
            [
                'nome' => 'Empada',
                'descricao' => 'Salgado assado recheado.',
                'data_registro' => now()->toDateString(),
                'custo' => 16.00,
                'tipo_receita' => 'salgada',
            ],
            [
                'nome' => 'Pudim',
                'descricao' => 'Sobremesa com leite condensado.',
                'data_registro' => now()->toDateString(),
                'custo' => 20.00,
                'tipo_receita' => 'doce',
            ],
            [
                'nome' => 'Enroladinho',
                'descricao' => 'Salgado assado com salsicha.',
                'data_registro' => now()->toDateString(),
                'custo' => 11.50,
                'tipo_receita' => 'salgada',
            ],
            [
                'nome' => 'Bolo de Cenoura',
                'descricao' => 'Bolo doce com cobertura de chocolate.',
                'data_registro' => now()->toDateString(),
                'custo' => 22.00,
                'tipo_receita' => 'doce',
            ],
            [
                'nome' => 'Esfirra',
                'descricao' => 'Salgado assado recheado com carne.',
                'data_registro' => now()->toDateString(),
                'custo' => 17.50,
                'tipo_receita' => 'salgada',
            ],
        ];

        foreach ($receitas as $receita) {
            Receita::create($receita);
        }
    }
}