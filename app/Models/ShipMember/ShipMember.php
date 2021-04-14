<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017/4/16
 * Time: 13:09
 */
namespace App\Models\ShipMember;

use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipTechnique\ShipPort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipMember extends Model
{
    protected $table = 'tb_ship_member';

    public function getShipNameAndPos() {
        $shipName = '';
        $dutyName = '';

        $ship = ShipRegister::where('RegNo', $this->ShipId)->first();
        if(!empty($ship)) {
            $shipName = $ship->shipName_Cn;

            $origin = Ship::find($ship->Shipid);
            if(!empty($origin))
                $shipName = $origin->name.' ( '.$ship->shipName_Cn .' )';
        }
        $duty = ShipPosReg::find($this->Duty);
        if(!empty($duty))
            $dutyName = $duty->Duty;

        return $shipName.'호 '.$dutyName;
    }

    public static function getMemberListOrderByShip($status = 1, $isParty = -1, $shipId = 0) {

        $query = static::query()->leftJoin('tb_ship', 'tb_ship_member.shipId', '=', 'tb_ship.id');
        if(isset($status))
            $query = $query->where('tb_ship_member.RegStatus', $status);

        if($isParty > -1)
            $query = $query->where('tb_ship_member.isParty', $isParty);

        if($shipId > 0)
            $query = $query->where('tb_ship_member.shipId', $shipId);

        $query = $query->orderBy('tb_ship.id');
        $result = $query->get();
        return $result;
    }

    public static function getShipMemberListByKeyword($shipId, $shipPos, $name, $state) {
        $query = static::query()
            ->select('tb_ship_member.id', 'tb_ship_member.realname', 'tb_ship_member.Surname', 'tb_ship_member.GivenName',
                'tb_ship_member.birthday', 'tb_ship_member.RegStatus','tb_ship_member.tel','tb_ship_member.phone', 'tb_ship_duty.Duty','tb_ship_duty.Duty_En',
                'tb_ship.name', 'tb_capacity_registry.CapacityID', 'tb_capacity_registry.GMDSSID', 'tb_ship_register.shipName_Cn',
                'tb_ship_register.shipName_En',
                DB::raw('IFNULL(tb_ship.id, 100) as orderNum'))
            ->leftJoin('tb_ship_register', 'tb_ship_member.ShipId', '=', 'tb_ship_register.RegNo')
            ->leftJoin('tb_ship','tb_ship_register.Shipid', '=', 'tb_ship.id')
            ->leftJoin('tb_ship_duty', 'tb_ship_member.Duty', '=', 'tb_ship_duty.id')
            ->leftJoin('tb_capacity_registry', 'tb_ship_member.id', '=', 'tb_capacity_registry.memberId');
        if($state > 0) {
            $status = $state - 1;
            $query = $query->where('tb_ship_member.RegStatus', $status);
        }
        if(isset($shipId) && !empty($shipId))
            $query->where('tb_ship_member.ShipId', $shipId);

        if(isset($name) && !empty($name))
            $query->where('tb_ship_member.realname', 'like', '%'.$name.'%');

        if(isset($shipPos) && !empty($shipPos))
            $query->where('tb_ship_member.Duty', $shipPos);

        $query->orderBy('orderNum')->orderBy('tb_ship_duty.id')->orderBy('tb_ship_member.realname');
        $result = $query->get();

        return $result;
    }

    public static function getTotalMemberList($regShip = null, $bookShip = null, $origShip = null, $regStatus = null) {

        $query = static::query()
             ->select('tb_ship_member.*', 'tb_member_social_detail.isParty', 'tb_member_social_detail.partyNo', 'tb_member_social_detail.partyDate',
                 'tb_member_social_detail.entryDate', 'tb_member_social_detail.fromOrigin', 'tb_member_social_detail.currOrigin', 'tb_member_social_detail.cardNum',
                 'tb_member_social_detail.isParty', 'tb_ship_register.shipName_Cn', 'tb_ship_duty.Duty', 'tb_ship.name')
             ->leftJoin('tb_member_social_detail', 'tb_ship_member.id', '=', 'tb_member_social_detail.memberId')
             ->leftJoin('tb_ship_register', 'tb_ship_member.ShipId', '=', 'tb_ship_register.RegNo')
             ->leftJoin('tb_ship_duty', 'tb_ship_member.Duty', '=', 'tb_ship_duty.id')
             ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id');
        if(!empty($regStatus))
             $query = $query->where('tb_ship_member.RegStatus', $regStatus - 1);

        if(isset($regShip) && !empty($regShip))
            $query = $query->where('tb_ship_member.ShipId', $regShip);

        if(isset($bookShip) && !empty($bookShip))
            $query = $query->where('tb_ship_member.ShipID_Book', $bookShip);

        if(isset($origShip) && !empty($origShip))
            $query = $query->where('tb_ship_member.ShipID_organization', $origShip);

        $result = $query->orderBy('tb_ship_register.RegNo')->orderBy('tb_ship_member.Duty')
            ->orderBy('tb_ship_member.realname')->get();

        return $result;
    }

    public static function getMemberCertList($ship, $pos, $capacity, $month, $page = 0, $perPage = 10) {

        $limit = '';
        if($page != -1) {
            $start = ($page - 1) * $perPage;
            if ($start < 0)
                $start = 0;
            $limit = ' LIMIT ' . $start . ', ' . $perPage;
        }

        $query = 'SELECT tb_ship_member.id AS crewId, tb_ship_member.realname, tb_ship_register.shipName_Cn, tb_ship_member.crewNum, 
				tb_ship_member.scanPath, tb_ship_member.Duty, tb_ship_member.DutyID_Book, tb_ship_duty.Duty AS ship_duty, tb_ship_member.ExpiryDate AS ship_ExpiryDate,
				book_member.shipName_Cn AS book_ship, book_member.book_id, book_member.Duty AS book_duty,
				general_capacity.CapacityID, general_capacity.capacity AS generalCapacity, general_capacity.GOC, general_capacity.ExpiryDate AS general_expireDate,
				GOC_capacity_table.GMDSSID, GOC_capacity_table.capacity AS GOC_capacity, GOC_capacity_table.GMDSS_Scan, GOC_capacity_table.GMD_ExpiryDate,
				COE_capacity_table.COEId, COE_capacity_table.capacity AS COE_capacity, COE_capacity_table.COE_Scan, COE_capacity_table.COE_ExpiryDate,COE_capacity_table.COE_Remarks,
				COE_GOC_capacity_table.COE_GOCId, COE_GOC_capacity_table.capacity AS COE_GOC_capacity, COE_GOC_capacity_table.COE_GOC_Scan, COE_GOC_capacity_table.COE_GOC_ExpiryDate,COE_GOC_capacity_table.COE_GOC_Remarks,
				Watch_capacity_table.WatchID, Watch_capacity_table.capacity AS Watch_capacity, Watch_capacity_table.Watch_Scan, Watch_capacity_table.Watch_ExpiryDate,
				tb_training_registry.TCBExpiryDate, tb_training_registry.TCBScan, tb_training_registry.TCSExpiryDate, tb_training_registry.TCSScan, tb_training_registry.TCPIssuedDate, tb_training_registry.TCPScan, tb_training_registry.TCP_certID,
				tb_training_registry.MCS_ExpiryDate, tb_training_registry.MCSScan, tb_training_registry.SSO_certID, tb_training_registry.SSOExpiryDate, tb_training_registry.SSOScan,
				tb_training_registry.ASD_typeID, tb_training_registry.ASDScan, tb_training_registry.ASDExpiryDate, ifNull(tb_ship_register.id, 100) as orderNum
			FROM tb_ship_member
			LEFT JOIN tb_ship_register ON tb_ship_member.ShipId = tb_ship_register.RegNo
			LEFT JOIN tb_ship_duty ON tb_ship_member.Duty = tb_ship_duty.id
			LEFT JOIN 
				(SELECT tb_ship_member.id, tb_ship_register.shipName_Cn, tb_ship_duty.id AS book_id, tb_ship_duty.Duty FROM tb_ship_member
				LEFT JOIN tb_ship_register ON tb_ship_member.ShipID_Book = tb_ship_register.RegNo
				LEFT JOIN tb_ship_duty ON tb_ship_member.DutyID_Book = tb_ship_duty.id) book_member
				ON tb_ship_member.id = book_member.id
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.CapacityID, tb_capacity_registry.ExpiryDate, tb_capacity_registry.GOC, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.CapacityID = tb_member_capacity.id) general_capacity
				 ON tb_ship_member.id = general_capacity.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.GMDSSID, tb_capacity_registry.GMD_ExpiryDate, tb_capacity_registry.GMDSS_Scan, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.GMDSSID = tb_member_capacity.id) GOC_capacity_table
				 ON tb_ship_member.id = GOC_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.COEId, tb_capacity_registry.COE_ExpiryDate, tb_capacity_registry.COE_Scan, tb_member_capacity.capacity, tb_capacity_registry.COE_Remarks FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.COEId = tb_member_capacity.id) COE_capacity_table
				 ON tb_ship_member.id = COE_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.COE_GOCId, tb_capacity_registry.COE_GOC_ExpiryDate, tb_capacity_registry.COE_GOC_Scan, tb_member_capacity.capacity, tb_capacity_registry.COE_GOC_Remarks FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.COE_GOCId = tb_member_capacity.id) COE_GOC_capacity_table
				 ON tb_ship_member.id = COE_GOC_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.WatchID, tb_capacity_registry.Watch_Scan, tb_capacity_registry.Watch_ExpiryDate, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.WatchID = tb_member_capacity.id) Watch_capacity_table
				 ON tb_ship_member.id = Watch_capacity_table.memberId
			LEFT JOIN tb_training_registry ON tb_ship_member.id = tb_training_registry.memberId	WHERE tb_ship_member.RegStatus = 1 ';

        if(!empty($ship))
            $query .= ' AND tb_ship_member.ShipId = "'.$ship.'"';
        if(!empty($pos))
            $query .= ' AND tb_ship_member.Duty = '. $pos;
        if(!empty($capacity)) {
            $query .= ' AND ((general_capacity.CapacityID = '.$capacity.') OR (GOC_capacity_table.GMDSSID = '.$capacity.') OR 
					(COE_capacity_table.COEId = '.$capacity.') OR (COE_GOC_capacity_table.COE_GOCId = '.$capacity.') OR 
					(Watch_capacity_table.WatchID = '.$capacity.'))';
		}

		if(!empty($month)) {

			$date = new \DateTime();
            $day = $month * 30;
            $date->modify("+$day day");
            $expireDate = $date->format('Y-m-d');

            $query .= 'AND (((general_capacity.ExpiryDate <> "") AND (general_capacity.ExpiryDate IS NOT NULL) AND (general_capacity.ExpiryDate < "'.$expireDate.'")) OR
			((tb_ship_member.ExpiryDate <> "") AND (tb_ship_member.ExpiryDate IS NOT NULL) AND (tb_ship_member.ExpiryDate < "'.$expireDate.'")) OR
			((GOC_capacity_table.GMD_ExpiryDate <> "") AND (GOC_capacity_table.GMD_ExpiryDate IS NOT NULL) AND (GOC_capacity_table.GMD_ExpiryDate < "'.$expireDate.'")) OR
			((COE_capacity_table.COE_ExpiryDate <> "") AND (COE_capacity_table.COE_ExpiryDate IS NOT NULL) AND (COE_capacity_table.COE_ExpiryDate < "'.$expireDate.'")) OR
			((COE_GOC_capacity_table.COE_GOC_ExpiryDate <> "") AND (COE_GOC_capacity_table.COE_GOC_ExpiryDate IS NOT NULL) AND (COE_GOC_capacity_table.COE_GOC_ExpiryDate < "'.$expireDate.'")) OR
			((Watch_capacity_table.Watch_ExpiryDate <> "") AND (Watch_capacity_table.Watch_ExpiryDate IS NOT NULL) AND (Watch_capacity_table.Watch_ExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.TCBExpiryDate <> "") AND (tb_training_registry.TCBExpiryDate IS NOT NULL) AND (tb_training_registry.TCBExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.TCSExpiryDate <> "") AND (tb_training_registry.TCSExpiryDate IS NOT NULL) AND (tb_training_registry.TCSExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.MCS_ExpiryDate <> "") AND (tb_training_registry.MCS_ExpiryDate IS NOT NULL) AND (tb_training_registry.MCS_ExpiryDate < "'.$expireDate.'")))';
		}

		$query .= ' ORDER BY orderNum, tb_ship_duty.id, tb_ship_member.realname '.$limit;
		
        $result = DB::select($query);

        return $result;
    }

    public static function countMemberCertList($ship, $pos, $capacity, $month, $perPage = 10) {

        $query = 'SELECT COUNT(*) as totalCount FROM tb_ship_member
			LEFT JOIN tb_ship_register ON tb_ship_member.ShipId = tb_ship_register.RegNo
			LEFT JOIN tb_ship_duty ON tb_ship_member.Duty = tb_ship_duty.id
			LEFT JOIN 
				(SELECT tb_ship_member.id, tb_ship_register.shipName_Cn, tb_ship_duty.Duty FROM tb_ship_member
				LEFT JOIN tb_ship_register ON tb_ship_member.ShipID_Book = tb_ship_register.RegNo
				LEFT JOIN tb_ship_duty ON tb_ship_member.DutyID_Book = tb_ship_duty.id) book_member
				ON tb_ship_member.id = book_member.id
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.CapacityID, tb_capacity_registry.ExpiryDate, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.CapacityID = tb_member_capacity.id) general_capacity
				 ON tb_ship_member.id = general_capacity.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.GMDSSID, tb_capacity_registry.GMD_ExpiryDate, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.GMDSSID = tb_member_capacity.id) GOC_capacity_table
				 ON tb_ship_member.id = GOC_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.COEId, tb_capacity_registry.COE_ExpiryDate, tb_member_capacity.capacity, tb_capacity_registry.COE_Remarks FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.COEId = tb_member_capacity.id) COE_capacity_table
				 ON tb_ship_member.id = COE_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.COE_GOCId, tb_capacity_registry.COE_GOC_ExpiryDate, tb_member_capacity.capacity, tb_capacity_registry.COE_GOC_Remarks FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.COE_GOCId = tb_member_capacity.id) COE_GOC_capacity_table
				 ON tb_ship_member.id = COE_GOC_capacity_table.memberId
			LEFT JOIN 
				(SELECT tb_capacity_registry.memberId, tb_capacity_registry.WatchID, tb_capacity_registry.Watch_ExpiryDate, tb_member_capacity.capacity FROM tb_capacity_registry
				 INNER JOIN tb_member_capacity ON tb_capacity_registry.WatchID = tb_member_capacity.id) Watch_capacity_table
				 ON tb_ship_member.id = Watch_capacity_table.memberId
			LEFT JOIN tb_training_registry ON tb_ship_member.id = tb_training_registry.memberId	WHERE tb_ship_member.RegStatus = 1 ';

        if(!empty($ship))
            $query .= ' AND tb_ship_member.ShipId = "'.$ship.'"';
        if(!empty($pos))
            $query .= ' AND tb_ship_member.Duty = '. $pos;
        if(!empty($capacity)) {
            $query .= ' AND ((general_capacity.CapacityID = '.$capacity.') OR (GOC_capacity_table.GMDSSID = '.$capacity.') OR 
					(COE_capacity_table.COEId = '.$capacity.') OR (COE_GOC_capacity_table.COE_GOCId = '.$capacity.') OR 
					(Watch_capacity_table.WatchID = '.$capacity.'))';
		}

		if(!empty($month)) {

			$date = new \DateTime();
            $day = $month * 30;
            $date->modify("+$day day");
            $expireDate = $date->format('Y-m-d');

			$query .= 'AND (((general_capacity.ExpiryDate <> "") AND (general_capacity.ExpiryDate IS NOT NULL) AND (general_capacity.ExpiryDate < "'.$expireDate.'")) OR
			((GOC_capacity_table.GMD_ExpiryDate <> "") AND (GOC_capacity_table.GMD_ExpiryDate IS NOT NULL) AND (GOC_capacity_table.GMD_ExpiryDate < "'.$expireDate.'")) OR
			((COE_capacity_table.COE_ExpiryDate <> "") AND (COE_capacity_table.COE_ExpiryDate IS NOT NULL) AND (COE_capacity_table.COE_ExpiryDate < "'.$expireDate.'")) OR
			((COE_GOC_capacity_table.COE_GOC_ExpiryDate <> "") AND (COE_GOC_capacity_table.COE_GOC_ExpiryDate IS NOT NULL) AND (COE_GOC_capacity_table.COE_GOC_ExpiryDate < "'.$expireDate.'")) OR
			((Watch_capacity_table.Watch_ExpiryDate <> "") AND (Watch_capacity_table.Watch_ExpiryDate IS NOT NULL) AND (Watch_capacity_table.Watch_ExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.TCBExpiryDate <> "") AND (tb_training_registry.TCBExpiryDate IS NOT NULL) AND (tb_training_registry.TCBExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.TCSExpiryDate <> "") AND (tb_training_registry.TCSExpiryDate IS NOT NULL) AND (tb_training_registry.TCSExpiryDate < "'.$expireDate.'")) OR
			((tb_training_registry.MCS_ExpiryDate <> "") AND (tb_training_registry.MCS_ExpiryDate IS NOT NULL) AND (tb_training_registry.MCS_ExpiryDate < "'.$expireDate.'")))';
		}

        $result = DB::select($query);

        $page = 0;
        if(count($result) > 0) {
            $totalCount = $result[0]->totalCount;
            $page = floor($totalCount / $perPage);
            $remain = fmod($totalCount, $perPage);
            if($remain > 0)
                $page++;
            return $page;

        }
        return $page;
    }


	public static function getMemberSimpleInfo($shipReg = null, $pagenation = 1000) {
        $query = static::query()->select('tb_ship_member.id', 'tb_ship_member.realname', 'tb_ship_register.shipName_Cn', 'tb_ship_duty.Duty', 'tb_member_capacity.Capacity')
            ->leftJoin('tb_ship_register', 'tb_ship_member.ShipId', '=', 'tb_ship_register.RegNo')
            ->leftJoin('tb_ship_duty', 'tb_ship_member.Duty', '=', 'tb_ship_duty.id')
            ->leftJoin('tb_member_capacity', 'tb_ship_member.QualificationClass', '=', 'tb_member_capacity.id')
            ->where('tb_ship_member.RegStatus', '1');
        if(isset($shipReg))
            $query = $query->where('tb_ship_member.ShipId', $shipReg);

        $query = $query->orderBy('tb_ship_register.id', 'desc')->orderBy('tb_ship_duty.id');
        $result = $query->paginate($pagenation);
        return $result;

    }

    public static function getMemberListByCommar($shipId = null) {
		if(!empty($shipId))
			$query = 'SELECT GROUP_CONCAT(id) AS shipMember FROM tb_ship_member WHERE tb_ship_member.RegStatus = 1 AND ShipId = "'.$shipId.'"';
		else 
			$query = 'SELECT GROUP_CONCAT(id) AS shipMember FROM tb_ship_member WHERE tb_ship_member.RegStatus = 1 AND (ShipId IS NULL OR ShipId = "" OR ShipId="(READY)")';
        $result = DB::select($query);
        if(count($result))
            $result = $result[0]->shipMember;
        else
            $result = '';

        return $result;

    }
    public function getForCertDatatable($params) {
        $selector = null;
        $records = [];
        $recordsFiltered = 0;
        if (isset($params['columns'][1]['search']['value'])
            //&& $params['columns'][1]['search']['value'] !== ''
           && (!isset($params['type']))
        ) {
            $selector = DB::table($this->table)->select('*');
            $selector->where('realname', 'like', '%' . $params['columns'][1]['search']['value'] . '%');
            $records = $selector->orderBy('id', 'desc')->limit(1)->get();
            $recordsFiltered = $selector->count();
        }

        if (isset($params['columns'][2]['search']['value'])
            && $params['columns'][2]['search']['value'] !== ''
        ) {
            $selector = DB::table($this->table)->select('*');
            $selector->where('ShipId', $params['columns'][2]['search']['value']);
            $records = $selector->orderBy('DutyID_Book')->get();
            $recordsFiltered = $selector->count();
        }
        
        $newArr = [];
        $newindex = 0;
        foreach($records as $index => $record) {
            if ($record->PassportNo == $record->crewNum) {
                $record->crewNum = "";
            }
            $newArr[$newindex]['no'] = $record->id;
            $newArr[$newindex]['name'] = $record->realname;
            $rank = ShipPosition::find($record->DutyID_Book);
            $newArr[$newindex]['rank'] = '';
            if(!empty($rank)) $newArr[$newindex]['rank'] = $rank->Duty_En;
            $newArr[$newindex]['nationality'] = $record->Nationality;
            $newArr[$newindex]['cert-id'] = $record->CertNo;
            $newArr[$newindex]['birth-and-place'] = $record->birthday;
            $newArr[$newindex]['date-and-embarkation'] = $record->DateOnboard;
            $newArr[$newindex]['bookno-expire'] = $record->crewNum;
            $newArr[$newindex]['passport-expire'] = $record->PassportNo;
            $newindex ++;
            $newArr[$newindex]['no'] = $record->id;
            $newArr[$newindex]['name'] = $record->GivenName;
            if(!empty($rank)) $newArr[$newindex]['rank'] = $rank->Duty_En;
            $newArr[$newindex]['nationality'] = $record->Nationality;
            $newArr[$newindex]['cert-id'] = $record->CertNo;
            $newArr[$newindex]['birth-and-place'] = $record->BirthPlace;
            $newArr[$newindex]['date-and-embarkation'] = '-';
            $newArr[$newindex]['bookno-expire'] = $record->ExpiryDate;
            $newArr[$newindex]['passport-expire'] = $record->PassportExpiryDate;
            $newindex ++;
        }
        return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $newArr,
            'error' => 0,
        ];
    }

    public function getForDatatable($params) {
        $selector = null;
        $records = [];
        $recordsFiltered = 0;
        if (isset($params['columns'][1]['search']['value'])
            //&& $params['columns'][1]['search']['value'] !== ''
           && (!isset($params['type']))
        ) {
            $selector = DB::table($this->table)->select('*');
            $selector->where('realname', 'like', '%' . $params['columns'][1]['search']['value'] . '%');
            $records = $selector->orderBy('id', 'desc')->limit(1)->get();
            $recordsFiltered = $selector->count();
        }

        if (isset($params['columns'][2]['search']['value'])
            && $params['columns'][2]['search']['value'] !== ''
        ) {
            $selector = DB::table($this->table)->select('*');
            $selector->where('ShipId', $params['columns'][2]['search']['value']);
            $records = $selector->orderBy('DutyID_Book')->get();
            $recordsFiltered = $selector->count();
        }

        if (isset($params['columns'][3]['search']['value'])
            && $selector !== null
        ) {
            if ($params['columns'][3]['search']['value'] == 'true')
            {
                $selector->where('DateOnboard', '!=', 'null');
                $records = $selector->orderBy('DutyID_Book')->get();
                $recordsFiltered = $selector->count();
            }
            else if ($params['columns'][3]['search']['value'] == 'false')
            {
                $selector->where('DateOnboard', null);
                $records = $selector->orderBy('DutyID_Book')->get();
                $recordsFiltered = $selector->count();
            }
            else
            {
                $records = $selector->orderBy('DutyID_Book')->get();
                $recordsFiltered = $selector->count();
            }
        }
        
        $newArr = [];
        $newindex = 0;
        foreach($records as $index => $record) {
            if ($record->PassportNo == $record->crewNum) {
                $record->crewNum = "";
            }
            $newArr[$newindex]['no'] = $record->id;
            $newArr[$newindex]['name'] = $record->realname;
            $rank = ShipPosition::find($record->DutyID_Book);
            $newArr[$newindex]['rank'] = '&nbsp;';
            if(!empty($rank) && $rank != null) $newArr[$newindex]['rank'] = $rank->Abb;
            $newArr[$newindex]['nationality'] = $record->Nationality;
            $newArr[$newindex]['cert-id'] = $record->CertNo;
            $newArr[$newindex]['birth-and-place'] = $record->birthday;
            $newArr[$newindex]['date-and-embarkation'] = $record->DateOnboard;
            $newArr[$newindex]['bookno-expire'] = $record->crewNum;
            $newArr[$newindex]['passport-expire'] = $record->PassportNo;
            $newindex ++;
            $newArr[$newindex]['no'] = $record->id;
            $newArr[$newindex]['name'] = $record->GivenName;
            $newArr[$newindex]['rank'] = '&nbsp;';
            if(!empty($rank) && $rank != null) $newArr[$newindex]['rank'] = $rank->Abb;
            $newArr[$newindex]['nationality'] = $record->Nationality;
            $newArr[$newindex]['cert-id'] = $record->CertNo;
            $newArr[$newindex]['birth-and-place'] = $record->BirthPlace;

            $port = ShipPort::find($record->PortID_Book);
            $newArr[$newindex]['date-and-embarkation'] = '&nbsp;';
            if(!empty($port) && $port != null) $newArr[$newindex]['date-and-embarkation'] = $port->Port_Cn;
            $newArr[$newindex]['bookno-expire'] = $record->ExpiryDate;
            $newArr[$newindex]['passport-expire'] = $record->PassportExpiryDate;
            $newindex ++;
        }
        return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $newArr,
            'error' => 0,
        ];
    }

}