<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class YearlyQuarterMonthPlan extends Model
{
    protected $table="tbl_yearly_quarter_monthplan";
    public $timestamps = false;

    // get all data
    public static function getAllData($ship, $year) {
        $result = static::query()
                    ->where('ShipID', $ship)
                    ->where('Yearly', $year)
                    ->orderBy('Month')
                    ->get();

        return $result;
    }

    // get report of all years
    public static function getCreditDebitAmount(){

/////
        $query = 'SELECT CP_Year, total_income, total_expense, (total_income - total_expense) AS total_profit, planIncome, planExpense, planProfit FROM
                    (SELECT
                        SUM(CASE tbl_ac_item.C_D WHEN "Credit" THEN tbl_invoice.Amount ELSE 0 END) AS total_income,
                        SUM(CASE tbl_ac_item.C_D WHEN "Debit" THEN tbl_invoice.Amount ELSE 0 END) AS total_expense,
                        YEAR(tbl_invoice.Appl_Date) AS CP_Year
                    FROM tbl_invoice
                    INNER JOIN tbl_ac_detail_item ON tbl_ac_detail_item.id = tbl_invoice.AC_Items
                    INNER JOIN tbl_ac_item ON tbl_ac_item.id = tbl_ac_detail_item.AC_Item
                    WHERE YEAR(tbl_invoice.Appl_Date) IS NOT NULL AND tbl_invoice.Completion = 1 AND tbl_invoice.Object = "Business" AND tbl_invoice.Curency = "USD"
                    GROUP BY YEAR(tbl_invoice.Appl_Date)) voy_invoice
                INNER JOIN ( SELECT Yearly AS plan_year, SUM(Income) AS planIncome, SUM(Expense) AS planExpense, SUM(IFNULL((income-Expense),0)) AS planProfit FROM tbl_yearly_quarter_monthplan
                        GROUP BY Yearly) plan_voy
                ON voy_invoice.CP_Year = plan_voy.plan_year';
        $result = DB::select($query);

        foreach($result as $yearInfo) {
            $year = $yearInfo->CP_Year;
            $sql = 'SELECT CP_Month, total_income, total_expense, (total_income - total_expense) AS total_profit, planIncome, planExpense, planProfit FROM
                        (SELECT
                            SUM(CASE tbl_ac_item.C_D WHEN "Credit" THEN tbl_invoice.Amount ELSE 0 END) AS total_income,
                            SUM(CASE tbl_ac_item.C_D WHEN "Debit" THEN tbl_invoice.Amount ELSE 0 END) AS total_expense,
                            MONTH(tbl_invoice.Appl_Date) AS CP_Month
                        FROM tbl_invoice
                        INNER JOIN tbl_ac_detail_item ON tbl_ac_detail_item.id = tbl_invoice.AC_Items
                        INNER JOIN tbl_ac_item ON tbl_ac_item.id = tbl_ac_detail_item.AC_Item
                        WHERE YEAR(tbl_invoice.Appl_Date) = "'.$year.'" AND tbl_invoice.Completion = 1 AND tbl_invoice.Object = "Business" AND tbl_invoice.Curency = "USD"
                        GROUP BY MONTH(tbl_invoice.Appl_Date)) voy_invoice
                    INNER JOIN ( SELECT `Month` AS plan_month, SUM(Income) AS planIncome, SUM(Expense) AS planExpense, SUM(IFNULL((income-Expense),0)) AS planProfit FROM tbl_yearly_quarter_monthplan
                            WHERE Yearly = "'.$year.'" GROUP BY `Month`) plan_voy
                    ON voy_invoice.CP_Month = plan_voy.plan_month';
            $month_result = DB::select($sql);
            $yearInfo->monthList = $month_result;
        }

        return $result;
    }

    // get report by year and month
    public static function getReportYearMonth(){

        $query = 'SELECT Yearly FROM tbl_yearly_quarter_monthplan GROUP BY Yearly';
        $result = DB::select($query);

        return $result;
    }

    // get shipYearReport data
    public static function getShipYearReport($year = 2017){
        $query = "SELECT tb_ship_register.shipName_Cn, QryYearly_PRT_DetailCal.ShipID, Qry_Y_Plan.IncomeOfSum AS PlanIncome,
                        IFNULL(QryYearly_PRT_DetailCal.ACTL_FRT, 0) AS YearlyIncome, Qry_Y_Plan.ExpenseOfSum AS PlanExpense,
                        IFNULL(QryYearly_PRT_DetailCal.EXP, 0) AS YearlyExpense, Qry_Y_Plan.Profit AS PlanProfit,
                        IFNULL(QryYearly_PRT_DetailCal.PROFIT, 0) AS YearlyProfit,
                        QryYearly_PRT_DetailCal.PD AS YearlyPD,
                        QryYearly_PRT_DetailCal.FO AS YearlyFO, QryYearly_PRT_DetailCal.DO AS YearlyDO, 
                        QryYearly_PRT_DetailCal.LO AS YearlyLO,
                        QryYearly_PRT_DetailCal.SS AS YearlySS, QryYearly_PRT_DetailCal.CTM AS YearlyCTM,
                        (CASE tb_ship_register.Shipid WHEN '0' THEN 100 ELSE tb_ship_register.Shipid END) as ShipOrder
                        FROM
                            (SELECT
                                QryYearly_AcDetail_Cal.ShipID, QryYearly_AcDetail_Cal.CP_Year,
                                total_income AS ACTL_FRT,
                                total_expense AS EXP,
                                (total_income - total_expense) AS PROFIT,
                                IFNULL(QryYearly_AcDetail_Cal.sum_pd,0) AS PD,
                                IFNULL(QryYearly_AcDetail_Cal.sum_fo,0) AS FO,
                                IFNULL(QryYearly_AcDetail_Cal.sum_do,0) AS `DO`,
                                IFNULL(QryYearly_AcDetail_Cal.sum_lo,0) AS LO,
                                IFNULL(QryYearly_AcDetail_Cal.sum_ss,0) AS SS,
                                IFNULL(QryYearly_AcDetail_Cal.sum_ctm,0) AS CTM
                            FROM (SELECT
                                    SUM(CASE tbl_ac_item.C_D WHEN 'Credit' THEN tbl_invoice.Amount ELSE 0 END) AS total_income,
                                    SUM(CASE tbl_ac_item.C_D WHEN 'Debit' THEN tbl_invoice.Amount ELSE 0 END) AS total_expense,
                                    SUM(CASE tbl_ac_item.AC_Item_En WHEN 'PD' THEN tbl_invoice.Amount ELSE 0 END) AS sum_pd,
                                    SUM(CASE tbl_ac_item.AC_Item_En WHEN 'CTM' THEN tbl_invoice.Amount ELSE 0 END) AS sum_ctm,
                                    SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'FO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_fo,
                                    SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'DO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_do,
                                    SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'LO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_lo,
                                    SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'SS' THEN tbl_invoice.Amount ELSE 0 END) AS sum_ss,
                                    YEAR(tbl_invoice.Appl_Date) AS CP_Year, tbl_invoice.ShipID
                                FROM tbl_invoice
                                INNER JOIN tbl_ac_detail_item ON tbl_ac_detail_item.id = tbl_invoice.AC_Items
                                INNER JOIN tbl_ac_item ON tbl_ac_item.id = tbl_ac_detail_item.AC_Item
                                WHERE YEAR(tbl_invoice.Appl_Date)  = ".$year." AND tbl_invoice.Completion = 1 AND tbl_invoice.Object = 'Business' AND tbl_invoice.Curency = 'USD'
                                GROUP BY tbl_invoice.ShipID
                                ORDER BY YEAR(tbl_invoice.Appl_Date)) AS QryYearly_AcDetail_Cal
                            ORDER BY QryYearly_AcDetail_Cal.CP_Year) AS QryYearly_PRT_DetailCal
                        INNER JOIN
                            (SELECT ShipID, Yearly, SUM(Income) AS IncomeOfSum, SUM(Expense) AS ExpenseOfSum, SUM(IFNULL((income-Expense),0)) AS Profit
                            FROM tbl_yearly_quarter_monthplan
                            WHERE Yearly = ".$year."
                            GROUP BY ShipID) AS Qry_Y_Plan
                        ON QryYearly_PRT_DetailCal.ShipID = Qry_Y_Plan.ShipID
                        INNER JOIN tb_ship_register ON tb_ship_register.RegNo = QryYearly_PRT_DetailCal.ShipID
                        WHERE QryYearly_PRT_DetailCal.CP_Year =".$year." GROUP BY QryYearly_PRT_DetailCal.ShipID
                        ORDER BY ShipOrder";

        $resultList = DB::select($query);
        return $resultList;
    }

    // get month report data  by year and ship
    public static function getShipYearMonthReport($year, $ship){
        $query = "SELECT tb_ship_register.shipName_Cn,QryYearlyMonthly_PRT_DetailCal.ShipID AS ShipID,
                QryYearlyMonthly_PRT_DetailCal.CP_Year, QryYearlyMonthly_PRT_DetailCal.CP_Month,
                QryYearlyMonthly_PRT_DetailCal.PlanProfit,
                QryYearlyMonthly_PRT_DetailCal.PlanIncome,
                QryYearlyMonthly_PRT_DetailCal.ACTL_FRT AS YearlyMonthIncome, QryYearlyMonthly_PRT_DetailCal.PlanExpense,
                QryYearlyMonthly_PRT_DetailCal.Exp AS YearlyMonthExpense, QryYearlyMonthly_PRT_DetailCal.PD AS YearlyMonthPD,
                QryYearlyMonthly_PRT_DetailCal.FO AS YearlyMonthFO, QryYearlyMonthly_PRT_DetailCal.DO AS YearlyMonthDO,
                QryYearlyMonthly_PRT_DetailCal.LO AS YearlyMonthLO, QryYearlyMonthly_PRT_DetailCal.SS AS YearlyMonthSS,
                QryYearlyMonthly_PRT_DetailCal.CTM AS YearlyMonthCTM
                FROM (
                    SELECT QryYearlyMonthly_AcDetail_Cal.ShipID,
                    QryYearlyMonthly_AcDetail_Cal.CP_Year, QryYearlyMonthly_AcDetail_Cal.CP_Month,
                    tbl_yearly_quarter_monthplan.Income AS PlanIncome,
                    tbl_yearly_quarter_monthplan.Expense AS PlanExpense,
                    tbl_yearly_quarter_monthplan.Profit AS PlanProfit,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.total_income,0) AS ACTL_FRT,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.total_expense,0) AS EXP,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_pd,0) AS PD,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_fo,0) AS FO,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_do,0) AS DO,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_lo,0) AS LO,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_SS,0) AS SS,
                    IFNULL(QryYearlyMonthly_AcDetail_Cal.sum_ctm,0) AS CTM
                    FROM ( SELECT
                            SUM(CASE tbl_ac_item.C_D WHEN 'Credit' THEN tbl_invoice.Amount ELSE 0 END) AS total_income,
                            SUM(CASE tbl_ac_item.C_D WHEN 'Debit' THEN tbl_invoice.Amount ELSE 0 END) AS total_expense,
                            SUM(CASE tbl_ac_item.AC_Item_En WHEN 'PD' THEN tbl_invoice.Amount ELSE 0 END) AS sum_pd,
                            SUM(CASE tbl_ac_item.AC_Item_En WHEN 'CTM' THEN tbl_invoice.Amount ELSE 0 END) AS sum_ctm,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'FO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_fo,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'DO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_do,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'LO' THEN tbl_invoice.Amount ELSE 0 END) AS sum_lo,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN 'SS' THEN tbl_invoice.Amount ELSE 0 END) AS sum_ss,
                            YEAR(tbl_invoice.Appl_Date) AS CP_Year, MONTH(Appl_Date) AS CP_Month, tbl_invoice.ShipID
                        FROM tbl_invoice
                        INNER JOIN tbl_ac_detail_item ON tbl_ac_detail_item.id = tbl_invoice.AC_Items
                        INNER JOIN tbl_ac_item ON tbl_ac_item.id = tbl_ac_detail_item.AC_Item
                        WHERE YEAR(tbl_invoice.Appl_Date) IS NOT NULL AND tbl_invoice.Curency = 'USD' AND tbl_invoice.Completion = 1 AND tbl_invoice.Object = 'Business'
                        GROUP BY tbl_invoice.ShipID, YEAR(tbl_invoice.Appl_Date), MONTH(tbl_invoice.Appl_Date)
                        ORDER BY YEAR(tbl_invoice.Appl_Date), MONTH(tbl_invoice.Appl_Date) ) AS QryYearlyMonthly_AcDetail_Cal
                    INNER JOIN tbl_yearly_quarter_monthplan
                    ON QryYearlyMonthly_AcDetail_Cal.ShipID = tbl_yearly_quarter_monthplan.ShipID 
			AND QryYearlyMonthly_AcDetail_Cal.CP_Year=tbl_yearly_quarter_monthplan.Yearly
			AND QryYearlyMonthly_AcDetail_Cal.CP_Month=tbl_yearly_quarter_monthplan.Month
                    ORDER BY QryYearlyMonthly_AcDetail_Cal.CP_Year, QryYearlyMonthly_AcDetail_Cal.CP_Month) AS QryYearlyMonthly_PRT_DetailCal
                INNER JOIN tb_ship_register ON tb_ship_register.RegNo = QryYearlyMonthly_PRT_DetailCal.ShipID
                WHERE QryYearlyMonthly_PRT_DetailCal.ShipID = '".$ship."' AND QryYearlyMonthly_PRT_DetailCal.CP_Year = '".$year."'
                ORDER BY QryYearlyMonthly_PRT_DetailCal.CP_Month";

        $result = DB::select($query);
        return $result;
    }

    // get count report data  by year and ship
    public static function getShipCountReport($year, $ship){
        $query = 'SELECT voy_profit.*, voy_log.*, tbl_cp.LPort, tbl_cp.DPort, tbl_cp.Cgo_Qtty, tbl_cp.Freight, tbl_cp.B_L, tbl_voy_profit.Profit, tbl_cp.Cargo
                    FROM (SELECT tbl_invoice.Voy,
                            SUM(CASE tbl_ac_item.C_D WHEN "Credit" THEN tbl_invoice.Amount ELSE 0 END) AS total_income,
                            SUM(CASE tbl_ac_item.C_D WHEN "Debit" THEN tbl_invoice.Amount ELSE 0 END) AS total_expense,
                            SUM(CASE tbl_ac_item.AC_Item_En WHEN "PD" THEN tbl_invoice.Amount ELSE 0 END) AS sum_pd,
                            SUM(CASE tbl_ac_item.AC_Item_En WHEN "CTM" THEN tbl_invoice.Amount ELSE 0 END) AS sum_ctm,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN "FO" THEN tbl_invoice.Amount ELSE 0 END) AS sum_fo,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN "DO" THEN tbl_invoice.Amount ELSE 0 END) AS sum_do,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN "LO" THEN tbl_invoice.Amount ELSE 0 END) AS sum_lo,
                            SUM(CASE tbl_ac_detail_item.AC_Detail_Item_Abb WHEN "SS" THEN tbl_invoice.Amount ELSE 0 END) AS sum_ss
                        FROM tbl_invoice
                        INNER JOIN tbl_ac_detail_item ON tbl_ac_detail_item.id = tbl_invoice.AC_Items
                        INNER JOIN tbl_ac_item ON tbl_ac_item.id = tbl_ac_detail_item.AC_Item
                        WHERE tbl_invoice.Object = "Business" AND tbl_invoice.Completion = 1 AND tbl_invoice.Voy IS NOT NULL AND tbl_invoice.Curency = "USD"
                             AND tbl_invoice.ShipId = "'.$ship . '"
                        GROUP BY tbl_invoice.Voy
                        ORDER BY tbl_invoice.Voy) voy_profit
                        INNER JOIN (SELECT tbl_cp.id, tbl_cp.Voy_No, tbl_cp.LPort, tbl_cp.DPort, StartDate, LastDate,
                                IFNULL(QryVoy_Distance.SailDistance, 0) AS SailDistance,
                                IFNULL(ROUND(((UNIX_TIMESTAMP(LastDate)- UNIX_TIMESTAMP(StartDate)) / 86400), 2), 0) AS dateInteval
                                FROM tbl_cp
                                    LEFT JOIN (SELECT CP_ID, MIN(Voy_Date) AS StartDate, MAX(Voy_Date) AS LastDate
                                        FROM tbl_voy_log GROUP BY CP_ID ORDER BY CP_ID, MIN(Voy_Date)) AS QryVoy_StartLast
                                        ON tbl_cp.id = QryVoy_StartLast.CP_ID
                                    LEFT JOIN (SELECT tbl_voy_log.CP_ID, SUM(ROUND(tbl_voy_log.Sail_Distance, 0)) AS SailDistance
                                        FROM tbl_voy_log GROUP BY CP_ID) AS QryVoy_Distance
                                ON tbl_cp.id = QryVoy_Distance.CP_ID) voy_log
                        ON voy_profit.Voy = voy_log.id
                        INNER JOIN tbl_cp ON tbl_cp.id = voy_profit.Voy
                        LEFT JOIN tbl_voy_profit ON tbl_voy_profit.VoyId = voy_profit.Voy
                        WHERE tbl_cp.Voy_No IS NOT NULL AND YEAR (tbl_cp.CP_Date) = '.$year;

        $result = DB::select($query);
        return $result;
    }
    // get years list
    public static function getYearList(){
        $result =  static::query()
                ->select('Yearly')
                ->groupBy('Yearly')
                ->orderBy('Yearly', 'DESC')
                ->get();
        return $result;
    }

    public static function carGoName($cargo){
        if(empty($cargo)) return '';
        if(strpos($cargo, ',') > -1)
            $cargoId = substr($cargo, 1, strlen($cargo) - 2 );
        else
            $cargoId = $cargo;
        $query = 'SELECT GROUP_CONCAT(CARGO_Cn) as cargoName FROM tbl_cargo WHERE id in ('. $cargoId .')';
        $result = DB::select($query);
        if(count($result))
            $result = $result[0]->cargoName;
        else
            $result = '';

        return $result;
    }
}