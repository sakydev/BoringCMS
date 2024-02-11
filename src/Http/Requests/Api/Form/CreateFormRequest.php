<?php

namespace Sakydev\Boring\Http\Requests\Api\Form;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateFormRequest extends FormRequest
{
    public function rules()
    {
        $userId = Auth::id();

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[0-9a-z\s]+$/i',
                Rule::unique('forms', 'name')
            ],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[0-9a-z_-]+$/i',
                Rule::unique('forms', 'slug')
            ]
        ];
    }
}
