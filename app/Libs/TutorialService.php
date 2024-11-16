<?php
namespace App\Libs;

use App\UserProfile;
use App\MasterQuest;
use App\UserQuest;
use Illuminate\Support\Facades\Log;
use PDOException;

class Tutorialervice{
    public static function TutorialStart($user_id){
                //User_Profileテーブルからレコードを取得
                $user_profile=UserProfile::GetUserProfile($user_id);

                //レコード存在チェック
                if(!$user_profile){
                    Log::debug('User_Profileテーブルなし');
                    return config('error.ERROR_INVALID_DATA');
                }
        
                //チュートリアル進捗の確認
                if(config('constants.TUTORIAL_QUEST')<=$user_profile->tutorial_progress){
                    return config('error.ERROR_INVALID_DATA');
                }
        
                //チュートリアル進捗の更新
                $user_profile->tutorial_progress=config('constants.TUTORIAL_QUEST');
        
                //データの書き込み
                //try{
                    $user_profile->save();
                //}catch(\PDOException $e){
                //    return config('error.ERROR_DB_UPDATE');
                //}
        
                //クライアントへのレスポンス
                $user_profile=UserProfile::GetUserProfile($user_id);

                return $user_profile;
    }


}