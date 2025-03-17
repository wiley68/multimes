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
            'finished_at' => $this->finished_at,
            'stock' => $this->stock,
            'price' => $this->price,
            'product' => new ProductResource($this->whenLoaded('product')),
            'group_number' => $this->group_number,
            'partida_number' => $this->partida_number,
            'created_at' => $this->created_at,
            'uhall' => new UhallResource($this->whenLoaded('uhall')),
            'udecrements' => UdecrementResource::collection($this->whenLoaded('udecrements')),
            'uincrements' => UincrementResource::collection($this->whenLoaded('uincrements')),
        ];
    }
}
