<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterLoginItem;
use App\MasterQuest;
use App\MasterCharacter;
use App\MasterGacha;
use App\MasterShop;
use Illuminate\Support\Facades\Log;

class MasterDataController extends Controller
{
    public function Get(Request $request){
        Log::info("MasterDataController::Get(request) :start");
        //クライアントに送信したいマスターデータだけを選択
        $master_login_item=MasterLoginItem::GetMasterLoginItem();
        $master_quest=MasterQuest::GetMasterQuest();
        $master_character=MasterCharacter::GetMasterCharacter();
        $master_gacha=MasterGacha::GetMasterGacha();
        $master_shop=MasterShop::GetMasterShop();

        $response=array(
            'master_data_version'=>config('constants.MASTER_DATA_VERSION'),
            'master_login_item'=>$master_login_item,
            'master_quest'=>$master_quest,
            'master_character'=>$master_character,
            'master_gacha'=>$master_gacha,
            'master_shop'=>$master_shop,
        );
        
        Log::info("MasterDataController::Get(request) :end");
        Log::debug(json_encode($response, JSON_PRETTY_PRINT));
        return json_encode($response);
    }
}
