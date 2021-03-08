<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Attend;

use Illuminate\Database\Eloquent\Model;

class AttendType extends Model
{
    protected $table = 'tb_attend_type';

    public function data(){
        return $this->get();
    }
}