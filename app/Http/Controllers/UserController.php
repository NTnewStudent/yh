<?php

namespace App\Http\Controllers;

use App\Model\CategoryModel;
use App\Model\UserModel ;
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
     * 文件上传
     * @param $request
     * @param $src
     * @return bool|string
     */
    public function uploadFile($request,$src) {
        $file = $request->file($src);
        // 此时 $this->upload如果成功就返回文件名不成功返回false
        $fileName = $this->upload($file);
        if ($fileName){
            return $fileName;
        }
        return '上传失败';
    }

    /**
     * 验证文件是否合法
     */
    public function upload($file, $disk='public') {
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['png', 'jpg', 'gif'])) {
            return false;
        }

        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            return false;
        }

        // 4.是否是通过http请求表单提交的文件
        if (! is_uploaded_file($tmpFile)) {
            return false;
        }

        // 5.每天一个文件夹,分开存储, 生成一个随机文件名
        $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileExtension;
        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
            return Storage::url($fileName);
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

}
