<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 4/21/2017
 * Time: 22:28
 */

namespace App\Models\Decision;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DecisionReport extends Model
{
    protected $table = 'tb_decision_report';


    public function userName(){
        return $this->hasOne('App\Models\UserInfo', 'creator', 'id');
    }

    public function flowName(){
        return $this->hasOne('App\Models\Decision\DecisionFlow', 'flowid', 'id');
    }

    // 기안한 목록
    public static function getDecisionReportList($decide_name, $flow_type, $creator, $from_date, $to_date)
    {
        $query = static::query()
            ->select('tb_decision_report.id', 'tb_decision_report.file1', 'tb_decision_report.file2', 'tb_decision_report.submitUnit',
                'tb_decision_report.fileName1', 'tb_decision_report.fileName2',
				'tb_decision_report.title as decide_name', 'tb_decision_report.state', 'tb_decision_report.draftDate',
                'tb_decision_report.eject', 'tb_decision_report.flowState', 'tb_decision_report.flowid', 'tb_decision_report.fileName1', 'tb_decision_report.fileName2',
                'tb_decision_flow.title as flow_name', 'tb_decision_flow.decideUsers', 'tb_decision_flow.recvUsers',
                'tb_users.realname' )
            ->join('tb_decision_flow','tb_decision_report.flowid', '=', 'tb_decision_flow.id')
            ->join('tb_users', 'tb_decision_report.creator', '=', 'tb_users.id')
            ->where('tb_decision_report.creator', $creator)
            ->where('tb_decision_report.tempBox', 0);

        if(isset($decide_name))
            $query->where('tb_decision_report.title', 'like', '%'.$decide_name.'%');
        if(isset($flow_type))
            $query->where('tb_decision_flow.id', '=', ''.$flow_type.'');
        if(isset($from_date))
            $query->where('tb_decision_report.draftDate', '>=',$from_date);
        if(isset($to_date))
            $query->where('tb_decision_report.draftDate', '<=', $to_date);

        $reportList = $query->orderBy('tb_decision_report.id', 'desc')->paginate(10)->setPath('');

        return $reportList;
    }

    // 결재할 목록
    public static function getWillDecisionReportList($decider, $decide_name, $flow_type, $creator, $from_date, $to_date, $page=1, $perPage = 10)
    {

        $agentDecider = DecEnvironment::deciderListByAgent($decider);

        $start = ($page - 1) * $perPage;

        $query = 'SELECT tb_decision_report.*, IFNULL(decider_table.deciderCount, 0) as decideCount, count_table.totalCount, tb_users.realname, tb_decision_flow.title AS flowTitle
                FROM
                (SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    WHERE tb_decider.userId = '.$decider.' GROUP BY tb_decision_report.id';
        if(!empty($agentDecider))
             $query .= ' UNION
                 SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    WHERE tb_decider.userId IN ('.$agentDecider.') GROUP BY tb_decision_report.id';

        $query .= ') AS report_table
                LEFT JOIN (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    ON  decider_table.reportId = report_table.id
                JOIN tb_decision_report ON report_table.id = tb_decision_report.id
                JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
			            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
		        ON count_table.id = tb_decision_report.id
                JOIN tb_users ON tb_decision_report.creator = tb_users.id
		        JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                WHERE tb_decision_report.tempBox = 0 AND tb_decision_report.eject = 0 AND ((decider_table.deciderCount + 1) = report_table.orderNum OR (decider_table.deciderCount IS NULL AND report_table.orderNum = 1))';

//        var_dump($query);die;

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_type))
            $query .= ' AND tb_decision_flow.id = "'.$flow_type.'"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $query .= ' ORDER BY tb_decision_report.id DESC LIMIT '.$start.','.$perPage;


        $reportList = DB::select($query);

        return $reportList;
    }

