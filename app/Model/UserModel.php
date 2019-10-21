<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = "yh_user";
    public  $timestamps = false;
    protected  $guarded = [];
    protected $hidden = ['UPDATED_TIME'];
    protected $size = 10 ;

    /**
     *
     * 获取用户通过page
     */
    public static function getUserByPage($page){

        return UserModel::query()->paginate($page);

    }
}
