<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\UserProfile;
use App\MasterShop;

class ShopController extends Controller
{
    public function BuyItem(Request $request){
        Log::info('Registration($request) start');
        Log::debug('Request data: ', $request->all());
        $user_id=$request->user_id;
        $shop_id=$request->shop_id;

        $user_profile=UserProfile::GetUserProfile($user_id);

        $master_shop=MasterShop::GetMasterShopByShopID($shop_id);
        //マスターデータ存在チェック
        if(is_null($master_shop)){
            return config('error.ERROR_INVALID_DARA');
        }

        $user_profile->crystal+=$master_shop->amount;

        //データの書き込み
        //try{
            $user_profile->save();
        //}catch(\PDOException $e){
          //  return config("error.ERROR_DB_UPDATE");
        //}

        //クライアントへのレスポンス
        $user_profile=UserProfile::GetUserProfile($user_id);
        $response=array(
            'user_profile'=>$user_profile,
        );

        return json_encode($response);

}

}