<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/16
 * Time: 6:25
 */

namespace App\Http\Controllers\shipManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Util;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Menu;
use App\Models\ShipManage\Ship;
use App\Models\ShipMember\ShipMember;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipMember\ShipMemberCapacity;
use App\Models\ShipManage\ShipType;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipBoardCareer;
use App\Models\ShipMember\ShipMemberSocial;
use App\Models\ShipMember\ShipMemberFamily;
use App\Models\ShipMember\ShipMemberCareer;
use App\Models\ShipMember\ShipMemberCapacityCareer;
use App\Models\ShipMember\ShipCapacityRegister;
use App\Models\ShipMember\ShipMemberSchool;
use App\Models\ShipMember\ShipMemberTraining;
use App\Models\ShipMember\ShipMemberExaming;
use App\Models\ShipMember\ShipMemberSubExaming;
use App\Models\ShipMember\SecurityCert;
use Config;
use Illuminate\Support\Facades\App;

use Auth;

class ShipMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;

        $locale = Session::get('locale');
        if(empty($locale)) {
            $locale = Config::get('app.locale');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        $this->userInfo = Auth::user();

        $admin = Session::get('admin');
        if($admin > 0){
            $topMenu = Menu::where('parentId', '0')->orderBy('id')->get();
        } else {
            $topMenu = Util::getTopMemu($this->userInfo['menu']);
        }
        foreach($topMenu as $menu) {
            $menu['submenu'] = Menu::where('parentId', '=', $menu['id'])->orderBy('id')->get();
            foreach($menu['submenu'] as $submenu)
            {
                $submenu['thirdmenu'] = Menu::where('parentId', '=', $submenu['id'])->orderBy('id')->get();
            }
        }
		$GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 5;

        if ($admin > 0) {
            $menulist = Menu::where('parentId', '=', '5')->orderBy('id')->get();
            foreach ($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {
			$user = Auth::user();
			if(in_array(5, explode(',', $user['menu']))) {
				$menulist = Menu::where('parentId', '=', '5')->where('admin', '=', '0')->get();
				foreach ($menulist as $menu) {
					$menuId = $menu['id'];
					$submenus = Menu::where('parentId', '=', $menuId)->get();
					$menu['submenu'] = $submenus;
				}
				$GLOBALS['menulist'] = $menulist;
			} else {
				$menulist = Menu::where('parentId', '=', '5')->where('admin', '=', '0')->whereIn('id', explode(',', $user['menu']))->get();
				foreach ($menulist as $menu) {
					$menuId = $menu['id'];
					$submenus = Menu::where('parentId', '=', $menuId)->get();
					$menu['submenu'] = $submenus;
				}
				$GLOBALS['menulist'] = $menulist;
			}
        }
    }

    public function index()
    {
        return redirect('shipMember/shipMember');
    }

    public function loadShipMembers (Request $request) {
        Util::getMenuInfo($request);

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
            $state = 2;

//        echo "<script>alert($state)</script>";
        $shipList = ShipRegister::getShipListOnlyOrigin();
        $posList = ShipPosition::orderBy('id')->get();
        //全部를 의미한다.
        if($state == 3)
            $list = ShipMember::getShipMemberListByKeyword($shipId, $pos, $name, null);
        else
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

        return view('shipMember.memberDirectory',
            [   'list'=>$list,
                'shipList'=>$shipList,
                'posList' => $posList,
                'ship'=>$shipId,
                'pos'=>$pos,
                'name'=>$name,
                'state' => $state,
            ]);
    }

    public function registerShipMember(Request $request) {

        $GLOBALS['selMenu'] = 55;
        $GLOBALS['submenu'] = 0;

        $shipList = ShipRegister::select('shipName_Cn', 'RegNo')->get();
        $posList = ShipPosition::all();
        $ksList = Ship::all();
        $typeList = ShipType::all();
        $capacityList = ShipMemberCapacity::all();
        $list = $this->getMemberGeneralInfo();

        $state = Session::get('state');

        $memberId = $request->get('memberId');
        if($memberId != "") {
            // 登记자료탭
            $info = ShipMember::find($memberId);
            $historyList = ShipBoardCareer::where('memberId', $memberId)->orderBy('FromDate')->get();

            // 登记카드자료
            $card = ShipMemberSocial::where('memberId', $memberId)->first();
            $career = ShipMemberCareer::where('memberId', $memberId)->orderBy('fromDate')->get();

            // 자격관련자료
            // 자격관련자료
            $capacity = ShipCapacityRegister::where('memberId', $memberId)->first();
            $capacity_career = ShipMemberCapacityCareer::where('memberId', $memberId)->orderBy("RegDate")->get();
            $school = ShipMemberSchool::where('memberId', $memberId)->orderBy("id")->get();
            
            // 훈련登记자료
            $training = ShipMemberTraining::where('memberId', $memberId)->first();
            $securityType = SecurityCert::all();

            // 실력평가자료
            $examingList = ShipMemberExaming::where('memberId', $memberId)->orderBy('ExamDate')->get();
            $subList = array(); $examId = '';
            if(count($examingList) > 0) {
                $subList = ShipMemberSubExaming::where('ExamId', $examingList[0]['id'])->orderBy('create_at')->get();
                $examId = $examingList[0]['id'];
            }
            $codeList = ShipMemberExaming::select('ExamCode')->groupBy('ExamCode')->get();

            return view('shipMember.register_member',
                [   'info'      =>      $info,
                    'shipList'  =>      $shipList,
                    'posList'   =>      $posList,
                    'ksList'    =>      $ksList,
                    'memberId'  =>      $memberId,

                    'historyList'=>     $historyList,
                    'typeList'  =>      $typeList,

                    'card'      =>      $card,
                    'careerList'=>      $career,

                    'capacity'  =>      $capacity,
                    'capacity_career'=> $capacity_career,
                    'schoolList'=>      $school,
                    'capacityList'=>    $capacityList,

                    'security'  =>      $securityType,
                    'training'  =>      $training,

                    'examingList'=>     $examingList,
                    'examId'    =>      $examId,
                    'subList'   =>      $subList,
                    'codeList'  =>      $codeList,
                    'list'      =>      $list,
                    'state'     =>      $state,
                ]);
        }
        //return view('shipMember.register_member', ['shipList'=>$shipList, 'posList'=>$posList, 'ksList'=>$ksList, 'typeList'=>$typeList, 'state'=>$state]);
        return view('shipMember.register_member',
                //[   'info'      =>      ['id' => -1, 'ShipId' => '', 'Duty' => '', 'sign_on_off' => '', 'sign_on_off' => '', 'ShipID_Book' => '', 'DutyID_Book' => '1', 'IssuedDate' => '', 'ExpiryDate' => '', 'ShipID_organization' => '', 'pos' => '', 'scanPath' => '', 'Remarks' => '', 'crewNum' => '', 'realname' => '', 'Surname' => '', 'GivenName' => '', 'Sex' => 0, 'birthday' => '', 'BirthPlace' => '', 'address' => '', 'tel' => '', 'phone' => '', 'RegDate' => '', 'DelDate' => '', 'crewPhoto' => '', 'signPhoto' => '', 'RegStatus' => '', 'DateOnboard' => ''],
                  [   'info'      =>      ['id' => -1, 'ShipId' => '', 'Duty' => '', 'sign_on_off' => '', 'sign_on_off' => '', 'ShipID_Book' => '', 'DutyID_Book' => '1', 'IssuedDate' => '', 'ExpiryDate' => '', 'ShipID_organization' => '', 'pos' => '', 'scanPath' => '', 'Remarks' => '', 'crewNum' => '', 'realname' => '', 'Surname' => '', 'GivenName' => '', 'Sex' => 0, 'birthday' => '', 'BirthPlace' => '', 'address' => '', 'tel' => '', 'phone' => '', 'RegDate' => '', 'DelDate' => '', 'crewPhoto' => '', 'signPhoto' => '', 'RegStatus' => '', 'DateOnboard' => '', 'DateOffboard' => '', 'Nationality' => '', 'CertNo' => '', 'OtherContacts' => '', 'BankInformation' => '', 'PassportNo' => '', 'PassportIssuedDate' => '', 'PassportExpiryDate' => ''],
                    'shipList'  =>      $shipList,
                    'posList'   =>      $posList,
                    'ksList'    =>      $ksList,
                    'memberId'  =>      null,

                    'historyList'=>     null,
                    'typeList'  =>      $typeList,

                    'card'      =>      null,
                    'careerList'=>      null,

                    'capacity'  =>      null,
                    'capacity_career'=> null,
                    'schoolList'=>      null,
                    'capacityList'=>    $capacityList,

                    'security'  =>      null,
                    'training'  =>      null,

                    'examingList'=>     null,
                    'examId'    =>      null,
                    'subList'   =>      null,
                    'codeList'  =>      null,
                    'list'      =>      $list,

                    'state'     =>      $state,
                ]);
    }

    public function showShipMemberDataTab(Request $request) {
        $memberId = $request->get('memberId');
        if(!empty($memberId)) {
            $info = ShipMember::find($memberId);
            // 登记자료탭
            $shipList = ShipRegister::select('shipName_Cn', 'RegNo')->get();
            $posList = ShipPosition::all();
            $ksList = Ship::all();
            $typeList = ShipType::all();
            $historyList = ShipBoardCareer::where('memberId', $memberId)->orderBy('FromDate')->get();

            return view('shipMember.member_main_tab', ['info'=>$info, 'shipList'=>$shipList, 'posList'=>$posList, 'ksList'=>$ksList, 'historyList'=>$historyList, 'typeList'=>$typeList]);

            // 자격관련자료
            $capacity = ShipCapacityRegister::where('memberId', $memberId)->first();
            $capacity_career = ShipMemberCapacityCareer::where('memberId', $memberId)->get();
            $school = ShipMemberSchool::where('memberId', $memberId)->orderBy("id")->get();
            $typeList = ShipMemberCapacity::all();
            $capacityList = ShipMemberCapacity::all();
            return view('shipMember.member_capacity_tab', ['memberId'=>$memberId, 'capacity'=>$capacity, 'careerList'=>$career, 'schoolList'=>$school, 'capacityList'=>$capacityList]);

            // 훈련登记자료
            $training = ShipMemberTraining::where('memberId', $memberId)->first();

            return view('shipMember.member_training_tab', ['memberId'=>$memberId, 'training'=>$training]);

            // 실력평가자료
            $examingList = ShipMemberExaming::where('memberId', $memberId)->orderBy('ExamDate')->get();
            $subList = array();
            if(count($examingList) > 0)
                $subList = ShipMemberSubExaming::where('ExamId', $list[0]['id'])->orderBy('create_at')->get();
            $codeList = ShipMemberExaming::select('ExamCode')->groupBy('ExamCode')->get();

            return view('shipMember.member_examing_tab', ['examList'=>$list, 'subList'=>$subList, 'codeList'=>$codeList]);
        }
    }

    public function showMemberExamSubMarks(Request $request) {
        $examId = $request->get('examId');
        $examCode = ShipMemberExaming::where('id', $examId)->first()->ExamCode;
        $subList = ShipMemberSubExaming::where('ExamId',$examId)->orderBy('create_at')->get();
        return view('shipMember.member_subMark_table', ['subList' => $subList, 'examId' => $examId, 'examCode' => $examCode]);
    }

    public function saveExamSubMarks(Request $request) {
        $id = $request->get('id');
        if(empty($id)) {
            $ExamSubMark = new ShipMemberSubExaming();
        } else {
            $ExamSubMark = ShipMemberSubExaming::find($id);
        }
        $ExamSubMark['ExamId'] = $request->get('examId');
        $ExamSubMark['SubMarks'] = $request->get('subMarks');
        $ExamSubMark->save();
        $avg = ShipMemberSubExaming::where('ExamId', $request->get('examId'))->avg('SubMarks');
        return round($avg, 2);
    }

    public function deleteExamSubMarks(Request $request) {
        $id = $request->get('id');
        if(!empty($id)) {
            ShipMemberSubExaming::where('id', $id)->delete();
        }
        $avg = ShipMemberSubExaming::where('ExamId', $request->get('examId'))->avg('SubMarks');
        return round($avg, 2);
    }

    public function updateMemberInfo(Request $request) {
        //dump($request);
        //die();
        $memberId = $this->updateMemberMainInfo($request);
        if ($memberId != "")
        {
            $this->updateMemberMainData($request, $memberId);
            $this->updateMemberCapacityData($request, $memberId);
            $this->updateMemberTrainingData($request, $memberId);
        }
        return redirect('shipMember/registerShipMember?memberId='.$memberId);
    }

    public function updateMemberMainInfo(Request $request) {
        $memberId = $request->get('memberId');
        $crewNum = $request->get('crewNum');

        $isExist = ShipMember::where('crewNum', $crewNum)->first();
        if(!empty($isExist) && ($isExist['id'] != $memberId) && $crewNum != "") {
            $msg = '登记号码是重复了。';
            return back()->with(['state'=>'error', 'msg'=>$msg]);
        }
        if($memberId != "")
            $member = ShipMember::find($memberId);
        else
            $member = new ShipMember();

        $member['crewNum'] = $crewNum;
        $member['realname'] = $request->get('realname');
        $member['Surname'] = $request->get('Surname');
        $member['GivenName'] = $request->get('GivenName');
        $member['Sex'] = $request->get('Sex');
        $birthday = $request->get('birthday');
        if(empty($birthday))
            $birthday = null;
        $member['birthday'] = $birthday;
        $member['RegStatus'] = $request->get('RegStatus');
        $member['BirthPlace'] = $request->get('BirthPlace');
        $member['tel'] = $request->get('tel');
        $member['address'] = $request->get('address');
        $member['phone'] = $request->get('phone');
        $member['Nationality'] = $request->get('Nationality');
        $member['BankInformation'] = $request->get('BankInformation');
        $member['OtherContacts'] = $request->get('OtherContacts');
        $member['PassportNo'] = $request->get('PassportNo');
        $member['CertNo'] = $request->get('CertNo');
        $regDate = $request->get('RegDate');
        if(empty($regDate))
            $regDate = date('Y-m-d');
        $member['RegDate'] = $regDate;

        $delDate = $request->get('DelDate');
        if(empty($delDate))
            $delDate = null;
        $member['DelDate'] = $delDate;

        $photo = $request->file('avatar');
        if (isset($photo)) {
            $ext = $photo->getClientOriginalExtension();
            $photoPath = Util::makeUploadFileName().'.'.$ext;
            $photo->move(public_path('uploads/crewPhoto'), $photoPath);
            $member['crewPhoto'] = $photoPath;
        }

        $sign = $request->file('stamp');
        if (isset($sign)) {
            $ext = $sign->getClientOriginalExtension();
            $signPath = Util::makeUploadFileName().'.'.$ext;
            $sign->move(public_path('uploads/signPhoto'), $signPath);
            $member['signPhoto'] = $signPath;
        }

        $member->save();

        if($memberId == "") {
            $memberId = ShipMember::all()->last()->id;
        }

        return $memberId;
    }

    public function updateMemberMainData(Request $request, $memberId) {
        $member = ShipMember::find($memberId);

        $member['ShipId'] = $request->get('ShipId');
        if($request->has('Duty')) {
            $member['Duty'] = $request->get('Duty');
        }

        $member['sign_on_off'] = $request->get('sign_on_off');
        if ($request->has('DateOnboard')) {
            $member['DateOnboard'] = $request->get('DateOnboard');
        }
        if ($request->has('DateOffboard')) {
            $member['DateOffboard'] = $request->get('DateOffboard');
        }
        if ($request->has('ShipID_Book')) {
            $member['ShipID_Book'] = $request->get('ShipID_Book');
        }

        if ($request->has('DutyID_Book')) {
            $member['DutyID_Book'] = $request->get('DutyID_Book');
        }

        if ($request->has('PassportIssuedDate')) {
            $member['PassportIssuedDate'] = $request->get('PassportIssuedDate');
        }

        if ($request->has('PassportExpiryDate')) {
            $member['PassportExpiryDate'] = $request->get('PassportExpiryDate');
        }

        if($request->has('ShipID_organization')) {
            $member['ShipID_organization'] = $request->get('ShipID_organization');
        }
        
        if($request->has('pos')) {
            $member['pos'] = $request->get('pos');
        }
        $member['Remarks'] = $request->get('Remarks');

        $file = $request->file('crewCard');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/crewCard'), $cardPath);

            $member['ScanState'] = 1;
            $member['scanPath'] = $cardPath;
        }
        $member->save();

        ShipBoardCareer::where('memberId', $memberId)->delete();

        for($i=0;$i<20;$i++) {
            $varname = 'index_'.$i;
            $careerId = $request->get($varname);
            if(isset($careerId)){
                $varName = '_'.$i;
                $career = new ShipBoardCareer();

                $career['memberId'] = $memberId;
                $fromDate = $request->get('FromDate'.$varName);
                if(empty($fromDate))
                    $fromDate = null;
                $career['FromDate'] = $fromDate;

                $toDate = $request->get('ToDate'.$varName);
                if(empty($toDate))
                    $toDate = null;
                if(is_null($fromDate) && is_null($toDate))
                    continue;

                $career['ToDate'] = $toDate;
                $career['Ship'] = $request->get('Ship'.$varName);
                $career['ShipType'] = $request->get('ShipType'.$varName);
                $career['DutyID'] = $request->get('DutyID'.$varName);
                $career['GrossTonage'] = $request->get('GrossTonage'.$varName);
                $career['Power'] = $request->get('Power'.$varName);
                $career['SailArea'] = $request->get('SailArea'.$varName);
                $career['Remarks'] = $request->get('Remarks'.$varName);

                $career->save();
            }
        }
    }

    public function updateMemberCapacityData(Request $request, $memberId) {
        $capacity = ShipCapacityRegister::where('memberId', $memberId)->first();
        if(is_null($capacity)) {
            $capacity = new ShipCapacityRegister();
            $capacity['memberId'] = $memberId;
        }
        
        $capacity['ItemNo'] = $request->get('ItemNo');
        $capacity['CapacityID'] = $request->get('CapacityID');
        $file = $request->file('GOC');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/capacity'), $cardPath);
            $capacity['GOC'] = $cardPath;
        }

        $dateStr = $request->get('IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['IssuedDate'] = $dateStr;

        $dateStr = $request->get('ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['ExpiryDate'] = $dateStr;

        $capacity['COC_Remarks'] = $request->get('COC_Remarks');

        // ----------------------------------
        $capacity['GMDSS_NO'] = $request->get('GMDSS_NO');
        $capacity['GMDSSID'] = $request->get('GMDSSID');
        $file = $request->file('GMDSS_Scan');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/capacity'), $cardPath);
            $capacity['GMDSS_Scan'] = $cardPath;
        }

        $dateStr = $request->get('GMD_IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['GMD_IssuedDate'] = $dateStr;

        $dateStr = $request->get('GMD_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['GMD_ExpiryDate'] = $dateStr;

        $capacity['GMD_Remarks'] = $request->get('GMD_Remarks');

        // ----------------------------------
        $capacity['COENo'] = $request->get('COENo');
        $capacity['COEId'] = $request->get('COEId');
        $file = $request->file('COE_Scan');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/capacity'), $cardPath);
            $capacity['COE_Scan'] = $cardPath;
        }

        $dateStr = $request->get('COE_IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COE_IssuedDate'] = $dateStr;

        $dateStr = $request->get('COE_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COE_ExpiryDate'] = $dateStr;

        $capacity['COE_Remarks'] = $request->get('COE_Remarks');

        // ----------------------------------
        $capacity['COE_GOCNo'] = $request->get('COE_GOCNo');
        $capacity['COE_GOCId'] = $request->get('COE_GOCId');
        $file = $request->file('COE_GOC_Scan');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/capacity'), $cardPath);
            $capacity['COE_GOC_Scan'] = $cardPath;
        }

        $dateStr = $request->get('COE_GOC_IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COE_GOC_IssuedDate'] = $dateStr;

        $dateStr = $request->get('COE_GOC_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COE_GOC_ExpiryDate'] = $dateStr;

        $capacity['COE_GOC_Remarks'] = $request->get('COE_GOC_Remarks');

        // ----------------------------------
        $capacity['WatchNo'] = $request->get('WatchNo');
        $capacity['WatchID'] = $request->get('WatchID');
        $file = $request->file('Watch_Scan');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $cardPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/capacity'), $cardPath);
            $capacity['Watch_Scan'] = $cardPath;
        }

        $dateStr = $request->get('Watch_IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['Watch_IssuedDate'] = $dateStr;

        $dateStr = $request->get('Watch_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['Watch_ExpiryDate'] = $dateStr;

        $capacity['Watch_Remarks'] = $request->get('Watch_Remarks');
        $capacity->save();

        $member = ShipMember::find($memberId);
        $member['QualificationClass'] = $capacity['CapacityID'];

        $member->save();

        // 선원자격경력보관
        ShipMemberCapacityCareer::where('memberId', $memberId)->delete();
        for($i=0;$i<20;$i++) {
            $varname = 'capacity_'.$i;
            $capacityId = $request->get($varname);
            if(isset($capacityId)){
                $varName = '_'.$i;
                $career = new ShipMemberCapacityCareer();

                $career['memberId'] = $memberId;
                $dateStr = $request->get('RegDate'.$varName);
                if(empty($dateStr))
                    $dateStr = null;
                $career['RegDate'] = $dateStr;

                $career['CapacityID'] = $request->get('CapacityID'.$varName);
                $scan = $request->get('GOC'.$varName);
                if($scan == 'on')
                    $career['GOC'] = 1;
                else
                    $career['GOC'] = 0;
                $career['RegReason'] = $request->get('RegReason'.$varName);
                $career['Remarks'] = $request->get('Remarks_Career'.$varName);
                if(is_null($career['RegDate']) && ($career['CapacityID'] == 0) && empty($career['RegReason']) && empty($career['Remarks']))
                    continue;

                $career->save();
            }
        }
        // 선원학력보관
        $school_goc = ShipMemberSchool::where('memberId', $memberId)->get();
        ShipMemberSchool::where('memberId', $memberId)->delete();

        $FromDate = $request->get('FromDate');
        $ToDate = $request->get('ToDate');
        $SchoolName = $request->get('SchoolName');
        $Major = $request->get('Major');
        $Grade = $request->get('Grade');
        $TechQualification = $request->get('TechQualification');
        $SchoolRemarks = $request->get('School_Remarks');

        foreach($FromDate as $index => $data) {

            if ($FromDate[$index] == "" && $ToDate[$index] == "" && $SchoolName[$index] == "" && $Major[$index] == "" && $Grade[$index] == "" && $TechQualification[$index] == "" && $SchoolRemarks[$index] == "") {
                continue;
            }
            else
            {
                $school = new ShipMemberSchool();
                $school['memberId'] = $memberId;
                $dateStr = $FromDate[$index];
                if($dateStr == "")
                    $dateStr = null;
                $school['FromDate'] = $dateStr;

                $dateStr = $ToDate[$index];
                if($dateStr == "")
                    $dateStr = null;
                $school['ToDate'] = $dateStr;

                $school['SchoolName'] = $SchoolName[$index];
                $school['Major'] = $Major[$index];
                $school['Grade'] = $Grade[$index];
                $school['TechQualification'] = $TechQualification[$index];
                $school['Remarks'] = $SchoolRemarks[$index];
                $school->save();
            }
        }
    }

    public function updateMemberTrainingData(Request $request, $memberId) {
        $training = ShipMemberTraining::where('memberId', $memberId)->first();
        if(is_null($training)) {
            $training = new ShipMemberTraining();
            $training['memberId'] = $memberId;
        }
        // 기초안전훈련
        $training['TCBNo'] = $request->get('TCBNo');
        $dateStr = $request->get('TCBIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCBIssuedDate'] = $dateStr;
        $dateStr = $request->get('TCBExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCBExpiryDate'] = $dateStr;
        $training['TCB_Remark'] = $request->get('TCB_Remark');

        $file = $request->file('TCBScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['TCBScan'] = $filename;
        }
        // 전문훈련
        $training['TCSNo'] = $request->get('TCSNo');
        $dateStr = $request->get('TCSIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCSIssuedDate'] = $dateStr;
        $dateStr = $request->get('TCSExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCSExpiryDate'] = $dateStr;
        $training['TCS_Remark'] = $request->get('TCS_Remark');

        $file = $request->file('TCSScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['TCSScan'] = $filename;
        }
        // 유조선훈련
        $training['TCTNo'] = $request->get('TCTNo');
        $dateStr = $request->get('TCTIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCTIssuedDate'] = $dateStr;
        $dateStr = $request->get('TCTExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCTExpiryDate'] = $dateStr;
        $training['TCT_Remark'] = $request->get('TCT_Remark');

        $file = $request->file('TCTScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['TCTScan'] = $filename;
        }

        // 안전
        $training['TCPNo'] = $request->get('TCPNo');

        if($request->has('TCP_certID')) {
            $training['TCP_certID'] = $request->get('TCP_certID');
        }
        $dateStr = $request->get('TCPIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCPIssuedDate'] = $dateStr;
        $dateStr = $request->get('TCPExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['TCPExpiryDate'] = $dateStr;
        $training['TCP_Remark'] = $request->get('TCP_Remark');

        $file = $request->file('TCPScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['TCPScan'] = $filename;
        }

        // 안전보장인증서
        $training['SSONo'] = $request->get('SSONo');
        if($request->has('SSO_certID')) {
            $training['SSO_certID'] = $request->get('SSO_certID');
        }
        $dateStr = $request->get('SSOIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['SSOIssuedDate'] = $dateStr;
        $dateStr = $request->get('SSOExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['SSOExpiryDate'] = $dateStr;
        $training['SSO_Remark'] = $request->get('SSO_Remark');

        $file = $request->file('SSOScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['SSOScan'] = $filename;
        }

        // 숙련(갑판/조기)원
        $training['ASDNo'] = $request->get('ASDNo');
        if($request->has('ASD_typeID')) {
            $training['ASD_typeID'] = $request->get('ASD_typeID');
        }
        $dateStr = $request->get('ASDIssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['ASDIssuedDate'] = $dateStr;
        $dateStr = $request->get('ASDExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['ASDExpiryDate'] = $dateStr;
        $training['ASD_Remark'] = $request->get('ASD_Remark');

        $file = $request->file('ASDScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['ASDScan'] = $filename;
        }
        // 선원건강증서
        $training['MCS_No'] = $request->get('MCS_No');
        $dateStr = $request->get('MCS_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $training['MCS_ExpiryDate'] = $dateStr;
        $training['MCS_Remark'] = $request->get('MCS_Remark');

        $file = $request->file('MCSScan');
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/training'), $filename);
            $training['MCSScan'] = $filename;
        }

        $training->save();
    }

    public function registerMemberExamingData(Request $request) {
        $examId = $request->get('examId');
        if(empty($examId)) {
            $count = ShipMemberExaming::where('ExamCode', $request->get('examCode'))
                ->where('Subject', $request->get('examSubject'))
                ->where('memberId', $request->get('memberId'))->get()->count();
            if($count > 0) return -1;
            $memberExamData = new ShipMemberExaming();
        } else {
            $memberExamData = ShipMemberExaming::find($examId);
        }
        $memberExamData['memberId'] = $request->get('memberId');
        $memberExamData['ExamCode'] = $request->get('examCode');
        $memberExamData['ExamDate'] = $request->get('examDate');
        $memberExamData['Place'] = $request->get('examPlace');
        $memberExamData['Subject'] = $request->get('examSubject');
        $memberExamData['Marks'] = $request->get('examMarks');
        $memberExamData->save();
        return $memberExamData['id'];
    }

    public function deleteMemberExamingData(Request $request) {
        $examId = $request->get('examId');
        if(!empty($examId)) {
            ShipMemberExaming::where('id', $examId)->delete();
            ShipMemberSubExaming::where('ExamId', $examId)->delete();
        }
        if($examId == $request->get('current')) {
            $memberId = $request->get('memberId');
            $exam = ShipMemberExaming::where('memberId', $memberId)->first();
            $examId = '';
            $examCode = '';
            $subList = array();
            if (!empty($exam)) {
                $examId = $exam->id;
                $examCode = $exam->ExamCode;
                $subList = ShipMemberSubExaming::where('ExamId', $examId)->orderBy('create_at')->get();
            }
            return view('shipMember.member_subMark_table', ['subList' => $subList, 'examId' => $examId, 'examCode' => $examCode]);
        }
        return 'success';
    }


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

        $shipList1 = ShipRegister::select('tb_ship_register.RegNo', 'tb_ship_register.shipName_Cn', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->orderBy('tb_ship_register.shipName_Cn')
            ->get();

        $ko_ship_list = Ship::select('id', 'name')->get();
        foreach($list as $member) {
            //$ship = ShipRegister::find($member['ShipID_Book']);
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

        return view('shipMember.total_member_list', ['list'=>$list, 'shipList'=>$shipList, 'shipList1'=>$shipList1, 'ko_ship_list'=>$ko_ship_list, 'regShip'=>$regShip, 'bookShip'=>$bookShip, 'origShip'=>$origShip, 'regStatus'=>$regStatus]);
    }

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
                    $member->ASDType = '갑판'; break;
                case 2:
                    $member->ASDType = '조기'; break;
                default:
                    $member->ASDType = '';
            }

        }

		$pageHtml = Util::makePaginateHtml($pageCount, $page);

        return view('shipMember.member_cert_list', 
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
			]);
    }

    // 선원별실력판정정형
    public function integretedMemberExaming(Request $request) {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $paramExamCode = $request->get('ExamCode');
        $shipList = ShipRegister::orderBy('shipName_Cn')->get();
        $members = ShipMember::getMemberSimpleInfo($shipId);
        $members->appends(['shipId'=>$shipId, 'ExamCode' => $paramExamCode]);

        $memberList = array();
        foreach($members as $member)
            $memberList[] = $member['id'];

        $list = ShipMemberExaming::getMemberMarks($memberList);
        $tExamCodes = ShipMemberExaming::select('ExamCode')->whereIn('memberId', $memberList)->groupBy('ExamCode')->orderBy('ExamCode')->get();
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
        return view('shipMember.member_examing_total', ['memberList'=>$members, 'shipList'=>$shipList, 'examingList'=>$examCodes, 'shipId'=>$shipId, 'examCodes'=>$tExamCodes,
            'paramExamCode' => $paramExamCode]);
    }

    
    public function getMemberGeneralInfo() {
        $member_infolist = ShipMember::select('id', 'crewNum', 'realname', 'Sex', 'birthday', 'Nationality', 'RegStatus')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return $member_infolist;
    }
}