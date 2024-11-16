<?php

namespace App\Http\Controllers;

use App\Libs\MasterDataService;
use Illuminate\Http\Request;
use App\Libs\TutorialService;
use App\Libs\QuestStartService;
use App\Libs\QuestEndService;
use App\MasterQuest;
use Illuminate\Support\Facades\Log;

class QuestController extends Controller
{   //チュートリアルスタート
    public function Tutorial(Request $request){
        Log::info('QuestController Tutorial($request) :start');
        Log::debug('Request data: ', $request->all());
        $user_id=$request->user_id;

        $user_profile=TutorialService::TutorialStart($user_id);

        $response=array(
            'user_profile'=>$user_profile,
        );
        Log::info('QuestController Tutorial($request) :end');
        return json_encode($response);
    }
    //クエストスタート
    public function Start(Request $request){
        Log::info("QuestController Start($request) :start" );
        Log::debug('Request data: ', $request->all());

        $client_master_version=$request->client_master_version;
        $user_id=$request->user_id;
        $quest_id=$request->quest_id;

        //マスターデータチェック
        if(!MasterDataService::CheckMasterDataVersion($client_master_version)){
            return config('error.ERROR_MASTER_UPDATE');
        }

        $user_quest_list=QuestStartService::QuestStart($user_id,$quest_id);

        $response=array(
            'user_quest_list'=>$user_quest_list,
        );
        Log::info('QuestController start($request) :end');
        return json_encode($response);
    }

    public function End(Request $request){
        Log::info('QuestController End($request) :start');
        Log::debug('Request data: ', $request->all());
        $user_id=$request->user_id;
        $quest_id=$request->quest_id;
        $score=$request->score;
        $clear_time=$request->clear_time;

        //クエストマスターデータを取得
        $master_quest=MasterQuest::GetMasterQuestByQuestID($quest_id);
            if(!$quest_id){
                Log::debug('マスターデータがありません');
                return config('error.ERROR_INVALID_DATA');
            }
 
        $response=QuestEndService::QuestEnd($master_quest,$user_id,$quest_id,$score,$clear_time);

        Log::info('QuestController End($request) :end');
        return json_encode($response);
    }
}
