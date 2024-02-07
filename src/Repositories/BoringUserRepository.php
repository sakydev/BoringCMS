<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\BoringUser;

class UserRepository
{
    public function getById(int $formId): ?Boring {
        return (new Boring())->find($formId);
    }
    public function store(array $formData): Boring {
        $form = new Boring();

        $form->fill($formData);
        $form->save();

        return $form;
    }
}
