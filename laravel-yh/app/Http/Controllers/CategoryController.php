<?php

namespace App\Http\Controllers;

use App\Model\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     *
     *  返回所有分类类型
     * @api getAllCategory
     *
     *
     *
     *
     *
     */
    public function getAllCategory(){

        $list =  CategoryModel::all();

        if(count($list) < 0 ){
            return response() ->json(['code'=> 404,'msg' => '没数据'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }else{
            return response() ->json(['code'=> 200,'msg' => $list -> makeHidden('parent_id')])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }


}
