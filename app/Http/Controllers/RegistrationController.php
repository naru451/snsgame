<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserProfile;
use App\Libs\LogManager;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function Registration(Request $request){
        Log::info('Registration($request) start');
        //アクセスログ
        LogManager::SetAccsessLog(get_class($this),$user_id);
        //ユーザIDの実装
        $user_id=uniqid();

        //初期データの設定
        $user_profile=new UserProfile;
        $user_profile->user_id=$user_id;
        $user_profile->user_name='user_name';
        $user_profile->crystal=$crystal=config('constants.CRYSTAL_DEFAULT');
        $user_profile->crystal_free=config('constants.CRYSTAL_FREE_DEFAULT');
        $user_profile->friend_coin=config('constants.FRIEND_COIN_DEFAULT');
        $user_profile->tutorial_progress=config('constants.TUTORIAL_START');

        //データの書き込み
        try{
            $user_profile->save();
        }catch(\PDOException $e){
             Log::info('Registration($request) Sql失敗27行目');
            return config('error.ERROR_DB_UPDATE');
        }

        //クライアントのレスポンス
        $user_profile=UserProfile::GetUserProfile($user_id);  
        
        $befor=array(
            'user_id'=>$user_profile->user_id,
            'crystal'=>0,
            'crystal_free'=>$user_profile->crystal_flee,
        );

        $after=array(
            'user_id'=>$user_profile->user_id,
            'crystal'=>0,
            'crystal_free'=>$user_profile->crystal_flee,
        );

        //トランザクションログ
        LogManger::SetTransactionLog($user_id,'Registration',$before,$after);

        $response=array(
            "user_profile"=>$user_profile,
        );
        Log::info('Registration($request) End');
        return json_encode($response);
    }
}