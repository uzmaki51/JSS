<?php

namespace App\Models\Decision;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DecEnvironment extends Model
{
    protected $table="tb_decision_env";

    public static function deciderListByAgent($agentId) {
        $today = date('Y-m-d');
        $result = static::query()
            ->select(DB::raw('GROUP_CONCAT(id) AS decider'))
            ->where('absFunc', 1)
            ->where('agentFunc', 1)
            ->where('agentId', $agentId)
            ->where('startDate', '<=', $today)
            ->where('endDate', '>=' , $today)
            ->first();

        $idStr = '';
        if($result['decider'])
            $idStr = $result['decider'];

        return $idStr;
    }
}