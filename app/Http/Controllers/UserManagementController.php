<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use App\Models\user_permissions;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class UserManagementController extends Controller
{
    public function index()
    {
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Users');
        if (Auth::user()->type=='Admin' || $returnAccessStatus=='allow')
        {
            //$company_id=Auth::user()->company_id;
            $result  = User::query()->where('type','User')->get();
            return view('usermanagement.user_control',compact('result'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    # search user
    public function searchUser(Request $request)
    {
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Users');

        if (Auth::user()->type=='Admin' || $returnAccessStatus=='allow')
        {
            //$company_id=Auth::user()->company_id;
            $users      = DB::table('users')->where('type','User')->get();
            $result     = DB::table('users')->where('type','User')->get();

            // search by name
            if($request->name)
            {
                $result = User::where('name','LIKE','%'.$request->name.'%')->where('type','User')->get();
            }

            // search by role name
            if($request->role_name)
            {
                $result = User::where('type','LIKE','%'.$request->role_name.'%')->where('type','User')->get();
            }

            // search by status
            if($request->status)
            {
                $result = User::where('status','LIKE','%'.$request->status.'%')->where('type','User')->get();
            }

            // search by name and role name
            if($request->name && $request->role_name)
            {
                $result = User::where('name','LIKE','%'.$request->name.'%')
                                ->where('type','LIKE','%'.$request->role_name.'%')
                                ->get();
            }

            // search by role name and status
            if($request->role_name && $request->status)
            {
                $result = User::where('type','LIKE','%'.$request->role_name.'%')
                                ->where('status','LIKE','%'.$request->status.'%')
                                ->get();
            }

            // search by name and status
            if($request->name && $request->status)
            {
                $result = User::where('name','LIKE','%'.$request->name.'%')
                                ->where('status','LIKE','%'.$request->status.'%')
                                ->get();
            }

            // search by name and role name and status
            if($request->name && $request->role_name && $request->status)
            {
                $result = User::where('name','LIKE','%'.$request->name.'%')
                                ->where('type','LIKE','%'.$request->role_name.'%')
                                ->where('status','LIKE','%'.$request->status.'%')
                                ->get();
            }

            return view('usermanagement.user_control',compact('users','result'));
        }
        else
        {
            return redirect()->route('home');
        }

    }

    // profile user
    public function profile()
    {
        $user = Auth::User();
        Session::put('user', $user);
        $user=Session::get('user');
        $profile = $user->rec_id;

        $user = DB::table('users')->get();
        $employees = DB::table('profile_information')->where('rec_id',$profile)->first();

        if(empty($employees))
        {
            $information = DB::table('profile_information')->where('rec_id',$profile)->first();
            return view('usermanagement.profile_user',compact('information','user'));

        }else{
            $rec_id = $employees->rec_id;
            if($rec_id == $profile)
            {
                $information = DB::table('profile_information')->where('rec_id',$profile)->first();
                return view('usermanagement.profile_user',compact('information','user'));
            }else{
                $information = ProfileInformation::all();
                return view('usermanagement.profile_user',compact('information','user'));
            }
        }

    }

    // save profile information
    public function profileInformation(Request $request)
    {
        try{
            if(!empty($request->images))
            {
                $image_name = $request->hidden_image;
                $image = $request->file('images');
                if($image_name =='photo_defaults.jpg')
                {
                    if($image != '')
                    {
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/assets/images/'), $image_name);
                    }
                }
                else{
                    if($image != '')
                    {
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/assets/images/'), $image_name);
                    }
                }
                $update = [
                    'rec_id' => $request->rec_id,
                    'name'   => $request->name,
                    'avatar' => $image_name,
                ];
                User::where('rec_id',$request->rec_id)->update($update);
            }

            $information = ProfileInformation::updateOrCreate(['rec_id' => $request->rec_id]);
            $information->name         = $request->name;
            $information->rec_id       = $request->rec_id;
            $information->email        = $request->email;
            $information->birth_date   = $request->birthDate;
            $information->gender       = $request->gender;
            $information->address      = $request->address;
            $information->state        = $request->state;
            $information->country      = $request->country;
            $information->pin_code     = $request->pin_code;
            $information->phone_number = $request->phone_number;
            $information->department   = $request->department;
            $information->designation  = $request->designation;
            $information->reports_to   = $request->reports_to;
            $information->save();

            DB::commit();
            Toastr::success('Profile Information successfully :)','Success');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Add Profile Information fail :)','Error');
            return redirect()->back();
        }
    }

    // save new user
    public function addNewUserSave(Request $request)
    {

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            //'phone'     => 'required|min:11|numeric',
            'role_name' => 'required|string|max:255',
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
                'role_name.required'    =>  'Role Name required!',
                'status.required'       =>  'Status required!',
                'password.required'     =>  'Password required!',
                'password_confirmation.required'     =>  'Repeat password required!',
                'password.confirmed'    =>  'Password not match!',
            ]
        );
        //$company_id=Auth::user()->company_id;
        DB::beginTransaction();
        try{

//            if (!empty($request->image))
//            {
//                $image = time().'.'.$request->image->extension();
//                $request->image->move(public_path('assets/images'), $image);
//            }
//            else
//                $image='';

            $user = new User;
            //$user->company_id   = 1;
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone        = $request->phone;
            $user->type         = $request->role_name;
            $user->status       = $request->status;
            //$user->avatar       = $image;
            $user->password     = Hash::make($request->password);
            $user->save();
            DB::commit();
            Toastr::success('Create new account successfully :)','Success');
            return redirect()->route('userManagement');
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('User add new account fail :)','Error');
            return redirect()->back();
        }
    }

    // update
    public function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $name         = $request->name;
            $email        = $request->email;
            $role_name    = $request->role_name;
            $phone        = $request->phone;
            $status       = $request->status;

            //$dt       = Carbon::now();
            //$todayDate = $dt->toDayDateTimeString();
//            $image_name = $request->hidden_image;
//            $image = $request->file('images');
//            if($image_name =='photo_defaults.jpg')
//            {
//                if($image != '')
//                {
//                    $image_name = rand() . '.' . $image->getClientOriginalExtension();
//                    $image->move(public_path('/assets/images/'), $image_name);
//                }
//            }
//            else{
//
//                if($image != '')
//                {
//                    $image_name = rand() . '.' . $image->getClientOriginalExtension();
//                    $image->move(public_path('/assets/images/'), $image_name);
//                }
//            }

            $update = [
                'name'    => $name,
                'type'    => $role_name,
                'email'   => $email,
                'phone'   => $phone,
                'status'  => $status,
                //'avatar'  => $image_name,
            ];


            User::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('User updated successfully :)','Success');
            return redirect()->route('userManagement');

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('User update fail :)','Error');
            return redirect()->back();
        }
    }

    // delete
    public function delete(Request $request)
    {
        $user = Auth::User();
        Session::put('user', $user);
        $user=Session::get('user');
        DB::beginTransaction();
        try{
            $fullName     = $user->name;
            $email        = $user->email;
            $phone        = $user->phone;
            $status       = $user->status;
            $role_name    = $user->role_name;

            if($request->avatar =='photo_defaults.jpg'){
                User::destroy($request->id);
            }else{
                User::destroy($request->id);
                unlink('assets/images/'.$request->avatar);
            }
            DB::commit();
            Toastr::success('User deleted successfully :)','Success');
            return redirect()->route('userManagement');

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('User deleted fail :)','Error');
            return redirect()->back();
        }
    }

    // view change password
    public function changePasswordView()
    {
        return view('settings.changepassword');
    }

    // change password in db
    public function changePasswordDB(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        DB::commit();
        Toastr::success('User change successfully :)','Success');
        return redirect()->intended('home');
    }

    function roleUser(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Role');
        if (Auth::user()->type=='Admin' || $returnAccessStatus=='allow')
        {
            //$company_id= Auth::user()->company_id;
            $user_id=$request->user;
            # user validation check
            $users=User::where('id',$user_id)->count();
            if(!empty($user_id) && $users==true )
            {
                $permissionModule=permissions::with(['userPermissions'=>function($q) use ($user_id){$q->where('user_id',$user_id);}])->select('id','name','parent_id')->where('parent_id','!=',0)->orderBy('parent_id')->get();
                $users=User::select('id','name','type')->where('type','User')->get();
                return view('usermanagement.roleUser',compact('permissionModule','users'));
            }
            else
            {
                //$users=User::select('id','name','type')->where('company_id',$company_id)->where('type','User')->get();
                $users=User::select('id','name','type')->where('type','User')->get();
                $permissionModule=[];
                return view('usermanagement.roleUser',compact('permissionModule','users'));
            }
        }
        else
        {
            return redirect()->route('home');
        }
    }

    function userRoleUpdate(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Role');
        if(Auth::user()->type=='Admin' || $returnAccessStatus=='allow')
        {
            //$company_id=Auth::user()->company_id;
            $user_id=$request->user_id;
            $countResult=User::where('id',$user_id)->count();
            if($countResult)
            {
                # permissionModule id with user permission.
                $userPermissions=$request->userPermission;
                user_permissions::where('user_id',$user_id)->delete();
                foreach ($userPermissions as $key=>$userPermission)
                {
                    # $key refers to permission module id.
                    $pemissionModuleID=$key;
                    if ($userPermission=='write')
                    {
                        user_permissions::insert([ 'user_id'=>$user_id, 'permission_id'=>$pemissionModuleID ]);
                    }
                }

                $toastMesssage=Toastr::success('Update successfully', 'Success');
                return redirect()->back()->with($toastMesssage);
            }
            else
            { return redirect()->route('home'); }
        }
        else
        {
            return redirect()->route('home');
        }


    }

}









