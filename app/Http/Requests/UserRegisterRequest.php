<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as Password_rule;

class UserRegisterRequest extends FormRequest
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
            'full_name' => 'required|max:55|string',
            'email' => 'email|required|unique:users',
            'password' => ['required', 'confirmed',
                Password_rule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
            'mother_name' => 'required|max:55|string',
            'old' => 'required|max:55|string',
            'mynumber' => 'required|max:55|string',
            'addess' => 'required|max:55|string',
            'ago_work' => 'required|max:55|string',
            'studing' => 'required|max:55|string',
        ];
    }
    public function messages()
    {

        return [
            'full_name.required' => 'full_name filed is required',
            'full_name.max' => 'full_name should be less than 55 charecters',
            'full_name.string' => 'full_name should be string',
            'email.required' => 'email filed is required',
            'email.digits' => 'Phone should be 12 number',
            'password.required' => 'password filed is required',
        ];

    }
}
