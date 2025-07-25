<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'staff_id'    => $this->staff->id,
            'full_name'   => $this->staff->full_name,
            'role'        => $this->role,
            'phone'       => $this->staff->phone,
            'national_id' => $this->staff->national_id,
        ];
    }
}
