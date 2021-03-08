<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/6/2017
 * Time: 6:13 PM
 */
namespace App\Models\Attend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendShip extends Model
{
    protected $table = 'tb_attend_ship';

    // 선원들의 날자에 따르는 출근정형얻기
    public static function getAttendShipMemberListByDate($attendDate, $shipId = null, $posId = null, $memberName = null) {
        $query = 'SELECT d.id, c.shipName_Cn, d.RegStatus, d.statusId, d.memo, d.realname, tb_ship_duty.Duty, tb_attend_type.name AS statusName, tb_users.name as creator, tb_ship.name as shipName
                FROM tb_ship_register c
                LEFT JOIN tb_ship ON c.Shipid = tb_ship.id
                RIGHT JOIN (
                      SELECT b.id, b.ShipId, a.statusId, a.memo, a.creator, b.realname, b.Duty, b.RegStatus
                      FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
                      RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1 ';
        if(!empty($memberName))
            $query .= ' WHERE b.realname = "'.$memberName .'"';

        $query .= ') d
                ON c.RegNo = d.ShipId
                LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
                LEFT JOIN tb_ship_duty ON d.Duty = tb_ship_duty.id
                LEFT JOIN tb_users ON tb_users.id = d.creator';

        $where = ' WHERE d.RegStatus = 1 ';
        if(!empty($shipId) && ($shipId <> 'empty'))
            $where .= ' AND d.ShipId = "'.$shipId.'"';
        else if(!empty($shipId) && ($shipId == 'empty'))
            $where = ' AND (d.ShipId IS NULL OR d.ShipId ="")';

        if(isset($posId))
            $where .= ' AND d.Duty = "'.$posId.'"';
        $query .= $where;
        $query .= ' ORDER BY c.shipName_Cn DESC, tb_ship_duty.id, d.realname';

        $memberList = DB::select($query);

        return $memberList;
    }

    // 날자에 따르는 선원들의 출근정형레코드의 페지수얻기
    public static function countAttendShipMemberListByDate($attendDate, $shipId = null, $memberName = null) {
        $query = 'SELECT COUNT(*) AS totalCount FROM tb_ship_register c
                RIGHT JOIN (
                      SELECT b.id, b.ShipId, a.statusId, a.memo, b.realname, b.Duty
                      FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
                      RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1 ';
        if(isset($memberName))
            $query .= ' WHERE b.realname = "'.$memberName .'"';

        $query .= ') d
                ON c.RegNo = d.ShipId
                LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
                LEFT JOIN tb_ship_duty ON d.Duty = tb_ship_duty.id';

        $where = ' WHERE d.RegStatus = 1 ';
        if(isset($shipId))
            $where .= ' AND c.RegNo = "'.$shipId.'"';

        $query .= $where;

        $countInfo = DB::select($query);
        if(count($countInfo)) {
            $totalCount = $countInfo[0]->totalCount;
            $page = floor($totalCount / 10);
            $remain = fmod($totalCount, 10);
            if($remain > 0)
                $page++;
            return $page;
        }
        return 0;
    }

    // 기구직제에 의한 선원들의 날자에 따르는 출근정형얻기
    public static function getAttendOriginShipMemberListByDate($attendDate, $shipId = null) {
		if(!empty($shipId) && ($shipId != 0))
			$query = 'SELECT d.id, c.name as shipName, d.statusId, d.memo, d.realname, tb_ship_duty.Duty, tb_attend_type.name AS statusName FROM tb_ship c
					RIGHT JOIN (
						  SELECT b.id, b.ShipID_organization, a.statusId, a.memo, b.realname, b.pos
						  FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
						  RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1) d
					ON c.id = d.ShipID_organization
					LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
					LEFT JOIN tb_ship_duty ON d.pos = tb_ship_duty.id
					WHERE c.id = "'.$shipId.'"
					ORDER BY c.name, tb_ship_duty.id';
		else 
			$query = 'SELECT d.id, c.name as shipName, d.statusId, d.memo, d.realname, tb_ship_duty.Duty, tb_attend_type.name AS statusName FROM tb_ship c
					RIGHT JOIN (
						  SELECT b.id, b.ShipID_organization, a.statusId, a.memo, b.realname, b.pos
						  FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
						  RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1) d
					ON c.id = d.ShipID_organization
					LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
					LEFT JOIN tb_ship_duty ON d.pos = tb_ship_duty.id
					WHERE d.ShipID_organization IS NULL OR d.ShipID_organization = 0
					ORDER BY c.name, tb_ship_duty.id';

        $memberList = DB::select($query);

        return $memberList;
    }

    // 날자에 따르는 선원들의 출근정형레코드의 페지수얻기
    public static function countAttendOriginShipMemberListByDate($attendDate, $shipId = null) {
		
		if(!empty($shipId) && ($shipId != 0))
			$query = 'SELECT COUNT(*) as totalCount FROM tb_ship c
					RIGHT JOIN (
						  SELECT b.id, b.ShipID_organization, a.statusId, a.memo, b.realname, b.pos
						  FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
						  RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1) d
					ON c.id = d.ShipID_organization
					LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
					LEFT JOIN tb_ship_duty ON d.pos = tb_ship_duty.id
					WHERE c.id = "'.$shipId.'"';
		else
			$query = 'SELECT COUNT(*) as totalCount FROM tb_ship c
					RIGHT JOIN (
						  SELECT b.id, b.ShipID_organization, a.statusId, a.memo, b.realname, b.pos
						  FROM (SELECT * FROM tb_attend_ship WHERE regDay = "'.$attendDate.'") a
						  RIGHT JOIN tb_ship_member b ON a.memberId = b.id AND b.RegStatus = 1) d
					ON c.id = d.ShipID_organization
					LEFT JOIN tb_attend_type ON d.statusId = tb_attend_type.id
					LEFT JOIN tb_ship_duty ON d.pos = tb_ship_duty.id
					WHERE d.ShipID_organization IS NULL OR d.ShipID_organization = "0"';


        $countInfo = DB::select($query);
        if(count($countInfo)) {
            $totalCount = $countInfo[0]->totalCount;
            $page = floor($totalCount / 10);
            $remain = fmod($totalCount, 10);
            if($remain > 0)
                $page++;
            return $page;
        }
        return 0;
    }

    //지정된 월에 해당한 출근항목에 따르는 날자수 구하기
    public static function getAttendDaysOfMonthByAttendType($idStr, $startDate, $endDate)
    {
        if(empty($idStr))
            return array();

        $result = [];
        $query = 'SELECT tb_ship_member.id, tb_ship_member.realname, attend.statusId, attend.attendCount, tb_ship.name, tb_ship_duty.Duty
                  FROM ( SELECT memberId, statusId, COUNT(*) AS attendCount FROM tb_attend_ship
                        WHERE (regDay BETWEEN "'.$startDate.'" AND "'.$endDate.'") GROUP BY memberId, statusId ) AS attend
                  RIGHT JOIN tb_ship_member ON attend.memberId = tb_ship_member.id AND tb_ship_member.RegStatus = 1
                  LEFT JOIN tb_ship ON tb_ship_member.ShipId = tb_ship.kinc_name
                  LEFT JOIN tb_ship_duty ON tb_ship_member.Duty = tb_ship_duty.id
                  WHERE tb_ship_member.RegStatus = 1 AND tb_ship.id IS NOT NULL AND tb_ship_member.id IN ('.$idStr.')
                  ORDER BY tb_ship.id, tb_ship_duty.id, tb_ship_member.realname';
        $tmpList = DB::select($query);
        foreach($tmpList as $user)
            $result[] = $user;

            $query = 'SELECT tb_ship_member.id, tb_ship_member.realname, attend.statusId, attend.attendCount, tb_ship.name, tb_ship_duty.Duty
                      FROM ( SELECT memberId, statusId, COUNT(*) AS attendCount FROM tb_attend_ship
                            WHERE (regDay BETWEEN "'.$startDate.'" AND "'.$endDate.'") GROUP BY memberId, statusId ) AS attend
                      RIGHT JOIN tb_ship_member ON attend.memberId = tb_ship_member.id AND tb_ship_member.RegStatus = 1
                      LEFT JOIN tb_ship ON tb_ship_member.ShipId = tb_ship.kinc_name
                      LEFT JOIN tb_ship_duty ON tb_ship_member.Duty = tb_ship_duty.id
                      WHERE tb_ship_member.RegStatus = 1 AND tb_ship.id IS NULL AND tb_ship_member.id IN ('.$idStr.')
                      ORDER BY tb_ship.id, tb_ship_duty.id, tb_ship_member.realname';
        $tmpList = DB::select($query);
        foreach($tmpList as $user)
            $result[] = $user;

        return $result;
    }

    public static function registerAttendByShip($selDate, $shipId, $type, $memo) {
        $query = static::query()
            ->leftJoin('tb_ship_member', 'tb_attend_ship.memberId', '=', 'tb_ship_member.id')
            ->where('tb_ship_member.ShipId', $shipId);

    }

    //지정된 날자에 해당한 출근항목에 따르는 날자수 구하기
    public static function getAttendStateByDate($userId = null, $selDate)
    {
        if(empty($userId))
            $userId = '0';
        return static::query()->select(DB::raw('count(*) as ucount, statusId'))
            ->where('regDay', $selDate)
            ->whereRaw('memberId in ('.$userId.')')
            ->groupBy('statusId')
            ->get();
    }

    // 지적된 선원의 월출근정형을 얻는다.
    public static function getMemberMonthAttend($memberId, $year, $month) {
        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $query = 'SELECT tb_attend_ship.regDay, tb_attend_ship.memo, tb_attend_type.name FROM tb_attend_ship
                    LEFT JOIN tb_attend_type ON tb_attend_ship.statusId = tb_attend_type.id
                    WHERE tb_attend_ship.memberId = '.$memberId.' AND tb_attend_ship.regDay LIKE "'.$attendMonth.'-%"';
        $result = DB::select($query);
        return $result;
    }

}