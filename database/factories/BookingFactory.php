<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "store_id" => $this->faker->numberBetween($min = 1, $max = 21),
            "user_id" => $this->faker->numberBetween($min = 1, $max = 15),
            "booking_date" => $this->faker->dateTimeBetween($startDate = "now",$endDate = "+1 month"),
            "booking_time" => $this->faker->time(),
            "booking_number" => $this->faker->numberBetween($min = 1, $max = 10)
        ];
    }
}
