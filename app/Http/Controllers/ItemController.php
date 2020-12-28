<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use App\Models\Item;
use App\Models\ItemCategories;
use App\Models\ItemSerialNumbers;
use Illuminate\Support\Facades\Hash;
use DB;
use Log;
use DataTables;

class ItemController extends Controller
{
    //
    public function showAllItems(Request $request)
    {
        $columns = array( 
            0 =>'item_code', 
            1 =>'item_name',
            2=> 'is_active',
            3=> 'unit_price',
        );

        $totalData = Item::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if( empty($request->input('search.value')) ) {            
            $items = Item::offset($start)
                    ->limit($limit)
                   // ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $request->input('search.value'); 

            $items =  Item::where('item_code','LIKE',"%{$search}%")
                        ->orWhere('item_name', 'LIKE',"%{$search}%")
                        ->orWhere('is_active', 'LIKE',"%{$search}%")
                        ->orWhere('unit_price', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                       // ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = Item::where('item_code','LIKE',"%{$search}%")
                            ->orWhere('item_name', 'LIKE',"%{$search}%")
                            ->orWhere('is_active', 'LIKE',"%{$search}%")
                            ->orWhere('unit_price', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if( !empty($items) ) {
            foreach ($items as $item)
                {
                    $Customer['item_code'] = $item->item_code;
                    $Customer['item_name'] = $item->item_name;
                    $Customer['is_active'] = ($item->is_active==1) ? 'Active' : 'inactive';
                    $Customer['unit_price'] = $item->unit_price;
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
        $items->item_description = $request->item_description;
        $items->category_code=$request->category_code;
        $items->unit_price=$request->unit_price;
        $items->is_active = $request->is_active;
        $items->division=$request->division;
        $items->parent_item=$request->parent_item;
        $items->save();

        if(isset($request->details)){
                foreach($request->details as $element) {
                    $Details = new ItemSerialNumbers();
                    $Details->item_id = $items->id;
                    $Details->serial_no = $element['serial_number'];
                    $Details->is_active = $element['is_active'];
                    $Details->save();
                }
        }
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
       // $this->validate($request, [
           // 'client_code' => ['required',Rule::unique('customers')->ignore($id)],
           // 'user_id' => ['required',Rule::unique('customers')->ignore($id)],
       // ]);
        DB::beginTransaction();
        $items = Item::where('id',$id)->first();
        if(isset($items))
        {
            $items->item_name  = $request->item_name;
            $items->item_description = $request->item_description;
            $items->category_code=$request->category_code;
            $items->unit_price=$request->unit_price;
            $items->is_active = $request->is_active;
            $items->division=$request->division;
            $items->parent_item=$request->parent_item;
            $items->save();
            $deleteData=ItemSerialNumbers::where('item_id',$items->id)->delete();
            if(isset($request->details)){
                foreach($request->details as $element) {
                    $Details = new ItemSerialNumbers();
                    $Details->item_id = $items->id;
                    $Details->serial_no = $element['serial_number'];
                    $Details->is_active = $element['is_active'];
                    $Details->save();
                }
        }
        }
        else
        {
        return response()->json(['message' => 'Failed to update the record!','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['item'=>$items,'message'=>'Record successfully updated!','status'=>200],200);
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
        $max_code=DB::select("select item_code  from items  ORDER BY RIGHT(item_code , 10) DESC");
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
        $new_code='ITM'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

    public function getAutocomplete(Request $request){

        $search = $request->search;
  
        if($search == ''){
           $autocomplate = Item::orderby('item_name','asc')->select('*')->limit(5)->get();
        }else{
           $autocomplate = Item::orderby('item_name','asc')->select('*')->where('item_name', 'like', '%' .$search . '%')->limit(5)->get();
        }
  
        $response = array();
        foreach($autocomplate as $autocomplate){
           $response[] = array("value"=>$autocomplate->id,"label"=>$autocomplate->item_name);
        }
  
        echo json_encode($response);
        exit;
     }
    
     public function getParentItems(){
        return response()->json(Item::where('is_active',1)->where('parent_item',0)->latest()->get());

     }

     public function getItemSerialNumber($id){
        return response()->json(ItemSerialNumbers::where('item_id',$id)->latest()->get());
     }

}
