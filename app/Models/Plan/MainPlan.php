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

class MainPlan extends Model
{
    protected $table = 'tb_main_plan';

    public static function getWeekMainPlan($startDate, $endDate) {
        /*
        $result = DB::select('select * from tb_main_plan '.
            'where (startDate <= ? and endDate >= ?) '
            .'or (startDate <= ? and endDate >= ?) '
            .'or (startDate >= ? and endDate <= ?) '
            .'or (startDate >= ? and startDate <= ? and endDate >= ?) ',
            array($startDate, $endDate, $startDate, $startDate, $startDate, $endDate, $startDate, $endDate, $endDate));
        */
        $result = DB::select('select * from tb_main_plan order by name');
        return $result;
    }
}