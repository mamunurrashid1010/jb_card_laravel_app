<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LockableTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    use LockableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'type',
        'avatar',
        'status',
        'address',
        'image',
        'storeAccess',
        'password',
        'merchant_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function company(){
        return $this->belongsTo(Companies::class);
    }

    function customerPackage(){
        return $this->hasOne(CustomerPackages::class,'customer_id','id');
    }

    function MerchantInfo(){
        return $this->hasOne(Merchant::class,'user_id','id');
    }

    function merchantPackage(){
        return $this->hasMany(MerchantPackage::class,'merchant_id','id');
    }

    function merchantWiseWallet(){
        return $this->hasMany(OfferTransactions::class,'customer_id','id');
    }

}
