<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class RegisterRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
            return [
        'name' => [
            'required',
            'string',
            'max:255',
            'alpha_spaces_dash'
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];}
        public function messages()
    {
    return [
        'name.regex' => 'يجب أن يحتوي الاسم على حروف فقط (عربية أو إنجليزية) مع شرطة وسطى وفراغات',
    ];
    }
    
}
