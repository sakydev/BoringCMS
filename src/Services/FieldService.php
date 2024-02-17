<?php

namespace Sakydev\Boring\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;
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

    /**
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function getByUUID(string $uuid): ?Field {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestException('item.error.field.invalidUUID');
        }

        $field = $this->fieldRepository->getByUUID($uuid);
        if (!$field) {
            throw new NotFoundException('item.error.field.notFound');
        }

        return $field;
    }

    public function list(int $page, int $limit): LengthAwarePaginator {
        return $this->fieldRepository->list($page, $limit);
    }

    /**
     * @throws BadRequestException
     */
    public function store(array $content, int $collectionId): Field {
        $nameExists = $this->fieldRepository->nameExists($content['name'], $collectionId);
        if ($nameExists) {
            throw new BadRequestException('item.error.alreadyExists');
        }

        return $this->fieldRepository->store($content, $collectionId);
    }

    /**
     * @throws BadRequestException
     */
    public function storeMany(array $content, int $collectionId): Collection {
        $nameExists = $this->fieldRepository->anyNameExists(array_keys($content), $collectionId);
        if ($nameExists) {
            throw new BadRequestException('item.error.alreadyExists');
        }

        return $this->fieldRepository->storeMany($content, $collectionId);
    }

    /**
     * @throws NotFoundException
     */
    public function update(array $updatedContent, string $uuid, int $userId): Field {
        $field = $this->fieldRepository->getByUUID($uuid);
        if (!$field) {
            throw new NotFoundException('item.error.field.notFound');
        }

        return $this->fieldRepository->update($field, $updatedContent, $userId);
    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function destroyByUUID(string $uuid): bool {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestException('item.error.field.invalidUUID');
        }

        return $this->fieldRepository->destroyByUUID($uuid);
    }
}
