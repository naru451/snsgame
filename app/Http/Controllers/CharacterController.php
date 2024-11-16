<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Libs\MasterDataService;
use App\MasterCharacter;
use App\UserProfile;
use App\UserCharacter;

class CharacterController extends Controller
{
    public function GetCharacterList(Request $request){
        Log::info('CharacterController::GetCharacterList($request) :start');
        Log::debug('Request data: ', $request->all());
        $client_master_version=$request->client_master_version;
        $user_id=$request->user_id;

        //マスターデータチェック
        if(!MasterDataService::CheckMasterDataVersion($client_master_version)){
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }
            
        //user_profileテーブルのレコードを取得
        $user_profile=UserProfile::GetUserProfile($user_id);
        //レコード存在チェック
        if(!$user_profile){
            return config('error.ERROR_INVALID_DATA');
        }

        //user_characterテーブルのレコードを取得
        $user_character_list=UserCharacter::GetCharacter($user_id);
        //レコード存在チェック
        if(!$user_character_list){
            return config('error.ERROR_INVALID_DATA');
        }
        
        //クライアントへのレスポンス
        $response=array(
            'user_character'=>$user_character_list,
        );
        Log::debug(json_encode($response, JSON_PRETTY_PRINT));
        Log::info('CharacterController::GetCharacterList($request) :end');
        return json_encode($response);
    }
    public function SellCharacter(Request $request){
        $user_id=$request->user_id;
        $client_master_version=$request->client_master_version;
        $id=$request->id;

        //マスターデータチェック
        if(!MasterDataService::CheckMasterDataVersion($client_master_version)){
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }

        //user_Profileのレコードを取得
        $user_profile=UserProfile::GetUserProfile($user_id);

        //レコード存在チェック
        if(!$user_profile){
            return config('error.ERROR_INVALID_DATA');
            return;
        }

        //user_characterテーブルからレコードを取得
        $user_character=UserCharacter::where('user_id',$user_id)->where('id',$id)->first();

        //キャラクターが存在するかチェック
        if(!$user_character){
            return config('error.ERROR_INVALID_DATA');
        }

        $master_character=MasterCharacter::GetMasterCharacterByCharacterID($user_character->character_id);

        //マスターデータ存在チェック
        if(!$master_character){
            return config('error.ERROR_INVALID_DATA');
        }
        
        $user_profile->friend_coin+=$master_character->sell_point;

        //データの書き込み
        try{
            $user_profile->save();
            $user_character->delete();
        }catch(\PDOException $e){
            config('error.ERROR_DB_UPDATE');
        }

        //クライアントへのレスポンス
        $user_profile=UserProfile::GetUserProfile($user_id);
        $user_character_list=UserCharacter::GetCharacter($user_id);

        $response=array(
            'user_profile'=>$user_profile,
            'user_character_list'=>$user_character_list,
        );

        return json_encode($response);
    }
}
