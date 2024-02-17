<?php

namespace Sakydev\Boring\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sakydev\Boring\Database\Factories\CollectionFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $is_hidden
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HasMany|Field $fields
 * @property string|Carbon $created_at
 * @property string|Carbon $updated_at
 */
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
