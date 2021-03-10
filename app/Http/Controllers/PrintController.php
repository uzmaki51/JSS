<?php
namespace App\Http\Controllers;

use App\Models\Operations\Cp;
use App\Models\Operations\Invoice;
use App\Models\Operations\VoyLog;
use App\Models\Operations\VoyProgramPractice;
use App\Models\Operations\YearlyPlanInput;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipTechnique\ShipAccident;
use App\Models\ShipTechnique\ShipDept;
use App\Models\ShipTechnique\ShipRepair;
use App\Models\ShipTechnique\ShipSurvey;
use App\Models\SupplyPlan\SupplyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;
use Auth;
use App\Models\UserInfo;
use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendTime;
use App\Models\Attend\AttendRest;
use App\Models\Attend\AttendShip;
use App\Models\Member\Unit;
use App\Models\Decision\DecEnvironment;
use App\Models\ShipMember\ShipMember;
use Illuminate\Support\Facades\App;


class PrintController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Print Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $locale = Session::get('locale');
        if(empty($locale)) {
            $locale = Config::get('app.locale');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

		$this->middleware('auth');
	}

    //항차타산표준

    //----------------- 항차타산 (표준) ------------------//
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

        return view('operation.ship_count_standard_print', [
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

    //----------------- 운임계산서 ------------------//
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

        return view('operation.ship_calc_print',
            [   'shipList'  =>  $shipList,
                'shipID'    =>  $shipId,
                'voyList'   =>  $voyNoList,
                'voyId'     =>  $voyId,
                'data'      =>  $data,
                'invoiceList'=>  $invoiceData,
            ]);
    }

    //배수리보고서종합열람
    public function shipRepairAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');

        $recovery = ShipRepair::getRepairSearch($shipId, $voy_number, 1);

        $cps = CP::where('Ship_ID', $shipId)->get();

        return view('shipTechnique.RepairAllBrowse_print',array(
            'RepairInfos'=>$recovery,
            'cps'=>$cps,
            'shipList'=>$shipList,
            'shipId'=>$shipId,
            'voy'=>$voy_number,
            'outMthd' => 1,
        ));
    }

    //배수리 공급계획종합
    public function supplyPlan(Request $request)
    {
        Util::getMenuInfo($request);

        //배목록
        $year=$request->get('year');
        $shipid=$request->get('shipid');
        $deptcount=ShipDept::count();
        $deptInfo=ShipDept::all(['id','Dept_Cn']);

        $shipList=SupplyPlan::getPlanedShipList(0);
        $shipNameCol=$request->get('shipNameCol');

        $nShips=count($shipList);

        $colYear=$request->get('yearCol');

        $tab=$request->get('tab');
        $supplyplanlist=SupplyPlan::getPlanList($year,$shipid);
        $supplyplanlist->appends(['tab'=>$tab,'year'=>$year,'shipid'=>$shipid,'menuId'=>$GLOBALS['selMenu']]);
        if($shipNameCol!=0){
            $nShips=1;
            $ship=ShipRegister::find($shipNameCol);
            $shipNames=$ship['RegNo'];
            $shipcolList=array();
            $shipcolList[]=array('shipName_Cn'=>$ship['shipName_Cn'], 'ShipName_En' => $ship['shipName_En'],'ShipName'=>$shipNames);
            $supplyAmounts=SupplyPlan::where('Yearly','=',$colYear)
                ->where('ShipName','=',$shipNames)->get(['Yearly','Monthly','ShipName','Dept','Amount']);
        }else{
            $shipcolList=$shipList;
            $supplyAmounts=SupplyPlan::where('Yearly','=',$colYear)
                ->get(['Yearly','Monthly','ShipName','Dept','Amount']);
        }

        $amounts=array();
        foreach($supplyAmounts as $supplyAmount)
        {
            $amounts[$supplyAmount['Yearly'].$supplyAmount['ShipName'].$supplyAmount['Dept'].$supplyAmount['Monthly']]=$supplyAmount['Amount'];
            if(!isset($amounts[$supplyAmount['Yearly'].$supplyAmount['ShipName'].$supplyAmount['Dept'].'0']))
                $amounts[$supplyAmount['Yearly'].$supplyAmount['ShipName'].$supplyAmount['Dept'].'0'] = 0;
            $amounts[$supplyAmount['Yearly'].$supplyAmount['ShipName'].$supplyAmount['Dept'].'0'] += $supplyAmount['Amount'];
            $year=$supplyAmount['Yearly'];
            $shipName=$supplyAmount['ShipName'];
            $Month=$supplyAmount['Monthly'];
            $amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].$supplyAmount['ShipName']]=
                SupplyPlan::where('Yearly','=',$year)->where('Monthly','=',$Month)->where('ShipName', $shipName)->sum('Amount');
            if(!isset($amounts[$supplyAmount['Yearly'].'0'.$supplyAmount['ShipName']]))
                $amounts[$supplyAmount['Yearly'].'0'.$supplyAmount['ShipName']] = 0;
            $amounts[$supplyAmount['Yearly'].'0'.$supplyAmount['ShipName']]
                += $amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].$supplyAmount['ShipName']];
            if(!isset($amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].'0']))
                $amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].'0'] = 0;
            $amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].'0']
                += $amounts[$supplyAmount['Yearly'].$supplyAmount['Monthly'].$supplyAmount['ShipName']];
        }
        return view('shipTechnique.shipEquipment.supplyPlan_print', [
            'shipcolList'=>$shipcolList,
            'supplyplanlist'=>$supplyplanlist,
            'nShips'=>$nShips,
            'nDepts'=>$deptcount,
            'deptInfos'=>$deptInfo,
            'supplyAmounts'=>$amounts,
            'yearCol'=>$colYear,
        ]);
    }

    //배사고 보고서종합열람

    public function shipAccidentAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');

        $shipList = ShipRegister::getShipListByOrigin();

        $recovery = ShipAccident::getAccidentSearch($shipId, $voy_number, 1);

        $cps = Cp::where('Ship_ID', $shipId)->get();

        return view('shipTechnique.AccidentAllBrowse_print',
            [   'AccidentInfos' =>  $recovery,
                'cps'           =>  $cps,
                'shipList'      =>  $shipList,
                'shipId'        =>  $shipId,
                'voy'           =>  $voy_number,
                'outMthd' => 1,
            ]);
    }

    //배검사보고서종합열람

    public function shipSurveyAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');
        $survey = ShipSurvey::getSurveySearch($shipId, $voy_number, 1);

        $shipList = ShipRegister::getShipListByOrigin();
        $cps = CP::where('Ship_ID', $shipId)->get();

        return view('shipTechnique.SurveyAllBrowse_print',
            [   'SurveyInfos'=>$survey,
                'cps'=>$cps,
                'shipList'=>$shipList,
                'id'=>$shipId,
                'voy'=>$voy_number,
                'outMthd' => 1,
            ]);
    }

    //기업소성원년보출근일보종합

    public function memberYearReportPrint(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 136;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $memberInfo = UserInfo::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $selMonth = 13;
        if($year == date('Y')){
            $selMonth = date('n') + 1;
        }
        $list = [];
        $list['month'] = $selMonth - 1;

        for($month = 1; $month < $selMonth; $month++) {
            $attendList = AttendUser::getMemberMonthAttend($memberId, $year, $month);

            $monthDay = Util::getDaysOfMonth($year, $month);

            $list[$month.'']['data'] = [];

            $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
            $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

            for($day = 0; $day<$monthDay; $day++){
                $isRegister = 0;
                $isRest = 0;
                foreach($restList as $rest) {
                    $restDay = $rest->day;
                    $date = new \DateTime($restDay);
                    $restDay = $date->format('j') * 1;
                    if($restDay == ($day + 1)) {
                        $userAttend['day'] = $day + 1;
                        $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                        $userAttend['memo'] = $rest->descript;
                        $userAttend['rest'] = 1;
                        $list[$month.'']['data'][] = $userAttend;
                        $isRest = 1;
                        break;
                    }
                }

                if($isRest)
                    continue;

                $attendDay = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
                $curr_day = date('Y-m-d');
                if($attendDay > $curr_day) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                    continue;
                }
                foreach($attendList as $attend) {
                    $attendDay = $attend->regDay;
                    $date = new \DateTime($attendDay);
                    $attendDay = $date->format('j') * 1;
                    if($attendDay == ($day + 1)) {
                        $isRegister = 1;
                        $userAttend['day'] = $attendDay;
                        $userAttend['name'] = $attend->name;
                        $userAttend['memo'] = $attend->memo;
                        $userAttend['rest'] = 0;
                        $list[$month.'']['data'][] = $userAttend;
                        break;
                    }
                }
                if($isRegister == 0) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '未确定';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                }
            }
        }
        //해당수표도장을 얻는다.
        $decEnv = DecEnvironment::find($memberId);
        if(isset($decEnv->signPath))
            $signPath = $decEnv->signPath;
        else
            $signPath = null;
        //출근합계값을 구한다.
        $totalData = array(
            "days"  =>  $request->get("days"),
            "rest"  =>  $request->get("rest"),
            "work"  =>  $request->get("work"),
            "attend"  =>  $request->get("attend"),
            "absence"  =>  $request->get("absence"),
        );
        for($i=2; $i<=14; $i++){
            $totalData["type_{$i}"] = $request->get("type_{$i}");
        }

        return view('business.attend.member_year_attend_print', ['info'=>$memberInfo, 'year'=>$year, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }

    public function shipMemberYearReportPrint(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 136;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $memberInfo = ShipMember::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $selMonth = 13;
        if($year == date('Y')){
            $selMonth = date('n') + 1;
        }
        $list = [];
        $list['month'] = $selMonth - 1;

        for($month = 1; $month < $selMonth; $month++) {
            $attendList = AttendShip::getMemberMonthAttend($memberId, $year, $month);

            $monthDay = Util::getDaysOfMonth($year, $month);

            $list[$month.'']['data'] = [];

            $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
            $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

            for($day = 0; $day<$monthDay; $day++){
                $isRegister = 0;
                $isRest = 0;
                foreach($restList as $rest) {
                    $restDay = $rest->day;
                    $date = new \DateTime($restDay);
                    $restDay = $date->format('j') * 1;
                    if($restDay == ($day + 1)) {
                        $userAttend['day'] = $day + 1;
                        $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                        $userAttend['memo'] = $rest->descript;
                        $userAttend['rest'] = 1;
                        $list[$month.'']['data'][] = $userAttend;
                        $isRest = 1;
                        break;
                    }
                }

                if($isRest)
                    continue;

                $attendDay = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
                $curr_day = date('Y-m-d');
                if($attendDay > $curr_day) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                    continue;
                }
                foreach($attendList as $attend) {
                    $attendDay = $attend->regDay;
                    $date = new \DateTime($attendDay);
                    $attendDay = $date->format('j') * 1;
                    if($attendDay == ($day + 1)) {
                        $isRegister = 1;
                        $userAttend['day'] = $attendDay;
                        $userAttend['name'] = $attend->name;
                        $userAttend['memo'] = $attend->memo;
                        $userAttend['rest'] = 0;
                        $list[$month.'']['data'][] = $userAttend;
                        break;
                    }
                }
                if($isRegister == 0) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '未确定';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                }
            }
        }

        if (isset($memberInfo['signPhoto']))
            $signPath = $memberInfo['signPhoto'];
        else
            $signPath = null;
        //출근합계값을 구한다.
        $totalData = array(
            "days"  =>  $request->get("days"),
            "rest"  =>  $request->get("rest"),
            "work"  =>  $request->get("work"),
            "attend"  =>  $request->get("attend"),
            "absence"  =>  $request->get("absence"),
        );
        for($i=2; $i<=14; $i++){
            $totalData["type_{$i}"] = $request->get("type_{$i}");
        }

        return view('business.attend.ship_member_year_attend_print', ['info'=>$memberInfo, 'year'=>$year, 'list'=>$list, 'signPath'=>$signPath,'totalData'=>$totalData]);
    }

    public function memberMonthAttendPrint(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 43;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        $month = $request->get('month');

        $memberInfo = UserInfo::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

        $attendList = AttendUser::getMemberMonthAttend($memberId, $year, $month);
        $monthDay = Util::getDaysOfMonth($year, $month);
        $curr_day = $monthDay;
        if(($year == date('Y')) && ($month == date('n'))){
            $curr_day = date('j') * 1;
        }

        $list = [];
        for($day = 0; $day<$monthDay; $day++){
            $isRegister = 0;
            $isRest = 0;
            foreach($restList as $rest) {
                $restDay = $rest->day;
                $date = new \DateTime($restDay);
                $restDay = $date->format('j') * 1;
                if($restDay == ($day + 1)) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                    $userAttend['memo'] = $rest->descript;
                    $userAttend['rest'] = 1;
                    $list[] = $userAttend;
                    $isRest = 1;
                    break;
                }
            }

            if($isRest)
                continue;
            if(($day + 1) > $curr_day) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
                continue;
            }
            foreach($attendList as $attend) {
                $attendDay = $attend->regDay;
                $date = new \DateTime($attendDay);
                $attendDay = $date->format('j') * 1;
                if($attendDay == ($day + 1)) {
                    $isRegister = 1;
                    $userAttend['day'] = $attendDay;
                    $userAttend['name'] = $attend->name;
                    $userAttend['memo'] = $attend->memo;
                    $userAttend['rest'] = 0;
                    $list[] = $userAttend;
                    break;
                }
            }
            if($isRegister == 0) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '未确定';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
            }
        }

        //해당수표도장을 얻는다.
        $decEnv = DecEnvironment::find($memberId);
        if(isset($decEnv->signPath))
            $signPath = $decEnv->signPath;
        else
            $signPath = null;

        //출근합계값을 구한다.
        $totalData = array(
            "days"  =>  $request->get("days"),
            "rest"  =>  $request->get("rest"),
            "work"  =>  $request->get("work"),
            "attend"  =>  $request->get("attend"),
            "absence"  =>  $request->get("absence"),
        );
        for($i=2; $i<=14; $i++){
            $totalData["type_{$i}"] = $request->get("type_{$i}");
        }

        return view('business.attend.member_month_attend_print', ['info'=>$memberInfo, 'year'=>$year, 'month'=>$month, 'monthDay'=>$monthDay, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }

    public function shipMemberMonthAttendPrint(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 104;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        $month = $request->get('month');

        $memberInfo = ShipMember::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

        $attendList = AttendShip::getMemberMonthAttend($memberId, $year, $month);
        $monthDay = Util::getDaysOfMonth($year, $month);
        $curr_day = $monthDay;
        if(($year == date('Y')) && ($month == date('n'))){
            $curr_day = date('j') * 1;
        }

        $list = [];
        for($day = 0; $day<$monthDay; $day++){
            $isRegister = 0;
            $isRest = 0;
            foreach($restList as $rest) {
                $restDay = $rest->day;
                $date = new \DateTime($restDay);
                $restDay = $date->format('j') * 1;
                if($restDay == ($day + 1)) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = constant("REST_TYPE_".$rest->state);;
                    $userAttend['memo'] = $rest->descript;
                    $userAttend['rest'] = 1;
                    $list[] = $userAttend;
                    $isRest = 1;
                    break;
                }
            }

            if($isRest)
                continue;
            if(($day + 1) > $curr_day) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
                continue;
            }
            foreach($attendList as $attend) {
                $attendDay = $attend->regDay;
                $date = new \DateTime($attendDay);
                $attendDay = $date->format('j') * 1;
                if($attendDay == ($day + 1)) {
                    $isRegister = 1;
                    $userAttend['day'] = $attendDay;
                    $userAttend['name'] = $attend->name;
                    $userAttend['memo'] = $attend->memo;
                    $userAttend['rest'] = 0;
                    $list[] = $userAttend;
                    break;
                }
            }
            if($isRegister == 0) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '未确定';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
            }
        }

        if (isset($memberInfo['signPhoto']))
            $signPath = $memberInfo['signPhoto'];
        else
            $signPath = null;

        //출근합계값을 구한다.
        $totalData = array(
            "days"  =>  $request->get("days"),
            "rest"  =>  $request->get("rest"),
            "work"  =>  $request->get("work"),
            "attend"  =>  $request->get("attend"),
            "absence"  =>  $request->get("absence"),
        );
        for($i=2; $i<=14; $i++){
            $totalData["type_{$i}"] = $request->get("type_{$i}");
        }

        return view('business.attend.ship_member_month_attend_print', ['info'=>$memberInfo, 'year'=>$year, 'month'=>$month, 'monthDay'=>$monthDay, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }
}
