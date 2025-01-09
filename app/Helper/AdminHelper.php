<?php

use App\Models\Restaurant\Restaurant;
use App\Models\AdminSetting;
use App\Models\Admin;
use App\Models\Bid;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

// used: for loading public assets for admin in future if we want change path
if (!function_exists('loadAssets')) {
    function loadAssets($dir)
    {
        return url('public/admin/' . $dir);
    }
}

// used: for call Auth::gurad('admin') once so we can easily make changes in gurad in future
if (!function_exists('admin')) {
    function admin()
    {
        return Auth::guard('admin');
    }
}

// restaurant list
if (!function_exists('current_restaurant_list')) {
    function current_restaurant_list()
    {
        // dd(admin()->resturant());
        // $list = Restaurant::where('user_id', admin()->id())->get();
        $list = admin()->user()->resturant;
        return $list;
    }
}

// admin setting info
if (!function_exists('admin_setting')) {
    function admin_setting($key)
    {
        $info = AdminSetting::where('setting_key', $key)->first();
        return !empty($info) ? $info->setting_value : '';
    }
}

// get all admin list
if (!function_exists('admin_list')) {
    function admin_list($type = 'ids')
    {
        $admin_ids = [];

        $info = Admin::where('role_id', 1)->where('group_admin_id', 1)->get();
        if (empty($info)) return [];

        if ($type = 'ids') {
            foreach ($info as $key => $admin)   array_push($admin_ids, $admin->id);
            return $admin_ids;
        } else   return $info;
    }
}

// currency format
if (!function_exists('currency_format')) {
    function currency_format($amount)
    {
        return '$ ' . number_format($amount, 2);
    }
}

// restaurant list
if (!function_exists('showRestaurantName')) {
    function showRestaurantName($resId)
    {
        $deatils = Restaurant::find($resId);
        return !empty($deatils) ? $deatils->name : '-';
    }
}



// used: get prmission list of role
if (!function_exists('permission_list')) {
    function permission_list()
    {
        $user = admin()->user();
        if (isset($user->role)) {
            return $user->role->module_permissions;
        }
        return [];
    }
}


// used: find permission
if (!function_exists('is_permission')) {
    function is_permission($key)
    {
        // if user: super-admin
        if (admin()->user()->role_id == \App\Models\Admin::SUPERADMIN) {
            return true;
        }

        // user: not super-admin
        $permission_list = permission_list();
        if (empty($permission_list)) return false;       // empty return false;

        if (in_array($key, $permission_list))    // if permission found return true;
            return true;
        else
            return false;
    }
}

// subscription : monthly
if (!function_exists('subscription_type')) {
    function subscription_type($oldVal = '')
    {
        $optionList = '';
        $type = ['month', 'weekly', 'daily'];
        foreach ($type as $key => $ty) {
            $selected = '';
            if (!empty($oldVal) && $ty == $oldVal)   $selected = 'selected';
            $optionList .= "<option value='" . $ty . "' " . $selected . ">" . $ty . "</option>";
        }
        return $optionList;
    }
}
if (!function_exists('transLang')) {
    function transLang($template = null, $dataArr = [])
    {
        return $template ? trans("messages.{$template}", $dataArr) : '';
    }
}
// subscription : monthly
if (!function_exists('getImagePath')) {
    function getImagePath($name, $type, $size = '')
    {
        $imgPath = '';
        $dirPath = '';

        switch ($type) {
            case 'profile':
                $imgPath = loadAssets('upload/profile');
                $dirPath = config('static.uplaodPath.foodPath');
                break;
            case 'product':
                $dirPath = config('static.uplaodPath.productPath');
                $imgPath = loadAssets('upload/ecom/product');
                break;
            case 'logo':
                $imgPath = loadAssets('images');
                break;
            case 'staff':
                $imgPath = loadAssets('upload/staff');
                $dirPath = config('static.uplaodPath.staffPath');
                    break;
            case 'strategy':
                $imgPath = loadAssets('upload/strategy');
                $dirPath = config('static.uplaodPath.strategyPath');
                            break;
            default:
                # code...
                break;
        }


        if (!empty($size)) {
            $imgPath =  $imgPath . '/' . $size . '/' . $name;
            // $dirPath = $dirPath. "\" . $size . "\" . $name;
        } else {
            $imgPath = $imgPath . '/' . $name;
            // $dirPath = $dirPath . '/' . $name;
        }

        // if image not store
        if (empty($name) || $name == null)    return loadAssets('image/no-image.png');

        return $imgPath;

        if (file_exists($imgPath))
            return $imgPath;
        else
            return loadAssets('image/no-image.png');
    }
}


