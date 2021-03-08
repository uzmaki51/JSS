<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/10
 * Time: 10:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Menu extends Model
{
    protected $table = 'tb_menu';

    public static function getSubTitleAndController($menu, $userId) {



    }

    public function userMenus()
    {
        return $this->hasOne('App\Users');
    }

}