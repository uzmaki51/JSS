<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/20
 * Time: 9:34
 */

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Member\Post;
use App\Models\Member\Unit;
use App\Models\UserInfo;
use App\Models\Decision\DecEnvironment;

use App\User;
use DB;
use DateTime;
use Illuminate\Support\Facades\Config;


class Util extends Controller
{
    // 2017-04-10 형의 날자형문자렬을 2017/04/10으로 변환한다.
    public static function convertDate($date)
    {
        if (strlen($date) > 10)
            $date = substr($date, 0, 10);
        $createDate = str_replace('-', '/', $date);
        return $createDate;
    }

    public static function convertDateStr($dateStr)
    {

        $compDate = DateTime::createFromFormat('Y-m-d H:i:s', $dateStr);
        $returnStr = date_format($compDate, "Y年m月d日 H点i分");

        return $returnStr;
    }

    public static function makeUploadFileName()
    {

        $datetime = date('YmdHis');
        $datetime = $datetime . rand(1111, 9999);
        return $datetime;
    }

    public static function caclulateTimeInteval($dateStr)
    {

        $nowstr = date('Y-m-d');
        $returnStr = '';

        $compDate = DateTime::createFromFormat('Y-m-d H:i:s', $dateStr);
        $todayDate = DateTime::createFromFormat('Y-m-d', $nowstr);

        $intervalToday = $todayDate->diff($compDate);
        if ($intervalToday->d == 0) {  // 오늘 발표한 시간
            if ($intervalToday->h > 0)
                $returnStr = $intervalToday->h . '시간전';
            else if ($intervalToday->i > 0)
                $returnStr = $intervalToday->i . '분전';
            else
                $returnStr = $intervalToday->s . '초전';
        } else if ($intervalToday->d == 1) { // 어제 발표한 시간
            $returnStr = '어제 ' . $compDate->format('H:i');
        } else {
            $returnStr = static::convertDate($dateStr);
        }

        return $returnStr;
    }

    public static function loadUnit()
    {
        $GLOBALS['selMenu'] = 10;
        $GLOBALS['submenu'] = 0;

        $doc = new \DOMDocument('1.0');
        $root = $doc->createElement('ul');
        $root->setAttribute("id", "tree");
        $root->setIdAttribute("id", TRUE);
        $root->setAttribute("class", "filetree treeview-famfamfam treeview");
        $parent = $doc->appendChild($root);
        $units = new Unit;
        foreach ($units->units() as $unit) {
            $child = $doc->createElement("li");
            $child->setAttribute("id", $unit->orderkey);
            $child->setIdAttribute("id", TRUE);
            $span = $doc->createElement("span");
            $span->setAttribute("class", "blue icon-folder-open");
            $text = $doc->createTextNode($unit->title);
            $span->appendChild($text);
            $child->appendChild($span);
            $keylen = strlen($unit->orderkey);
            $parent_id = "";
            if ($keylen > 3) $parent_id = substr($unit->orderkey, 0, $keylen - 3);
            if ($parent_id == "") {
                $parent->appendChild($child);//제일 웃단위인경우
            } else {
                $child = $doc->createElement("li");
                $child->setAttribute("id", $unit->orderkey);
                $child->setIdAttribute("id", TRUE);
                $span = $doc->createElement("span");
                $span->setAttribute("class", "icon-hdd blue");
                $text = $doc->createTextNode($unit->title);
                $span->appendChild($text);
                $child->appendChild($span);
                $cur_parent = $doc->getElementById($parent_id);
                if ($cur_parent == null) continue;
                if ($cur_parent->getElementsByTagName("ul")->length == 0) {
                    $cur_parent->getElementsByTagName("span")->item(0)->setAttribute("class", "icon-folder-close orange");
                    $tmp = $doc->createElement("ul");
                    $prev_parent = $cur_parent->appendChild($tmp);
                    $cur_parent->setAttribute("class", "folder");
                    $cur_parent->getElementsByTagName("span")->item(0)->setAttribute("class", "icon-folder-open blue");

                } else {//첫번째 자식이 추가된 경우
                    $prev_parent = $cur_parent->getElementsByTagName("ul")->item(0);
                }
                $prev_parent->appendChild($child);
            }
        }
        $str = $doc->saveHTML();
        return $str;
    }

