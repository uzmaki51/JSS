<?php

/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubPlan extends Model
{
    protected $table = 'tb_sub_plan';

    public function mainPlan() {
        return $this->hasOne('App\Models\Plan\MainPlan', 'id', 'mainId');
    }

    public static function checkAlreadExist($userId, $planId, $planTitle, $startDate, $endDate, $planId = 0) {
        $query = 'SELECT * FROM tb_sub_plan WHERE creator = '.$userId.' AND mainId = '.$planId.' AND planTitle = "'.$planTitle.'"'.
            ' AND (startDate BETWEEN "'.$startDate.'" AND "'.$endDate.'" OR endDate BETWEEN "'.$startDate.'" AND "'.$endDate.'")';

        $result = DB::select($query);

        $check = count($result);
        if(($check > 0) && ($result[0]->id == $planId))
            $check = 1;
        else
            $check = 0;

        return $check;
    }

    public static function getSubPlanByDate($userId, $type=0) {
        /*
        $query = 'SELECT tb_sub_plan.*, tb_main_plan.name as mainPlan FROM tb_sub_plan
              INNER JOIN tb_main_plan ON tb_main_plan.id = tb_sub_plan.mainId
              WHERE tb_sub_plan.creator = '.$userId.' AND tb_sub_plan.endDate > "'.$startDate.'"';
        */
        /*
        $query = DB::table('tb_sub_plan')
            ->join('tb_main_plan', 'tb_main_plan.id', '=', 'tb_sub_plan.mainId')
            ->leftJoin('tb_person_report', 'tb_person_report.itemId', '=', 'tb_sub_plan.id')
            ->where('tb_sub_plan.creator', $userId);
        if($type == 1)
            $query = $query->where('tb_person_report.rate == 100');
        elseif($type == 2)
            $query = $query->where('tb_person_report.rate', '<', 100);
        $result = $query->select('tb_sub_plan.*', 'tb_main_plan.name as mainPlan')->paginate(10);
        */
        $query = 'SELECT tb_sub_plan.*, tb_main_plan.name as mainPlan FROM tb_sub_plan
              INNER JOIN tb_main_plan ON tb_main_plan.id = tb_sub_plan.mainId
              LEFT JOIN tb_person_report ON tb_person_report.itemId = tb_sub_plan.id
              WHERE tb_sub_plan.creator = '.$userId;

        if($type == 1) {
            $query .= ' AND tb_person_report.rate = 100';
        } else if($type == 2) {
            $query .= ' AND tb_person_report.rate < 100';
        }
        $query .= ' GROUP BY tb_sub_plan.id';
        $query .= ' ORDER BY tb_sub_plan.planTitle';
        $result = DB::select($query);

        // sort by mainPlan
        for ($idx1 = 0; $idx1 < count($result); $idx1++) {
            for ($idx2 = $idx1 + 1; $idx2 < count($result); $idx2++) {
                if (strcmp($result[$idx1]->mainPlan, $result[$idx2]->mainPlan) > 0) {
                    $temp = array();
                    $temp = $result[$idx1];
                    $result[$idx1] = $result[$idx2];
                    $result[$idx2] = $temp;
                }
            }
        }

        return $result;
    }
}