<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    function userInfo(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
