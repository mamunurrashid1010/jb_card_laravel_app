<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permissions extends Model
{
    use HasFactory;

    function userPermissions(){
        return $this->hasOne(user_permissions::class,'permission_id','id');
    }

    public function nameInfo(){
        return $this->belongsTo(permissions::class,'parent_id','id')->select('name');
    }
}
