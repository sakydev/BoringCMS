<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Models\Collection;

class EntryRepository
{
    public function getByName(string $name): ?Collection {
        return (new Collection())->where('name', $name)->first();
    }

    public function list(int $page, int $limit): LengthAwarePaginator
    {
        return (new Collection())
            ->orderBy('id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function store(array $content, int $userId): Collection {
        $collection = new Collection();

        $collection->fill($content);
        $collection->created_by = $userId;

        $collection->save();
        $collection->refresh();

        return $collection;
    }

    public function destroyByName(string $name): bool {
        return (new Collection())->where('name', $name)->delete();
    }

    public function destroy(Collection $collection): bool {
        return $collection->delete();
    }
}
