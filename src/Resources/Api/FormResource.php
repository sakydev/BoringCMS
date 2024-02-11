<?php

namespace Sakydev\Boring\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
