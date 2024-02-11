<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\Collection;

class CollectionRepository
{
    public function getByName(string $name): ?Collection {
        return (new Collection())->where('name', $name)->first();
    }
    public function store(array $content, int $userId): Collection {
        $collection = new Collection();

        $collection->fill($content);
        $collection->created_by = $userId;

        $collection->save();
        $collection->refresh();

        return $collection;
    }
}
