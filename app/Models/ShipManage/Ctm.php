<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/14
 * Time: 7:41
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ctm extends Model
{
    protected $table = 'tb_ctm';

    public function getYearList($shipId) {
        $yearList = [];
        $record = self::where('shipId', $shipId)->orderBy('reg_date', 'desc')->groupBy(DB::raw('mid(reg_date, 1, 4)'))->get();

        foreach($record as $key => $item) {
            $yearList[] = date('Y', strtotime($item->reg_date));
        }

        if(count($yearList) == 0)
            $yearList[] = date('Y');
        else {
            if($yearList[0] < date('Y') )
                $yearList[] = date('Y');
        }

        return $yearList;
    }
}