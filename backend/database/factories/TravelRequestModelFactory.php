<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\TravelRequestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelRequestModel>
 */
class TravelRequestModelFactory extends Factory
{
    protected $model = TravelRequestModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'requester_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
            'status' => 'solicitado',
        ];
    }
}
