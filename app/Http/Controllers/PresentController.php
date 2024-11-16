<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\UserProfile;
use App\UserPresent;

class PresentController extends Controller
{
    public function GetPresentList(Request $request)
    {
        $client_master_version = $request->client_master_version;
        $user_id = $request->user_id;

        // マスターデータのチェック
        if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }

        // UserProfileテーブルからレコードを取得
        UserProfile::GetUserProfile($user_id);

        // UserPresentテーブルからレコードを取得
        $user_present_list = UserPresent::where('user_id', $user_id)->get();

        // クライアントへのレスポンス
        $response = array(
            'user_present' => $user_present_list,
        );
        return json_encode($response);
    }

    public function GetItem(Request $request)
    {
        $client_master_version = $request->client_master_version;
        $user_id = $request->user_id;
        $present_id = $request->present_id;

        // マスターデータチェック
        if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }

        // user_profileテーブルのレコードを取得
        $user_profile = UserProfile::GetUserProfile($user_id);

        // user_presentテーブルのレコードを取得
        $user_present = UserPresent::where('user_id', $user_id)->first();

        // 受け取り期限チェック
        if (strtotime($user_present->limited_at) < time()) {
            return config("error.ERROR_INVALID_SCHEDULE");
        }

        // プレゼントからアイテム付与
        switch ($user_present->item_type) {
            case config('constants.ITEM_TIME_CRYSTAL'):
                $user_profile->crystal += $user_present->item_count;
                break;
            case config('constants.ITEM_TIME_CRYSTAL_FREE'):
                $user_profile->crystal_free += $user_present->item_count;
                break;
            case config('constants.FRIEND_COIN'):
                $user_profile->friend_coin += $user_present->item_count; // 修正点: 'riend_coinf' -> 'friend_coin'
                break;
            default:
                break;
        }

        // データの書き込み
        try {
            $user_profile->save();
            // $user_present->delete();
        } catch (\PDOException $e) {
            return config('error.ERROR_DB_UPDATE');
        }

        // クライアントへのレスポンス
        $user_profile = UserProfile::GetUserProfile($user_id);
        $user_present = UserPresent::where('user_id', $user_id)->get();

        $response = array(
            'user_profile' => $user_profile, // 修正点: 'user_profiile' -> 'user_profile'
            'user_present' => $user_present
        );
        return json_encode($response);
    }
}
