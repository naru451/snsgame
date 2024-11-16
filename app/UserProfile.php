<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserProfile extends Model
{
    protected $table='user_profile';
    public $incrementing=false;
    protected $primaryKey='user_id';
    public $timestamps=false;


    protected $fillable = [
        'user_id', 'user_name', 'crystal', 'crystal_free', 'friend_coin', 'tutorial_progress'
    ];

    public static function GetUserProfile($user_id){
        Log::info('GetUserProfile($user_id) :start');
        $user_profile=UserProfile::where('user_id',$user_id)->first();

        if(!$user_profile){
            Log::debug('user_profileテーブルにレコードがありませんでした');
            return config('error.ERROR_INVALID_DATA');
        }

        Log::info('GetUserProfile($user_id) :end');
        return $user_profile;

    }
}

