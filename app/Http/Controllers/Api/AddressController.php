<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function index()
    {
        try {
            $address = UserAddress::select('id', 'user_id', 'postal_code', 'street_name', 'building_number', 'unit')->where('user_id', Auth::user()->id)->get();
            if (!blank($address)) {
                return JsonResponse(true, $address);
            }
            return JsonResponse(false, [],  transLang('address_not_found'));
        } catch (\Exception $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having some issue.');
        }
    }

    public function addAddress(AddAddressRequest $request)
    {
        try {
            // check duplication of address
            $address =  UserAddress::updateOrCreate([
                'user_id' => Auth::user()->id,
                'building_number' => $request->building_number,
                'postal_code' => $request->postal_code,
            ], [
                'street_name' => $request->street_name,
                'unit' => $request->unit,
            ]);
            return JsonResponse(true, $address,  transLang('address_added_success'));
        } catch (\Exception $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having some issue.');
        }
    }

    public function destroy($id = '')
    {

        try {
            if (blank($id))
                return JsonResponse(false, [],  transLang('address_id_required'));

            $delete = UserAddress::where(['id' => $id, 'user_id' => Auth::user()->id])->first();
            if (!blank($delete)) {
                $delete->delete();
                return JsonResponse(true, [],  transLang('address_deleted_success'));
            }
            return JsonResponse(false, [],  transLang('not_found'));
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having some issue.');
        }
    }
}
