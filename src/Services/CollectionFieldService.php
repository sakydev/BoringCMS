<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Models\Collection;

class CollectionFieldService
{
    public function __construct(
        readonly CollectionService $collectionService,
        readonly FieldService $fieldService,
        readonly TableService $tableService,
    ) {

    }

    public function storeCollection(array $content, int $userId): Collection {
        if ($this->tableService->exists($content['name'])) {
            throw new BadRequestException('item.error.invalidValue');
        }

        // 1/3: create collection table entry
        $collection = $this->collectionService->store($content, $userId);

        // 2/3: create collection entries table with default fields
        $defaultFields = $this->tableService->getDefaultFields();
        $this->tableService->store($content['name'], $defaultFields);

        // 3/3: create entries in fields table
        $this->fieldService->storeMany($defaultFields, $collection->id);

        return $collection;
    }

    public function updateCollection() {}
    public function destroyCollection() {}
    public function storeField() {}
    public function updateField() {}
    public function deleteField() {}
}
