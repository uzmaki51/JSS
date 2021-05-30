<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/10/19
 * Time: 10:16
 */

namespace App\Models\Convert;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VoyLog extends Model
{
    protected $table = "tbl_voy_log";
    public $timestamps = false;

    public function getYearList($shipId) {
        $yearList = [];
        $shipInfo = self::where('Ship_ID', $shipId)->orderBy('Voy_Date', 'asc')->first();
        if($shipInfo == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($shipInfo->Voy_Date, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
    }
}
