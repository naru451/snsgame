<?php

namespace App;
use App\Libs\MasterDataService;
use Illuminate\Database\Eloquent\Model;

class MasterShop extends Model
{
    protected $table='master_shop';
    protected $primaryKey='shop_id';
    public $incrementing=false;

    public static function GetMasterShop(){
        $master_data_list=MasterDataService::GetMasterData('master_shop');
        return $master_data_list;
    }

    public static function GetMasterShopByShopID($shop_id){
        $master_data_list=self::GetMasterShop();
        foreach($master_data_list as $master_data){
            $master_shop=new MasterShop;
            $master_shop->shop_id=$master_data['shop_id'];
            $master_shop->cost=$master_data['cost'];
            $master_shop->amount['amount'];
            if($shop_id==$master_shop->shop_id){
                return $master_shop;
            }
        }
    }
}
