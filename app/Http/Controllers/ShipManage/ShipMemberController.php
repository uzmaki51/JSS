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
use App\Models\ShipTechnique\ShipPort;
use Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Auth;

class ShipMemberController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
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
        $shipList = ShipRegister::select('shipName_En', 'IMO_No')->get();
        $posList = ShipPosition::all();
        $portList = ShipPort::orderBy('Port_En')->get();
        $ksList = Ship::all();
        $typeList = ShipType::all();
        $capacityList = ShipMemberCapacity::all();
        $list = $this->getMemberGeneralInfo();
        $nationList = DB::table('tb_dynamic_nationality')->select('name')->get();
        $securityType = SecurityCert::all();
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
            $training = ShipMemberTraining::where('memberId', $memberId)->groupBy("CertSequence")->get();
            

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
                    'portList'  =>      $portList,
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
                    'nationList' =>    $nationList,
                ]);
        }
        //return view('shipMember.register_member', ['shipList'=>$shipList, 'posList'=>$posList, 'ksList'=>$ksList, 'typeList'=>$typeList, 'state'=>$state]);
        return view('shipMember.register_member',
                //[   'info'      =>      ['id' => -1, 'ShipId' => '', 'Duty' => '', 'sign_on_off' => '', 'sign_on_off' => '', 'ShipID_Book' => '', 'DutyID_Book' => '1', 'IssuedDate' => '', 'ExpiryDate' => '', 'ShipID_organization' => '', 'pos' => '', 'scanPath' => '', 'Remarks' => '', 'crewNum' => '', 'realname' => '', 'Surname' => '', 'GivenName' => '', 'Sex' => 0, 'birthday' => '', 'BirthPlace' => '', 'address' => '', 'tel' => '', 'phone' => '', 'RegDate' => '', 'DelDate' => '', 'crewPhoto' => '', 'signPhoto' => '', 'RegStatus' => '', 'DateOnboard' => ''],
                  [   'info'      =>      ['id' => -1, 'ShipId' => '', 'Duty' => '', 'sign_on_off' => '', 'sign_on_off' => '', 'ShipID_Book' => '', 'DutyID_Book' => '0', 'PortID_Book' => '0', 'IssuedDate' => '', 'ExpiryDate' => '', 'ShipID_organization' => '', 'pos' => '', 'scanPath' => '', 'Remarks' => '', 'crewNum' => '', 'realname' => '', 'BirthCountry' => '', 'GivenName' => '', 'Sex' => 0, 'birthday' => '', 'BirthPlace' => '', 'address' => '', 'tel' => '', 'phone' => '', 'RegDate' => '', 'DelDate' => '', 'crewPhoto' => '', 'signPhoto' => '', 'RegStatus' => '', 'DateOnboard' => '', 'DateOffboard' => '', 'Nationality' => '', 'CertNo' => '', 'OtherContacts' => '', 'BankInformation' => '', 'WageCurrency' => '', 'PassportNo' => '', 'PassportIssuedDate' => '', 'PassportExpiryDate' => '', 'Salary' => '', 'ShipType' => ''],
                    'shipList'  =>      $shipList,
                    'posList'   =>      $posList,
                    'portList'   =>      $portList,
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

                    'security'  =>      $securityType,
                    'training'  =>      null,

                    'examingList'=>     null,
                    'examId'    =>      null,
                    'subList'   =>      null,
                    'codeList'  =>      null,
                    'list'      =>      $list,
                    'nationList' =>     $nationList,
                    'state'     =>      $state,
                ]);
    }

    public function showShipMemberDataTab(Request $request) {
        $memberId = $request->get('memberId');
        if(!empty($memberId)) {
            $info = ShipMember::find($memberId);
            // 登记자료탭
            $shipList = ShipRegister::select('shipName_En', 'RegNo')->get();
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

    public function deleteShipMember(Request $request)
    {
        $dataId = $request->get('dataId');
        $memberData = ShipMember::find($dataId);

        if(is_null($memberData)) {
            return -1;
        } else {
            $memberData->delete();
            ShipMemberSchool::where('memberId', $dataId)->delete();
            ShipMemberCapacityCareer::where('memberId', $dataId)->delete();
            ShipBoardCareer::where('memberId', $dataId)->delete();
        }

        return 1;
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
            return redirect('shipMember/registerShipMember?memberId='.$memberId);
        }
        else
        {
            return back()->with(['state'=>'error']);
        }
        
    }

    public function updateMemberMainInfo(Request $request) {
        $memberId = $request->get('memberId');
        $crewNum = $request->get('crewNum');

        $isExist = ShipMember::where('crewNum', $crewNum)->first();
        if(!empty($isExist) && ($isExist['id'] != $memberId) && $crewNum != "") {
            return "";
        }
        if($memberId != "")
            $member = ShipMember::find($memberId);
        else
            $member = new ShipMember();

        $member['crewNum'] = $crewNum;
        $member['realname'] = $request->get('realname');
        
        $member['GivenName'] = $request->get('GivenName');
        $member['Sex'] = $request->get('Sex');
        $birthday = $request->get('birthday');
        if(empty($birthday))
            $birthday = null;
        $member['birthday'] = $birthday;
        $member['RegStatus'] = $request->get('RegStatus');
        $member['BirthPlace'] = $request->get('BirthPlace');
        $member['BirthCountry'] = $request->get('BirthCountry');
        $dateStr = $request->get('IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $member['IssuedDate'] = $dateStr;
        $dateStr = $request->get('ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $member['ExpiryDate'] = $dateStr;

        $member['tel'] = $request->get('tel');
        $member['address'] = $request->get('address');
        $member['phone'] = $request->get('phone');
        $member['Nationality'] = $request->get('Nationality');
        $member['BankInformation'] = $request->get('BankInformation');
        $member['OtherContacts'] = $request->get('OtherContacts');
        $member['PassportNo'] = $request->get('PassportNo');
        $member['CertNo'] = $request->get('CertNo');
        $member['Salary'] = $request->get('Salary');
        $member['WageCurrency'] = $request->get('WageCurrency');
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
        $member->save();

        if($memberId == "") {
            $memberId = ShipMember::all()->last()->id;
        }

        return $memberId;
    }

    public function updateMemberMainData(Request $request, $memberId) {
        $member = ShipMember::find($memberId);

        $member['ShipId'] = $request->get('ShipId');
        /*
        if($request->has('Duty')) {
            $member['Duty'] = $request->get('Duty');
        }
        */

        $member['sign_on_off'] = $request->get('sign_on_off');
        if ($request->has('DateOnboard')) {
            $member['DateOnboard'] = $request->get('DateOnboard');
        }
        else
        {
            $member['DateOnboard'] = null;
        }

        if ($request->has('DateOffboard')) {
            $member['DateOffboard'] = $request->get('DateOffboard');
        }
        else
        {
            $member['DateOffboard'] = null;
        }

        if ($request->has('ShipID_Book')) {
            $member['ShipID_Book'] = $request->get('ShipID_Book');
        }
        
        if ($request->has('DutyID_Book')) {
            $member['DutyID_Book'] = $request->get('DutyID_Book');
        }

        if ($request->has('PortID_Book')) {
            $member['PortID_Book'] = $request->get('PortID_Book');
        }

        if ($request->has('PassportIssuedDate')) {
            $member['PassportIssuedDate'] = $request->get('PassportIssuedDate');
        }
        else {
            $member['PassportIssuedDate'] = null;
        }

        if ($request->has('PassportExpiryDate')) {
            $member['PassportExpiryDate'] = $request->get('PassportExpiryDate');
        }
        else {
            $member['PassportExpiryDate'] = null;
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
        // 
        $school_goc = ShipBoardCareer::where('memberId', $memberId)->get();
        ShipBoardCareer::where('memberId', $memberId)->delete();

        $FromDate = $request->get('FromDate');
        $ToDate = $request->get('ToDate');
        $ShipName = $request->get('ShipName');
        $DutyID = $request->get('DutyID');
        $GT = $request->get('GT');
        $ShipType = $request->get('ShipType');
        $Power = $request->get('Power');
        $TradingArea = $request->get('TradingArea');
        foreach($FromDate as $index => $data) {
            if ($FromDate[$index] == "" && $ToDate[$index] == "" && $ShipName[$index] == "" && $DutyID[$index] == "0" && $GT[$index] == "" && $ShipType[$index] == "0" && $Power[$index] == "" && $TradingArea[$index] == "") {
                continue;
            }
            else
            {
                $career = new ShipBoardCareer();
                $career['memberId'] = $memberId;
                $dateStr = $FromDate[$index];
                if($dateStr == "")
                    $dateStr = null;
                $career['FromDate'] = $dateStr;

                $dateStr = $ToDate[$index];
                if($dateStr == "")
                    $dateStr = null;
                $career['ToDate'] = $dateStr;

                $career['Ship'] = $ShipName[$index];
                $career['DutyID'] = $DutyID[$index];
                $career['GrossTonage'] = $GT[$index];
                $career['ShipType'] = $ShipType[$index];
                $career['Power'] = $Power[$index];
                $career['SailArea'] = $TradingArea[$index];
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

        $dateStr = $request->get('COC_IssuedDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COC_IssuedDate'] = $dateStr;

        $dateStr = $request->get('COC_ExpiryDate');
        if(empty($dateStr))
            $dateStr = null;
        $capacity['COC_ExpiryDate'] = $dateStr;
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
    }

    public function updateMemberTrainingData(Request $request, $memberId) {
        $STCW = $request->get('Train_STCW');
        $CertNo = $request->get('Train_CertNo');
        $CertIssue = $request->get('Train_CertIssue');
        $CertExpire = $request->get('Train_CertExpire');
        $IssuedBy = $request->get('Train_IssuedBy');

        $result = ShipMemberTraining::insertMemberTrainning($memberId, $STCW, $CertNo, $CertIssue, $CertExpire, $IssuedBy);

        return $result;
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

        $shipList = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship_register.NickName', 'tb_ship.name')
                        ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->get();

        $shipList1 = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship.name')
            ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->orderBy('tb_ship_register.shipName_En')
            ->get();

        $ko_ship_list = Ship::select('id', 'name')->get();
        foreach($list as $member) {
            //$ship = ShipRegister::find($member['ShipID_Book']);
            $ship = ShipRegister::where('IMO_No', $member['ShipId'])->first();
            if($ship)
                $member['book_ship'] = $ship['shipName_En'];
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
        /*
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
        */

        $list = "";//ShipMember::getMemberCertList($shipId, $posId, $capacityId, $month, -1);
        $shipList = ShipRegister::select('shipName_En', 'IMO_No', 'NickName')->get();
		$posList = ShipPosition::all();
        $capacityList = ShipMemberCapacity::all();
        $securityType = SecurityCert::all();

        return view('shipMember.member_cert_list', 
			[	'list'		=>		$list, 
				'shipList'	=>		$shipList,
				'posList'	=>		$posList,
				'capacityList'=>	$capacityList,
                'security'    =>    $securityType,
                /*
				'shipId'	=>		$shipId,
				'posId'		=>		$posId,
				'capacityId'=>		$capacityId,
				'expire'	=>		$month,
				'page'		=>		$page,
				'pageHtml'	=>		$pageHtml,*/
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

    public function ajaxSearchMember(Request $request) {
        $params = $request->all();
        $tbl = new ShipMember();
        $ret = $tbl->getForDatatable($params);
        return response()->json($ret);
    }

    public function ajaxSearchMemberWithWage(Request $request) {
        $params = $request->all();
        $tbl = new ShipMember();
        $ret = $tbl->getForWageDatatable($params);
        return response()->json($ret);
    }

    public function ajaxShipMemberCertList(Request $request) {
        $params = $request->all();
        $tbl = new ShipMember();
        $ret = $tbl->getForCertDatatable($params);
        return response()->json($ret);//
    }

    public function autocomplete(Request $request)
    {
        $names = ShipMember::select("realname")
                    ->where('realname', 'like', '%' . $request->terms . '%')->get();

        $data = array();
        foreach ($names as $name)
        {
            $data[] = $name->realname;
        }
        //echo json_encode($data);
        return response()->json($data);
        //echo json_encode($names);
    }

}