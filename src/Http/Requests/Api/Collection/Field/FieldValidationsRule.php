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
            'unique' => 'boolean|nullable',
            'maximumLength' => 'integer|nullable',
            'between' => 'array|size:2|nullable',
            'contains' => 'string|nullable',
            'endsWith' => 'string|nullable',
            'equals' => 'string|nullable',
            'isIn' => 'array|nullable',
            'custom' => 'array|nullable',
            'custom.*.contains|nullable' => 'string',
            'custom.*.endsWith|nullable' => 'string',
            'custom.*.operator|nullable' => 'string|in:AND,OR',
            'custom.*.message|nullable' => 'string',
        ]);

        foreach ($validator->errors()->toArray() as $key => $error) {
            $fail($key . ': ' . current($error));
        }
    }
}
