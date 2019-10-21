<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserFormModel extends Model
{
    protected $table = "yh_user_form";
    public  $timestamps = false;
    protected  $guarded = [];
}
