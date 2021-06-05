<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;

use App\Models\Operations\Account;
use App\Models\Operations\AcItem;
use App\Models\Operations\PayMode;
use App\Models\Operations\VoyProfit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Util;

use App\Models\Menu;
use App\Models\ShipManage\ShipRegister;
use App\Models\Decision\DecisionReport;

use App\Models\Operations\AcItemDetail;
use App\Models\Operations\YearlyPlanInput;
use App\Models\Operations\VoyStatus;
use App\Models\Operations\VoyStatusEvent;
use App\Models\Operations\VoyStatusType;
use App\Models\Operations\YearlyQuarterMonthPlan;
use App\Models\Operations\Cp;
use App\Models\Operations\StandardCp;
use App\Models\Operations\VoyLog;
use App\Models\Operations\Invoice;
use App\Models\Operations\YearlyPlan;
use App\Models\Operations\ShipOilSupply;
use App\Models\ShipTechnique\ShipPort;
use App\Models\Operations\Cargo;
use App\Models\Operations\VoyProRandom;
use App\Models\Operations\VoyProgramPractice;
use App\Models\Operations\SailDistance;
use Config;

use Illuminate\Database\Eloquent;

use Auth;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('operation/operationPlan');
    }

    //----------------- 운영계획 ------------------//
    public  function operationPlan(Request $request){
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();
        $yearList = YearlyQuarterMonthPlan::getYearList();

        $year = is_null($request->get('year')) ? 2019 : $request->get('year');
        $ship = is_null($request->get('shipId')) ? $shipList[0]->RegNo : $request->get('shipId') ;

        $yearData = YearlyPlan::getPlan($year);

        // get list of ship don't have the value
        $curShipList = array();
        foreach ($yearData as $list) {
            $curShipList[$list->ShipID] = $list->shipName_Cn;
        }

        $shipName = '';
        $shipListArr = array();

        foreach ($shipList as $list) {
            $shipListArr[$list->RegNo] = $list->shipName_Cn;
            if($list->RegNo == $ship)
                $shipName = $list->shipName_Cn;
        }
        $remainShipList = array_diff_assoc($shipListArr, $curShipList);
        //
        $monthData = YearlyQuarterMonthPlan::getAllData($ship, $year);

        $yearPlan = YearlyPlanInput::where('ShipID', $ship)->where('Yearly', $year)->first();
        if(empty($yearPlan))
            $yearPlan = new YearlyPlanInput();

        $status = Session::get('status');
        $message = Session::get('msg');

        return view('operation.schedule', array(
            'yearData'=>$yearData,
            'year'=>$year,
            'shipID'=>$ship,
            'monthData'=>$monthData,
            'shipList'=>$shipList,
            'yearList'=>$yearList,
            'remainShipList'=>$remainShipList,
            'yearPlan' =>$yearPlan,
            'shipName' => $shipName,
            'status'   => $status,
            'msg'   => $message,
        ));
    }

    public function updateYearPlan(Request $request){
        // token process

        $shipID = $request->get('shipID');
        $year = $request->get('year');
        $income = $request->get('income');
        $expense = $request->get('expense');
        $profit = $request->get('profit');
        $remark = $request->get('remark');
        if(empty($remark))
            $remark = null;

        $plan = YearlyPlan::where('ShipID', $shipID)
                    ->where('Yearly', $year)
                    ->first();
        if(is_null($plan))
            $plan = new YearlyPlan();

        $plan['ShipID'] = $shipID;
        $plan['Yearly'] = $year;
        $plan['INCOME'] = $income;
        $plan['EXPENSE'] = $expense;
        $plan['PROFIT'] = $profit;
        $plan['REMARK'] = $remark;
        $plan->save();

        $result['status'] = 'success';

        return json_encode($result);
    }

    public function updateQuarterMonthPlan(Request $request){
        // token process

        $ship = $request->get('ship');
        $year = $request->get('year');
        $month = $request->get('month');
        $income = $request->get('income');
        $expense = $request->get('expense');
        $profit = $request->get('profit');

        $plan = YearlyQuarterMonthPlan::where('ShipID', $ship)
            ->where('Yearly', $year)
            ->where('Month', $month)
            ->first();
        if(is_null($plan)) {
            $plan = new YearlyQuarterMonthPlan();
            $plan['ShipID'] = $ship;
            $plan['Yearly'] = $year;
            $plan['Quarter'] = round(($month - 1) / 3) + 1;
            $plan['Month'] = $month;
        }

        $plan['Income'] = $income;
        $plan['Expense'] = $expense;
        $plan['Profit'] = $profit;
        $plan->save();

        $result['status'] = 'success';

        return json_encode($result);
    }

    public function removeYearPlan(Request $request){
        // token process

        $shipID = $request->get('shipID');
        $year = $request->get('year');

        $val = YearlyPlan::where('ShipID', $shipID)->where('Yearly', $year)->delete();

        $result['status'] = 'success';

        return json_encode($result);
    }

    public function updateYearInputPlan(Request $request) {
        $shipID = $request->get('shipId');
        $year = $request->get('year');

        $yearPlan = YearlyPlanInput::where('ShipID', $shipID)->where('Yearly', $year)->first();
        if(empty($yearPlan)) {
            $yearPlan = new YearlyPlanInput();
            $yearPlan['ShipID'] = $shipID;
            $yearPlan['Yearly'] = $year;
        }

        $yearPlan['INCOME'] = $request->get('INCOME');
        $yearPlan['BUNKER'] = $request->get('BUNKER');
        $yearPlan['FO'] = $request->get('FO');
        $yearPlan['DO'] = $request->get('DO');
        $yearPlan['LO'] = $request->get('LO');
        $yearPlan['SS'] = $request->get('SS');
        $yearPlan['PDA'] = $request->get('PDA');
        $yearPlan['CTM'] = $request->get('CTM');
        $yearPlan['INSURANCE'] = $request->get('INSURANCE');
        $yearPlan['OAP'] = $request->get('OAP');
        $yearPlan['TELCOM'] = $request->get('TELCOM');
        $yearPlan['DUNNAGE'] = $request->get('DUNNAGE');
        $yearPlan['ISM'] = $request->get('ISM');
        $yearPlan['OTHERS'] = $request->get('OTHERS');
        $yearPlan['DOCKING REPAIR'] = $request->get('DOCKING');
        $yearPlan['EXPENSE'] = $request->get('EXPENSE');
        $yearPlan['PROFIT'] = $request->get('PROFIT');
        $yearPlan['YEARLY VOY DAY'] = $request->get('VOY_DAY');
        $yearPlan->save();

        $msg = $year.'的年支出计划被保存成功!';
        $type = $request->get('submit_type');
        if($type == 'calc') {
            $calc_voy_ids = Cp::where('Ship_ID', $shipID)->where('CP_Date', 'like', $year.'-%')->get();
            foreach ($calc_voy_ids as $voy) {
                $voyId = $voy->id;

                $sailInfo = VoyLog::getSailTime($voyId);

                $incomeInfo = Invoice::caculateVoyInvoice($voyId);
                if(count($incomeInfo) > 0)
                    $incomeInfo = $incomeInfo[0];
                $planInfo = $yearPlan;
                $voyDay = $planInfo['YEARLY VOY DAY'];
                if(is_null($voyDay) || ($voyDay == 0))
                    $voyDay = 365;
                $proInfo = VoyProgramPractice::where('ShipId', $shipID)->where('voyId', $voyId)->first();
                if(is_null($proInfo))
                    $proInfo = new VoyProgramPractice();

                $prac_exp_fo = Round($proInfo['Prac_Fo'] * $proInfo['Fo_Unit'], 2);
                $prac_exp_do = Round($proInfo['Prac_Do'] * $proInfo['Do_Unit'], 2);
                $prac_exp_lo = Round($proInfo['Prac_Lo'] * $proInfo['Lo_Unit'], 2);
                $ss_prac = Round($planInfo['SS'] * $sailInfo->DateInteval / $voyDay, 2);
                $ctm_prac = Round($planInfo['CTM'] * $sailInfo->DateInteval / $voyDay, 2);
                $telcom_prac = Round($planInfo['TELCOM'] * $sailInfo->DateInteval / $voyDay, 2);
                $insurance_prac = Round($planInfo['INSURANCE'] * $sailInfo->DateInteval / $voyDay, 2);
                $oap_prac = Round($planInfo['OAP'] * $sailInfo->DateInteval / $voyDay, 2);
                $ism_prac = Round($planInfo['ISM'] * $sailInfo->DateInteval / $voyDay, 2);
                $other_prac = Round($planInfo['OTHERS'] * $sailInfo->DateInteval / $voyDay, 2);

                $prac_tt = $incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage;
                $prac_tt_expense = $prac_exp_fo + $prac_exp_do + $prac_exp_lo + $ss_prac + $incomeInfo->Pda + $ctm_prac + $insurance_prac + $oap_prac + $telcom_prac + $ism_prac + $other_prac;
                $prac_tt_profit = $prac_tt - $prac_tt_expense;

                $profitInfo = VoyProfit::where('ShipID', $shipID)->where('VoyId', $voyId)->first();
                if(empty($profitInfo)) {
                    $profitInfo = new VoyProfit();
                    $profitInfo['ShipID'] = $shipID;
                    $profitInfo['VoyId'] = $voyId;
                }

                $profitInfo['Income'] = $prac_tt;
                $profitInfo['Expense'] = $prac_tt_expense;
                $profitInfo['Profit'] = $prac_tt_profit;
                $profitInfo->save();
                $msg = $year.'年利润计算成功!';

            }

        }
        return redirect()->back()->with(['status'=>'success', 'msg'=>$msg]);
    }

    public function getShipYearPlan(Request $request) {
        $shipId = $request->get('shipId');

        $yearPlan = YearlyPlanInput::where('ShipID', $shipId)->orderBy('Yearly', 'desc')->first();
        if(empty($yearPlan))
            $yearPlan['status'] = 'error';
        else
            $yearPlan['status'] = 'success';

        $yearPlan['year_day'] = $yearPlan['YEARLY VOY DAY'];

        return response()->json($yearPlan);
    }

    // 용선계약 수정
    public function updateContract(Request $request){

        $voyId = $request->get('voyId');

        $shipId = $request->get('Ship_ID');
        $voyNo = $request->get('Voy_No');

        $isExist = Cp::where('Ship_ID', $shipId)->where('Voy_No', $voyNo)->first();
        if(!empty($isExist) && ($isExist['id'] != $voyId)) {
            return back()->with(['status'=>'error']);
        }

        if(empty($voyId)) {
            $voyInfo = new Cp();
            $voyInfo['Ship_ID'] = $shipId;
        } else
            $voyInfo = Cp::find($voyId);

        $voyInfo['CP_kind'] = $request->get('CP_Kind');
        $voyInfo['Voy_No'] = $request->get('Voy_No');
        $voyInfo['CP_No'] = $request->get('CP_No');
        $voyInfo['CP_Date'] = $request->get('CP_Date');
        $voyInfo['LPort'] =  empty($request->get('LPort')) ? '' : implode(",", $request->get('LPort'));
        $voyInfo['DPort'] =  empty($request->get('DPort')) ? '' : implode(",", $request->get('DPort'));
        $voyInfo['Cargo'] = empty($request->get('Cargo')) ? '' : ','.implode(",", $request->get('Cargo')).',';
        $voyInfo['Cgo_Qtty'] = $request->get('Cgo_Qtty');
        $voyInfo['Cgo_Qttylimit'] = $request->get('Cgo_Qttylimit');
        $voyInfo['LayCan_Date1'] = $request->get('LayCan_Date1');
        $voyInfo['LayCan_Date2'] = $request->get('LayCan_Date2');
        $voyInfo['L_Rate'] = $request->get('L_Rate');
        $voyInfo['D_Rate'] = $request->get('D_Rate');
        $voyInfo['Freight'] = $request->get('Freight');
        $voyInfo['total_Freight'] = $request->get('total_Freight');
        $voyInfo['B_L'] = $request->get('B_L');
        $voyInfo['Demurrage'] = $request->get('Demurrage');
        $voyInfo['Brokerage'] = $request->get('Brokerage');
        $voyInfo['Charterer'] = $request->get('Charterer');
        $voyInfo['Shipper'] = $request->get('Shipper');
        $voyInfo['Consignee'] = $request->get('Consignee');
        $voyInfo['Remarks'] = $request->get('Remarks');
        $voyInfo['Unit'] = $request->get('Unit');

        $voyInfo->save();

        return back()->with(['status'=>'success']);
    }

    public function getContract(Request $request) {
        $voyId = $request->get('voyId');
        $total_Freight = '';
        $Charterer = '';
        $Shipper = '';
        $Consignee = '';
        $Remarks = '';
        if(!empty($voyId)) {
            $voyInfo = Cp::find($voyId);
            if(!empty($voyInfo)) {
                $total_Freight = $voyInfo->total_Freight;
                $Charterer = $voyInfo->Charterer;
                $Shipper = $voyInfo->Shipper;
                $Consignee = $voyInfo->Consignee;
                $Remarks = $voyInfo->Remarks;
            }
        }
        return response()->json([
            'total_Freight' => $total_Freight,
            'Charterer' => $Charterer,
            'Shipper' => $Shipper,
            'Consignee' => $Consignee,
            'Remarks' => $Remarks,
        ]);
    }

    public function removeContract(Request $request){
        // token process

        $voyId = $request->get('voyId');

        $voyInfo = Cp::find($voyId);
        if(!empty($voyInfo))
            $voyInfo->delete();

        $result['status'] = 'success';

        return json_encode($result);
    }

    //----------------- 배동태 ------------------//
    public function homeMovement(Request $request) {
        Util::getMenuInfo($request);

        //배동태
        $movement = VoyLog::getHomeShipVoyLogData();
        return view('operation.home-movement', ['movement' => $movement]);
    }

    public function movement(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListOnlyOrigin();

        $shipId = is_null($request->get('shipId')) ? $shipList[0]->RegNo : $request->get('shipId');
//        $shipId = is_null($request->get('shipId')) ? '' : $request->get('shipId');

        $shipPositionList = VoyLog::getShipPositionList();
        $shipStatusList = VoyStatus::all();

        $voyNoList = Cp::getVoyNosOfShip($shipId);
        $voyNo = is_null($request->get('voyNo')) ? $voyNoList[0]->id : $request->get('voyNo');


        $content = VoyLog::getShipVoyLogData($shipId, $voyNo);
        $error = Session::get('error');

        return view('operation.movement', array(
            'shipList'      =>  $shipList,
            'shipID'        =>  $shipId,
            'voyList'       =>  $voyNoList,
            'shipPositionList' => $shipPositionList,
            'shipStatusList'=>  $shipStatusList,
            'voyNo'         =>  $voyNo,
            'data'          =>  $content,
            'error'         =>  $error
        ));
    }

    // 배동태 수정
    public function getSailTimement(Request $request){

        $logId = $request->get('ID');
        $shipId = $request->get('shipId');
        $voyNo = $request->get('voyNo');
        $voyDate = $request->get('voyDate');
        $voyTime = $request->get('voyTime');
        $voyStatus = $request->get('voyStatus');

        $dateStr = $voyDate.' '.$voyTime;
        $isExist = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyNo)->where('Voy_Date', $dateStr)->where('Voy_Status', $voyStatus)->first();
        if(isset($isExist) && ($isExist['id'] != $logId)) {
            $error = 'error';
            return back()->with(['error'=>$error]);
        }

        if(empty($logId)) {
            $log = new VoyLog();
            $log['Ship_ID'] = $request->get('shipId');
            $log['CP_ID'] = $request->get('voyNo');
        } else
            $log = VoyLog::find($logId);

        $log['Voy_Date'] = $dateStr;
        $log['Voy_Status'] = $request->get('voyStatus');
        $log['Ship_Position'] = $request->get('voyPos');
        $log['Cargo_Qtty'] = $request->get('voyQtty');
        $log['ROB_FO'] = $request->get('voyFO');
        $log['ROB_DO'] = $request->get('voyDO');
        $log['Sail_Distance'] = $request->get('voyDistance');
        $log['ROB_LO_M'] = $request->get('voyLOM');
        $log['ROB_LO_A'] = $request->get('voyLOA');
        $log['ROB_FW'] = $request->get('voyFW');
        $log['Remarks'] = $request->get('voyRemark');
        $log->save();

        return redirect('operation/movement?shipId='.$shipId.'&voyNo='.$voyNo);
    }

    // 배동태 수정
    public function updateMovement(Request $request){

        $logId = $request->get('voyId');
        $shipId = $request->get('shipId');
        $voyNo = $request->get('voyNo');
        $voyDate = $request->get('voyDate');
        $voyStatus = $request->get('voyStatus');

        $isExist = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyNo)->where('Voy_Date', $voyDate)->where('Voy_Status', $voyStatus)->first();
        if(isset($isExist) && ($isExist['id'] != $logId)) {
            $error = '选择的船舶动态是已经登记的。';
            return back()->with(['error'=>$error]);
        }

        if(empty($logId)) {
            $log = new VoyLog();
            $log['Ship_ID'] = $request->get('shipId');
            $log['CP_ID'] = $request->get('voyNo');
        } else
            $log = VoyLog::find($logId);
        $log['Ship_ID'] = $request->get('shipId');
        $log['CP_ID'] = $request->get('voyNo');
        $log['Voy_Date'] = $request->get('voyDate').' '.$request->get('voyTime');
        $log['Voy_Status'] = $request->get('voyStatus');
        $log['Ship_Position'] = $request->get('voyPos');
        $log['Cargo_Qtty'] = $request->get('voyQtty');
        $log['ROB_FO'] = $request->get('voyFO');
        $log['ROB_DO'] = $request->get('voyDO');
        $log['Sail_Distance'] = $request->get('voyDistance');
        $log['ROB_LO_M'] = $request->get('voyLOM');
        $log['ROB_LO_A'] = $request->get('voyLOA');
        $log['ROB_FW'] = $request->get('voyFW');
        $log['Remarks'] = $request->get('voyRemark');
        $log->save();

        return redirect('operation/movement?shipId='.$shipId.'&voyNo='.$voyNo);
    }

    public function removeMovement(Request $request) {
        // token process

        $logId = $request->get('logId');
        $log = VoyLog::find($logId);
        if(is_null($log)) {
            $result['status'] = 'error';
            return json_encode($result);
        }

        $log->delete();

        $result['status'] = 'success';
        return json_encode($result);
    }

    public function voyStatusManage(Request $request) {

        Util::getMenuInfo($request);

        $status = $request->get('status');

        $list = VoyStatus::getStatusList($status);
        if(isset($status))
            $list->appends(['status'=>$status]);

        $economyList = VoyStatusEvent::getVoyEventList(1); // 경제일수项目
        $uneconomyList = VoyStatusEvent::getVoyEventList(2); // 경제일수项目
        $otherList = VoyStatusEvent::getVoyEventList(0); // 경제일수项目

        return view('operation.ship_voy_status',
            [   'list'      =>  $list,
                'status'    =>  $status,
                'economy'   =>  $economyList,
                'uneconomy'   =>  $uneconomyList,
                'other'   =>  $otherList,
            ]);
    }

    public function updateVoyStatus(Request $request) {
        $statusId = $request->get('statusId');

        $voyStatus = $request->get('Voy_Status');
        $isExist = VoyStatus::where('Voy_Status', $voyStatus)->first();
        if(isset($isExist) && ($isExist['id'] != $statusId)) {
            $result['status'] = 'error';

            return json_encode($result);
        }
        if(empty($statusId))
            $status = new VoyStatus();
        else
            $status = VoyStatus::find($statusId);

        $status['Voy_Status'] = $request->get('Voy_Status');
        $status['Status_Name'] = $request->get('Status_Name');
        $status['Descript'] = $request->get('Descript');
        $status['Descript_En'] = $request->get('Descript_En');
        $status['Related_Economy'] = $request->get('Related_Economy');
        $status['Related_UnEconomy'] = $request->get('Related_UnEconomy');
        $status['Related_Other'] = $request->get('Related_Other');
        $status->save();

        $result['status'] = 'success';
        if(empty($statusId))
            $result['statusId'] = VoyStatus::all()->last()->id;
        return json_encode($result);
    }

    public function removeVoyStatus(Request $request) {

        $statusId = $request->get('statusId');

        $status = VoyStatus::find($statusId);
        if(is_null($status))
            $result['status'] = 'error';
        else {
            $status->delete();
            $result['status'] = 'success';
        }

        return json_encode($result);
    }

    public function voyStatusEventManage(Request $request) {
        Util::getMenuInfo($request);

        $list = VoyStatusEvent::paginate();
        $typeList = VoyStatusType::all(['id', 'ItemName']);

        $error = Session::get('error');

        return view('operation.ship_voy_status_event', ['list'=>$list, 'typeList'=>$typeList, 'error'=>$error]);
    }

    public function updateVoyStatusEvent(Request $request) {
        $eventId = $request->get('eventId');

        if(empty($eventId))
            $event = new VoyStatusEvent();
        else
            $event = VoyStatusEvent::find($eventId);

        $event['TypeId'] = $request->get('TypeId');
        $event['Event'] = $request->get('Event');
        $event['Description'] = $request->get('Description');
        $event->save();

        return redirect('operation\voyStatusEventManage');
    }

    public function deleteVoyStatusEvent(Request $request) {
        $eventId = $request->get('eventId');
        $event = VoyStatusEvent::find($eventId);

        if(is_null($event)) {
            $result['status'] = 'error';
            return json_encode($result);
        }

        $event->delete();

        $result['status'] = 'success';
        return json_encode($result);
    }

    public function voyStatusTypeManage(Request $request) {
        Util::getMenuInfo($request);

        $list = VoyStatusType::paginate();

        $error = Session::get('error');

        return view('operation.ship_voy_status_type', ['list'=>$list, 'error'=>$error]);
    }

    public function updateVoyStatusType(Request $request) {
        $typeId = $request->get('typeId');
        $itemName = $request->get('ItemName');

        $isExist = VoyStatusType::where('ItemName', $itemName)->first();
        if(isset($isExist) && ($isExist['id'] != $typeId)) {
            $error = '项目名称重复!';
            return back()->with(['error'=>$error]);
        }

        if(empty($typeId))
            $event = new VoyStatusType();
        else
            $event = VoyStatusType::find($typeId);

        $event['ItemName'] = $request->get('ItemName');
        $event['VoyItem'] = $request->get('VoyItem');
        $event['Type'] = $request->get('Type');
        $event->save();

        return redirect('operation\voyStatusTypeManage');
    }

    public function deleteVoyStatusType(Request $request) {
        $typeId = $request->get('typeId');
        $type = VoyStatusEvent::find($typeId);

        if(is_null($type)) {
            $result['status'] = 'error';
            return json_encode($result);
        }

        $type->delete();

        $result['status'] = 'success';
        return json_encode($result);
    }

    //----------------- 수입지출 ------------------//
    public function incomeExpense(Request $request) {
        $start_year = DecisionReport::select(DB::raw('MIN(create_at) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = substr($start_year['min_date'],0,4);
        }
        $shipList = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship_register.NickName', 'tb_ship.name')
                        ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->get();
        return view('operation.incomeExpense', array(
            'start_year' => $start_year,
            'shipList'   => $shipList,
        ));
    }

    public function incomeAllExpense(Request $request) {
        $start_year = DecisionReport::select(DB::raw('MIN(create_at) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = substr($start_year['min_date'],0,4);
        }
        $shipList = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship_register.NickName', 'tb_ship.name')
                        ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->get();
        return view('operation.incomeAllExpense', array(
            'start_year' => $start_year,
            'shipList'   => $shipList,
        ));
    }

    public function ajaxIncomeExportListByShip(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getIncomeExportList($params);

		return response()->json($reportList);
    }

    public function ajaxListBySOA(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getListBySOA($params);

		return response()->json($reportList);
    }

    public function ajaxListByAll(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getListByAll($params);

		return response()->json($reportList);
    }

    

    public function import(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $firstVoy = $request->get('firstVoy');
        $endVoy = $request->get('endVoy');
        $firstPaidVoy = $request->get('firstPaidVoy');
        $endPaidVoy = $request->get('endPaidVoy');
        $pay_mode = $request->get('payMode');
        $Account = Account::get();
        $PayMode = PayMode::get();

        $shipList = ShipRegister::getShipListByOrigin();
        $payList = AcItemDetail::orderBy('AC_Item')->orderBy('id')->get();
        $voyList = array();
        if(!empty($shipId))
            $voyList = Cp::select('id', 'Voy_No', 'CP_No')
                    ->where('Ship_ID', $shipId)
                    ->orderBy(DB::raw('CONVERT(Voy_No , DECIMAL(4,0))'), 'DESC')
                    ->get();

        $data = Invoice::getInvoiceData($shipId, $firstVoy, $endVoy, $firstPaidVoy, $endPaidVoy, $pay_mode);

        return view('operation.import', array(
                                    'data'      =>  $data,
                                    'shipList'  =>  $shipList,
                                    'voyList'   =>  $voyList,
                                    'payList'   =>  $payList,
                                    'shipId'    =>  $shipId,
                                    'firstVoy'  =>  $firstVoy,
                                    'endVoy'    =>  $endVoy,
                                    'firstPaidVoy'  =>  $firstPaidVoy,
                                    'endPaidVoy'    =>  $endPaidVoy,
                                    'payMode'   =>  $pay_mode,
                                    'Account'   =>  $Account,
                                    'PayMode'   =>  $PayMode,
        ));
    }

    // 수입 및 지출 >> 연유 부속자재
    public function getSupplyElement(Request $request){

        $invoiceId = $request->get('invoice');

		if(empty($invoiceId) || ($invoiceId == '0')) {
			$shipSupply = array();
		
		} else {

			$shipSupply = ShipOilSupply::where('INVOICE_ID', $invoiceId)->get();
		}

        return view('operation.import_supply', ['list'=>$shipSupply]);
    }

	public function deleteShipInvoice(Request $request) {
		$invoiceId = $request->get('invoice');

		$invoice = Invoice::find($invoiceId);
		if(!empty($invoice))
			$invoice->delete();

		return 'success';
	}

	public function updateShipInvoice(Request $request) {

		$invoiceId = $request->get('invoice_id');
		if(empty($invoiceId) || ($invoiceId == '0')) {
			$shipInvoice = new Invoice();

		} else {
			$shipInvoice = Invoice::find($invoiceId);
		}

        $refNo = $request->get('RefNo');
        if(!empty($refNo)) {
            $isExist = Invoice::where('Ref_No', $refNo)->first();
            if(!empty($isExist) && $isExist->id != $invoiceId) return back()->with(['error' => '参考号码重复!']);
        }
        	$shipInvoice['Object'] = $request->get('Object');

		$oldAppleDate = $shipInvoice['Appl_Date'];
		$oldItem = $shipInvoice['AC_Items'];
		$appleDate = $request->get('Appl_Date');
		$acItemId = $request->get('AC_Items');

		if(empty($refNo)) {
			$refNo = date('y-m', strtotime($appleDate));

			$acItems = AcItemDetail::find($acItemId);

            $year = date('y', strtotime($appleDate));
			$refNo .= '-'.$acItems['AC_Detail Item_Referance'];

			$last = Invoice::where('Ref_No', 'like',$year.'%'. $acItems['AC_Detail Item_Referance'].'%')->count();
			$count = $last + 1;
            /*
			if(!empty($last)){
				$lastRefNo = $last['Ref_No'];
				$lastItems = explode('-', $lastRefNo);
				$count = $lastItems[count($lastItems) - 1] + 1;
			}
            */
			 
			$refNo .= '-'.$count;

			$shipInvoice['Ref_No'] = $refNo;
		} else
            $shipInvoice['Ref_No'] = $request->get('RefNo');

		$shipInvoice['ShipID'] = $request->get('ShipID');
		$shipInvoice['Voy'] = $request->get('Voy');
		$shipInvoice['Paid_Voy'] = $request->get('Paid_Voy');
		$shipInvoice['Discription'] = $request->get('Discription');
		$shipInvoice['AC_Items'] = $acItemId;
		$shipInvoice['Place'] = $request->get('Place');
		$shipInvoice['Amount'] = $request->get('Amount');
		$shipInvoice['Curency'] = $request->get('Curency');
		$shipInvoice['Account'] = $request->get('Account');
		$shipInvoice['Pay_Mthd'] = $request->get('Pay_Mthd');
		$shipInvoice['Appl_Date'] = $appleDate;
		$shipInvoice['Recipt_Date'] = $request->get('Recipt_Date');
		$check = $request->get('Completion');
		if($check == '1')
			$shipInvoice['Completion'] = 1;
		else
			$shipInvoice['Completion'] = 0;

		$check = $request->get('Recipt');
		if($check == '1')
			$shipInvoice['Recipt'] = 1;
		else
			$shipInvoice['Recipt'] = 0;
		$shipInvoice['Remark'] = $request->get('Remark');

		$shipInvoice->save();

		$updatedId = $invoiceId;
		if(empty($updatedId)){
			$last = Invoice::all()->last(['id']);
			$updatedId = $last['id'];
		}

		if(($acItemId == 4) || ($acItemId == 5) || ($acItemId == 8)) {
			if(!empty($invoiceId) || ($invoiceId != 0)) {
				$supplyList = ShipOilSupply::where('INVOICE_ID', $invoiceId)->get();
				$deletedIdList = array();
				foreach($supplyList as $supply) {
					$id = $supply['id'];
					$supplyId = $request->get('supply_'.$id);
					if(empty($supplyId)) {
						$deletedIdList[] = $id;
						continue;
					}

					$supply['SUPPLD_DATE'] = $request->get('SUPPLD_DATE_'.$id);
					$supply['PLACE'] = $request->get('PLACE_'.$id);
					$supply['AC_ITEM'] = $request->get('AC_ITEM_'.$id);
					$supply['DESCRIPTION'] = $request->get('DESCRIPTION_'.$id);
					$supply['PART_NO'] = $request->get('PART_NO_'.$id);
					$supply['QTY'] = $request->get('QTY_'.$id);
					$supply['UNIT'] = $request->get('UNIT_'.$id);
					$supply['PRCE'] = $request->get('PRCE_'.$id);
					$supply['AMOUNT'] = $request->get('AMOUNT_'.$id);
					$supply['REMARK'] = $request->get('REMARK_'.$id);
					
					$supply->save();
				}

				if(count($deletedIdList) > 0) {
					ShipOilSupply::whereIn('id', $deletedIdList)->delete();
				}
			}

			for($index=1; $index < 20; $index++) {
				$newId = $request->get('new_'.$index);
				if(empty($newId))
					continue;

				$supply = new ShipOilSupply();
				$supply['INVOICE_ID'] = $updatedId;
				$supply['SUPPLD_DATE'] = $request->get('SUPPLD_DATE_new_'.$newId);
				$supply['PLACE'] = $request->get('PLACE_new_'.$newId);
				$supply['AC_ITEM'] = $request->get('AC_ITEM_new_'.$newId);
				$supply['DESCRIPTION'] = $request->get('DESCRIPTION_new_'.$newId);
				$supply['PART_NO'] = $request->get('PART_NO_new_'.$newId);
				$supply['QTY'] = $request->get('QTY_new_'.$newId);
				$supply['UNIT'] = $request->get('UNIT_new_'.$newId);
				$supply['PRCE'] = $request->get('PRCE_new_'.$newId);
				$supply['AMOUNT'] = $request->get('AMOUNT_new_'.$newId);
				$supply['REMARK'] = $request->get('REMARK_new_'.$newId);
				
				if(empty($supply['SUPPLD_DATE']) && empty($supply['PLACE']) && (empty($supply['DESCRIPTION'])))
					continue;

				$supply->save();
			}
		}

		return back()->with(['status'=>'success']);

    }

    //----------------- 运费账单 ------------------//
    public function shipCalc(Request $request)
    {

        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $voyId = $request->get('voyId');
        $shipList = ShipRegister::getShipListByOrigin();

        if(empty($shipId)) {
            $shipId = empty($shipList) ? '' : $shipList[0]->RegNo;
        }

        $voyNoList = Cp::getVoyNosOfShip($shipId);
        if(empty($voyId) && count($voyNoList)) {
            $voyId = $voyNoList[0]->id;
        }

        if(empty($voyId) || ($voyId == 0)){
            return view('operation.ship_calc',
                [   'shipList'  =>  $shipList,
                    'shipID'    =>  $shipId,
                    'voyList'   =>  $voyNoList,
                    'voyId'     =>  $voyId,
                ]);
        }


        $data = Cp::find($voyId);
        if($data) {
            $data['L_Port'] = Util::getPortName_Cn($data['LPort']);
            $data['D_Port'] = Util::getPortName_Cn($data['DPort']);
            $data['Cargo_Name'] = Util::getCargoName($data['Cargo']);
            $invoiceData = Cp::getInviceOfCalcData($voyId);
        } else {
            $invoiceData = array();
            $data = new Cp();
        }

        return view('operation.ship_calc',
            [   'shipList'  =>  $shipList,
                'shipID'    =>  $shipId,
                'voyList'   =>  $voyNoList,
                'voyId'     =>  $voyId,
                'data'      =>  $data,
                'invoiceList'=>  $invoiceData,
            ]);
    }


    /* 계획수행 */
    //----------------- 계획수행 / 년계획수행종합 ------------------//
    public function yearPlanReport(Request $request)
    {
        Util::getMenuInfo($request);

        $data = YearlyQuarterMonthPlan::getCreditDebitAmount();
        $dataYear = YearlyQuarterMonthPlan::getReportYearMonth();

        return view('operation.report.ship_all', array('allList'=>$data, 'yearList' => $dataYear));
    }

    //----------------- 계획수행 / 배별 년계획수행종합 ------------------//
    public function shipYearReport(Request $request)
    {
        Util::getMenuInfo($request);

        $yearList = YearlyQuarterMonthPlan::getYearList();
        $year = $request->get('year');
        if(empty($year) && count($yearList))
            $year = $yearList[0]->Yearly;
        else if(empty($year))
            $year = date('Y');

        $data = YearlyQuarterMonthPlan::getShipYearReport($year);

        return view('operation.report.ship_year', array(
            'currentYear'=>$year,
            'yearList'=>$yearList,
            'lists'=>$data));
    }

    //----------------- 계획수행 / 배별 월계획수행종합 ------------------//
    public function shipMonthReport(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();
        $yearList = YearlyQuarterMonthPlan::getYearList();

        $shipId = $request->get('shipId');
        $year = $request->get('year');
        if(empty($year) && count($yearList))
            $year = $yearList[0]->Yearly;
        else if(empty($year))
            $year = date('Y');

        if(empty($shipId))
            $shipId = $shipList[0]->RegNo;

        $data = YearlyQuarterMonthPlan::getShipYearMonthReport($year, $shipId);
        $monthData = [];
        if(count($data) < 12) {
            $index = 0;
            for($month = 1; $month < 13; $month++) {
                if(isset($data[$index]) && ($data[$index]->CP_Month == $month)){
                    $monthData[$month-1] = $data[$index];
                    $index++;
                    continue;
                }

                $planMonth = YearlyQuarterMonthPlan::where('ShipID', $shipId)->where('Yearly', $year)->where('Month', $month)->first();
                $month_data = new \stdClass();
                $month_data->CP_Month = $month;
                $month_data->PlanProfit = $planMonth->Profit;
                $month_data->PlanIncome = $planMonth->Income;
                $month_data->PlanExpense = $planMonth->Expense;
                $month_data->YearlyMonthIncome = 0;
                $month_data->YearlyMonthExpense = 0;
                $month_data->YearlyMonthPD = 0;
                $month_data->YearlyMonthFO = 0;
                $month_data->YearlyMonthDO = 0;
                $month_data->YearlyMonthLO = 0;
                $month_data->YearlyMonthSS = 0;
                $month_data->YearlyMonthCTM = 0;
                $monthData[$month-1] = $month_data;
            }
        } else
            $monthData = $data;

        return view('operation.report.ship_month', array(
            'currentYear'=>$year,
            'yearList'=>$yearList,
            'shipList'=>$shipList,
            'ship'=>$shipId,
            'data'=>$monthData,
        ));
    }

    //----------------- 계획수행 / 배별 航次실적종합 ------------------//
    public function shipCountReport(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();
        $yearList = YearlyQuarterMonthPlan::getYearList();
        $year = $request->get('year');

        if(empty($year))
            $year = $yearList[0]->Yearly;
        $ship = $request->get('shipId');

        if(empty($ship))
            $ship = $shipList[0]->RegNo;

        $data = YearlyQuarterMonthPlan::getShipCountReport($year, $ship);

        return view('operation.report.ship_count', array(
            'currentYear'   =>  $year,
            'yearList'      =>  $yearList,
            'shipList'      =>  $shipList,
            'ship'          =>  $ship,
            'list'          =>  $data
                )
        );
    }

    /* 연유공급 */
    //----------------- 연유공급 ------------------//
    public function oilSupply(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();
        $yearList = YearlyQuarterMonthPlan::getYearList();
        $year = !empty($_COOKIE["year"]) ? $_COOKIE["year"] : $yearList[0]->Yearly;
        $ship = !empty($_COOKIE["shipID"]) ? $_COOKIE["shipID"] : $shipList[0]->RegNo;

        $allData = ShipOilSupply::getAllData();
        $yearData = ShipOilSupply::getDataByYear($year);
        $shipData = ShipOilSupply::getDataByYearAndShip($year,$ship);

        return view('operation.oil_supply', array(
            'yearList'=>$yearList,
            'year'=>$year,
            'shipID'=>$ship,
            'shipList'=>$shipList,
            'allData'=>$allData,
            'yearData'=>$yearData,
            'shipData'=>$shipData,
        ));
    }

    // 航次타산 >>
    public function getSailDistance(Request $request){

        $LPort = $request->get('lport');
        $DPort = $request->get('dport');

        $val = SailDistance::where('LPortID', $LPort)->where('DPortID', $DPort)->first();

        if($val){
            $result = array(
                'status' =>'success',
                'distance' =>$val->SailDistance
            );
        }else{
            $result['status'] = 'failed';
        }
        return json_encode($result);
    }

    // 航次일수분석
    public function shipVoyAnalysis(Request $request) {

        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $shipList = ShipRegister::getShipListByOrigin();
        if(is_null($shipId)) {
            $shipId = $shipList[0]['RegNo'];
        }
        $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);
        $voyNoList = Cp::getVoyNosOfShip($shipId);

        $page = $request->get('page');
        if(is_null($page))
            $page = 1;

        $firstVoy = $request->get('first');
        $endVoy = $request->get('end');
        if(isset($firstVoy) && ($endVoy == 0) && count($voyNoList))
            $endVoy = $voyNoList[0]['id'];

        $voyList = VoyLog::getShipVoyInfo($shipId, $page, $firstVoy, $endVoy);
        $voyListCount = VoyLog::countShipVoyInfoList($shipId, $firstVoy, $endVoy);
        $voyIdStr = '';
        foreach($voyList as $voy)
            $voyIdStr = empty($voyIdStr) ? $voy->id : ($voyIdStr.','.$voy->id);

        $analysis = VoyLog::getShipVoyAnalysis($shipId, $voyIdStr);
        $eventList = VoyStatusEvent::getVoyTypeEventList();
        $typeList = VoyStatusType::countEventType();

        $analysIndex = 0;
        $voyAnalysList = array();
        foreach($voyList as $voy) {
            $voyAnalys = array();
            $voyAnalys['shipName'] = $voy->shipName_Cn;
            $voyAnalys['voyNo'] = $voy->Voy_No;
            $voyAnalys['startDate'] = $voy->StartDate;
            $voyAnalys['endDate'] = $voy->LastDate;
            $voyAnalys['L_Port'] = Util::getPortName($voy->LPort);
            $voyAnalys['D_Port'] = Util::getPortName($voy->DPort);
            $voyAnalys['distance'] = $voy->SailDistance;
            $voyAnalys['sailTime'] = $voy->DateInteval;

            foreach ($eventList as $event)
                $voyAnalys[$event['Event']] = 0;

            while ($voy->id == $analysis[$analysIndex]->CP_ID) {
                $eventName = $analysis[$analysIndex]->Event;
                $voyAnalys[$eventName] = is_null($analysis[$analysIndex]->time_sum)? 0 : $analysis[$analysIndex]->time_sum;
                $analysIndex++;
                if($analysIndex >= count($analysis))
                    break;
            }

            $voyAnalysList[] = $voyAnalys;
        }

        $pageHtml = Util::makePaginateHtml($voyListCount, $page);

        return view('operation.ship_voy_analysis',
            [   'shipId'    =>  $shipId,
                'shipName'  =>  $shipNameInfo,
                'shipList'  =>  $shipList,
                'firstVoy'  =>  $firstVoy,
                'endVoy'    =>  $endVoy,
                'voyList'   =>  $voyNoList,
                'list'      =>  $voyAnalysList,
                'eventList' =>  $eventList,
                'typeList'  =>  $typeList,
                'pageHtml'  =>  $pageHtml,
                'page'      =>  $page
            ]);
    }


    //----------------- 航次타산 (초본) ------------------//
    public function shipCountSimpleList(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $shipList = ShipRegister::getShipListByOrigin();

        // get data of saved voy random
        $voyProRandom = VoyProRandom::getCalcVoyData($shipId);

        return view('operation.ship_count_list', array(
            'voyProRandom'=>$voyProRandom,
            'shipList'=>$shipList,
            'shipId'=>$shipId
        ));
    }

    //----------------- 航次타산 (초본仔细) ------------------//
    public function shipCountSimple(Request $request)
    {
        $GLOBALS['selMenu'] = 68;
        $GLOBALS['submenu'] = 110;

        $calcId = $request->get('id');

        $shipList = ShipRegister::getShipListByOrigin();
        $contractList = StandardCp::orderBy('id')->get();

        $portList = ShipPort::get();

        $data = VoyProRandom::find($calcId);
        $year = date('Y');
        if(!empty($data))
            $shipId = $data['shipid'];
        else if(count($shipList) > 0)
            $shipId = $shipList[0]['RegNo'];

        $yearPlan = YearlyPlanInput::where('Yearly', $year)->where('ShipID', $shipId)->first();
        if(empty($yearPlan))
            $yearPlan = YearlyPlanInput::where('Yearly', $year-1)->first();

        return view('operation.ship_count_simple', array(
            'shipList'=>$shipList,
            'contractList'=>$contractList,
            'portList'=>$portList,
            'data' => $data,
            'yearPlan' => $yearPlan
        ));
    }

    public function updateStandardCp(Request $request) {

        $baseId = $request->get('baseId');
        if(!empty($baseId) && ($baseId <> 0)) {
            $base = VoyProRandom::find($baseId);
        } else {
            $base = new VoyProRandom();
        }

        $base['caldate'] = date('Y-m-d H:m:s');
        $base['calyear'] = date('Y');
        $base['typeofcp'] = $request['ship_contract'];
        $base['shipid'] = $request['ship_name'];
        $base['lport'] = $request['lport'];
        $base['dport'] = $request['dport'];
        $base['way_type'] = $request['way_select'];
        $base['qtty'] = $request['qtty'];
        $base['frt'] = $request['frt'];
        $base['demurrage'] = $request['demurrage'];
        $base['broker'] = $request['broker'];
        $base['addincome'] = $request['addincome'];
        $base['distance'] = $request['distance'];
        $base['voyspeed'] = $request['voyspeed'];
        $base['ld_time'] = $request['ld_time'];
        $base['idle_time'] = $request['idle_time'];
        $base['fo_sail_consum'] = $request['fo_sail_consum'];
        $base['fo_ld_consum'] = $request['fo_ld_consum'];
        $base['fo_idle_consum'] = $request['fo_idle_consum'];
        $base['do_sail_consum'] = $request['do_sail_consum'];
        $base['do_ld_consum'] = $request['do_ld_consum'];
        $base['do_idle_consum'] = $request['do_idle_consum'];
        $base['lo_sail_consum'] = $request['lo_sail_consum'];
        $base['lo_ld_consum'] = $request['lo_ld_consum'];
        $base['lo_idle_consum'] = $request['lo_idle_consum'];
        $base['fo_price'] = $request['fo_price'];
        $base['do_price'] = $request['do_price'];
        $base['lo_price'] = $request['lo_price'];
        $base['pd_l'] = $request['pd_l'];
        $base['pd_d'] = $request['pd_d'];
        $base['lkt'] = $request['lkt'];
        $base['ss'] = $request['ss'];
        $base['ctm'] = $request['ctm'];
        $base['insurance'] = $request['insurance'];
        $base['ism'] = $request['ism'];
        $base['other'] = $request['other'];

        $base->save();

        return back();
    }
    //----------------- 航次타산 (표준) ------------------//
    public function shipCountStandard(Request $request)
    {
        Util::getMenuInfo($request);
        $shipId = $request->get('shipId');
        $voyId = $request->get('voy');

        $shipList = ShipRegister::getShipListByOrigin();
        $voyList = array();
        if(count($shipList) > 0) {
            if(is_null($shipId))
                $shipId = $shipList[0]['RegNo'];
            $voyList = Cp::where('Ship_ID', $shipId)->orderBy('id', 'Desc')->get(['id','Voy_No', 'CP_No']);
            if(is_null($voyId) && (count($voyList) > 0))
                $voyId = $voyList[0]['id'];
        }

        $voyInfo = Cp::find($voyId);
        $proInfo = VoyProgramPractice::where('voyId', $voyId)->first();
        if(is_null($proInfo))
            $proInfo = new VoyProgramPractice();

        $sailInfo = VoyLog::getSailTime($voyId);

        $incomeInfo = Invoice::caculateVoyInvoice($voyId);
        if(count($incomeInfo) > 0)
            $incomeInfo = $incomeInfo[0];

        if(isset($proInfo['CalculationDate'])) {
            $year = substr($proInfo['CalculationDate'], 0, 4);
            $plan = YearlyPlanInput::where('ShipID', $shipId)->where('Yearly', $year)->first();
        } else {
            $plan = YearlyPlanInput::where('ShipID', $shipId)->orderBy('Yearly', 'desc')->first();
        }

        return view('operation.ship_count_standard',
            [
                'shipList'  =>  $shipList,
                'voyList'   =>  $voyList,
                'shipId'    =>  $shipId,
                'voyId'     =>  $voyId,
                'voyInfo'   =>  $voyInfo,
                'proInfo'   =>  $proInfo,
                'sailInfo'  =>  $sailInfo,
                'incomeInfo'=>  $incomeInfo,
                'planInfo'  =>  $plan,
            ]);
    }

    // 배선택시 航次号码목록얻기
    public function getVoyList(Request $request) {
        $shipId = $request->get('shipId');
        $voyList = Cp::where('Ship_ID', $shipId)->orderBy(DB::raw('CONVERT(Voy_No , DECIMAL(4,0))'), 'DESC')->get(['id', 'Voy_No', 'CP_No']);

        if(is_null($voyList))
            return;

        return json_encode($voyList);
    }

    // 배선택시 航次号码목록과 배속도얻기
    public function getVoyListAndShipSpeed(Request $request) {
        $shipId = $request->get('shipId');
        $voyList = Cp::where('Ship_ID', $shipId)->orderBy('id', 'DESC')->get(['id', 'Voy_No', 'CP_No']);

        $shipInfo = ShipRegister::where('RegNo', $shipId)->first();
        $data = array();
        $data['speed'] = $shipInfo['Speed'];
        $data['voyList'] = $voyList;

        return json_encode($data);
    }

    // 船舶名称과 航次에 의한 航次타산자료 얻기
    public function getVoyListAndCaculInfo(Request $request) {
        $shipId = $request->get('shipId');
        $voyId = $request->get('voyId');
        $voyList = Cp::where('Ship_ID', $shipId)->orderBy('id', 'DESC')->get(['id', 'Voy_No', 'CP_No']);
        if( empty($voyId) && (count($voyList) > 0))
            $voyId = $voyList[0]->id;

        $calcInfo = VoyProgramPractice::where('ShipId', $shipId)->where('VoyId', $voyId)->first();

        $data = [];
        $data['voyList'] = $voyList;
        $data['calcInfo'] = $calcInfo;

        return response()->json($data);
    }

    // 航次타산을 위한 기초자료입력페지
    public function voyCountCalculateInput() {

        $GLOBALS['selMenu'] = 68;
        $GLOBALS['submenu'] = 111;

        $shipList = ShipRegister::getShipListByOrigin();

        return view('operation.standard_input', ['shipList'=>$shipList]);
    }

    public function calculateVoyageData(Request $request) {

        $shipId = $request->get('shipId');
        $voyId = $request->get('voyId');

        $inputInfo = VoyProgramPractice::where('ShipId', $shipId)->where('voyId', $voyId)->first();
        if(is_null($inputInfo)) {
            $inputInfo = new VoyProgramPractice();
            $inputInfo['CalculationDate'] = date('Y-m-d');
        }

        $inputInfo['ShipId'] = $shipId;
        $inputInfo['voyId'] = $voyId;
        $inputInfo['Speed'] = $request->get('Speed');
        $inputInfo['PracDistance'] = $request->get('Distance');
        $inputInfo['LD_Day'] = $request->get('LD_Day');
        $inputInfo['Idle_Day'] = $request->get('Idle_Day');
        $inputInfo['PDA_Prog'] = $request->get('PDA_Prog');
        $inputInfo['Prac_Fo'] = $request->get('Prac_Fo');
        $inputInfo['Prac_Do'] = $request->get('Prac_Do');
        $inputInfo['Prac_Lo'] = $request->get('Prac_Lo');
        $inputInfo['DailyPrac_Fo_Sail'] = $request->get('DailyPrac_Fo_Sail');
        $inputInfo['DailyPrac_Do_Sail'] = $request->get('DailyPrac_Do_Sail');
        $inputInfo['DailyPrac_Lo_Sail'] = $request->get('DailyPrac_Lo_Sail');
        $inputInfo['DailyPrac_Fo_LD'] = $request->get('DailyPrac_Fo_LD');
        $inputInfo['DailyPrac_Do_LD'] = $request->get('DailyPrac_Do_LD');
        $inputInfo['DailyPrac_Lo_LD'] = $request->get('DailyPrac_Lo_LD');
        $inputInfo['DailyPrac_Fo_Idle'] = $request->get('DailyPrac_Fo_Idle');
        $inputInfo['DailyPrac_Do_Idle'] = $request->get('DailyPrac_Do_Idle');
        $inputInfo['DailyPrac_Lo_Idle'] = $request->get('DailyPrac_Lo_Idle');
        $inputInfo['Fo_Unit'] = $request->get('Fo_Unit');
        $inputInfo['Do_Unit'] = $request->get('Do_Unit');
        $inputInfo['Lo_Unit'] = $request->get('Lo_Unit');
        $inputInfo['Pro_AddIn'] = $request->get('Pro_AddIn');
        $inputInfo->save();

        $sailInfo = VoyLog::getSailTime($voyId);

        $incomeInfo = Invoice::caculateVoyInvoice($voyId);
        if(count($incomeInfo) > 0)
            $incomeInfo = $incomeInfo[0];

        $year = substr($inputInfo['CalculationDate'], 0, 4);
        $planInfo = YearlyPlanInput::where('ShipID', $shipId)->where('Yearly', $year)->first();
        if(empty($planInfo))
            $planInfo = YearlyPlanInput::where('ShipID', $shipId)->orderBy('Yearly', 'desc')->first();

        $proInfo = $inputInfo;
        $voyDay = $planInfo['YEARLY VOY DAY'];
        if(is_null($voyDay) || ($voyDay == 0))
            $voyDay = 365;
        $prac_exp_fo = Round($proInfo['Prac_Fo'] * $proInfo['Fo_Unit'], 2);
        $prac_exp_do = Round($proInfo['Prac_Do'] * $proInfo['Do_Unit'], 2);
        $prac_exp_lo = Round($proInfo['Prac_Lo'] * $proInfo['Lo_Unit'], 2);
        $ss_prac = Round($planInfo['SS'] * $sailInfo->DateInteval / $voyDay, 2);
        $ctm_prac = Round($planInfo['CTM'] * $sailInfo->DateInteval / $voyDay, 2);
        $telcom_prac = Round($planInfo['TELCOM'] * $sailInfo->DateInteval / $voyDay, 2);
        $insurance_prac = Round($planInfo['INSURANCE'] * $sailInfo->DateInteval / $voyDay, 2);
        $oap_prac = Round($planInfo['OAP'] * $sailInfo->DateInteval / $voyDay, 2);
        $ism_prac = Round($planInfo['ISM'] * $sailInfo->DateInteval / $voyDay, 2);
        $other_prac = Round($planInfo['OTHERS'] * $sailInfo->DateInteval / $voyDay, 2);

        $prac_tt = $incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage;
        $prac_tt_expense = $prac_exp_fo + $prac_exp_do + $prac_exp_lo + $ss_prac + $incomeInfo->Pda + $ctm_prac + $insurance_prac + $oap_prac + $telcom_prac + $ism_prac + $other_prac;
        $prac_tt_profit = $prac_tt - $prac_tt_expense;

        $profitInfo = VoyProfit::where('ShipID', $shipId)->where('VoyId', $voyId)->first();
        if(empty($profitInfo)) {
            $profitInfo = new VoyProfit();
            $profitInfo['ShipID'] = $shipId;
            $profitInfo['VoyId'] = $voyId;
        }

        $profitInfo['Income'] = $prac_tt;
        $profitInfo['Expense'] = $prac_tt_expense;
        $profitInfo['Profit'] = $prac_tt_profit;
        $profitInfo->save();

        return redirect('operation/shipCountStandard?shipId='.$shipId.'&voyId='.$voyId);
    }

    public function showPortDistance(Request $request) {

        $portList = ShipPort::get();
        $distanceList = SailDistance::get();

        return view('operation.select_distance', ['portList'=>$portList, 'distanceList'=>$distanceList]);
    }

    public function betweenPortDistance(Request $request) {

        $sPort = $request->get('sPort');
        $dPort = $request->get('dPort');

        $portList = ShipPort::get();
        $query = SailDistance::query();
        if(!empty($sPort))
            $query->where('LPortID', $sPort);
        if(!empty($dPort))
            $query->where('DPortID', $dPort);
        $distanceList = $query->get();

        return view('operation.distance_table', ['distanceList'=>$distanceList]);
    }

    public function voyLogByShipId(Request $request) {

        $shipId = $request->get('shipId');
        $voyId = $request->get('voyId');

        $list = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyId)->get();

        return view('operation.select_bunker', ['list'=>$list]);
    }

    public function shipFuelCondition(Request $request) {

        $shipId = $request->get('shipId');
        $shipInfo = ShipRegister::where('RegNo', $shipId)->first();

        return view('operation.select_fuel_consum', ['info'=>$shipInfo]);
    }

    public function shipPortManage(Request $request) {
        Util::getMenuInfo($request);

        $list = ShipPort::paginate();

        $error = Session::get('error');
        return view('operation.port_manage', ['list'=>$list, 'error'=>$error]);
    }

    public function registerShipPort(Request $request) {
        $portId = $request->get('portId');

        $portName = $request->get('port_name');
        $portName_en = $request->get('port_name_en');

        $isExist = ShipPort::where('Port_Cn', $portName)->orWhere('Port_En', $portName_en)->first();
        if(!empty($isExist) && ($isExist['id'] != $portId)) {
            $error = '港口名称重复。';
            return back()->with(['error'=>$error]);
        }
        if(($portId == 0) || empty($portId))
            $port = new ShipPort();
        else
            $port = ShipPort::find($portId);

        $port['Port_Cn'] = $portName;
        $port['Port_En'] = $portName_en;

        $port->save();

        return redirect('operation/shipPortManage');
    }

    public function deleteShipPort(Request $request) {
        $portId = $request->get('portId');
        $port = ShipPort::find($portId);
        if(empty($port))
            return -1;

        $port->delete();
        return 1;

    }

    public function cargoManage(Request $request) {
        Util::getMenuInfo($request);

        $list = Cargo::paginate();

        $error = Session::get('error');
        return view('operation.cargo_manage', ['list'=>$list, 'error'=>$error]);
    }

    public function registerShipCargo(Request $request) {
        $cargoId = $request->get('cargoId');

        $cargoName = $request->get('cargo_name');
        $cargoName_en = $request->get('cargo_name_en');

        $isExist = ShipPort::where('Port_Cn', $cargoName)->orWhere('Port_En', $cargoName_en)->first();
        if(!empty($isExist) && ($isExist['id'] != $cargoId)) {
            $error = '货物名重复!';
            return back()->with(['error'=>$error]);
        }
        if(($cargoId == 0) || empty($cargoId))
            $cargo = new Cargo();
        else
            $cargo = Cargo::find($cargoId);

        $cargo['CARGO_Cn'] = $cargoName;
        $cargo['CARGO_En'] = $cargoName_en;

        $cargo->save();

        return redirect('operation/cargoManage');
    }

    public function deleteShipCargo(Request $request) {
        $cargoId = $request->get('cargoId');
        $cargo = ShipPort::find($cargoId);
        if(empty($cargo))
            return -1;

        $cargo->delete();
        return 1;

    }

    public function ACManage(Request $request) {
        Util::getMenuInfo($request);

        $ACList = AcItem::all();
        $ACDetailList = AcItemDetail::getACDetailItem($ACList[0]['id']);
        return view('operation.AC_manage', ['ACList'=>$ACList, 'ACDetailList'=>$ACDetailList]);
    }

    public function addACType(Request $request) {
        $id = $request->get('AC_type_id');
        $AC_Item_Cn = $request->get('AC_Item_Cn');
        $AC_Item_En = $request->get("AC_Item_En");
        $C_D = $request->get('C_D');
        $AC_description = $request->get('AC_description');
        $item = AcItem::where('AC_Item_Cn', $AC_Item_Cn)->get();

        if(count($item) > 1) return;
        else if(count($item) == 1 && $item[0]->id != $id) return;
        if(empty($id))
            $AC_Item = new AcItem();
        else
            $AC_Item = AcItem::find($id);
        $AC_Item['AC_Item_Cn'] = $AC_Item_Cn;
        $AC_Item['AC_Item_En'] = $AC_Item_En;
        $AC_Item['C_D'] = $C_D;
        $AC_Item['AC_description'] = $AC_description;

        $AC_Item->save();
        return redirect()->back();
    }

    public function deleteACType(Request $request) {
        $typeId = $request->get('typeId');
        AcItem::where('id', $typeId)->delete();
    }

    public function loadACDetail(Request $request) {
        $typeId = $request->get('typeId');
        $AC_Item_name = AcItem::where('id', $typeId)->first()->AC_Item_Cn;
        $ACDetails = AcItemDetail::select('tbl_ac_detail_item.*', 'tbl_ac_item.AC_Item_Cn')
            ->join('tbl_ac_item', 'tbl_ac_detail_item.AC_Item', '=', 'tbl_ac_item.id')
            ->where('AC_Item', $typeId)->get();
        return view('operation.AC_Detail_List')->with(compact('ACDetails', 'AC_Item_name'));
    }

    public function addACDetail(Request $request) {
        $AC_Item_Id = $request->get('AC_Item_Id');
        $AC_Item_Detail_Id = $request->get('AC_Item_Detail_Id');
        $AC_Item_Detail_Cn = $request->get('AC_Item_Detail_Cn');
        $AC_Item_Detail_Abb = $request->get('AC_Item_Detail_Abb');
        $AC_Item_Detail_Referance = $request->get('AC_Item_Detail_Referance');
        $AC_Detail_Item_Description = $request->get('AC_Detail_Item_Description');
        $Order_No = $request->get('Order_No');
        $item = AcItemDetail::where('AC_Item', $AC_Item_Id)
            ->where('AC_Detail_Item_Cn', $AC_Item_Detail_Cn)->get();

        if(count($item) > 1) return;
        else if(count($item) == 1 && $item[0]->id != $AC_Item_Detail_Id) return;
        if(empty($AC_Item_Detail_Id))
            $AC_Item_Detail = new AcItemDetail();
        else
            $AC_Item_Detail = AcItemDetail::find($AC_Item_Detail_Id);
        $AC_Item_Detail['AC_Item'] = $AC_Item_Id;
        $AC_Item_Detail['AC_Detail_Item_Cn'] = $AC_Item_Detail_Cn;
        $AC_Item_Detail['AC_Detail_Item_Abb'] = $AC_Item_Detail_Abb;
        $AC_Item_Detail['AC_Detail Item_Referance'] = $AC_Item_Detail_Referance;
        $AC_Item_Detail['AC_Detail_Item_Description'] = $AC_Detail_Item_Description;
        $AC_Item_Detail['Order_No'] = $Order_No;
        $AC_Item_Detail->save();
        return redirect()->back();
    }

    public function deleteACDetail(Request $request) {
        $typeId = $request->get('typeId');
        AcItemDetail::where('id', $typeId)->delete();
    }

    public function accountManage(Request $request) {
        Util::getMenuInfo($request);

        $list = Account::all();

        $error = Session::get('error');
        return view('operation.account_manage', ['list'=>$list, 'error'=>$error]);
    }

    public function addAccount(Request $request) {
        $accountId = $request->get('accountId');
        $AccountName_Cn = $request->get('AccountName_Cn');
        $AccountName_En = $request->get('AccountName_En');
        $isUse = $request->get('isUse');

        $isExist = Account::where('AccountName_Cn', $AccountName_Cn)
            ->orWhere('AccountName_En', $AccountName_En)->first();

        if(!empty($isExist) && ($isExist['id'] != $accountId)) {
            $error = '名称重复了。';
            return redirect()->back()->with(['error' => $error]);
        }

        if(empty($accountId) || $accountId == 0)
            $account = new Account();
        else
            $account = Account::find($accountId);
        $account['AccountName_Cn'] = $AccountName_Cn;
        $account['AccountName_En'] = $AccountName_En;
        if($isUse == 'on')
            $account['isUse'] = 1;
        else
            $account['isUse'] = 0;

        $account->save();
        return redirect()->back();
    }

    public function deleteAccount(Request $request) {
        $accountId = $request->get('accountId');
        Account::where('id', $accountId)->delete();
        return 1;
    }

    public function payModeManage(Request $request) {
        Util::getMenuInfo($request);

        $list = PayMode::all();

        $error = Session::get('error');
        return view('operation.pay_mode_manage', ['list'=>$list, 'error'=>$error]);
    }

    public function addPayMode(Request $request) {
        $payId = $request->get('payId');
        $PayMode_Cn = $request->get('PayMode_Cn');
        $PayMode_En = $request->get('PayMode_En');
        $isExist = PayMode::where('PayMode_Cn', $PayMode_Cn)
            ->orWhere('PayMode_En', $PayMode_En)->first();
        if(!empty($isExist) && ($isExist['id'] != $payId)) {
            $error = '名称重复了。';
            return redirect()->back()->with(['error' => $error]);
        }
        if(empty($payId) || $payId == 0)
            $pay = new PayMode();
        else
            $pay = PayMode::find($payId);
        $pay['PayMode_Cn'] = $PayMode_Cn;
        $pay['PayMode_En'] = $PayMode_En;
        $pay->save();
        return redirect()->back();
    }

    public function deletePayMode(Request $request) {
        $payId = $request->get('payId');
        PayMode::where('id', $payId)->delete();
        return 1;
    }

    public function navigtionDistance(Request $request) {
        Util::getMenuInfo($request);

        $lport = $request->get('lp');
        $dport = $request->get('dp');

        $list = SailDistance::getDistanceList($lport, $dport);
        $ports = ShipPort::get();

        if(!empty($lport))
            $list->appends(['lp'=>$lport]);
        if(!empty($dport))
            $list->appends(['dp'=>$dport]);

        $error = Session::get('error');

        return view('operation.distance_manage', ['list'=>$list, 'ports'=>$ports, 'lport'=>$lport, 'dport'=>$dport, 'error'=>$error]);
    }

    public function updateDistance(Request $request) {
        $distanceId = $request->get('distanceId');
        $lport = $request->get('LPort');
        $dport = $request->get('DPort');

        $isExist = SailDistance::where('LPortId', $lport)->where('DPortID', $dport)->where('id', '<>', $distanceId)->count();
        if($isExist) {
            $error = '名称重复了。';
            return redirect()->back()->with(['error' => $error]);
        }

        $distance = SailDistance::find($distanceId);
        if(empty($distance))
            $distance = new SailDistance();

        $distance['LPortID'] = $lport;
        $distance['DPortID'] = $dport;
        $distance['SailDistance'] = $request->get('distance');
        $distance->save();

        return redirect()->back();
    }

    public function deleteDistance(Request $request) {
        $distanceId = $request->get('distanceId');
        $distance = SailDistance::find($distanceId);
        $distance->delete();

    }

    public function updateVoyProfit(Request $request) {

        $voyId = $request->get('voyId');
        $income = $request->get('income');
        $expense = $request->get('expense');
        $profit = $request->get('profit');

        $cpInfo = Cp::find($voyId);
        $shipId = $cpInfo->Ship_ID;

        $profitInfo = VoyProfit::where('ShipID', $shipId)->where('VoyId', $voyId)->first();
        if(empty($profitInfo)) {
            $profitInfo = new VoyProfit();
            $profitInfo['ShipID'] = $shipId;
            $profitInfo['VoyId'] = $voyId;
        }

        $profitInfo['Income'] = $income;
        $profitInfo['Expense'] = $expense;
        $profitInfo['Profit'] = $profit;
        $profitInfo->save();

        return 1;
    }
}