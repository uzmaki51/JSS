<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-10-29
 * Time: 오전 12:13
 */

namespace App\Models\Home;

require_once "appmodel.class.php";

class RateModel extends \Model_Appmodel
{
    protected $_name = "rate";

    public function getRecentRate()
    {
        $where = "SELECT * FROM	rate ORDER BY rate_date DESC LIMIT 30 OFFSET 0";
        $rateData = self::$_dbAdapter->fetchAll($where);

        return isset($rateData) ? $rateData : "";
    }
	 public function getSearchRate($year)
    {
		 for($i=1;$i<100;$i++){
			//$where = "SELECT * FROM	rate where  rate_date='{$year}-0{$month}-25' ORDER BY rate_date DESC ";
             $where = "SELECT * FROM	rate where  rate_date BETWEEN '{$year}-01-01' and '{$year}-12-31'ORDER BY rate_date DESC ";
			$searchData = self::$_dbAdapter->fetchAll($where);

			return isset($searchData) ? $searchData : "";
		}
    }
}