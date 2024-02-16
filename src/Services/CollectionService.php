<?php

namespace Sakydev\Boring\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Repositories\CollectionRepository;

class CollectionService
{
    public function __construct(
        readonly CollectionRepository $collectionRepository,
    ) {}

    public function getByName(string $name): ?Collection {
        $collection = $this->collectionRepository->getByName($name);
        if (!$collection) {
            throw new NotFoundException('item.error.collection.notFound');
        }

        return $collection;
    }

    public function list(int $page, int $limit): LengthAwarePaginator {
        return $this->collectionRepository->list($page, $limit);
    }

    public function store(array $content, int $userId): Collection {
        return $this->collectionRepository->store($content, $userId);
    }

    public function update(array $content, int $userId) {
        // This will be called on field creation as well
    }
}
