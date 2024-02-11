<?php

namespace Sakydev\Boring\Database\Factories;

use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Collection>
 */
class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(10),
            'description' => fake()->text(100),
            'is_hidden' => fake()->boolean(),
            'user_id' => BoringUser::factory()->createOne()->id,
        ];
    }
}
