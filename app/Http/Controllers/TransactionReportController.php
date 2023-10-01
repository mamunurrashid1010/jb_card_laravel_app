<?php

namespace App\Http\Controllers;

use App\Models\OfferTransactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionReportController extends Controller
{
    # ------------------------------------------ Merchant panel use ------------------------------------- #
    function index(Request $request){
        if(Auth::user()->type=='Merchant' || Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->id;
            if(Auth::user()->type=='Agent'){
                $merchant_id = Auth::user()->merchant_id;
            }

            $agents = User::query()->where('merchant_id',$merchant_id)->get();

            $invoice_no = $request->invoice_no;
            $agent_id = $request->agent_id;
            $fromDate=$request->fromDate;
            $toDate=$request->toDate;
            $transactionReport = OfferTransactions::query()
                ->where(function ($q) use ($agent_id,$fromDate,$toDate,$invoice_no){
                    if($invoice_no)
                        $q->where('invoice_no',$invoice_no);
                    if($agent_id)
                        $q->where('user_id',$agent_id);
                    if(!empty($fromDate) && !empty($toDate))
                        $q->whereBetween('created_at',[$fromDate,$toDate]);
                })
                ->where('merchant_id',$merchant_id)
                ->orderBy('id','desc')
                ->simplePaginate(20);
            return view('merchantPanel.report.transaction_report',compact('transactionReport','agents'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    # ------------------------------------------ Customer panel use ------------------------------------- #
    function customerOfferTransactionHistory(Request $request){
        if(Auth::user()->type=='Customer')
        {
            $customer_id = Auth::user()->id;
            $invoice_no = $request->invoice_no;

            $transactionHistory = OfferTransactions::query()
                ->where(function($q) use ($invoice_no){
                    if($invoice_no)
                        $q->where('invoice_no',$invoice_no);
                })
                ->where('customer_id',$customer_id)
                ->orderBy('id','desc')->simplePaginate(30);

            return view('customerPanel.transactionHistory.index',compact('transactionHistory'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

}
