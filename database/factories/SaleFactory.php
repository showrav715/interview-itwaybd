<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->id : User::factory(),
            'sale_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'total' => 0, // total later update
        ];
    }
}
