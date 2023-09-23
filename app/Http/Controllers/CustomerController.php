<?php

namespace App\Http\Controllers;

use App\Models\CustomerPackages;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /* ---------------------------------------- Customer panel use ------------------------------------------------- */
    /**
     * profile
     */
    function profile()
    {
        if( Auth::user()->type=='Customer'){
            $customer = User::query()
                ->find(Auth::user()->id);
            return view('customerPanel.profile.index',compact('customer'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
    /* ---------------------------------------- Admin panel use ------------------------------------------------- */
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Customer Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $email = $request->email;
            $customers = User::query()
                ->where(function ($q) use ($email){
                    if($email)
                        $q->where('email',$email);
                })
                ->where('type','Customer')
                ->orderBy('id','desc')->paginate(10);
            return view('customer.index',compact('customers'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * accountStatusUpdate
     */
    function accountStatusUpdate(Request $request){
        $data=[
            'status'       => $request->status,
            'updated_at'    => Carbon::now()
        ];

        User::query()->find($request->id)->update($data);

        Toastr::success('Status Updated Successfully','Success');
        return redirect()->back();
    }

    /**
     * packageStatusUpdate
     */
    function packageStatusUpdate(Request $request){
        $data=[
            'status'       => $request->status,
            'updated_at'    => Carbon::now()
        ];

        CustomerPackages::query()->where('customer_id',$request->id)->update($data);

        Toastr::success('Status Updated Successfully','Success');
        return redirect()->back();
    }

}
