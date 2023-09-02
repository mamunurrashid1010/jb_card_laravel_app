<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackagesController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Package');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $packages = Packages::query()->get();
            return view('packages.index',compact('packages'));
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
            'name'          => 'required',
            'amount'        => 'required',
        ],
            [
                'name.required'         => 'Name required',
                'amount.required'       => 'Amount required',
            ]
        );

        Packages::query()->insertGetId([
            'name'              => $request->name,
            'amount'            => $request->amount,
            'created_at'        => Carbon::now(),
        ]);

        Toastr::success('Data Added Successfully','Success');
        return redirect()->back();
    }

    /**
     * update
     */
    function update(Request $request){
        $request->validate([
            'name'          => 'required',
            'amount'        => 'required',
        ],
            [
                'name.required'         => 'Name required',
                'amount.required'       => 'Amount required',
            ]
        );

        $data=[
            'name'          => $request->name,
            'amount'        => $request->amount,
            'updated_at'    => Carbon::now()
        ];

        Packages::query()->where('id',$request->id)->update($data);

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
    }

}
