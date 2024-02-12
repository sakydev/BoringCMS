<?php

namespace Sakydev\Boring\Services;

use Illuminate\Database\Eloquent\Collection;
use Sakydev\Boring\Exceptions\BadRequestException;
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

        $nameExists = $this->fieldRepository->nameExists($content['name'], $collectionDetails->id);
        if ($nameExists) {
            throw new BadRequestException('item.error.alreadyExists');
        }

        return $this->fieldRepository->store($content, $collectionDetails->id);
    }

    public function storeMany(array $content, int $collectionId): Collection {
        $nameExists = $this->fieldRepository->anyNameExists(array_keys($content), $collectionId);
        if ($nameExists) {
            throw new BadRequestException('item.error.alreadyExists');
        }

        return $this->fieldRepository->storeMany($content, $collectionId);
    }
}
