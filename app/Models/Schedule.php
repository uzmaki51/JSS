<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/1
 * Time: 21:59
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Util;

class Schedule extends Model
{
    use SoftDeletes;

    protected $table = 'tb_person_schedule';
    protected $date = ['deleted_at'];
}