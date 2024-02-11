<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Sakydev\Boring\Models\Form;

class FormRepository
{
    public function list(int $page, int $limit): LengthAwarePaginator
    {
        return (new Form())
            ->orderBy('id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getById(int $formId): ?Form {
        return (new Form())->find($formId);
    }

    public function existsBySlug(string $slug): bool {
        return (new Form())->where('slug', $slug)->exists();
    }

    public function getBySlug(string $slug): ?Form {
        return (new Form())->where('slug', $slug)->first();
    }

    public function store(array $content, int $userId): Form {
        $form = new Form();

        $form->fill($content);
        $form->created_by = $userId;

        $form->save();
        $form->refresh();

        return $form;
    }

    public function update(Form $form, array $updatedFields, int $userId): Form {
        $form->fill($updatedFields);
        $form->updated_by = $userId;

        $form->save();
        $form->refresh();

        return $form;
    }

    public function destroyBySlug(string $slug): bool {
        return (new Form())->where('slug', $slug)->delete();
    }
}
