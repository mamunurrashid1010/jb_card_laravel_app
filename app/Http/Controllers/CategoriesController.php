<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Category Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $categories = Categories::query()->get();
            return view('category.index',compact('categories'));
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
            'name'  => 'required',
        ],
            [
                'name.required'   => 'Name required',
            ]
        );

        Categories::query()->insert([
            'name'              => $request->name,
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
        ],
            [
                'name.required'         => 'Name required',
            ]
        );

        $data=[
            'name'          => $request->name,
            'updated_at'    => Carbon::now()
        ];

        Categories::query()->where('id',$request->id)->update($data);

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
    }
}
