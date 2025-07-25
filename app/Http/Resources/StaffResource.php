<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'full_name' => $this->full_name,
            'mother_name' => $this->mother_name,
            'birth_date' => $this->birth_date,
            'national_id' => $this->national_id,
            'address' => $this->address,
            'previous_job' => $this->previous_job,
            'education_level' => $this->education_level,
            'phone' => $this->phone,
            'created_at' => $this->created_at->toDateTimeString(),
            'mosque_id' => optional($this->mosque_staff->first())->mosque_id,
            'role' => optional($this->mosque_staff->first())->role,
            'mosque_name' => optional(optional($this->mosque_staff->first())->mosque)->name,

        ];
    }
}
