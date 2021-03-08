<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReportPersonWeek extends Model
{
    protected $table = 'tb_person_week_report';

    public static function getMemberReportWeek($year, $week) {
        $query = 'SELECT report_table.plan, report_table.report, report_table.update_at, tb_users.realname, tb_unit.title, tb_pos.title as posName FROM
                    (SELECT userId, plan, report, update_at FROM tb_person_week_report
                                      WHERE planYear = '.$year.' AND planWeek = '.$week.') report_table
                  RIGHT JOIN tb_users ON report_table.userId = tb_users.id
                  LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id
                  LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id
                  ORDER BY tb_unit.orderkey, tb_pos.orderNum, tb_users.realname';

        $result = DB::select($query);

        return $result;
    }
}
