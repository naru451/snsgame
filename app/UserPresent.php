<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPresent extends Model
{
    protected $table='user_present';
    public $incrementing=true;
    protected $primaryKey='present_id';
    public $timestumps=false;

    public static function GetUserPresent($user_id){
        $user_present=UserPresent::where('user_id',$user_id)->first();

        if(!$user_present){
            return config('error.ERROR_INVALID_DATA');
        }
        return  $user_present;
    }

}
