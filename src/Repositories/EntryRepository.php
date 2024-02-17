<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Models\Entry;

class EntryRepository
{
    public function getByUUID(string $uuid): ?Entry {
        return (new Entry())->where('uuid', $uuid)->first();
    }

    public function list(int $page, int $limit): LengthAwarePaginator
    {
        return (new Entry())
            ->orderBy('id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function store(array $content, int $userId): Entry {
        $collection = new Entry();

        $collection->fill($content);
        $collection->created_by = $userId;

        $collection->save();
        $collection->refresh();

        return $collection;
    }

    public function destroyByUUID(string $uuid): bool {
        return (new Entry())->where('uuid', $uuid)->delete();
    }

    public function destroy(Entry $entry): bool {
        return $entry->delete();
    }
}
