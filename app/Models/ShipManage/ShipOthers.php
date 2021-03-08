<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017-06-01
 * Time: 오후 3:06
 */
namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipOthers extends Model
{
    protected $table = 'tbl_others';
    protected $primaryKey = 'OthersId';
    public $timestamps = false;
}