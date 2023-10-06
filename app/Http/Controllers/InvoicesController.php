<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
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
            $invoice_no = $request->invoice_no;
            $user_type = $request->user_type;
            $fromDate=$request->fromDate;
            $toDate=$request->toDate;
            $invoices = Invoices::query()
                ->where(function ($q) use ($invoice_no,$user_type,$fromDate,$toDate){
                    if($invoice_no)
                        $q->where('invoice_no',$invoice_no);
                    if($user_type)
                        $q->where('user_type',$user_type);
                    if(!empty($fromDate) && !empty($toDate))
                        $q->whereBetween('invoice_date',[$fromDate,$toDate]);
                })
                ->orderBy('id','desc')
                ->simplePaginate(20);
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

    /**
     * store
     */
    function store(Request $request){
        $request->validate([
            'user_search'   => 'required',
            'invoice_date'  => 'required',
            'amount'        => 'required',
            'status'        => 'required',
        ],
            [
                'user_search.required'  => 'user required',
                'invoice_date.required' => 'invoice_date required',
                'amount.required'       => 'Amount required',
                'status.required'       => 'status required',
            ]
        );

        $user = User::query()->find($request->user_search);
        $invoice_no = $this->uniqueRandomNumberGenerate();

        Invoices::query()->insert([
            'user_id'           => $user->id,
            'user_type'         => $user->type,
            'invoice_no'        => $invoice_no,
            'invoice_date'      => $request->invoice_date,
            'amount'            => $request->amount,
            'status'            => $request->status,
            'description'       => $request->description,
            'created_at'        => Carbon::now(),
        ]);

        Toastr::success('Invoice Added Successfully','Success');
        return redirect()->back();
    }

    /**
     * update
     */
    function update(Request $request){
        $request->validate([
            'id'            => 'required',
            'invoice_date'  => 'required',
            'amount'        => 'required',
            'status'        => 'required',
        ],
            [
                'invoice_date.required' => 'invoice_date required',
                'amount.required'       => 'Amount required',
                'status.required'       => 'status required',
            ]
        );

        $data=[
            'invoice_date'      => $request->invoice_date,
            'amount'            => $request->amount,
            'status'            => $request->status,
            'description'       => $request->description,
            'updated_at'        => Carbon::now(),
        ];

        # change user
        if($request->user_search){
            $user = User::query()->find($request->user_search);
            $data['user_id'] = $user->id;
            $data['user_type'] = $user->type;
        }


        Invoices::query()->where('id',$request->id)->update($data);

        Toastr::success('Data Updated Successfully','Success');
        return redirect()->back();
    }

    /**
     * printInvoice
     */
    function printInvoice(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Invoice Manage');
        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow'){
            $invoice_id = $request->invoice_id;

            $invoice = Invoices::query()->findOrFail($invoice_id);
            return view('invoice.printInvoice',compact('invoice'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    function uniqueRandomNumberGenerate(){
        $randomNumber = rand(1,10000000);
        $exist = Invoices::query()->where('invoice_no',$randomNumber)->count();
        if($exist){
            $this->uniqueRandomNumberGenerate();
        }
        return $randomNumber;
    }
}
