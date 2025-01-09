<?php

namespace App\Service\Api\Food;

use App\Models\Restaurant\FoodCart;
use App\Models\Restaurant\RestaurantFood;
use App\Models\Restaurant\RestaurantFoodVariationOption;

class CartService
{
    public static function getCartList(){

        $subtotal = $finaltotal = $delivery_fees = $tax = $total_qty = 0;
        $subtotal_arr = [];

        // $user_id = $request->user_id;
        $user_id = auth()->user()->id;
        
        $cartItem = FoodCart::select(FoodCart::column)->with('food:id,restaurant_id,name,image')->where('user_id', $user_id)->get();
        if (!empty($cartItem)) {

            foreach ($cartItem as $key => $cart) {
                $subtotal += $cart->price;
                $total_qty += $cart->quantity;

                $choseOptions = [];
                if (!empty($cart->variation_options)) {
                    $res = RestaurantFoodVariationOption::select('option_name', 'addition_price')->whereIn('id',$cart->variation_options)->get();
                    $choseOptions = !empty($res) ? $res : [];
                }
                $cartItem[$key]['choosenOptions'] = $choseOptions;

                if (!empty($cart->food)) {
                    $imagepath = getImagePath($cart->food->image, 'food');
                    $cartItem[$key]['food']['image'] = $imagepath;
                }
            }
        }

        $finaltotal = $subtotal + $delivery_fees + $tax;

        $response = [
            'sub_total' => $subtotal,
            'delivery_fees' => $delivery_fees,
            'tax' => $tax,
            'total_qty'=>$total_qty,
            'total' => $finaltotal,
            'item' => $cartItem
        ];

        return $response;


    }
}