    // 결재할 목록
    public static function countWillDecisionReportList($decider, $decide_name, $flow_type, $creator, $from_date, $to_date)
    {

        $agentDecider = DecEnvironment::deciderListByAgent($decider);

        $query = 'SELECT count(*) as totalCount
                FROM
                (SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    WHERE tb_decider.userId = '.$decider.' GROUP BY tb_decision_report.id';
        if(!empty($agentDecider))
            $query .= ' UNION
                 SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    WHERE tb_decider.userId IN ('.$agentDecider.') GROUP BY tb_decision_report.id';
        $query .= ') AS report_table
                LEFT JOIN (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    ON  decider_table.reportId = report_table.id
                JOIN tb_decision_report ON report_table.id = tb_decision_report.id
                JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
			            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
		        ON count_table.id = tb_decision_report.id
                JOIN tb_users ON tb_decision_report.creator = tb_users.id
		        JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                WHERE tb_decision_report.tempBox = 0 AND tb_decision_report.eject = 0 AND ((decider_table.deciderCount + 1) = report_table.orderNum OR (decider_table.deciderCount IS NULL AND report_table.orderNum = 1))';

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_type))
            $query .= ' AND tb_decision_flow.id = "'.$flow_type.'"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $query .= ' ORDER BY tb_decision_report.id DESC';

        $result = DB::select($query);

        $page = 0;
        if(count($result) > 0) {
            $totalCount = $result[0]->totalCount;
            $page = floor($totalCount / 10);
            $remain = fmod($totalCount, 10);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;

        return $totalCount;
    }

    // 수신된 목록
    public static function getReceiveReportList($receiver, $decide_name, $flow_type, $creator, $from_date, $to_date, $page = 1, $perPage = 15)
    {
        $start = ($page - 1) * $perPage;
        if($start < 0)
            $start = 0;

        $query = 'SELECT tb_decision_report.*, IFNULL(decider_table.deciderCount, 0) AS decideCount, count_table.totalCount, tb_users.realname, tb_decision_flow.title AS flowTitle
                    FROM (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    RIGHT JOIN tb_decision_report ON tb_decision_report.id = decider_table.reportId
                    JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
                            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
                    ON count_table.id = tb_decision_report.id
                    JOIN tb_users ON tb_decision_report.creator = tb_users.id
                    JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                    WHERE tb_decision_report.tempBox = 0 AND tb_decision_report.recvUser LIKE "%,'.$receiver.',%"';

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_type))
            $query .= ' AND tb_decision_flow.id = "'.$flow_type.'"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $query .= ' ORDER BY tb_decision_report.id DESC LIMIT '.$start.','.$perPage;

        $reportList = DB::select($query);

        return $reportList;

    }

    // 수신된 목록의 페지개수를 귀환한다.
    public static function countReceiveReportList($receiver, $decide_name, $flow_type, $creator, $from_date, $to_date, $perPage = 15)
    {

        $query = 'SELECT COUNT(*) AS totalCount
                    FROM (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    RIGHT JOIN tb_decision_report ON tb_decision_report.id = decider_table.reportId
                    JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
                            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
                    ON count_table.id = tb_decision_report.id
                    JOIN tb_users ON tb_decision_report.creator = tb_users.id
                    JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                    WHERE tb_decision_report.tempBox = 0 AND tb_decision_report.recvUser LIKE "%,'.$receiver.',%"';

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_type))
            $query .= ' AND tb_decision_flow.id = "'.$flow_type.'"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $reportList = DB::select($query);

        $page = 0;
        if(count($reportList) > 0) {
            $totalCount = $reportList[0]->totalCount;
            $page = floor($totalCount / $perPage);
            $remain = fmod($totalCount, $perPage);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }

    // 결재진행상태 목록
    public static function getProcessReportList($userId, $decide_name, $flow_type, $creator, $from_date, $to_date, $page=1, $perPage=10)
    {

        $agentDecider = DecEnvironment::deciderListByAgent($userId);
        if(empty($agentDecider))
            $agentDecider = $userId;
        else
            $agentDecider = $userId.','.$agentDecider;

        $start = ($page - 1) * $perPage;

        $query = 'SELECT tb_decision_report.*, IFNULL(decider_table.deciderCount, 0) AS decideCount, count_table.totalCount, tb_users.realname, tb_decision_flow.title AS flowTitle FROM
                (SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    INNER JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                    WHERE tb_decider.userId IN ('.$agentDecider.') OR tb_decision_report.recvUser LIKE "%,'.$userId.',%" GROUP BY tb_decision_report.id) AS report_table
                LEFT JOIN (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    ON  decider_table.reportId = report_table.id
                JOIN tb_decision_report ON report_table.id = tb_decision_report.id
                JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
			            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
		        ON count_table.id = tb_decision_report.id
                JOIN tb_users ON tb_decision_report.creator = tb_users.id
		        JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                WHERE tb_decision_report.tempBox = 0';

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_type))
            $query .= ' AND tb_decision_flow.id = "'.$flow_type.'"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $query .= ' ORDER BY tb_decision_report.id DESC LIMIT '.$start.','.$perPage;

        $reportList = DB::select($query);

