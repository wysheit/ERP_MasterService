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

class QuotationController extends Controller
{
    //
    public function showAllQuotationHeader(Request $request)
    {
        if ($request->ajax()) {
            $data = QuotationsHeader::latest()->get();
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
    public function showQuotationDetails($id)
    {
        return response()->json(QuotationDetails::where('quotation_id',$id)->get());
    }
    public function showOneQuotationHeader($id)
    {
        return response()->json(QuotationsHeader::find($id));
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
        $quotationHeader = new QuotationsHeader();
        $quotationHeader->quote_number  = $this->code_Create();
        $quotationHeader->order_id = $request->order_id;
        $quotationHeader->customer_id=$request->customer_id;
        $quotationHeader->is_approved = $request->is_approved;
        $quotationHeader->total_value=$request->total_value;
        $quotationHeader->remarks=$request->remarks;
        $quotationHeader->save();
        foreach($request->details as $element)
        {
            $quotationDetails=new QuotationDetails();
            $quotationDetails->quotation_id=$quotationHeader->id;
            $quotationDetails->item=$element->item;
            $quotationDetails->unit_price=$request->unit_price;
            $quotationDetails->qty=$element->qty;
            $quotationDetails->discount=$element->discount;
            $quotationDetails->amount=$element->amount;
            $quotationDetails->save();
        }
        DB::commit();
        return response()->json(['quotationHeader' => $quotationHeader, 'message' => 'CREATED','status'=>200], 200);
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
        $quotationHeader = QuotationsHeader::where('id',$id)->first();
        if(isset($quotationHeader))
        {
            $quotationHeader->order_id = $request->order_id;
            $quotationHeader->customer_id=$request->customer_id;
            $quotationHeader->is_approved = $request->is_approved;
            $quotationHeader->total_value=$request->total_value;
            $quotationHeader->remarks=$request->remarks;
            $quotationHeader->save();

            $deletePreData=QuotationDetails::where('quotation_id',$quotationHeader->id)->delete();
            foreach($request->details as $element)
            {
            $quotationDetails=new QuotationDetails();
            $quotationDetails->quotation_id=$quotationHeader->id;
            $quotationDetails->item=$element->item;
            $quotationDetails->unit_price=$request->unit_price;
            $quotationDetails->qty=$element->qty;
            $quotationDetails->discount=$element->discount;
            $quotationDetails->amount=$element->amount;
            $quotationDetails->save();
            }

        }
        else
        {
        return response()->json(['message' => 'UPDATED Failed','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['quotationHeader'=>$quotationHeader,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        QuotationsHeader::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select quote_number  from quotations_header  ORDER BY RIGHT(quote_number , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->quote_number;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='QUO'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
