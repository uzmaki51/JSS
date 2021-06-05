<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/14
 * Time: 7:41
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ShipManage\ShipRegister;

class Fuel extends Model
{
    protected $table = 'tb_fuel_analyze';
}