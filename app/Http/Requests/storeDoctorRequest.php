<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeDoctorRequest extends FormRequest
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
            'name' =>['required', 'string', 'min:2', 'max:255'],
            'phone' =>['required', 'numeric'],
            'address' =>['required', 'string', 'min:3', 'max:255'],
            'years_of_experience' => ['required', 'numeric', 'min:1', 'max:50'],
            'email' =>['required', 'email', 'unique:doctors'],
            'password' => ['required', 'min:4', 'max:255']

            // "photo" => "required|max:1000|mimes:jpg,png,jpeg"
        ];
    }
}
