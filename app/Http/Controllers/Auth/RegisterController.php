<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\CustomerPackages;
use App\Models\Packages;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
//use Hash;
//use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register()
    {
        $packages = Packages::query()->get();
        return view('auth.register',compact('packages'));
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'phone'     => 'required',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
            'package'   => 'required',
            //'company_name'      => 'required|string|max:255',
        ]);

        # add company/store record
//        $companyId=Companies::insertGetId([
//            'name'=>$request->company_name,
//            'created_at' => date("Y-m-d H:i:s")
//        ]);

        DB::beginTransaction();
        try{
            # user(customer) registration
            $regStatus=User::query()->create([
                'name'      => $request->name,
                //'avatar'    => $request->image,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'type'      => 'Customer',
                'password'  => Hash::make($request->password),
                //'company_id' => $companyId,
                'created_at' => Carbon::now(),
            ]);
            # customer package
            CustomerPackages::query()->create([
                'customer_id' => $regStatus->id,
                'package_id'  => $request->package,
            ]);

            DB::commit();
            Toastr::success('Create new account successfully :)','Success');
            return redirect('login');
        }
        catch(\Exception $e){
            DB::rollback();
            //dd($e);
            Toastr::error('Fail :)','Error');
            return redirect()->back();
        }


//            if ($regStatus==true)
//            {
//                $data= User::where('id',$regStatus->id)->update([
//                    'company_id' => $companyId,
//                ]);
//                Toastr::success('Create new account successfully :)','Success');
//                return redirect('login');
//            }

    }
}
