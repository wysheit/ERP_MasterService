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

class CustomerController extends Controller
{
    //
    public function showAllCustomer(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::latest()->get();
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
    public function showOneCustomer($id)
    {
        return response()->json(Customer::find($id));
    }
    public function getAllCustomer()
    {
        return response()->json(Customer::latest()->get());
    }
    public function create(Request $request)
    {
    try
        {
        //$this->validate($request, [
           // 'customer_name' => 'required|string|unique:customers',
           // 'user_id' => 'required|unique:customers',
      //  ]);
        DB::beginTransaction();
        $client = new Customer();
        $client->customer_code=$this->code_Create();
        $client->customer_name  = $request->customer_name;
        $client->contact_person = $request->contact_person;
        $client->telephone_1=$request->telephone_1;
        $client->telephone_2 = $request->telephone_2;
        $client->email=$request->email;
        $client->fax=$request->fax;
        $client->address=$request->address;
        $client->lead_id=$request->lead_id;
        $client->save();
        DB::commit();
        return response()->json(['customer' => $client, 'message' => 'CREATED','status'=>200], 200);
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
        $this->validate($request, [
           // 'client_code' => ['required',Rule::unique('customers')->ignore($id)],
           // 'user_id' => ['required',Rule::unique('customers')->ignore($id)],
        ]);
        DB::beginTransaction();
        $client = Customer::where('id',$id)->first();
        if(isset($client))
        {
        $client->customer_name  = $request->customer_name;
        $client->contact_person = $request->contact_person;
        $client->telephone_1=$request->telephone_1;
        $client->telephone_2 = $request->telephone_2;
        $client->email=$request->email;
        $client->fax=$request->fax;
        $client->address=$request->address;
        $client->lead_id=$request->lead_id;
        $client->save();
        }
        else
        {
        return response()->json(['message' => 'UPDATED Faild','status'=>500], 500);
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
        Customer::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select customer_code  from customer  ORDER BY RIGHT(customer_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->customer_code;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='CUS'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
