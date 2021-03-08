<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Attend;

use Illuminate\Database\Eloquent\Model;
use DB;

class AttendUser extends Model
{
    protected $table = 'tb_attend_user';

    public static function getAttendMemberListByDate($selDate = null, $unit = 1, $page = 1) {
        if(is_null($page))
            $page = 1;
        $start = ($page - 1) * 10;
        if($selDate == null)
            $selDate = date("Y-m-d");

        $query = 'SELECT c.id, c.realname, c.statusId, c.memo, c.update_at as regDate, tb_pos.title, tb_attend_type.name as statusName, tb_users.name as creator
                  FROM (
                      SELECT b.id, b.realname, b.pos, a.statusId, a.memo, a.creator, a.update_at
                          FROM (SELECT * FROM tb_attend_user WHERE regDay="'.$selDate.'") a
                          RIGHT JOIN tb_users b ON a.userId = b.id WHERE status = 1';
        if(isset($unit))
            $query .= ' AND b.unit='.$unit;
        $query .= ' ORDER BY b.unit) as c
            LEFT JOIN tb_attend_type ON c.statusId = tb_attend_type.id
            LEFT JOIN tb_pos ON c.pos = tb_pos.id
            LEFT JOIN tb_users ON c.creator = tb_users.id
            ORDER BY tb_pos.orderNum, c.realname LIMIT '.$start.', 10';

        $memberList = DB::select($query);
        return $memberList;
    }

    //출근항목에 해당한 날자수 구하기
    public static function getDaysOfMonthByAttendType($type, $userId = null, $year = null, $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;
        $day = date('Y-m', mktime(0, 0, 0, $month, 1, $year));

        //해당부서의 출근항목에 해당한 날자수를 구한다.
        return static::query()->where('regDay', 'like', ''.$day.'%')
                            ->where('userId', $userId)
                            ->where('statusId', $type)->count();

    }

    //지정된 월에 해당한 출근항목에 따르는 날자수 구하기
    public static function getAttendDaysOfMonthByAttendType($idStr, $startDate, $endDate)
    {
        if(empty($idStr))
            return array();

        $query = 'SELECT tb_users.id, tb_users.realname, attend.statusId, attend.attendCount, tb_unit.title AS unit, tb_pos.title
                  FROM ( SELECT userId, statusId, COUNT(*) AS attendCount FROM tb_attend_user
                        WHERE (regDay BETWEEN "'.$startDate.'" AND "'.$endDate.'") GROUP BY userId, statusId ) AS attend
                  RIGHT JOIN tb_users ON attend.userId = tb_users.id
                  LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id
                  LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id
                  WHERE  tb_users.status = 1 AND tb_users.id IN ('.$idStr.')
                  ORDER BY tb_unit.orderkey, tb_pos.orderNum, tb_users.realname';
        $result = DB::select($query);
        return $result;
    }

    //지정된 날자에 해당한 출근항목에 따르는 날자수 구하기
    public static function getAttendStateByDate($userId = null, $selDate)
    {
        if(empty($userId))
            $userId = '0';
        return static::query()->select(DB::raw('count(*) as ucount, statusId'))
            ->where('regDay', $selDate)
            ->whereRaw('userId in ('.$userId.')')
            ->groupBy('statusId')
            ->get();
    }

    // 지적된 리용자의 월출근정형을 얻는다.
    public static function getMemberMonthAttend($memberId, $year, $month) {
        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $query = 'SELECT tb_attend_user.regDay, tb_attend_user.memo, tb_attend_type.name FROM tb_attend_user
                    LEFT JOIN tb_attend_type ON tb_attend_user.statusId = tb_attend_type.id
                    WHERE tb_attend_user.userId = '.$memberId.' AND tb_attend_user.regDay LIKE "'.$attendMonth.'-%"';
        $result = DB::select($query);
        return $result;
    }

    // 지적된 리용자의 년출근정형을 얻는다.
    public static function getMemberYearAttend($memberId, $year) {
        $query = 'SELECT tb_attend_user.regDay, tb_attend_user.memo, tb_attend_type.name FROM tb_attend_user
                    LEFT JOIN tb_attend_type ON tb_attend_user.statusId = tb_attend_type.id
                    WHERE tb_attend_user.userId = '.$memberId.' AND tb_attend_user.regDay LIKE "'.$year.'-%"';
        $result = DB::select($query);
        return $result;
    }

}