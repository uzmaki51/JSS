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

    public function generateReportID() {
		$retVal = false;
		try {
			DB::beginTransaction();
			DB::table($this->table)->lockForUpdate();
			$count = self::count();
			$today = date('y');
			$retVal = $today . sprintf("%'04d\n", $count);
			
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