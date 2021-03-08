<?php
namespace App\Models\SupplyPlan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 5/23/2017
 * Time: 1:02 PM
 */
class SupplyPlan extends Model
{
    protected $table = 'tbl_supplyplan';
    public $timestamps = false;
    protected $primaryKey = 'Id';

    public static function getMinYear()
    {
        return SupplyPlan::join('tb_ship_register', 'tbl_supplyplan.ShipName', '=', 'tb_ship_register.RegNo')
            ->join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
            ->where('tbl_supplyplan.Yearly', '<>', 'NULL')
            ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
            ->min('tbl_supplyplan.Yearly');
    }

    public static function getMaxYear()
    {
        return SupplyPlan::join('tb_ship_register', 'tbl_supplyplan.ShipName', '=', 'tb_ship_register.RegNo')
            ->join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
            ->where('tbl_supplyplan.Yearly', '<>', 'NULL')
            ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
            ->max('Yearly');
    }

    public static function getPlanList($year = null, $shipid = null)
    {
        $query = static::query()
            ->select('tbl_dept.Dept_Cn','tb_ship_register.shipName_Cn', 'tb_ship_register.ShipName_En','tbl_supplyplan.*')
            ->join('tbl_dept','tbl_dept.id','=','tbl_supplyplan.Dept')
            //->join('tbl_cp', 'tbl_cp.id', '=', 'tbl_supplyplan.ApplicationVoy')
            ->join('tb_ship_register','tb_ship_register.RegNo','=','tbl_supplyplan.ShipName')
            ->orderBy('tbl_supplyplan.Yearly', 'DESC')
            ->orderBy('tbl_supplyplan.Monthly');

        if($year!=0)
        {
            $query=$query->where('tbl_supplyplan.Yearly','=',$year);
        }
        if($shipid!=0)
        {
            $query=$query->where('tb_ship_register.id','=',$shipid);
        }
        $result=$query->orderby('tbl_supplyplan.Id')->paginate()->setPath('');
        return $result;
    }

    public static function getPlanedShipList($year = null)
    {
        if ($year==0) {
            $planedShipList = static::query()
                ->select('tb_ship_register.id','tb_ship_register.shipName_Cn', 'tb_ship_register.ShipName_En', 'tb_ship_register.id as shipID', 'tbl_supplyplan.ShipName')
                ->join('tb_ship_register', 'tbl_supplyplan.ShipName', '=', 'tb_ship_register.RegNo')
                ->join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->where('tbl_supplyplan.Yearly', '<>', 'NULL')
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->distinct('tb_ship_register.shipName_Cn')->distinct('tb_ship_register.ShipName_En')->get();

        } else {
            $planedShipList = static::query()
                ->select('tb_ship_register.shipName_Cn', 'tbl_supplyplan.ShipName', 'tbl_supplyplan.ShipName')
                ->join('tb_ship_register', 'tbl_supplyplan.ShipName', '=', 'tb_ship_register.RegNo')
                ->join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->where('tbl_supplyplan.Yearly', '=', $year)
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->distinct('tb_ship_register.shipName_Cn')
                ->distinct('tb_ship_register.ShipName_En')
                ->get();

        }
        return $planedShipList;
    }

    public static function getSupplyAmount($yearRange,$shipList,$deptList)
    {
        $result=array();
        for($year=$yearRange->min;$year<=$yearRange->max;$year++)
        {
            foreach($shipList as $ship)
            {
                foreach($deptList as $dept)
                {
                    for($month=1;$month<13;$month++)
                    {
                        $result[$year.$ship.$dept.$month]=static::query()->select('Amount')->where('Yearly','=',$year)->where('ShipName','=',$ship['ShipName'])
                            ->where('Dept','=',$dept['id'])->where('Monthly','=',$month)->first();
                    }
                }
            }
        }
        return $result;
    }
}