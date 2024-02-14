<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;

class BoringTestService
{
    public function __construct(readonly CollectionFieldService $collectionFieldService) {}

    public function storeTestCollection(array $content, ?int $userId): Collection
    {
        if (!$userId) {
            $userId = BoringUser::factory()->createOne()->id;
        }

        $content = [
            'name' => $content['name'] ?? fake()->bothify('??????????'),
            'description' => $content['description'] ?? fake()->text(100),
            'is_hidden' => $content['is_hidden'] ?? fake()->boolean(),
        ];

        return $this->collectionFieldService->storeCollection($content, $userId);
    }

    public function storeTestField(array $content, string $collectionName, ?int $userId): Field
    {
        if (!$userId) {
            $userId = BoringUser::factory()->createOne()->id;
        }

        $content = [
            'name' => $content['name'] ?? fake()->bothify('??????????'),
            'field_type' => $content['description'] ?? array_rand(Field::SUPPORTED_TYPES),
            'is_required' => $content['is_hidden'] ?? fake()->boolean(),
        ];

        return $this->collectionFieldService->storeField($content, $collectionName, $userId);
    }


}
