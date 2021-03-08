<?php
/**
 * Created by PhpStorm.
 * User: SJG
 * Date: 2017.05.23
 * Time: AM 9:33
 */

namespace App\Models\ShipTechnique;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipRepair extends Model
{
    protected $table = "tbl_repairrecord";

    public static function getRepairInfos()
    {
        $query = static::query()
            ->select('tbl_repairrecord.id', 'tbl_repairrecord.VoyId','tbl_repairrecord.ShipId','tbl_repairrecord.AddFileName','tbl_repairrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn','tbl_cp.Voy_No','tbl_repairrecord.FromDate','tbl_repairrecord.ToDate','tbl_repairrecord.Place',
                'tbl_repairrecord.RepairKind','tbl_repairrecord.AddFileName','tbl_repairrecord.D_Officer','tbl_repairrecord.Amount','tbl_repairrecord.Content','tbl_repairrecord.Detail')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_repairrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_repairrecord.VoyId');

        $result = $query->paginate(6);
        return$result ;
    }

    public static function getRepairSearch($shipId,$voy_number,$page=0)
    {
        $query = static::query()
            ->select('tbl_repairrecord.id', 'tbl_repairrecord.VoyId','tbl_repairrecord.ShipId','tbl_repairrecord.AddFileName','tbl_repairrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En','tbl_cp.Voy_No', 'tbl_cp.CP_No','tbl_repairrecord.FromDate','tbl_repairrecord.ToDate','tbl_repairrecord.Place',
                'tbl_repairrecord.RepairKind','tbl_repairrecord.AddFileName','tbl_repairrecord.D_Officer','tbl_repairrecord.Amount','tbl_repairrecord.Content','tbl_repairrecord.Detail')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_repairrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_repairrecord.VoyId');

        if (isset($shipId))
            $query = $query->where('tb_ship_register.RegNo', $shipId);
        if (isset($voy_number))
            $query = $query->where( 'tbl_cp.CP_No', $voy_number);

        $query = $query->orderBy(DB::raw('CONVERT(tbl_cp.Voy_No , DECIMAL(4,0))'), 'DESC');//->orderBy('tbl_repairrecord.ToDate', 'dsc');
        if($page == 0)
            $result = $query->paginate(1000);
        else
            $result = $query->get();

        return $result ;
    }

    public static function getRepairDetail($id)
    {
        $query = static::query()
            ->select('tbl_repairrecord.id', 'tbl_repairrecord.VoyId','tbl_repairrecord.ShipId','tbl_repairrecord.AddFileName','tbl_repairrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn','tbl_cp.Voy_No','tbl_repairrecord.FromDate','tbl_repairrecord.ToDate','tbl_repairrecord.Place',
                'tbl_repairrecord.RepairKind','tbl_repairrecord.AddFileName','tbl_repairrecord.D_Officer','tbl_repairrecord.Amount','tbl_repairrecord.Content','tbl_repairrecord.Detail')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_repairrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_repairrecord.VoyId');

        $query = $query->where( 'tbl_repairrecord.id',$id)->first();

        return$query ;
    }
}