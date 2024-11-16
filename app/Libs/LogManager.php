<?php
namespace App\Libs;

class LogManager{
    public function SetAccsessLog($class,$user_id){
        $time=time();
        $date=date('Ymd');
        error_log('$user_id,$class,$time\n',3,dirname(__FILE__).'/accsess.log.$date');
    }
    public function SetTransactionLog($user_id,$reason,$before,$after){
        $time=time();
        $date=date('Ymd');
        $before_json=json_encode($before);
        $after_json=json_encode($after);
        error_log('$user_id,$reason,$time,$before_json,$after_json\n',3,dirname(__FILE__).'/transactionLog.log.$date');
    } 
}