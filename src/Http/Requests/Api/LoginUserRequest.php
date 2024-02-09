<?php

namespace Sakydev\Boring\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'min:3',
                'max:50',
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
