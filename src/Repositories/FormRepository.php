<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Models\Form;

class FormRepository
{
    public function listByUser(int $userId, int $page, int $limit): LengthAwarePaginator
    {
        return (new Form())->where('user_id', $userId)
            ->orderBy('id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getById(int $formId): ?Form {
        return (new Form())->find($formId);
    }

    public function getBySlug(string $slug): ?Form {
        return (new Form())->where('slug', $slug)->first();
    }

    public function existsBySlugAndUser(string $slug, int $userId): bool {
        return (new Form())->where('slug', $slug)->where('user_id', $userId)->exists();
    }

    public function getBySlugAndUser(string $slug, int $userId): ?Form {
        return (new Form())->where('slug', $slug)->where('user_id', $userId)->first();
    }

    public function store(array $content, int $userId): Form {
        $form = new Form();

        $form->fill($content);
        $form->user_id = $userId;

        $form->save();
        $form->refresh();

        return $form;
    }

    public function update(Form $form, array $updatedFields): Form {
        $form->fill($updatedFields);

        $form->save();
        $form->refresh();

        return $form;
    }

    public function destroyBySlugAndUser(string $slug, int $userId): bool {
        return (new Form())->where('slug', $slug)->where('user_id', $userId)->delete();
    }
}
