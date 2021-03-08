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
}
