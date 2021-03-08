<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/16
 * Time: 13:02
 */
namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'tb_news';
    protected $date = ['deleted_at'];

    public function newsUser()
    {
        return $this->belongsTo('App\User', 'userId');
    }

    public static function getNewsListForTema($temaId) {
        $query = static::query();
        $query->where('temaId', $temaId);
        $list = $query->orderBy('id', 'desc')->paginate(10)->setPath('');
        return $list;
    }

    public static function getHomeNewsListForTema() {
        $list = static::orderBy('id', 'desc')->take(10)->get();
        return $list;
    }

}