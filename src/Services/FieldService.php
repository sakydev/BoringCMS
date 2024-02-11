<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Repositories\FieldRepository;

class FieldService
{
    public function __construct(readonly FieldRepository $fieldRepository) {}

    public function store(array $content, int $collectionId): Field {
        return $this->fieldRepository->store($content, $collectionId);
    }
}
