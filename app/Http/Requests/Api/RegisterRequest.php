<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Customize validator response to json.
     * also sending as validation error in json format
     */

    protected $stopOnFirstFailure = true;

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            JsonResponse(false, [], $validator->errors()->first())
        );
    }

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
            'dial_code' => ['required',],
            'phone' => ['required', 'unique:users,phone'],
            'device_name' => ['required'],
            'fcm_token_id' => ['required'],
            'device_name' => ['required'],
            'device_type' => ['required'],
            'device_id' => ['required'],
        ];
    }
}
