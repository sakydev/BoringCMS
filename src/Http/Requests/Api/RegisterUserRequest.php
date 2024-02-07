<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'min:3',
                'max:100',
                'unique:boring_users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
            ]
        ];
    }
}
