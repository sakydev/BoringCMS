<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\Field;

class FieldRepository
{
    public function store(array $content, int $collectionId): Field {
        $field = new Field();

        $field->fill($content);
        $field->collection_id = $collectionId;

        $field->save();
        $field->refresh();

        return $field;
    }
}
