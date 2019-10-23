<?php

namespace App\Http\Controllers;

use App\Model\CategoryModel;
use App\Model\UserModel ;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Storage;
use Validator;

class UserController extends Controller
{
    /**
     * 保存商家入驻信息
     * @param Request $request
     * @return mixed
     */
    public function saveUser(Request $request){

//        验证

       $validator  =  Validator::make($request -> all(),[
            'contact_name' => 'required',
            'phone' => 'required',
            'home_phone' => 'required',
            'company_name' => 'required',
            'company_postion' => 'required',
            'category_name' => 'required',
            'category_id' => 'required',
            'jin' => 'required',
            'wei' => 'required',
       ],[
            'contact_name.required' => '不能为空',
            'phone.required' => '不能为空',
            'home_phone.required' => '不能为空',
            'company_name.required' => '不能为空',
            'company_postion.required' => '不能为空',
            'category_name.required' => '不能为空',
            'category_id.required' => '不能为空',
            'jin.required' => '不能为空',
            'wei.required' => '不能为空',
       ]);

       if($validator ->fails() ){
            return response() ->json(['code' => 401,'msg' => $validator ->errors()]) -> setEncodingOptions(JSON_UNESCAPED_UNICODE);
       }

        //        接收前端参数并验证
        $contact_name = Input::get('contact_name');
        $phone = Input::get('phone');
        $home_phone = Input::get('home_phone');
        $company_name = Input::get('company_name');
        $company_postion = Input::get('company_postion');
        $category_name= Input::get('category_name');
        $category_id = Input::get('category_id');
        $jin = Input::get('jin');
        $wei = Input::get('wei');

//        插入数据库并返回状态

       $user =  UserModel::create([
            'contact_name' => $contact_name,
            'phone' => $phone,
            'home_phone' => $home_phone,
            'company_name' => $company_name,
            'company_postion' => $company_postion,
            'category_name' => $category_name,
            'category_id' => $category_id,
            'postion_jin' => $jin,
            'postion_wei' => $wei,
            'CREATED_TIME' => date('Y-m-d H:i:s')
        ]);



        if($user){
            return response() -> json(['code' => 200,'msg' => "添加成功！"]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }else{
            return response() -> json(['code' => 401,'msg' => "添加失败！"]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }


    }



    /**
     *
     * 获取供货商列表
     * @example {
     * {
            "code": 200,
            "msg": "empty",
            "data": []
        }
     * @api getUserList
     */

    public function getUserList(Request $request){
        $size = 4 ;
        $list = UserModel::paginate($size);
      
        if($list){
            return response() -> json(['code' => 200,'msg' => "success！",'data'=>$list]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }else{
            return response() -> json(['code' => 404,'msg' => "empty",'data'=>[]]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     *
     * 获取供应商详细
     * @param id
     *
     */
    public function getUserDetailById(){

    }



    /**
     * 用户登录
     * @param mobile,pwd
     *
     */
    public function UserLogin(Request $request){
    
    $validator = Validator::make($request->all(),
            [
            'mobile' => 'required|unique:posts|max:12',
            'pwd' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.'
            ])->validate();

        if($validator ->fails() ){
            return response() ->json(['code' => 401,'msg' => $validator ->errors()]) -> setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $User = \DB::table('yh_user')->where(['phone'=>Input::get('mobile'),'pwd'=>Input::get('pwd')]);
       
        $request->session()->put('is_login',1);

        return $request->session()->get('is_login');


    }

    /**
     * 显示用户信息
     *
     */
        public function getUserInfo(){

//            通过缓存查询用户id
                $toke = Input::get('token');
                $cache = Cache::get($toke);
                //uuid
                $uuid = $cache['uuid'];

//            通过用户id查询相关内容
           $data =  (new UserModel) ->getUserInfo ($uuid);
           if($data['code'] == 1){
               return response() ->json(['code' => 200,'msg' => $data['msg'] ]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
           }else{
               return response() ->json(['code' => 400,'msg' => '查询数据失败']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
           }



//            返回数据

        }


    /**
     * 获取营业执照副本图片
     */
        public function getUserSocialCodeImage(){

            $token = Input::get('token');

            $cache = Cache::get($token);

            $uuid = $cache['uuid'];

//            查询数据库
            if( $data = (new UserModel()) ->getUserSocialCodeImage($uuid) ){
                return response() ->json(['code' => 200,'msg' => $_SERVER['SERVER_NAME'].$data['msg'] ]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

            }

            return response() ->json(['code' => 400,'msg' => '查询图片失败！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
//            return $_SERVER['SERVER_NAME'];

        }

}
