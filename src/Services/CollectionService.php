<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Repositories\CollectionRepository;

class CollectionService
{
    public function __construct(
        readonly CollectionRepository $collectionRepository,
    ) {}

    public function store(array $content, int $userId): Collection {
        return $this->collectionRepository->store($content, $userId);
    }

    public function update(array $content, int $userId) {
        // This will be called on field creation as well
    }
}
