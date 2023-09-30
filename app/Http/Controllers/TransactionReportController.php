<?php

namespace App\Http\Controllers;

use App\Models\OfferTransactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionReportController extends Controller
{
    function index(Request $request){
        if(Auth::user()->type=='Merchant' || Auth::user()->type=='Agent'){
            $merchant_id = Auth::user()->id;
            if(Auth::user()->type=='Agent'){
                $merchant_id = Auth::user()->merchant_id;
            }

            $agents = User::query()->where('merchant_id',$merchant_id)->get();

            $agent_id = $request->agent_id;
            $fromDate=$request->fromDate;
            $toDate=$request->toDate;
            $transactionReport = OfferTransactions::query()
                ->where(function ($q) use ($agent_id,$fromDate,$toDate){
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
}
