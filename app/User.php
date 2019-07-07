<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

//    users types

    const ADMIN_TYPE = 'admin';
    const USER_TYPE = 'user';

    const TYPES = [self::USER_TYPE,self::ADMIN_TYPE];

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type' , 'email', 'mobile' , 'password' , 'avatar' , 'website' , 'verify_code' ,'verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'verify_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];


    /**
     * find user with email or phonenumber
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        $user = static::where('email' , $username)->orWhere('mobile' , $username)->first();
        return $user;
    }

    public function setMobileAttribute($value)
    {
        $mobile = '+98' . substr($value , -10 , 10);
        $this->attributes['mobile'] = $mobile;
    }
}
