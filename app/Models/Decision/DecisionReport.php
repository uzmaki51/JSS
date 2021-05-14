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
			$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['book_no'] = $record->book_no == null ? '' : $record->book_no;
			$newArr[$newindex]['datetime'] = $record->create_time;
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = $record->rate == null ? '' : $record->rate;
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
		$year = $params['year'];
        $month = $params['month'];

		if ($year == null) {
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		$report_list_record = BooksList::where('year', $year)->where('month', $month)->first();
        if (!is_null($report_list_record)) {
            return $this->getForSavedBookDatatable($params, $year, $month);
        }

		$selector = DB::table($this->table)
			->orderBy('update_at', 'asc')
			->select('*');

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
			$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['book_no'] = '';
			$newArr[$newindex]['datetime'] = $record->create_at;
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
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
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function getForDatatable($params, $status = null) {
		$user = Auth::user();
		$selector = DB::table($this->table)
			->orderBy('update_at', 'desc')
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
			$selector->where('shipNo', $params['columns'][0]['search']['value']);
		}
		if (isset($params['columns'][1]['search']['value'])
			&& $params['columns'][1]['search']['value'] !== ''
		) {
			$fromDate = str_replace('/', '-', $params['columns'][1]['search']['value']);
			$selector->where('create_at', '>=', $fromDate . ' ' . '00:00:00');
		}
		if (isset($params['columns'][2]['search']['value'])
			&& $params['columns'][2]['search']['value'] !== ''
		) {
			$fromDate = str_replace('/', '-', $params['columns'][2]['search']['value']);
			$selector->where('create_at', '<=', $fromDate . ' ' . '23:59:59');
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
			if(ShipRegister::where('id', $item->shipNo)->first())
				$shipName = ShipRegister::where('id', $item->shipNo)->first()->NickName;
			else
				$shipName = '';
			if(ACItem::where('id', $item->profit_type)->first())
				$profit = ACItem::where('id', $item->profit_type)->first()->AC_Item_Cn;
			else
				$profit = '';
			if(VoyLog::where('id', $item->voyNo)->first())
				$voyName = VoyLog::where('id', $item->voyNo)->first()->CP_ID;
			else
				$voyName = '';
			$reporter = User::where('id', $item->creator)->first()->realname;

			$records[$key]->shipName = $shipName;
			$records[$key]->realname = $reporter;
			$records[$key]->voyNo = $voyName;

		}

		return [
			'draw' => $params['draw']+0,
			'recordsTotal' => DB::table($this->table)->count(),
			'recordsFiltered' => $recordsFiltered,
			'data' => $records,
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
			$attachmentList = DecisionReportAttachment::where('reportId', $params['reportId'])->get();
		}

		return array(
			'list'      => $result,
			'attach'    => $attachmentList
		);
	}

}