<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // login check

        try {


            $username = $request->user;
            $password = $request->password;
            $device_name = $request->device_name;
            $fcm_token_id = $request->fcm_token_id;
            $device_name = $request->device_name;
            $device_type = $request->device_type;
            $device_id = $request->device_id;

            $selectName = ['id', 'name', 'email', 'status', 'password'];
            $user = User::select($selectName)->where('email', $username)->orWhere('phone', $username)->first();

            if (empty($user) || $user == null)
                return JsonResponse(false, [], 'email/Phone not valid');

            if ($user->status == 'block')
                return JsonResponse(false, [], 'your account is blocked please contact to support team.');

            if (!Hash::check($password, $user->password))
                return JsonResponse(false, [], 'The provided credentials are incorrect.');


            $user->tokens()->delete();  // revoke all old tokens

            $currentAccessToken = $user->createToken($username)->plainTextToken;    // create always fresh toekn while login

            // update firebase token for push notify
            if (!empty($fcm_token_id)) {
                $user->firebase_token = $fcm_token_id;
                $user->device_name = $device_name;
                $user->device_type = $device_type;
                $user->device_id = $device_id;
                $user->save();
            }

            $user->currentAccessToken = $currentAccessToken;

            Auth::login($user);     //login user

            return JsonResponse(true, $user);
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having some issue.');
        }
    }
}
