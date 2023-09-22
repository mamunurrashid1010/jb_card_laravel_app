<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPackages extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','package_id','status'];

    function packageInfo(){
        return $this->belongsTo(Packages::class,'package_id','id');
    }
}
