<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use App\Models\Item;
use App\Models\ItemCategories;
use Illuminate\Support\Facades\Hash;
use DB;
use Log;
use DataTables;

class ItemController extends Controller
{
    //
    public function showAllItems(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::latest()->get();
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
    public function showOneItems($id)
    {
        return response()->json(Item::find($id));
    }
    public function getAllItems()
    {
        return response()->json(Item::latest()->get());
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
        $items = new Item();
        $items->item_code=$this->code_Create();
        $items->item_name  = $request->item_name;
        $items->item_discription = $request->item_discription;
        $items->category_id=$request->category_id;
        $items->is_active = $request->is_active;
        $items->save();
        DB::commit();
        return response()->json(['item' => $items, 'message' => 'CREATED','status'=>200], 200);
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
        $items = Item::where('id',$id)->first();
        if(isset($items))
        {
            $items->item_name  = $request->item_name;
            $items->item_discription = $request->item_discription;
            $items->category_id=$request->category_id;
            $items->is_active = $request->is_active;
            $items->save();
        }
        else
        {
        return response()->json(['message' => 'UPDATED Faild','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['item'=>$items,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        Item::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select item_code  from item  ORDER BY RIGHT(item_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->item_code;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code=sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
