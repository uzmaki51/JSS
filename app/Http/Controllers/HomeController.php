<?php namespace App\Http\Controllers;


use App\Models\Attend\AttendShip;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendRest;
use App\Models\Board\News;
use App\Models\Member\Unit;
use App\Models\Operations\VoyLog;
use App\Models\Schedule;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipMember;
use App\Models\Decision\DecisionReport;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Util;
use App\Models\Menu;
use Illuminate\Support\Facades\App;
use Auth;
use Config;
use App\Models\Home;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
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
		$this->middleware('auth');

		$GLOBALS['selMenu'] = 0;
		$GLOBALS['submenu'] = 0;

		$admin = Session::get('admin');
		$query = Menu::where('parentId', '0');
		if($admin == 0)
			$query = $query->where('admin', '0');
		$topMenu = $query->get();
		$GLOBALS['topMenu'] = $topMenu;
		$GLOBALS['topMenuId'] = 1;

		if($admin > 0) {
			$menulist = Menu::where('parentId', '=', '1')->get();
			foreach($menulist as $menu) {
				$menuId = $menu['id'];
				$submenus = Menu::where('parentId', '=', $menuId)->get();
				$menu['submenu'] = $submenus;
			}
			$GLOBALS['menulist'] = $menulist;
		} else {

		}
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        //출근정형상태
        $selDate = date('Y/m/d');

        $result = new \stdClass();
        $result->year = date('Y');
        $result->month = date('n');
        $result->day = date('d');
        $result->hour = date('H');
        $result->minute = date('i');
        $result->second = date('s');
        $result->half = date('A');

        //현재날자와 가입한 리용자에 대한처리
        //현재 가입한 리용자의 식별자
        $user = Auth::user();
        $today = date("Y-m-d");

        $attendance = AttendUser::where('regDay', $today)->where('userId', $user->id)->first();
        $attendStatus = 0;
        if ($attendance == null) {
            if (!$this->isWorkingDay())  // 로동일검사
                $attendStatus = 0;   //명절일,휴식일
            else
                $attendStatus = 1;  //출근기록가능한 상태
        } else {
            $attendStatus = 4; // 이미 登记한 상태
        }


        //부서목록을 얻는다.
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
                $memberList = UserInfo::getDirectlyUserList($unit['id']); // 직속부서의 리용자들의 ID를 반점으로 区分하여 얻는다.
                $unit['title'] = '경송선박회사';
            } else {
                $memberList = UserInfo::getUserListByUnit($unit['id']); // 해당부서의 리용자들의 ID를 반점으로 区分하여 얻는다.
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

        $shipList = ShipRegister::getShipListByOrigin();
        foreach($shipList as $ship) {
            $shipMemberList = ShipMember::getMemberListByCommar($ship['RegNo']); // 해당배의 선원들의 ID를 반점으로 区分하여 얻는다.
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
            $ship['id'] = $ship['RegNo'];
            $ship['unitType'] = 0;
            $ship['attend'] = $attendCount;
            $ship['absence'] = $absenceCount;
            $ship['userCount'] = $memberCount;
            $ship['valueList'] = $valueList;

            $units[] = $ship;
        }
//var_dump($shipList);die;
        $shipMemberList = ShipMember::getMemberListByCommar(); // 대기선원들을 반점으로 区分하여 얻는다.
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

        $ship = [];
        $ship['title'] = '대기선원';
        $ship['id'] = 'empty';
        $ship['unitType'] = 0;
        $ship['attend'] = $attendCount;
        $ship['absence'] = $absenceCount;
        $ship['userCount'] = $memberCount;
        $ship['valueList'] = $valueList;

        $units[] = $ship;

        //배동태
        $movement = VoyLog::getHomeShipVoyLogData();

        // 결재할 문서
        $userId = Auth::user()->id;
        $reportList = DecisionReport::getWillDecisionReportList($userId, '', '', '', '', '');

        //전자게시판
        $newses= News::getHomeNewsListForTema();

        //오늘의 일정
        $today = date('Y-m-d');
        $schedules = Schedule::where('startDate', '=', $today)
            ->where('attend_user', 'like', '%,'.$user->id.',%')
            ->orderBy('startDate')
            ->get();
        foreach($schedules as $schedule) {
            $schedule['attend_user'] = $this->getUserNames($schedule['attend_user']);
        }

        //월일정정보
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-01'))));
        $lastDay = date('Y-m-d', strtotime("-1 days", strtotime($lastDay)));
        $monthSchedules = Schedule::select('*', DB::raw('MIN(startDate) as minDate, MAX(startDate) as maxDate'))
                        ->whereBetween('startDate', [$firstDay, $lastDay])
                        ->where('attend_user', 'like', '%,'.$user->id.',%')
                        ->groupBy('title')
                        ->orderBy('minDate')
                        ->get();
        foreach($monthSchedules as $schedule) {
            $schedule['attend_user'] = $this->getUserNames($schedule['attend_user']);
        }

        //재정업무체계의 자료기지에 접속하여 미결건자료와 시세자료를 얻는다.
        $MoneyOutLayModel = new Home\MoneyOutLayModel();
        $RateModel = new Home\RateModel();
        $layDatas = $MoneyOutLayModel->getLayDatas();
        $rateData = $RateModel->getRecentRate();
        $year = $request->get('year');

        if (empty($year))
            $year = date('Y');

        $searchData = $RateModel->getSearchRate($year);

            return view('home.front', [
                'attendStatus' => $attendStatus,
                'units'             => $units,
                'typeList'          => $typeList,
                'shipList'          => $shipList,
                'selDate'           => $selDate,
                'reportList'        => $reportList,
                'movement'          => $movement,
                'newses'            => $newses,
                'schedules'         => $schedules,
                'monthSchedules'    => $monthSchedules,
                'layDatas'          => $layDatas,
                'crDatas'           => $MoneyOutLayModel->getCrDatas(),
                'rateData'          => $rateData,
                'searchData'        => $searchData,
                'year'              => $year,
            ]);

	}

	public function file_download(Request $request) {

		$filepath = $request->get('path');
		$type = $request->get('type');
		$path = public_path('uploads');
		$filename = $request->get('filename');


		if($type == 'news')
			$path = $path.'/news/'.$filepath;

		else if($type == 'report')
			$path = $path.'/report/'.$filepath;

		else if($type == 'training')
			$path = $path.'/training/'.$filepath;

		else if($type == 'crewCard')
			$path = $path.'/crewCard/'.$filepath;

		else if($type == 'capacity')
			$path = $path.'/capacity/'.$filepath;

		else if($type == 'school')
			$path = $path.'/school/'.$filepath;
		else if($type == 'ship-cert')
			$path = $path.'/ship-cert/'.$filepath;
        else if($type == 'repair')
            $path = $path.'/repair/'.$filepath;

		return response()->download($path, $filename);
	}

	public function translatePage($locale) {

        if (!in_array($locale, Config::get('app.locales')))
            $locale = Config::get('app.locale');

        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect()->back();
    }


	public function testpage() {

		return view('operation.supply.icons');
	}

    //오늘이 공작일인가를 검사한다.
    private function isWorkingDay($selDate = null)
    {
        if(is_null($selDate))
            $selDate = date("Y-m-d");
        $restList = AttendRest::where('day', $selDate)->get();
        if(count($restList) > 0)
            return false;

        return true;
    }

    //현재시간이 로동시간에 포함되는가를 검사한다.//지각생판정
    public function isValidateOfTime()
    {
        $id = AttendTime::max('id');
        $attendtime = AttendTime::find($id);
        $attend = date('Y-m-d').' '. $attendtime->start;

        $time1 = date_timestamp_get(date_create());
        $time2 = date_timestamp_get(date_create($attend));

        if ( $time1 < $time2) {
            return true;
        }
        return false;
    }

    //ID목록으로从 이름목록을 얻기
    private function getUserNames($idList)
    {
        $idList = explode(',', $idList);
        $count = 0;
        foreach ($idList as $userid) {
            if(empty($userid))
                continue;
            $count++;
        }

        $user = Auth::user();

        $namestr = $user->name;
        if($count > 1)
            $namestr .=  '외'.($count - 1).'명';
        return $namestr;
    }

    public function resetPassword(Request $request) {
        $old_passwd = $request->get('old_passwd');
        $new_passwd = $request->get('password');
        $confirm_passwd = $request->get('password_confirmation');

        $state = Session::get('state');
        $msg = Session::get('msg');

        if(empty($new_passwd))
            return view('auth.reset', ['state'=>$state, 'msg'=>$msg]);

        if($new_passwd != $confirm_passwd) {
            $msg = "암호를 다시 정확히 입력하십시오.";
            return back()->with(['state'=>'error','msg'=>$msg]);
        }

        $user = Auth::user();
        $password = $user->password;

        if( password_verify($old_passwd, $password)){
            $user['password'] = Hash::make($new_passwd);
            $user->save();
            return redirect('/home');
        } else {
            $msg = "이전의 암호가 정확치 않습니다. 다시 입력하십시오.";
            return back()->with(['state'=>'error','msg'=>$msg]);
        }
    }

	//은행시세표 搜索
	public function searchList(Request $request){

		$RateModel = new Home\RateModel();
        $year = $request->get('year');
	    $month = $request->get('month');
        print_r($month);die;

//        $searchData = $RateModel->getSearchRate($year, $month);
//		//print_r($searchData);
//		//	die("dsf");
//		$jsonResultData = json_encode($searchData);
//		print_r($jsonResultData);die();



	}

	public function gotoWelcome() {
        return view('home.welcome');
    }
}



