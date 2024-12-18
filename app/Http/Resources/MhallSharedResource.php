<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MhallSharedResource extends JsonResource
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
            'mproductions' => MproductionsResource::collection($this->whenLoaded('mproductions')),
        ];
    }
}
