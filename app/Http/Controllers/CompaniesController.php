<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\permissions;
use App\Models\User;
use App\Models\user_permissions;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CompaniesController extends Controller
{
    /**
     * view function return/view a company information.
     */
    function view(){
        #get module permission record
        $moduleAccess="deny";
        $permission=permissions::select('id','name')->where('name','Company Manage')->first();
        $modulePID=$permission->id;
        $user_id=Auth::user()->id;
        $count=user_permissions::where('user_id',$user_id)->where('permission_id',$modulePID)->count();
        if ($count)
            $moduleAccess="allow";

        if( Auth::user()->type=='SuperAdmin' || $moduleAccess=='allow')
        {
            $company_id=Auth::user()->company_id;
            $companyInfo=Companies::where('id',$company_id)->first();
            return view('companies.view',compact('companyInfo'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * update function post/update to db table of a company information.
     */
    function update(Request $request){
        $company_id=Auth::user()->company_id;
        $data=[
            'name'=>$request->companyName,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ];
        $restult=Companies::where('id',$company_id)->update($data);
        if ($restult){
            $toastMesssage=Toastr::success('Update successfully', 'Success');
            return redirect()->back()->with($toastMesssage);
        }
        else
        {
            $toastMesssage=Toastr::error('Update Fail', 'Fail!');
            return redirect()->back()->with($toastMesssage);
        }
    }

    # ---------------------------------------------  Super Admin -----------------------------------------------------
    /**
     * getAllCompanyList function
     * this function use only super admin
     */
    function getAllCompanyList(){
        if(Auth::user()->type=='SuperAdmin'){
            $companies = Companies::orderBy('id','asc')->get();
            return view('superAdmin.company.view',compact('companies'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
    /**
     * loginCompanyAccount_BySuperAdmin function
     * login company account by SuperAdmin without password
     * @param $request->companyId
     * @return Redirect to account access;
     */
    function loginCompanyAccount_BySuperAdmin(Request $request){
        //$request->validate(['companyId'=>'required']);
        if(Auth::user()->type=='SuperAdmin'){
            $company=Companies::where('id',$request->companyId)->count();
            if($company){
                $user=User::select('id')->where('company_id',$request->companyId)->where('type','Admin')->first();
                if(!empty($user->id)){
                    Auth::loginUsingId($user->id);
                    return redirect()->to('/home');
                }
                else{
                    $toastMesssage=Toastr::error('Admin account not available!.', 'Fail!', ["positionClass" => "toast-top-right","closeButton"=>"true","progressBar"=>"true","timeOut"=>"5000"]);
                    return redirect()->back()->with($toastMesssage);
                }
            }
            else{
                $toastMesssage=Toastr::error('Company not found.', 'Fail!', ["positionClass" => "toast-top-right","closeButton"=>"true","progressBar"=>"true","timeOut"=>"5000"]);
                return redirect()->back()->with($toastMesssage);
            }
        }
        else
        {
            Auth::logout();
        }
    }


}
