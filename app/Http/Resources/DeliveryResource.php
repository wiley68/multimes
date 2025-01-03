<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'document' => $this->document,
            'supplier' => $this->supplier,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'subdeliveries' => SubdeliveryResource::collection($this->whenLoaded('subdeliveries')),
        ];
    }
}
