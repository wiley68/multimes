<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiloResource extends JsonResource
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
            'mhalls' => MhallResource::collection($this->whenLoaded('mhalls')),
            'uhalls' => UhallResource::collection($this->whenLoaded('uhalls')),
        ];
    }
}
