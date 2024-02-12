<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Repositories\CollectionRepository;

class CollectionService
{
    public function __construct(
        readonly CollectionRepository $collectionRepository,
        readonly TableService $tableService
    ) {}

    public function store(array $content, int $userId): Collection {
        if ($this->tableService->exists($content['name'])) {
            throw new BadRequestException('item.error.invalidValue');
        }

        $collection = $this->collectionRepository->store($content, $userId);

        $this->tableService->createWithDefaults($content['name']);

        return $collection;
    }

    public function update(array $content, int $userId) {
        // This will be called on field creation as well
    }
}
