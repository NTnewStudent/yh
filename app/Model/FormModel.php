<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormModel extends Model
{
    protected $table = "yh_form";
    public  $timestamps = false;
    protected  $guarded = [];
}