        return $reportList;
    }

    public static function countProcessReportList($userId, $decide_name, $flow_name, $creator, $from_date, $to_date)
    {
        $perPage = 10;
        $agentDecider = DecEnvironment::deciderListByAgent($userId);
        if(empty($agentDecider))
            $agentDecider = $userId;
        else
            $agentDecider = $userId.','.$agentDecider;

        $query = 'SELECT count(*) as totalCount FROM
                (SELECT tb_decision_report.id, tb_decider.orderNum FROM tb_decider
                    INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid
                    INNER JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                    WHERE tb_decider.userId IN ('.$agentDecider.') OR tb_decision_report.recvUser LIKE "%,'.$userId.',%" GROUP BY tb_decision_report.id) AS report_table
                LEFT JOIN (SELECT reportId, COUNT(*) AS deciderCount FROM tb_decision_note WHERE state = 0 GROUP BY reportId) AS decider_table
                    ON  decider_table.reportId = report_table.id
                JOIN tb_decision_report ON report_table.id = tb_decision_report.id
                JOIN (SELECT tb_decision_report.id, COUNT(*) AS totalCount FROM tb_decider
			            INNER JOIN tb_decision_report ON tb_decider.flowId = tb_decision_report.flowid GROUP BY tb_decision_report.id) AS count_table
		        ON count_table.id = tb_decision_report.id
                JOIN tb_users ON tb_decision_report.creator = tb_users.id
		        JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
                WHERE tb_decision_report.tempBox = 0';

        if(!empty($decide_name))
            $query .= ' AND tb_decision_report.title like "%'.$decide_name.'%"';
        if(!empty($flow_name))
            $query .= ' AND tb_decision_flow.title like "%'.$flow_name.'%"';
        if(!empty($creator))
            $query .= ' AND tb_users.realname like "%'.$creator.'%"';
        if(!empty($from_date))
            $query .= ' AND tb_decision_report.draftDate >= "'.$from_date.'"';
        if(!empty($to_date))
            $query .= ' AND tb_decision_report.draftDate <= "'.$to_date.'"';

        $query .= ' ORDER BY tb_decision_report.id DESC';

        $reportList = DB::select($query);

        $page = 0;
        if(count($reportList) > 0) {
            $totalCount = $reportList[0]->totalCount;
            $page = floor($totalCount / $perPage);
            $remain = fmod($totalCount, $perPage);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }

    // 림시보관함
    public static function getDraftReportList($decide_name, $flow_name, $creator, $create_date)
    {
        $query = static::query()
            ->select( 'tb_decision_report.id', 'tb_decision_report.file1', 'tb_decision_report.file2', 'tb_decision_report.submitUnit',
				'tb_decision_report.fileName1', 'tb_decision_report.fileName2',
                'tb_decision_report.title as decide_name', 'tb_decision_report.state', 'tb_decision_report.draftDate',
                'tb_decision_report.eject','tb_decision_report.update_at',
                'tb_decision_flow.title as flow_name', 'tb_decision_flow.decideUsers', 'tb_decision_flow.recvUsers',
                'tb_users.realname' )
            ->leftJoin('tb_decision_flow','tb_decision_report.flowid', '=', 'tb_decision_flow.id')
            ->join('tb_users', 'tb_decision_report.creator', '=', 'tb_users.id')
            ->where('tb_decision_report.creator', $creator)
            ->where('tb_decision_report.tempBox', 1);

        if(isset($decide_name))
            $query = $query->where('tb_decision_report.title', 'like', '%'.$decide_name.'%');
        if(isset($flow_name))
            $query = $query->where('tb_decision_flow.title', 'like', '%'.$flow_name.'%');
        if(isset($from_date))
            $query = $query->where('tb_decision_report.update_at', '>=', $from_date);
        if(isset($to_date))
            $query = $query->where('tb_decision_report.update_at', '<=', $to_date);

        $reportList = $query->orderBy('tb_decision_report.id', 'desc')->paginate()->setPath('');

        return $reportList;
    }

    // 문서번호가 이미 존재하는가를 판정
    public static function checkAlreadyExistReportNum($serialNum) {
        $isExist = static::where('docNo', $serialNum)->get();
        if(count($isExist))
            $isExist = 1;
        else
            $isExist = 0;
        return $isExist;

    }

    // 새로 수신된 문서의 개수를 계산한다.
    public static function countNoReadRecvReport($userId) {

        $query = 'SELECT COUNT(*) AS noRead FROM tb_decision_report
            INNER JOIN tb_decision_flow ON tb_decision_report.flowid = tb_decision_flow.id
            WHERE tb_decision_report.eject = 0 AND tempBox = 0 AND tb_decision_flow.recvUsers LIKE "%,'.$userId.',%"
            AND tb_decision_report.id NOT IN (SELECT reportId FROM tb_decision_read WHERE userId = '.$userId. ')';

        $result = DB::select($query);
        if(is_null($result))
            $count = 0;
        else {
            $count = $result[0]->noRead;
        }

        return $count;
    }

}