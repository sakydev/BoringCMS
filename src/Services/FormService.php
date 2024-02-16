<?php

namespace Sakydev\Boring\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Form;
use Sakydev\Boring\Repositories\FormRepository;

class FormService
{
    public function __construct(readonly FormRepository $formRepository) {}

    public function list(int $page, int $limit): LengthAwarePaginator {
        return $this->formRepository->list($page, $limit);
    }

    /**
     * @throws NotFoundException
     */
    public function getBySlug(string $slug): Form {
        $form = $this->formRepository->getBySlug($slug);
        if (!$form) {
            throw new NotFoundException('item.error.form.notFound');
        }

        return $form;
    }

    public function store(array $content, int $userId): Form {
        return $this->formRepository->store($content, $userId);
    }

    /**
     * @throws NotFoundException
     */
    public function update(array $updatedContent, string $slug, int $userId): Form {
        $form = $this->formRepository->getBySlug($slug);
        if (!$form) {
            throw new NotFoundException('item.error.form.notFound');
        }

        return $this->formRepository->update($form, $updatedContent, $userId);
    }

    /**
     * @throws NotFoundException
     */
    public function destroyBySlug(string $slug): bool {
        if (!$this->formRepository->existsBySlug($slug)) {
            throw new NotFoundException('item.error.form.notFound');
        }

        return $this->formRepository->destroyBySlug($slug);
    }
}
