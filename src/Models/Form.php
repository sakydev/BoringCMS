<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sakydev\Boring\Database\Factories\FormFactory;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'name', 'slug'];
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return FormFactory::new();
    }

    public function user()
    {
        // A form belongs to a user with explicit foreign key and local key
        return $this->belongsTo(BoringUser::class, 'created_by', 'id');
    }
}
