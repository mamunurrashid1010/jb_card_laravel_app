<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offers;
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
}
