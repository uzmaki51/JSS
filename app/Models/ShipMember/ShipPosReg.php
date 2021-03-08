<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/12
 * Time: 21:25
 */

namespace App\Models\ShipMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipPosReg extends Model
{
    protected $table = 'tb_ship_msmcdata';


    public static function getPostionListByShip($regCode) {
        $result = static::query()
                ->select('tb_ship_msmcdata.id', 'tb_ship_msmcdata.Priority', 'tb_ship_msmcdata.PersonNum', 'tb_ship_duty.Duty', 'tb_ship_duty.Duty_En', 'tb_ship_msmcdata.DutyID', 'tb_ship_msmcdata.STCWRegCodeID', 'tb_ship_stcw_reg.STCWRegCode')
                ->join('tb_ship_duty', 'tb_ship_msmcdata.DutyID', '=', 'tb_ship_duty.id')
                ->join('tb_ship_stcw_reg', 'tb_ship_msmcdata.STCWRegCodeID', '=', 'tb_ship_stcw_reg.id')
                ->where('tb_ship_msmcdata.RegNo', $regCode)
                ->orderBy('tb_ship_msmcdata.Priority')
                ->get();
        return $result;
    }
}