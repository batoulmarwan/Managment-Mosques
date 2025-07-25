<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMosqueManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         
        return [
                'full_name' => 'required|string',
                'mother_name' => 'required|string',
                'birth_date' => 'required|date',
                'national_id' => 'required|string',
                'address' => 'required|string',
                'previous_job' => 'nullable|string',
                'education_level' => 'nullable|string',
                'phone' => 'nullable|string',
                'mosque_id' => 'required|exists:mosques,id',
                'role' => 'required|string',
        ];
    }
}
