<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = "yh_category";
    public  $timestamps = false;
    protected  $guarded = [];
    protected $hidden = ['CREATED_TIME','UPDATED_TIME'];
}
