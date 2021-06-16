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
use Illuminate\Support\Facades\Log;
use App\Models\ShipManage\ShipRegister;

class ShipEquipmentRequire extends Model
{
    protected $table = 'tb_ship_equipment_require';

	public function getYearList($shipId) {
        $yearList = [];
        $info = self::where('shipId', $shipId)->first();
        if($info == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($info->request_date, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
    }

	public function getEquipmentList($params) {
		$selector = self::whereRaw(1);
		
		if(isset($params['shipId']) && $params['shipId'] != 0) {
			$selector->where('shipId', $params['shipId']);
		}

		if(isset($params['year']) && $params['year'] != 0) {
			$selector->whereRaw(DB::raw('mid(create_at, 1, 4) like ' . $params['year']));
		}

		if(isset($params['placeType']) && $params['placeType'] != 0) {
			$selector->where('place', $params['placeType']);
		}

		if(isset($params['checkLack']) && $params['checkLack'] != 0) {
			$selector->whereRaw('require_vol < inventory_vol');
		}

		$records = $selector->get();

		return $records;
	}

	public function getDataForDash() {
		$selector = self::whereRaw(1);
		$selector->whereRaw('require_vol < inventory_vol');
		$records = $selector->get();

		$placeList = array(
			'1'		=> '主机(M/E)',
			'2'		=> '辅机(A/E)',
			'3'		=> '锅炉(BLR)',
			'4'		=> '机械',
		);

		$shipReg = new ShipRegister();
		foreach($records as $key => $item) {
			$records[$key]->shipName = $shipReg->getShipNameByIMO($item->shipId);
			$records[$key]->place = $placeList[$item->place];
		}

		return $records;
	}
	
}