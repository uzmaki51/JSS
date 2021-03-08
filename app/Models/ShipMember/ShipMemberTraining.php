<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/25
 * Time: 9:47
 */

namespace App\Models\ShipMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipMemberTraining extends Model
{
    protected $table = 'tb_training_registry';

    public function security(){
        return $this->hasOne('App\Models\ShipMember\SecurityCert', 'id', 'TCP_certID');
    }

}
