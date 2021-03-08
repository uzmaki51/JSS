<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YearlyPlanInput extends Model
{
    protected $table="tbl_yearlyplaninput";
    public $timestamps = false;


    // get years list
    public static function getYearList(){
        $query = 'SELECT Yearly
                FROM Tbl_Yearly_Quarter_MonthPlan
                GROUP BY Yearly
                ORDER BY Yearly';
        $result = DB::select($query);
        return $result;
    }

}