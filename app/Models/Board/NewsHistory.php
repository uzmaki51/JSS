<?php
/**
 * Created by PhpStorm.
 * User: ChoeMunBong
 * Date: 2017/4/16
 * Time: 13:09
 */
namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;

class NewsHistory extends Model
{
    protected $table = 'tb_news_view_history';
    protected $fillable = ['newsId', 'userId'];
}