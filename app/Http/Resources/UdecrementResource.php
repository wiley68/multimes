<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UdecrementResource extends JsonResource
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
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
