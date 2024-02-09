<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Sakydev\Boring\Database\Factories\BoringUserFactory;

class BoringUser extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return BoringUserFactory::new();
    }
}
