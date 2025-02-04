<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UincrementResource extends JsonResource
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
            'uproduction' => new UproductionsResource($this->whenLoaded('uproduction')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'price' => $this->price,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at,
        ];
    }
}
