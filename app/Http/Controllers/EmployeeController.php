<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
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
            0 => 'employee_code', 
            1 => 'first_name',
            2 => 'nic',
            3 => 'telephone_1',
            4 => 'epf_number'
        );

        $totalData = Employees::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if( empty($request->input('search.value')) ) {            
            $items = Employees::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $request->input('search.value'); 

            $items =  Employees::where('employee_code','LIKE',"%{$search}%")
                        ->orWhere('first_name', 'LIKE',"%{$search}%")
                        ->orWhere('last_name', 'LIKE',"%{$search}%")
                        ->orWhere('nic', 'LIKE',"%{$search}%")
                        ->orWhere('telephone_1', 'LIKE',"%{$search}%")
                        ->orWhere('epf_number', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();

            $totalFiltered = Employees::where('employee_code','LIKE',"%{$search}%")
                            ->orWhere('first_name', 'LIKE',"%{$search}%")
                            ->orWhere('last_name', 'LIKE',"%{$search}%")
                            ->orWhere('nic', 'LIKE',"%{$search}%")
                            ->orWhere('telephone_1', 'LIKE',"%{$search}%")
                            ->orWhere('epf_number', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if( !empty($items) ) {
            foreach ($items as $item)
                {
                    $Employee['employee_code'] = $item->employee_code;
                    $Employee['first_name'] = $item->first_name.' '.$item->last_name;
                    $Employee['nic'] = $item->nic;
                    $Employee['telephone_1'] = $item->telephone_1;
                    $Employee['epf_number'] = $item->epf_number;
                    $Employee['action'] = '<button class="edit btn btn-icon btn-success btn-sm mr-2" data-id='.$item->id.'><i class="text-light-50 flaticon-edit"></i></button>';
                    $data[] = $Employee;
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
        return response()->json(Employees::find($id));
    }
    public function getAllEmployee()
    {
        return response()->json(Employees::latest()->get());
    }
    public function create(Request $request)
    {
    try {
            DB::beginTransaction();
            $employee = new Employees();
            $employee->employee_code  = $this->code_Create();
            $employee->first_name     = $request->first_name;
            $employee->last_name      = $request->last_name;
            $employee->nic            = $request->nic;
            $employee->telephone_1    = $request->telephone_1;
            $employee->telephone_2    = $request->telephone_2;
            $employee->address_line_1 = $request->address_line_1;
            $employee->address_line_2 = $request->address_line_2;
            $employee->city           = $request->city;
            $employee->email          = $request->email;
            $employee->zip_code       = $request->zip_code;
            $employee->epf_number     = $request->epf_number;
            $employee->etf_number     = $request->etf_number;
            $employee->is_active      = $request->is_active;
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
     try {
        
            DB::beginTransaction();
            $employee = Employees::where('id',$id)->first();
            if( isset($employee) ) {
                $employee->first_name     = $request->first_name;
                $employee->last_name      = $request->last_name;
                $employee->nic            = $request->nic;
                $employee->telephone_1    = $request->telephone_1;
                $employee->telephone_2    = $request->telephone_2;
                $employee->address_line_1 = $request->address_line_1;
                $employee->address_line_2 = $request->address_line_2;
                $employee->city           = $request->city;
                $employee->email          = $request->email;
                $employee->zip_code       = $request->zip_code;
                $employee->epf_number     = $request->epf_number;
                $employee->etf_number     = $request->etf_number;
                $employee->is_active      = $request->is_active;
                $employee->save();          
        } else {
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
    public function code_Create() {
        $max_code=DB::select("select employee_code  from employees  ORDER BY RIGHT(employee_code , 10) DESC");
        $Regi=null;
        if(sizeof($max_code)==0) {
            $new_code=0;
        } else {
            $last_code_no=$max_code[0]->employee_code;
            list($Regi,$new_code) = explode('-', $last_code_no);
        }
        $new_code='EMP'.'-'.sprintf('%010d', intval($new_code) + 1);
        return $new_code;
    }

}
