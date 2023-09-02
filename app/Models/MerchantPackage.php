<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantPackage extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id','package_id'];

    function packageName(){
        return $this->belongsTo(Packages::class,'package_id','id');
    }
}
