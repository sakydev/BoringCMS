<?php

namespace Sakydev\Boring\Http\Requests\Api\Collection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sakydev\Boring\Models\Field;

class CreateCollectionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[0-9a-z_-]+$/i',
                Rule::unique('collections', 'name')
            ],
            'is_hidden' => [
                'sometimes',
                'required',
                'boolean',
            ],
            'description' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],
        ];
    }
}
