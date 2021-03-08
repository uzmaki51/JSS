<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Decision;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecisionFlow extends Model
{
    use SoftDeletes;
    protected $table = 'tb_decision_flow';
    protected $date = ['deleted_at'];

    public function getDeciderList() {
        $list = static::query()
            ->select('tb_decider.userId')
            ->join('tb_decider', 'tb_decision_flow.id', '=', 'tb_decider.flowId')
            ->where('tb_decision_flow.id', $this['id'])
            ->orderBy('tb_decider.orderNum')
            ->get();
        return $list;
    }

    public function countDeciders() {
        $count = static::query()
            ->join('tb_decider', 'tb_decision_flow.id', '=', 'tb_decider.flowId')
            ->where('tb_decision_flow.id', $this['id'])
            ->orderBy('tb_decider.orderNum')
            ->get()
            ->count();
        return $count;
    }
    public static function deciderList($flowId) {

        $query = 'SELECT userId FROM tb_decider WHERE flowId = '.$flowId.' ORDER BY orderNum';
        $list = DB::select($query);

        $listStr = '';
        foreach($list as $decider) {
            $listStr = empty($listStr) ? $decider->userId : $listStr.','.$decider->userId;
        }
        return $listStr;
    }

    public static function getDecisionFlow() {
    	$query = 'SELECT * FROM tb_decision_flow where 1 ';
    	$list = DB::select($query);

    	return $list;
    }

}