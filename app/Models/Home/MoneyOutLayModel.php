<?php

namespace App\Models\Home;

require_once "appmodel.class.php";

class MoneyOutLayModel extends \Model_Appmodel
{
    /** Table name */
    protected $_name    = 'moneyoutlay';

    public function getLayDatas()
    {
        $where = "select moneyoutlay.id as mainId, moneyoutlay.* , classinfo.classname as classname, bungi.*
				from moneyoutlay,classinfo,bungi
				where classinfo.classnum = bungi.classinfo_classnum and moneyoutlay.bungi_id = bungi.id order by bungi.bunginum, bungi.moneytypeflag";
        $result = self::$_dbAdapter->fetchAll($where);
        foreach($result as $key => $item){
            $result[$key]["doc_content"] = str_replace("\r\n", "", nl2br(htmlspecialchars($item["doc_content"])));
            $dateArr = getdate(strtotime($result[$key]["bungidate"]));
            $nowArr = date('m');
            $diff = intval($nowArr) - intval($dateArr['mon']);
            $result[$key]['diff'] = $diff;
        }
        return $result;
    }

    public function getCrDatas()
    {
        $where = "select * from cr order by Id";
        $tempCrDatas = self::$_dbAdapter->fetchAll($where);
        foreach($tempCrDatas as $key => $crItem) {
            $retDatas[$crItem['Id']]['CrName'] = $crItem['Cr_Name'];
            $retDatas[$crItem['Id']]['CrLabel'] = $crItem['Cr_Label'];
            $retDatas[$crItem['Id']]['CrUnit'] = $crItem['Cr_Unit'];
        }

        $retDatas[-1]['CrName'] = $crItem['Cr_Name'];
        $retDatas[-1]['CrLabel'] = $crItem['Cr_Label'];
        $retDatas[-1]['CrUnit'] = $crItem['Cr_Unit'];
        return $retDatas;
    }
}