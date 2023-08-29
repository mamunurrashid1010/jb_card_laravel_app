<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_permissions extends Model
{
    use HasFactory;


    function permissions(){
        return $this->belongsTo(permissions::class,'permission_id','id');
    }
}
