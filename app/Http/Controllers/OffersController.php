<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Offers;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffersController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        if( Auth::user()->type=='Merchant' || Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->id;
            if( Auth::user()->type=='Agent'){
                $merchant_id = Auth::user()->merchant_id;
            }
            $offers = Offers::query()->where('merchant_id',$merchant_id)->orderBy('id','desc')->simplePaginate(20);
            $categories = Categories::query()->get();
            return view('merchantPanel.offer.index',compact('offers','categories'));
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
            'description'   => 'required',
            'category_id'   => 'required',
            //'start_date'    => 'required',
            //'end_date'      => 'required',
        ],
            [
                'name.required'         => 'Name required',
                'description.required'  => 'Description required',
                'category_id.required'  => 'Category required',
                //'start_date.required'   => 'Start date required',
                //'end_date.required'     => 'End date required',
            ]
        );
        if( Auth::user()->type=='Merchant'){
            $merchant_id = Auth::user()->id;
        }
        elseif( Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->merchant_id;
        }

        Offers::query()->insertGetId([
            'merchant_id'       => $merchant_id,
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'discount'          => $request->discount,
            'point'             => $request->point,
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
            'description'   => 'required',
            'category_id'   => 'required',
            //'start_date'    => 'required',
            //'end_date'      => 'required',
        ],
            [
                'name.required'         => 'Name required',
                'description.required'  => 'Description required',
                'category_id.required'  => 'Category required',
                //'start_date.required'   => 'Start date required',
                //'end_date.required'     => 'End date required',
            ]
        );

        $data=[
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'discount'          => $request->discount,
            'point'             => $request->point,
            'status'            => 'pending',
            'updated_at'    => Carbon::now()
        ];

        Offers::query()->where('id',$request->id)->update($data);

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
    }

# ------------------------------------------ Admin panel use ------------------------------------- #
    function getAllOffer(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Offer');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $offers = Offers::query()->orderBy('id','desc')->simplePaginate(20);
            $categories = Categories::query()->get();
            return view('offer.index',compact('offers','categories'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    function statusUpdate(Request $request){
        $data=[
            'status'       => $request->status,
            'updated_at'    => Carbon::now()
        ];

        Offers::query()->find($request->id)->update($data);

        Toastr::success('Status Updated Successfully','Success');
        return redirect()->back();
    }


}