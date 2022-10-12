<?php

namespace BiiiiiigMonster\Fireable\Database\Factories;

use BiiiiiigMonster\Fireable\Tests\Models\Phone;
use BiiiiiigMonster\Fireable\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneFactory extends Factory
{
    protected $model = Phone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'phone_number' => $this->faker->phoneNumber
        ];
    }
}
