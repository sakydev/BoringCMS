<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Sakydev\Boring\Database\Factories\BoringUserFactory;

class BoringUser extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return BoringUserFactory::new();
    }

    public function forms(): HasMany {
        return $this->hasMany(Form::class, 'created_by', 'id');
    }
}
