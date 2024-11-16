<?php
namespace App\Libs;


use App\MasterLoginItem;
use App\MasterQuest;
use App\MasterCharacter;
use App\MasterGacha;
use App\MasterGachaCharacter;
use Aoo\MasterShop;
use App\MasterShop as AppMasterShop;
use Illuminate\Support\Facades\Log;

class MasterDataService{
    public static function GenerateMasterData($version){
        touch(__DIR__  . '/' . $version);
        chmod(__DIR__  . '/' . $version, 0644);

        $master_data_list=array();
        //マスターデータの種類を以下に随時追加
        $master_data_list['master_login_item']=MasterLoginItem::all();
        $master_data_list['master_quest']=MasterQuest::all();
        $master_data_list['master_character']=MasterCharacter::all();
        $master_data_list['master_gacha']=MasterGacha::all();
        $master_data_list['master_gacha_character']=MasterGachaCharacter::all();
        $master_data_list['master_shop']=AppMasterShop::all();

        $json=json_encode($master_data_list);
        file_put_contents(__DIR__ . '/' . $version, $json);
    }
    public static function GetMasterData($data_name){
        Log::debug($data_name);
        
        $file = file_get_contents(__DIR__ . '/' . config('constants.MASTER_DATA_VERSION'));
        $json = json_decode($file, true);

        if (!array_key_exists($data_name, $json)) {
            Log::debug("ファイルがありません");
            return false;
        }

        return $json[$data_name];
    }
    public static function CheckMasterDataVersion($client_master_version){
        return config('constants.MASTER_DATA_VERSION')<=$client_master_version;
    }
    public function GetMasterDataSize(){
        $size= filesize(__DIR__ . '/' . config('constants.Master_DATA_VERSION'));
        $size_bytes=floatval($size);
        return $size_bytes;
    }
    
}


