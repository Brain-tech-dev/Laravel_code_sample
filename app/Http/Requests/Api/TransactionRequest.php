<?php

namespace App\Http\Requests\api;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check(); // <-------

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:Cr,Dr',
            'amount' => 'required|numeric',
            'comment' => 'nullable'
        ];
    }
}
