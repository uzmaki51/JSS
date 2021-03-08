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

class ShipIssaCodeNo extends Model
{
    protected $table = 'tb_issacodeno';
    public $timestamps = false;

    public static function getAllItems($code, $codeNo, $content, $pagenation = 10) {
        $query = static::query()
                ->select('tb_issacodeno.*', 'tb_issacode.Code_Cn', DB::raw('tb_issacode.Code as CodeId'))
                ->join('tb_issacode', 'tb_issacodeno.Code', '=', 'tb_issacode.id');

        if(isset($code))
            $query->where('tb_issacode.id', $code);

        if(isset($codeNo))
            $query->where('tb_issacodeno.CodeNo', 'like', '%'.$codeNo.'%');

        if(isset($content))
            $query->where('tb_issacodeno.Content_Cn', 'like', '%'.$content.'%')
                ->orWhere('tb_issacodeno.Content_En', 'like', '%'.$content.'%');

        if ($pagenation != '')
            $list = $query->orderBy('id')->paginate(10)->setPath('');
        else
            $list = $query->orderBy('CodeNo')->get();
        return $list;
    }

}