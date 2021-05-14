<?php

namespace Database\Factories;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class FavoriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "store_id" => $this->faker->numberBetween($min = 1, $max = 21),
            "user_id" => $this->faker->numberBetween($min = 1, $max = 20),
        ];
    }
}
