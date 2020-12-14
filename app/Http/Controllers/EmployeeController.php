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
        if ($request->ajax()) {
            $data = Employee::latest()->get();
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
        $max_code=DB::select("select employee_code  from employee  ORDER BY RIGHT(employee_code , 10) DESC");
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
