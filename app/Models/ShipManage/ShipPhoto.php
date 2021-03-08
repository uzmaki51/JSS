<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017/5/09
 * Time: 13:09
 */
namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipPhoto extends Model
{
    use SoftDeletes;
    protected $table = 'tb_ship_photo';
    protected $date = ['deleted_at'];
}