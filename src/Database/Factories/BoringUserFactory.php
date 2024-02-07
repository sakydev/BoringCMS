<?php

namespace Sakydev\Boring\Database\Factories;

use Sakydev\Boring\Models\BoringUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<BoringUser>
 */
class BoringUserFactory extends Factory
{
    protected $model = BoringUser::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
        ];
    }
}
