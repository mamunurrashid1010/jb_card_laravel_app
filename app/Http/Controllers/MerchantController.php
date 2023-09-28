<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\MerchantPackage;
use App\Models\Packages;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MerchantController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Merchant Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $email = $request->email;
            $merchants = User::query()
                ->where(function ($q) use ($email){
                    if($email)
                        $q->where('email',$email);
                })
                ->where('type','Merchant')
                ->orderBy('id','desc')->paginate(10);
            $packages = Packages::query()->get();
            return view('merchant.index',compact('merchants','packages'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * store
     */
    function store(Request $request){
        $request->validate([
            'business_name' => 'required',
            'owner_name'    => 'required',
            'email'         => 'required|email|max:255|unique:users',
            'phone'         => 'required',
            'address'       => 'required',
            'password'      => 'required|string|min:5',
            'package_id'    => 'required',
        ],
            [
                'business_name.required'=> 'business_name required',
                'owner_name.required'   => 'owner_name required',
                'email.required'        =>  'Email required!',
                'email.email'           =>  'Email format not valid!',
                'email.unique'          =>  'Email exist!',
                'phone.required'        =>  'Phone required',
                'address.required'      =>  'Address required',
                'password.required'     =>  'Password required!',
                'package_id.required'   =>  'Package required',
            ]
        );

        DB::beginTransaction();
        try{
            # image
            if(!empty($request->image))
            {
                $image = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/users/'), $image);
            }
            else{
                $image='';
            }


            $password = Hash::make($request->password);
            # user(merchant) create
            $userId = User::query()->insertGetId([
                'name'      => $request->business_name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'password'  => $password,
                'type'      => 'Merchant',
                'image'     => $image,
                'created_at'=> Carbon::now(),
            ]);
            # merchant details
            $merchantId = Merchant::query()->insertGetId([
                'user_id'               => $userId,
                'business_name'         => $request->business_name,
                'owner_name'            => $request->owner_name,
                //'email'                 => $request->email,
                //'phone'                 => $request->phone,
                //'address'               => $request->address,
                'contact_person_name'   => $request->contact_person_name,
                'contact_person_phone'  => $request->contact_person_phone,
                //'password'              => $password,
                'created_at'            => Carbon::now(),
            ]);
            # merchant package add
            MerchantPackage::query()->create([
                'merchant_id'   => $userId,
                'package_id'    => $request->package_id,
            ]);

            DB::commit();
            Toastr::success('Data Added Successfully','Success');
            return redirect()->back();
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e);
            Toastr::error('Fail :)','Error');
            return redirect()->back();
        }

    }

    /**
     * update
     */
    function update(Request $request){
        $request->validate([
            'business_name' => 'required',
            'owner_name'    => 'required',
            'phone'         => 'required',
            'address'       => 'required',
        ],
            [
                'business_name.required'=> 'business_name required',
                'owner_name.required'   => 'owner_name required',
                'phone.required'        =>  'Phone required',
                'address.required'      =>  'Address required',
            ]
        );

//        DB::beginTransaction();
//        try{

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
                'name'    => $request->business_name,
                'email'   => $email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'status'  => $request->status,
                'image'   => $image,
                'updated_at'=> Carbon::now()
            ];
            User::query()->where('id',$request->id)->update($update);

            $data=[
                'business_name'         => $request->business_name,
                'owner_name'            => $request->owner_name,
                'contact_person_name'   => $request->contact_person_name,
                'contact_person_phone'  => $request->contact_person_phone,
                'updated_at'            => Carbon::now()
            ];
            Merchant::query()->where('user_id',$request->id)->update($data);

            # password update
            if($request->password){
                User::query()->find($request->id)->update(['password'=> Hash::make($request->password)]);
            }

            Toastr::success('Data Updated Successfully!','Success');
            return redirect()->back();
//        }catch(\Exception $e){
//            DB::rollback();
//            Toastr::error('User update fail :)','Error');
//            return redirect()->back();
//        }

    }

}
