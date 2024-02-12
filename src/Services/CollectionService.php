<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Repositories\CollectionRepository;
use Sakydev\Boring\Repositories\FieldRepository;

class CollectionService
{
    public function __construct(
        readonly CollectionRepository $collectionRepository,
    ) {}

    public function store(array $content, int $userId): Collection {
        return $this->collectionRepository->store($content, $userId);
    }
}
