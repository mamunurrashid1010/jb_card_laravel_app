<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\OfferTransactions;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferTransactionsController extends Controller
{

    function create(Request $request)
    {
        if( Auth::user()->type=='Merchant' || Auth::user()->type=='Agent') {
            $merchant_id = Auth::user()->id;
            if(Auth::user()->type=='Agent'){
                $merchant_id = Auth::user()->merchant_id;
            }

            //$offers = Offers::query()->where('merchant_id',$merchant_id)->orderBy('id','desc')->simplePaginate(20);
            return view('merchantPanel.offerTransaction.create');
        }
        else
        {
            return redirect()->route('home');
        }
    }

    function store(Request $request)
    {
        $request->validate([
            'customer_id'       => 'required',
            'offer_id'          => 'required',
            'invoice_no'        => 'required',
            'amount'            => 'required',
            'point_status'      => 'required',
        ],
            [
                'customer_id.required'   => 'Customer required',
                'offer_id.required'      => 'Offer  required',
                'invoice_no.required'    => 'Invoice_no required',
                'amount.required'        => 'Amount required',
                'point_status.required'  => 'Point status required',
            ]
        );

        # get offer details
        $offer = Offers::query()->find($request->offer_id);

        # get merchant id
        if(Auth::user()->type=='Merchant'){
            $merchant_id = Auth::user()->id;
        }
        elseif(Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->merchant_id;
        }

        # store
        OfferTransactions::query()->insert([
            'customer_id'       => $request->customer_id,
            'offer_id'          => $request->offer_id,
            'merchant_id'       => $merchant_id,
            'user_id'           => Auth::user()->id,
            'invoice_no'        => $request->invoice_no,
            'amount'            => $request->amount,
            'discount'          => $offer->discount,
            'point'             => $offer->point,
            'point_status'      => $request->point_status,
            'details'           => $request->details,
            'status'            => 'confirm',
            'created_at'        => Carbon::now(),
        ]);

        Toastr::success('Transaction Added Successfully','Success');
        return redirect()->back();

    }
}
