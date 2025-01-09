<?php


namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
{
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
            'name' => ['required', 'string'],
            'category_id' => ['required', 'numeric'],
            "description" => ['required'],
            'variation_name.*' => ['required'],
            'selection_option.*' => ['required'],
            'maximun_selection.*' => ['required'],
            'option_name.*.*' => ['required'],
            'addition_price.*.*' => ['required'],
            'base_price' => ['required_if:price_type,base'],
            'subscription_price' => ['required_if:price_type,subscription'],
            'subscription_type' => ['required_if:price_type,subscription'],
            'quantity_of_item' => ['required_if:price_type,subscription'],
            'frequency_of_delivery' => ['required_if:price_type,subscription'],

        ];
    }
}
