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

class ShipSurvey extends Model
{
    protected $table = "tbl_surveyrecord";
    protected $timestamp=false;
    public static function getSurveyInfos()
    {
        $query = static::query()
            ->select('tbl_surveyrecord.id','tbl_surveyrecord.ShipId','tbl_surveyrecord.VoyId','tbl_surveyrecord.AddFileName','tbl_surveyrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn','tbl_cp.Voy_No','tbl_surveyrecord.SurveyDate',
                'tbl_surveyrecord.PortId','tbl_surveyrecord.SurveyKindId','tbl_surveyrecord.Object','tbl_surveyrecord.Surveyer',
                'tbl_surveyrecord.Amount','tbl_surveyrecord.Content','tbl_surveyrecord.Deficiency','tbl_surveyrecord.Rectify',
                'tbl_port.Port_Cn','tbl_survey_kind.SurveyKind_Cn')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_surveyrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_surveyrecord.VoyId')
            ->leftjoin('tbl_port','tbl_port.id','=','tbl_surveyrecord.PortId')
            ->leftjoin('tbl_survey_kind','tbl_survey_kind.id','=','tbl_surveyrecord.SurveyKindId')
            ->orderBy('VoyId');

        $result = $query->paginate();
        return$result ;
    }

    public static function getSurveySearch($shipId, $voy_number, $page=0)
    {
        $query = static::query()
            ->select('tbl_surveyrecord.id','tbl_surveyrecord.ShipId','tbl_surveyrecord.VoyId','tbl_surveyrecord.AddFileName','tbl_surveyrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn', 'tb_ship_register.shipName_En','tbl_cp.Voy_No', 'tbl_cp.CP_No','tbl_surveyrecord.SurveyDate',
                'tbl_surveyrecord.PortId','tbl_surveyrecord.SurveyKindId','tbl_surveyrecord.Object','tbl_surveyrecord.Surveyer',
                'tbl_surveyrecord.Amount','tbl_surveyrecord.Content','tbl_surveyrecord.Deficiency','tbl_surveyrecord.Rectify',
                'tbl_port.Port_Cn','tbl_survey_kind.SurveyKind_Cn')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_surveyrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_surveyrecord.VoyId')
            ->leftjoin('tbl_port','tbl_port.id','=','tbl_surveyrecord.PortId')
            ->leftjoin('tbl_survey_kind','tbl_survey_kind.id','=','tbl_surveyrecord.SurveyKindId')
            ->orderBy('VoyId');

        if(isset($shipId))
            $query->where('tb_ship_register.RegNo', $shipId);
        if (isset($voy_number))
            $query->where( 'tbl_cp.CP_No', $voy_number);

        $query = $query->orderBy(DB::raw('CONVERT(tbl_cp.Voy_No , DECIMAL(4,0))'), 'DESC');
        if($page == 0)
            $result = $query->paginate(1000);
        else
            $result = $query->get();
        return $result ;
    }

    public static function getSurveyDetail($id)
    {
        $query = static::query()
            ->select('tbl_surveyrecord.id','tbl_surveyrecord.ShipId','tbl_surveyrecord.VoyId','tbl_surveyrecord.AddFileName','tbl_surveyrecord.AddFileServerPath',
                'tb_ship_register.shipName_Cn','tbl_cp.Voy_No','tbl_surveyrecord.SurveyDate',
                'tbl_surveyrecord.PortId','tbl_surveyrecord.SurveyKindId','tbl_surveyrecord.Object','tbl_surveyrecord.Surveyer',
                'tbl_surveyrecord.Amount','tbl_surveyrecord.Content','tbl_surveyrecord.Deficiency','tbl_surveyrecord.Rectify',
                'tbl_port.Port_Cn','tbl_survey_kind.SurveyKind_Cn')
            ->leftjoin('tb_ship_register','tb_ship_register.RegNo','=','tbl_surveyrecord.ShipId')
            ->leftjoin('tbl_cp','tbl_cp.CP_No','=','tbl_surveyrecord.VoyId')
            ->leftjoin('tbl_port','tbl_port.id','=','tbl_surveyrecord.PortId')
            ->leftjoin('tbl_survey_kind','tbl_survey_kind.id','=','tbl_surveyrecord.SurveyKindId');

        $query = $query->where( 'tbl_surveyrecord.id',$id)->first();

        return$query ;
    }
}