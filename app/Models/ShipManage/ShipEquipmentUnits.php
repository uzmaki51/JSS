<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/15
 * Time: 9:10
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipEquipmentUnits extends Model
{
    use SoftDeletes;
    protected $table = 'tb_equipment_units';
    protected $date = ['deleted_at'];

}