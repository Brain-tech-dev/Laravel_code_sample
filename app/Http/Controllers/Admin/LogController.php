<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Role;
use Illuminate\Http\Request;
use DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $current_page = !empty($request->page) ? $request->page : 1;
        $search = $request->search ? $request->search : '';
        $perPage = !empty($request->perPage) ? $request->perPage : 10;
        $leadFilter = $request->leadFilter ? $request->leadFilter : '';
        // $dataFilter = $request->dataFilter != "" ? $request->dataFilter : "";

        $date = date('m/d/Y',strtotime("first day of this month")) . ' - ' . date('m/d/Y',strtotime("last day of this month"));
        $dateRange = $request->dateRange ? $request->dateRange : $date ;

        $method = $request->method;

        $logs = Log::query();
        $leads = Lead::query();
        

        if($leadFilter) {
            $logs = $logs->where('lead_id', $leadFilter);
        }

        // if ($dataFilter != ""){
        //     $logs = $logs->where("is_test",'=',(int)$dataFilter);
        // }
        // dd($logs->toSql());   

        if ($search) {
            $logs = $logs->whereRaw(" ( user_name like '%".$search."%' OR lead_show_id like '%".$search."%' OR page = '$search')"); 
        }
       
        if(!empty($dateRange)){
            $dateExp = explode(" - ", $dateRange);
            $start_date = date("Y-m-d", strtotime($dateExp[0]));
            $end_date = date("Y-m-d", strtotime($dateExp[1])); 
            $logs = $logs->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
        }

        $logs = $logs->latest()->paginate($perPage);
        $leads = $leads->get();
        if (!empty($method)) {
            $html = view('admin.log.table', compact(['logs','leads']))->render();
            return response()->json([
                'html' => $html,
            ]);
        } else {
            return view('admin.log.index', compact(['logs','leads']));
        }
        
    }
}
