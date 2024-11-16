<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    protected $table='user_quest';
    public $incrementing=false;
    protected $primaryKey='user_id';
    public $timestamps=false;

    public static function GetUserQuest($user_id,$quest_id){
        $user_quest=UserQuest::where('user_id',$user_id)->where('quest_id',$quest_id)->first();
        if(!$user_quest){
            $user_quest=new UserQuest;
            $user_quest->user_id=$user_id;
            $user_quest->quest_id=$quest_id;
            $user_quest->status=config('constants.QUEST_START');
        }
    return $user_quest;
    }
}