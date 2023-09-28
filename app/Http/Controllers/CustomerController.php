<?php

namespace App\Http\Controllers;

use App\Models\CustomerPackages;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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

    /**
     * update
     * @param Request $request
     * @return RedirectResponse
     */
    function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required',
        ],
            [
                'name.required'      =>  'Name required!',
                'phone.required'     =>  'Phone required!',
            ]
        );
        $customer_id = auth()->user()->id;

        # image update
        $oldInfo=User::query()->select('image','email')->where('id',$customer_id)->first();
        if (!empty($request->image))
        {
            $image = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/users/'), $image);
            #delete old image
            if (File::exists(public_path('images/users/'.$oldInfo->image))) {
                File::delete(public_path('images/users/'.$oldInfo->image));
            }
        }
        else{
            $image=$oldInfo->image;
        }

        # email update
        $email = $oldInfo->email;
        if($request->email){
           $emailExist = User::query()->where('email',$request->email)->exists();
           if($emailExist){
               Toastr::error('Email already exist!','Error');
               return redirect()->back();
           }
           else
               $email = $request->email;
        }

        $data = [
            'name'      => $request->name,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'email'     => $email,
            'image'     => $image,
            'updated_at' => Carbon::now()
        ];
        User::query()->find($customer_id)->update($data);

        # password update
        if($request->current_password && $request->password){
            $checkPassword = Hash::check($request->current_password, auth()->user()->password);
            if(!$checkPassword){
                Toastr::error('Current password does not match!','Error');
                return redirect()->back();
            }
            else{
                User::query()->find($customer_id)->update(['password'=> Hash::make($request->password)]);
            }
        }

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
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


    /**
     * getCustomerList_searchByName function
     * return searchable customer id,name
     */
    function getCustomerList_searchByName(Request $request)
    {
        //$company_id=Auth::user()->company_id;
        $data = [];
        if($request->filled('q')){
            $data = User::query()->select('name','id')
                ->where('name', 'LIKE', '%'. $request->get('q'). '%')
                //->where('company_id',$company_id)
                ->where('type','Customer')
                ->take(5)
                ->get();
        }
        return response()->json($data);
    }

}
