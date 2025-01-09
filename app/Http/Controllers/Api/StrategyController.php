<?php

namespace App\Http\Controllers\Api;
use App\Models\StrategyInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class StrategyController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $detail = StrategyInformation::find($id);
            return JsonResponse(true, $detail,'Strategy detail');

        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }


    }
    public function listing()
    {
        try {

            $detail = StrategyInformation::where('status','active')->get();
            $detail = $detail->makeHidden(['status']);
            return JsonResponse(true, $detail,'Strategy listing');

        } catch (\Throwable $th) {
            LogError($th); // log error
            return JsonResponse(false, [], 'Having issue !');
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
