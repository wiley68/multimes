<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UproductionsResource extends JsonResource
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
            'status' => $this->status,
            'production_days' => $this->production_days,
            'finished_at' => $this->finished_at,
            'stock' => $this->stock,
            'price' => $this->price,
            'product' => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->created_at,
            'uhall' => new UhallResource($this->whenLoaded('uhall')),
        ];
    }
}
