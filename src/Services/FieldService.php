<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Repositories\CollectionRepository;
use Sakydev\Boring\Repositories\FieldRepository;

class FieldService
{
    public function __construct(
        readonly FieldRepository $fieldRepository,
        readonly CollectionRepository $collectionRepository,
    ) {}

    public function store(array $content, string $collectionName): Field {
        $collectionDetails = $this->collectionRepository->getByName($collectionName);
        if (!$collectionDetails) {
            throw new NotFoundException('item.error.notFound');
        }

        return $this->fieldRepository->store($content, $collectionDetails->id);
    }
}
