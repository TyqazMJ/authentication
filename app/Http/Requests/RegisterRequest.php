<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // Set to true as we are allowing access to the request
    }

    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^[A-Za-z ]+$/', 'max:255'],  // Only letters and spaces
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'The name must only contain letters and spaces.',
        ];
    }
}
