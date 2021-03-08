<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportPerson extends Model
{

    protected $table = 'tb_person_report';

    public function subPlan() {
        return $this->hasOne('App\Models\Plan\SubPlan', 'id', 'itemId');
    }

    public static function weekReportList($userId, $dateStr) {
        $query = 'SELECT tb_main_plan.name, tb_sub_plan.id as planId, tb_sub_plan.planTitle, tb_sub_plan.color, tb_sub_plan.startDate, tb_sub_plan.endDate, plan_table.* FROM tb_sub_plan
                    LEFT JOIN (SELECT * FROM tb_person_report WHERE userId = '.$userId.' AND create_plan = "'.$dateStr.'") AS plan_table
                    ON plan_table.itemId = tb_sub_plan.id
                    INNER JOIN tb_main_plan ON tb_main_plan.id = tb_sub_plan.mainId
                    WHERE tb_sub_plan.creator = '.$userId .' AND tb_sub_plan.startDate <= "'.$dateStr.'" AND tb_sub_plan.endDate >= "'.$dateStr.'"
                    ORDER BY tb_main_plan.name, tb_sub_plan.planTitle';
        $result = DB::select($query);

        return $result;
    }

    public static function weekReportListAndUncompleteList($userId, $dateStr) {
        $query = 'SELECT tb_main_plan.name, tb_sub_plan.id as planId, tb_sub_plan.planTitle, tb_sub_plan.color, tb_sub_plan.startDate, tb_sub_plan.endDate, plan_table.* FROM tb_sub_plan
                    LEFT JOIN (SELECT * FROM tb_person_report WHERE userId = '.$userId.' AND create_plan = "'.$dateStr.'") AS plan_table
                    ON plan_table.itemId = tb_sub_plan.id
                    INNER JOIN tb_main_plan ON tb_main_plan.id = tb_sub_plan.mainId
                    WHERE tb_sub_plan.creator = '.$userId.' AND ((tb_sub_plan.startDate <= "'.$dateStr.'" AND tb_sub_plan.endDate >= "'.$dateStr.'") OR tb_sub_plan.endDate < "'.$dateStr.'")
                    AND (plan_table.create_plan = "'.$dateStr.'" OR ((tb_sub_plan.comple_date > "'.$dateStr.'" OR tb_sub_plan.comple_date IS NULL) AND (plan_table.create_plan < "'.$dateStr.'" OR plan_table.create_plan IS NULL)))
                    ORDER BY tb_main_plan.name, tb_sub_plan.planTitle';
        $result = DB::select($query);

        return $result;
    }

}