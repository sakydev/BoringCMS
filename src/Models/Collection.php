<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sakydev\Boring\Database\Factories\CollectionFactory;

class Collection extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CollectionFactory::new();
    }

    public function fields(): HasMany {
        return $this->hasMany(Field::class, 'collection_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Register the "deleting" event to delete related fields
        static::deleting(function ($collection) {
            $collection->fields()->delete();
        });
    }
}
