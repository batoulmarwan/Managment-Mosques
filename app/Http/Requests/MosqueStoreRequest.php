<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MosqueStoreRequest extends FormRequest
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
          //  'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'area' => 'nullable|numeric',
            'details' => 'nullable|string',
            'technical_status' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'has_female_section' => 'nullable|boolean',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
