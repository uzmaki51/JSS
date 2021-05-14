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
use Session;

class DecisionReportAttachment extends Model {
    protected $table = 'tb_decision_report_attachment';

    public function updateAttach($reportId, $fileList) {

	    foreach($fileList as $key => $item) {
		    $ret = DB::table($this->table)
			    ->insert([
				    'reportId'  => $reportId,
				    'file_name' => $item[0],
				    'file_url'  => $item[1],
					'file_link'  => $item[2],
				    'index'     => $key
			    ]);
	    }

	    return true;
    }

    public function deleteRecord($id) {
            $selector = DB::table($this->table)
		        ->where('id', '=', $id)
		        ->select('*');
    	$result = $selector->first();

        if(file_exists($result->file_url))
            @unlink($result->file_url);

		$ret = DB::table($this->table)
            ->where('id', $id)
            ->delete();

		return true;
    }
}