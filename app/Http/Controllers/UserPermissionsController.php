<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use App\Models\stores;
use App\Models\User;
use App\Models\user_permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPermissionsController extends Controller
{
    /**
     * moduleAccessPermission
     */
    function moduleAccessPermission($moduleName){
        #get module permission record
        $moduleAccess="deny";
        $permission=permissions::select('id','name')->where('name',$moduleName)->first();
        $modulePID=$permission->id;
        $user_id=Auth::user()->id;
        $count=user_permissions::where('user_id',$user_id)->where('permission_id',$modulePID)->count();
        if ($count)
        {
            $moduleAccess="allow";
            return $moduleAccess;
        }
        else
            return 'deny';
        #close module permission
    }
    /**
     * storeAccessPermission return accessable store details(id,name)
     */
    function storeAccessPermission(){
        $company_id=Auth::user()->company_id;
        $user_id=Auth::user()->id;
        $storeAccessList=User::select('storeAccess')->where('id',$user_id)->where('company_id',$company_id)->first();
        $storeAccessList=$storeAccessList->storeAccess;
        $storeAccessList = explode (",", $storeAccessList);
        $stores=stores::select('id','name')->where('company_id',$company_id)->whereIn('id',$storeAccessList)->get();
        return $stores;
    }
    /**
     * storeAccessPermission_storeId
     */
    function storeAccessPermission_storeId(){
        $company_id=Auth::user()->company_id;
        $user_id=Auth::user()->id;
        $storeAccessList=User::select('storeAccess')->where('id',$user_id)->where('company_id',$company_id)->first();
        $storeAccessList=$storeAccessList->storeAccess;
        $storeAccessList = explode (",", $storeAccessList);
        return $storeAccessList;
    }
}
