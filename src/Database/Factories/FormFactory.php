<?php

namespace Sakydev\Boring\Database\Factories;

use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        $user = BoringUser::factory()->createOne();
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(10),
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
    }
}
