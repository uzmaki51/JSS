<?php namespace App\Http\Controllers;


use App\Models\Attend\AttendShip;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendRest;
use App\Models\Board\News;
use App\Models\Member\Unit;
use App\Models\Operations\VoyLog;
use App\Models\Schedule;
use App\Models\ShipManage\ShipCertList;
use App\Models\ShipManage\ShipCertRegistry;
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
		$shipList = ShipRegister::all();
		$shipForDecision = array();
		foreach($shipList as $key => $item) {
			$shipForDecision[$item->IMO_No] = $item->shipName_En;
		}

		$expired_certData['ship'] = array();
		$expired_certData['member'] = array();

		$shipCertTbl = new ShipCertRegistry();
		$memberCertTbl = new ShipMember();

		$expired_certData['ship'] = $shipCertTbl->getExpiredList();

		foreach($expired_certData['ship'] as $key => $item) {
			$expired_certData['ship'][$key]->ship_name = ShipRegister::where('IMO_No', $item->ship_id)->first()->shipName_En;
			$expired_certData['ship'][$key]->cert_name = ShipCertList::where('id', $item->cert_id)->first()->name;
		}

		// var_dump($shipForDecision);die;
		return view('home.front', [
			'shipList'          => $shipList,
			'reportList'        => $reportList,
			'shipForDecision'   => $shipForDecision,
			'expired_data'      => $expired_certData
		]);
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
            $msg = "两次输入的密码不一致。";
            return back()->with(['state'=>'error','msg'=>$msg]);
        }

        $user = Auth::user();
        $password = $user->password;

        if( password_verify($old_passwd, $password)){
            $user['password'] = Hash::make($new_passwd);
            $user->save();
            return redirect('/home');
        } else {
            $msg = "密码错误，请重新输入密码。";
            return back()->with(['state'=>'error','msg'=>$msg]);
        }
    }
}



