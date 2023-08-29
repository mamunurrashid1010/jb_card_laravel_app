<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buses;
use App\Models\Products;
use App\Models\ProductStatus;
use App\Models\Students;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    /**
     * index
     */
    function index(Request $request){
        # get have module access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Products');
        if( Auth::user()->type=='Admin' || Auth::user()->type=='DeliveryAgent' || $returnAccessStatus=='allow'){

            $invoice_no = $request->invoice_no;
            $status = $request->status;
            $products = Products::query()
                ->where(function ($q) use ($invoice_no,$status){
                    if($invoice_no)
                        $q->where('invoice_no',$invoice_no);
                    if($status)
                        $q->where('status',$status);
                })
                ->orderBy('id','desc')
                ->simplePaginate(20);
            return view('product.index',compact('products'));

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
            'date'          => 'required',
            'invoice_no'    => 'required',
            'status'        => 'required',
        ],
            [
                'date.required'         => 'Date required',
                'invoice_no.required'   => 'invoice_no required',
                'status.required'       => 'Status required',
            ]
        );

        $user_id = Auth::user()->id;

        DB::beginTransaction();
        try {
            if (!empty($request->image))
            {
                $image = time().'.'.$request->image->extension();
                $request->image->move(public_path('assets/product'), $image);
            }
            else
                $image='';

            $product_id = Products::query()->insertGetId([
                'date'              => $request->date,
                'invoice_no'        => $request->invoice_no,
                'booking_branch'    => $request->booking_branch,
                'destination_branch'=> $request->destination_branch,
                'service_type'      => $request->service_type,
                'delivery_mode'     => $request->delivery_mode,
                'sender_name'       => $request->sender_name,
                'sender_phone'      => $request->sender_phone,
                'sender_address'    => $request->sender_address,
                'receiver_name'     => $request->receiver_name,
                'receiver_phone'    => $request->receiver_phone,
                'receiver_address'  => $request->receiver_address,
                'product_description'=> $request->product_description,
                'quantity'          => $request->quantity,
                'unit'              => $request->unit,
                //'word'              => $request->word,
                'image'             => $image,
                'status'            => $request->status,
                'created_at'        => Carbon::now(),
            ]);

            ProductStatus::query()->insert([
                'user_id'      => $user_id,
                'product_id'   => $product_id,
                'status'       => $request->status,
                'created_at'   => Carbon::now(),
            ]);

            DB::commit();
            Toastr::success('Data Added Successfully','Success');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Fail!','Error');
            return redirect()->back();
        }

    }

    /**
     * update
     */
    function update(Request $request){
        $request->validate([
            'id'            => 'required'
        ],
            [
                'id.required'           => 'Id required',
            ]
        );

        # received image
        $productOldImage=Products::query()->select('image')->where('id',$request->id)->first();
        if (!empty($request->image))
        {
            $image = time().'.'.$request->image->extension();
            $request->image->move(public_path('assets/product'), $image);
            #delete old image
            if (File::exists(public_path('assets/product'.$productOldImage->image))) {
                File::delete(public_path('assets/product'.$productOldImage->image));
            }
        }
        else
            $image=$productOldImage->image;


        # delivered image
        if (!empty($request->delivered_image))
        {
            $d_productOldImage=ProductStatus::query()->where('product_id',$request->id)->where('status','Delivered')->first();
            if($d_productOldImage){
                $delivered_image = time().'.'. '_d' .$request->delivered_image->extension();
                $request->delivered_image->move(public_path('assets/product'), $delivered_image);
                #delete old image
                if (File::exists(public_path('assets/product'.$d_productOldImage->image))) {
                    File::delete(public_path('assets/product'.$d_productOldImage->image));
                }
                $d_productOldImage->update(['image'=>$delivered_image]);
            }
        }

        $data=[
            'date'              => $request->date,
            'invoice_no'        => $request->invoice_no,
            'booking_branch'    => $request->booking_branch,
            'destination_branch'=> $request->destination_branch,
            'service_type'      => $request->service_type,
            'delivery_mode'     => $request->delivery_mode,
            'sender_name'       => $request->sender_name,
            'sender_phone'      => $request->sender_phone,
            'sender_address'    => $request->sender_address,
            'receiver_name'     => $request->receiver_name,
            'receiver_phone'    => $request->receiver_phone,
            'receiver_address'  => $request->receiver_address,
            'product_description'=> $request->product_description,
            'quantity'          => $request->quantity,
            'unit'              => $request->unit,
            //'word'              => $request->word,
            'image'             => $image,
            //'status'            => $request->status,
            'updated_at'    => Carbon::now()
        ];
        $result=Products::query()->where('id',$request->id)->update($data);
        if ($result){
            Toastr::success('Data Updated Successfully','Success');
            return redirect()->back();
        }
        else
        {
            Toastr::error('Fail!','Error');
            return redirect()->back();
        }
    }

    /**
     * statusUpdate
     */
    function statusUpdate(Request $request){
        $request->validate([
            'id'       => 'required',
            'status'   => 'required'
        ],
            [
                'id.required'       => 'Id required',
                'status.required'   => 'Status required'
            ]
        );

        $user_id = Auth::user()->id;

        if (!empty($request->image))
        {
            $image = time().'.'.$request->image->extension();
            $request->image->move(public_path('assets/product'), $image);
        }
        else
            $image='';

        DB::beginTransaction();
        try {

            Products::query()->where('id',$request->id)->update(['status'=>$request->status, 'updated_at'=> Carbon::now()]);

            ProductStatus::query()->insert([
                'user_id'      => $user_id,
                'product_id'   => $request->id,
                'status'       => $request->status,
                'image'        => $image,
                'created_at'   => Carbon::now(),
            ]);

            DB::commit();
            Toastr::success('Status Updated Successfully','Success');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Fail!','Error');
            return redirect()->back();
        }
    }

    /**
     * invoice
     */
    function invoice(Request $request){
        $product = Products::query()->find($request->product_id);
        $productStatus = ProductStatus::query()->where('product_id',$request->product_id)->get();

        return view('product.invoice',compact('product','productStatus'));
    }

    /**
     * label
     */
    function label(Request $request){
        $product = Products::query()->find($request->product_id);
        return view('product.label',compact('product'));
    }

    /**
     * delete
     */
    function delete(Request $request){
        $product = Products::query()->find($request->id);
        $product->delete();
        ProductStatus::query()->where('product_id',$request->id)->delete();

        Toastr::success('Delete Successfully','Success');
        return redirect()->back();
    }

}
