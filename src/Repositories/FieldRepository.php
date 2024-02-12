<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Sakydev\Boring\Models\Field;

class FieldRepository
{
    public function nameExists(string $name, int $collectionId): bool {
        return (new Field())
            ->where('name', $name)
            ->where('collection_id', $collectionId)
            ->exists();
    }

    public function anyNameExists(array $names, int $collectionId): bool {
        return (new Field())
            ->whereIn('name', $names)
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

    public function storeMany(array $contents, int $collectionId): Collection
    {
        $fillables = (new Field())->getFillable();

        $fields = collect();

        foreach ($contents as $name => $content) {
            $content['name'] = $name;
            $content['collection_id'] = $collectionId;
            if (!isset($content['is_required'])) {
                $content['is_required'] = false;
            }

            $fields->push(Arr::only($content, $fillables));
        }

        Field::insert($fields->toArray());

        return Field::where('collection_id', $collectionId)
            ->whereIn('name', $fields->pluck('name'))
            ->get();
    }
}