    public static function loadMember($type)
    {
        $doc = new \DOMDocument('1.0');
        $root = $doc->createElement('ul');
        $root->setAttribute("id", "tree");
        $root->setIdAttribute("id", TRUE);
        $root->setAttribute("class", "filetree treeview-famfamfam treeview");
        $parent = $doc->appendChild($root);

        $units = new Unit;

        foreach ($units->units() as $unit) {
            $child = $doc->createElement("li");
            $child->setAttribute("class", "folder");
            $child->setAttribute("id", $unit->orderkey);
            $child->setIdAttribute("id", TRUE);
            $span = $doc->createElement("span");
            $span->setAttribute("class", "blue icon-folder-open");
            $text = $doc->createTextNode($unit->title);
            $span->appendChild($text);
            $child->appendChild($span);
            $keylen = strlen($unit->orderkey);
            $parent_id = "";
            if ($keylen > 3) $parent_id = substr($unit->orderkey, 0, $keylen - 3);
            if ($parent_id == "") {
                $parent->appendChild($child);//제일 웃단위인경우
            } else {
                $cur_parent = $doc->getElementById($parent_id);
                if ($cur_parent == null) continue;
                if ($cur_parent->getElementsByTagName("ul")->length == 0) {
                    $tmp = $doc->createElement("ul");
                    $prev_parent = $cur_parent->appendChild($tmp);

                } else {//첫번째 자식이 추가된 경우
                    $prev_parent = $cur_parent->getElementsByTagName("ul")->item(0);
                }
                $prev_parent->appendChild($child);
            }

            $tmp = $doc->createElement("ul");
            $prev_parent = $child->appendChild($tmp);
            $users = UserInfo::select('tb_users.*')->leftJoin('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')->where('tb_users.status', 1)->where('tb_users.unit', '=', $unit->id)->orderBy('tb_pos.orderNum')->get();
            foreach ($users as $user) {
                $child = $doc->createElement("li");
                $child->setAttribute("id", $user->id);
                $child->setIdAttribute("id", TRUE);
                $chk = $doc->createElement("input");
                $chk->setAttribute('type', $type);
                if ($type == 'radio') {
                    $chk->setAttribute('name', 'radio');
                }
                $chk->setAttribute('class', 'chkUser');
                $child->appendChild($chk);
                $span = $doc->createElement("span");
                $span->setAttribute("class", "blue");
                $span->setAttribute("style", "padding-left:5px");
                $text = $doc->createTextNode($user->realname);
                $span->appendChild($text);
                $child->appendChild($span);
                $prev_parent->appendChild($child);
            }
        }
        $str = $doc->saveHTML();
        return $str;
    }

    public static function getUserinfoById($id)
    {
        $user_profile = User::find($id);
        if(is_null($user_profile))
            return $user_profile;

        $posId = $user_profile['pos'];
        $unitId = $user_profile['unit'];

        $name = $user_profile['realname'];
        $unit = Unit::find($unitId);
        $unitTitle = '';
        if (!is_null($unit))
            $unitTitle = $unit['title'];
        $pos = Post::find($posId);
        $posTitle = '';
        if (!is_null($pos))
            $posTitle = $pos['title'];

        $decideEnv = DecEnvironment::find($id);
        if (is_null($decideEnv) || empty($decideEnv['signPath']))
            $stamp = 'default.png';
        else
            $stamp = $decideEnv['signPath'];

        $result = array();
        $result['name'] = $name;
        $result['pos'] = $posTitle;
        $result['unit'] = $unitTitle;
        $result['stamp'] = $stamp;
        $result['isAdmin'] = $user_profile->isAdmin()->isAdmin;
        $result['id'] = $id;
        return $result;
    }

    public static function getEnterpriseUnitId() {

        $unit = Unit::where('parentId', 0)->first();
        if(is_null($unit))
            $unitId = 0;
        else
            $unitId = $unit['id'];

        return $unitId;
    }

    public static function makeForthDigital($num) {
        $numStr = '';
        if ($num < 10)
            $numStr = '000' . $num;
        else if ($num < 100)
            $numStr = '00' . $num;
        else if ($num < 1000)
            $numStr = '0' . $num;
        else if ($num > 9999) {
            $numStr = '' . $num;
            $numStr = substr($numStr, -1, 4);
        } else
            $numStr = '' . $num;
        return $numStr;
    }

    //해당 월의 날자수
    public static function getDaysOfMonth($year, $month)
    {
        $days = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

        return $days;

    }

    //날자값이 빈경우에 빈문자를 현시하기위한 함수
    public static function fixDateValue($dates, $fields)
    {
        foreach ($dates as $date) {
            foreach ($fields as $field) {
                if ($date[$field] == '0000-00-00') {
                    $date[$field] = '';
                }
            }
        }
        return $dates;
    }

    // 반점으로 구분된 목록에서 처음과 마지막에 붙은 반점을 제거된 목록을 귀환한다.
    public static function removeFirstAndEndComma($liststr)
    {
        $templist = explode(',', $liststr);
        $list = array();
        foreach ($templist as $str) {
            if (!empty($str))
                $list[] = $str;
        }

        return $list;
    }

    // 반점으로 구분된 목록에서 처음과 마지막에 붙은 반점을 제거된 목록을 귀환한다.
    public static function removeFirstAndEndCommaStr($liststr)
    {
        $templist = explode(',', $liststr);
        $liststr = '';
        foreach ($templist as $str) {
            if (empty($str))
                continue;
            if (empty($liststr))
                $liststr = '' . $str;
            else
                $liststr = $liststr . ',' . $str;
        }

        return $liststr;
    }

    public static function getMenuInfo($request)
    {
        $path = $request->path();
        $menu = Menu::where('controller', $path)->first();
        $parentId = $menu['parentId'];
        $menuId = $menu['id'];

        if ($parentId < 10) {
            $GLOBALS['selMenu'] = $menuId;
            $GLOBALS['submenu'] = 0;

        } else {
            $GLOBALS['selMenu'] = $parentId;
            $GLOBALS['submenu'] = $menuId;
        }
        return;
    }

