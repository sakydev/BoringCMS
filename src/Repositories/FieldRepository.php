<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\Field;

class FieldRepository
{
    public function duplicateFieldExists(string $name, int $collectionId): bool {
        return (new Field())
            ->where('name', $name)
            ->where('collection_id', $collectionId)
            ->exists();
    }
    public function store(array $content, int $collectionId): Field {
        $field = new Field();

        $field->fill($content);
        $field->collection_id = $collectionId;

        $field->save();
        $field->refresh();

        return $field;
    }
}
