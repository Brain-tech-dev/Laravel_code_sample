<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddToCartRequest extends FormRequest
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
            'food_id' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'variation_options' => ['required', 'array', 'min:1'],
            // 'additional_note' => ['required'],
        ];
    }
}
