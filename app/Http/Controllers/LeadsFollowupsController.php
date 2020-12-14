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

class LeadsFollowupsController extends Controller
{
    //
    public function showAllLeadFollowups(Request $request)
    {
        if ($request->ajax()) {
            $data = LeadFollowups::latest()->get();
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
    public function showOneLeadFollowups($id)
    {
        return response()->json(LeadFollowups::find($id));
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
        $leadfollwups = new LeadFollowups();
        $leadfollwups->followed_by  = $request->followed_by;
        $leadfollwups->contact_methode = $request->contact_methode;
        $leadfollwups->remarks=$request->remarks;
        $leadfollwups->next_schedule = $request->next_schedule;
        $leadfollwups->save();
        DB::commit();
        return response()->json(['leadfollowups' => $leadfollwups, 'message' => 'CREATED','status'=>200], 200);
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
        $leadfollwups = LeadFollowups::where('id',$id)->first();
        if(isset($leadfollwups))
        {
            $leadfollwups->followed_by  = $request->followed_by;
            $leadfollwups->contact_methode = $request->contact_methode;
            $leadfollwups->remarks=$request->remarks;
            $leadfollwups->next_schedule = $request->next_schedule;
            $leadfollwups->save();
        }
        else
        {
        return response()->json(['message' => 'UPDATED Failed','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['leadfollowups'=>$leadfollwups,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        LeadFollowups::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

}
