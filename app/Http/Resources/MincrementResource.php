<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MincrementResource extends JsonResource
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
            'mproduction' => new MproductionsResource($this->whenLoaded('mproduction')),
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
