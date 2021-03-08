<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/7
 * Time: 18:44
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserInfo extends Model
{
    protected $table = 'tb_users';

    public function loginId(){
        return $this->hasOne('App\User', 'id', 'id');
    }

    public function position(){
        return $this->hasOne('App\Models\Member\Post', 'id', 'pos');
    }

    public function unitName(){
        return $this->hasOne('App\Models\Member\Unit', 'id', 'unit');
    }

    public function isAdmin() {
        $result = $this->query()
                ->select('tb_users.isAdmin')
                ->join('tb_users', 'tb_users.id', '=', 'tb_users.id')
                ->where('tb_users.id', $this['id'])
                ->first();

        if(empty($result)) {
            $result = new \stdClass();
            $result->isAdmin = 0;
        }

        return $result;
    }

    public static function getSimpleUserList($unit = null, $pos = null, $realname = null, $status = null) {
        $query = static::query()->select('tb_users.*')
                        ->leftJoin('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')
                        ->leftJoin('tb_unit', 'tb_users.unit', '=', 'tb_unit.id');

        if(isset($unit))
            $query->where('tb_users.unit', $unit);

        if(isset($pos))
            $query->where('tb_users.pos', $pos);

        if(isset($realname))
            $query->where('tb_users.realname', 'like', '%'.$realname.'%');

        if(isset($status))
            $query->where('tb_users.status', $status);

        $result = $query->orderBy('tb_unit.orderkey')->orderBy('tb_pos.orderNum')->paginate()->setPath('');

        return $result;
    }

    public static function getUserSimpleListByUnit($unit = 0) {
        $query = static::query()
                    ->select('tb_users.realname', 'tb_users.id', 'tb_unit.title', 'tb_pos.title as pos', 'tb_users.releaseDate')
                    ->leftJoin('tb_unit', 'tb_users.unit', '=', 'tb_unit.id')
                    ->leftJoin('tb_pos', 'tb_users.pos', '=', 'tb_pos.id');

        if($unit > 0)
            $query->where('tb_users.unit', $unit);

        $query->orderBy('tb_unit.orderkey')->orderBy('tb_pos.orderNum');

        $result = $query->get();

        return $result;
    }

    // 직속부서의 직원들의 ID를 반점으로 구분하여 얻는다.
    public static function getDirectlyUserList($unitId) {

        $query = 'SELECT GROUP_CONCAT(id) AS idList FROM tb_users WHERE status = 1 AND unit = '.$unitId;

        $result = DB::select($query);

        if(count($result) == 0)
            return null;

        return $result[0]->idList;
    }

    // 부서에 속한 직원들의 id목록을 반점으로 구분하여 얻는다.
    public static function getUserListByUnit($unitId, $page = 0) {
        $start = ($page - 1) * 15;
        $unit = DB::table('tb_unit')->where('id', $unitId)->first();
        if(isset($unit))
            $prefix = $unit->orderkey;
        else {
            $unit = DB::table('tb_unit')->where('parentId', 0)->first();
            if(isset($unit))
                $prefix = $unit->orderkey;
            else
                $prefix = '001';
        }

        if($page > 0) {
            $query = 'SELECT GROUP_CONCAT(user_table.id) AS idList FROM
                          (SELECT id FROM tb_users
                          WHERE tb_users.status = 1 AND unit IN (SELECT id FROM tb_unit WHERE orderkey LIKE "'.$prefix.'%") LIMIT '.$start.', 15) AS user_table';
        } else {
            $query = 'SELECT group_concat(id) AS idList FROM tb_users
                  WHERE tb_users.status = 1 AND unit IN (SELECT id FROM tb_unit WHERE orderkey LIKE "'.$prefix.'%")';
        }

        $result = DB::select($query);

        if(count($result) == 0)
            return null;

        return $result[0]->idList;
    }

    // 부서에 속한 직원들의 총인원수를 얻는다.
    public static function countUserListByUnit($unitId) {
        $unit = DB::table('tb_unit')->where('id', $unitId)->first();
        if(isset($unit))
            $prefix = $unit->orderkey;
        else {
            $unit = DB::table('tb_unit')->where('parentId', 0)->first();
            if(isset($unit))
                $prefix = $unit->orderkey;
            else
                $prefix = '001';
        }

        $query = 'SELECT COUNT(*) AS totalCount FROM tb_users
                  WHERE tb_users.status = 1 AND unit IN (SELECT id FROM tb_unit WHERE orderkey LIKE "'.$prefix.'%")';

        $result = DB::select($query);

        $page = 0;
        if(count($result) > 0) {
            $totalCount = $result[0]->totalCount;
            $page = floor($totalCount / 15);
            $remain = fmod($totalCount, 15);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }

    public static function getUserListOrderByUnit($status = 1, $isParty = -1, $unit = 0) {

        $query = static::query()->leftJoin('tb_unit', 'tb_users.unit', '=', 'tb_unit.id');
        if($status > -1 )
                $query = $query->where('tb_users.status', $status);
        if($isParty > -1)
            $query = $query->where('tb_users.isParty', $isParty);
        if($unit > 0)
            $query = $query->where('tb_users.unit', $unit);
        $query = $query->orderBy('tb_unit.orderkey');
        $result = $query->get();
        return $result;
    }

    // 기업소안의 성원들의 ID를 조건에 따라 얻는다.
    public static function totalMemberIDList($unit, $posId, $ship, $dutyId, $party, $username, $address,
                                             $phone, $status, $birthStart, $birthEnd, $entryStart, $entryEnd, $cardNum) {
        $unitWhere = '';
        $shipWhere = '';

        if(!empty($unit))
            $unitWhere = ' WHERE tb_users.unit = '.$unit;
        if(!empty($ship))
            $shipWhere = ' WHERE tb_ship_member.ShipId = "'.$ship.'"';

        if(!empty($posId)) {
            $unitWhere =  empty($unitWhere) ? ' WHERE tb_users.pos = ' . $posId : $unitWhere .' AND tb_users.pos = ' . $posId;
        }

        if(!empty($dutyId)) {
            $shipWhere =  empty($shipWhere) ? ' WHERE tb_ship_member.Duty = ' . $dutyId : $shipWhere .' AND tb_ship_member.Duty = ' . $dutyId;
        }

        if(!empty($party)) {
            $partyNum = $party * 1 - 1;
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.isParty = '.$partyNum : $unitWhere.' AND tb_users.isParty = '.$partyNum;
            $shipWhere = empty($shipWhere) ? ' WHERE tb_member_social_detail.isParty = '.$partyNum : $shipWhere.' AND tb_member_social_detail.isParty = '.$partyNum;
        }

        if(!empty($username)) {
            $unitWhere =  empty($unitWhere) ? ' WHERE tb_users.realname = "' . $username .'"' : $unitWhere .' AND tb_users.realname = "' . $username .'"';
            $shipWhere =  empty($shipWhere) ? ' WHERE tb_ship_member.realname = "' . $username.'"' : $shipWhere .' AND tb_ship_member.realname = "' . $username .'"';
        }

        if(!empty($address)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.address like "%'.$address.'%"' : $unitWhere.' AND tb_users.address like "%'.$address.'%"';
            $shipWhere = empty($shipWhere) ? ' WHERE tb_ship_member.address like "%'.$address.'%"'  : $shipWhere.' AND tb_ship_member.address like "%'.$address.'%"';
        }

        if(!empty($phone)) {
            $unitWhere =  empty($unitWhere) ? ' WHERE tb_users.phone = "' . $phone .'"' : $unitWhere .' AND tb_users.phone = "' . $phone .'"';
            $shipWhere =  empty($shipWhere) ? ' WHERE tb_ship_member.phone = "' . $phone .'"' : $shipWhere .' AND tb_ship_member.phone = "' . $phone .'"';
        }

        if(!empty($status)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.status = '.$status : $unitWhere.' AND tb_users.status = '.$status;
            $shipWhere = empty($shipWhere) ? ' WHERE tb_ship_member.RegStatus = '.$status : $shipWhere.' AND tb_ship_member.RegStatus = '.$status;
        }

        if(!empty($birthStart)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.birthday >= "'.$birthStart .'"' : $unitWhere.' AND tb_users.birthday >= "'.$birthStart .'"';
            $shipWhere = empty($shipWhere) ? ' WHERE tb_ship_member.birthday >= "'.$birthStart .'"' : $shipWhere.' AND tb_ship_member.birthday >= "'.$birthStart .'"';
        }

        if(!empty($birthEnd)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.birthday <= "'.$birthEnd .'"' : $unitWhere.' AND tb_users.birthday <= "'.$birthEnd .'"';
            $shipWhere = empty($shipWhere) ? ' WHERE tb_ship_member.birthday <= "'.$birthEnd .'"' : $shipWhere.' AND tb_ship_member.birthday <= "'.$birthEnd .'"';
        }

        if(!empty($entryStart)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.entryDate >= "'.$entryStart .'"' : $unitWhere.' AND tb_users.entryDate >= "'.$entryStart .'"';
            $shipWhere = empty($shipWhere) ? ' WHERE tb_member_social_detail.entryDate >= "'.$entryStart .'"' : $shipWhere.' AND tb_member_social_detail.entryDate >= "'.$entryStart .'"';
        }

        if(!empty($entryEnd)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.entryDate <= "'.$entryEnd .'"' : $unitWhere.' AND tb_users.entryDate <= "'.$entryEnd .'"';
            $shipWhere = empty($shipWhere) ? ' WHERE tb_member_social_detail.entryDate <= "'.$entryEnd .'"' : $shipWhere.' AND tb_member_social_detail.entryDate <= "'.$entryEnd .'"';
        }

        if(!empty($cardNum)) {
            $unitWhere =  empty($unitWhere) ? ' WHERE tb_users.cardNum = "' . $cardNum .'"' : $unitWhere .' AND tb_users.cardNum = "' . $cardNum .'"';
            $shipWhere =  empty($shipWhere) ? ' WHERE tb_member_social_detail.cardNum = "' . $cardNum .'"' : $shipWhere .' AND tb_member_social_detail.cardNum = "' . $cardNum .'"';
        }

        $query = 'SELECT GROUP_CONCAT(id) AS memberList, memberType FROM
                    ( SELECT * FROM 
						( SELECT id, memberType, orderkey AS unit, pos FROM 
							( SELECT tb_users.id, 1 AS memberType, tb_unit.orderkey, tb_users.pos FROM tb_users
								LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id
								LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id';
        if(!empty($unitWhere))
            $query .= $unitWhere;
		$query .= ' ORDER BY tb_unit.orderkey, tb_pos.orderNum) AS unit_member_table';
        $query .=' UNION
                    SELECT id, memberType, ShipId AS unit, pos FROM (
                        SELECT tb_ship_member.id, 2 AS memberType, tb_ship_member.ShipId, tb_ship_member.pos FROM tb_ship_member
								LEFT JOIN tb_ship ON tb_ship_member.ShipId = tb_ship.kinc_name
								LEFT JOIN tb_ship_duty ON tb_ship_duty.id = tb_ship_member.Duty
								LEFT JOIN tb_member_social_detail ON tb_member_social_detail.memberId = tb_ship_member.id';
        if(!empty($shipWhere))
            $query .= $shipWhere;
		$query .= ' ORDER BY tb_ship.id, tb_ship_duty.id ) AS ship_member_table
		            ORDER BY memberType, unit, pos) AS total_member)
					AS member_table GROUP BY memberType';

        $result = DB::select($query);
        return $result;
    }

    // 기업소안의 성원들의 인원수를 조건에 따라 얻는다.
    public static function countTotalMemberInfoList($unit, $ship, $party, $status) {
        $unitWhere = '';
        $shipWhere = '';

        if(isset($unit)) {
            $unitWhere = ' WHERE tb_users.unit = ' . $unit;
        }

        if(isset($ship)) {
            $shipWhere = ' WHERE tb_ship_member.ShipId = "' . $ship . '"';
        }

        if(isset($unit) && is_null($ship)) {
            $unitWhere = ' WHERE tb_users.unit = ' . $unit;
            $shipWhere = ' WHERE tb_ship_member.ShipID_organization = 0';
        }

        if(is_null($unit) && isset($ship)) {
            $unitWhere = ' WHERE tb_users.unit = 0';
            $shipWhere = ' WHERE tb_ship_member.ShipID_organization = '.$ship;
        }

        if(isset($party)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.isParty = '.$party : $unitWhere.' AND tb_users.isParty = '.$party;
            $shipWhere = empty($shipWhere) ? ' WHERE tb_member_social_detail.isParty = '.$party : $shipWhere.' AND tb_member_social_detail.isParty = '.$party;
        }

        if(isset($status)) {
            $unitWhere = empty($unitWhere) ? ' WHERE tb_users.status = '.$status : $unitWhere.' AND tb_users.status = '.$status;
            $shipWhere = empty($shipWhere) ? ' WHERE tb_ship_member.RegStatus = '.$status : $shipWhere.' AND tb_ship_member.RegStatus = '.$status;
        }

        $query = 'SELECT COUNT(*) AS totalCount FROM (
                    SELECT tb_unit.title AS unitName, tb_pos.title AS posName, tb_users.realname, tb_users.birthday, tb_users.sex, tb_users.isParty, tb_users.entryDate, tb_users.address, tb_users.phone, tb_users.cardNum, 1 AS memberType FROM tb_users
                        LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id
                        LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id';
        if(!empty($unitWhere))
            $query .= $unitWhere;
        $query .=' UNION
                    SELECT tb_ship.name AS unitName, tb_ship_duty.Duty AS posName, tb_ship_member.realname, tb_ship_member.birthday, tb_ship_member.Sex, tb_member_social_detail.isParty, tb_member_social_detail.entryDate, tb_ship_member.address, tb_ship_member.phone, tb_member_social_detail.cardNum, 2 AS memberType FROM tb_ship_member
                        LEFT JOIN tb_member_social_detail ON tb_ship_member.id = tb_member_social_detail.memberId
                        LEFT JOIN tb_ship ON tb_ship_member.ShipID_organization = tb_ship.id
                        LEFT JOIN tb_ship_duty ON tb_ship_member.pos = tb_ship_duty.id';
        if(!empty($shipWhere))
            $query .= $shipWhere;
        $query .= ') AS total_member';

        $result = DB::select($query);

        $page = 0;
        if(count($result) > 0) {
            $totalCount = $result[0]->totalCount;
            $page = floor($totalCount / 15);
            $remain = fmod($totalCount, 15);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }

    // 기업소에 속한 성원들의 id를 직원과 선원으로 갈라 반점으로 구분된 문지렬로 얻는다.
    public static function enterpriseTotalMemberList($unitId, $shipId, $memberName) {

        $query = 'SELECT GROUP_CONCAT(id) AS idStr, memberType FROM
                        ( SELECT * FROM 
                            ( SELECT id, memberType, orderkey AS unit, pos FROM 
                                ( SELECT tb_users.id, 1 AS memberType, tb_unit.orderkey, tb_users.pos FROM tb_users 
                                  LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id 
                                  LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id
                                  WHERE tb_users.status = 1 ';
        if(!empty($memberName))
            $query .= ' AND tb_users.realname = "'.$memberName .'"';

        if(!empty($unitId))
            $query .= ' AND tb_users.unit = '.$unitId;
        else if(!empty($shipId))
            $query .= ' AND 0 ';

        $query .= ' ORDER BY tb_unit.orderkey, tb_pos.orderNum) AS member_table';

        $query .= ' UNION 
        SELECT id, memberType, ShipId AS unit, Duty FROM
				(SELECT tb_ship_member.id, 2 AS memberType, tb_ship_member.ShipId, tb_ship_member.Duty FROM tb_ship_member
					LEFT JOIN tb_ship ON tb_ship_member.ShipId = tb_ship.kinc_name
					LEFT JOIN tb_ship_duty ON tb_ship_duty.id = tb_ship_member.Duty
		         WHERE tb_ship_member.RegStatus = 1';

        if(isset($memberName))
            $query .= ' AND tb_ship_member.realname = "'.$memberName .'"';

        if(empty($unitId) && !empty($shipId) && ($shipId != 'READY'))
            $query .= ' AND tb_ship_member.ShipId = "'.$shipId.'"';
        else if(!empty($shipId) && ($shipId == 'READY'))
            $query .= ' AND tb_ship.id IS NULL';

        if(!empty($unitId))
            $query .= ' AND 0 ';

        $query .= ' ORDER BY tb_ship.id, tb_ship_duty.id ) as ship_table';

        $query .= ' ORDER BY memberType, unit, pos
		    ) AS total_member ) AS member_table  GROUP BY memberType';

        Log::info($query);

        $result = DB::select($query);
        return $result;
    }

    // 기업소에 속한 성원들의 총인원수를 얻는다.
    public static function countEnterpriseTotalMemberList($unitId, $shipId, $memberName) {
        $query = 'SELECT COUNT(*) as totalCount FROM (
                        SELECT id, 1 AS memberType FROM tb_users WHERE tb_users.status = 1';
        if(isset($unitId))
            $query .= ' AND unit = '.$unitId;
        if(isset($memberName))
            $query .= ' AND realname = "'.$memberName .'"';

        $query .= ' UNION SELECT id, 2 AS memberType FROM tb_ship_member WHERE tb_ship_member.RegStatus = 1';
        if(isset($shipId))
            $query .= ' AND ShipID_organization = "'.$shipId.'"';
        if(isset($memberName))
            $query .= ' AND realname = "'.$memberName .'"';

        $query .= ') AS total_member';

        $result = DB::select($query);

        $page = 0;
        if(count($result) > 0) {
            $totalCount = $result[0]->totalCount;
            $page = floor($totalCount / 15);
            $remain = fmod($totalCount, 15);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }

	public static function getMemberProfilelist($idList) {
		if(empty($idList))
			return array();

		$memberList = array();
		foreach($idList as $list) {
			$memberType = $list->memberType;
			if($memberType == 1)
				$query = 'SELECT tb_users.id, tb_unit.title AS unitName, tb_pos.title AS posName, tb_users.realname, 
							tb_users.birthday, tb_users.sex, tb_users.isParty, tb_users.entryDate, tb_users.address, 
							tb_users.phone, tb_users.cardNum, tb_users.status, 1 AS memberType 
							FROM tb_users 
							LEFT JOIN tb_unit ON tb_users.unit = tb_unit.id 
							LEFT JOIN tb_pos ON tb_users.pos = tb_pos.id 
							WHERE tb_users.id IN ('.$list->memberList.') ORDER BY tb_unit.orderkey, tb_pos.orderNum';
			else if($memberType == 2)
				$query = 'SELECT tb_ship_member.id, tb_ship.name AS unitName, tb_ship_duty.Duty AS posName, tb_ship_member.realname, tb_ship_member.birthday, 
							tb_ship_member.Sex as sex, tb_member_social_detail.isParty, tb_member_social_detail.entryDate, tb_ship_member.address, 
							tb_ship_member.phone, tb_member_social_detail.cardNum, tb_ship_member.RegStatus AS status, 2 AS memberType, IFNULL(tb_ship.id, 100) as orderNum
							FROM tb_ship_member 
							LEFT JOIN tb_member_social_detail ON tb_ship_member.id = tb_member_social_detail.memberId 
							LEFT JOIN tb_ship ON tb_ship_member.ShipId = tb_ship.kinc_name
							LEFT JOIN tb_ship_duty ON tb_ship_member.Duty = tb_ship_duty.id
							WHERE tb_ship_member.id IN ('.$list->memberList.') ORDER BY orderNum, tb_ship_duty.id';
				
			
			$tempList = DB::select($query);
			$memberList = array_merge($memberList, $tempList);
		}

		return $memberList;
	}
}