<?php

namespace App\Http\Controllers;

use App\Models\CustomerPackages;
use App\Models\Packages;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * profileUpdate
     * @param Request $request
     * @return RedirectResponse
     */
    function profileUpdate(Request $request): RedirectResponse
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
            $packages = Packages::query()->get();
            return view('customer.index',compact('customers','packages'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * new customer info store form admin panel
     */
    function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'phone'     => 'required',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
            'package'   => 'required',
        ]);

        DB::beginTransaction();
        try{

            if (!empty($request->image))
            {
                $image = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/users/'), $image);
            }
            else
                $image='';

            # customer registration
            $regStatus=User::query()->create([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'type'      => 'Customer',
                'image'     => $image,
                'password'  => Hash::make($request->password),
                'created_at' => Carbon::now(),
            ]);
            # customer package
            CustomerPackages::query()->create([
                'customer_id' => $regStatus->id,
                'package_id'  => $request->package,
                'status'      => 'active',
            ]);

            DB::commit();
            Toastr::success('Create new account successfully :)','Success');
            return redirect()->back();
        }
        catch(\Exception $e){
            DB::rollback();
            //dd($e);
            Toastr::error('Fail :)','Error');
            return redirect()->back();
        }
    }

    /**
     * update, customer info update from admin panel
     */
    function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $name         = $request->name;
            //$email        = $request->email;
            $phone        = $request->phone;
            //$status       = $request->status;

            # image update
            $oldInfo = User::query()->select('image','email')->where('id',$request->id)->first();
            if (!empty($request->image))
            {
                $image = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/users/'), $image);
                #delete old image
                if (File::exists(public_path('images/users/'.$oldInfo->image))){
                    File::delete(public_path('images/users/'.$oldInfo->image));
                }
            }
            else{
                $image=$oldInfo->image ?? '';
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
                //'status'  => $status,
                'image'   => $image,
                'updated_at'=>Carbon::now(),
            ];

            User::query()->where('id',$request->id)->update($update);

            # password update
            if($request->password){
                User::query()->find($request->id)->update(['password'=> Hash::make($request->password)]);
            }

            DB::commit();
            Toastr::success('Updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            dd($e);
            Toastr::error('User update fail :)','Error');
            return redirect()->back();
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

    /* ---------------------------------------- Merchant panel use ------------------------------------------------- */
    /**
     * getCustomerList_searchByName function
     * return searchable customer id,name
     */
    function getCustomerList_searchByName(Request $request)
    {
        $data = [];
        if($request->filled('q')){
            $data = User::query()->select('name','id')
                ->where('name', 'LIKE', '%'. $request->get('q'). '%')
                ->where('type','Customer')
                ->where('status','active')
                ->take(5)
                ->get();
        }
        return response()->json($data);
    }

    /**
     * getCustomerDetails
     */
    function getCustomerDetails(Request $request)
    {
        $merchant_id = Auth::user()->id;
        if(Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->merchant_id;
        }

        $customer_id = $request->customerId;
        $customer = User::query()
            ->with('merchantWiseWallet',function ($q) use ($merchant_id){
                $q->where('merchant_id',$merchant_id);
                $q->where('status','confirm');
            })
            ->where('id',$customer_id)
            ->where('type','Customer')
            ->where('status','active')
            ->first();
        # wallet point calculation
        $merchantWiseWalletPoint_add = $customer->merchantWiseWallet->where('point_status','add')->sum('point');
        $merchantWiseWalletPoint_deduction = $customer->merchantWiseWallet->where('point_status','deduction')->sum('point');
        $customer['merchantWiseWalletPoint'] = ($merchantWiseWalletPoint_add - $merchantWiseWalletPoint_deduction);

        return response()->json($customer);
    }

}
