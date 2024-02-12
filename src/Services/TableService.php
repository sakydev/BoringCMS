<?php

namespace Sakydev\Boring\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableService
{
    private function getFieldsMap(): array {
        // create
    }

    public function getDefaultFields(): array {
        return [
            'id' => [
                'type' => 'primary',
            ],
            'created_at' => [
                'type' => 'timestamp',
                'is_nullable' => false,
                'default' => '',
            ],
            'updated_at' => [
                'type' => 'timestamp',
                'is_nullable' => false,
                'default' => '',
            ],
        ];
    }

    public function exists(string $name): bool {
        return Schema::hasTable($name);
    }

    public function create(string $name, array $content): void {
        Schema::create($name, function (Blueprint $table) use ($content) {
            foreach ($content as $fieldName => $fieldRules) {
                $this->createField($table, $fieldName, $fieldRules);
            }
        });
    }

    public function createWithDefaults(string $name): void {
        $this->create($name, $this->getDefaultFields());
    }

    private function createField(Blueprint $table, string $name, array $rules): void {
        switch ($rules['type']) {
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
