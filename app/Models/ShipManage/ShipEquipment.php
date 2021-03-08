<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/16
 * Time: 5:21
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipEquipment extends Model
{
//    use SoftDeletes;
    protected $table            = 'tb_ship_equipment';
	protected $table_diligence  = 'tb_ship_equipment_diligence';
    protected $date = ['deleted_at'];

    public function ship() {
        return $this->hasOne('App\Models\ShipManage\ShipEquipmentRegKind', 'id', 'KindOfEuipmentId');
    }

    public function getShipEquipList($shipId, $params) {
		$selector = DB::table($this->table)
			->leftJoin($this->table_diligence, $this->table_diligence . '.IssaCodeNo', '=', $this->table . '.IssaCodeNo')
			->where($this->table . '.ShipRegNo', "$shipId")
			->where($this->table . '.supplied_at', '!=', "")
			->orderBy('supplied_at', 'desc')
			->orderBy($this->table . '.create_at', 'desc')
			->select([$this->table . '.*', $this->table_diligence . '.status']);

		if(isset($params['params[EquipName_Cn]']) && $params['params[EquipName_Cn]'] != "") {
			$selector->where($this->table . '.Euipment_Cn', 'like', '%' . $params['params[EquipName_Cn]'] . '%');
		}
	    if(isset($params['params[EquipName_En]']) && $params['params[EquipName_En]'] != "") {
		    $selector->where($this->table . '.Euipment_En', 'like', '%' . $params['params[EquipName_En]'] . '%');
	    }
	    if(isset($params['params[EquipType]']) && $params['params[EquipType]'] != "") {
	    	$selector->where($this->table . '.KindOfEuipmentId', '=', $params['params[EquipType]']);
	    }
	    if(isset($params['params[Issa_Code]']) && $params['params[Issa_Code]'] != "") {
		    $selector->where($this->table . '.IssaCodeNo', 'like', '%' . $params['params[Issa_Code]'] . '%');
	    }
	    if(isset($params['params[EquipName_Term]']) && $params['params[EquipName_Term]'] != "") {

	    	$termData = $params['params[EquipName_Term]'];
		    $todayDate = date('Y-m-d H:i:s');
		    $filterMinDate = date('Y-m-d H:i:s');
		    $todayDate = strtotime($todayDate);
		    $todayString = strtotime("-" . $termData . " months", $todayDate);
		    $filterDate = date('Y-m-d H:i:s', $todayString);
		    if($termData != TERM_MONTH_1_IN)
		        $selector->where('supplied_at', '<=', $filterDate);
		    else {
			    $todayString = strtotime("-" . TERM_MONTH_1 . " months", $todayDate);
			    $filterDate = date('Y-m-d H:i:s', $todayString);
			    $selector->where('supplied_at', '<=', $filterMinDate);
			    $selector->where('supplied_at', '>=', $filterDate);
		    }
	    }

	    $result = $selector->get();

	    return $result;

    }

}