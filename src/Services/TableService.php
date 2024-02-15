<?php

namespace Sakydev\Boring\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\Field;

class TableService
{
    public function getDefaultFields(): array {
        return [
            'id' => [
                'field_type' => 'primary',
                'is_default' => true,
            ],
            'created_at' => [
                'field_type' => 'timestamp',
                'is_default' => true,
                'default' => '',
            ],
            'updated_at' => [
                'field_type' => 'timestamp',
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

    public function update(string $name, array $content): void {
        Schema::table($name, function (Blueprint $table) use ($content) {
            $this->storeField($table, $content['name'], $content);
        });
    }

    public function updateMany(string $name, array $content): void {
        Schema::table($name, function (Blueprint $table) use ($content) {
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
                $field = $table->id($name);
                break;
            case Field::TYPE_SHORT_TEXT:
                $field = $table->string($name);
                break;
            case Field::TYPE_LONG_TEXT:
                $field = $table->longText($name);
                break;
            case Field::TYPE_TIMESTAMP:
                $field = $table->timestamp($name);
                break;
            default:
                // Handle other types as needed
                break;
        }

        if (!empty($rules['is_nullable']) || (empty($rules['is_required']) && empty($rules['is_default']))) {
            $field->nullable();
        } elseif (!empty($rules['default'])) {
            $field->default($rules['default']);
        }
    }

    public function dropColumn(string $name, string $column): void {
        Schema::dropColumns($name, $column);
    }
}
