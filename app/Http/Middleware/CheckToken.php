<?php

namespace App\Http\Middleware;

use Closure;

class CheckToken
{
    /**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @return mixed
 */

    public function handle($request, Closure $next)
    {
        //        获取token判断是否过期
        $token = Input::get('token');

        $uuid =  Cache::get($token);

        if(empty($uuid)){
            return response() ->json(['code' => 400,'msg' =>'token过期']) ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return $next($request);
    }
}
