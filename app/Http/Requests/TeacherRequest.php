<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'national_id' => 'required|string|unique:staff,national_id',
            'address' => 'required|string',
            'previous_job' => 'required|string',
            'education_level' => 'required|string',
            'phone' => 'required|string|unique:staff,phone',
        ];
    }
}
