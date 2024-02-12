<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Repositories\CollectionRepository;
use Sakydev\Boring\Repositories\FieldRepository;

class CollectionService
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

        $nnameExists = $this->fieldRepository->nameExists($content['name'], $collectionDetails->id);
        if ($nnameExists) {
            throw new BadRequestException('item.error.alreadyExists');
        }

        return $this->fieldRepository->store($content, $collectionDetails->id);
    }
}
