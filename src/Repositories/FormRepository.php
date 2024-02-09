<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\Form;

class FormRepository
{
    public function getById(int $formId): ?Form {
        return (new Form())->find($formId);
    }

    public function getBySlug(string $slug): ?Form {
        return (new Form())->where('slug', $slug)->first();
    }

    public function store(array $formData): Form {
        $form = new Form();

        $form->fill($formData);
        $form->user_id = $userId;

        $form->save();

        return $form;
    }
}
