<?php

namespace Sakydev\Boring\Database\Factories;

use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Collection>
 */
class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        $user = BoringUser::factory()->createOne();

        return [
            'name' => fake()->bothify('??????????'),
            'description' => fake()->text(100),
            'is_hidden' => fake()->boolean(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
    }
}
