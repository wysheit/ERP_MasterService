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

class CustomerOrdersController extends Controller
{
    //
    public function showAllCusOrdesHeader(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomerOrdersHeaders::latest()->get();
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
    public function showCusOrdersDetails($id)
    {
        return response()->json(CustomerOrderDetails::where('order_id',$id)->get());
    }
    public function showOneCusOrderHeader($id)
    {
        return response()->json(CustomerOrdersHeaders::find($id));
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
        $cusOrderHeader = new CustomerOrdersHeaders();
        $cusOrderHeader->order_code  = $this->code_Create();
        $cusOrderHeader->customer_id = $request->customer_id;
        $cusOrderHeader->is_quored=$request->is_quored;
        $cusOrderHeader->order_department = $request->order_department;
        $cusOrderHeader->save();
        foreach($request->details as $element)
        {
            $cusOrderDetails=new CustomerOrderDetails();
            $cusOrderDetails->order_id=$cusOrderHeader->id;
            $cusOrderDetails->item=$element->item;
            $cusOrderDetails->qty=$request->qty;
            $cusOrderDetails->save();
        }
        DB::commit();
        return response()->json(['cusOrderHeader' => $cusOrderHeader, 'message' => 'CREATED','status'=>200], 200);
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
        $cusOrderHeader = CustomerOrdersHeaders::where('id',$id)->first();
        if(isset($CustomerOrdersHeaders))
        {
            $cusOrderHeader->customer_id = $request->customer_id;
            $cusOrderHeader->is_quored=$request->is_quored;
            $cusOrderHeader->order_department = $request->order_department;
            $cusOrderHeader->save();

            $deletePreData=CustomerOrderDetails::where('order_id',$cusOrderHeader->id)->delete();
            foreach($request->details as $element)
            {
                $cusOrderDetails=new CustomerOrderDetails();
                $cusOrderDetails->order_id=$cusOrderHeader->id;
                $cusOrderDetails->item=$element->item;
                $cusOrderDetails->qty=$request->qty;
                $cusOrderDetails->save();
            }
        }
        else
        {
        return response()->json(['message' => 'UPDATED Failed','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['cusOrderHeader'=>$cusOrderHeader,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        CustomerOrdersHeaders::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select order_code  from customer_orders_headers  ORDER BY RIGHT(order_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->order_code;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='ORD'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
