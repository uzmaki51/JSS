<?php

/**
 * Created by PhpStorm.
 * User: 최문봉
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnitMonthReport extends Model
{

    protected $table = 'tb_unit_month_report';

    public static function getReportPerUnit($year, $month) {
        $query = 'SELECT a.plan, a.report, a.update_at, b.title FROM
        (SELECT unitId, plan, report, update_at FROM tb_unit_month_report WHERE planYear = '.$year.' AND planMonth = '.$month.') a
        RIGHT JOIN tb_unit b ON a.`unitId` = b.`id` WHERE b.orderkey LIKE "001%" ORDER BY b.orderkey';

        $result = DB::select($query);

        return $result;
    }


}