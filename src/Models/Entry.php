<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sakydev\Boring\Database\Factories\CollectionFactory;

class Entry extends Model
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

    public function collection(): BelongsTo {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
}
