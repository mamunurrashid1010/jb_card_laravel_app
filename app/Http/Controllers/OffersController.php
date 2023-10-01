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
use Illuminate\Support\Facades\File;

class OffersController extends Controller
{
    # ------------------------------------------ Merchant panel use ------------------------------------- #
    /**
     * index
     */
    function index(Request $request){
        if( Auth::user()->type=='Merchant' || Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->id;
            if( Auth::user()->type=='Agent'){
                $merchant_id = Auth::user()->merchant_id;
            }
            $category_id = $request->category_id;
            $offer_code = $request->offer_code;
            $offers = Offers::query()
                ->where(function($q) use ($category_id,$offer_code){
                    if($category_id)
                        $q->where('category_id',$category_id);
                    if($offer_code)
                        $q->where('offer_code',$offer_code);
                })
                ->where('merchant_id',$merchant_id)->orderBy('id','desc')->simplePaginate(20);
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

        # image
        if (!empty($request->image))
        {
            $image = 'offer_'.date('d-m-y').'_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/offers/'), $image);
        }
        else{
            $image='';
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
            'offer_code'        => $request->offer_code,
            'image'             => $image,
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

        # image update
        $oldInfo=Offers::query()->select('image')->where('id',$request->id)->first();
        if (!empty($request->image))
        {
            $image = 'offer_'.date('d-m-y').'_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/offers/'), $image);
            #delete old image
            if (File::exists(public_path('images/offers/'.$oldInfo->image))) {
                File::delete(public_path('images/offers/'.$oldInfo->image));
            }
        }
        else{
            $image=$oldInfo->image;
        }

        $data=[
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'discount'          => $request->discount,
            'point'             => $request->point,
            'offer_code'        => $request->offer_code,
            'image'             => $image,
            'status'            => 'pending',
            'updated_at'    => Carbon::now()
        ];

        Offers::query()->where('id',$request->id)->update($data);

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
    }

    /**
     * getOfferList_searchByName
     */
    function getOfferList_searchByName(Request $request)
    {
        $merchant_id = Auth::user()->id;
        if(Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->merchant_id;
        }

        $data = [];
        if($request->filled('q')){
            $data = Offers::query()
                ->where('name', 'LIKE', '%'. $request->get('q'). '%')
                ->where('merchant_id',$merchant_id)
                ->where('status','active')
                ->take(5)
                ->get();
        }
        return response()->json($data);
    }

    /**
     * getOfferDetails
     */
    function getOfferDetails(Request $request)
    {
        $offer_id = $request->offerId;
        $offer = Offers::query()
            ->where('id',$offer_id)
            ->where('status','active')
            ->first();
        return response()->json($offer);
    }

# ------------------------------------------ Admin panel use ------------------------------------- #
    function getAllOffer(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Offer Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $category_id = $request->category_id;
            $offers = Offers::query()
                ->where(function($q) use ($category_id){
                    if($category_id)
                        $q->where('category_id',$category_id);
                })
                ->orderBy('id','desc')->simplePaginate(20);
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


# ------------------------------------------ Customer panel use ------------------------------------- #
    function getCustomerOfferList(Request $request){
        if( Auth::user()->type=='Customer'){
            $category_id = $request->category_id;
            $offer_code = $request->offer_code;
            $offers = Offers::query()
                ->where(function($q) use ($category_id,$offer_code){
                    if($category_id)
                        $q->where('category_id',$category_id);
                    if($offer_code)
                        $q->where('name',$offer_code);
                })
                ->orderBy('id','desc')->where('status','active')->simplePaginate(30);
            $categories = Categories::query()->get();
            return view('customerPanel.offer.index',compact('offers','categories'));
        }
        else
        {
            return redirect()->route('home');
        }
    }



}
