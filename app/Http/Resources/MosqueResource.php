<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MosqueResource extends JsonResource
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
            'city_id' => $this->city_id,
            'name' => $this->name,
            'address' => $this->address,
            'area' => $this->area,
            'details' => $this->details,
            'technical_status' => $this->technical_status,
            'category' => $this->category,
            'has_female_section' => (bool) $this->has_female_section,
            'image_path' => $this->image_path ? asset( $this->image_path) : null,
        ];
    }
}
