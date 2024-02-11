<?php

namespace Sakydev\Boring\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Form;
use Sakydev\Boring\Repositories\FormRepository;

class FormService
{
    public function __construct(readonly FormRepository $formRepository) {}

    public function listByUser(int $userId, int $page, int $limit): LengthAwarePaginator {
        return $this->formRepository->listByUser($userId, $page, $limit);
    }

    /**
     * @throws NotFoundException
     */
    public function getBySlugAndUser(string $slug, int $userId): Form {
        $form = $this->formRepository->getBySlugAndUser($slug, $userId);
        if (!$form) {
            throw new NotFoundException('item.error.notFound');
        }

        return $form;
    }

    public function store(array $content, int $userId): Form {
        return $this->formRepository->store($content, $userId);
    }

    /**
     * @throws NotFoundException
     */
    public function updateBySlugAndUser(array $updatedContent, string $slug, int $userId): Form {
        $form = $this->formRepository->getBySlugAndUser($slug, $userId);
        if (!$form) {
            throw new NotFoundException('item.error.notFound');
        }

        return $this->formRepository->update($form, $updatedContent);
    }

    /**
     * @throws NotFoundException
     */
    public function destroyBySlugAndUser(string $slug, int $userId): bool {
        if (!$this->formRepository->existsBySlugAndUser($slug, $userId)) {
            throw new NotFoundException('item.error.notFound');
        }

        return $this->formRepository->destroyBySlugAndUser($slug, $userId);
    }
}
