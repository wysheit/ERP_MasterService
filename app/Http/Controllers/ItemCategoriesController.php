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

class ItemCategoriesController extends Controller
{
    //
    public function showAllCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = ItemCategories::latest()->get();
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
    public function showOneCategories($id)
    {
        return response()->json(ItemCategories::find($id));
    }
    public function getAllCategories()
    {
        return response()->json(ItemCategories::latest()->get());
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
        $categories = new ItemCategories();
        $categories->category_code=$this->code_Create();
        $categories->category_name  = $request->category_name;
        $categories->is_active = $request->is_active;
        $categories->save();
        DB::commit();
        return response()->json(['categories' => $categories, 'message' => 'CREATED','status'=>200], 200);
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
        $categories = ItemCategories::where('id',$id)->first();
        if(isset($items))
        {
            $categories->category_name  = $request->category_name;
            $categories->is_active = $request->is_active;
            $categories->save();
        }
        else
        {
        return response()->json(['message' => 'UPDATED Faild','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['categories'=>$categories,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        ItemCategories::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select category_code  from item_categories  ORDER BY RIGHT(category_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->category_code;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='CAT-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
