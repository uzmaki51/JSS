<?php
namespace App\Http\Controllers;


use App\Models\Attend\AttendRest;
use App\Models\Attend\AttendShip;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendUser;
use App\Models\Member\Unit;
use App\Models\Operations\Cargo;
use App\Models\Operations\Cp;
use App\Models\Operations\Invoice;
use App\Models\Operations\ShipOilSupply;
use App\Models\Operations\VoyLog;
use App\Models\Operations\VoyProgramPractice;
use App\Models\Operations\VoyStatus;
use App\Models\Operations\VoyStatusEvent;
use App\Models\Operations\VoyStatusType;
use App\Models\Operations\YearlyPlanInput;
use App\Models\Operations\YearlyQuarterMonthPlan;
use App\Models\Plan\MainPlan;
use App\Models\Plan\ReportPerson;
use App\Models\Plan\ReportPersonMonth;
use App\Models\Plan\ReportPersonWeek;
use App\Models\Plan\SubPlan;
use App\Models\Plan\UnitMonthReport;
use App\Models\Plan\UnitWeekReport;
use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipCertList;
use App\Models\ShipManage\ShipCertRegistry;
use App\Models\ShipManage\ShipEquipment;
use App\Models\ShipManage\ShipEquipmentMainKind;
use App\Models\ShipManage\ShipEquipmentRegKind;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\SecurityCert;
use App\Models\ShipMember\ShipMember;
use App\Models\ShipMember\ShipMemberCapacity;
use App\Models\ShipMember\ShipMemberExaming;
use App\Models\ShipMember\ShipMemberSchool;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipMember\ShipSTCWCode;
use App\Models\ShipTechnique\EquipmentUnit;
use App\Models\ShipTechnique\ShipDept;
use App\Models\ShipTechnique\ShipPort;
use App\Models\ShipTechnique\ShipRepair;
use App\Models\ShipTechnique\ShipSupply;
use App\Models\SupplyPlan\SupplyPlan;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Util;
use App\Models\Menu;
use Auth;
use Illuminate\Support\Facades\App;
use App\Models\ShipTechnique\ShipAccident;
use App\Models\ShipTechnique\ShipSurvey;
use DB;

