<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                /*Rule::unique('folders', 'name')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),*/
            ],
            'slug' => [
                'required',
                'unique:forms'
            ]
        ];
    }
}
