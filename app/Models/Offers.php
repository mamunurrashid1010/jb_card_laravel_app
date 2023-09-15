<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    function category(){
        return $this->belongsTo(Categories::class,'category_id','id');
    }

    function merchant(){
        return $this->belongsTo(User::class,'merchant_id','id');
    }

}
