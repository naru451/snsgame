<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table='user_login';
    public $incrementing=false;
    protected $primaryKey='user_id';
    public $timestamps=false;

    public  static function GetUserLogin($user_id){
        $user_login=UserLogin::where('user_id',$user_id)->first();
        if(!$user_login){
            //初期値の設定
            $user_login=new UserLogin();
            $user_login->user_id=$user_id;
            $user_login->login_day=0;
            $last_login_at=date('Y-m-d H:i:s',mktime(0,0,1,1,2000));

            $user_login->last_login_at= $last_login_at;
        }
        return $user_login;
    }
}
