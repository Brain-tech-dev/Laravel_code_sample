<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {

        $current_page = !empty($request->page) ? $request->page : 1;
        $search = $request->search ? $request->search : "";
        $perPage = !empty($request->perPage) ? $request->perPage : 50;
        $method = $request->method;

        $clients = Client::query();

        if($search){
            $clients = $clients->where('client_name', 'like' , '%'.$search.'%')->orWhere('mobile','like','%'.$search.'%')->orWhere('email', 'like' , '%'.$search.'%')->orWhere('linkedin', 'like' , '%'.$search.'%')->orWhere('skype', 'like' , '%'.$search.'%')->orWhere('other', 'like' , '%'.$search.'%');
        }

        $clients = $clients->with('bids','leads')->orderBy('id','desc')->paginate($perPage);

        if (!empty($method)) {
            $html = view('admin.client.table', compact('clients'))->render();
            return response()->json([
                'html' => $html
            ]);
        } else {
            return view('admin.client.index', compact('clients'));
        }

    }

    public function create()
    {
        return view('admin.client.create');
    }

   
    public function store(Request $request)
    {
        $this->validate($request,[
            'client_name'=>'required|regex:/^[\p{L}\'\-\. ]+$/u',
            'email'=>['nullable','email','unique:clients,email'],
            'mobile'=>'nullable|numeric|digits_between:6,15|unique:clients,mobile',
            // 'email'=>['required','email','unique:clients,email'],
       ]);

        $client = Client::create([
            'client_name' => $request->client_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'skype' => $request->skype,
            'linkedin' => $request->linkedin,
            'other' => $request->other,
            'location' => $request->location,
        ]);
        return redirect()->route('admin.client.list')->with('success',"Client added successfully");
    }

    public function clientDetail(Request $request)
    {
        $detail = Client::where('id',$request->id)->get();  
        if(!blank($detail)){
            return response()->json([
                'status'=>true,
                'detail' => $detail
            ]);
        }
        return response()->json([
            'status'=>false,
        ]);
    }

    public function show($id){}
    
    public function edit($id)
    {
        
        $client = Client::findOrFail($id);
        return view('admin.client.edit', compact('client'));
    }

    
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request,[
            'client_name'=>['required'],
            // 'email'=>['required','email','unique:clients,email,'.$id],
       ]);
       
       $client = Client::findOrFail($id);
      
       $client->update([
            'client_name' => $request->client_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'linkedin' => $request->linkedin,
            'skype' => $request->skype,
            'other' => $request->other,
            'location' => $request->location,
        ]);
        
        return redirect()->back()->with('success',"Client Updated successfully");
    }


    public function destroy($id)
    {
        $data = Client::findOrFail($id);
        if ($data) {
            $data->delete();
        }
        return redirect()->route('admin.client.list')->with('success', 'Client Removed');
    }

    public function merge(Request $request)
    {   
        $idString = $request->ids;
        $ids = strpos($request->ids, ',') ? explode(",",$request->ids) : [$request->ids];
        $clients = Client::whereIn('id', $ids)->orderBy("email", "DESC")->get();
        return view('admin.client.merge', compact('clients','idString'));
    }

    public function storeMerge(Request $request)
    {
        // update client
        $client = Client::find($request->updateId);
        $client->fill([
            'client_name' => $request->client_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'skype' => $request->skype,
            'linkedin' => $request->linkedin,
            'other' => $request->other,
            'location' => $request->location,
        ]);
        $client->save();

        // remove client and update to lead id
        $ids = strpos($request->removeIds, ',') ? explode(",",$request->removeIds) : [$request->removeIds];
        
        // update client ids in lead
        Lead::whereIn('client_id', $ids)->update(["client_id"=>$request->updateId]);
        Bid::whereIn('client_id', $ids)->update(["client_id"=>$request->updateId]);

        // remove merged client 
        if (($key = array_search($request->updateId, $ids)) !== false) {
            unset($ids[$key]);
            Client::whereIn('id', $ids)->delete();
        }
        return redirect()->route('admin.client.list')->with('success',"Client merged successfully");
    }

    public function clientBidsList(Request $request, $id){
        $bids = Bid::where('client_id',$id)->get();
        return view('admin.client.bids-list',compact('bids'));
    }
}
