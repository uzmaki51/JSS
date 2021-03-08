<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 5/22/2017
 * Time: 10:27 PM
 */

namespace App\Models\ShipTechnique;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ShipSupply extends Model
{
    use SoftDeletes;

    protected $table = "tbl_supplyrecord";
    protected $date = ['deleted_at'];

    public function Application() {
        return $this->hasOne('App\Models\Operations\Cp', 'id', 'ApplicationVoy');
    }

    public function Recipt() {
        return $this->hasOne('App\Models\Operations\Cp', 'id', 'ReciptVoy');
    }

    public static function getApplInfo($shipId, $voy = null, $page = 0)
    {

        $query = static::query()
            ->select('tbl_supplyrecord.*', 'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En',
                'tbl_dept.Dept_Cn', 'tbl_dept.Dept_En',
                'tb_ship_equipment.Euipment_Cn', 'tb_ship_equipment.Euipment_En', 'tb_ship_equipment.SN',
                'tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartName_En', 'tb_equipment_parts.PartNo',
                'tb_count_unit.Unit_Cn', 'tb_count_unit.Unit_En',
                'tb_equ_main_kind.Kind_Cn', 'tb_equ_main_kind.Kind_En',
                'tb_issacodeno.Content_Cn', 'tb_issacodeno.Content_En', 'tb_issacodeno.CodeNo',
                'tbl_others.Others_Cn', 'tbl_others.Others_En', 'tbl_others.Special',
                'tbl_port.Port_Cn', 'tbl_port.Port_En')
            ->leftJoin('tbl_cp', 'tbl_cp.id', '=', 'tbl_supplyrecord.ApplicationVoy')
            ->leftJoin('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyrecord.ShipName')
            ->leftJoin('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyrecord.Dept')
            ->leftJoin('tb_ship_equipment', 'tb_ship_equipment.id', '=', 'tbl_supplyrecord.Euipment')
            ->leftJoin('tb_equipment_parts', 'tb_equipment_parts.id', '=', 'tbl_supplyrecord.Part')
            ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
            ->leftJoin('tb_equ_main_kind', 'tb_equ_main_kind.id', '=', 'tbl_supplyrecord.SSkind')
            ->leftJoin('tb_issacodeno', 'tb_issacodeno.id', '=', 'tbl_supplyrecord.IssaCodeNo')
            ->leftJoin('tbl_others', 'tbl_others.OthersId', '=', 'tbl_supplyrecord.Others')
            ->leftJoin('tbl_port', 'tbl_port.id', '=', 'tbl_supplyrecord.ReciptPlace')
            ->where('tbl_supplyrecord.ShipName', '=', $shipId);

        if (empty($voy) || $voy == -1) {
            $query = $query->orderby('tbl_supplyrecord.id', 'dsc');
        } else {
            $query = $query->where('tbl_supplyrecord.ApplicationVoy', '=', $voy)->orderby('tbl_supplyrecord.id', 'dsc');
        }
        if($page == 0) return $query->paginate(20);
        return $query->get();
    }

    public static function getVoyListByShipId($shipId)
    {
        $result=static::query()
            ->select('tbl_cp.id','tbl_cp.CP_No', 'tbl_cp.Voy_No')
            ->leftJoin('tbl_cp', 'tbl_supplyrecord.ApplicationVoy', '=', 'tbl_cp.id')
            ->where('ShipName','=',$shipId)
            ->groupBy('tbl_supplyrecord.ApplicationVoy')
            ->orderby(DB::raw('CONVERT(Voy_No , DECIMAL(4,0))'), 'DESC')
            ->get();
        return $result;
    }

