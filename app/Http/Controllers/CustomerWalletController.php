<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OfferTransactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerWalletController extends Controller
{
    # ------------------------------------------ Customer panel use ------------------------------------- #
    function wallet(Request $request){
        if(Auth::user()->type=='Customer')
        {
            $customer_id = Auth::user()->id;
            //$invoice_no = $request->invoice_no;

            # transactional merchant list
            $merchants = User::query()
                ->whereHas('merchantTransactions',function($q) use ($customer_id){
                    $q->where('customer_id',$customer_id);
                })
                ->where('type','Merchant')
                ->get();

            # total wallet point
            $transactions = OfferTransactions::query()->select('id','customer_id','point','point_status','status')
                ->where('customer_id',$customer_id)
                ->where('status','confirm')
                ->get();
            $point_add = $transactions->where('point_status','add')->sum('point');
            $point_deduction = $transactions->where('point_status','deduction')->sum('point');
            $totalWalletPoint = $point_add - $point_deduction;

            return view('customerPanel.wallet.wallet',compact('merchants','totalWalletPoint'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
