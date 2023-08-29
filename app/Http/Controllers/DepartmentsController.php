<?php

namespace App\Http\Controllers;

use App\Models\departments;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    /**
     * show function return a view with all department list in a company.
     */
    function show(){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Employee Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $company_id=Auth::user()->company_id;
            $departments=departments::select('id','name')->where('company_id',$company_id)->simplePaginate(10);
            return view('departments.show',compact('departments'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * save function, store department info to db.
     */
    function save(Request $request){
        $company_id=Auth::user()->company_id;
        $request->validate([
           'name'   => 'required',
        ]);

        $result=departments::insert([
            'company_id'    => $company_id,
            'name'          => $request->name,
            'created_at'    => Carbon::now(),
        ]);

        if ($result){
            $toastMesssage=Toastr::success('Data added successfully', 'Success');
            return redirect()->back()->with($toastMesssage);
        }
        else
        {
            $toastMesssage=Toastr::error('Fail', 'Fail!');
            return redirect()->back()->with($toastMesssage);
        }
    }

    /**
     * update function
     */
    function update(Request $request){
        $company_id=Auth::user()->company_id;
        $result=departments::where('id',$request->id)->where('company_id',$company_id)->update([  'name'=>$request->name,'updated_at'=>Carbon::now()]);
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


}
