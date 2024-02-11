<?php

namespace Sakydev\Boring\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

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
                'regex:/^[0-9a-z\s]+$/i'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'min:3',
                'max:100',
                'unique:users,email',
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
