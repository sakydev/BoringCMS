<?php

namespace Sakydev\Boring\Database\Seeders;

use Illuminate\Database\Seeder;
use Sakydev\Boring\Models\Form;

class BoringSeeder extends Seeder
{
    public function run(): void {
        Form::factory()->create([
            'name' => 'Contact',
            'slug' => 'contact',
        ]);
    }
}
