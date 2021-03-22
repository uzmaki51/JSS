<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 4/21/2017
 * Time: 22:28
 */

namespace App\Models\Decision;


use App\Models\Operations\AcItem;
use App\Models\ShipManage\ShipRegister;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class DecisionReportAttachment extends Model {
    protected $table = 'tb_decision_report_attachment';

    public function getForDatatable($params, $status = 0) {
	    $user_id = Auth::user()->id;
	    $selector = DB::table($this->table)
		    ->where('state', '!=', REPORT_STATUS_DRAFT)
		    ->select('*');

	    if (isset($params['columns'][1]['search']['value'])
		    && $params['columns'][1]['search']['value'] !== ''
	    ) {
		    $selector->where($this->table . '.name', 'like', '%' . $params['columns'][1]['search']['value'] . '%');
	    }
	    if (isset($params['columns'][2]['search']['value'])
		    && $params['columns'][2]['search']['value'] !== ''
	    ) {
		    $selector->Where(function ($query) use ($params) {
			    $query->where('region_to', $params['columns'][2]['search']['value'])
				    ->orWhere('region_dou', $params['columns'][2]['search']['value']);
		    });
	    }
	    if (isset($params['columns'][3]['search']['value'])
		    && $params['columns'][3]['search']['value'] !== ''
	    ) {
		    $selector->where($this->table . '.detail_addr', 'like', '%' . $params['columns'][3]['search']['value'] . '%');
	    }
	    if (isset($params['columns'][4]['search']['value'])
		    && $params['columns'][4]['search']['value'] !== ''
	    ) {
		    $selector->where($this->table . '.contact_number', 'like', '%' . $params['columns'][4]['search']['value'] . '%');
	    }
	    if (isset($params['columns'][5]['search']['value'])
		    && $params['columns'][5]['search']['value'] !== ''
	    ) {
		    $selector->where($this->table . '.status', $params['columns'][5]['search']['value']);
	    }
	    if (isset($params['columns'][6]['search']['value'])
		    && $params['columns'][6]['search']['value'] !== ''
	    ) {
		    $amountRange = preg_replace('/[\$\,]/', '', $params['columns'][6]['search']['value']);
		    $elements = explode(':', $amountRange);

		    if ($elements[0] != "" || $elements[1] != "") {
			    $elements[0] .= ' 00:00:00';
			    $elements[1] .= ' 23:59:59';
			    $selector->whereBetween($this->table . '.created_at', $elements);
		    }
	    }

	    // number of filtered records
	    $recordsFiltered = $selector->count();

	    // sort
	    foreach ($params['order'] as $order) {
		    $field = $params['columns'][$order['column']]['data'];
		    $selector->orderBy($field, $order['dir']);
	    }

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
		    $shipName = ShipRegister::where('id', $item->shipNo)->first()->shipName_Cn;
		    $reporter = User::where('id', $item->creator)->first()->realname;
		    $profit = ACItem::where('id', $item->profit_type)->first()->AC_Item_Cn;

		    $records[$key]->shipName = $shipName;
		    $records[$key]->realname = $reporter;
		    $records[$key]->profit_type = $profit;

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

    	if(isset($result) || count($result) == 0)
    		return [];

    	return $result;
    }

}