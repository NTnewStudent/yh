<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


//保存用户数据
Route::post('User','UserController@saveUser');

/**
 * 获取所有分类
 * 参数 无
 * @example {
 * {
"code": 200,
"msg": [
        {
        "id": 1,
        "category_name": "土木"
        },
        {
        "id": 2,
        "category_name": "水泥"
        },
        {
        "id": 3,
        "category_name": "钢铁"
        },
        {
        "id": 4,
        "category_name": "铁"
        },
        {
        "id": 5,
        "category_name": "水管"
        }
        ]
}
 *
 *
 * }
 */
Route::get('getAllCategory','CategoryController@getAllCategory');

/**
 *
 * 获取供货商列表
 * 参数 无
 * @example {{
    "code": 200,
    "msg": "empty",
    "data": []
    }}
 *
 *
 *
 */
Route::get('getUserByPage','UserController@getUserList');


/**
 *
 * 登录
 * mobile
 * pwd
 */
Route::post('UserLogin','UserController@UserLogin');


/**
 *
 * 填表单的页面
 *
 */
Route::get('qrform',function (){
   return view('qrform');
});


/**
 *
 * 设置密码
 *
 */
Route::get('setting',function(){
    return view('');
});

/**
 * 获取短信验证码
 */
Route::post('getNote','NoteController@getNote');

/**
 * 登陆注册
 */
Route::post('register','LoginController@register');

/**
 * 用户登陆
 */
Route::post('login','LoginController@login');