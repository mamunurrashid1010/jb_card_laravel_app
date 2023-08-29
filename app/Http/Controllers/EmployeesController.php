<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\departments;
use App\Models\Designations;
use App\Models\Employees;
use App\Models\Pay_Grade;
use App\Models\Shifts;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EmployeesController extends Controller
{
    /**
     * show function return a view with all employee list in a company.
     */
    function show(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Employee Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $company_id=Auth::user()->company_id;
            $employee_id=$request->employee_id;

            $employees=Employees::where(function ($q) use ($company_id,$employee_id){
                $q->where('company_id',$company_id);
                if(!empty($employee_id)){
                    $q->where('id',$employee_id);
                }
            })
            ->simplePaginate(10);
            return view('employees.show',compact('employees'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * create function
     */
    function create(){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Employee Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $company_id=Auth::user()->company_id;
            $departments=departments::select('id','name')->where('company_id',$company_id)->get();
            $designations=Designations::select('id','name')->where('company_id',$company_id)->get();
            $branches=Branches::select('id','name')->where('company_id',$company_id)->get();
            $shifts=Shifts::select('id','name')->where('company_id',$company_id)->get();
            //$payGrades=Pay_Grade::select('id','name')->where('company_id',$company_id)->get();
            return view('employees.create',compact('departments','designations','branches','shifts'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * save function, store employee info to db.
     */
    function save(Request $request){
       // dd($request->all());
        $request->validate([
            'department_id'     => 'required',
            'designation_id'    => 'required',
            'branch_id'         => 'required',
            'shift_id'          => 'required',
            //'pay_grade_id'      => 'required',
            'name'              => 'required',
            'phone'             => 'required',
            'gender'            => 'required',
            'birth_date'        => 'required',
            'joining_date'      => 'required',
            'job_status'        => 'required',
            'status'            => 'required',
            'present_address'   => 'required',
            'permanent_address' => 'required',
            //'photo'             => 'image|mimes:jpg,png,jpeg|max:300',
        ]);
        $company_id=Auth::user()->company_id;
        $image='';
        if (!empty($request->image))
        {
            $image = $company_id.'_'.date('YmdHims').'.'.$request->image->extension();
            $request->image->move(public_path('assets/images'), $image);
        }
        $result=Employees::insert([
            'company_id'        => $company_id,
            'department_id'     => $request->department_id,
            'designation_id'    => $request->designation_id,
            'branch_id'         => $request->branch_id,
            'shift_id'          => $request->shift_id,
            //'pay_grade_id'      => $request->pay_grade_id,
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'fingerprint_id'    => $request->fingerprint_id,
            'gender'            => $request->gender,
            'religion'          => $request->religion,
            'birth_date'        => $request->birth_date,
            'joining_date'      => $request->joining_date,
            'job_status'        => $request->job_status,
            'reference'         => $request->reference,
            'spouse_name'       => $request->spouse_name,
            'emergency_contact' => $request->emergency_contact,
            'id_type'           => $request->id_type,
            'id_number'         => $request->id_number,
            'status'            => $request->status,
            'present_address'   => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'photo'             => $image,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number'=> $request->bank_account_number,
            'created_at'        => Carbon::now(),
        ]);

        if ($result){
            $toastMesssage=Toastr::success('Record added successfully', 'Success');
            return redirect()->back()->with($toastMesssage);
        }
        else
        {
            $toastMesssage=Toastr::error('Fail', 'Fail!');
            return redirect()->back()->with($toastMesssage);
        }
    }

    /**
     * edit
     */
    function edit(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Employee Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $company_id=Auth::user()->company_id;
            # check employeeID is valid
            $employeeInfo=Employees::where('company_id',$company_id)->find($request->employeeID);
            if($employeeInfo){
                $employee=$employeeInfo->toArray();
                $departments=departments::select('id','name')->where('company_id',$company_id)->get();
                $designations=Designations::select('id','name')->where('company_id',$company_id)->get();
                $branches=Branches::select('id','name')->where('company_id',$company_id)->get();
                $shifts=Shifts::select('id','name')->where('company_id',$company_id)->get();
                //$payGrades=Pay_Grade::select('id','name')->where('company_id',$company_id)->get();
                return view('employees.edit',compact('employee','departments','designations','branches','shifts'));
            }
            else{
                return redirect()->route('home');
            }

        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * update function
     */
    function update(Request $request){
        $request->validate([
            'department_id'     => 'required',
            'designation_id'    => 'required',
            'branch_id'         => 'required',
            'shift_id'          => 'required',
            //'pay_grade_id'      => 'required',
            'name'              => 'required',
            'phone'             => 'required',
            'gender'            => 'required',
            'birth_date'        => 'required',
            'joining_date'      => 'required',
            'job_status'        => 'required',
            'status'            => 'required',
            'present_address'   => 'required',
            'permanent_address' => 'required',
            //'photo'             => 'image|mimes:jpg,png,jpeg|max:300',
        ]);
        $company_id=Auth::user()->company_id;
        if(empty($request->employee_id)){
            return redirect()->route('home');
        }
        # image
        $OldImage=Employees::select('photo')->where('id',$request->employee_id)->where('company_id',$company_id)->first();
        if (!empty($request->image))
        {
            $image = $company_id.'_'.date('YmdHims').'.'.$request->image->extension();
            $request->image->move(public_path('assets/images'), $image);
            #delete old image
            if (File::exists(public_path('assets/images'.$OldImage->photo))) {
                File::delete(public_path('assets/images'.$OldImage->photo));
            }
        }
        else
            $image=$OldImage->photo;

        $data=[
            'department_id'     => $request->department_id,
            'designation_id'    => $request->designation_id,
            'branch_id'         => $request->branch_id,
            'shift_id'          => $request->shift_id,
            //'pay_grade_id'      => $request->pay_grade_id,
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'fingerprint_id'    => $request->fingerprint_id,
            'gender'            => $request->gender,
            'religion'          => $request->religion,
            'birth_date'        => $request->birth_date,
            'joining_date'      => $request->joining_date,
            'job_status'        => $request->job_status,
            'reference'         => $request->reference,
            'spouse_name'       => $request->spouse_name,
            'emergency_contact' => $request->emergency_contact,
            'id_type'           => $request->id_type,
            'id_number'         => $request->id_number,
            'status'            => $request->status,
            'present_address'   => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'photo'             => $image,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number'=> $request->bank_account_number,
            'updated_at'        =>Carbon::now()
        ];
        $result=Employees::where('id',$request->employee_id)->where('company_id',$company_id)->update($data);
        if ($result){
            $toastMesssage=Toastr::success('Update successfully', 'Success');
            return redirect()->back()->with($toastMesssage);
        }
        else
        {
            $toastMesssage=Toastr::error('Fail', 'Fail!');
            return redirect()->back()->with($toastMesssage);
        }
    }

    /**
     * deviceId_duplicateCheck function
     * check a given employee device Id is already use or not
     */
    function deviceId_duplicateCheck(Request $request){
        $company_id=Auth::user()->company_id;
        $countResult=Employees::where('company_id',$company_id)->where('fingerprint_id',$request->device_id)->count();
        if($countResult){
            return 1;
        }
        else
            return 0;
    }

}
