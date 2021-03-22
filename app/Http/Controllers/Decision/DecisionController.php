<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/13
 * Time: 9:39
 */

namespace App\Http\Controllers\Decision;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;

use App\Models\Convert\VoyLog;
use App\Models\Decision\DecEnvironment;
use App\Models\Decision\DecisionFlow;
use App\Models\Decision\DecisionReport;
use App\Models\Decision\DecisionNote;
use App\Models\Decision\Decider;
use App\Models\Decision\ReadReport;

use App\Models\Operations\AcItem;
use App\Models\ShipManage\ShipRegister;
use App\Models\UserInfo;
use App\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Member\Unit;

use Auth;
use Illuminate\Support\Facades\Session;

class DecisionController extends Controller
{
    protected $userinfo;

    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;

        $admin = Session::get('admin');
        $this->userInfo = Auth::user();

        $admin = Session::get('admin');
        if($admin > 0){
            $topMenu = Menu::where('parentId', '0')->get();
        } else {
            $topMenu = Util::getTopMemu($this->userInfo['menu']);
        }
        foreach($topMenu as $menu) {
            $menu['submenu'] = Menu::where('parentId', '=', $menu['id'])->get();
            foreach($menu['submenu'] as $submenu)
            {
                $submenu['thirdmenu'] = Menu::where('parentId', '=', $submenu['id'])->get();
            }
        }
		$GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 2;

        if ($admin > 0) {
            $menulist = Menu::where('parentId', '=', '2')->orderBy('id')->get();
            foreach ($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {
            $user = Auth::user();
            if (in_array(2, explode(',', $user['menu']))) {
                $menulist = Menu::where('parentId', '=', '2')->where('admin', '=', '0')->get();
                foreach ($menulist as $menu) {
                    $menuId = $menu['id'];
                    $submenus = Menu::where('parentId', '=', $menuId)->get();
                    $menu['submenu'] = $submenus;
                }
                $GLOBALS['menulist'] = $menulist;
            } else {
                $menulist = Menu::where('parentId', '=', '2')->where('admin', '=', '0')->whereIn('id', explode(',', $user['menu']))->get();
                foreach ($menulist as $menu) {
                    $menuId = $menu['id'];
                    $submenus = Menu::where('parentId', '=', $menuId)->get();
                    $menu['submenu'] = $submenus;
                }
                $GLOBALS['menulist'] = $menulist;
            }
        }
        $this->userinfo = Auth::user();
    }

    public function index()
    {
        return redirect()->action('Decision\DecisionController@loadReportForDecide');
    }

    ////////////////////수신된 문서관리부분//////////////////////////////////////////////
    public function receivedReport(Request $request) {
	    Util::getMenuInfo($request);
        return view('decision.received_report');
    }

    // New Definition by Uzmaki

	public function reportSubmit(Request $request) {
    	$params = $request->all();

    	$reportId = $params['reportId'];
    	if(isset($reportId) && $reportId != "")
    		$reportTbl = DecisionReport::find($reportId);
    	else
    		$reportTbl =new DecisionReport();

    	$user = Auth::user();

    	$reportTbl['flowid'] = $params['flowid'];
		$reportTbl['shipNo'] = $params['shipNo'];
		$reportTbl['voyNo'] = $params['voyNo'];
		if($params['flowid'] == REPORT_TYPE_CONTRACT) {
			$reportTbl['profit_type'] = '';
			$reportTbl['amount'] = '';
			$reportTbl['currency'] = '';
		} else {
			$reportTbl['profit_type'] = $params['profit_type'];
			$reportTbl['amount'] = $params['amount'];
			$reportTbl['currency'] = $params['currency'];
		}

		$reportTbl['creator'] = $user->id;
		$reportTbl['content'] = $params['content'];
		$reportTbl['state'] = $params['reportType'];

		$reportTbl->save();

		return redirect('decision/receivedReport');
	}
    public function getACList(Request $request) {
    	$param = $request->all();
	    if(!isset($param['type']) || $param['type'] == "")
	    	return response()->json(array());

    	$type = $param['type'];
	    $ACList = AcItem::where('C_D', g_enum('ReportTypeData')[$type])->get();
	    return response()->json($ACList);
    }

    public function ajaxGetReceive(Request $request) {
    	$params = $request->all();
	    $userid = Auth::user()->id;

	    $decide_name =$request->get('d_name');
	    $flow_type = $request->get('flow');
	    $creator = $request->get('creator');
	    $from_date = $request->get('from_date');
	    $to_date = $request->get('to_date');

		$decideTbl = new DecisionReport();
	    $reportList = $decideTbl->getForDatatable($params);


	    return response()->json($reportList);
    }

	public function ajaxReportDecide(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;
		$userRole = Auth::user()->isAdmin;

		if($userRole != SUPER_ADMIN)
			return response()->json('-1');

		$decideTbl = new DecisionReport();
		$ret = $decideTbl->decideReport($params);


		return response()->json($ret);
	}

	public function ajaxReportDetail(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;
		$userRole = Auth::user()->isAdmin;

		if($userRole != SUPER_ADMIN)
			return response()->json('-1');

		$decideTbl = new DecisionReport();
		$ret = $decideTbl->getReportDetail($params);


		return response()->json(1);
	}

	public function ajaxReportData(Request $request) {
    	$params = $request->all();

    	$shipList = ShipRegister::getShipListByOrigin();

    	if(isset($params['shipId'])) {
    		$shipRegNo = ShipRegister::find($params['shipId'])['RegNo'];
    		$voyList = VoyLog::where('ship_ID', $shipRegNo)->get();
	    } else {
			$voyList = array();
	    }

    	return response()->json(array('shipList'    => $shipList, 'voyList' => $voyList));
	}

	public function ajaxProfitList(Request $request) {
    	$params = $request->all();

    	if(isset($params['profitType']))
    	    $profitType = $params['profitType'];
    	else
		    $profitType = 0;

    	$profitList = ACItem::where('C_D', $profitType)->get();

    	return response()->json($profitList);

	}
}