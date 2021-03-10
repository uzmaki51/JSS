<?php

/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 5/17/2017
 * Time: 2:36 PM
 */
namespace App\Http\Controllers\shipTechnique;

use App\Http\Controllers\Controller;
use App\Models\Operations\Account;
use App\Models\Operations\PayMode;
use App\Models\ShipManage\ShipOthers;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipManage\ShipIssaCodeNo;
use App\Models\ShipManage\ShipEquipment;
use App\Models\ShipManage\ShipEquipmentMainKind;

use App\Models\Operations\Cp;
use App\Models\Operations\AcItemDetail;
use App\Models\Operations\Invoice;

use App\Models\ShipTechnique\EquipmentUnit;
use App\Models\ShipTechnique\ShipDept;
use App\Models\ShipTechnique\ShipRepair;
use App\Models\ShipTechnique\ShipAccident;
use App\Models\ShipTechnique\ShipPort;
use App\Models\ShipTechnique\ShipSurvey;
use App\Models\ShipTechnique\ShipSurveyKind;
use App\Models\ShipTechnique\ShipSupply;

use Illuminate\Http\Request;
use App\Http\Controllers\Util;
use Illuminate\Support\Facades\Session;

use App\Models\Menu;

use Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
class shipTechniqueController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;

        $this->userInfo = Auth::user();

        $admin = Session::get('admin');
        if($admin > 0){
            $topMenu = Menu::where('parentId', '0')->get();
        } else {
            $topMenu = Util::getTopMemu($this->userInfo['menu']);
        }
        $GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 7;

        $locale = Session::get('locale');
        if(empty($locale)) {
            $locale = Config::get('app.locale');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

	    if ($admin > 0) {
            $menulist = Menu::where('parentId', '=', '7')->orderBy('id')->get();
            foreach ($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {
			$user = Auth::user();
			if(in_array(7, explode(',', $user['menu']))) {
				$menulist = Menu::where('parentId', '=', '7')->where('admin', '=', '0')->get();
				foreach ($menulist as $menu) {
					$menuId = $menu['id'];
					$submenus = Menu::where('parentId', '=', $menuId)->get();
					$menu['submenu'] = $submenus;
				}
				$GLOBALS['menulist'] = $menulist;
			} else {
				$menulist = Menu::where('parentId', '=', '7')->where('admin', '=', '0')->whereIn('id', explode(',', $user['menu']))->get();
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
        return redirect('shipTechnique/import');
    }

    public function import(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $firstVoy = $request->get('firstVoy');
        $endVoy = $request->get('endVoy');
        $firstPaidVoy = $request->get('firstPaidVoy');
        $endPaidVoy = $request->get('endPaidVoy');
        $pay_mode = $request->get('payMode');
        $Account = Account::get();
        $PayMode = PayMode::get();

        $shipList = ShipRegister::getShipListByOrigin();
        $payList = AcItemDetail::orderBy('AC_Item')->orderBy('id')->get();
        $voyList = array();
        if(!empty($shipId))
            $voyList = Cp::select('id', 'Voy_No', 'CP_No')
                ->where('Ship_ID', $shipId)
                ->orderBy(DB::raw('CONVERT(Voy_No , DECIMAL(4,0))'), 'DESC')
                ->get();

        $data = Invoice::getInvoiceData($shipId, $firstVoy, $endVoy, $firstPaidVoy, $endPaidVoy, $pay_mode);

        return view('operation.import', array(
            'data'      =>  $data,
            'shipList'  =>  $shipList,
            'voyList'   =>  $voyList,
            'payList'   =>  $payList,
            'shipId'    =>  $shipId,
            'firstVoy'  =>  $firstVoy,
            'endVoy'    =>  $endVoy,
            'firstPaidVoy'  =>  $firstPaidVoy,
            'endPaidVoy'    =>  $endPaidVoy,
            'payMode'   =>  $pay_mode,
            'Account'   =>  $Account,
            'PayMode'   =>  $PayMode,
        ));
    }

    //船舶修理조종부
    public function shipRepairRegister(Request $request)
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

        return view('shipTechnique.RepairRegister',array('RepairInfos'=>$recovery,'cps'=>$cps,'shipList'=>$shipList,'shipId'=>$shipId,'voy'=>$voy_number));
    }

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

        return view('shipTechnique.RepairAllBrowse',array('RepairInfos'=>$recovery,'cps'=>$cps,'shipList'=>$shipList,'shipId'=>$shipId,'voy'=>$voy_number));
    }

    public function shipRepairDetail(Request $request) {

        $GLOBALS['selMenu'] = 79;
        $readonly = $request->get('readonly');
        if($readonly == 0)
            $GLOBALS['submenu'] = 116;
        else
            $GLOBALS['submenu'] = 117;

        $id = $request->get('id');
        $shipList = ShipRegister::getShipListByOrigin();
        $recovery = ShipRepair::getRepairDetail($id);
        $shipId = '';
        if(is_null($recovery))
            $shipId = $shipList[0]['RegNo'];
        else
            $shipId = $recovery['ShipId'];

        $cps = CP::getVoyNosOfShip($shipId);

        return view('shipTechnique.RepairDetail',array('shipList'=>$shipList,'cps'=>$cps, 'recovery'=>$recovery, 'readonly'=>$readonly));
    }

    // 报告 追加 및 수정
    public function updateRepair(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)) {
            $repairInfo = new ShipRepair();

            $repairInfo['ShipId'] = $request->get('ShipId');
            $repairInfo['VoyId'] = $request->get('VoyId');
            $repairInfo['FromDate'] = $request->get('FromDate');
            $repairInfo['ToDate'] = $request->get('ToDate');
            $repairInfo['Place'] = $request->get('Place');
            $repairInfo['RepairKind'] = $request->get('RepairKind');
            $repairInfo['D_Officer'] = $request->get('D_Officer');
            $repairInfo['Amount'] = $request->get('Amount');
            $repairInfo['Content'] = $request->get('Content');
            $repairInfo['Detail'] = $request->get('Detail');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $repairInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $repairInfo['AddFileName'] = $filename;
            }

            $repairInfo->save();

        } else {
            $repairInfo = ShipRepair::find($id);

            $repairInfo['ShipId'] = $request->get('ShipId');
            $repairInfo['VoyId'] = $request->get('VoyId');
            $repairInfo['FromDate'] = $request->get('FromDate');
            $repairInfo['ToDate'] = $request->get('ToDate');
            $repairInfo['Place'] = $request->get('Place');
            $repairInfo['RepairKind'] = $request->get('RepairKind');
            $repairInfo['D_Officer'] = $request->get('D_Officer');
            $repairInfo['Amount'] = $request->get('Amount');
            $repairInfo['Content'] = $request->get('Content');
            $repairInfo['Detail'] = $request->get('Detail');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $repairInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $repairInfo['AddFileName'] = $filename;
            }

            $repairInfo->save();
        }

        return redirect('shipTechnique/shipRepairRegister');
    }

    //船舶事故조종부
    public function shipAccidentRegister(Request $request)
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

        return view('shipTechnique.AccidentRegister',
            [   'AccidentInfos' =>  $recovery,
                'cps'           =>  $cps,
                'shipList'      =>  $shipList,
                'shipId'        =>  $shipId,
                'voy'           =>  $voy_number
            ]);
    }

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

        return view('shipTechnique.AccidentAllBrowse',
            [   'AccidentInfos' =>  $recovery,
                'cps'           =>  $cps,
                'shipList'      =>  $shipList,
                'shipId'        =>  $shipId,
                'voy'           =>  $voy_number
            ]);
    }

    public function shipAccidentDetail(Request $request) {

        $GLOBALS['selMenu'] = 81;
        $readonly = $request->get('readonly');
        if($readonly == 0)
            $GLOBALS['submenu'] = 90;
        else
            $GLOBALS['submenu'] = 91;

        $id = $request->get('id');
        $shipList = ShipRegister::getShipListByOrigin();
        $portnames = ShipPort::get(['id','Port_Cn']);
        $accident = ShipAccident::getAccidentDetail($id);
        $shipId = '';
        if(is_null($accident) && count($shipList))
            $shipId = $shipList[0]['RegNo'];
        else
            $shipId = $accident['ShipId'];
        $cps = CP::getVoyNosOfShip($shipId);

        $readonly = $request->get('readonly');

        return view('shipTechnique.AccidentDetail',
            [      'shipList'   =>  $shipList,
                    'portnames' =>  $portnames,
                    'cps'       =>  $cps,
                    'accident'  =>  $accident,
                    'readonly'  =>  $readonly
            ]);
    }

    // 报告 追加 및 수정
    public function updateAccident(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)) {
            $accidentInfo = new ShipAccident();

            $accidentInfo['ShipId'] = $request->get('ShipId');
            $accidentInfo['VoyId'] = $request->get('VoyId');
            $accidentInfo['AccidentDate'] = $request->get('AccidentDate');
            $accidentInfo['PortId'] = $request->get('PortId');
            $accidentInfo['Place'] = $request->get('Place');
            $accidentInfo['AccidentKind'] = $request->get('AccidentKind');
            $accidentInfo['Content'] = $request->get('Content');
            $accidentInfo['Details'] = $request->get('Details');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $accidentInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $accidentInfo['AddFileName'] = $filename;
            }

            $accidentInfo->save();

        } else {
            $accidentInfo = ShipAccident::find($id);

            $accidentInfo['ShipId'] = $request->get('ShipId');
            $accidentInfo['VoyId'] = $request->get('VoyId');
            $accidentInfo['AccidentDate'] = $request->get('AccidentDate');
            $accidentInfo['PortId'] = $request->get('PortId');
            $accidentInfo['Place'] = $request->get('Place');
            $accidentInfo['AccidentKind'] = $request->get('AccidentKind');
            $accidentInfo['Content'] = $request->get('Content');
            $accidentInfo['Details'] = $request->get('Details');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $accidentInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $accidentInfo['AddFileName'] = $filename;
            }

            $accidentInfo->save();
        }

        return redirect('shipTechnique/shipAccidentRegister');
    }

    //船舶检查조종부
    public function shipSurveyRegister(Request $request)
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

        return view('shipTechnique.SurveyRegister',
            [   'SurveyInfos'   =>  $survey,
                'cps'           =>  $cps,
                'shipList'      =>  $shipList,
                'shipId'        =>  $shipId,
                'voy'           =>  $voy_number
            ]);
    }

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

        return view('shipTechnique.SurveyAllBrowse',
            [   'SurveyInfos'=>$survey,
                'cps'=>$cps,
                'shipList'=>$shipList,
                'id'=>$shipId,
                'voy'=>$voy_number
            ]);
    }

    public function shipSurveyDetail(Request $request) {

        $GLOBALS['selMenu'] = 82;
        $readonly = $request->get('readonly');
        if($readonly == 0)
            $GLOBALS['submenu'] = 92;
        else
            $GLOBALS['submenu'] = 93;

        $id = $request->get('id');
        $shipList = ShipRegister::getShipListByOrigin();
        $portnames = ShipPort::get(['id','Port_Cn']);
        $surveykinds = ShipSurveyKind::all();

        $recovery = ShipSurvey::getSurveyDetail($id);

        $shipId = '';
        if(is_null($recovery) && count($shipList))
            $shipId = $shipList[0]['RegNo'];
        else
            $shipId = $recovery['ShipId'];
        $cps = CP::getVoyNosOfShip($shipId);

        $readonly = $request->get('readonly');

        return view('shipTechnique.SurveyDetail',
                [   'shipList'  =>  $shipList,
                    'portnames' =>  $portnames,
                    'cps'       =>  $cps,
                    'surveykinds'=> $surveykinds,
                    'recovery'  =>  $recovery,
                    'readonly'  =>  $readonly
                ]);
    }

    // 报告 追加 및 수정
    public function updateSurvey(Request $request)
    {
        $id = $request->get('id');
        if(empty($id)) {
            $SurveyInfo = new ShipSurvey();

            $SurveyInfo['ShipId'] = $request->get('ShipId');
            $SurveyInfo['VoyId'] = $request->get('VoyId');
            $SurveyInfo['SurveyDate'] = $request->get('SurveyDate');
            $SurveyInfo['PortId'] = $request->get('PortId');
            $SurveyInfo['SurveyKindId'] = $request->get('SurveyKindId');
            $SurveyInfo['Object'] = $request->get('Object');
            $SurveyInfo['Surveyer'] = $request->get('Surveyer');
            $SurveyInfo['Amount'] = $request->get('Amount');
            $SurveyInfo['Content'] = $request->get('Content');
            $SurveyInfo['Deficiency'] = $request->get('Deficiency');
            $SurveyInfo['Rectify'] = $request->get('Rectify');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $SurveyInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $SurveyInfo['AddFileName'] = $filename;
            }
            $SurveyInfo->save();

        } else {
            $SurveyInfo = ShipSurvey::find($id);

            $SurveyInfo['ShipId'] = $request->get('ShipId');
            $SurveyInfo['VoyId'] = $request->get('VoyId');
            $SurveyInfo['SurveyDate'] = $request->get('SurveyDate');
            $SurveyInfo['PortId'] = $request->get('PortId');
            $SurveyInfo['SurveyKindId'] = $request->get('SurveyKindId');
            $SurveyInfo['Object'] = $request->get('Object');
            $SurveyInfo['Surveyer'] = $request->get('Surveyer');
            $SurveyInfo['Amount'] = $request->get('Amount');
            $SurveyInfo['Content'] = $request->get('Content');
            $SurveyInfo['Deficiency'] = $request->get('Deficiency');
            $SurveyInfo['Rectify'] = $request->get('Rectify');

            $file=$request->file('attachFile');
            if(isset($file)) {
                $ext = $file->getClientOriginalExtension();
                $filename = Util::makeUploadFileName() . '.' .$ext;
                $file->move(public_path('uploads/repair'), $filename);

                $SurveyInfo['AddFileServerPath'] = $file->getClientOriginalName();
                $SurveyInfo['AddFileName'] = $filename;
            }

            $SurveyInfo->save();
        }

        return redirect('shipTechnique/shipSurveyRegister');
    }

    public function RepairDelete(Request $request) {

        $repairId = $request->get('repairId');

        $repair = ShipRepair::find($repairId);

        if(is_null($repair))
            return -1;

//        if($repair['creator'] != $this->userinfo['id'])
//            return -2;
//
//        if(!empty($repair['state']))
//            return -3;

        $repair->delete();
        return 1;
    }

    public function AccidentDelete(Request $request) {
        $accidentId = $request->get('accidentId');

        $accident = ShipAccident::find($accidentId);

        if(is_null($accident))
            return -1;

//        if($accident['creator'] != $this->userinfo['id'])
//            return -2;
//
//        if(!empty($accident['state']))
//            return -3;

        $accident->delete();
        return 1;
    }

    public function surveyDelete(Request $request) {
        $surveyId = $request->get('surveyId');

        $survey = ShipSurvey::find($surveyId);

        if(is_null($survey))
            return -1;

//        if($survey['creator'] != $this->userinfo['id'])
//            return -2;
//
//        if(!empty($survey['state']))
//            return -3;

        $survey->delete();
        return 1;
    }

    public function loadSupplyRecord(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
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
        $supplyInfos = ShipSupply::getApplInfo($shipId, $voy);
        $supplyInfos->appends(['shipId'=>$shipId,'voy'=>$voy]);

        return view('shipTechnique.shipEquipment.application',
            array(
                'shipInfos' => $shipInfo,
                'supplyInfos'=>$supplyInfos,
                'shipId'=>$shipId,
                'voy'=>$voy,
                'voyInfos'=>$voyinfo,
                'cpInfo' => $cpInfo,
            )
        );
    }

    public function addApplication(Request $request)
    {
        $supplyId = $request->get('supplyId');
        $supplyInfo = ShipSupply::find($supplyId);
        if(empty($supplyInfo)) {
            $shipId = $request->get('shipId');
            $supplyId = 0;
        } else {
            $shipId = $supplyInfo->ShipName;
        }
        $cpInfos = Cp::select('id', 'Voy_No', 'CP_No')
            ->where('Ship_ID', $shipId)
            ->groupBy('Voy_No')
            ->orderBy('id', 'dsc')
            ->get();
        $shipInfo = ShipRegister::getShipListByOrigin();
        $deptInfos = ShipDept::all(['id','Dept_Cn']);
        $equipUnits = EquipmentUnit::all(['id','Unit_Cn', 'Unit_En']);
        $shipPorts = ShipPort::all(['id', 'Port_Cn', 'Port_En']);
        $kinds = ShipEquipmentMainKind::all(['id', 'Kind_Cn']);

        $equipInfos = ShipSupply::getEquipmentInfo($shipId, $kinds[0]['id']);
        if(count($equipInfos) == 0) $parts =[];
        else $parts = ShipSupply::getPartInfo($equipInfos[0]->id);
        $issaCodes = ShipIssaCodeNo::select(['id','CodeNo','Content_Cn', 'Content_En'])
            ->orderBy('CodeNo')
            ->get();
        $others = ShipOthers::select(['OthersId', 'Others_Cn', 'Others_En', 'Special'])
            ->orderBy('Others_Cn')
            ->get();

        return view('shipTechnique.shipEquipment.application_add', [
            'supplyId' => $supplyId,
            'supplyInfo' => $supplyInfo,
            'shipInfos' => $shipInfo,
            'cpInfos' => $cpInfos,
            'deptInfos' => $deptInfos,
            'equipInfos' => $equipInfos,
            'parts' => $parts,
            'issaCodes' => $issaCodes,
            'others' => $others,
            'kinds' => $kinds,
            'equipUnits' => $equipUnits,
            'shipPorts' => $shipPorts,
        ]);
    }

    public function getInfo(Request $request)
    {
        $type = $request->get('type');
        if($type == 'voy') {
            $shipId = $request->get('shipId');
            $kindId = $request->get('kindId');
            $cpInfos = Cp::select('id', 'Voy_No', 'CP_No')
                ->where('Ship_ID', $shipId)
                ->groupBy('Voy_No')
                ->orderBy('id', 'dsc')
                ->get();
            if($kindId == 1 || $kindId == 2) {
                $equipInfos = ShipSupply::getEquipmentInfo($shipId, $kindId);
                if (count($equipInfos) == 0) $parts = [];
                else $parts = ShipSupply::getPartInfo($equipInfos[0]->id);
            } else {
                $equipInfos = []; $parts = [];
            }
            return view('shipTechnique.shipEquipment.getInfoList')
                ->with(compact('type', 'cpInfos', 'equipInfos', 'parts'));
        } else if($type == 'equipment') {
            $shipId = $request->get('shipId');
            $kindId = $request->get('kindId');
            $equipInfos = ShipSupply::getEquipmentInfo($shipId, $kindId);
            if (count($equipInfos) == 0) $parts = [];
            else $parts = ShipSupply::getPartInfo($equipInfos[0]->id);
            return view('shipTechnique.shipEquipment.getInfoList')
                ->with(compact('type', 'equipInfos', 'parts'));
        } else if($type == 'part') {
            $equipId = $request->get('equipId');
            $parts = ShipSupply::getPartInfo($equipId);
            return view('shipTechnique.shipEquipment.getInfoList')
                ->with(compact('type', 'parts'));
        }
    }

    public function saveSupplyInfo(Request $request)
    {

        $supplyId = $request->get('supplyId');
        if(!empty($supplyId) && $supplyId != 0) {
            $supply = ShipSupply::find($supplyId);
        } else {
            $supply = new ShipSupply();
        }
        $supply->ShipName = $request->get('shipName_Cn');
        $supply->ApplicationVoy = $request->get('ApplicationVoy');
        $supply->No = $request->get('No');
        $supply->Dept = $request->get('Dept_Cn');
        $supply->SSkind = $request->get('Kind_Cn');
        $SSkind = $request->get('Kind_Cn');
        if($SSkind == 1 || $SSkind == 2) {
            $supply->Euipment = $request->get('Euipment_Cn');
            $supply->SN = $request->get('SN');
            $supply->Part = $request->get('PartName_Cn');
            $supply->PartNo = $request->get('PartNo');
        } else if($SSkind == 3) {
            $supply->IssaCodeContent = $request->get('Content_Cn');
            $supply->IssaCodeNo = $request->get('CodeNo');
        } else {
            $supply->Others = $request->get('Others_Cn');
            $supply->OthersSpecial = $request->get('Special');
        }
        $supply->ApplQtty = $request->get('ApplQtty');
        $supply->Unit = $request->get('Unit_Cn');
        $supply->ApplCheck = $request->get('ApplCheck');
        $supply->AppRemark = $request->get('AppRemark');
        $supply->QuotDate = date('Y-m-d', strtotime($request->get('QuotDate')));
        $supply->QuotObject = $request->get('QuotObject');
        $supply->QuotQtty = $request->get('QuotQtty');
        $supply->QuotState = $request->get('QuotState');
        $supply->QuotPrice = $request->get('QuotPrice');
        $supply->QuotPrice = $request->get('QuotPrice');
        $supply->Currency = $request->get('Currency');
        $supply->QuotAmount = $request->get('QuotAmount');
        $supply->QuotRemark = $request->get('QuotRemark');
        $supply->SupplyApplDate = date('Y-m-d', strtotime($request->get('SupplyApplDate')));
        $supply->SupplyApplQtty = $request->get('SupplyApplQtty');
        $supply->SupplyApplCheck = $request->get('SupplyApplCheck');
        $supply->SupplyRemark = $request->get('SupplyRemark');
        $supply->ReciptVoy = $request->get('Recipt');
        $supply->ReciptDate = date('Y-m-d', strtotime($request->get('ReciptDate')));
        $supply->ReciptPlace = $request->get('ReciptPlace');
        $supply->Supplier = $request->get('Supplier');
        $supply->ReciptQtty = $request->get('ReciptQtty');
        $supply->MarketCondition_Usd = $request->get('MarketCondition_Usd');
        $supply->Amount = $request->get('Amount');
        $supply->ReciptPrice = $request->get('ReciptPrice');
        $supply->DeliveryAmount = $request->get('DeliveryAmount');
        $supply->ReciptCheck = $request->get('ReciptCheck');
        $supply->TotalAmount = $request->get('TotalAmount');
        $supply->ReciptRemark = $request->get('ReciptRemark');

        $supply->save();

        return redirect()->back();
    }

    public function deleteSupplyInfo(Request $request)
    {
        $supplyId = $request->get('supplyId');
        if(!empty($supplyId) && $supplyId != 0) {
            ShipSupply::find($supplyId)->delete();
            return 'success';
        }
        return 'fail';
    }

    public function getHistory(Request $request)
    {
        $action = $request->get('action');
        if($action == 'history') {
            $supplyId = $request->get('supplyId');
            $selectSupply = ShipSupply::find($supplyId);
        } else {
            $selectSupply = [];
            $selectSupply['ShipName'] = $request->get('shipId');
            $selectSupply['SSkind'] = $request->get('kindId');
            $selectSupply['Euipment'] = $request->get('equipId');
            $selectSupply['Part'] = $request->get('partId');
            $selectSupply['IssaCodeContent'] = $request->get('issaId');
            $selectSupply['Others'] = $request->get('otherId');
        }
        $supplyInfos = ShipSupply::getHistoryInfo($selectSupply);

        return view('shipTechnique.shipEquipment.getHistoryList')
            ->with(compact('supplyInfos', 'action'));
    }

    public function getVoyList(Request $request)
    {
        $regno = $request->get('shipId');

        $cps = Cp::getVoyNosOfShip($regno);

        return view('shipTechnique.VoyList',array('cps'=>$cps));
    }

    public function getVoyListSearch(Request $request)
    {
        $regno = $request->get('shipId');
        $cps = Cp::getVoyNosOfShip($regno);

        return view('shipTechnique.VoyNumList',array('cps'=>$cps));
    }


    public function getVoyListOfShip(Request $request)
    {
        $shipId = $request->get('shipId');
        $voy = $request->get('voy');
        $voyinfos = ShipSupply::getVoyListByShipId($shipId);
        if(isset($voy))
        {
            return view('shipTechnique.shipEquipment.voyList',
                ['voyInfos' => $voyinfos,'voy' => $voy]);
        } else {
            return view('shipTechnique.shipEquipment.voyList',
                ['voyInfos' => $voyinfos]);
        }

    }

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
        return view('shipTechnique.shipEquipment.supplyInfo',['shipInfos'=>$shipInfo,'equipInfos'=>$equipInfo,
            'kinds'=>$kinds,'supplyInfos'=>$supplyInfos,'shipId'=>$shipRegNo,'kindId'=>$kind,'equip'=>$equipId]);
    }

    public function detailSupplyInfo(Request $request)
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
        if($kind == 1 || $kind == 2) {
            $parts = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'Part');
        } elseif($kind == 3) {
            $parts = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'IssaCodeNo');
        } else {
            $parts = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'Others');
        }
        $QuotObjects = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'QuotObject');
        $ApplicationVoys = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'ApplicationVoy');
        $ReciptVoys = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'ReciptVoy');
        $ReciptPlaces = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'ReciptPlace');
        $Suppliers = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'Supplier');
        $ReciptDates = ShipSupply::getDetailReciptInfo($shipRegNo, $kind, $equipId, 'ReciptDate');
        return view('shipTechnique.shipEquipment.detailSupplyInfo',[
            'shipInfos'=>$shipInfo, 'equipInfos'=>$equipInfo,
            'kinds'=>$kinds,
            'shipId'=>$shipRegNo, 'kindId'=>$kind, 'equip'=>$equipId,
            'Parts' => $parts, 'QuotObjects' => $QuotObjects,
            'ApplicationVoys' => $ApplicationVoys, 'ReciptVoys' => $ReciptVoys,
            'ReciptPlaces' => $ReciptPlaces, 'Suppliers' => $Suppliers,
            'ReciptDates' => $ReciptDates,
        ]);
    }

    public function showDetailSupplyInfo(Request $request)
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
        $part = $request->get('part');
        $QuotObject = $request->get('QuotObject');
        $ApplicationVoy = $request->get('ApplicationVoy');
        $ReciptVoy = $request->get('ReciptVoy');
        $ReciptPlace = $request->get('ReciptPlace');
        $Supplier = $request->get('Supplier');
        $ReciptDate = $request->get('ReciptDate');
        $supplyInfos = ShipSupply::getDetailReciptInfoData($shipRegNo, $kind, $equipId, $part, $QuotObject,
            $ApplicationVoy, $ReciptVoy, $ReciptPlace, $Supplier, $ReciptDate);
        return view('shipTechnique.shipEquipment.supplyInfo',['shipInfos'=>$shipInfo,'equipInfos'=>$equipInfo,
            'kinds'=>$kinds,'supplyInfos'=>$supplyInfos,'shipId'=>$shipRegNo,'kindId'=>$kind,'equip'=>$equipId]);
    }

    public function getEquipmentKindInfo(Request $request)
    {
        $shipRegNo = $request->get('shipId');
        $kinds = ShipSupply::getKindInfo($shipRegNo);
        $select_kind = '<select id="kind" name="kind" class="form-control chosen-select"
            style="height: 25px" onchange="kindChange(this.value)">';
        $chosen_kind = ''; $index = 0; $selected_kind ='';
        foreach($kinds as $kind) {
            $selected_kind = $kinds[0]['Kind_Cn'];
            $selected = ($index == 0) ? 'selected' : '';
            $select_kind .= '<option value="'. $kind['id'] . '" '. $selected . '>' . $kind['Kind_Cn'] . '</option>';
            $chosen_kind .= '<li class="active-result" style="" data-option-array-index="'.$index++.'">'.$kind['Kind_Cn'].'</li>';
        }
        $select_kind .= '</select>';
        $select_equip = '';$disabled = 'disabled';
        $chosen_equip = '';
        $selected_equip =''; $selected_label = ''; $selected_type = ''; $selected_sn = '';
        if(count($kinds) > 0) {
            $equipInfos = ShipSupply::getEquipInfo($shipRegNo, $kinds[0]['id']);
            if($kinds[0]['id'] == 1 || $kinds[0]['id'] == 2) $disabled = '';
            $index = 0;
            foreach($equipInfos as $equipInfo) {
                $selected_equip = $equipInfos[0]['Euipment_Cn'];
                $selected_label = $equipInfos[0]['Label'];
                $selected_type = $equipInfos[0]['Type'];
                $selected_sn = $equipInfos[0]['SN'];
                $selected = ($index == 0) ? 'selected' : '';
                $select_equip .= '<option value="'. $equipInfo['id'] . '" '. $selected . '>' . $equipInfo['Euipment_Cn'] . '</option>';
                $chosen_equip .= '<li class="active-result" style="" data-option-array-index="'.$index++.'">'.$equipInfo['Euipment_Cn'].'</li>';
            }
        }
        $select_equip = '<select name="equipment" id="equipment" class="form-control chosen-select"
            style="height: 25px" onchange="equipmentChange(this.value)" '.$disabled.' >'.$select_equip.'</select>';
        $result = $select_kind . '@' . $chosen_kind . '@' . $selected_kind;
        $result .= '@' . $select_equip . '@' . $chosen_equip . '@' . $selected_equip;
        $result .= '@' . $selected_label . '@' . $selected_type . '@' . $selected_sn;
        return $result;
    }

    public function getEquipmentInfo(Request $request)
    {
        $shipRegNo = $request->get('shipId');
        $kind = $request->get('kind');
        if($kind == 1 || $kind == 2) $disabled = '';
        else $disabled = 'disabled';
        $select_equip = '<select name="equipment" id="equipment" class="form-control chosen-select"
            style="height: 25px" onchange="equipmentChange(this.value)" '.$disabled.' >';
        $chosen_equip = '';
        $selected_equip =''; $selected_label = ''; $selected_type = ''; $selected_sn = '';
        $equipInfos = ShipSupply::getEquipInfo($shipRegNo, $kind);
        $index = 0;
        foreach($equipInfos as $equipInfo) {
            $selected_equip = $equipInfos[0]['Euipment_Cn'];
            $selected_label = $equipInfos[0]['Label'];
            $selected_type = $equipInfos[0]['Type'];
            $selected_sn = $equipInfos[0]['SN'];
            $selected = ($index == 0) ? 'selected' : '';
            $select_equip .= '<option value="'. $equipInfo['id'] . '" '. $selected . '>' . $equipInfo['Euipment_Cn'] . '</option>';
            $chosen_equip .= '<li class="active-result" style="" data-option-array-index="'.$index++.'">'.$equipInfo['Euipment_Cn'].'</li>';
        }
        $select_equip .='</select>';
        $result = $select_equip . '@' . $chosen_equip . '@' . $selected_equip;
        $result .= '@' . $selected_label . '@' . $selected_type . '@' . $selected_sn;
        return $result;
    }

    public function getEquipmentDetailInfo(Request $request)
    {
        $equipId = $request->get('equipId');
        $equipInfo = ShipEquipment::find($equipId);
        $selected_label = ''; $selected_type = ''; $selected_sn = '';
        if(!empty($equipInfo)) {
            $selected_label = $equipInfo['Label'];
            $selected_type = $equipInfo['Type'];
            $selected_sn = $equipInfo['SN'];
        }
        $result = $selected_label . '@' . $selected_type . '@' . $selected_sn;
        return $result;
    }
}