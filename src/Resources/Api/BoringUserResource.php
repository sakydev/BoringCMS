<?php

namespace Sakydev\Boring\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoringUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'auth_token' => $this->auth_token,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
