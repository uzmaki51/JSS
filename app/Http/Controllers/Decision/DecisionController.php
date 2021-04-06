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
use App\Models\Decision\DecisionReportAttachment;
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
use Illuminate\Support\Str;

class DecisionController extends Controller
{
	protected $userinfo;

	public function __construct() {
		$this->middleware('auth');
	}

	// Report List
	public function receivedReport(Request $request) {
		Util::getMenuInfo($request);

		$shipList = ShipRegister::all();
		$params = $request->all();

		$id = -1;
		if(isset($params['id']))
			$id = $params['id'];

		return view('decision.received_report', ['draftId'  => $id, 'shipList'  => $shipList]);
	}

	// Draft List
	public function draftReport(Request $request) {
		Util::getMenuInfo($request);
		$shipList = ShipRegister::getShipListByOrigin();

		return view('decision.draft_report', ['shipList'    => $shipList]);
	}

	public function redirect(Request $request) {
		$params = $request->all();

		$draftId = $params['id'];

		return redirect('decision/receivedReport?id=' . $draftId);
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
		$reportTbl['shipNo'] = isset($params['shipNo']) ? $params['shipNo'] : 0;
		$reportTbl['voyNo'] = isset($params['voyNo']) ? $params['voyNo'] : 0;
		if($params['flowid'] == REPORT_TYPE_CONTRACT) {
			$reportTbl['profit_type'] = '';
			$reportTbl['amount'] = '';
			$reportTbl['currency'] = '';
		} else {
			$reportTbl['profit_type'] = isset($params['profit_type']) ? $params['profit_type'] : '';
			$reportTbl['amount'] = isset($params['amount']) ? $params['amount'] : 0;
			$reportTbl['currency'] = isset($params['currency']) ? $params['currency'] : CNY_LABEL;
		}

		$reportTbl['creator'] = $user->id;
		$reportTbl['content'] = isset($params['content']) ? $params['content'] : '';
		$reportTbl['state'] = $params['reportType'];
		$reportTbl->save();

		$isRegister = false;
		if(isset($reportId) && $reportId != "") {
			$lastId = $reportId;
			$isRegister = false;
		} else {
			$last = DecisionReport::all()->last('id');
			$lastId = $last['id'];
			$isRegister = true;
		}

		$attachmentTbl = new DecisionReportAttachment();
		$files = $request->file('attachments');

		if(isset($params['is_update'])) {
			$isUpdate = $params['is_update'];
			foreach ($isUpdate as $item) {
				$value = explode('_', $item);
				if ($value[0] == 'remove') {
					$attachmentTbl->deleteRecord($value[1]);
				}
			}
		}

		if($request->hasFile('attachments')) {
			$attachmentUpdate = DecisionReport::find($lastId);
			$attachmentUpdate['attachment'] = 1;
			$attachmentUpdate->save();
			foreach ($files as $file) {
				$fileName = $file->getClientOriginalName();
				$name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
				$file->move(public_path() . '/reports/' . $params['flowid'] . '/', $name);
				$fileList[] =  array($fileName, public_path() . '/reports/' . $params['flowid'] . '/' . $name);
			}

			$ret = $attachmentTbl->updateAttach($lastId, $fileList);
		}

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

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForDatatable($params);

		return response()->json($reportList);
	}

	public function ajaxGetDraft(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForDatatable($params, REPORT_STATUS_DRAFT);


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

		Session::forget('reportFiles');
//		if($userRole != SUPER_ADMIN)
//			return response()->json('-1');

		$decideTbl = new DecisionReport();
		$retVal = $decideTbl->getReportDetail($params);

		return response()->json($retVal);
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

		$profitList = ACItem::where('C_D', $profitType)->orderBy('id')->get();

		return response()->json($profitList);

	}

	public function ajaxReportFile(Request $request) {
		$params = $request->all();

		$hasFile = $request->file('file');
		if(isset($hasFile)) {
			if(isset($hasFile)) {
				$name = date('Ymd_H_i_s'). '.' . $hasFile->getClientOriginalExtension();
				$hasFile->move(public_path() . '/files/', $name);
				$fileList[] =  array($hasFile->getClientOriginalName(), '/files/' . $name);

				if(Session::get('reportFiles')) {
					$reportFile = Session::get('reportFiles');
				} else {
					$reportFile = array();
				}
				$reportFile[] = $fileList;
				Session::put('reportFiles', $reportFile);
			}
		}

		$retVal = Session::get('reportFiles');

		$retVal[][] = array('请选择文件。', '请选择文件。');

		return response()->json($retVal);
	}

	public function ajaxGetDepartment() {
		$retVal = Unit::where('parentId', '!=', 0)->get();

		return response()->json($retVal);
	}
}