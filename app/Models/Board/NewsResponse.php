<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017/4/16
 * Time: 13:09
 */
namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Util;

class NewsResponse extends Model
{
    use SoftDeletes;

    protected $table = 'tb_news_response';
    protected $fillable = ['newsId', 'content', 'userId'];
    protected $date = ['deleted_at'];

    public function responseUser()
    {
        return $this->belongsTo('App\User', 'userId');
    }

}