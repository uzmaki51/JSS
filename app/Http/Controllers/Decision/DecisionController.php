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

    //  결재환경페지적재
    public function decisionEnv(Request $request)
    {
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = $request->get('submenu');

        $userid = $this->userinfo['id'];

        $content = DecEnvironment::find($userid);
        $agentInfo = null;

        if(isset($content)) {
            $agentId = $content['agentId'];
            if (!empty($agentId))
                $agentInfo = Util::getUserinfoById($agentId);
        }

        $unitMember = Util::loadMember('radio');

        $state = Session::get('state');
        $msg = Session::get('msg');

        return view('decision.decisionEnv',
            [   'content' => $content,
                'unitMember' => $unitMember,
                'agentInfo' => $agentInfo,
                'id' => $userid,
                'state' => $state,
                'msg'=>$msg
            ]);
    }

    // 결재환경등록
    public function envRegister(Request $request)
    {
        $userid = $this->userinfo['id'];
        $file = $request->file('stamp');
        $stampPath = '';

        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $stampPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/stamp'), $stampPath);
        }
        $envContent = $request->all();

        $ismailUse = $envContent['isMailUse'];
        $isNofunc = $envContent['isNofunc'];
        if(empty($envContent['sDate']) || (trim($envContent['sDate']) == '0000-00-00'))
            $sDate = null;
        else
            $sDate = $envContent['sDate'];

        if(empty($envContent['eDate']) || (trim($envContent['eDate']) == '0000-00-00'))
            $eDate = null;
        else
            $eDate = $envContent['eDate'];

        if($isNofunc && (empty($sDate) || empty($eDate))) {
            $msg = '请输入不在期间。';
            return back()->with(['state'=>'error', 'msg'=>$msg]);
        }

        if(!$isNofunc) {
            $sDate = null;
            $eDate = null;
        }

        $isAuto = empty($envContent['isAuto']) ? null : $envContent['isAuto'];
        $autoreplyContent = empty($envContent['autoreplyContent']) ? null: $envContent['autoreplyContent'];
        $isUseAgent = empty($envContent['isUseAgent']) ? 0 : $envContent['isUseAgent'];
        $agentId = 0;
        if ($isUseAgent == 1) {
            if(empty($envContent['agentId'])) {
                $msg = '需要选择您不在时的代理批准人。';
                return back()->with(['state'=>'error', 'msg'=>$msg]);
            }

            $agentId = $envContent['agentId'];

            $agentEnv = DecEnvironment::find($agentId);
            if(!empty($agentEnv) && ($agentEnv['absFunc'] == 1)) {
                $today = date('Y-m-d');
                if(($agentEnv['startDate'] <= $today) && ($today <= $agentEnv['endDate'])) {
                    $msg = '您选择的代理批准人在不在中。';
                    return back()->with(['state'=>'error', 'msg'=>$msg]);
                }
            }
        }

        $decEnv = DecEnvironment::find($userid);
        if ($decEnv == null) {
            $decEnv = new DecEnvironment;
            $decEnv->id = $userid;
        }

        if(!empty($stampPath))
            $decEnv->signPath = $stampPath;

        $decEnv->mailUse = $ismailUse;
        $decEnv->absFunc = $isNofunc;
        $decEnv->startDate = empty($sDate)? null : $sDate;
        $decEnv->endDate =  empty($eDate)? null : $eDate;
        $decEnv->autoResp = $isAuto;
        $decEnv->respContent = $autoreplyContent;
        $decEnv->agentFunc = $isUseAgent;
        $decEnv->agentId = $agentId;
        $decEnv->save();

        return back()->with(['state'=>'success']);
    }

    //기안서 액쎤 정의
    public function Reportedit(Request $request)
    {
        Util::getMenuInfo($request);

        $user = $this->userinfo;
        $userInfo1 = Util::getUserinfoById($user['id']);
        $flowList = DecisionFlow::get();
        foreach($flowList as $flow) {
            $deciderStr = DecisionFlow::deciderList($flow['id']);
            $flow['deciders'] = $this->getUserNames($deciderStr);
            $flow['receivers'] = $this->getUserNames($flow['recvUsers']);
        }

        $reportNum = '';
        $report = array();

        $reportId = $request->get('reportId');
        if(isset($reportId)) {
            $report = DecisionReport::find($reportId);
            $reportNum = $report['docNo'];
            if(isset($report['flowid'])) {
                $flow = DecisionFlow::find($report['flowid']);
                $deciders = $flow->getDeciderList();
                foreach($deciders as $decider) {
                    $userInfo = Util::getUserinfoById($decider['userId']);
                    $decider['pos'] = $userInfo['pos'];
                    $decider['name'] = $userInfo['name'];
                }
                $report['deciders'] = $deciders;
            }
        }

        if(empty($reportNum))
            $reportNum = $this->makeReportSerialNumber();

        $state = Session::get('state');
        $msg = Session::get('msg');

	    $shipList = ShipRegister::getShipListByOrigin();
	    if(isset($report->flowid))
	        $ACList = AcItem::where('C_D', g_enum('ReportTypeData')[$report->flowid])->get();

        if(count($report))
            return view('decision.Reportedit', array(
            	'userInfo1'     => $userInfo1,
	            'acList'        => $ACList,
	            'user'          => $user,
	            'flows'         => $flowList,
	            'report_num'    => $reportNum,
	            'shipList'      => $shipList,
	            'reportinfo'    =>$report,
	            'state'         =>$state,
	            'msg'           =>$msg
                )
            );
        else
            return view('decision.Reportedit', array(
            	'userInfo1'     => $userInfo1,
//	            'acList'        => $ACList,
	            'user'          => $user,
	            'flows'         => $flowList,
	            'shipList'      => $shipList,
	            'report_num'    => $reportNum,
	            'state'         =>$state,
	            'msg'           =>$msg
                )
            );
    }

    // 기안문건의 문서번호를 생성한다.
    private function makeReportSerialNumber() {
        $lastid = DecisionReport::max('id') + 1001;
        $currentDate = date('Y-m');
        $monthLast = DecisionReport::where('create_at', 'like', $currentDate . '%')->max('id');
        if ($monthLast == null)
            $monthLast = 1;
        else
            $monthLast = $monthLast + 1;
        $year = date('Y');
        $month = date('m');
        $reportNum = Util::makeForthDigital($lastid) . '-' . Util::makeForthDigital($year) . '-' . Util::makeForthDigital($month * 1) . '-' . Util::makeForthDigital($monthLast);
        return $reportNum;
    }

    // 기안문건을 수정페지
    public function updateReport(Request $request)
    {
        $reportId = $request->get('uid');
        if(isset($reportId)){
            $reportInfos=DecisionReport::find($reportId);
            $userid=$reportInfos['creator'];
            $userInfo=Util::getUserinfoById($userid);
            $user=$this->userinfo;
            $flow=DecisionFlow::where('creator', $userid)->get();

            return view('decision.Reportedit', array('userInfo'=>$userInfo, 'user'=>$user,'flows'=>$flow,'reportInfos'=>$reportInfos));
        }
    }

    // 보류된 기안문건을 수정페지
    public function updateSuspendReport(Request $request)
    {
        $reportId = $request->get('rId');
        if(isset($reportId)){
            $reportInfos = DecisionReport::find($reportId);
            $userid = $reportInfos['creator'];
            $userInfo = Util::getUserinfoById($userid);
            $user = $this->userinfo;

			$notes = DecisionNote::where('reportId', $reportId)->orderBy('id')->get();
			$deciderList = Decider::where('flowId', $reportInfos['flowid'])->orderBy('orderNum')->get();

			$respons = array();
			$index = 0;
			foreach($deciderList as $decider) {
				$decideUser = $decider['userId'];

				$userInfo = Util::getUserinfoById($decideUser);
				$decideInfo = array();
				$decideInfo['name'] = $userInfo['name'];
				$decideInfo['pos'] = $userInfo['pos'];
				$decideInfo['stamp'] = $userInfo['stamp'];
				$decideInfo['isDecide'] = 0;

				if(!empty($notes[$index])) {
                    $note = $notes[$index];
                    $state = $note['state'];
                    $decideInfo['stampDate'] = $note['update_at'];
                    $decideInfo['note'] = $note['note'];

                    if ($state == 0){
                        if($note['isAgent'] == 1)
                            $decideInfo['state'] = '代理批准';
                        else
                            $decideInfo['state'] = '批准';
                    }else if ($state == 1) {
						$decideInfo['state'] = '否决';
						$decideInfo['stamp'] = null;
					} else {
						$decideInfo['state'] = '保留';
						$decideInfo['stamp'] = null;
					}

					if($note['isAgent'] == 1){
						$agentUser = Util::getUserinfoById($note['userId']);
						$decideInfo['agentUser'] = $agentUser['name'];
						$decideInfo['agentPos'] = $agentUser['pos'];
						$decideInfo['stamp'] = $agentUser['stamp'];
					} else if($note['isAgent'] == 2){
						$decideInfo['stampDate'] = null;
						$decideInfo['state'] = null;
						$decideInfo['stamp'] = null;
					}


					$decideInfo['isDecide'] = 1;
				} else {
					$decideInfo['stampDate'] = null;
					$decideInfo['state'] = null;
					$decideInfo['stamp'] = null;
				}
				$respons[] = $decideInfo;
				$index++;
			}


			return view('decision.suspend_edit', array('userInfo'=>$userInfo, 'user'=>$user,'decidedInfos'=>$respons,'reportinfo'=>$reportInfos));
        }
    }

	// 문서번호의 증복을 막기 위하여 유효성을 검사한다.
    public function validateReport(Request $request)
    {
        $docNo = $request->get('docno');
        $count = DecisionReport::where('docNo', '=', $docNo)->count();

        if ($count == 0) {
            $result = 'success';
        } else {
            $result = 'faild';
        }
        return json_encode(['result' => $result]);
    }

    // 결재흐름선택시 결재도장페지를 내려보낸다.
    public function getDecidersStamp(Request $request) {

        $flowId = $request->get('flowId');
        $flow = DecisionFlow::find($flowId);
        $deciders = $flow->getDeciderList();
        $stampHtml = '';
        foreach($deciders as $decider) {
            $userInfo = Util::getUserinfoById($decider['userId']);

            $stampHtml .= '<li style="height: 110px; width: 140px">';
            $stampHtml .= '<div class="stamp-item" style="border:1px solid #959f9f;margin:3px;text-align: center">';
            $stampHtml .= '<div style="width:100%;border-bottom: 1px solid #959f9f;padding:3px"><span>결재자</span></div>';
            $stampHtml .= '<div style="width:100%;border-bottom: 1px solid #959f9f;padding:3px">'.$userInfo['pos'].' '.$userInfo['name'].'</div>';
            $stampHtml .= '<div style="padding:3px;height:65px"></div></div></li>';
        }
        $stamp['count'] = count($deciders);
        $stamp['html'] = $stampHtml;

        return json_encode($stamp);
    }

    // 기안문건을 작성한다.
    public function saveReport(Request $request)
    {
        $user = $this->userinfo;
        $reportInfo = $request->all();

        $filename1 = '';
        $fileOrgName1 = '';
        $filename2 = '';
        $fileOrgName2 = '';

        $file1 = $request->file('attachFile1');
        if ($file1 != null) {
            $fileOrgName1 = $file1->getClientOriginalName();
			$fileExt = $file1->getClientOriginalExtension();
            $filename1 = Util::makeUploadFileName() . '.' .$fileExt;
            $file1->move(public_path('uploads/report/'), $filename1);
        }

        $file2 = $request->file('attachFile2');
        if ($file2 != null) {
            $fileOrgName2 = $file2->getClientOriginalName();
			$fileExt = $file2->getClientOriginalExtension();
            $filename2 = Util::makeUploadFileName() . '.' .$fileExt;
            $file2->move(public_path('uploads/report/'), $filename2);
        }

		$reportId = $request->get('reportId') * 1;

        if($reportId > 0) {
            $report = DecisionReport::find(trim($reportId));
            if(empty($report)) {
                $error = '批准文件不正确。请重新加载页面。';
                return back()->with(['state'=>'error', 'msg'=>$error]);
            }

			if($report['eject'] == 2) {
                DecisionNote::where('reportId', $report['id'])->where('state', 2)->delete();

				$decideList = DecisionNote::where('reportId', $reportId)->orderBy('id')->get();
                $state = '';
				foreach($decideList as $decider) {
					$state = empty($state) ? ','.$decider['userId'] .',' : $state . $decider['userId'].',';
				}
				$report->state = $state;

			} else {
				$reportNum = $report['docNo'];
				if(DecisionReport::checkAlreadyExistReportNum($reportNum))
					$report->docNo = $this->makeReportSerialNumber();
				else
					$report->docNo = $reportInfo['report_num'];

                $flowId = $request->get('flowid');
                if(empty($flowId)) {
                    $error = '请选择批准流程。';
                    return back()->with(['state'=>'error', 'msg'=>$error]);
                }
				$report->flowid = $flowId;
                if(isset($reportInfo['fee_type']))
					$report->profit_type = $request->get('fee_type');

                if(isset($reportInfo['shipNo']))
					$report->shipNo = $request->get('shipNo');

                if(isset($reportInfo['amount']))
					$report->amount = $request->get('amount');

                if(isset($reportInfo['currency']))
					$report->currency = $request->get('currency');
			}

			$report->creator = $user['id'];
            $report->title = $reportInfo['decTitle'];
            $report->storage = $reportInfo['saveYears'];
            $report->content = $reportInfo['comment'];
        
			if(!empty($file1)) {
                $report->file1 = $filename1;
                $report->fileName1 = $fileOrgName1;
            }

            if(!empty($file2)) {
                $report->file2 = $filename2;
                $report->fileName2 = $fileOrgName2;
            }

            $report->eject = 0;

            if (isset($reportInfo['flowid'])) {
                $report->flowid = $reportInfo['flowid'];
            }

            if ($reportInfo['tempBox'] == 'temp') {
                $report->tempBox = 1;
            } else {
                $report->tempBox = 0;
                $report->draftDate = date('Y-m-d H:i:s');
            }

            $report->save();


        } else {

			if(empty($filename1) && !empty($filename2)) {
				$filename1 = $filename2;
				$filename2 = '';
				$fileOrgName1 = $fileOrgName2;
				$fileOrgname2 = '';
			}

			$report = new DecisionReport();
            $report->title = $reportInfo['decTitle'];
            $report->docNo = $this->makeReportSerialNumber();
            $report->creator = $user['id'];

            $userTbl = new User();
            $flowType = $reportInfo['flowid'];
            $report->storage = $reportInfo['saveYears'];
            $report->content = $reportInfo['comment'];
            $report->file1 = $filename1;
            $report->fileName1 = $fileOrgName1;
            $report->file2 = $filename2;
            $report->fileName2 = $fileOrgName2;

            if (isset($reportInfo['flowid'])) {
                $report->flowid = $reportInfo['flowid'];
            }

            $flowId = $request->get('flowid');
            if(empty($flowId)) {
                $error = '请选择批准流程。';
                return back()->with(['state'=>'error', 'msg'=>$error]);
            }

            if ($reportInfo['tempBox'] == 'temp') {
                $report->tempBox = 1;
            } else {
                $report->draftDate = date('Y-m-d H:i:s');
            }

	        if(isset($reportInfo['fee_type']))
		        $report->profit_type = $request->get('fee_type');
	        else
	        	$report->profit_type = 0;

	        if(isset($reportInfo['shipNo']))
		        $report->shipNo = $request->get('shipNo');

	        if(isset($reportInfo['amount']))
		        $report->amount = $request->get('amount');

	        if(isset($reportInfo['currency']))
		        $report->currency = $request->get('currency');

            $report->save();

            $last = DecisionReport::all()->last('id');
            $reportId = $last['id'];
	        $userTbl->getRecvUser($flowType, $reportId);
        }
        if ($reportInfo['tempBox'] != 'temp') {
            $this->agentDecide($reportId);
        }

        if ($reportInfo['tempBox'] == 'temp')
            return redirect('decision/draftReport');
        else
            return redirect('decision/Reportview');
    }

    //기안한 목록현시
    public function Reportview(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $decide_name =$request->get('d_name');
        $flow_type = $request->get('flow');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $reportList = DecisionReport::getDecisionReportList($decide_name, $flow_type, $userid, $from_date, $to_date);
        $decisionFlowList = DecisionFlow::getDecisionFlow();

        //결재흐름명을 포함하는 결재흐름ID목록을 얻는다. 만일 검색된것이 없으면 검색을 끝낸다.
        foreach($reportList as $report)
        {
            $decideIdList = DecisionFlow::deciderList($report['flowid']);
            $stateIdList = array();
            if(!empty($report['state']))
                $stateIdList = Util::removeFirstAndEndComma($report['state']);

            $report['decideCount'] = count($stateIdList);
            $report['totlCount'] = count(explode(',', $decideIdList));

			if(count($stateIdList) && $report['eject'] > 0)
				$report['decideCount'] = count($stateIdList) - 1;
            if(count($stateIdList) > 0)
                $report['isEdit'] = 0;
            else
                $report['isEdit'] = 1;

            if($report['eject'] == 2)
                $report['isEdit'] = 2;
        }

        $param = array();
        if(isset($decide_name))
            $param['d_name'] = $decide_name;
        if(isset($flow_name))
            $param['flow'] = $flow_name;
        if(isset($creator))
            $param['creator'] = $creator;
        if(isset($from_date))
            $param['from_date'] = $from_date;
        if(isset($to_date))
            $param['to_date'] = $to_date;

        if(count($param) > 0)
            $reportList->appends($param);

        return view('decision.report_view',
            [   'list' => $reportList,
                'd_name' => $decide_name,
                'flow' => $flow_type,
                'from_date' => $from_date,
                'decisionFlowList'      => $decisionFlowList,
                'to_date' => $to_date,
            ]);
    }

    public function deleteReport(Request $request) {
        $reportId = $request->get('reportId');

        $report = DecisionReport::find($reportId);

        if(is_null($report))
            return -1;

        if($report['creator'] != $this->userinfo['id'])
            return -2;

        if(!empty($report['state']))
            return -3;

        $report->delete();
        return 1;
    }

    /////////////////////////////  결재흐름관리   ///////////////////////
    public function flowmanage(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $flow_name = $request->get('flow');

        if(isset($flow_name))
            $flowlist = DecisionFlow::where('creator', $userid)->where('title', 'like', '%'.$flow_name.'%')->paginate(10)->setPath('');
        else
            $flowlist = DecisionFlow::where('creator', $userid)->paginate(10)->setPath('');

        foreach ($flowlist as $flow) {
            //결재자들의 이름목록얻기
            $deciderList = DecisionFlow::deciderList($flow['id']);
            $flow['decideUserlist'] = $this->getUserNames($deciderList);
            $flow['recvUserlist'] = $this->getUserNames($flow['recvUsers']);
        }

        if(isset($flow_name))
            $flowlist->appends(['flow'=>$flow_name]);

        return view('decision.flow', ['list' => $flowlist, 'flow'=>$flow_name]);
    }

    //ID목록으로부터 이름목록을 얻기
    private function getUserNames($idList)
    {
        $userlist = '';
        $idList = explode(',', $idList);
        foreach ($idList as $userid) {
            if(empty($userid))
                continue;

            $user = UserInfo::where('id', $userid)->first();

            if(is_null($user))
                $userName = '(删掉的用户)';
            else
                $userName = $user['realname'];

            if ($userlist != "") $userlist = $userlist . ', ';
            $userlist = $userlist . $userName;

        }

        return $userlist;
    }

    //결재흐름을 추가 및 변경할 때 호출되는 페지.
    public function addFlow(Request $request)
    {

        $GLOBALS['selMenu'] = 19;
        $GLOBALS['submenu'] = 23;

        $userid = $this->userinfo['id'];

        //부서조직구조별 인원보기 현시자료처리부분
        $str = Util::loadMember('checkbox');

        $flowId = $request->get('flowId');
        if (isset($flowId)) {
            //현재흐름정보얻기
            $flow = DecisionFlow::find($flowId);
            if(isset($flow) && ($flow['creator']) != $userid) {
                return redirect()->back();
            }


            $flowinfo = array();
            $flowinfo['flow_name'] = $flow['title'];
            $flowinfo['decideUsers'] = DecisionFlow::deciderList($flowId);
            $flowinfo['decideUserlist'] = $this->getUserNames($flowinfo['decideUsers']);
            $flowinfo['recvUsers'] = Util::removeFirstAndEndCommaStr($flow['recvUsers']);
            $flowinfo['recvUserlist'] = $this->getUserNames($flow['recvUsers']);

            return view('decision.flowadd', array('flowId' => $flowId, 'result' => $str, 'flowinfo' => $flowinfo));
        }

        return view('decision.flowadd', array('result' => $str));
    }

    //결재흐름을 자료기지에 추가하는 동작을 수행한다.
    public function insertFlow(Request $request)
    {
        $flowinfo = explode('_', $request->get('flowinfo'));

        if(count($flowinfo) > 3) {
            $flowId = trim($flowinfo[0]);
            $flowTitle = $flowinfo[1];
        } else {
            return array('result' => 'parameter');
        }

        $user = Auth::user();

        $flowCnt = DecisionFlow::where('title', $flowTitle)->where('creator', $user['id'])->first();

        if(!empty($flowCnt) && ($flowCnt['id'] != $flowId))
            return array('result' => 'repetition');

        if ($flowId != '0')
            $flow = DecisionFlow::find($flowId);
        else {
            $flow = new DecisionFlow;
            $flow->creator = $user['id'];
        }

        $flow->title = $flowTitle;
        $flow->recvUsers = $flowinfo[3];

        if ($flow->save()) {
            $deciderList = explode(',', $flowinfo[2]);
            if (trim($flowId) == '0') {
                $flowId = DecisionFlow::all()->last('id')->id;
                $index = 1;
                foreach($deciderList as $decider) {
                    if(empty($decider))
                        continue;
                    $flowDecider = new Decider();
                    $flowDecider['flowId'] = $flowId;
                    $flowDecider['userId'] = $decider;
                    $flowDecider['orderNum'] = $index++;
                    $flowDecider->save();
                }
            } else {
                $list = Decider::where('flowId', $flowId)->get();
                foreach($list as $old_user) {
                    $check = 0;
                    foreach($deciderList as $new_user) {
                        if($old_user['userId'] == $new_user){
                            $check = 1;
                            break;
                        }
                    }

                    if($check == 0)
                        $old_user->delete();
                }

                $index = 1;
                foreach($deciderList as $decider) {
                    $isExist = Decider::where('flowId', $flowId)->where('userId', $decider)->first();
                    if(is_null($isExist)) {
                        $flowDecider = new Decider();
                        $flowDecider['flowId'] = $flowId;
                        $flowDecider['userId'] = $decider;
                        $flowDecider['orderNum'] = $index;
                        $flowDecider->save();
                    } else {
                        $isExist['orderNum'] = $index;
                        $isExist->save();
                    }
                    $index++;
                }
            }

            return array('result' => 'success');
        }

        return array('result' => 'fail');
    }

    //결재흐름을 삭제한다.
    public function deleteFlow(Request $request) {

        $userid = $this->userinfo['id'];
        $flowId = $request->get('flow_id');

        // 현재 결재에 리용되였는가를 판정한다.
        $nowDecide = DecisionReport::where('creator', $userid)->where('flowid', $flowId)->get();
        if(count($nowDecide)) {
            $result = array('status' => '正在利用中就而不可以删掉。');
            return json_encode($result);
        }
        $flow = DecisionFlow::find($flowId);
        $flow->delete();
        $result = array('status' => 'success');

        return json_encode($result);
    }

    //////////////////////결재할 문서관리부분///////////////////////////////////
    public function decidemanage(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $page = $request->get('page');
        if(empty($page))
            $page = 1;

        $reportList = DecisionReport::getWillDecisionReportList($userid, '', '', '', '', '', $page);
	    $decisionFlowList = DecisionFlow::getDecisionFlow();
        $pageCount = DecisionReport::countWillDecisionReportList($userid, '', '', '', '', '');

        $paginate = Util::makePaginateHtml($pageCount, $page);

        return view('decision.decide_manage', array(
	            'list'                  => $reportList,
		        'page'                  =>$page,
		        'paginate'              =>$paginate,
		        'flow'                  => '',
		        'decisionFlowList'      => $decisionFlowList
            )
        );
    }

    public function getDecideReportlist(Request $request) {
        //페지에서 넘어온 검색어들에 따라 검색을 진행한다.
        $decide_name = $request->get('decide_name');
        $creator = $request->get('creator');
        $flow_type = $request->get('flow_type');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $page = $request->get('page');
        if(empty($page))
            $page = 1;

        $userid = $this->userinfo['id'];

        $reportList = DecisionReport::getWillDecisionReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date, $page);
	    $decisionFlowList = DecisionFlow::getDecisionFlow();
        $pageCount = DecisionReport::countWillDecisionReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date);

        $paginate = Util::makePaginateHtml($pageCount, $page);

        return view('decision.decide_manage_table', array(
        	    'list'              => $reportList,
	            'page'              =>$page,
	            'paginate'          =>$paginate,
	            'flow'              => $flow_type,
	            'decisionFlowList'  => $decisionFlowList
	        ));
    }

    //결재문건에 대한 상세페지.
    public function showDecidingReport(Request $request)
    {
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = 0;

        $userId = $this->userinfo['id'];
        $report_id = $request->get('reportId');

        //결재문건의 모든 정보들을 얻는다.
        $reportInfo = DecisionReport::find($report_id);
        $notes = DecisionNote::where('reportId', $report_id)->orderBy('id')->get();
        $deciderList = Decider::where('flowId', $reportInfo['flowid'])->orderBy('orderNum')->get();
        $recvUsers = DecisionReport::find($report_id)->recvUser;
        if(!empty($recvUsers)) {
            $userList = explode(',', $recvUsers);
            $recvUserNames = '';
            foreach($userList as $recver) {
                if(empty($recver))
                    continue;

                $recverInfo = Util::getUserinfoById($recver);
                $recvUserNames = empty($recvUserNames) ? $recverInfo['name'].'('.$recverInfo['pos'].')' :
                    $recvUserNames .', '.$recverInfo['name'].'('.$recverInfo['pos'].')';
            }

            $reportInfo['recvUser'] = $recvUserNames;
        }
        $respons = array();
        $index = 0;
        foreach($deciderList as $decider) {
            $decideUser = $decider['userId'];
            $userInfo = Util::getUserinfoById($decideUser);
            $decideInfo = array();
            $decideInfo['name'] = $userInfo['name'];
            $decideInfo['pos'] = $userInfo['pos'];
            $decideInfo['stamp'] = $userInfo['stamp'];
            $decideInfo['isDecide'] = 0;

            if(!empty($notes[$index])){
                $note = $notes[$index];
                $state = $note['state'];
                $decideInfo['stampDate'] = $note['update_at'];
                $decideInfo['note'] = $note['note'];

                if ($state == 0) {
                    if($note['isAgent'] == 1)
                        $decideInfo['state'] = '代理批准';
                    else
                        $decideInfo['state'] = '批准';
                } else if ($state == 1) {
                    $decideInfo['state'] = '否决';
                    $decideInfo['stamp'] = null;
                } else {
                    $decideInfo['state'] = '保留';
                    $decideInfo['stamp'] = null;
                }

                if($note['isAgent'] == 1){
                    $agentUser = Util::getUserinfoById($note['userId']);
                    $decideInfo['agentUser'] = $agentUser['name'];
                    $decideInfo['agentPos'] = $agentUser['pos'];
                    $decideInfo['stamp'] = $agentUser['stamp'];
                } else if($note['isAgent'] == 2){
                    $decideInfo['stampDate'] = null;
                    $decideInfo['state'] = null;
                    $decideInfo['stamp'] = null;
                }


                $decideInfo['isDecide'] = 1;
            } else {
                $decideInfo['stampDate'] = null;
                $decideInfo['state'] = null;
                $decideInfo['stamp'] = null;
            }
            $respons[] = $decideInfo;
            $index++;
        }

        $creator = Util::getUserinfoById($reportInfo['creator']);
        if($reportInfo['flowid'] != 1)
	        $reportInfo['acName'] = json_decode(AcItem::where('id', $reportInfo['profit_type'])->get(), true)[0]['AC_Item_Cn'];
        else
	        $reportInfo['acName'] = '';
	    $reportInfo['shipName'] = ShipRegister::getShipFullName($reportInfo['shipNo'])['shipName_Cn'];

        // 결재가능한가를 판정한다.
        if(($reportInfo['eject'] < 1) && ($reportInfo['flowState'] != 1)) {
            $flow = DecisionFlow::find($reportInfo['flowid']);

            $deciders = $flow->getDeciderList();
            $isDecider = false;
            $orderNum = 1;
            foreach($deciders as $decider) {
                if($orderNum <= count($notes)) {
                    $orderNum++;
                    continue;
                }
                if($decider['userId'] == $userId) {
                    $isDecider = true;
                    if($orderNum <= count($notes)){
                        $isDecider = false;
                        $orderNum++;
                        continue;
                    }
                    else
                        break;
                } else {
                    if($this->checkAgentUser($decider['userId'])) {
                        $isDecider = true;
                        break;
                    }
                }
                $orderNum++;
            }

            //made by kchs
            $reportList = DecisionReport::getWillDecisionReportList($userId, $reportInfo['title'], $flow['id'], $creator['name'], '', '', 1);
            if($isDecider && (count($notes) < $orderNum) && !empty($reportList)) { // 결재페지를 귀환한다.
        		$state = Session::get('status');
        		$msg = Session::get('msg');

                $isShow = ReadReport::where('reportId', $reportInfo['id'])->where('userId', $userId)->first();
                if(empty($isShow)) {
                    $isShow = new ReadReport();
                    $isShow['reportId'] = $report_id;
                    $isShow['userId'] = $userId;
                    $isShow->save();
                }

                return view('decision.report_decide', array('creator'=>$creator, 'reportInfo'=>$reportInfo, 'decidedInfos'=>$respons, 'state'=>$state, 'msg'=>$msg));
			}
        }

        $isShow = ReadReport::where('reportId', $reportInfo['id'])->where('userId', $userId)->first();
        if(empty($isShow)) {
            $isShow = new ReadReport();
            $isShow['reportId'] = $report_id;
            $isShow['userId'] = $userId;
            $isShow->save();
        }

        // 기안완료된 결재문건의 내려받기
        if ($request->exists('download')) {
            $flowlist = DecisionFlow::where('id', $reportInfo['flowid'])->get();

            $recvUsers = explode(",", $flowlist[0]['recvUsers']);
            foreach ($recvUsers as $recvUser) {
                if (empty($recvUser)) {
                    continue;
                }
                $recvUsersInfo[] = Util::getUserinfoById($recvUser);
            }

            $mailcontents = view('decision.download', [
                'creator'       =>$creator,
                'reportInfo'    =>$reportInfo,
                'decidedInfos'  =>$respons,
                'recvUsersInfo' =>!empty($recvUsersInfo) ? $recvUsersInfo: 0
            ])->render();

            $loginUserInfo = Util::getUserinfoById($this->userinfo['id']);

            $this->_downloadReport($mailcontents, $loginUserInfo['name'], $reportInfo);
            exit;
        }

        return view('decision.report_show', array(
        	    'creator'       =>$creator,
	            'reportInfo'    =>$reportInfo,
	            'decidedInfos'  =>$respons
            )
        );
    }

	// 기안문건에 대한 결재처리
    public function submitDecideState(Request $request)
    {

        $reportId = $request->get('reportId');
        $state = $request->get('decideRadio');
        $content = $request->get('decide-content');
        $note = $request->get('decide_note');
        
//        if (($state > 0) && empty($note)) {
//            $msg = "부결하거나 보류하실때에는 의견을 입력하여야 합니다.";
//            return back()->with(['status'=>'error', 'msg'=>$msg]);
//        }
        
        if(empty($content)) {
            $msg = "请填写批准内容。";
            return back()->with(['status'=>'error', 'msg'=>$msg]);
        }

        $userid = $this->userinfo['id'];

        $report = DecisionReport::find($reportId);
        if(empty($report)) {
        	$msg = '批准文件不存在。';
        	return back()->with(['status'=>'error', 'msg'=>$msg]);
        }

        if($report['eject'] > 0) {
        	$msg = '文件已经被否决了。';
        	return back()->with(['status'=>'error', 'msg'=>$msg]);
        }

        $flow = DecisionFlow::find($report['flowid']);
        $decideIdList = $flow->getDeciderList();
        $isDecider = false;

        $noteCount = DecisionNote::where('reportId', $reportId)->count();
        $deciderIndex = 1;
        foreach ($decideIdList as $decider) {
            if($deciderIndex <= $noteCount) {
                $deciderIndex++;
                continue;
            }
            if($decider['userId'] == $userid) {
                $isDecider = true;
                break;
            } else {
                if($this->checkAgentUser($decider['userId'])) {
                    $isDecider = true;
                    break;
                }
            }
            $deciderIndex++;
        }

        if(!$isDecider) {
        	$msg = '你没有批准权限。';
        	return back()->with(['status'=>'error', 'msg'=>$msg]);
        }

        $stateIdList = Util::removeFirstAndEndComma($report['state']);
        $stateCount = count($stateIdList);

        if(empty($report['state']))
            $stateCount = 0;

        if($deciderIndex <= $stateCount) {
        	$msg = '对文件的批准已经完成了。';
        	return back()->with(['status'=>'error', 'msg'=>$msg]);
        }

        for($index = $stateCount; $index <$deciderIndex; $index++) {
            $nextDecider = $decideIdList[$index]['userId'];
            $nextRealDecider = $nextDecider;
            $isAgent = 0;
            $agentId = 0;

            // 대리결재인가를 판정한다.
            if($nextDecider != $userid) {
                if($this->checkAgentUser($nextDecider)) {
                    $agentId = $nextDecider;
                    $nextDecider = $userid;
                    $isAgent = 1;
                }
            }

            if($nextDecider == $userid) {
                if(empty($report['state']))
                    $report['state'] = ','.$userid.',';
                else
                    $report['state'] = $report['state'].$userid.',';

                $decider = new DecisionNote();
                $decider['reportId'] = $reportId;
                $decider['userId'] = $userid;
                $decider['state'] = $state;
                $decider['note'] = $note;
                $decider['isAgent'] = $isAgent;
                $decider['agentId'] = $agentId;
                $decider->save();
            } else {
                if(empty($report['state']))
                    $report['state'] = ',0,';
                else
                    $report['state'] = $report['state'].'0,';

                $decider = new DecisionNote();
                $decider['reportId'] = $reportId;
                $decider['userId'] = $nextRealDecider;
                $decider['state'] = 0;
                $decider['isAgent'] = 2;
                $decider->save();
            }

            $stateCount++;
        }

        // 마지막 결재자인가를 판정한다.
        if(($state == 0) && (count($decideIdList) == $stateCount ))
            $report['flowState'] = 1;

		$report['content'] = $content;
        $report['eject'] = $state;
        $report->save();


        if(($report['flowState'] == 0) && ($state == 0))
            $this->agentDecide($reportId);

        return redirect('/decision/decidestate');
    }

    private function agentDecide($reportId) {

        $report = DecisionReport::find($reportId);
        $flow = DecisionFlow::find($report['flowid']);
        $decideIdList = $flow->getDeciderList();
        $deciderCount = count($decideIdList);
        $stateIdList = Util::removeFirstAndEndComma($report['state']);
        $stateCount = count($stateIdList);
        if(($report['eject'] == 1) || ($report['flowState'] == 1) || ($stateCount >= $deciderCount))
            return 0;

        $deciderIndex = $stateCount;
        $decider = $decideIdList[$deciderIndex]['userId'];

        $userId = $this->userinfo['id'];
        $env = DecEnvironment::find($decider);
        if(isset($env) && ($env['absFunc'] == 1)) {
            $start = $env['startDate'];
            $end = $env['endDate'];
            $today = date('Y-m-d');
            if(($today >= $start) && ($today <= $end)){
                $note = '';
                if($env['autoResp'] && !empty($env['respContent']))
                    $note = $env['respContent'];
                if($env['agentFunc'] == 2) {
                    $decideNote = new DecisionNote();
                    $decideNote['reportId'] = $reportId;
                    $decideNote['userId'] = $decider;
                    $decideNote['state'] = 0;
                    $decideNote['note'] = $note;
                    $decideNote['isAgent'] = 2;
                    $decideNote->save();

                    $state = $report['state'];
                    $state = $stateCount == 0 ? ','.$decider.',' : $state.$decider.',';
                    $report['state'] = $state;
                    $stateCount++;
                    if($deciderCount == $stateCount)
                        $report['flowState'] = 1;
                    $report->save();
                    if(!$this->agentDecide($reportId))
                        return 0;

                } else if(($env['agentFunc'] == 1) && ($this->checkAgentUser($env['agentId']))) {
                    $lastNote = DecisionNote::where('reportId', $reportId)
                        ->where('userId', $userId)
                        ->orderBy('id', 'DESC')
                        ->first();
                    if(is_null($lastNote))
                        return 0;

                    DecisionNote::where('reportId', $reportId)
                        ->where('userId', $userId)
                        ->update(['userId'=>0]);

                    $autoResponse = new DecisionNote();
                    $autoResponse['reportId'] = $reportId;
                    $autoResponse['userId'] = $userId;
                    $autoResponse['state'] = 0;
                    $autoResponse['note'] = $lastNote['note'];
                    $autoResponse['isAgent'] = 0;
                    $autoResponse->save();

                    $state = $report['state'];
                    $state = empty($state) ? ','.$userId.',' : $state .$userId.',';
                    $report['state'] = $state;
                    $stateCount++;
                    if($deciderCount == $stateCount)
                        $report['flowState'] = 1;
                    $report->save();

                    if(!$this->agentDecide($reportId))
                        return 0;
                }
            }
        }

        return 0;
    }

    private function checkAgentUser($deciderId) {
        $deciderEnv = DecEnvironment::find($deciderId);
        if(empty($deciderEnv))
            return 0;

        $userId = $this->userinfo['id'];
        $today = date('Y-m-d');

        if(($deciderEnv['absFunc'] == 1) && ($deciderEnv['startDate'] <= $today) && ($deciderEnv['endDate'] >= $today) && ($deciderEnv['agentFunc'] == 1)) {
            if($deciderEnv['agentId'] == $userId) {
                return $userId;
            } else {
                if($this->checkAgentUser($deciderEnv['agentId']))
                    return $userId;
                else
                    return 0;
            }
        }

        return 0;
    }

    // 결재자목록에서 자기를 대리결재자로 하는 리용자가 있는가를 검사한다.
    private function checkAgentUserInFlow($flowId) {
        $deciderList = explode(',', DecisionFlow::deciderList($flowId));
        foreach($deciderList as $decider) {
            $deciderEnv = DecEnvironment::find($decider);
            if(empty($deciderEnv))
                continue;

            $userId = $this->userinfo['id'];
            $today = date('Y-m-d');

            if(($deciderEnv['absFunc'] == 1) && ($deciderEnv['startDate'] <= $today) && ($deciderEnv['endDate'] >= $today) && ($deciderEnv['agentFunc'] == 1)) {
                if($deciderEnv['agentId'] == $userId) {
                    return $userId;
                } else {
                    if($this->checkAgentUser($deciderEnv['agentId']))
                        return $userId;
                }
            }
        }

        return 0;
    }

    ////////////////////수신된 문서관리부분//////////////////////////////////////////////
    public function receivedReport(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $decide_name =$request->get('d_name');
        $flow_type = $request->get('flow');
        $creator = $request->get('creator');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $page = $request->get('page');
        if(empty($page))
            $page = 1;

        $perPage = 10;
        $reportList = DecisionReport::getReceiveReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date, $page, $perPage);
        $decisionFlowList = DecisionFlow::getDecisionFlow();
        $pageCount = DecisionReport::countReceiveReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date, $perPage);

        $paginateHtml = Util::makePaginateHtml($pageCount, $page);

        return view('decision.received_report',
                [   'list' => $reportList,
                    'd_name' => $decide_name,
                    'flow' => $flow_type,
                    'creator' => $creator,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'page'=>$page,
                    'perPage'=>$perPage,
                    'decisionFlowList'  => $decisionFlowList,
                    'pageHtml' => $paginateHtml
                ]);
    }

    ///////////////////결재진행상태관리부분///////////////////////////////////////////////
    public function decidestate(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $decide_name =$request->get('d_name');
        $flow_type = $request->get('flow');
        $creator = $request->get('creator');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $page = $request->get('page');
        if(empty($page))
            $page = 1;
        $reportList = DecisionReport::getProcessReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date, $page);
        $decisionFlowList = DecisionFlow::getDecisionFlow();
        $pageCount = DecisionReport::countProcessReportList($userid, $decide_name, $flow_type, $creator, $from_date, $to_date);

        $paginate = Util::makePaginateHtml($pageCount, $page);

        return view('decision.decide_state',
            [
                'list'                  => $reportList,
                'd_name'                => $decide_name,
                'flow'                  => $flow_type,
                'creator'               => $creator,
                'from_date'             => $from_date,
                'to_date'               => $to_date,
                'decisionFlowList'      => $decisionFlowList,
                'page'                  => $page,
                'paginate'              => $paginate,
            ]);
    }

    /////////////////림시보관함관리부분//////////////////////////////////////////////
    public function draftReport(Request $request)
    {
        Util::getMenuInfo($request);

        $userid = $this->userinfo['id'];

        $decide_name =$request->get('d_name');
        $flow_name = $request->get('flow');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $reportList = DecisionReport::getDraftReportList($decide_name, $flow_name, $userid, $from_date, $to_date);

        //결재흐름명을 포함하는 결재흐름ID목록을 얻는다. 만일 검색된것이 없으면 검색을 끝낸다.
        foreach ($reportList as $report) {
            //해당 부서명을 얻는다.
            $unitinfo = Unit::find($report['submitUnit']);
            $report['unit_name'] = $unitinfo['title'];
        }

        $param = array();
        if(isset($decide_name))
            $param['d_name'] = $decide_name;
        if(isset($flow_name))
            $param['flow'] = $flow_name;
        if(isset($create_date))
            $param['c_date'] = $create_date;

        if(count($param) > 0)
            $reportList->appends($param);

        return view('decision.draftReport',
            ['list' => $reportList,
                'd_name' => $decide_name,
                'flow' => $flow_name,
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);
    }

    // 결재할 문서가 있는가를 검사한다.
    public function checkWillDecideDoc() {
        $userId = $this->userinfo['id'];
        $count = DecisionReport::countWillDecisionReportList($userId, '', '', '', '', '');

        return $count;
    }

    // 새로 결재된 문서가 수신됐는가를 검사한다.
    public function checkRecvDecideDoc() {
        $userId = $this->userinfo['id'];

        $count = DecisionReport::countNoReadRecvReport($userId);

        return $count;
    }

    /**
     * 기안완료된 문서를 메일화일(eml)형식으로 내려받기한다.
     * 2017/09/07 JHS 새로 작성
     */
    protected function _downloadReport($mailcontents, $userName, $reportInfo) {
        // 메일송신
        require_once base_path('vendor/phpmailer/PHPMailerAutoload.php');
        $mail = new \PHPMailer;
        $mail->SMTPDebug = 4;                           // Enable verbose debug output
        $mail->isSMTP();                                // Set mailer to use SMTP

        // - 서버설정
        $mail->Host = 'localhost';                      // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                         // Enable SMTP authentication
        $mail->SMTPSecure = '';                         // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                               // TCP port to connect to

        $mail->CharSet = "utf-8";
        $mail->From = 'pgi@google.com';
        $mail->FromName = '船舶业务管理网站';

        $mail->addAddress('ftb@sts.co.kp', $userName);     // Add a recipient
        $mail->Subject = $reportInfo['title'];
        $mail->isHTML();
        $mail->msgHTML($mailcontents, public_path());
        $mail->AltBody = 'This is a plain-text message body';

        // - 파일첨부하기
        if (!empty($reportInfo['fileName1'])) {
            $filePath = public_path('uploads/report/' . $reportInfo['file1']);//$uploadPath.'/tickets/'.$ticketProject.'/'.$ticketFileRealname;
            $filePath = $this->_convertEncoding($filePath);
            $mail->addAttachment($filePath, $reportInfo['fileName1']);
        }

        if (!empty($reportInfo['fileName2'])) {
            $filePath = public_path('uploads/report/' . $reportInfo['file2']);//$uploadPath.'/tickets/'.$ticketProject.'/'.$ticketFileRealname;
            $filePath = $this->_convertEncoding($filePath);
            $mail->addAttachment($filePath, $reportInfo['fileName2']);
        }

        //Download
        if(!$mail->PreSend()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            header('Content-type: application/eml');
            header('Content-Disposition:attachment;filename= "'.$reportInfo['title'].'.eml"');
            echo $mail->GetSentMIMEMessage();
        }
    }

    protected function _convertEncoding($str = '', $toEncoding = 'EUC-KR', $fromEncoding = 'UTF-8')
    {
        if (empty($str)) {
            return '';
        }

        $str = mb_convert_encoding( $str, $toEncoding, $fromEncoding);

        return $str;
    }

    public function getACList(Request $request) {
    	$param = $request->all();
	    if(!isset($param['type']) || $param['type'] == "")
	    	return response()->json(array());

    	$type = $param['type'];
	    $ACList = AcItem::where('C_D', g_enum('ReportTypeData')[$type])->get();
	    return response()->json($ACList);
    }
}