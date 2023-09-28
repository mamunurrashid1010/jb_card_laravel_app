<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    function index(Request $request){
        if(Auth::user()->type=='Merchant'){
            $merchant_id = Auth::user()->id;
            $agents = User::query()->where('merchant_id',$merchant_id)->orderBy('id','desc')->get();
            return view('merchantPanel.agent.index',compact('agents'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    function store(Request $request){
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            //'phone'     => 'required|min:11|numeric',
            'status'    => 'required|string|max:255',
            //'image'     => 'required|image',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
        ],
            [
                'name.required'         =>  'Name required!',
                'email.required'        =>  'Email required!',
                'email.email'           =>  'Email format not valid!',
                'email.unique'          =>  'Email exist!',
                'status.required'       =>  'Status required!',
                'password.required'     =>  'Password required!',
                'password_confirmation.required'     =>  'Repeat password required!',
                'password.confirmed'    =>  'Password not match!',
            ]
        );

        if(!empty($request->image))
        {
            $image = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/users/'), $image);
        }
        else
            $image='';

        $merchant_id = Auth::user()->id;
        $user = new User;
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->type         = 'Agent';
        $user->status       = $request->status;
        $user->merchant_id  = $merchant_id;
        $user->image        = $image;
        $user->password     = Hash::make($request->password);
        $user->save();

        Toastr::success('Create new account successfully :)','Success');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $name         = $request->name;
            $phone        = $request->phone;
            $status       = $request->status;

            # image update
            $oldInfo = User::query()->select('image','email')->where('id',$request->id)->first();
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
            if($request->new_email){
                $emailExist = User::query()->where('email',$request->new_email)->exists();
                if($emailExist){
                    Toastr::error('Email already exist!','Error');
                    return redirect()->back();
                }
                else
                    $email = $request->new_email;
            }

            $update = [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'status'  => $status,
                'image'   => $image,
            ];

            User::query()->where('id',$request->id)->update($update);

            # password update
            if($request->password){
                User::query()->find($request->id)->update(['password'=> Hash::make($request->password)]);
            }

            DB::commit();
            Toastr::success('User updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('User update fail :)','Error');
            return redirect()->back();
        }
    }

}
