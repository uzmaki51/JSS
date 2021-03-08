<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 4/11/2015
 * Time: 10:55
 */

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table="tb_user_family";
    public $timestamps=false;

    public function relationName(){
        return $this->hasOne('App\Models\Member\FamilyRelease', 'id', 'relation');
    }

    public function sexName(){
        $name = '';
        if($this->sex == 0)
            $name = '남자';
        else
            $name = '녀자';

         return $name;
    }

    public function partyName(){
        $name = '';
        if($this->isParty == 0)
            $name = '';
        else
            $name = '로동당';

        return $name;
    }

}