<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TransactionRequest;
use Psy\Command\WhereamiCommand;
use App\Models\TradingDuration;
use App\Models\KnowingAboutApplication;
use App\Models\TradingLevel;
use App\Models\UserInterest;
use App\Models\UserInterestKnowMap;
use App\Models\UserInterestTradingLevelMap;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function wallet_top_up_store(TransactionRequest $request)
    {
        try{
        $tans = Auth::user();


        $user = Transaction::create([
            'user_id' => $tans->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'comment' => $request->comment,
        ]);
        $user = User::find(Auth::user()->id);
        if ($request->type == 'Cr') {
            $user->wallet_amount = $user->wallet_amount + $request->amount;
        } elseif ($request->type == 'Dr') {
            $user->wallet_amount = $user->wallet_amount - $request->amount;
        }
            $user->save();

            return JsonResponse(true,[], $request->amount.' Will be updated on your wallet');
     } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
     }}



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $detail = Transaction::find($id);
            return JsonResponse(true, $detail,'transaction detail');
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }

    public function listing()
    {
        try {
            $id = Auth::user()->id;

            $transaction = Transaction::where('user_id', $id)->get();

            return JsonResponse(true, $transaction, 'All the transactions');
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }


    public function interest_dynamic_form()
    {
        try {
            $knowing_about_application = KnowingAboutApplication::all();
            $trading_level = TradingLevel::all();
            $trading_duration = TradingDuration::all();
            $object['knowing_about_application'] = $knowing_about_application;
            $object['trading_level'] = $trading_level;
            $object['trading_duration'] = $trading_duration;

            return JsonResponse(true, $object, 'Interest dynamic form');
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }

    public function store_interest_form(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:user_interest,email',
            'trading_duration_id' => 'required|integer',
            'knowing_id.*' => 'required|integer',
            'trading_level_id.*' => 'required|integer',
        ]);

        try {


            $tans = Auth::user();

            $user = UserInterest::create([
                'user_id' => $tans->id,
                'name' => $request->name,
                'email' => $request->email,
                'trading_duration_id' => $request->trading_duration_id,
            ]);
            foreach($request->knowing_id as $key => $value){
                $user2 = UserInterestKnowMap::create(
                    [
                        'user_id' => $tans->id,
                        'user_interest_id' => $user->id,
                        'knowing_id' => $value
                    ]
                );
            }

            foreach($request->trading_level_id as $key => $value){
                $user3 = UserInterestTradingLevelMap::create(
                    [
                        'user_id' => $tans->id,
                        'user_interest_id' => $user->id,
                        'trading_level_id' => $value
                    ]
                );
            }

            $objectarr = null;
            return JsonResponse(true, [], 'Interest form added successfully');
        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request)
    {
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
