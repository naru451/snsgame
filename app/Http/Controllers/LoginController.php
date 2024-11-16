<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\MasterLoginItem;
use App\UserProfile;
use App\UserLogin;
use App\UserPresent;
use PSpell\Config;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{    
    public function Login(Request $request){
        Log::info('Login($request) start');
        Log::debug('Request data: ', $request->all());
        $client_master_version=$request->client_master_version;
        $user_id=$request->user_id;

        //マスターデータチェック
        if (!MasterDataService::CheckMasterDataVersion($client_master_version)){
            
            return config("constants.ERROR_MASTER_DATA");
           
        }

        //user_profileテーブルのレコードを取得
        $user_profile=UserProfile::GetUserProfile($user_id);   

        //ログインボーナステーブルのレコードを取得
        $user_login=UserLogin::GetUserLogin($user_id);  

        //日付の比較
        $today=date('Y-m-d');
        $last_login_day=date('Y-m-d',strtotime($user_login->last_login_at));
        
        $user_present=new UserPresent;
        //if(strtotime($today)!==strtotime($last_login_day)){
            $user_login->login_day+=1;
            $master_login_item=MasterLoginItem::GetMasterLoginItemByLoginDay($user_login->login_day);
            Log::debug($master_login_item);
            //アイテムデータがあるか確認
            if(!is_null($master_login_item)){
                Log::debug("49行");
            //    //アイテム付与
            //    switch($master_login_item){
            //        case config('constants.ITEM_TYPE_CRYSTAL'):
            //            $user_profile->crystal+=$master_login_item->item_count;
            //            break;
            //        case config('constants.ITEM_TYPE_FREE_CRYSTAL'):
            //            $user_profile->crystal_free+=$master_login_item->item_count;
            //            break;
            //        case config('constants.ITEM_TYPE_FRIEND_COIN');
            //            $user_profile->firend_coin+=$master_login_item->item_count;
            //            break;
            //        default:
            //            break;
            //    }

            //プレゼント作成
            $user_present->user_id=$user_id;
            $user_present->item_type=$master_login_item->item_type;
            $user_present->item_count=$master_login_item->item_count;
            $user_present->description='Loginbonus';
            //30日後まで受け取りOK
            $user_present->limited_at=date('Y-m-d',(time()+(60*60*24*30)));
            }
       // }
        //ログイン時刻の更新
        $user_login->last_login_at=date("Y-m-d ,H:i:s");

        //データの書き込み
        //try{
            //$user_profile->save();
            $user_login->save();
            //if(isset($user_present->user_id)){
                Log::Debug("user_presentテーブルに書き込みします");
                $user_present->save();
            //}
       // }catch(\PDOException $e){
            Log::debug(' Login($request) 73行 {書き込み失敗');
      //  }
         
        //user_presentテーブルからレコードを取得
        $user_present_list=UserPresent::where('user_id',$user_id)->get();

        //クライアントへのレスポンス
        //$user_profile=UserProfile::GetUserProfile($user_id); 
        //$use_login=UserProfile::GetUserProfile($user_id);

        $response=array(
            //"user_profile"=>$user_profile,
            "user_login"=>$user_login,
            "user_profile"=>$user_present_list,
        );
        Log::info('Login($request) end');
        return json_encode($response);
    }  
}

