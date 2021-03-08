<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017/8/7
 * Time: 0:10
 */

namespace App\Models\Decision;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Decider extends Model
{
    protected $table="tb_decider";
    public $timestamps = false;

}