<?php

namespace Database\Factories;

use App\Models\Receita;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceitaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receita::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->words(2, true),
            'descricao' => $this->faker->sentence(),
            'data_registro' => $this->faker->date(),
            'custo' => $this->faker->randomFloat(2, 10, 500),
            'tipo_receita' => $this->faker->randomElement(['doce', 'salgada']),
            'status' => $this->faker->randomElement(['ativo', 'inativo']),
        ];
    }
}