    public static function makePaginateHtml($pageCount=0, $page=0, $paginate = null)
    {
        if(isset($paginate)) {
            $lastPage = $paginate->lastPage();
            $currPage = $paginate->currentPage();
        } else {
            $lastPage = $pageCount;
            $currPage = $page;
        }

        if($lastPage <  2)
            return '';

        $startPage = ($currPage - 1) < 2 ? 1 : $currPage - 1;
        $endPage = ($currPage + 1) > $lastPage ? $lastPage : $currPage + 1;
        if($startPage < 3)
            $startPage = 1;
        if($endPage > ($lastPage - 2))
            $endPage = $lastPage;

        $html = '<ul class="pagination pull-right">';
        $html .= '<li class="prev';
        if ($currPage == 1)
            $html .= ' disabled';
        $html .= '"><a href="javascript:void(0)" class="prev_page"><i class="icon-double-angle-left"></i></a></li>';
        if ($lastPage < 10) {
            for ($page = 1; $page < $lastPage + 1; $page++) {
                $html .= '<li';
                if ($page == $currPage)
                    $html .= ' class="active"';
                $html .= '><a href="javascript:void(0)" class="page">' . $page . '</a></li>';
            }
        } else {
            $moreHtml = '';
            for ($page = 1; $page < $lastPage + 1; $page++) {
                if((($page > 2) && ($page < $startPage)) || (($page > $endPage) && ($page < ($lastPage - 1)))){
                    if($moreHtml == '') {
                        $moreHtml = '<li class="disabled"><span>...</span></li>';
                        $html .= $moreHtml;
                    }
                    continue;
                }
                $moreHtml = '';
                $html .= '<li';
                if ($page == $currPage)
                    $html .= ' class="active"';
                $html .= '><a href="javascript:void(0)" class="page">' . $page . '</a></li>';
            }
        }

        $html .= '<li class="next';
        if ($currPage == $lastPage)
            $html .= ' disabled';
        $html .= '"><a class="next_page" href="javascript:void(0)"><i class="icon-double-angle-right"></i></a></li>';
        return $html;
    }

    public static function getPortName($portIds) {
        if(empty($portIds))
            return '';

        $query = 'SELECT GROUP_CONCAT(Port_En) as portName FROM tbl_port WHERE id in ('.$portIds .')';
        $result = DB::select($query);
        if(count($result))
            $result = str_replace(',', '=>', $result[0]->portName);
        else
            $result = '';

        return $result;
    }

    public static function getPortName_Cn($portIds) {
        if(empty($portIds))
            return '';
        $query = 'SELECT GROUP_CONCAT(Port_Cn) as portName FROM tbl_port WHERE id in ('.$portIds .')';
        $result = DB::select($query);
        if(count($result))
            $result = str_replace(',', '=>', $result[0]->portName);
        else
            $result = '';

        return $result;
    }
    public static function getCargoName($cargoId) {
        if(empty($cargoId))
            return '';

        if(strpos($cargoId, ',') > -1)
            $ids = substr($cargoId, 1, strlen($cargoId) - 2 );
        else
            $ids = $cargoId;

        $query = 'SELECT GROUP_CONCAT(CARGO_Cn) as cargoName FROM tbl_cargo WHERE id in ('. $ids .')';
        $result = DB::select($query);
        if(count($result))
            $result = $result[0]->cargoName;
        else
            $result = '';

        return $result;
    }

    public static function getTopMemu($menuId) {
        if(empty($menuId))
            return array();

        $query = "SELECT * FROM tb_menu WHERE admin = 0 AND parentId = 0 AND id IN (".$menuId.")
                  UNION
                  SELECT * FROM tb_menu WHERE id IN
                    (SELECT parentId FROM tb_menu WHERE admin = 0 AND id IN (".$menuId.") GROUP BY parentId)
                  ORDER BY id";
        $result = DB::select($query);
        return $result;
    }

    public static function getNumberFt($number) {
        return $number == 0 ? "":(number_format($number, 2, '.', ',') != 0.00) ? number_format($number, 2, '.', ','): "";
    }

    public static function getNumberFtNZ($number) {
        return $number == 0 ? "":number_format($number, 0, '.', ',');
    }

    public static function getNumberFt1Z($number) {
        return $number == 0 ? "":number_format($number, 1, '.', ',');
    }

    public static function getRoundFt($number) {
        return $number == 0 ? "":(round($number, 2) != 0.00) ? round($number, 2): "";
    }

    public static function getStampUrl($kData, $signPath, $memberType) {
        $url = null;
        global $attendData;
        if (isset($attendData[$kData])) {
            $url = url("img/{$kData}.jpg");
        }elseif ($kData == "출근" && $signPath != null){
            $url = $memberType == 1 ? url("uploads/stamp/{$signPath}") : url("uploads/signPhoto/{$signPath}");
        }

        return $url;
    }
}