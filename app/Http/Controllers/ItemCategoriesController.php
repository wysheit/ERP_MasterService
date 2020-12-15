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
        $columns = array( 
            0 =>'category_code', 
            1 =>'category_name',
            2=> 'is_active',
        );

        $totalData = ItemCategories::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if( empty($request->input('search.value')) ) {            
            $items = ItemCategories::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $request->input('search.value'); 

            $items =  ItemCategories::where('category_code','LIKE',"%{$search}%")
                        ->orWhere('category_name', 'LIKE',"%{$search}%")
                        ->orWhere('is_active', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = ItemCategories::where('category_code','LIKE',"%{$search}%")
                        ->orWhere('category_name', 'LIKE',"%{$search}%")
                        ->orWhere('is_active', 'LIKE',"%{$search}%")
                        ->count();
        }

        $data = array();
        if( !empty($items) ) {
            foreach ($items as $item)
                {
                    $Customer['category_code'] = $item->category_code;
                    $Customer['category_name'] = $item->category_name;
                    $Customer['is_active'] = ($item->is_active==1) ? 'Active' : 'inactive';
                    $Customer['action'] = '<button class="edit btn btn-icon btn-success btn-sm mr-2" data-id='.$item->id.'><i class="text-light-50 flaticon-edit"></i></button>';
                    $data[] = $Customer;

                }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
