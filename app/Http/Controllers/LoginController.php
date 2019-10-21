<?php

namespace App\Http\Controllers;

use App\Model\UserModel;
use Cache;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

/**
 * 登陆注册接口
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{

    /**
     * 用户注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){

//        验证参数是否合法
        $validator = Validator::make($request ->all(),[
            "phone" => 'required|numeric',
            "code" => 'required|numeric',
            "password" => 'required',
        ],[
            "phone.required" => '手机号必填',
            "phone.numeric" => '手机号必需是数字',
            "code.required" => '验证码不能为空',
            "code.numeric" => '验证码只能是数字',
            "password.required" => '密码不能为空',
        ]);

        if($validator -> fails()){
            return response() ->json(['code' => 400,'msg' => $validator -> errors()]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $phone = Input::get('phone');
        $code = Input::get('code');
        $password = Input::get('password');

//        短信验证码是否正确
      $phoneCode =  Cache::get($phone);
      if(!$code == $phoneCode){
          return response() ->json(['code' => 400,'msg' => '验证码错误！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
      }

//        查询数据库用户是否存在
      $user =   UserModel::where(['phone' => $phone]) -> get();

      if(count($user) > 0){//已注册
          return response() ->json(['code' => 200,'msg' => '已经注册过了！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
      }

//          添加用户
      $createUser =   DB::table('yh_user') -> insertGetId([
          'phone' => $phone,
          'password' => $password,
      ]);

      if( $createUser > 0){

//          获取token 发送给前端保存
          $token =   $this -> getToken();
          //添加缓存
          Cache::put($token,[
              'uuid' => $createUser
          ],env('TokenData'));

          return response() ->json(['code' => 200,'msg' => $token]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
      }

        return response() ->json(['code' => 400,'msg' => '注册失败！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }

    /**
     * 验证登陆密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

      $validator =   Validator::make($request->all(),[
          "phone" => 'required|numeric',
          "password" => 'required',
      ],[
          "phone.required" => '手机号必填',
          "phone.numeric" => '手机号必需是数字',
          "password.required" => '密码不能为空',
      ]);

      if($validator ->fails()){
          return response() ->json(['code' => 400,'msg' => $validator -> errors()]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
      }

        $phone = Input::get('phone');
        $password = Input::get('password');

//        查询用户密码是否正确

     $user =   UserModel::where(['phone' => $phone ,'password' => $password]) ->get();

     if(count($user) > 0){
         return response() ->json(['code' => 200,'msg' => '登陆成功！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
     }

        return response() ->json(['code' => 400,'msg' =>'账号或者密码错误！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取token
     */
    public function getToken(){
        return bcrypt(str_random(20));
    }
}
