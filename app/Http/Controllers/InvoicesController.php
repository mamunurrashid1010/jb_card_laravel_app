<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Invoice Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $invoices = Invoices::query()->simplePaginate(20);
            return view('invoice.index',compact('invoices'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
    /**
     * getUserList_searchByName function
     * return searchable customer/merchant/user id,name
     */
    function getUserList_searchByName(Request $request)
    {
        $data = [];
        if($request->filled('q')){
            $data = User::query()->select('name','id','email')
                ->where('name', 'LIKE', '%'. $request->get('q'). '%')
                ->where('type','!=','Admin')
                ->where('type','!=','User')
                ->where('type','!=','Agent')
                ->where('status','active')
                ->take(10)
                ->get();
        }
        return response()->json($data);
    }
}
