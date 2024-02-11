<?php

namespace Sakydev\Boring\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'collection_id' => $this->collection_id,
            'name' => $this->name,
            'field_type' => $this->field_type,
            'validation' => $this->validation,
            'condition' => $this->condition,
            'is_required' => $this->is_required,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
