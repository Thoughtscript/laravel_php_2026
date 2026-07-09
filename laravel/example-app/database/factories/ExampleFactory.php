<?php

namespace Database\Factories;

use App\Models\Example;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Example>
 */
class ExampleFactory extends Factory
{
public function definition(): array
    {   
        // https://github.com/FakerPHP/Faker
        return [
            'name' => fake()->name(),
            'note' => fake()->text(),
        ];
    }
}
