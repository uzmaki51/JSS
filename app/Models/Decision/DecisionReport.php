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
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class DecisionReport extends Model {
	protected $table = 'tb_decision_report';
	protected $table_register_ship = 'tb_ship_register';

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
			$records[$key]->profit_type = $profit;
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