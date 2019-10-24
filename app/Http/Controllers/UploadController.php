<?php

namespace App\Http\Controllers;

use App\Model\UserModel;
use Illuminate\Http\Request;
use Storage;

class UploadController extends Controller
{

    /**
     * 营业执照副本上次
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function uploadImage(Request $request){

     $data =  $this->uploadFile($request,'file');

        if($data['code']){

//            将路径插入数据库
          if( $result =  (new UserModel()) ->uploadImage($data['msg'],$request ->all()) ){
              return response() ->json(['code' => 200,'msg' => $result['msg']]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

          }  else{
              return response() ->json(['code' => 400,'msg' => $result['msg']]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

          }

        }else{
            return response() ->json(['code' => 400,'msg' => '上传文件失败！']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

    }

    /**
     * 文件上传
     * @param $request
     * @param $src
     * @return array
     */
    public function uploadFile($request,$src) {
        $file = $request->file($src);
        // 此时 $this->upload如果成功就返回文件名不成功返回false
        $fileName = $this->upload($file);
        if ($fileName){
            return ['code' => true,'msg' => $fileName];
        }
          return ['code' => false,'msg' => '上传文件失败！'];
    }

    /**
     * 验证文件是否合法
     * @param $file
     * @param string $disk
     * @return bool|string
     */
    public function upload($file, $disk='public') {
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['png', 'jpg', 'bmp','jpeg'])) {
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
            return $fileName;
        }

    }

}
