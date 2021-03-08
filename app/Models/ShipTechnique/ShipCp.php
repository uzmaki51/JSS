<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 5/24/2017
 * Time: 9:46 PM
 */

namespace App\Models\ShipTechnique;


use Illuminate\Database\Eloquent\Model;

class ShipCp extends Model
{
    protected $table='tbl_cp';

    public static function getVoyListByShipId($shipId)
    {
        $result=static::query()
            ->select('id', 'Voy_No','CP_No')
            ->where('Ship_ID','=',$shipId)
            ->groupBy('Voy_No')
            ->orderby('id', 'dsc')
            ->get();
        return $result;
    }
}