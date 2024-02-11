<?php

namespace Sakydev\Boring\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sakydev\Boring\Database\Factories\FieldFactory;

class Field extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public const TYPE_SHORT_TEXT = 'short_text';
    public const TYPE_LONG_TEXT = 'long_text';
    public const TYPE_MARKDOWN = 'markdown';
    public const TYPE_RICHTEXT = 'richtext';
    public const TYPE_FLOAT = 'float';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_LIST = 'list';
    public const TYPE_JSON = 'json';

    public const SUPPORTED_TYPES = [
        self::TYPE_SHORT_TEXT => 'string',
        self::TYPE_LONG_TEXT => 'text',
        self::TYPE_MARKDOWN => 'text',
        self::TYPE_RICHTEXT => 'text',
        self::TYPE_FLOAT => 'float',
        self::TYPE_INTEGER => 'integer',
        self::TYPE_LIST => 'array',
        self::TYPE_JSON => 'json'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return FieldFactory::new();
    }
}
