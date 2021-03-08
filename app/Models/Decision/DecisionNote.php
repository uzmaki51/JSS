<?php
/**
 * Created by PhpStorm.
 * User: Choe Mun Bong
 * Date: 2017/5/3
 * Time: 19:03
 */

namespace App\Models\Decision;

use Illuminate\Database\Eloquent\Model;
use DB;

class DecisionNote extends Model
{
    protected $table = 'tb_decision_note';

    public static function reportDecideUsers($reportId) {
        $query = 'SELECT tb_decider.userId AS decider, note_table.* FROM tb_decider
                LEFT JOIN (SELECT * FROM tb_decision_note WHERE tb_decision_note.reportId = '.$reportId.') AS note_table
                ON tb_decider.userId = note_table.userId
                WHERE tb_decider.flowId IN (SELECT flowid FROM tb_decision_report WHERE id = '.$reportId.') ORDER BY orderNum';
        $result = DB::select($query);

        return $result;
    }
}