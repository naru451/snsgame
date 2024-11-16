<?php
namespace App\Libs;

use App\UserProfile;
use App\UserQuest;
use Illuminate\Support\Facades\Log;
use PDOException;
 class QuestEndService{
    public static function QuestEnd($master_quest,$user_id,$quest_id,$score,$clear_time){
        if (!$master_quest) {
             return config('error.ERROR_INVALID_DATA');
        }

        //スケジュールチェック
        if(time()<strtotime($master_quest->open_at)){
            return config('error.ERROR_INVALID_SCHEDULE');
        }
        if(strtotime($master_quest->close_at)<time()){
            return config('error.ERROR_INVALID_SCHEDULE');
        }
        
        //値の懸賞
        if($score<=0){
            return config('error.ERROR_INVALID_DATA');
        }
        if($clear_time<=0){
            return config('error.ERROR_INVALID_DATA');
        }
        
        //user_questテーブルからレコードを取得
        $user_quest=UserQuest::GetUserQuest($user_id,$quest_id);
        if(!$user_quest){
            return config('error.ERROR_INVALID_DATA');
        }
        
        //user_profileテーブルからレコードを取得
        $user_profile=UserProfile::GetUserProfile($user_id);
        //レコード存在チェック
        if(!$user_profile){
            return config('error.ERROR_INVALID_DATA');
        }
        
        //初回クリア報酬
        if($user_quest->status!=config('constants.QUEST_CLEAR')){
            switch ($master_quest->item_type){
                case config('constants.ITEM_TYPE_CRYSTAL'):
                    $user_profile->crystal+=$master_quest->item_count;
                    break;
                case config('constants.ITEM_TYPE_CRYSTAL_FREE'):
                    $user_profile->crystal_free+=$master_quest->item_count;
                    break;
                case config('constants.ITEM_TYPE_FRIEND_COIN'):
                    $user_profile->friend_coin+=$master_quest->item_count;
                    break;
                default:
                    break;
            }
        }
        
        //user_questの更新
        $user_quest->status=config('constants.QUEST_CLEAR');
        $user_quest->score=$score;
        $user_quest->clear_time=$clear_time;
        
        //データの書き込み
        try{
            $user_quest->save();
            $user_profile->save();
        }catch(PDOException $e){
            Log::error('Database update error: ' . $e->getMessage());
            return config('error.ERROR_DB_UPDATE');
        }
                
        //クライアントへレスポンス
        $user_quest_list=UserQuest::where('user_id',$user_id)->get();

        $response=array(
            'user_profile'=>$user_profile,
            'user_quest'=>$user_quest_list,
        );
        return $response;
    }
}
