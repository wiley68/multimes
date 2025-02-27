<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MproductionsResource extends JsonResource
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
            'group_number' => $this->group_number,
            'partida_number' => $this->partida_number,
            'created_at' => $this->created_at,
            'mhall' => new MhallResource($this->whenLoaded('mhall')),
            'mdecrements' => MdecrementResource::collection($this->whenLoaded('mdecrements')),
            'mincrements' => MincrementResource::collection($this->whenLoaded('mincrements')),
        ];
    }
}
