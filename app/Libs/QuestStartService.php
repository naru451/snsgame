<?php
namespace App\Libs;

use App\UserProfile;
use App\MasterQuest;
use App\UserQuest;
use Illuminate\Support\Facades\Log;
use PDOException;

class QuestStartService{
    public static function QuestStart($client_master_version,$user_id,$quest_id){
        Log::info('QuestStart($client_master_version,$user_id,$quest_id) ;start');
        //user_profileテーブルからレコードを取得
        
        $user_profile=UserProfile::GetUserProfile($user_id);

        //レコード存在チェック
        if(!$user_profile){
            Log::debug('user_profileテーブルのレコードがありません');
            return config('error.ERROR_INVALID_DATA');
        }

        //クエストマスターデータを取得
        $master_quest=MasterQuest::GetMasterQuestByQuestID($quest_id);
        if(is_null($master_quest)){
            Log::debug('マスターデータがありません');
            return config('error.ERROR_INVALID_DATA');
        }
        
        //スケジュールチェック
        if(time() <strtotime($master_quest->open_at)){
            return config('error.ERROR_INVALID_SCHDULE');
        }
        if(strtotime($master_quest->close_at >time())){
            return config('error.ERROR_INVALID_SCHDULE');
        }
        
        //user_questテーブルからレコードを取得
        $user_quest=UserQuest::GetUserQuest($user_id,$quest_id);
        
        
        //データの書き込み
        try{
            $user_quest->save();
        }catch(PDOException $e){
            Log::debug('user_questテーブルへの書き込みに失敗しました')
            return config('error.ERROR_DB_UPDATE');
        }

        //クライアントへレスポンス
        $user_quest_list=UserQuest::where('user_id',$user_id)->get();
        
        Log::info('QuestStart($client_master_version,$user_id,$quest_id) :end');
        return $user_quest_list;
    }
}