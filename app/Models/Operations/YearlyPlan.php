<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YearlyPlan extends Model
{
    protected $table = "tbl_synthesisyearlyplan_ship";

    public static function getPlan($year = '2017'){
        // 자료 얻기
        $query = 'SELECT tb_ship_register.shipName_Cn, tb_ship.name, tbl_synthesisyearlyplan_ship.*
              FROM tbl_synthesisyearlyplan_ship
              JOIN tb_ship_register on tb_ship_register.RegNo = tbl_synthesisyearlyplan_ship.ShipID
              JOIN tb_ship on tb_ship_register.Shipid = tb_ship.id
              WHERE tbl_synthesisyearlyplan_ship.yearly = "'.$year.'"
              ORDER BY tb_ship_register.order';
        $result=DB::select($query);
        return $result;
    }




}