<?php

namespace App\Http\Controllers\Auth;

use App\CustomClass\accountHead;
use App\Http\Controllers\Controller;
use App\Models\Companies;
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
        return view('auth.register');
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
            'company_name'      => 'required|string|max:255',
        ]);

        # add company/store record
        $companyId=Companies::insertGetId([
            'name'=>$request->company_name,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($companyId==true)
        {
            # user registration
            $regStatus=User::create([
                'name'      => $request->name,
                //'avatar'    => $request->image,
                'email'     => $request->email,
                'type'      => 'Admin',
                'password'  => Hash::make($request->password),
                'company_id' => $companyId,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            if ($regStatus==true)
            {
                $data= User::where('id',$regStatus->id)->update([
                    'company_id' => $companyId,
                ]);
                Toastr::success('Create new account successfully :)','Success');
                return redirect('login');
            }
        }

    }
}
