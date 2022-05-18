<?php

namespace App\Http\Requests\API;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserUpdateProfileRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'password' => $this->passwordRules(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'house_number' => 'nullable|string',
            'city' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return ResponseFormatter::failedValidation($validator->errors());
    }
}
