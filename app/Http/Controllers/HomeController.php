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
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$reportList = DecisionReport::where('state', '=', REPORT_STATUS_REQUEST)->get();
		foreach($reportList as $key => $item) {
			$reportList[$key]->realname = UserInfo::find($item->creator)['realname'];
		}
		$shipList = ShipRegister::getShipListByOrigin();
		$tmp = ShipRegister::getSimpleDataList();
		$shipForDecision = array();
		foreach($tmp as $key => $item) {
			$shipForDecision[$item->id] = $item->shipName_Cn;
		}

		return view('home.front', [
			'shipList'          => $shipList,
			'reportList'        => $reportList,
			'shipForDecision'   => $shipForDecision,
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



