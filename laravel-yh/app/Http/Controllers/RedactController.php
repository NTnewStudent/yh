<?php

namespace App\Http\Controllers;

use App\Model\UserModel;
use Illuminate\Http\Request;
use Validator;

class RedactController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function UserInfoRedact(Request $request){

       $Validator =  Validator::make($request ->all(),[
            'phone' => 'required|numeric',
            'home_phone' => 'numeric',
            'company_postion' => 'required',
            'contact_name' => 'required',
       ],[
            'phone.required' => '电话不能为空！',
            'phone.numeric' => '电话必须是数字',
            'home_phone.numeric' => '座机必须是数字',
            'company_postion.required' => '公司地址不能为空哦！',
            'contact_name.required' => '姓名不能为空哦！',
       ]);

       if($Validator ->fails()){
           return response() ->json(['code' => 400,'msg' => $Validator ->errors()]) -> setEncodingOptions(JSON_UNESCAPED_UNICODE);
       }

       //插入数据库
       $data =  (new UserModel()) ->UserInfoRedact($request -> all());

       if($data['code'] == 1){
           return response() ->json(['code' => 200,'msg' =>$data['msg']]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
       }else{
           return response() ->json(['code' => 400,'msg' =>$data['msg']]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
       }

    }
}