    public static function getReciptInfo($shipRegNo, $kindId, $equipId, $page = 0)
    {
        $query = static::query();
        if($kindId == 1 || $kindId == 2) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_equipment_parts', 'tb_equipment_parts.id', '=', 'tbl_supplyrecord.Part')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
                ->where('tbl_supplyrecord.Euipment', $equipId)
                ->where('tbl_supplyrecord.ShipName', $shipRegNo)
                ->where('tbl_supplyrecord.SSkind', $kindId)
                ->orderBy('tb_equipment_parts.PartNo')
                ->orderBy('tb_equipment_parts.PartName_Cn')
                ->orderBy('tbl_supplyrecord.ApplicationVoy')
                ->orderBy('tbl_supplyrecord.ReciptVoy');

        } elseif($kindId == 3) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_issacodeno.Content_Cn', 'tb_issacodeno.CodeNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_issacodeno', 'tb_issacodeno.id', '=', 'tbl_supplyrecord.IssaCodeNo')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
                ->where('tbl_supplyrecord.ShipName', $shipRegNo)
                ->where('tbl_supplyrecord.SSkind', $kindId)
                ->orderBy('tb_issacodeno.CodeNo');
        } else {
            $query = $query->select('tbl_supplyrecord.*', 'tbl_others.Others_Cn', 'tbl_others.Special', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tbl_others', 'tbl_others.OthersId', '=', 'tbl_supplyrecord.Others')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
                ->where('tbl_supplyrecord.ShipName', $shipRegNo)
                ->where('tbl_supplyrecord.SSkind', $kindId)
                ->orderBy('tbl_others.Special');
        }
        //if($page == 0) return $query->orderby('tbl_supplyrecord.ReciptDate', 'dsc')->paginate()->setPath('');
        return $query->orderby('tbl_supplyrecord.ReciptDate', 'dsc')->get();
    }

    public static function getDetailReciptInfoData($shipRegNo, $kindId, $equipId, $part, $QuotObject,
                                                   $ApplicationVoy, $ReciptVoy, $ReciptPlace, $Supplier, $ReciptDate)
    {
        $query = static::query();
        if($kindId == 1 || $kindId == 2) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_equipment_parts', 'tb_equipment_parts.id', '=', 'tbl_supplyrecord.Part')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
                ->where('tbl_supplyrecord.Euipment', $equipId);
            if(!empty($part))
                $query = $query->where('tbl_supplyrecord.Part', $part);
        } elseif($kindId == 3) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_issacodeno.Content_Cn', 'tb_issacodeno.CodeNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_issacodeno', 'tb_issacodeno.id', '=', 'tbl_supplyrecord.IssaCodeNo')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit');
            if(!empty($part))
                $query = $query->where('tbl_supplyrecord.IssaCodeNo', $part);
        } else {
            $query = $query->select('tbl_supplyrecord.*', 'tbl_others.Others_Cn', 'tbl_others.Special', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tbl_others', 'tbl_others.OthersId', '=', 'tbl_supplyrecord.Others')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit');
            if(!empty($part))
                $query = $query->where('tbl_supplyrecord.Others', $part);
        }
        if(!empty($QuotObject))
            $query = $query->where('tbl_supplyrecord.QuotObject', $QuotObject);
        if(!empty($ApplicationVoy))
            $query = $query->where('tbl_supplyrecord.ApplicationVoy', $ApplicationVoy);
        if(!empty($ReciptVoy))
            $query = $query->where('tbl_supplyrecord.ReciptVoy', $ReciptVoy);
        if(!empty($ReciptPlace))
            $query = $query->where('tbl_supplyrecord.ReciptPlace', $ReciptPlace);
        if(!empty($Supplier))
            $query = $query->where('tbl_supplyrecord.Supplier', $Supplier);
        if(!empty($ReciptDate))
            $query = $query->where('tbl_supplyrecord.ReciptDate', $ReciptDate);
        $query = $query->where('tbl_supplyrecord.ShipName', $shipRegNo)
            ->where('tbl_supplyrecord.SSkind', $kindId);
        return $query->orderby('tbl_supplyrecord.ReciptDate', 'dsc')->get();
    }

    public static function getDetailReciptInfo($shipRegNo, $kindId, $equipId, $param)
    {
        $query = static::query();
        if($kindId == 1 || $kindId == 2) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_equipment_parts', 'tb_equipment_parts.id', '=', 'tbl_supplyrecord.Part')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
                ->where('tbl_supplyrecord.Euipment', $equipId);
        } elseif($kindId == 3) {
            $query = $query->select('tbl_supplyrecord.*', 'tb_issacodeno.Content_Cn', 'tb_issacodeno.CodeNo', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tb_issacodeno', 'tb_issacodeno.id', '=', 'tbl_supplyrecord.IssaCodeNo')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit');
        } else {
            $query = $query->select('tbl_supplyrecord.*', 'tbl_others.Others_Cn', 'tbl_others.Special', 'tb_count_unit.Unit_Cn')
                ->leftJoin('tbl_others', 'tbl_others.OthersId', '=', 'tbl_supplyrecord.Others')
                ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit');
        }
        $result = $query->where('tbl_supplyrecord.ShipName', $shipRegNo)
            ->where('tbl_supplyrecord.SSkind', $kindId)
            ->groupBy('tbl_supplyrecord.'.$param)
            ->orderBy('tbl_supplyrecord.'.$param)
            ->get();
        return $result;
    }

    public static function getKindInfo($shipRegNo)
    {
        $query = static::query()
            ->select('tb_equ_main_kind.id', 'tb_equ_main_kind.Kind_Cn')
            ->leftJoin('tb_equ_main_kind', 'tb_equ_main_kind.id', '=', 'tbl_supplyrecord.SSkind')
            ->where('ShipName', $shipRegNo)
            ->groupby('tbl_supplyrecord.SSkind');
        return $query->get();
    }

    public static function getEquipInfo($shipRegNo, $kind)
    {
        $query = static::query()
            ->select('tb_ship_equipment.id', 'tb_ship_equipment.Euipment_Cn', 'tb_ship_equipment.Label', 'tb_ship_equipment.Type', 'tb_ship_equipment.SN')
            ->leftJoin('tb_ship_equipment', 'tb_ship_equipment.id', '=', 'tbl_supplyrecord.Euipment')
            ->where('ShipName', $shipRegNo)
            ->where('SSkind', $kind)
            ->groupby('tbl_supplyrecord.Euipment');
        return $query->get();
    }

    public static function getEquipmentInfo($shipRegNo, $SSkind)
    {
        $query = DB::table('tb_ship_equipment')
            ->select('tb_ship_equipment.id', 'tb_ship_equipment.Euipment_Cn', 'tb_ship_equipment.Euipment_En',
                'tb_ship_equipment.Label', 'tb_ship_equipment.Type', 'tb_ship_equipment.SN',
                'tb_equ_sub_kind.GroupOfEuipment_Cn')
            ->join('tb_ship_equip_kind', 'tb_ship_equipment.KindOfEuipmentId', '=', 'tb_ship_equip_kind.id')
            ->join('tb_equ_sub_kind', 'tb_ship_equip_kind.KindOfEuipmentId', '=', 'tb_equ_sub_kind.id')
            ->where('tb_ship_equip_kind.ShipId', $shipRegNo)
            ->where('tb_ship_equip_kind.KindId', $SSkind)
            ->orderBy('tb_ship_equipment.Euipment_Cn');

        return $query->get();
    }

    public static function getPartInfo($Equipment = null)
    {
        $query = DB::table('tb_equipment_parts')
            ->select('tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartName_En', 'tb_equipment_parts.PartNo',
                'tb_equipment_parts.id')
            ->join('tb_ship_equipment', 'tb_ship_equipment.id', '=', 'tb_equipment_parts.EuipmentID')
            ->join('tb_ship_equip_kind', 'tb_ship_equipment.KindOfEuipmentId', '=', 'tb_ship_equip_kind.id')
            ->where('tb_equipment_parts.EuipmentID', $Equipment)
            ->orderBy('tb_equipment_parts.PartName_Cn');

        return $query->get();
    }

    public static function getHistoryInfo($sample)
    {

        $query = static::query()
            ->select('tbl_supplyrecord.*', 'tb_ship_register.shipName_Cn', 'tbl_dept.Dept_Cn',
                'tb_ship_equipment.Euipment_Cn', 'tb_ship_equipment.SN', 'tb_ship_equipment.Label', 'tb_ship_equipment.Type',
                'tb_equipment_parts.PartName_Cn', 'tb_equipment_parts.PartNo',
                'tb_count_unit.Unit_Cn', 'tb_equ_main_kind.Kind_Cn',
                'tb_issacodeno.Content_Cn', 'tb_issacodeno.CodeNo',
                'tbl_others.Others_Cn', 'tbl_others.Special', 'tbl_port.Port_Cn')
            ->leftJoin('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyrecord.ShipName')
            ->leftJoin('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyrecord.Dept')
            ->leftJoin('tb_ship_equipment', 'tb_ship_equipment.id', '=', 'tbl_supplyrecord.Euipment')
            ->leftJoin('tb_equipment_parts', 'tb_equipment_parts.id', '=', 'tbl_supplyrecord.Part')
            ->leftJoin('tb_count_unit', 'tb_count_unit.id', '=', 'tbl_supplyrecord.Unit')
            ->leftJoin('tb_equ_main_kind', 'tb_equ_main_kind.id', '=', 'tbl_supplyrecord.SSkind')
            ->leftJoin('tb_issacodeno', 'tb_issacodeno.id', '=', 'tbl_supplyrecord.IssaCodeNo')
            ->leftJoin('tbl_others', 'tbl_others.OthersId', '=', 'tbl_supplyrecord.Others')
            ->leftJoin('tbl_port', 'tbl_port.id', '=', 'tbl_supplyrecord.ReciptPlace')
            //->where('tbl_supplyrecord.id', '!=', $sample['id'])
            ->where('tbl_supplyrecord.ShipName', '=', $sample['ShipName'])
            ->where('tbl_supplyrecord.SSkind', $sample['SSkind']);
        if($sample['SSkind'] == 1 || $sample['SSkind'] == 2) {
            $query = $query->where('tbl_supplyrecord.Euipment', $sample['Euipment'])
                ->where('tbl_supplyrecord.Part', $sample['Part']);
        } elseif($sample['SSkind'] == 3) {
            $query = $query->where('tbl_supplyrecord.IssaCodeContent', $sample['IssaCodeContent']);
        } else {
            $query = $query->where('tbl_supplyrecord.Others', $sample['Others']);
        }

        $query = $query->orderby('tbl_supplyrecord.id', 'dsc');

        return $query->get();
    }
}