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

class NewsTema extends Model
{
    use SoftDeletes;

    protected $table = 'tb_news_tema';
    protected $fillable = ['temaId', 'tema', 'create_at'];
    protected $date = ['deleted_at'];

    public static function getNewsTemaList($keyword = null) {

        $query = static::query()
                ->select('tb_news_tema.id', 'tb_news_tema.tema', 'tb_news_tema.descript', 'tb_news_tema.create_at')
                ->leftJoin('tb_news','tb_news_tema.id', '=', 'tb_news.temaId');
        if(isset($keyword))
            $query = $query->where('tb_news_tema.tema', 'like', '%'.$keyword.'%');

        $temaList = $query
                ->groupBy('tb_news_tema.id')
                ->orderBy('tb_news.id', 'desc')
                ->paginate();

        if(count($temaList) > 0) {
            foreach($temaList as $tema) {
                $temaId = $tema['id'];
                $temaObj = DB::table('tb_news')
                            ->select(DB::raw('COUNT(*) as newsCount, SUM(response) AS response, MAX(update_at) AS updateTime'))
                            ->where('temaId', $temaId)
                            ->first();
                if($temaObj) {
                    $tema['news'] = $temaObj->newsCount;
                    $createDate = $tema['create_at'];
                    $tema['create'] = Util::convertDate($createDate);

                    if($temaObj->newsCount == 0) {
                        $tema['response'] = 0;
                        $tema['update'] = '无消息';
                    } else {
                        $tema['response'] = $temaObj->response;
                        $tema['update'] = convert_datetime($temaObj->updateTime);
                    }
                }
            }
        }

        if(isset($keyword))
            $temaList->appends(['keyword'=>$keyword]);

        return $temaList;
    }
}