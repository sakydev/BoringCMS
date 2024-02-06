<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[0-9a-z\s]+$/i',
                'unique:forms,name',
            ],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[0-9a-z_-]+$/i',
                'unique:forms,slug',
            ]
        ];
    }
}
