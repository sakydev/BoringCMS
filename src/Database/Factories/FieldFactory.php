<?php

namespace Sakydev\Boring\Database\Factories;

use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {
        return [
            'name' => fake()->bothify('??????????'),
            'uuid' => fake()->uuid(),
            'collection_id' => Collection::factory()->createOne()->id,
            'field_type' => array_rand(Field::SUPPORTED_TYPES),
            'is_required' => false,
        ];
    }
}
