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

class EmployeeController extends Controller
{
    //
    public function showAllEmployee(Request $request)
    {
        $columns = array( 
            0 =>'employee_code', 
            1 =>'employee_name',
            2=> 'nic',
            3=> 'phone',
        );

        $totalData = Employee::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if( empty($request->input('search.value')) ) {            
            $items = Employee::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $request->input('search.value'); 

            $items =  Employee::where('employee_code','LIKE',"%{$search}%")
                        ->orWhere('first_name', 'LIKE',"%{$search}%")
                        ->orWhere('last_name', 'LIKE',"%{$search}%")
                        ->orWhere('nic', 'LIKE',"%{$search}%")
                        ->orWhere('phone', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = Employee::where('employee_code','LIKE',"%{$search}%")
                            ->orWhere('first_name', 'LIKE',"%{$search}%")
                            ->orWhere('last_name', 'LIKE',"%{$search}%")
                            ->orWhere('nic', 'LIKE',"%{$search}%")
                            ->orWhere('phone', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if( !empty($items) ) {
            foreach ($items as $item)
                {
                    $Customer['employee_code'] = $item->employee_code;
                    $Customer['employee_name'] = $item->first_name.' '.$item->last_name;
                    $Customer['nic'] = $item->nic;
                    $Customer['phone'] = $item->phone;
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
    public function showOneEmployee($id)
    {
        return response()->json(Employee::find($id));
    }
    public function getAllEmployee()
    {
        return response()->json(Employee::latest()->get());
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
        $employee = new Employee();
        $employee->employee_code  = $this->code_Create();
        $employee->first_name = $request->first_name;
        $employee->last_name=$request->last_name;
        $employee->nic = $request->nic;
        $employee->phone=$request->phone;
        $employee->mobile=$request->mobile;
        $employee->address_line_1=$request->address_line_1;
        $employee->address_line_2=$request->address_line_2;
        $employee->epf_number=$request->epf_number;
        $employee->etf_number=$request->etf_number;
        $employee->is_active=$request->is_active;
        $employee->save();
    
        DB::commit();
        return response()->json(['employee' => $employee, 'message' => 'CREATED','status'=>200], 200);
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
        $employee = Employee::where('id',$id)->first();
        if(isset($employee))
        {
            $employee->first_name = $request->first_name;
            $employee->last_name=$request->last_name;
            $employee->nic = $request->nic;
            $employee->phone=$request->phone;
            $employee->mobile=$request->mobile;
            $employee->address_line_1=$request->address_line_1;
            $employee->address_line_2=$request->address_line_2;
            $employee->epf_number=$request->epf_number;
            $employee->etf_number=$request->etf_number;
            $employee->is_active=$request->is_active;
            $employee->save();          
        }
        else
        {
        return response()->json(['message' => 'UPDATED Failed','status'=>500], 500);
        }
        DB::commit();
        return response()->json(['employee'=>$employee,'message'=>'UPDATED','status'=>200],200);
        } catch (\Exception $e) {
        //return error message
        DB::rollBack();
        Log::error($e);
        return response()->json(['message' => $e->getmessage(),'status'=>500], 500);
        }
    }
    public function delete($id)
    {
        Employee::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    public function code_Create()
    {
        $max_code=DB::select("select employee_code  from employees  ORDER BY RIGHT(employee_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0)
        {
            $new_code=0;
        }
        else
        {
            $last_code_no=$max_code[0]->employee_code;
            //$last_file_no=SalesHeader::where('invoice_number',$last_file_no)->first();
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='EMP'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
