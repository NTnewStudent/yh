<?php

namespace App\Model;

use App\User;
use Cache;
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


    /**
     * 查询用户相关信息
     * @param $uuid 用户id
     * @return array
     */
    public function getUserInfo($uuid){

       $UserInfo =  UserModel::find($uuid);

       if(!$UserInfo -> isEmpty ){
           return ['code' => 1,'msg' => $this->returnArray($UserInfo)];
       }

       return ['code' => 0, 'msg' => '数据为空！'];

    }

    //隐藏字段
    public function returnArray($UserInfo){
        return [
            'id' => $UserInfo -> id,
            'phone' => $UserInfo -> phone,
            'home_phone' => $UserInfo -> home_phone,
            'company_postion' => $UserInfo -> company_postion,
            'contact_name' => $UserInfo -> contact_name,
        ];
    }

    public function UserInfoRedact($all)
    {
        $phone  =  $all['phone'];//
        $home_phone  = $all['home_phone'];
        $company_postion  =  $all['company_postion'];
        $contact_name  =  $all['contact_name'];
        $token = $all['token'];

        $uuid =   Cache::get($token);
        //查询用户修改

        if(empty($home_phone)){

            $state =   UserModel::where(['id' => $uuid]) ->update([
                'phone' => $phone,
                'company_postion' => $company_postion,
                'contact_name' => $contact_name
            ]);

            if($state == 1){
                return ['code' =>1,'msg' => $state];

            }

            return ['code' =>0,'msg' => '修改失败！'];


        }

      $state =   UserModel::where(['id' => $uuid]) ->update([
            'phone' => $phone,
            'home_phone' => $home_phone,
            'company_postion' => $company_postion,
            'contact_name' => $contact_name
        ]);

      if($state == 1){
          return ['code' =>1,'msg' => $state];

      }

        return ['code' =>0,'msg' => '修改失败！'];



    }

//营业执照副本上次
    public function uploadImage($msg,$all)
    {

//        获取token
        $token = $all['token'];

        $cache =  Cache::get($token);

        $uuid = $cache['uuid'];

//        插入数据库
       $user =  UserModel::where(['id' => $uuid]) ->update([
                'isbn_img_url' => $msg
        ]);

        if($user == 1){
            return ['code' => true,'msg' => '保存成功！'];

        }else{
            return ['code' => false,'msg' => '路径保存失败！'];
        }


    }


    /**
     * 获取营业执照副本图片
     * @param $uuid
     * @return array
     */
    public function getUserSocialCodeImage($uuid)
    {

       $data =  UserModel::find($uuid);

       if(!$data -> isEmpty){
           return ['code' => true,'msg' => $data -> isbn_img_url];
       }

       return ['code' => true,'msg' => 'empty'];

    }

}
