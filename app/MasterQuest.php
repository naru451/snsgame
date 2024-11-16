<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;
use Illuminate\Support\Facades\Log;

class MasterQuest extends Model
{
    protected $table='master_quest';
    protected $primaryKey='quest_id';

    public static function GetMasterQuest(){
        $master_data_list=MasterDataService::GetMasterData('master_quest');
        Log::debug($master_data_list);
        return $master_data_list;
    }

    public static function GetMasterQuestByQuestID($quest_id){
        $master_data_list=self::GetMasterQuest();
        foreach($master_data_list as $master_data){
            $master_quest=New MasterQuest();
            $master_quest->quest_id=$master_data['quest_id'];
            $master_quest->quest_name=$master_data['quest_name'];
            $master_quest->open_at=$master_data['open_at'];
            $master_quest->close_at=$master_data['close_at'];
            $master_quest->item_type=$master_data['item_type'];
            $master_quest->ite_count=$master_data['item_count'];
            if($quest_id==$master_quest->quest_id){
                return $master_quest;
            }
        }
    }
}
