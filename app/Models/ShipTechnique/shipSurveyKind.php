<?php
/**
 * Created by PhpStorm.
 * User: SJG
 * Date: 2017.05.25
 * Time: AM 9:59
 */

namespace App\Models\ShipTechnique;

use Illuminate\Database\Eloquent\Model;

class ShipSurveyKind extends Model
{
    protected $table = "tbl_survey_kind";
    protected $timestamp=false;
    public static function getSurveyKinds()
    {
        $query = static::query()
            ->select('tbl_survey_kind.id', 'tbl_survey_kind.SurveyKind_Cn','tbl_survey_kind.SurveyKind_En');

        return$query->get() ;
    }

    public static function getSurveyKind($id)
    {
        $query = static::query()
            ->select('tbl_survey_kind.id', 'tbl_survey_kind.SurveyKind_Cn','tbl_survey_kind.SurveyKind_En')
        ->where( 'tbl_survey_kind.id',$id)->first();

        return$query ;
    }
}