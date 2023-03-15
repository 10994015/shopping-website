<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->text(),
            'image'=>$this->faker->imageUrl(),
            'description'=>$this->faker->realTExt(2000),
            'price'=>$this->faker->randomNumber(2, 3),
            'created_at'=>now(),
            'updated_at'=>now(),
            'created_by'=>1,
            'updated_by'=>1,
        ];
    }
}
