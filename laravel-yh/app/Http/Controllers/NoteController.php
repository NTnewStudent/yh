<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Qcloud\Sms\SmsSingleSender;

/**
 * 短信接口
 * Class NoteController
 * @package App\Http\Controllers
 */
class NoteController extends Controller
{

    /**
     * 获取腾讯短信
     */
    public function getNote(){

        // 短信应用SDK AppID
        $appid = 1400273074; // 1400开头

// 短信应用SDK AppKey
        $appkey = "6717c8e7fd6e73b13b8956bee4e1804b";

// 需要发送短信的手机号码
        $phoneNumbers = '15338503994';

// 短信模板ID，需要在短信应用中申请
        $templateId = 448524;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

        $smsSign = "贵州中软捷升有限公司"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $params = [random_int(1000,9999)];
            $ssender->sendWithParam("86", $phoneNumbers, $templateId,
                $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信

            //添加缓存 判断验证码是否合法
            Cache::put($phoneNumbers,$params,7200);//5分钟

            return response() ->json(['code' => 200,'msg' => $params]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        } catch(\Exception $e) {
            return response() ->json(['code' => 400,'msg' => $e]) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        }

    }

}
