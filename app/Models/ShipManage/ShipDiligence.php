<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/16
 * Time: 5:21
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipDiligence extends Model
{
//    use SoftDeletes;
    protected $table            = 'tb_ship_equipment_diligence';
    protected $date = ['deleted_at'];

}