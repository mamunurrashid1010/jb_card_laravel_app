<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTransactions extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','point'];

    function customerInfo(){
        return $this->belongsTo(User::class,'customer_id','id');
    }

    function offerInfo(){
        return $this->belongsTo(Offers::class,'offer_id','id');
    }

    function userInfo(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
