<?php

namespace Sakydev\Boring\Http\Requests\Api\Collection\Field;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sakydev\Boring\Models\Field;

class CreateFieldRequest extends FormRequest
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
            ],
            'field_type' => [
                'required',
                'string',
                Rule::in(array_keys(Field::SUPPORTED_TYPES))
            ],
            'is_required' => [
                'required',
                'boolean',
            ],
            'validation' => [
                'sometimes',
                'required',
                'json',
            ],
            'condition' => [
                'sometimes',
                'required',
                'json',
            ],
        ];
    }
}
