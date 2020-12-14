<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use App\Models\CustomerOrderDetails;
use App\Models\CustomerOrdersHeaders;
use App\Models\LeadFollowups;
use App\Models\Leads;
use App\Models\QuotationDetails;
use App\Models\QuotationsHeader;
use Illuminate\Support\Facades\Hash;
use DB;
use Log;
use DataTables;

class LeadsController extends Controller
{
    //
    public function showAllLeads(Request $request)
    {
        if ($request->ajax()) {
            $data = Leads::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<button class="edit btn btn-icon btn-success btn-sm mr-2" data-id='.$row->id.'><i class="text-light-50 flaticon-edit"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function showOneLead($id)
    {
        return response()->json(Leads::find($id));
    }
    public function getAllLeads()
    {
        return response()->json(Leads::latest()->get());
    }
    public function create(Request $request)
    {
    try
        {
       // $this->validate($request, [
           // 'customer_name' => 'required|string|unique:customers',
           // 'user_id' => 'required|unique:customers',
        //]);
        DB::beginTransaction();
        $leads = new Leads();
        $leads->company_name  = $request->company_name;
        $leads->contact_person = $request->contact_person;
        $leads->telephone=$request->telephone;
        $leads->email = $request->email;
        $leads->address=$request->address;
        $leads->handled_by=$request->handled_by;
        $leads->lead_type=$request->lead_type;
        $leads->next_schedule=$request->next_schedule;
        $leads->remarks=$request->remarks;
        $leads->save();
        DB::commit();
        return response()->json(['leads' => $leads, 'message' => 'CREATED','status'=>200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        //return error message
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function update($id, Request $request)
    {
     try
        {
        //$this->validate($request, [
           // 'client_code' => ['required',Rule::unique('customers')->ignore($id)],
           // 'user_id' => ['required',Rule::unique('customers')->ignore($id)],
        //]);
        DB::beginTransaction();
        $leads = Leads::where('id',$id)->first();
        if(isset($leads))
        {
            $leads->company_name  = $request->company_name;
            $leads->contact_person = $request->contact_person;
            $leads->telephone=$request->telephone;
            $leads->email = $request->email;
            $leads->address=$request->address;
            $leads->handled_by=$request->handled_by;
            $leads->lead_type=$request->lead_type;
            $leads->next_schedule=$request->next_schedule;
            $leads->remarks=$request->remarks;
            $leads->save();
        }
        else
        {
        return response()->json(['message' => 'UPDATED Failed','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['customer'=>$client,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        Leads::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

}
