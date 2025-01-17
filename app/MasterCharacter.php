<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class MasterCharacter extends Model
{
    protected $table='master_character';
    protected $primaryKey='character_id';

    public static function GetMasterCharacter(){
        $master_data_list=MasterDataService::GetMasterData('master_character');
        return $master_data_list;
    }

    public static function GetMasterCharacterByCharacterID($character_id){
        $master_data_list=self::GetMasterCharacter();
        foreach($master_data_list as $master_data){
            $master_character=new MasterCharacter;
            $master_character->character_id=$master_data['character_id'];
            $master_character->assert_id=$master_data['assert_id'];
            $master_character->character_name=$master_data['character_name'];
            $master_character->rarity=$master_data['rarity'];
            $master_character->type=$master_data['type'];
            $master_character->sell_point=$master_data['sell_point'];
            if($master_characte==$master_character->character_id){
                return $master_character;
            }
        }
        return null;
    }
}