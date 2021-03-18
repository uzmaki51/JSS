<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ShipRegister extends Model
{
    use SoftDeletes;
    protected $table = 'tb_ship_register';
    protected $date = ['deleted_at'];

    public static function getSimpleDataList() {
        $infoList = static::query()
            ->select('tb_ship_register.id','tb_ship.name', 'tb_ship_type.ShipType_Cn', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En',
                'tb_ship_register.Class', 'tb_ship_register.IMO_No', 'tb_ship_register.Flag_Cn', 'tb_ship_register.Displacement', DB::raw('IFNULL(tb_ship.id, 100) as orderNum'))
            ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
            ->join('tb_ship_type', 'tb_ship_register.ShipType', '=', 'tb_ship_type.id')
            ->orderby('orderNum')
            ->paginate(10);
        return $infoList;
    }

    public static function getSimpleDataListExcel() {
        $infoList = static::query()
            ->select('tb_ship_register.id','tb_ship.name', 'tb_ship_type.ShipType_Cn', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En',
                'tb_ship_register.Class', 'tb_ship_register.IMO_No', 'tb_ship_register.Flag_Cn', 'tb_ship_register.Displacement')
            ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
            ->join('tb_ship_type', 'tb_ship_register.ShipType', '=', 'tb_ship_type.id')
            ->orderby('tb_ship_register.id')
            ->get();
        return $infoList;
    }

    public static function getShipListByOrigin() {
        $list = static::query()
                    ->select('tb_ship_register.RegNo', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En', 'tb_ship_register.id as shipID',
                        'tb_ship.id', 'tb_ship.name', 'tb_ship_register.Speed', DB::raw('IFNULL(tb_ship.id, 100) as orderNum'))
                    ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                    ->orderby('tb_ship_register.order')
                    ->get();
        return $list;
    }

    public static function getShipListOnlyOrigin() {
        $list = static::query()
            ->select('tb_ship_register.RegNo', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En', 'tb_ship_register.id as regId',  'tb_ship.id', 'tb_ship.name')
            ->join('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
            ->orderBy('tb_ship.name')
            ->orderBy('tb_ship_register.id')
            ->get();
        return $list;
    }

    public static function getShipFullName($shipId) {
        $nameInfo = static::query()
            ->select('tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En', 'tb_ship_register.RegNo', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
            ->where('tb_ship_register.id', $shipId)
            ->first();
        return $nameInfo;
    }

    public static function getShipFullNameByRegNo($shipReg) {
        $nameInfo = static::query()
            ->select('tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En', 'tb_ship_register.RegNo', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
            ->where('tb_ship_register.RegNo', $shipReg)
            ->first();
        return $nameInfo;
    }

    public static function getShipFullNameByOriginId($shipId) {
        $nameInfo = static::query()
            ->select('tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En', 'tb_ship_register.RegNo', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
            ->where('tb_ship.id', $shipId)
            ->first();
        return $nameInfo;
    }

    public function saveShipGeneralData($params, $shipData) {

    }

}