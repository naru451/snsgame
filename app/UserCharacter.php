<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCharacter extends Model
{
    protected $table='user_character';
    public $incrementing=true;
    protected $primarykey='id';
    public $timestamps=false;

    public static function GetCharacter($user_id){
        $user_character_list=UserCharacter::where('user_id',$user_id)->get();
        return $user_character_list;
    }
}
