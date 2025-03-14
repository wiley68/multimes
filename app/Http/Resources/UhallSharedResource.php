<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UhallSharedResource extends JsonResource
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
            'name' => $this->name,
            'factory' => new FactoryResource($this->whenLoaded('factory')),
            'silo' => new SiloResource($this->whenLoaded('silo')),
            'uproduction' => new UproductionsResource($this->whenLoaded('uproductions')->firstWhere('status', 1)),
        ];
    }
}