class ExcelController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Excel Controller
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

    //???????????????????????????
    public function enterpriseMonthAttend(Request $request)
    {

        Util::getMenuInfo($request);

        //????????? ???????????????
        $year = is_null($request->get('selYear')) ? date('Y') : $request->get('selYear');
        $month = is_null($request->get('selMonth')) ? date('n') : $request->get('selMonth');
        $days = Util::getDaysOfMonth($year, $month);

        //???????????? ??????????????? ??????
        $start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, $month, $days, $year)); // ????????? ???????????????
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //?????????????????? ??????
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if(($year == date('Y')) && ($month == date('n'))){
            $work_days = date('j') * 1 - $total_rest;
        }

        $shipId = $request->get('ship');
        $unitId = $request->get('unit');
        $memberName = $request->get('name');

        // ???????????? ????????? ?????? ??????(?????? ??? ??????)?????? ????????? ?????????.
        $memberList = UserInfo::enterpriseTotalMemberList($unitId, $shipId, $memberName);

        $userStr = '';
        $crewStr = '';
        foreach($memberList as $members) {
            if($members->memberType == 1)
                $userStr = $members->idStr;
            else
                $crewStr = $members->idStr;
        }

        $userAttend = AttendUser::getAttendDaysOfMonthByAttendType($userStr, $start, $end);
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($crewStr, $start, $end);
        $typeList = AttendType::all();

        $list = array();
        $totl_absen = 0;
        $totl_attend = 0;

        // ---------  ???????????? ?????? ??????????????? ????????????.  ------------
        $attendMember = array();
        if(count($userAttend) > 0) {
            $attendMember['id'] = $userAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($userAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                $attendMember['isShip'] = 0;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // ??????????????? ???????????? ?????? ????????? ????????????????????? ??????.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 0;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // ?????????????????? ?????? ??????
        if(count($userAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        // ---------  ???????????? ?????? ??????????????? ????????????.  ------------
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        if(count($crewAttend) > 0) {
            $attendMember['id'] = $crewAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($crewAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                $attendMember['isShip'] = 1;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // ??????????????? ???????????? ?????? ????????? ????????????????????? ??????.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 1;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // ?????????????????? ?????? ??????
        if(count($crewAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        $ships = ShipRegister::getShipListOnlyOrigin();
        $units = Unit::unitFullNameList();

        $unit = '??????';
        foreach($units as $item) if($item['id'] == $unitId) $unit = $item['title'];
        $ship = '??????';
        foreach($ships as $item) if($item['id'] == $shipId) $ship = $item['name'];
        $excel_title = $year.'??? '.$month.'??? ??????:'.$unit.' ????????????: '.$ship;
        if(!empty($memberName)) $excel_title .= ' ??????'.$memberName;
        return View('business.attend.enterprise_month_attend',
            [   'dates'     =>  $dates,
                'year'      =>  $year,
                'month'     =>  $month,
                'list'      =>  $list,
                'typeList'  =>  $typeList,
                'units'     =>  $units,
                'ships'     =>  $ships,
                'unitId'    =>  $unitId,
                'shipId'    =>  $shipId,
                'memberName'=>  $memberName,
                'pageHtml'  =>  '',
                'page'      =>  0,
                'excel'      =>  1,
                'excel_title' => $excel_title,
                'excel_name' => '???????????????????????? '.$excel_title
            ]);
    }

    //???????????????????????????
    public function enterpriseDayAttend(Request $request) {
        Util::getMenuInfo($request);

        $selDate = is_null($request->get('selDate')) ? date('Y/m/d') : $request->get('selDate');

        //??????????????? ?????????.
        $enterprise = Unit::where('parentId', '0')->orderBy('id')->first();
        if(is_null($enterprise))
            return;

        $units = array();
        $subUnits = Unit::where('parentId', $enterprise['id'])->get();
        $enterprise['unitType'] = 1;
        $units[] = $enterprise;
        foreach($subUnits as $unit){
            $units[] = $unit;
        }
        $typeList = AttendType::all();
        foreach($units as $unit) {
            if($unit['parentId'] == 0) {
                $memberList = UserInfo::getDirectlyUserList($unit['id']); // ??????????????? ??????????????? ID??? ???????????? ???????????? ?????????.
                $unit['title'] = '??????????????????????????????';
            } else {
                $memberList = UserInfo::getUserListByUnit($unit['id']); // ??????????????? ??????????????? ID??? ???????????? ???????????? ?????????.
            }
            $resultList = AttendUser::getAttendStateByDate($memberList, $selDate);
            $valueList = array();
            $attendCount = 0;
            $absenceCount = 0;
            $memberCount = 0;
            foreach ($typeList as $type) {
                $valueList[$type['id']] = 0;
                foreach ($resultList as $result) {
                    if ($result['statusId'] == $type['id'])
                        $valueList[$type['id']] = $result['ucount'];
                }
                if ($type['id'] < 4)
                    $attendCount += $valueList[$type['id']];
                else
                    $absenceCount += $valueList[$type['id']];
            }

            if(!empty($memberList))
                $memberCount = count(explode(',', $memberList));

            if(($attendCount + $absenceCount) < $memberCount) {
                $valueList[4] += $memberCount - ($attendCount + $absenceCount);
                $absenceCount = $memberCount - $attendCount;
            }
            $unit['unitType'] = 1;
            $unit['attend'] = $attendCount;
            $unit['absence'] = $absenceCount;
            $unit['userCount'] = $memberCount;
            $unit['valueList'] = $valueList;
        }

        $shipList = Ship::all(['id', 'name']);
        foreach($shipList as $ship){
            $shipMemberList = ShipMember::getMemberListByCommar($ship['id']); // ???????????? ???????????? ID??? ???????????? ???????????? ?????????.
            $resultList = AttendShip::getAttendStateByDate($shipMemberList, $selDate);
            $valueList = array();
            $attendCount = 0;
            $absenceCount = 0;
            $memberCount = 0;
            foreach ($typeList as $type) {
                $valueList[$type['id']] = 0;
                foreach ($resultList as $result) {
                    if ($result['statusId'] == $type['id'])
                        $valueList[$type['id']] = $result['ucount'];
                }
                if ($type['id'] < 4)
                    $attendCount += $valueList[$type['id']];
                else
                    $absenceCount += $valueList[$type['id']];
            }

            if(!empty($shipMemberList))
                $memberCount = count(explode(',', $shipMemberList));

            if(($attendCount + $absenceCount) < $memberCount) {
                $valueList[4] += $memberCount - ($attendCount + $absenceCount);
                $absenceCount = $memberCount - $attendCount;
            }

            $ship['title'] = $ship['name'];
            $ship['unitType'] = 0;
            $ship['attend'] = $attendCount;
            $ship['absence'] = $absenceCount;
            $ship['userCount'] = $memberCount;
            $ship['valueList'] = $valueList;

            $units[] = $ship;
        }

        $shipMemberList = ShipMember::getMemberListByCommar(0); // ???????????? ???????????? ID??? ???????????? ???????????? ?????????.
        $resultList = AttendShip::getAttendStateByDate($shipMemberList, $selDate);
        $valueList = array();
        $attendCount = 0;
        $absenceCount = 0;
        $memberCount = 0;
        foreach ($typeList as $type) {
            $valueList[$type['id']] = 0;
            foreach ($resultList as $result) {
                if ($result['statusId'] == $type['id'])
                    $valueList[$type['id']] = $result['ucount'];
            }
            if ($type['id'] < 4)
                $attendCount += $valueList[$type['id']];
            else
                $absenceCount += $valueList[$type['id']];
        }

        if(!empty($shipMemberList))
            $memberCount = count(explode(',', $shipMemberList));

        if(($attendCount + $absenceCount) < $memberCount) {
            $valueList[4] += $memberCount - ($attendCount + $absenceCount);
            $absenceCount = $memberCount - $attendCount;
        }

        $ship = new Ship();
        $ship['title'] = '????????????';
        $ship['unitType'] = 0;
        $ship['attend'] = $attendCount;
        $ship['absence'] = $absenceCount;
        $ship['userCount'] = $memberCount;
        $ship['valueList'] = $valueList;

        $units[] = $ship;

        $excel_title = date('Y??? m??? d???');
        return View('business.attend.enterprise_day_attend',
            [   'units'     => $units,
                'typeList'  =>  $typeList,
                'selDate'   =>  $selDate,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '????????????????????????-'.$excel_title,
            ]);
    }

    // ---------------------     ????????????   ---------------------
    // ?????????????????? ??????????????????
    public function reportPerson(Request $request)
    {
        Util::getMenuInfo($request);

        $cur_date = Array();
        $cur_date['date'] = date('Y-m-d');
        $cur_date['year'] = date('Y');
        $cur_date['month'] = date('n');
        $cur_date['day'] = date('j');
        $cur_date['weekday'] = date('w');
        $cur_date['week'] = date('W');
        $cur_date['week'] = $cur_date['week'] - date('W', mktime(0, 0, 0, $cur_date['month'], 1, $cur_date['year'])) + 1;
        $weekdays = array("???","???","???","???","???","???","???");

        $i = $cur_date['weekday'];
        $date = new \DateTime(date("Y-m-d"));
        $date -> modify("-$i day");
        $start_date = $date -> format("Y-m-d");
        $date = new \DateTime($start_date);
        $date -> modify("+6 day");
        $end_date = $date -> format("Y-m-d");

        $user = Auth::user();
        $today = date('Y-m-d');

        // ?????? ????????? ?????????????????????
        $all_person_plans = array();
        for ($i = 0; $i < 7; $i++) {
            $date = new \DateTime($start_date);
            $date->modify("+$i day");
            $dateStr = $date->format('Y-m-d');
            $reportList = ReportPerson::weekReportList($user['id'], $dateStr);
            foreach ($reportList as $person_report) {
                // ?????? ????????? ????????? ????????? ???????????? ??????
                $person_report->selDate = $dateStr;
                $person_report->dateStr = $date->format("n???j???").'('.$weekdays[$date->format("w")].')';
                if($dateStr == $today)
                    $person_report->active = 1;
                else
                    $person_report->active = 0;
                $all_person_plans[] = $person_report;
            }

            if(count($reportList) == 0) {
                $person_report = new \stdClass();
                $person_report->id = 0;
                $person_report->planId = 0;
                $person_report->selDate = $dateStr;
                $person_report->dateStr = $date->format("n???j???").'('.$weekdays[$date->format("w")].')';
                $person_report->name = '';
                $person_report->planTitle = '';
                $person_report->color = 'fff';
                $person_report->rate = '';
                $person_report->plan = '';
                $person_report->report = '';
                $person_report->update_at = '';
                if($dateStr == $today)
                    $person_report->active = 1;
                else
                    $person_report->active = 0;

                $all_person_plans[] = $person_report;
            }
        }

        // ???????????? ????????????
        $main_plans = MainPlan::getWeekMainPlan($start_date, $end_date);
        // ????????? ????????????
        $subReportList = SubPlan::getSubPlanByDate($user->id, $start_date, $end_date);

        $excel_title = $cur_date['year'].'??? '.$cur_date['month'].'??? '.$cur_date['week'].'???';
        return view('business.plan.reportPerson',
            [   'all_plans'      => $all_person_plans,
                'cur_date'       => $cur_date,
                'start_date'     => $start_date,
                'end_date'       => $end_date,
                'sub_plan_list'  => $subReportList,
                'main_plans'     => $main_plans,
                'excel'          => 1,
                'excel_title'    => $excel_title,
                'excel_name'     => '??????????????????-????????????-'.$excel_title,
            ]);
    }

    //????????????
    public function reportPersonUpdateWeekList(Request $request)
    {

        $user = Auth::user();
        if (!empty($request->get('plan'))) {
            $id = $request->get('reportId');
            if(empty($id)){
                $report = new ReportPersonWeek();
                $report['userId'] = $user['id'];
                $report['planYear'] = $request->get('year');
                $report['planWeek'] = $request->get('week');
            } else
                $report = ReportPersonWeek::find($id);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report->save();
        }

        $year = $request->get('year');
        $month = $request->get('month');

        if($month == 0) {
            $date = new \DateTime(date('Y-m-d'));
            $year = date('Y');
            $month = date('n');
            $selDate = mktime(0, 0, 0, $month, 1, $year);

        } else {
            $selDate = mktime(0, 0, 0, $month, 1 ,$year);
        }

        $cur_date = Array();
        $cur_date['year'] = $year;
        $cur_date['month'] = $month;
        $cur_date['week'] = date('W');
        $endDate = mktime(0, 0, 0, $cur_date['month'] + 1, 1, $cur_date['year']);
        $startWeek = date('W',$selDate);
        $endWeek = date('W', $endDate);

        $date = new \DateTime(date("Y-n-j", $selDate));

        $firstDayWeek = date('w', $selDate);
        if($firstDayWeek > 3) {
            $startWeek = $startWeek + 1;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDate);
        if($endDayWeek < 3)
            $endWeek = $endWeek - 1;

        $all_plans = array();
        $index = 1;
        for ($week = $startWeek; $week <= $endWeek; $week++) {

            $plan = ReportPersonWeek::where('userId', $user->id)
                ->where('planYear', $year)
                ->where('planWeek', $week)
                ->first();

            if (is_null($plan)) {
                $plan = new ReportPersonWeek;
                $plan['planYear'] = $year;
                $plan['planWeek'] = $week;
            }

            $start = $date->format('n.j');
            $date->modify("+6 day");
            $end = $date->format('n.j');
            $date->modify('+1 day');

            $plan['dateStr'] = $index.'??? ('.$start.'~'.$end.')';
            $all_plans[] = $plan;
            $index++;
        }

        $excel_title = $cur_date['year'].'??? '.$cur_date['month'].'???';
        return view('business.plan.reportPersonList',
            [
                'main_plans'=>  $all_plans,
                'cur_date'  =>  $cur_date,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '??????????????????-????????????-'.$excel_title,
            ]);
    }

    //??????????????????
    public function reportPersonUpdateMonthList(Request $request)
    {
        $user = Auth::user();
        if(!empty($request->get('plan'))){
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new ReportPersonMonth();
                $report['userId'] = $user['id'];
                $report['planYear'] = $request->get('year');
                $report['planMonth'] = $request->get('month');
            } else
                $report = ReportPersonMonth::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report->save();
        }

        $year = $request->get('year');
        $cur_date = Array();
        $cur_date['year'] = date('Y');
        $cur_date['month'] = date('n');
        $cur_date['selYear'] = $year;

        $all_plans = Array();
        for ($month = 1; $month < 13; $month++) {
            $plan = ReportPersonMonth::where('userId', '=', $user->id)
                ->where('planYear', $year)
                ->where('planMonth', $month)
                ->first();
            if(is_null($plan)) {
                $plan = new ReportPersonMonth();
                $plan['planYear'] = $year;
                $plan['planMonth'] = $month;
            }

            $all_plans[] = $plan;
        }
        $excel_title = $cur_date['year'].'???';
        return view('business.plan.reportPersonListMonth',
            [
                'main_plans' => $all_plans,
                'cur_date'   => $cur_date,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '??????????????????-????????????-'.$excel_title,
            ]);
    }

    // ??????????????????????????? ????????????
    public function reportPersonUpdateAllList(Request $request)
    {
        $selDate = $request->get('selDate');
        $unitName = $request->get('unit');

        if(is_null($selDate))
            $selDate = date('Y-m-d');

        $unitId = 0;
        if(!is_null($unitName) && (!empty($unitName))) {
            $unitId = Unit::where('title', $unitName)->first()->id;
        }

        $memberList = UserInfo::getUserSimpleListByUnit($unitId);

        $allList = array();
        foreach ($memberList as $member) {
            $reportList = ReportPerson::where('userId', $member['id'])
                ->where('create_plan', $selDate)
                ->get();
            foreach($reportList as $report) {
                $planReport = array();
                $planReport['realname'] = $member['realname'];
                $planReport['title'] = $member['title'];
                $planReport['pos'] = $member['pos'];
                $planReport['update_at'] = $report['update_at'];
                $planReport['mainPlan'] = $report['subPlan']['mainPlan']['name'];
                $planReport['task'] = $report['subPlan']['planTitle'];
                $planReport['rate'] = $report['rate'];
                $planReport['plan'] = $report['plan'];
                $planReport['report'] = $report['report'];
                $allList[] = $planReport;
            }
            if(count($reportList) == 0) {
                $planReport = array();
                $planReport['realname'] = $member['realname'];
                $planReport['title'] = $member['title'];
                $planReport['pos'] = $member['pos'];
                $planReport['update_at'] = '';
                $planReport['mainPlan'] = '';
                $planReport['task'] = '';
                $planReport['rate'] = '';
                $planReport['plan'] = '';
                $planReport['report'] = '';
                $allList[] = $planReport;
            }
        }
        $excel_title = '????????????: ';
        if(!empty($unitName)) $excel_title .= $unitName; else $excel_title .= '??????';
        $excel_title .= ' ??????: '.$selDate;
        return view('business.plan.allReportList', array(
            'list' => $allList,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '????????????????????????-'.$excel_title,
        ));
    }

    // ????????? ??????????????????
    public function reportUnitWeekRead(Request $request) {

        Util::getMenuInfo($request);

        $selDate = $request->get('selDate');
        if(is_null($selDate) || empty($selDate))
            $selDate = date('Y-n-j');

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;
        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), $unitId);

        $excel_title = $curDate['year'].'??? '.$curDate['month'].'???';
        return view('business.plan.unit_week_read_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '??????????????????-'.$excel_title,
        ));
    }

    // ????????? ??????????????????
    public function reportPerMemberWeekExcel(Request $request) {

        $year = $request->get('year');
        $month = $request->get('month');
        $selWeek = $request->get('week');

        $curDate['year'] = $year;
        $curDate['month'] = $month;
        $curDate['week'] = $selWeek;

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        if($month == 12)
            $endDay = mktime(0, 0, 0, $month, 31, $year);
        else
            $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if(($endDayWeek < 3) && ($endDayWeek > 0))
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weekReportList = ReportPersonWeek::getMemberReportWeek($year, $selWeek);

        $index = $selWeek - $startWeek + 1;
        $plusDay = ($index - 1) * 7;
        $date->modify("+$plusDay day");
        $startStr = $date->format('m.d');
        $date->modify("+6 day");
        $endStr = $date->format('m.d');
        $weekTitle = $index . '???' . ' (' . $startStr . '~' . $endStr . ')';

        $excel_title = $curDate['year'].'??? '.$curDate['month'].'??? '.$weekTitle;
        return view('business.plan.per_member_week', array(
            'list'=>$weekReportList,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????????????????? - '.$excel_title,
        ));
    }

    public function reportPerMemberMonthExcel(Request $request) {
        $year = $request->get('year');
        $month = $request->get('month');

        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $monthReportList = ReportPersonMonth::getMemberReportMonth($year, $month);
        $excel_title = $curDate['year'].'??? '.$curDate['month'].'??? ';
        return view('business.plan.per_member_week', array(
            'list'=>$monthReportList,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????????????????? - '.$excel_title,
        ));
    }

    // ????????? ?????? ????????? ??????????????????
    private function getUnitWeekList($dateStr, $unitId) {

        $selDate = new \DateTime($dateStr);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');

        $today = new \DateTime();
        $selWeek = $today->format('W');

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if(($endDayWeek > 0) && ($endDayWeek < 3))
            $endWeek = $endWeek - 1;

        $list = array();
        $index = 1;
        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('n.j');
            $date->modify("+6 day");
            $endStr = $date->format('n.j');
            $report = UnitWeekReport::where('unitId', $unitId)
                ->where('planYear', $year)
                ->where('planWeek', $week)
                ->first();
            if(is_null($report)) {
                $report = new UnitWeekReport();
                $report['planYear'] = $year;
                $report['planWeek'] = $week;
            }
            if($week == $selWeek)
                $report['select'] = 1;
            else
                $report['select'] = 0;
            $report['selDate'] = $month.'???'.$index.'???'.'('.$startStr.'~'.$endStr.')';
            $list[] = $report;
            $index++;
            $date->modify("+1 day");
        }

        return $list;
    }

    // ????????? ??????????????????
    public function reportUnitMonthRead(Request $request) {

        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;

        $list = $this->getUnitMonthList($year, $unitId);

        $excel_title = $year.'???';
        return view('business.plan.unit_month_read_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '??????????????????-'.$excel_title,
        ));
    }

    // ????????? ????????? ????????? ??????????????????
    private function getUnitMonthList($year, $unitId) {

        $today = new \DateTime();
        $todayMonth = $today->format('n');
        $todayYear = $today->format('Y');

        $list = array();
        for($m = 1; $m<13; $m++) {

            $report = UnitMonthReport::where('unitId', $unitId)
                ->where('planYear', $year)
                ->where('planMonth', $m)
                ->first();
            if(is_null($report)) {
                $report = new UnitMonthReport();
                $report['planYear'] = $year;
                $report['planMonth'] = $m;
            }
            if(($todayYear == $year) && ($todayMonth == $m))
                $report['select'] = 1;
            else
                $report['select'] = 0;
            $report['selDate'] = $year.'???'.$m.'???';
            $list[] = $report;
        }

        return $list;
    }

    // ????????? ????????? ??????????????????
    public function reportPerUnit(Request $request) {

        Util::getMenuInfo($request);

        $year = $request->get('year');
        $month = $request->get('month');
        $selWeek = $request->get('week');

        $curDate['year'] = $year;
        $curDate['month'] = $month;
        $curDate['week'] = $selWeek;

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if($endDayWeek < 3)
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weeklist = array();
        $index = 1;

        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('m.d');
            $date->modify("+6 day");
            $endStr = $date->format('m.d');

            $weekObj['week'] = $week;
            $weekObj['title'] = $index . '???' . ' (' . $startStr . '~' . $endStr . ')';
            $weeklist[] = $weekObj;
            $index++;
            $date->modify("+1 day");
        }

        $list = UnitWeekReport::getReportPerUnit($year, $selWeek);
        $excel_title = $year.'??? '.$month.'??? ';
        $weekName = '';
        foreach($weeklist as $weekItem) {
            if($weekItem['week'] == $selWeek)
                $weekName = $weekItem['title'];
        }
        $excel_title .= $weekName.' ???';
        return view('business.plan.per_unit_week_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '????????????????????????-'.$excel_title,
        ));
    }

    // ????????? ????????? ????????????.
    public function reportPerUnitMonth(Request $request)
    {

        $year = $request->get('year');
        $month = $request->get('month');
        if (empty($year)) {
            $year = date('Y');
            $month = date('n');
        }

        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $list = UnitMonthReport::getReportPerUnit($year, $month);
        $excel_title = $year.'??? '.$month.'???';
        return view('business.plan.per_unit_week_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '????????????????????????-'.$excel_title,
        ));
    }

    // ????????? ??????????????????
    public function reportEnterpriseWeekRead(Request $request) {

        Util::getMenuInfo($request);

        $selDate = $request->get('selDate');

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), 0);

        $excel_title = $year.'??? '.$month.'???';
        return view('business.plan.unit_week_read_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????????-'.$excel_title,
        ));
    }

    // ????????? ??????????????????
    public function reportEnterpriseMonthRead(Request $request) {

        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $user = Auth::user();
        $unitId = 0;

        $list = $this->getUnitMonthList($year, $unitId);

        $excel_title = $year.'???';
        return view('business.plan.unit_month_read_table', array(
            'list'=>$list,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????????-'.$excel_title,
        ));
    }

    //?????????????????????
    public function loadShipGeneralInfos(Request $request)
    {
        $ship_infolist = ShipRegister::select('tb_ship_register.*', 'tb_ship.name', 'tb_ship.shipNo', 'tb_ship.person_num', 'tb_ship_type.ShipType_Cn', 'tb_ship_type.ShipType', DB::raw('IFNULL(tb_ship.id, 100) as num'))
            ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
            ->leftJoin('tb_ship_type', 'tb_ship_register.ShipType', '=', 'tb_ship_type.id')
            ->orderBy('num')
            ->get();

        foreach($ship_infolist as $info) {
            $query = "SELECT COUNT(*) AS navi_count, member.personSum FROM tb_ship_member
                        LEFT JOIN ( SELECT RegNo, SUM(PersonNum) AS personSum FROM tb_ship_msmcdata WHERE RegNo = '".$info['RegNo']."') AS member
                        ON  tb_ship_member.ShipId = member.RegNo
                        WHERE ShipId = '".$info->RegNo."'";
            $result = DB::select($query);
            if(count($result) > 0)
                $result = $result[0];
            $info['navi_count'] = $result->navi_count;
            $info['personSum'] = $result->personSum;
        }

        return view('shipManage.shipinfo', array(
            'list'=> $ship_infolist,
            'excel' => 1,
            'excel_name' => '????????????'
        ));
    }

    //???????????????
    public function shipCertList(Request $request)
    {
        $shipRegList = ShipRegister::getShipListByOrigin();

        $shipId = $request->get('shipId');
        $certName = $request->get('certName');
        $issuUnit = $request->get('issuUnit');
        $expireMonth = $request->get('expireMonth');

        $shipNameInfo = null;
        if(isset($shipId))
            $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);

        $certType = ShipCertList::all();
        $certList = ShipCertRegistry::getShipCertList($shipId, $certName, $issuUnit, $expireMonth);

        $shipName = '??????';
        foreach($shipRegList as $ship) if($shipId == $ship['RegNo']) $shipName = $ship['shipName_Cn'];
        $excel_title = '????????????: '.$shipName;

        return view('shipManage.ship_cert_registry',
            [   'shipList'  =>  $shipRegList,
                'shipName'  =>  $shipNameInfo,
                'list'      =>  $certList,
                'typeList'  =>  $certType,
                'shipId'    =>  $shipId,
                'certName'  =>  $certName,
                'issuUnit'  =>  $issuUnit,
                'expireMonth'=> $expireMonth,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '????????????-'.$excel_title,
            ]);
    }

    //??????????????????
    public function shipCertManage(Request $request)
    {
        Util::getMenuInfo($request);
        $cert = $request->get('cert');

        $query = ShipCertList::query();
        if(isset($cert))
            $query->where('CertName_Cn', 'like', '%'.$cert.'%');
        $certList = $query->get();

        $error = Session::get('error');

        return view('shipManage.cert_manage', [
            'list'=>$certList,
            'cert'=>$cert,
            'error'=>$error,
            'excel'=>1,
            'excel_name'=>'??????????????????',
        ]);
    }

    //??????????????????
    public function shipEquepmentByKind(Request $request) {
        $kindId = $request->get('kindId');
        $shipId = $request->get('shipId');
        $equipmentName = $request->get('keyword');

        $shipRegList = ShipRegister::all(['RegNo', 'shipName_Cn']);
        $mainKind = ShipEquipmentRegKind::mainKindByShip($shipId);

        $query = ShipEquipment::where('KindOfEuipmentId', $kindId);
        if(!empty($equipmentName))
            $query->where('Euipment_Cn', 'like', '%'.$equipmentName.'%')
                ->orWhere('Euipment_En', 'like', '%'.$equipmentName.'%');

        $list = $query->get();

        $shipName = '??????';
        foreach($shipRegList as $ship) {
            if($ship['RegNo'] == $shipId) $shipName = $ship['shipName_Cn'];
        }
        $excel_title = '??????: '.$shipName;
        return view('shipManage.ship_equipment_table', [
            'list'=>$list,
            'shipId'=>$shipId,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '????????????????????????-'.$excel_title,
        ]);
    }

    //????????????
    public function totalShipMember(Request $request) {
        Util::getMenuInfo($request);

        $regShip = $request->get('regShip');
        $bookShip = $request->get('bookShip');
        $origShip = $request->get('origShip');
        $regStatus = $request->get('regStatus');

        $list = ShipMember::getTotalMemberList($regShip, $bookShip, $origShip, $regStatus);

        $shipList = ShipRegister::select('tb_ship_register.RegNo', 'tb_ship_register.shipName_Cn', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
            ->get();

        $ko_ship_list = Ship::select('id', 'name')->get();
        foreach($list as $member) {
            $ship = ShipRegister::where('RegNo', $member['ShipID_Book'])->first();
            if($ship)
                $member['book_ship'] = $ship['shipName_Cn'];
            $ship = Ship::find($member['ShipID_organization']);
            if($ship)
                $member['origin_ship'] = $ship['name'];
            $duty = ShipPosition::find($member['DutyID_Book']);
            if($duty)
                $member['book_duty'] = $duty['Duty'];

            $duty = ShipPosition::find($member['pos']);
            if($duty)
                $member['orgin_duty'] = $duty['Duty'];
        }

        $shipName = '??????'; $shipBookName = '??????';
        foreach($shipList as $ship) {
            if ($ship['RegNo'] == $regShip) {
                if (empty($ship['name'])) $shipName = $ship['shipName_Cn'];
                else $shipName = $ship['name'] . ' | ' . $ship['shipName_Cn'];
            }
            if($ship['RegNo'] == $bookShip) $shipBookName = $ship['shipName_Cn'];
        }
        $shipKoName = '??????';
        foreach($ko_ship_list as $ship) {
            if($ship['id'] == $origShip) $shipKoName = $ship['name'];
        }
        $excel_title = '????????????(??????): '.$shipName.' ????????????(?????????): '.$shipBookName.' ????????????(????????????): '.$shipKoName;
        return view('shipMember.total_member_list', [
            'list'=>$list,
            'shipList'=>$shipList,
            'ko_ship_list'=>$ko_ship_list,
            'regShip'=>$regShip,
            'bookShip'=>$bookShip,
            'origShip'=>$origShip,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '????????????-'.$excel_title,
        ]);
    }

    //????????????
    public function memberCertList(Request $request) {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();
        $posList = ShipPosition::orderBy('id')->get();
        $capacityList = ShipMemberCapacity::orderBy('id')->get();

        $shipId = $request->get('ship');
        $capacityId = $request->get('capacity');
        $posId = $request->get('pos');
        $month = $request->get('month');
        $page = $request->get('page');
        if(empty($page))
            $page = 1;

        $list = ShipMember::getMemberCertList($shipId, $posId, $capacityId, $month, -1);
        $pageCount = ShipMember::countMemberCertList($shipId, $posId, $capacityId, $month);
        foreach($list as $member) {

            $school = ShipMemberSchool::where('memberId', $member->crewId)->orderBy('id', 'desc')->first();
            if(!empty($school)) {
                $member->school = $school['SchoolName'];
                $member->school_path = $school['GOC'];
            } else {
                $member->school = '';
                $member->school_path = null;
            }

            $security = SecurityCert::find($member->TCP_certID);
            if(!empty($security))
                $member->securityItem = $security['title'];
            else
                $member->securityItem = '';

            $security = SecurityCert::find($member->SSO_certID);
            if(!empty($security))
                $member->securityTrain = $security['title'];
            else
                $member->securityTrain = '';

            switch($member->ASD_typeID) {
                case 1:
                    $member->ASDType = '??????'; break;
                case 2:
                    $member->ASDType = '2'; break;  //??????
                default:
                    $member->ASDType = '';
            }

        }

        $pageHtml = Util::makePaginateHtml($pageCount, $page);

        $shipName = '??????';
        foreach($shipList as $ship) {
            if($shipId == $ship['RegNo']) {
                if(empty($ship['name'])) {
                    $shipName = $ship['name'].' | '.$ship['shipName_Cn'];
                } else {
                    $shipName = $ship['shipName_Cn'];
                }
            }
        }
        $Duty = '??????';
        foreach($posList as $pos)
            if($posId == $pos['id'])
                $Duty = $pos['Duty'].' | '.$pos['Duty_En'];
        $Capacity = '??????';
        foreach($capacityList as $capacity)
            if($capacityId == $capacity['id'])
                $Capacity = $capacity['Capacity'];
        $excel_title = '????????????: '.$shipName.' ??????: '.$Duty.' ??????????????????: '.$Capacity;
        return view('shipMember.excel_member_cert_list',
            [	'list'		=>		$list,
                'shipList'	=>		$shipList,
                'posList'	=>		$posList,
                'capacityList'=>	$capacityList,
                'shipId'	=>		$shipId,
                'posId'		=>		$posId,
                'capacityId'=>		$capacityId,
                'expire'	=>		$month,
                'page'		=>		$page,
                'pageHtml'	=>		$pageHtml,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '????????????-'.$excel_title,
            ]);
    }

    // ???????????????????????????
    public function integretedMemberExaming(Request $request) {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $shipList = ShipRegister::all(['RegNo', 'shipName_Cn']);
        $paramExamCode = $request->get('ExamCode');
        $members = ShipMember::getMemberSimpleInfo($shipId,10000);
        $members->appends(['shipId'=>$shipId, 'ExamCode' => $paramExamCode]);

        $memberList = array();
        foreach($members as $member)
            $memberList[] = $member['id'];

        $list = ShipMemberExaming::getMemberMarks($memberList);
        if($paramExamCode == '')
            $examCodes = ShipMemberExaming::select('ExamCode')->whereIn('memberId', $memberList)->groupBy('ExamCode')->orderBy('ExamCode')->get();
        else
            $examCodes = ShipMemberExaming::select('ExamCode')->whereIn('memberId', $memberList)->where('ExamCode', $paramExamCode)->groupBy('ExamCode')->orderBy('ExamCode')->get();
        foreach($examCodes as $examing)
            $examing['subjects'] = ShipMemberExaming::select('Subject')->where('ExamCode', $examing['ExamCode'])->groupBy('Subject')->get();

        if(count($list) > 0)
            $first = $list[0]['id'];

        $marks = array();
        foreach($list as $mark) {
            foreach($members as $member) {
                if($member['id'] == $mark['memberId']) {
                    $userId = 'id_'.$member['id'];
                    if(!isset($marks[$userId])) {
                        $marks[$userId] = array();
                    }
                    foreach($examCodes as $examing) {
                        $codeId = $examing['ExamCode'];
                        if($codeId == $mark['ExamCode']){
                            foreach($examing['subjects'] as $subject) {
                                $member_subject = $mark['Subject'];
                                if($member_subject == $subject['Subject']) {
                                    if(!isset($marks[$userId][$codeId])){
                                        $marks[$userId][$codeId] = array();
                                    }
                                    $marks[$userId][$codeId][$member_subject] = $mark['Marks'];
                                }
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }

        foreach($members as $member) {
            $userId = 'id_'.$member['id'];
            if(isset($marks[$userId])) {
                $mark = $marks[$userId];
                $member['mark'] = $mark;
            }
        }

        $shipName = '??????';
        foreach($shipList as $ship)
            if($shipId == $ship['RegNo'])
                $shipName = $ship['shipName_Cn'];
        $excel_title = '????????????: '.$shipName;
        return view('shipMember.excel_member_examing_total', [
            'memberList'=>$members,
            'shipList'=>$shipList,
            'examingList'=>$examCodes,
            'shipId'=>$shipId,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '??????????????????-'.$excel_title,
        ]);
    }

    //----------------- ???????????? ------------------//
    public function contract(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $cargoId = $request->get('cargo');

        $shipList = ShipRegister::getShipListByOrigin();

        $portList = ShipPort::get();
        $cargoList = Cargo::get();

        $query = Cp::query();

        if(!empty($shipId))
            $query->where('Ship_ID', $shipId);
        if(!empty($fromDate))
            $query->where('CP_Date', '>=', $fromDate);
        if(!empty($toDate))
            $query->where('CP_Date', '<=', $toDate);
        if(!empty($cargoId))
            $query->where('Cargo', 'like', '%,'.$cargoId.',%');

        $list = $query->orderBy('CP_Date', 'desc')->get();
        $lrateList = Cp::select('L_Rate')->groupBy('L_Rate')->get();
        $drateList = Cp::select('D_Rate')->groupBy('D_Rate')->get();

        /*
        if(!empty($shipId))
            $list->appends(['shipId'=>$shipId]);
        if(!empty($fromDate))
            $list->appends(['fromDate'=>$fromDate]);
        if(!empty($toDate))
            $list->appends(['toDate'=>$toDate]);
        if(!empty($cargoId))
            $list->appends(['cargo'=>$cargoId]);
        */

        $state = Session::get('status');

        $shipName = '??????';
        foreach($shipList as $ship) {
            if($shipId == $ship['RegNo']) {
                if(empty($ship['name'])) $shipName = $ship['name'].' | '.$ship['shipName_Cn'];
                else $shipName = $ship['shipName_Cn'];
            }
        }
        $excel_title = '????????????: '.$shipName;
        if(!empty($fromDate) || !empty($toDate)) $excel_title .= ' ????????????: ';
        if(!empty($fromDate)) $excel_title .= date('???Y??? m??? d??? ', strtotime($fromDate));
        if(!empty($toDate)) $excel_title .= date('???Y??? m??? d??? ', strtotime($toDate));
        $CargoName = '??????';
        foreach($cargoList as $cargo) {
            if($cargoId == $cargo['id'])
                $CargoName = $cargo['CARGO_En'].' | '.$cargo['CARGO_Cn'];
        }
        $excel_title .= $CargoName;
        return view('operation.contract', array(
            'shipId'	=>	$shipId,
            'fromDate'	=>	$fromDate,
            'toDate'	=>	$toDate,
            'cargoId'	=>	$cargoId,
            'shipList'	=>	$shipList,
            'portList'	=>	$portList,
            'cargoList'	=>	$cargoList,
            'list'		=>	$list,
            'status'    =>  $state,
            'lrate'     =>  $lrateList,
            'drate'     =>  $drateList,
            'excel'     =>  1,
            'excel_title' => $excel_title,
            'excel_name'  => '????????????-'.$excel_title,
        ));
    }

    //----------------- ????????? ------------------//
    public function movement(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::all(['RegNo', 'shipName_Cn', 'shipName_En']);

        $shipId = is_null($request->get('shipId')) ? $shipList[0]->RegNo : $request->get('shipId');

        $shipPositionList = VoyLog::getShipPositionList();
        $shipStatusList = VoyStatus::all();

        $voyNoList = Cp::getVoyNosOfShip($shipId);
        $voyNo = is_null($request->get('voyNo')) ? $voyNoList[0]->id : $request->get('voyNo');

        $content = VoyLog::getShipVoyLogDataExcel($shipId, $voyNo);
        $error = Session::get('error');

        foreach($shipList as $ship) {
            if($ship->RegNo == $shipId) {
                $shipName = $ship['shipName_En'] . ' | ' . $ship['shipName_Cn'];
            }
        }
        if(!empty($shipName)) $excel_title = '????????????: '.$shipName;
        foreach($voyNoList as $voy) {
            if($voy['id'] == $voyNo) $voyName = $voy['Voy_No'].' | '.$voy['CP_No'];
        }
        if(!empty($voyName)) {
            if(!isset($excel_title)) $excel_title = '';
            $excel_title .= ' ??????: '.$voyName;
        }
        if(!isset($excel_title)) $excel_title = '';

        return view('operation.movement', array(
            'shipList'      =>  $shipList,
            'shipID'        =>  $shipId,
            'voyList'       =>  $voyNoList,
            'shipPositionList' => $shipPositionList,
            'shipStatusList'=>  $shipStatusList,
            'voyNo'         =>  $voyNo,
            'data'          =>  $content,
            'error'         =>  $error,
            'excel'         =>  1,
            'excel_title'   =>  $excel_title,
            'excel_name'    =>  '????????????-'.$excel_title,
        ));
    }

    //----------------- ???????????? (??????) ------------------//
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

        $shipName = '??????';
        foreach($shipList as $ship)
            if($ship['RegNo'] == $shipId)
                $shipName = $ship['shipName_En'].' | '.$ship['shipName_Cn'];
        $voyName = '??????';
        foreach($voyList as $voy)
            if($voy['id'] == $voyId)
                $voyName = $voy['Voy_No'].' | '.$voy['CP_No'];
        $excel_title = '????????????: '.$shipName.' ??????: '.$voyName;
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
                'excel'     =>  1,
                'excel_title' => $excel_title,
                'excel_name'  => '????????????-'.$excel_title,
            ]);
    }

    // ??????????????????
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

        $sum_distance = 0;
        $sum_day = 0;

        $sum = array();

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

            $sum_distance += $voyAnalys['distance'];
            $sum_day += $voyAnalys['sailTime'];

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


        foreach($voyAnalysList as $voy) {
            $analysIndex = 0;

            $sum_economy = 0;
            if (!isset($sum[0]))
                $sum[0] = 0;
            foreach ($eventList as $event) {
                if ($event['Type'] == 1) {
                    $sum_economy += $voy[$event['Event']];
                    $analysIndex++;
                    if (!isset($sum[$analysIndex]))
                        $sum[$analysIndex] = 0;
                    $sum[$analysIndex] += $voy[$event['Event']];
                }
            }
            $sum[0] += $sum_economy;

            $analysIndex++;

            $non_economy_index = $analysIndex;
            $sum_uneconomy = 0;
            if (!isset($sum[$non_economy_index]))
                $sum[$non_economy_index] = 0;

            foreach ($eventList as $event) {
                if ($event['Type'] == 2) {
                    $sum_uneconomy += $voy[$event['Event']];
                    $analysIndex++;
                    if (!isset($sum[$analysIndex]))
                        $sum[$analysIndex] = 0;
                    $sum[$analysIndex] += $voy[$event['Event']];
                }
            }
            $sum[$non_economy_index] += $sum_uneconomy;

            $analysIndex++;
            $non_economy_index = $analysIndex;
            if (!isset($sum[$non_economy_index]))
                $sum[$non_economy_index] = 0;

            $analysIndex++;
            $sumOther = 0;
            $other_index = $analysIndex;

            if (!isset($sum[$other_index]))
                $sum[$other_index] = 0;
            foreach($eventList as $event) {
                if($event['Type'] == 0) {
                    $sumOther += $voy[$event['Event']];
                    $analysIndex++;
                    if (!isset($sum[$analysIndex]))
                        $sum[$analysIndex] = 0;
                    $sum[$analysIndex] += $voy[$event['Event']];
                }
            }

            $un_economy_other = $voy['sailTime'] - $sum_economy - $sum_uneconomy - $sumOther;
            $sum[$non_economy_index] += $un_economy_other;
            $sum[$other_index] += $sumOther;

        }

        $shipName = '??????';
        foreach($shipList as $ship)
            if($ship['RegNo'] == $shipId)
                $shipName = $ship['shipName_En'].' | '.$ship['shipName_Cn'];
        $excel_title = '????????????: '.$shipName;
        $firstVoyName = ''; $endVoyName = '';
        foreach($voyList as $voy) {
            if ($voy->id == $firstVoy)
                $firstVoyName = $voy->Voy_No . ' | ' . $voy->CP_No;
            if($voy->id == $endVoy)
                $endVoyName = $voy->Voy_No . ' | ' . $voy->CP_No;
        }
        $excel_title .= ' ????????????: '.$firstVoyName.' - '.$endVoyName;

        return view('operation.ship_voy_analysis_excel',
            [   'shipId'    =>  $shipId,
                'shipName'  =>  $shipNameInfo,
                'shipList'  =>  $shipList,
                'firstVoy'  =>  $firstVoy,
                'endVoy'    =>  $endVoy,
                'voyList'   =>  $voyNoList,
                'list'      =>  $voyAnalysList,
                'eventList' =>  $eventList,
                'typeList'  =>  $typeList,
                'sum_distance' => $sum_distance,
                'sum_day'   =>  $sum_day,
                'sum_list'  =>  $sum,
                'excel'     =>  1,
                'excel_title' => $excel_title,
                'excel_name'  => '??????????????????-'.$excel_title,
            ]);
    }

    /* ???????????? */
    //----------------- ???????????? / ????????????????????? ------------------//
    public function yearPlanReport(Request $request)
    {
        Util::getMenuInfo($request);

        $data = YearlyQuarterMonthPlan::getCreditDebitAmount();
        $dataYear = YearlyQuarterMonthPlan::getReportYearMonth();

        return view('operation.report.ship_all', array(
            'allList'=>$data,
            'yearList' => $dataYear,
            'excel' => 1,
            'excel_name' => '????????????????????????',
        ));
    }

    //----------------- ???????????? / ?????? ????????????????????? ------------------//
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
            'lists'=>$data,
            'excel' => 1,
            'excel_title' => '??????' . $year.'?????????????????????',
            'excel_name' => '??????'. $year.'?????????????????????????????????',
        ));
    }

    //----------------- ???????????? / ?????? ????????????????????? ------------------//
    public function shipMonthReport(Request $request)
    {
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

        $shipName = '??????';
        foreach($shipList as $ship)
            if($ship->RegNo == $shipId)
                $shipName = $ship->shipName_En.' | '.$ship->shipName_Cn;
        $excel_title = '????????????: '.$shipName.' '.$year.'???';

        return view('operation.report.ship_month', array(
            'currentYear'=>$year,
            'yearList'=>$yearList,
            'shipList'=>$shipList,
            'ship'=>$shipId,
            'data'=>$monthData,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => $excel_title.' ????????????????????????',
        ));
    }

    //----------------- ???????????? / ?????? ?????????????????? ------------------//
    public function shipCountReport(Request $request)
    {
        $shipList = ShipRegister::getShipListByOrigin();
        $yearList = YearlyQuarterMonthPlan::getYearList();
        $year = $request->get('year');

        if(empty($year))
            $year = $yearList[0]->Yearly;
        $ship = $request->get('shipId');

        if(empty($ship))
            $ship = $shipList[0]->RegNo;

        $data = YearlyQuarterMonthPlan::getShipCountReport($year, $ship);

        $shipName = '??????';
        foreach($shipList as $ship)
            if($ship->RegNo == $ship)
                $shipName = $ship->shipName_En.' | '.$ship->shipName_Cn;
        $excel_title = '????????????: '.$shipName.' '.$year.'???';

        return view('operation.report.ship_count', array(
            'currentYear'=>$year,
            'yearList'=>$yearList,
            'shipList'=>$shipList,
            'ship'=>$ship,
            'list'=>$data,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => $excel_title.' ??????????????????',
        ));
    }

    /* ???????????? */
    //----------------- ???????????? ------------------//
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
            'excel' => 1,
            'excel_name' => '????????????',
        ));
    }

    //?????????????????? ??????????????????

    public function supplyPlan(Request $request)
    {
        Util::getMenuInfo($request);

        $years_range = new \stdClass();
        $years_range->min = SupplyPlan::getMinYear();
        $years_range->max = SupplyPlan::getMaxYear();
//        ?????????
        $year = $request->get('year');
        $shipid = $request->get('shipid');
        $deptcount = ShipDept::count();
        $shipInfo = ShipRegister::getShipListByOrigin();
        $deptInfo = ShipDept::all(['id','Dept_Cn']);

        $shipList = SupplyPlan::getPlanedShipList(0);
        $shipNameCol = $request->get('shipNameCol');

        $nShips = count($shipList);

        $colYear = $request->get('yearCol');

        $tab = $request->get('tab');
        $type = $request->get('type');
        $excel_title = '???: ';
        if(isset($year) && $year > 0)
            $excel_title .= $year;
        else
            $excel_title .= '??????';
        $shipName = '??????';
        foreach($shipList as $ship) {
            if($ship['id'] == $shipid) {
                $shipName = $ship['ShipName_En'].' | '.$ship['shipName_Cn'];
            }
        }
        $excel_title .= ' ????????????: '.$shipName;
        if(!empty($sel_year)) {
            $supplyplanlist = SupplyPlan::getPlanList($sel_year, $shipid);
            $supplyplanlist->appends(['tab' => $tab, 'year' => $sel_year, 'shipid' => $shipid, 'menuId' => $GLOBALS['selMenu']]);
        } else {
            $supplyplanlist = SupplyPlan::getPlanList(0, 0);
        }
        if(empty($colYear)) $colYear = $years_range->min;
        if(!empty($shipNameCol)){
            $nShips = 1;
            $ship = ShipRegister::find($shipNameCol);
            $shipNames=$ship['RegNo'];
            $shipcolList=array();
            $shipcolList[]=array('shipName_Cn'=>$ship['shipName_Cn'], 'ShipName_En' => $ship['shipName_En'],'ShipName'=>$shipNames);
            $supplyAmounts=SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->join('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyplan.ShipName')
                ->where('tbl_supplyplan.Yearly', $colYear)
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->where('tbl_supplyplan.ShipName', $shipNames)
                ->get(['tbl_supplyplan.Yearly','tbl_supplyplan.Monthly','tbl_supplyplan.ShipName',
                    'tbl_supplyplan.Dept','tbl_supplyplan.Amount']);
        }else{
            $shipcolList=$shipList;
            $supplyAmounts=SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->join('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyplan.ShipName')
                ->where('tbl_supplyplan.Yearly', $colYear)
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->get(['tbl_supplyplan.Yearly','tbl_supplyplan.Monthly','tbl_supplyplan.ShipName',
                    'tbl_supplyplan.Dept','tbl_supplyplan.Amount']);
        }

        $amounts=array();
        $year = $colYear;
        foreach($supplyAmounts as $supplyAmount)
        {
            $shipName=$supplyAmount['ShipName'];
            $Month=$supplyAmount['Monthly'];
            $amounts[$year.$shipName.$supplyAmount['Dept'].$Month]=$supplyAmount['Amount'];
            if(!isset($amounts[$year.$shipName.$supplyAmount['Dept'].'0']))
                $amounts[$year.$shipName.$supplyAmount['Dept'].'0'] = 0;
            $amounts[$year.$shipName.$supplyAmount['Dept'].'0'] += $supplyAmount['Amount'];
        }
        for($Month = 1; $Month < 13; $Month++) {
            foreach($shipcolList as $shipListItem) {
                $shipName = $shipListItem['ShipName'];
                $amounts[$year . $Month . $shipName] =
                    SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                        ->where('tbl_supplyplan.Yearly', '=', $year)
                        ->where('tbl_supplyplan.Monthly', '=', $Month)
                        ->where('tbl_supplyplan.ShipName', $shipName)
                        ->sum('tbl_supplyplan.Amount');
                if (!isset($amounts[$year . '0' . $shipName]))
                    $amounts[$year . '0' . $shipName] = 0;
                $amounts[$year . '0' . $shipName]
                    += $amounts[$year . $Month . $shipName];
                if(!isset($amounts[$year.$Month.'0']))
                    $amounts[$year.$Month.'0'] = 0;
                $amounts[$year.$Month.'0']
                    += $amounts[$year.$Month.$shipName];
            }
            if(!isset($amounts[$year.'00']))
                $amounts[$year.'00'] = 0;
            $amounts[$year.'00']
                += $amounts[$year.$Month.'0'];
        }
        return view('shipTechnique.shipEquipment.supplyPlan', [
            'tab'=>$tab,
            'shipcolList'=>$shipcolList,
            'year_range'=>$years_range,
            'shipList'=>$shipList,
            'supplyplanlist'=>$supplyplanlist,
            'year'=>$year,
            'shipid'=>$shipid,
            'nShips'=>$nShips,
            'nDepts'=>$deptcount,
            'shipInfos'=>$shipInfo,
            'deptInfos'=>$deptInfo,
            'supplyAmounts'=>$amounts,
            'yearCol'=>$colYear,
            'shipNameCol'=>$shipNameCol,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????????????????????-'.$excel_title,
            'type' => $type,
        ]);
    }

    //????????????
    public function shipRepairAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipList = ShipRegister::getShipListByOrigin();

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');

        $recovery = ShipRepair::getRepairSearch($shipId, $voy_number);
        if(isset($shipId))
            $recovery->appends(['ship'=>$shipId]);
        if(isset($voy_number))
            $recovery->appends(['voy'=>$voy_number]);

        $cps = Cp::getVoyNosOfShip($shipId);

        return view('shipTechnique.RepairAllBrowse_print',
            array('RepairInfos'=>$recovery,'cps'=>$cps,'shipList'=>$shipList,'shipId'=>$shipId,'voy'=>$voy_number,'outMthd' => 2,
                'excel' => 1,
                'excel_title' => '??????',
                'excel_name' => '??????',
                )
        );
    }

    //????????????
    public function shipAccidentAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');

        $shipList = ShipRegister::getShipListByOrigin();

        $recovery = ShipAccident::getAccidentSearch($shipId, $voy_number);
        if(isset($shipId))
            $recovery->appends(['ship'=>$shipId]);
        if(isset($voy_number))
            $recovery->appends(['voy'=>$voy_number]);

        $cps = CP::getVoyNosOfShip($shipId);

        return view('shipTechnique.AccidentAllBrowse_print',
            [   'AccidentInfos' =>  $recovery,
                'cps'           =>  $cps,
                'shipList'      =>  $shipList,
                'shipId'        =>  $shipId,
                'voy'           =>  $voy_number,
                'outMthd' => 2,
                'excel' => 1,
                'excel_title' => '????????????',
                'excel_name' => '????????????',
            ]);
    }

    //????????????
    public function shipSurveyAllBrowse(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('ship');
        $voy_number = $request->get('voy');
        $survey = ShipSurvey::getSurveySearch($shipId, $voy_number);
        if(isset($shipId))
            $survey->appends(['ship'=>$shipId]);
        if(isset($voy_number))
            $survey->appends(['voy'=>$voy_number]);

        $shipList = ShipRegister::getShipListByOrigin();
        $cps = CP::getVoyNosOfShip($shipId);

        return view('shipTechnique.SurveyAllBrowse_print',
            [   'SurveyInfos'=>$survey,
                'cps'=>$cps,
                'shipList'=>$shipList,
                'id'=>$shipId,
                'voy'=>$voy_number,
                'outMthd' => 2,
                'excel' => 1,
                'excel_title' => '??????',
                'excel_name' => '??????',
            ]);
    }

    //?????????????????? ??????????????????

    public function loadSupplyRecord(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId=$request->get('shipId');
        $voy = $request->get('voy');
        $shipInfo = ShipRegister::getShipListByOrigin();
        if(empty($shipId)) {
            $voyinfo = ShipSupply::getVoyListByShipId($shipInfo[0]['RegNo']);
            $shipId = $shipInfo[0]['RegNo'];
        } else {
            $voyinfo = ShipSupply::getVoyListByShipId($shipId);
        }
        if(empty($voy)) {
            if(count($voyinfo) > 0) $voy = $voyinfo[0]['id'];
        }
        $cpInfo = Cp::where('id', $voy)->first();
        $supplyInfos = ShipSupply::getApplInfo($shipId, $voy, 1);

        //application add params
        $deptInfos = ShipDept::all(['id','Dept_Cn']);
        $cpInfos = Cp::select('Voy_No', 'CP_No')
            ->where('Ship_ID', $shipId)->groupBy('Voy_No')->orderBy('Voy_No', 'dsc')->get();
        $kinds = ShipEquipmentMainKind::all(['id', 'Kind_Cn']);
        $equipInfos = ShipSupply::getEquipmentInfo($shipId, $kinds[0]['id']);
        if(count($equipInfos) == 0) $parts =[];
        else $parts = ShipSupply::getPartInfo($equipInfos[0]->id);
        $equipUnits = EquipmentUnit::all(['id','Unit_Cn', 'Unit_En']);
        $shipPorts = ShipPort::all(['id', 'Port_Cn', 'Port_En']);

        $shipName = '??????';
        foreach($shipInfo as $ship) {
            if($ship['RegNo'] == $shipId)
                $shipName = $ship['shipName_En'].' | '.$ship['shipName_Cn'];
        }
        $voyName = '??????';
        foreach($voyinfo as $voyItem) {
            if($voyItem['id'] == $voy)
                $voyName = $voyItem['Voy_No'];
        }
        $excel_title = '????????????: '.$shipName.' ????????????: '.$voyName;
        return view('shipTechnique.shipEquipment.application',
            array(
                'shipInfos' => $shipInfo,
                'supplyInfos'=>$supplyInfos,
                'shipId'=>$shipId,
                'voy'=>$voy,
                'voyInfos'=>$voyinfo,
                'cpInfo' => $cpInfo,
                'cpInfos' => $cpInfos,
                'deptInfos' => $deptInfos,
                'kinds' => $kinds,
                'equipInfos' => $equipInfos,
                'parts' => $parts,
                'equipUnits' => $equipUnits,
                'shipPorts' => $shipPorts,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => '?????????????????? ??????????????????-'.$excel_title,
            )
        );
    }

    //?????????????????? ??????????????????
    public function showSupplyInfo(Request $request)
    {
        Util::getMenuInfo($request);

        $shipInfo = ShipRegister::getShipListByOrigin();
        $shipRegNo = $request->get('shipId');
        if(empty($shipRegNo)) {
            if(!empty($shipInfo)) $shipRegNo = $shipInfo[0]->RegNo;
        }
        $kinds = ShipSupply::getKindInfo($shipRegNo);
        $kind = $request->get('kind');
        if(empty($kind)){
            if(count($kinds) > 0) $kind = $kinds[0]->id;
        }
        if($kind == 1 || $kind == 2) {
            $equipInfo = ShipSupply::getEquipInfo($shipRegNo, $kind);
            $equipId = $request->get('equip');
            if (empty($equipId)) {
                if (count($equipInfo) > 0) $equipId = $equipInfo[0]->id;
            }
        } else {
            $equipInfo = [];
            $equipId = null;
        }
        $supplyInfos = ShipSupply::getReciptInfo($shipRegNo, $kind, $equipId);

        $shipName = '??????';
        foreach($shipInfo as $ship) {
            if($ship['RegNo'] == $shipRegNo) {
                $shipName = $ship['shipName_Cn'];
                if (!empty($ship['name'])) $shipName .= ' | ' . $ship['name'];
            }
        }
        $kindName = '??????';
        foreach($kinds as $kindItem) {
            if($kindItem['id'] == $kind)
                $kindName = $kindItem['Kind_Cn'];
        }
        $equipName = '??????';
        foreach($equipInfo as $equip) {
            if($equip['id'] == $equipId)
                $equipName = $equip['Euipment_Cn'];
        }
        $excel_title = '????????????: '.$shipName.' ??????: '.$kindName.' ?????????: '.$equipName;
        return view('shipTechnique.shipEquipment.supplyInfo',[
            'shipInfos'=>$shipInfo,
            'equipInfos'=>$equipInfo,
            'kinds'=>$kinds,
            'supplyInfos'=>$supplyInfos,
            'shipId'=>$shipRegNo,
            'kindId'=>$kind,
            'equip'=>$equipId,
            'excel' => 1,
            'excel_title' => $excel_title,
            'excel_name' => '?????????????????? ??????????????????-'.$excel_title,
        ]);
    }

    // ????????? ?????? ????????????
    public function enterpriseYearAttendExcel(Request $request) {
        //????????? ???????????????
        $year = is_null($request->get('year')) ? date('Y') : $request->get('year');

        $days = 365;
        $remain = fmod($year, 4);
        if($remain == 0)
            $days = 366;

        //???????????? ??????????????? ??????
        $start = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, 12, 31, $year)); // ????????? ???????????????
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //?????????????????? ??????
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if($year == date('Y')){
            $days = 0;
            $months = [31,28,31,30,31,30,31,31,30,31,30,31];
            $month = date('n') - 1;
            for($i = 0; $i<$month; $i++)
                $days += $months[$i];
            $days += date('j') * 1;

            $work_days = $days - $total_rest;
        }

        $shipId = $request->get('ship');
        $unitId = $request->get('unit');
        $memberName = $request->get('name');

        // ???????????? ????????? ?????? ??????(?????? ??? ??????)?????? ????????? ?????????.
        $memberList = UserInfo::enterpriseTotalMemberList($unitId, $shipId, $memberName);

        $userStr = '';
        $crewStr = '';
        foreach($memberList as $members) {
            if($members->memberType == 1)
                $userStr = $members->idStr;
            else
                $crewStr = $members->idStr;
        }

        $userAttend = AttendUser::getAttendDaysOfMonthByAttendType($userStr, $start, $end);
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($crewStr, $start, $end);
        $typeList = AttendType::all();

        $list = array();
        $totl_absen = 0;
        $totl_attend = 0;

        // ---------  ???????????? ?????? ??????????????? ????????????.  ------------
        $attendMember = array();
        if(count($userAttend) > 0) {
            $attendMember['id'] = $userAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($userAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                $attendMember['isShip'] = 0;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // ??????????????? ???????????? ?????? ????????? ????????????????????? ??????.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 0;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // ?????????????????? ?????? ??????
        if(count($userAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        // ---------  ???????????? ?????? ??????????????? ????????????.  ------------
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        if(count($crewAttend) > 0) {
            $attendMember['id'] = $crewAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($crewAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                $attendMember['isShip'] = 1;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // ??????????????? ???????????? ?????? ????????? ????????????????????? ??????.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 1;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // ?????????????????? ?????? ??????
        if(count($crewAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        $ships = ShipRegister::getShipListOnlyOrigin();
        $units = Unit::unitFullNameList();

        $excel_title = '';
        if(!empty($unitId)) {
            $unitName = Unit::find($unitId)->title;
            $excel_title .= ' ??????: '.$unitName;
        }
        if(!empty($shipId)) {
            $shipName = ShipRegister::select('tb_ship.name')->join('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')->where('RegNo', $shipId)->first()->name;
            $excel_title .= ' ????????????: '.$shipName;
        }
        if(!empty($memberName))
            $excel_title .= ' ??????: '.$memberName;

        return View('business.attend.enterprise_year_attend',
            [   'dates'     =>  $dates,
                'year'      =>  $year,
                'list'      =>  $list,
                'typeList'  =>  $typeList,
                'units'     =>  $units,
                'ships'     =>  $ships,
                'unitId'    =>  $unitId,
                'shipId'    =>  $shipId,
                'memberName'=>  $memberName,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => $year.'??? ?????????????????????-'.$excel_title,
            ]);
    }

    // ????????? ??????????????????
    public function memberCapacityManageExcel() {
        $list = ShipMemberCapacity::totalData();
        $excel_title = '??????????????????';

        return view('shipManage.member_capacity_manage', ['list' => $list, 'excel' => 1, 'excel_name' =>$excel_title]);
    }

    // ???????????????
    public function loadShipMembersExcel (Request $request) {

        $shipId = $request->get('ship');
        $pos = $request->get('pos');
        $name = $request->get('name');
        $state = $request->get('state');

        if(empty($shipId))
            $shipId = null;
        if(empty($pos))
            $pos = null;
        if(empty($name))
            $name = null;
        if(empty($state))
            $state = null;

        $shipList = ShipRegister::getShipListOnlyOrigin();
        $posList = ShipPosition::orderBy('id')->get();

        $list = ShipMember::getShipMemberListByKeyword($shipId, $pos, $name, $state);
        foreach($list as $member) {
            $capacityId = $member->CapacityID;
            $gmdssId = $member->GMDSSID;

            if(!empty($capacityId)) {
                $capacity = ShipMemberCapacity::find($capacityId);
                if(empty($capacity))
                    continue;
                $member['capacity_Cn'] = $capacity->Capacity;
                $member['capacity_en'] = $capacity->Capacity_En;
            }
            if(!empty($gmdssId)) {
                $capacity = ShipMemberCapacity::find($gmdssId);
                if(empty($capacity))
                    continue;
                $member['gmdss_Cn'] = $capacity->Capacity;
                $member['gmdss_en'] = $capacity->Capacity_En;
            }
        }

        $excel_title = '';
        $shipName = '';
        if(!empty($shipId)) {
            $shipInfo = ShipRegister::where('regNo', $shipId)->first();
            $shipName = $shipInfo['shipName_Cn'];
        }

        if(!empty($pos)) {
            $posInfo = ShipPosition::find($pos);
            $excel_title = ' ?????? : '.$posInfo['Duty'];
        }
        if(!empty($name))
            $excel_title = $excel_title. ' ?????? : '.$name;

        if(!empty($state))
            $excel_title = $excel_title. ' ???????????? : '. ($state == 1 ? '??????' : '??????');

        return view('shipMember.memberDirectory',
            [   'list'=>$list,
                'shipList'=>$shipList,
                'posList' => $posList,
                'ship'=>$shipId,
                'pos'=>$pos,
                'name'=>$name,
                'state' => $state,
                'excel' => 1,
                'excel_title' => $excel_title,
                'excel_name' => $shipName.(empty($shipName) ? '' : '-').'???????????????-'.$excel_title,
            ]);
    }


}
