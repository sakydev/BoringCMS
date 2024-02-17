<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Models\Entry;

class EntryRepository
{
    private string $table;

    public function setTableName(string $table) {
        $this->table = $table;
    }

    public function hasDuplicateEntry(string $fieldName, string $value): bool {
        return (new Entry())
            ->setTable($this->table)
            ->where($fieldName, $value)
            ->exists();
    }

    public function getByUUID(string $uuid): ?Entry {
        return (new Entry())
            ->setTable($this->table)
            ->where('uuid', $uuid)
            ->first();
    }

    public function list(int $page, int $limit): LengthAwarePaginator
    {
        return (new Entry())
            ->setTable($this->table)
            ->orderBy('id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function store(array $content): Entry {
        $entry = new Entry();
        $entry->setTable($this->table);

        $entry->fill($content);

        $entry->save();
        $entry->refresh();

        return $entry;
    }

    public function destroyByUUID(string $uuid): bool {
        return (new Entry())
            ->setTable($this->table)
            ->where('uuid', $uuid)
            ->delete();
    }

    public function destroy(Entry $entry): bool {
        return $entry->delete();
    }
}
