<?php

namespace Sakydev\Boring\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Sakydev\Boring\Models\Form;

class FormRepository
{
    public function listByUserId(int $userId, int $page, int $limit): ?Collection
    {
        return (new Form())->where('user_id', $userId)
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getById(int $formId): ?Form {
        return (new Form())->find($formId);
    }

    public function getBySlug(string $slug): ?Form {
        return (new Form())->where('slug', $slug)->first();
    }

    public function getBySlugAndUserId(string $slug, int $userId): ?Form {
        return (new Form())->where('slug', $slug)->where('user_id', $userId)->first();
    }

    public function store(array $formData, int $userId): Form {
        $form = new Form();

        $form->fill($formData);
        $form->user_id = $userId;

        $form->save();

        return $form;
    }
}
