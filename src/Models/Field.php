<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sakydev\Boring\Database\Factories\FieldFactory;

class Field extends Model
{
    use HasFactory;

    protected $protected = ['created_at', 'updated_at'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return FieldFactory::new();
    }
}
