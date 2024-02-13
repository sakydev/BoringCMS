<?php

namespace Sakydev\Boring\Http\Requests\Api\Collection\Field;

use Illuminate\Foundation\Http\FormRequest;

class UpdatedFieldRequest extends FormRequest
{
    public function rules()
    {
        return [
            'is_required' => [
                'sometimes',
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
