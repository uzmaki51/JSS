<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 4/21/2017
 * Time: 22:28
 */

namespace App\Models\Decision;


use App\Models\Convert\VoyLog;
use App\Models\Operations\AcItem;
use App\Models\ShipManage\ShipRegister;
use App\Models\Finance\BooksList;
use App\Models\Finance\ReportSave;
use App\Models\Finance\WaterList;
use App\Models\Finance\AccountPersonalInfo;
use App\Models\Decision\DecisionReportAttachment;
use App\Models\Member\Unit;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class DecisionReport extends Model {
	protected $table = 'tb_decision_report';
	protected $table_register_ship = 'tb_ship_register';

	public function getForSavedBookDatatable($params, $year, $month) {
		$selector = ReportSave::where('year', $year)->where('month', $month);
        $recordsFiltered = $selector->count();
		$records = $selector->orderBy('id', 'asc')->get();
		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['id'] = $record->orig_id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = $record->book_no == null ? '' : $record->book_no;
			$newArr[$newindex]['datetime'] = $record->create_time;

			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}
			/*
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$newArr[$newindex]['ship_no'] = $record->shipNo;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
			*/
			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = $record->rate == null ? '' : $record->rate;
			$attachment = DecisionReportAttachment::where('reportId', $record->orig_id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		///////////////// Need to Optimize
		$selector = DB::table($this->table)
			->orderBy('update_at', 'asc')
			->where('state', 1);

		$next_year = $year;
        $next_month = $month;
        if ($month == 12) {
            $next_month = 1;
            $next_year ++;
        }
        else
        {
            $next_month = $month + 1;
        }
		$now = date('Y-m-d', strtotime("$year-$month-1"));
		$next = date('Y-m-d', strtotime("$next_year-$next_month-1"));
		$next = date('Y-m-d', strtotime('-1 day', strtotime($next)));
			
		$selector->where('create_at', '>=', $now)->where('create_at', '<', $next);
		$recordsFiltered = $selector->count();
		$records = $selector->get();
		foreach($records as $index => $record) {
			$report_original_record = ReportSave::where('orig_id', $record->id)->first();
			if (!empty($report_original_record)) continue;

			$newArr[$newindex]['id'] = $record->id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = '';
			$newArr[$newindex]['datetime'] = $record->create_at;
			/*
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$newArr[$newindex]['ship_no'] = $record->shipNo;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
			*/
			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}
			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = '';
			$attachment = DecisionReportAttachment::where('reportId', $record->id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => false,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function getForBookDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		$report_list_record = BooksList::where('year', $year)->where('month', $month)->first();
        if (!is_null($report_list_record)) {
            return $this->getForSavedBookDatatable($params, $year, $month);
        }

		$selector = DB::table($this->table)
			->orderBy('update_at', 'asc')
			->where('state', 1);

		$next_year = $year;
        $next_month = $month;
        if ($month == 12) {
            $next_month = 1;
            $next_year ++;
        }
        else
        {
            $next_month = $month + 1;
        }
		$now = date('Y-m-d', strtotime("$year-$month-1"));
		$next = date('Y-m-d', strtotime("$next_year-$next_month-1"));
		$next = date('Y-m-d', strtotime('-1 day', strtotime($next)));
			
		$selector->where('create_at', '>=', $now)->where('create_at', '<', $next);
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['id'] = $record->id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = '';
			$newArr[$newindex]['datetime'] = $record->create_at;
			
			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}

			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = '';
			$newArr[$newindex]['attachment'] = null;
			$attachment = DecisionReportAttachment::where('reportId', $record->id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function getForDatatable($params, $status = null) {
		$user = Auth::user();
		$selector = DB::table($this->table)
			->orderBy('report_id', 'desc')
			->select('*');

		if($status != null)
			$selector->where('state', '=', $status);
		else
			$selector->where('state', '!=', REPORT_STATUS_DRAFT);

		if($user->isAdmin != SUPER_ADMIN)
			$selector->where('creator', '=', $user->id);

		if (isset($params['columns'][0]['search']['value'])
			&& $params['columns'][0]['search']['value'] !== ''
		) {
			$selector->whereRaw(DB::raw('mid(report_date, 1, 4) like ' . $params['columns'][0]['search']['value']));
		}
		if (isset($params['columns'][1]['search']['value'])
			&& $params['columns'][1]['search']['value'] !== ''
		) {
			$selector->whereRaw(DB::raw('mid(report_date, 6, 7) = ' . sprintf("%'02d\n", $params['columns'][1]['search']['value'])));
		}
		if (isset($params['columns'][2]['search']['value'])
			&& $params['columns'][2]['search']['value'] !== ''
		) {
			$obj = $params['columns'][2]['search']['value'];
			if($obj == 'OBJ') {
				$selector->where('obj_type', OBJECT_TYPE_PERSON);
			} else {
				$selector->where('obj_type', OBJECT_TYPE_SHIP);
				$selector->where('shipNo', $obj);
			}
		}

		// number of filtered records
		$recordsFiltered = $selector->count();

		// offset & limit
		if (!empty($params['start']) && $params['start'] > 0) {
			$selector->skip($params['start']);
		}

		if (!empty($params['length']) && $params['length'] > 0) {
			$selector->take($params['length']);
		}

		// get records
		$records = $selector->get();

		foreach($records as $key => $item) {
			if($item->obj_type == OBJECT_TYPE_SHIP) {
				$shipInfo = ShipRegister::where('IMO_No', $item->shipNo)->first();
				if($shipInfo == null)
					$shipName = '';
				else {
					$shipName = $shipInfo->NickName == '' ? $shipInfo->shipName_En : $shipInfo->NickName;
				}
			} else {
				$personInfo = AccountPersonalInfo::where('id', $item->obj_no)->first();
				if($personInfo == null) 
					$shipName = '';
				else {
					$shipName = $personInfo->person;
				}
			}
				
			$attach = DecisionReportAttachment::where('reportId', $item->id)->first();
			if($attach != null)
				$records[$key]->attach_link = $attach->file_link;
			else
				$records[$key]->attach_link = '';

			if(ACItem::where('id', $item->profit_type)->first())
				$profit = ACItem::where('id', $item->profit_type)->first()->AC_Item_Cn;
			else
				$profit = '';

			$retVal = Unit::where('parentId', '!=', 0)->where('id', $item->depart_id)->first();
			if($retVal == null)
				$records[$key]->depart_name = '';
			else
				$records[$key]->depart_name = $retVal->title;

			$reporter = User::where('id', $item->creator)->first()->realname;

			$records[$key]->shipName = $shipName;
			$records[$key]->realname = $reporter;
		}

		return [
			'draw' => $params['draw']+0,
			'recordsTotal' => DB::table($this->table)->count(),
			'recordsFiltered' => $recordsFiltered,
			'data' => $records,
			'error' => 0,
		];
	}

	public function getForAccountReportDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		if ($month == 0)
			$selector = WaterList::where('year', $year);
		else
			$selector = WaterList::where('year', $year)->where('month', $month);
		
		$selector = $selector->groupBy('account_type')->selectRaw('sum(credit) as credit, sum(debit) as debit, max(register_time) as update_date, currency, account_type, account_name')->groupBy('currency');
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $records,
            'error' => 0,
        ];
	}

	public function getForAccountAnalysisDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == '' ||
			!isset($params['columns'][3]['search']['value']) ||
            $params['columns'][3]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
			$account_type = $params['account'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
			$account_type = $params['columns'][3]['search']['value'];
		}

		if ($month == 0) {
			$selector = WaterList::where('year', $year)->where('account_type',$account_type)->select('*');
		} else {
			$selector = WaterList::where('year', $year)->where('month', $month)->where('account_type',$account_type)->select('*');
		}
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['book_no'] = $record->book_no;
			$newArr[$newindex]['ship_name'] = $record->ship_name;
			//$newArr[$newindex]['datetime'] = $record->create_at;
			$newArr[$newindex]['datetime'] = $record->register_time;
			//$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['currency'] = $record->currency;
			$newArr[$newindex]['credit'] = $record->credit;
			$newArr[$newindex]['debit'] = $record->debit;
			$newArr[$newindex]['rate'] = $record->rate;
			$newArr[$newindex]['pay_type'] = $record->pay_type;
			//$newArr[$newindex]['account_type'] = $record->account_type;
			$newArr[$newindex]['account_name'] = $record->account_name;
			$newArr[$newindex]['report_id'] = $record->report_id;
			$attachment = DecisionReportAttachment::where('reportId', $record->report_id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function getForWaterDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		$selector = WaterList::where('year', $year)->where('month', $month)->select('*');
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['book_no'] = $record->book_no;
			$newArr[$newindex]['ship_name'] = $record->ship_name;
			//$newArr[$newindex]['datetime'] = $record->create_at;
			$newArr[$newindex]['datetime'] = $record->register_time;
			//$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['currency'] = $record->currency;
			$newArr[$newindex]['credit'] = $record->credit;
			$newArr[$newindex]['debit'] = $record->debit;
			$newArr[$newindex]['rate'] = $record->rate;
			$newArr[$newindex]['pay_type'] = $record->pay_type;
			//$newArr[$newindex]['account_type'] = $record->account_type;
			$newArr[$newindex]['account_name'] = $record->account_name;
			$newArr[$newindex]['report_id'] = $record->report_id;
			$attachment = DecisionReportAttachment::where('reportId', $record->report_id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function decideReport($params) {
		$ret = DB::table($this->table)
			->where('id', $params['reportId'])
			->update([
				'state' => $params['decideType']
			]);

		return $ret;
	}

	public function getReportDetail($params) {
		$selector = DB::table($this->table)
			->where('id', $params['reportId'])
			->select('*');
		$result = $selector->first();

		if(isset($params['reportId'])) {
			$attachmentList = DecisionReportAttachment::where('reportId', $params['reportId'])->first();
		}

		return array(
			'list'      => $result,
			'attach'    => $attachmentList
		);
	}

	public function getYearList() {
		$yearList = [];
        $info = self::orderBy('report_date', 'asc')->first();
        if($info == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($info->report_date, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
	}

}