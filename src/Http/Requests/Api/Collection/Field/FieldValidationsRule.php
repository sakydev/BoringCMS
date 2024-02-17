<?php

namespace Sakydev\Boring\Http\Requests\Api\Collection\Field;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class FieldValidationsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || !is_array($value)) {
            $fail('Invalid field validation schema');

            return;
        }

        $validator = Validator::make($value, [
            'required' => 'boolean',
            'maximumLength' => 'integer',
            'between' => 'array|size:2',
            'contains' => 'string',
            'endsWith' => 'string',
            'equals' => 'string',
            'isIn' => 'array',
            'custom' => 'array',
            'custom.*.contains' => 'string',
            'custom.*.endsWith' => 'string',
            'custom.*.operator' => 'string|in:AND,OR',
            'custom.*.message' => 'string',
        ]);

        foreach ($validator->errors()->toArray() as $key => $error) {
            $fail($key . ': ' . current($error));
        }
    }
}
