<?php

namespace BiiiiiigMonster\Fireable\Database\Factories;

use BiiiiiigMonster\Fireable\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'age' => $this->faker->numberBetween(10, 30),
            'status' => $this->faker->numberBetween(1, 9),
            'password' => $this->faker->password,
        ];
    }
}
