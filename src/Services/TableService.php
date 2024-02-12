<?php

namespace Sakydev\Boring\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableService
{
    public function getDefaultFields(): array {
        return [
            'id' => [
                'field_type' => 'primary',
            ],
            'created_at' => [
                'field_type' => 'timestamp',
                'is_nullable' => false,
                'is_default' => true,
                'default' => '',
            ],
            'updated_at' => [
                'field_type' => 'timestamp',
                'is_nullable' => false,
                'is_default' => true,
                'default' => '',
            ],
        ];
    }

    public function exists(string $name): bool {
        return Schema::hasTable($name);
    }

    public function store(string $name, array $content): void {
        Schema::create($name, function (Blueprint $table) use ($content) {
            foreach ($content as $fieldName => $fieldRules) {
                $this->storeField($table, $fieldName, $fieldRules);
            }
        });
    }

    public function storeWithDefaults(string $name): void {
        $this->store($name, $this->getDefaultFields());
    }

    private function storeField(Blueprint $table, string $name, array $rules): void {
        switch ($rules['field_type']) {
            case 'primary':
                $table->id($name);
                break;
            case 'string':
                $table->string($name);
                break;
            case 'timestamp':
                $field = $table->timestamp($name);

                if ($rules['is_nullable']) {
                    $field->nullable();
                }

                if (!empty($rules['default'])) {
                    $field->default($rules['default']);
                }

                break;
            default:
                // Handle other types as needed
                break;
        }
    }
}
