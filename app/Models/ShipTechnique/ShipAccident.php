<?php
/**
 * Created by PhpStorm.
 * User: SJG
 * Date: 2017.05.25
 * Time: AM 9:59
 */

namespace App\Models\ShipTechnique;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipAccident extends Model
{
    protected $table = "tbl_accidentrecord";

    public static function getAccidentSearch($shipId, $voy_number, $page=0)
    {
        $query = static::query()
            ->select('tbl_accidentrecord.id','tbl_accidentrecord.VoyId','tbl_accidentrecord.ShipId','tbl_accidentrecord.AddFileName','tbl_accidentrecord.AddFileServerPath', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En','tbl_cp.Voy_No', 'tbl_cp.CP_No','tbl_accidentrecord.AccidentDate','tbl_accidentrecord.Place','tbl_accidentrecord.AccidentKind','tbl_accidentrecord.Content','tbl_accidentrecord.Details','tbl_port.Port_Cn')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_accidentrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_accidentrecord.VoyId')
            ->leftjoin('tbl_port','tbl_port.id','=','tbl_accidentrecord.PortId')
            ->orderBy('VoyId');

        if(isset($shipId))
            $query = $query->where('tb_ship_register.RegNo', $shipId);
        if(isset($voy_number))
            $query = $query->where( 'tbl_cp.CP_No',$voy_number);

        $query = $query->orderBy(DB::raw('CONVERT(tbl_cp.Voy_No , DECIMAL(4,0))'), 'DESC');//->orderBy('tbl_accidentrecord.id', 'desc')
        if($page == 0)
            $result = $query->paginate(1000);
        else
            $result = $query->get();
        return$result ;
    }

    public static function getAccidentDetail($id)
    {
        $query = static::query()
            ->select('tbl_accidentrecord.id','tbl_accidentrecord.VoyId','tbl_accidentrecord.ShipId','tbl_accidentrecord.AddFileName','tbl_accidentrecord.AddFileServerPath', 'tb_ship_register.shipName_Cn','tbl_cp.Voy_No','tbl_accidentrecord.AccidentDate','tbl_accidentrecord.Place','tbl_accidentrecord.AccidentKind','tbl_accidentrecord.Content','tbl_accidentrecord.Details','tbl_port.Port_Cn')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_accidentrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_accidentrecord.VoyId')
            ->leftjoin('tbl_port','tbl_port.id','=','tbl_accidentrecord.PortId');

        $query = $query->where( 'tbl_accidentrecord.id',$id)->first();

        return$query ;
    }

}