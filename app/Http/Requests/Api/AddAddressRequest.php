<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class AddAddressRequest extends FormRequest
{

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
            'postal_code' => 'required|numeric',
            'street_name' => 'required',
            'building_number' => 'required|numeric',
            'unit' => 'required',
        ];
    }
}