// subscription : monthly
if (!function_exists('resizeImage')) {
    function resizeImage($oldVal = '')
    {
        $optionList = '';
        $type = ['month', 'year'];
        foreach ($type as $key => $ty) {
            $selected = '';
            if (!empty($oldVal) && $ty == $oldVal)   $selected = 'selected';
            $optionList .= "<option value='" . $ty . "' " . $selected . ">" . $ty . "</option>";
        }
        return $optionList;
    }

    if (!function_exists('certification_id')) {
        function certification_id()
        {
            $certificationId = 0;
            $certificationId = "CRAZII" . rand(111, 999);
            return $certificationId;
        }
    }

    
    if (!function_exists('statusList')) {
        function statusList()
        {
            return ['open'=>'Open','replied'=>'Replied','followup'=>'Follow UP','hot_lead'=>'Hot Lead','dead_lead'=>'Dead Lead',
            'fake_lead'=>'Fake Lead','awarded'=>'Awarded','cancelled'=>'Cancelled','invite'=>'Invite'];
        }
    }

    if (!function_exists('checkBidJobLink')) {
        function checkBidJobLink($link,$id)
        {
            $bids = Bid::get();
            if($id != ""){
                $bids = Bid::where('id','<>',$id)->get();
            }
            
            if (str_contains($link, "upwork")) {
                $number = explode("/",$link);
                $num = $number[sizeof($number) - 1];
                if(str_contains($num, "?")){
                    $num = explode("?",$num)[0];
                }
                
                
                foreach($bids as $bid){
                    $bid_number = explode("/",$bid->job_link);
                    $bid_num = $bid_number[sizeof($bid_number) - 1];
                    if(str_contains($bid_num, "?")){
                        $bid_num = explode("?",$bid_num)[0];
                    }

                    if($bid_num == $num){
                        return false;
                    }
                }
                return true;
            } else if(str_contains($link, "guru")) {
                
                $number = explode("/",$link);
                $num = $number[sizeof($number) - 1];
                
                if(str_contains($num, "?")){
                    $num = explode("?",$num)[0];
                }

                foreach($bids as $bid){
                    $bid_number = explode("/",$bid->job_link);
                    $bid_num = $bid_number[sizeof($bid_number) - 1];
                    if(str_contains($bid_num, "?")){
                        $bid_num = explode("?",$bid_num)[0];
                    }

                    if($bid_num == $num){
                        return false;
                    }
                }
                return true;
            }
            return true;
        }
    }

    if (!function_exists('checkLeadJobLink')) {
        function checkLeadJobLink($link,$id)
        {
            
            $leads = Lead::get();
            if($id != ""){
                $leads = Lead::where('id','<>',$id)->get();
            }
            
            if (str_contains($link, "upwork")) {
                $number = explode("/",$link);
                $num = $number[sizeof($number) - 1];
                if(str_contains($num, "?")){
                    $num = explode("?",$num)[0];
                }

                foreach($leads as $lead){
                    $lead_number = explode("/",$lead->job_link);
                    $lead_num = $lead_number[sizeof($lead_number) - 1];
                    if(str_contains($lead_num, "?")){
                        $lead_num = explode("?",$lead_num)[0];
                    }

                    if($lead_num == $num){
                        return false;
                    }
                }
                return true;
            } else if(str_contains($link, "guru")) {
                
                $number = explode("/",$link);
                $num = $number[sizeof($number) - 1];
                if(str_contains($num, "?")){
                    $num = explode("?",$num)[0];
                }
                foreach($leads as $lead){
                    $lead_number = explode("/",$lead->job_link);
                    $lead_num = $lead_number[sizeof($lead_number) - 1];
                    if(str_contains($lead_num, "?")){
                        $lead_num = explode("?",$lead_num)[0];
                    }

                    if($lead_num == $num){
                        return false;
                    }
                }
                return true;
            }
            return true;
        }
    }

    if(!function_exists('getDates')){
        function getDates($start_date,$end_date,$graph_filter){
            
            // $firstDay=date('d-m-Y',strtotime("first day of this month"));
            // $lastDay=date('d-m-Y',strtotime("last day of this month"));
 
            $array = []; 
            $start = strtotime($start_date); 
            $end = strtotime($end_date); 

            // 86400 sec = 24 hrs = 60*60*24 = 1 day
            if($graph_filter == "daily"){
                for ($currentDate = $start; $currentDate <= $end; $currentDate += (86400)) {                                     
                    $store = date('d-m-Y', $currentDate); 
                    $array[] = $store; 
                }
            }

            if($graph_filter == "weekly"){
                $new['first'] =  date ('Y-m-d ', $start) ;
                $new['last'] = date ('Y-m-d 23:59', $end) ;
                
                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod(new DateTime($new['first']), $interval,new DateTime($new['last']));

                $weekNumber = 1;
                foreach ($dateRange as $date) {
                    $array[$weekNumber][] = $date->format('d-m-Y');
                    if ($date->format('N') == 7) {
                        $weekNumber++;
                    }
                }
            }

            if($graph_filter == "monthly"){
                $key = 0;
                while (strtotime($start_date) <= strtotime($end_date)){
                    $array[$key] = ['month' => date('M',strtotime($start_date)),'year' => date('Y',strtotime($start_date))];
                    $start_date = date('01 M Y', strtotime($start_date . '+ 1 month'));
                    $key++;
                }
            }
            return $array; 
        }
    }

    if(!function_exists('getBidsLeadsPerMonth')){
        function getBidsLeadsPerMonth($bid,$month_dates,$graph_filter){
            $list = [];
            $bids_per_day = [];
            $converted_bids_per_day = [];
            $bids=[];
           
            $converted_bids = [];
            $previous_day = [];
            $bid_count = 1;
           
            $converted = 0;
            foreach($bid as $data){
                $date = date('d-m-Y',strtotime($data['created_at']));
                if(!in_array($date,$previous_day)){
                    // $bids_per_day[$date] = $bid_count;
                    // $converted_bids_per_day[$date] = $data['is_lead_converted'] == 1 ? ++$converted : $converted ;
                    $list[$date] = ['bids' => $bid_count, 'converted_bids_count' => $data['is_lead_converted'] == 1 ? ++$converted : $converted];
                    $converted = 0;
                }
                else{
                    // $bids_per_day[$date] = ++$bids_per_day[$date];
                    // $converted_bids_per_day[$date] = $data['is_lead_converted'] == 1 ? ++ $converted_bids_per_day[$date] : $converted_bids_per_day[$date] ;
                    $list[$date] = ['bids' => ++$list[$date]['bids'], 'converted_bids_count' => $data['is_lead_converted'] == 1 ? ++ $list[$date]['converted_bids_count'] : $list[$date]['converted_bids_count']];
                }
                
                array_push($previous_day,$date); 
            }

            if($graph_filter == "daily"){
                foreach($month_dates as $key1 => $date){
                    if(!empty($list)){
                        foreach($list as $key => $value){
                            if($date == $key){
                                $bids[$key1] = $list[$key]['bids'];
                                $converted_bids[$key1] = $list[$key]['converted_bids_count'];
                                break;
                            }
                            else{
                                $bids[$key1] = 0;
                                $converted_bids[$key1] = 0;
                            } 
                        }
                        
                    }else{
                        $bids[$key1] = 0;
                        $converted_bids[$key1] = 0;  
                    }   
                }
            }
            
            if($graph_filter == "weekly"){
                $days = [];
                foreach($list as $key => $val){
                    $days[] = $key;
                }
                $index = 0;
                foreach($month_dates as $month_date){
                    $bids[$index] = 0;
                    $converted_bids[$index] = 0;
                    foreach($month_date as $date){
                        if(in_array($date,$days)){
                            $bids[$index] = $bids[$index] + $list[$date]['bids'];
                            $converted_bids[$index] = $converted_bids[$index] + $list[$date]['converted_bids_count'];
                        }
                    }
                    $index++;
                }
            }

            if($graph_filter == "monthly"){
                $days = [];
                foreach($list as $key => $val){
                    $days[] = $key;
                }
                $index = 0;
                foreach($month_dates as $month){
                    $bids[$index] = 0;
                    $converted_bids[$index] = 0;
                    foreach($days as $date){
                        if($month['month'] == date('M',strtotime($date)) && $month['year'] == date('Y',strtotime($date))){
                            $bids[$index] = $bids[$index] + $list[$date]['bids'];
                            $converted_bids[$index] = $converted_bids[$index] + $list[$date]['converted_bids_count'];
                        }
                    }
                    $index++;
                }
            }
            
            $data= [];
            $data['total_bids'] = $bids;
            $data['total_converted_bids'] = $converted_bids;
           
            return $data;
        }
    }

    if(!function_exists('getAwardedPerMonth')){
        function getAwardedPerMonth($lead,$month_dates,$graph_filter){
            $lead = $lead->where('status','awarded');
            
            $awarded_per_day = [];
            $awarded = [];
            $previous_day = [];
            $awarded_count = 1;
            foreach($lead as $data){
                $date = date('d-m-Y',strtotime($data['created_at']));
                if(!in_array($date,$previous_day)){
                    $awarded_per_day[$date] = $awarded_count;
                }
                else{
                    $awarded_per_day[$date] = ++$awarded_per_day[$date];
                    
                }
                array_push($previous_day,$date); 
            }
        //    dd($awarded_per_day);
            if($graph_filter == "daily"){
                foreach($month_dates as $key1 => $date){
                    if(!empty($awarded_per_day)){
                    foreach($awarded_per_day as $key => $value){
                            if($date == $key){
                                $awarded[$key1] = $awarded_per_day[$key];
                                break;
                            }
                            else{
                                $awarded[$key1] = 0;
                            } 
                        }   
                    }
                    else{
                        $awarded[$key1] = 0;
                    } 
                }
            }
            if($graph_filter == "weekly"){
                $list=[];
                $index = 0;
                foreach($awarded_per_day as $key => $day){
                    $list[] = $key;
                }
                foreach($month_dates as $month_date){
                    $awarded[$index] = 0;
                    foreach($month_date as $date){
                        if(in_array($date,$list)){
                            $awarded[$index] = $awarded[$index]+ $awarded_per_day[$date];
                        }
                    }
                    $index++;
                }
            }
            if($graph_filter == "monthly"){
                $list=[];
                $index = 0;
                foreach($awarded_per_day as $key => $day){
                    $list[] = $key;
                }
               
                foreach($month_dates as $month){
                    $awarded[$index] = 0;
                    foreach($list as $val){
                        if($month['month'] == date('M',strtotime($val)) && $month['year'] == date('Y',strtotime($val))){
                            $awarded[$index] = $awarded[$index] + $awarded_per_day[$val];
                        }
                    }
                    $index++;
                }
            }
            return $awarded;
        }
    }
}
