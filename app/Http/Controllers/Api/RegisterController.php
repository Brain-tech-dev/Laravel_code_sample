<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SendOtp;
use App\Models\User;

use App\Http\Requests\Api\RegisterRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\UpdateProfileRequest;

use App\Service\SmsService;


class RegisterController extends Controller
{
    /**
     * send otp to signup user.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendOtp(Request $request)
    {
        try {
            $otp = rand(1111, 9999);
            $res = SendOtp::firstOrCreate(
                ['phone' => $request->phone, 'dial_code' => $request->dial_code, 'status' => 'pending'],
                ['otp' => $otp],

            );


            // sms send code put here
            $msg = "Your Account Verification Code - ".$otp;
            // SmsService::sendSMS($request->dial_code.''.$request->phone, $msg);

            return JsonResponse(true, $res,  $msg);

        } catch (\Throwable $th) {
            LogError($th); // log error
            // return JsonResponse(false, [], 'Otp sent fail !');
            return JsonResponse(false, [], $th->getMessage());
        }
    }
    /**
     * Otp verify for register user
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyOtp(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'dial_code' => 'required',
            'otp' => 'required',
        ]);
        try {

            $otpVerify = tap(SendOtp::select('phone', 'dial_code')
                ->where(['dial_code' => $request->dial_code, 'phone' =>  $request->phone, 'otp' => $request->otp]))
                ->update(['status' => 'verified'])
                ->first();

            if ($otpVerify && $otpVerify != null) {
                return JsonResponse(true, $otpVerify, 'Otp verify !');
            } else
                return JsonResponse(false, [], 'Otp verification failed !');
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Otp verification having issue !');
        }
    }

    /**
     * send otp to signup user.
     *
     * @return \Illuminate\Http\Response
     */



    public function register(RegisterRequest $request)
    {
        try {

            $sendOtp = SendOtp::where(['dial_code' => $request->dial_code, 'phone' => $request->phone])->where('status', 'verified')->first();

            $insData = [
                'certification_id' => $request->certification_id,
                'dial_code' => $request->dial_code,
                'phone' => $request->phone,
                'firebase_token' => $request->fcm_token_id,
                'device_name' =>  $request->device_name,
                'device_type' => $request->device_type,
                'device_id' => $request->device_id,
                'password' => Hash::make($request->password)
            ];

            if (!empty($sendOtp))
                $insData['phone_verified_at'] = $sendOtp->updated_at;

            $user = User::create($insData);

            $user = User::find($user->id);
            Auth::login($user);

            $currentAccessToken = $user->createToken($request->phone)->plainTextToken;    // create accessToken
            $user->currentAccessToken = $currentAccessToken;

            return JsonResponse(true, $user, 'Welcome ' . $user->name);
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }

    /**
     * send otp to signup user.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        try {

            $list = User::find(Auth::user()->id);
            return JsonResponse(true, $list);
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }

    // update profile
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $updateProfile = User::findOrFail(Auth::user()->id);
            $updateProfile->name = $request->name ?? $updateProfile->name;
            $updateProfile->email = $request->email ?? $updateProfile->email;
            $updateProfile->dial_code = $request->dial_code ?? $updateProfile->dial_code;
            $updateProfile->phone = $request->phone ?? $updateProfile->phone;
            if($request->current_password) {
                if (Hash::check($request->current_password, $updateProfile->password)) {
                    if (!Hash::check($request->password, $updateProfile->password)) {
                        $updateProfile->password =  Hash::make($request->password);
                    } else {
                        return JsonResponse(false, [], transLang('new_old_equal'));
                    }
                } else {
                    return JsonResponse(false, [], transLang('password_not_matched'));
                }
            }
            $updateProfile->save();
            return JsonResponse(true, $updateProfile);
        } catch (\Exception $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }

    public function logout(Request $request)
    {
        try {

            // $user = auth()->user();
            // $user->fill(['device_id'=>null, 'device_token'=>null, 'device_type'=>null]);
            // $user->save();

            auth()->user()->currentAccessToken()->delete(); // revoke current token

            auth()->guard('web')->logout(); // logout

            return JsonResponse(true, [], 'Logout Success');
        } catch (\Exception $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }
}
