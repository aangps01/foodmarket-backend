<?php

namespace App\Http\Requests\API;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserRegisterRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $this->passwordRules(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'house_number' => 'nullable|string',
            'city' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return ResponseFormatter::failedValidation($validator->errors());
    }
}
