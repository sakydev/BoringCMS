<?php

namespace Sakydev\Boring\Http\Requests\Api\Field;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateFieldRequest extends FormRequest
{
    public function rules()
    {
        $collectionId = $this->route('collectionId');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[0-9a-z_-]+$/i',
                Rule::unique('fields', 'name')->where(function ($query) use ($collectionId) {
                    return $query->where('collection_id', $collectionId);
                }),
            ],
            'field_type' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-z]+$/i',
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
            'is_required' => [
                'required',
                'boolean',
            ]
        ];
    }
}
