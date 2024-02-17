<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;

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

    public function destroyCollection(string $collectionName): void {
        $collection = $this->collectionService->getByName($collectionName);
        if (!$collection) {
            throw new NotFoundException('item.error.collection.notFound');
        }

        $this->tableService->dropTable($collectionName);
        $this->collectionService->destroy($collection);
    }


    /**
     * @throws NotFoundException
     */
    public function storeField(array $content, string $collectionName, int $userId): Field {
        $collectionDetails = $this->collectionService->getByName($collectionName);
        if (!$collectionDetails) {
            throw new NotFoundException('item.error.collection.notFound');
        }

        $field = $this->fieldService->store($content, $collectionDetails->id);
        $this->collectionService->update($content, $userId);
        $this->tableService->update($collectionName, $content);

        return $field;
    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function destroyField(string $fieldUUID, string $collectionName): void {
        $collectionDetails = $this->collectionService->getByName($collectionName);
        if (!$collectionDetails) {
            throw new NotFoundException('item.error.collection.notFound');
        }

        $fieldDetails = $this->fieldService->getByUUID($fieldUUID);
        if (!$fieldDetails) {
            throw new NotFoundException('item.error.field.notFound');
        }

        $this->fieldService->destroyByUUID($fieldUUID);
        $this->tableService->dropColumn($collectionName, $fieldDetails->name);
    }
}
