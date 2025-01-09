<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $stopOnFirstFailure = true;

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            JsonResponse(false, [], $validator->errors()->first())
        );
    }

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
            'name' => 'nullable|string',
            'email' => 'nullable|email|string|unique:users,email,'.Auth::user()->id.'id',
            'dial_code' => 'nullable|string',
            'phone' => 'nullable|string:unique:users,phone,'.Auth::user()->id.'id',
            'current_password' => 'nullable',
            'password' => 'required_with:current_password|min:6|confirmed',
        ];
    }
}
