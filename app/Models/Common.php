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

class Common extends Model
{
    protected $table = 'tb_decision_report';

    public function generateReportID($report_data) {
		$retVal = false;
		try {
			DB::beginTransaction();
			DB::table($this->table)->lockForUpdate();
			$year = date('Y', strtotime($report_data));
			$count = self::whereRaw(DB::raw('mid(report_date, 1, 4) like ' . $year))->where('state', '!=', REPORT_STATUS_DRAFT)->orderBy('report_id', 'desc')->first();
			
			if($count == null) {
				$count = date('y', strtotime($report_data)) . '0001';
				return $count;
			} else {
				$count = $count->report_id + 1;
			}
			
			$sufix = substr($count, 2, strlen($count) - 1);
			if(intval($sufix) >= 10000) {
				DB::rollback();
				return false;
			}

			$retVal = date('y', strtotime($report_data)) . $sufix;
			
			
			$is_exist = DB::table($this->table)->where('report_id', $retVal)->first();
			if($is_exist != null)
				$retVal = $retVal + 1;
				
			DB::commit();
		} catch(\Exception $e) {
			DB::rollback();
			return $retVal;
		}

		return $retVal;
	}
}