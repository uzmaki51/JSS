<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/13
 * Time: 9:39
 */

namespace App\Http\Controllers\shipManage;

use App\Http\Controllers\Controller;
use App\Models\Member\Unit;
use App\Models\ShipManage\ShipEquipmentUnits;
use App\Models\ShipManage\ShipOthers;
use App\Models\ShipMember\ShipMember;
use App\Models\ShipMember\ShipMemberCapacity;
use Illuminate\Http\Request;
use App\Http\Controllers\Util;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Menu;
use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipType;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipMember\ShipSTCWCode;
use App\Models\ShipMember\ShipTrainingCourse;
use App\Models\ShipMember\ShipPosReg;
use App\Models\ShipManage\ShipPhoto;
use App\Models\ShipManage\ShipCertList;
use App\Models\ShipManage\ShipCertRegistry;
use App\Models\ShipManage\ShipEquipmentMainKind;
use App\Models\ShipManage\ShipEquipmentSubKind;
use App\Models\ShipManage\ShipEquipmentRegKind;
use App\Models\ShipManage\ShipEquipment;
use App\Models\ShipManage\ShipDiligence;
use App\Models\ShipManage\ShipEquipmentPart;
use App\Models\ShipManage\ShipEquipmentProperty;
use App\Models\ShipManage\ShipIssaCode;
use App\Models\ShipManage\ShipIssaCodeNo;
use App\Models\ShipManage\ShipFreeBoard;
use App\Models\ShipManage\Ctm;

use App\Models\ShipTechnique\EquipmentUnit;

use Auth;
use Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Lang;

class ShipRegController extends Controller
{
    protected $userInfo;
    private $control = 'shipManage';
    protected $__CERT_EXCEL = array(
    	['Nationality / Registry', 'COR'],
	    ['Minimum Safe Manning', 'A-2 MSMC'],
	    ['Tonnage' , 'ITC'],
	    ['Load Line'   , 'ILL'],
	    ['IOPP', 'IOPP-A'],
	    ['Safety Construction'  , 'SC'],
	    ['Safety Equipment', 'SE'],
	    ['Saftey Radio'    , 'SR'],
	    ['CLC' , 'BCC'],
	    ['DOC' , 'DOC'],
	    ['SMC', 'SMC'],
	    ['ISSC', 'ISSC'],
	    ['Life saving appliances Provided for a total number of', 'SE']
    );

	protected $__MEMBER_EXCEL_COC = array(
		['MASTER', 'caption'],
		['CHIEF MATE', 'C / Officer'],
		['2nd DECK OFFICER' , '2 / Officer'],
		['3nd DECK OFFICER'   , '3 / Officer'],
		['RADIO OFFICER', 'Radio Officer personnel'],);
	protected $__MEMBER_EXCEL_GOC = array(
		['CHIEF ENGINEER'  , 'C / Engineer'],
		['2nd ENGINEER', '2 / Engineer'],
		['3rd ENGINEER'    , '3 / Engineer'],
	);

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return redirect('shipManage/shipinfo');
    }

    //배제원현시부분
    public function loadShipGeneralInfos(Request $request) {
        $ship_infolist = $this->getShipGeneralInfo();

	    $params = $request->all();

	    if(isset($params['id']))
		    $ship_id = $params['id'];
	    else {
		    $ship_id = ShipRegister::first()->id;
	    }

	    $ship_id = isset($ship_id) ? $ship_id : 0;

	    $shipRegTbl = new ShipRegister();
	    $elseInfo = $shipRegTbl->getShipForExcel($ship_id, $this->__CERT_EXCEL);

	    $shipInfo = ShipRegister::where('id', $ship_id)->first();
	    $shipCertList = ShipCertRegistry::where('ship_id', $ship_id)->get();

	    $imo_no = $shipInfo->IMO_No;
		$shipName = $shipInfo->shipName_En;
	    $shipTypeTbl = ShipType::where('id', $shipInfo->ShipType)->first();
	    $shipInfo['ShipType'] = isset($shipTypeTbl) ? $shipTypeTbl['ShipType'] : '';

		$shipMembers = ShipMember::where('ShipId', $imo_no)->get();

		$memberCertXls['COC'] = $this->__MEMBER_EXCEL_COC;
	    $memberCertXls['GOC'] = $this->__MEMBER_EXCEL_GOC;

        return view('shipManage.shipinfo', [
        	'list'      => $ship_infolist,
	        'shipInfo'  => $shipInfo,
			'shipName'	=> $shipName,
	        'elseInfo'  => $elseInfo,
	        'id'        => $ship_id,
	        'memberCertXls'       =>    $memberCertXls
        ]);
    }

    public function exportShipInfo(Request $request) {
	    $params = $request->all();

	    if(isset($params['id']))
		    $ship_id = $params['id'];
	    else {
		    $ship_id = ShipRegister::first()->id;
	    }

	    $ship_id = isset($ship_id) ? $ship_id : 0;

	    $shipInfo = ShipRegister::where('id', $ship_id)->first();
	    $shipName = $shipInfo->NickName;
	    if(!isset($shipName) || $shipName == '')
	    	$shipName = $shipInfo->shipName_En;

	    $shipTypeTbl = ShipType::where('id', $shipInfo->ShipType)->first();
	    $shipInfo['ShipType'] = isset($shipTypeTbl) ? $shipTypeTbl['ShipType'] : '';

	    return view('shipManage.shipinfo', [
		    'shipInfo'          => $shipInfo,
		    'is_excel'          => 1,
		    'excel_name'        => $shipName . '_SHIP PARTICULARS_' . date('Ymd'),
			'shipName'			=> $shipName,
		    'id'                => $ship_id
	    ]);
    }

    //배登记
    public function registerShipData(Request $request) {
        $GLOBALS['selMenu'] = 52;
        $GLOBALS['submenu'] = 0;

        $shipList = Ship::all();
        $shipType = ShipType::all();

        $shipId = $request->get('shipId');
        if(is_null($shipId))
            $shipId = '0';
        
        if($shipId != '0') {
            $shipInfo = ShipRegister::where('id', $shipId)->first();
            $freeBoard = ShipFreeBoard::where('shipId', $shipId)->first();
        } else {
            $shipInfo = new ShipRegister();
            $freeBoard = new ShipFreeBoard();
        }

        $status = Session::get('status');

        $ship_infolist = $this->getShipGeneralInfo();
        return view('shipManage.shipregister', [
                        'shipList'      =>  $shipList, 
                        'shipType'      =>  $shipType, 
                        'shipInfo'      =>  $shipInfo, 
                        'freeBoard'     =>  $freeBoard, 
                        'status'        =>  $status,
                        'list'          =>  $ship_infolist
                    ]);
    }

    public function saveShipData(Request $request) {
	    $params = $request->all();
	    $shipId = trim($request->get('shipId')) * 1;
	    $freeId = $request->get('freeId');

	    if($shipId > 0) {
	    	$isRegister = false;
		    $shipData = ShipRegister::find($shipId);
	    } else {
	    	$isRegister = true;
		    $shipData = new ShipRegister();
	    }

	    $commonLang = Lang::get('common');
	    $lastShipId = $this->saveShipGeneralData($params, $shipData);
	    if($lastShipId != false && $lastShipId != "") {
			$this->saveShipHullData($params, $lastShipId, $freeId);
		    $this->saveShipMachineryData($params, $lastShipId);
		    $this->saveShipRemarksData($params, $lastShipId);
		    $status = $isRegister == true ? $commonLang['message']['register']['success'] : $commonLang['message']['update']['success'];
            return redirect(url('shipManage/registerShipData?shipId=' . $lastShipId));
	    } else {
		    //$status = $isRegister == true ? $commonLang['message']['register']['failed'] : $commonLang['message']['update']['failed'];
            return back()->with(['status'=>'error']);
	    }

	    //return redirect(url('shipManage/registerShipData?shipId=' . $lastShipId));
    }

    public function saveShipGeneralData($params, $shipData) {
    	//try {
            $shipId = $params['shipId'];
            $IMO_No = $params['IMO_No'];
            $isExist = ShipRegister::where('IMO_No', $IMO_No)->first();
            if(!empty($isExist) && ($isExist['id'] != $shipId) && $IMO_No != "") {
                return "";
            }
		    $shipData['shipName_Cn'] = $params['shipName_Cn'];
		    $shipData['shipName_En'] = $params['shipName_En'];
		    $shipData['NickName'] = $params['NickName'];
		    $shipData['Class'] = $params['Class'];
		    $shipData['RegNo'] = $params['RegNo'];
		    $shipData['RegStatus'] = $params['RegStatus'];
		    $shipData['CallSign'] = $params['CallSign'];
		    $shipData['MMSI'] = $params['MMSI'];
		    $shipData['IMO_No'] = $params['IMO_No'];
		    $shipData['INMARSAT'] = isset($params['INMARSAT']) ? $params['INMARSAT'] : null;
		    $shipData['order'] = isset($params['order']) ? ($params['order'] == "" ? 0 : $params['order']) : 0;
		    $shipData['OriginalShipName'] = $params['OriginalShipName'];
		    $shipData['FormerShipName'] = $params['FormerShipName'];
		    $shipData['SecondFormerShipName'] = $params['SecondFormerShipName'];
		    $shipData['Flag'] = $params['Flag'];
		    $shipData['PortOfRegistry'] = $params['PortOfRegistry'];
		    $shipData['Owner_Cn'] = $params['Owner_Cn'];
		    $shipData['OwnerAddress_Cn'] = $params['OwnerAddress_Cn'];
		    $shipData['OwnerTelnumber'] = $params['OwnerTelnumber'];
		    $shipData['OwnerFax'] = $params['OwnerFax'];
		    $shipData['OwnerEmail'] = $params['OwnerEmail'];
		    $shipData['ISM_Cn'] = $params['ISM_Cn'];
		    $shipData['ISMAddress_Cn'] = $params['ISMAddress_Cn'];
		    $shipData['ISMTelnumber'] = $params['ISMTelnumber'];
		    $shipData['ISMFax'] = $params['ISMFax'];
		    $shipData['ISMEmail'] = $params['ISMEmail'];
		    $shipData['ShipType'] = $params['ShipType'] == "" ? null : $params['ShipType'];
		    $shipData['GrossTon'] = $params['GrossTon'] == "" ? null : $params['GrossTon'];
		    $shipData['LOA'] = $params['LOA'] == "" ? null : $params['LOA'];
		    $shipData['NetTon'] = $params['NetTon'] == "" ? null : $params['NetTon'];
		    $shipData['LBP'] = $params['LBP'] == "" ? null : $params['LBP'];
		    $shipData['Deadweight'] = $params['Deadweight'] == "" ? null : $params['Deadweight'];
		    $shipData['Length'] = $params['Length'] == "" ? null : $params['Length'];
		    $shipData['Displacement'] = $params['Displacement'] == "" ? null : $params['Displacement'];
		    $shipData['BM'] = $params['BM'] == "" ? null : $params['BM'];
		    $shipData['Ballast'] = $params['Ballast'] == "" ? null : $params['Ballast'];
		    $shipData['DM'] = $params['DM'] == "" ? null : $params['DM'];
		    $shipData['FuelBunker'] = $params['FuelBunker'];
		    $shipData['ShipBuilder'] = $params['ShipBuilder'];
		    $shipData['KeelDate'] = $params['KeelDate'];
		    $shipData['DeckErection_B'] = $params['DeckErection_B'] == "" ? null : $params['DeckErection_B'];
		    $shipData['LaunchDate'] = $params['LaunchDate'];
		    $shipData['DeckErection_F'] = $params['DeckErection_F'] == "" ? null : $params['DeckErection_F'];
		    $shipData['DeliveryDate'] = $params['DeliveryDate'];
		    $shipData['DeckErection_P'] = $params['DeckErection_P'] == "" ? null : $params['DeckErection_P'];
		    $shipData['ConversionDate'] = $params['ConversionDate'];
		    $shipData['DeckErection_H'] = $params['DeckErection_H'] == "" ? null : $params['DeckErection_H'];
		    $shipData['RegDate'] = $params['RegDate'] == "" ? null : $params['RegDate'];
		    $shipData['RenewDate'] = $params['RenewDate'] == "" ? null : $params['RenewDate'];
		    $shipData['KCExpiryDate'] = $params['KCExpiryDate'] == "" ? null : $params['KCExpiryDate'];
		    $shipData['ConditionalDate'] = $params['ConditionalDate'] == "" ? null : $params['ConditionalDate'];
		    $shipData['DelDate'] = $params['DelDate'] == "" ? null : $params['DelDate'];
		    $shipData['Draught'] = $params['Draught'] == "" ? null : $params['Draught'];
		    $shipData['BuildPlace_Cn'] = $params['BuildPlace_Cn'];
		    $shipData->save();

		    return $shipData['id'];
	    //} catch (\Exception $exception) {
    	//	return false;
	    //}
    }

    public function saveShipHullData($params, $shipId, $freeId) {
//    	try {
		    if($shipId > 0) {
			    $shipData = ShipRegister::find($shipId);
		    } else {
			    $shipData = new ShipRegister();
		    }
            $shipData['Hull'] = $params['Hull'] == "" ? null : $params['Hull'];
            $shipData['HullNotation'] = $params['HullNotation'] == "" ? null : $params['HullNotation'];
            $shipData['Machinery'] = $params['Machinery'] == "" ? null : $params['Machinery'];
            $shipData['MachineryNotation'] = $params['MachineryNotation'] == "" ? null : $params['MachineryNotation'];
            $shipData['Refrigerater'] = $params['Refrigerater'] == "" ? null : $params['Refrigerater'];
            $shipData['RefrigeraterNotation'] = $params['RefrigeraterNotation'] == "" ? null : $params['RefrigeraterNotation'];
            

		    $shipData['HullNo'] = $params['HullNo'] == "" ? null : $params['HullNo'];
		    $shipData['Decks'] = $params['Decks'] == "" ? null : $params['Decks'];
		    $shipData['Bulkheads'] = $params['Bulkheads'] == "" ? null : $params['Bulkheads'];
		    $shipData['NumberOfHolds'] = $params['NumberOfHolds'] == "" ? null : $params['NumberOfHolds'];
		    $shipData['CapacityOfHoldsG'] = $params['CapacityOfHoldsG'] == "" ? null : $params['CapacityOfHoldsG'];
		    $shipData['CapacityOfHoldsB'] = $params['CapacityOfHoldsB'] == "" ? null : $params['CapacityOfHoldsB'];
		    $shipData['HoldsDetail'] = $params['HoldsDetail'] == "" ? null : $params['HoldsDetail'];
		    $shipData['NumberOfHatchways'] = $params['NumberOfHatchways'] == "" ? null : $params['NumberOfHatchways'];
		    $shipData['SizeOfHatchways'] = $params['SizeOfHatchways'] == "" ? null : $params['SizeOfHatchways'];
		    $shipData['ContainerOnDeck'] = $params['ContainerOnDeck'] == "" ? null : $params['ContainerOnDeck'];
		    $shipData['ContainerInHold'] = $params['ContainerInHold'] == "" ? null : $params['ContainerInHold'];
		    $shipData['LiftingDevice'] = $params['LiftingDevice'] == "" ? null : $params['LiftingDevice'];
		    $shipData['TK_TOP'] = $params['TK_TOP'] == "" ? null : $params['TK_TOP'];
		    $shipData['ON_DECK'] = $params['ON_DECK'] == "" ? null : $params['ON_DECK'];
		    $shipData['H_COVER'] = $params['H_COVER'] == "" ? null : $params['H_COVER'];
		    $shipData->save();

		    if($freeId > 0)
			    $freeData = ShipFreeBoard::find($freeId);
		    else
			    $freeData = new ShipFreeBoard();

		    $freeData['shipId'] = $params['shipId'];
		    $freeData['ship_type'] = $params['ship_type'];
		    $freeData['new_ship'] = (isset($params['new_ship'])) ? ($params['new_ship'] == 'on' ? 1 : 0) : 0;
		    $freeData['new_free_tropical'] = $params['new_free_tropical'] == "" ? null : $params['new_free_tropical'];
		    $freeData['new_load_tropical'] = $params['new_load_tropical'] == "" ? null : $params['new_load_tropical'];
		    $freeData['new_free_summer'] = $params['new_free_summer'] == "" ? null : $params['new_free_summer'];
		    $freeData['new_free_winter'] = $params['new_free_winter'] == "" ? null : $params['new_free_winter'];
		    $freeData['new_load_winter'] = $params['new_load_winter'] == "" ? null : $params['new_load_winter'];
		    $freeData['new_free_winteratlantic'] = $params['new_free_winteratlantic'] == "" ? null : $params['new_free_winteratlantic'];
		    $freeData['new_load_winteratlantic'] = $params['new_load_winteratlantic'] == "" ? null : $params['new_load_winteratlantic'];
		    $freeData['new_free_fw'] = $params['new_free_fw'] == "" ? null : $params['new_free_fw'];
		    $freeData['timber'] = isset($params['timber']) ? ($params['timber'] == 'on' ? 1 : 0) : 0;
		    $freeData['timber_free_tropical'] = isset($params['timber_free_tropical']) ? ($params['timber_free_tropical'] == "" ? null : $params['timber_free_tropical']) : null;
		    $freeData['timber_load_tropical'] = isset($params['timber_load_tropical']) ? ($params['timber_load_tropical'] == "" ? null : $params['timber_load_tropical']) : null;
		    $freeData['timber_free_summer'] = isset($params['timber_free_summer']) ? ($params['timber_free_summer'] == "" ? null : $params['timber_free_summer']) : null;
		    $freeData['timber_load_summer'] = isset($params['timber_load_summer']) ? ($params['timber_load_summer'] == "" ? null : $params['timber_load_summer']) : null;
		    $freeData['timber_free_winter'] = isset($params['timber_free_winter']) ? ($params['timber_free_winter'] == "" ? null : $params['timber_free_winter']) : null;
		    $freeData['timber_load_winter'] = isset($params['timber_load_winter']) ? ($params['timber_load_winter'] == "" ? null : $params['timber_load_winter']) : null;
		    $freeData['timber_free_winteratlantic'] = isset($params['timber_free_winteratlantic']) ? ($params['timber_free_winteratlantic'] == "" ? null : $params['timber_free_winteratlantic']) : null;
		    $freeData['timber_load_winteratlantic'] = isset($params['timber_load_winteratlantic']) ? ($params['timber_load_winteratlantic'] == "" ? null : $params['timber_load_winteratlantic']) : null;
		    $freeData['timber_free_fw'] = isset($params['timber_free_fw']) ? ($params['timber_free_fw'] == "" ? null : $params['timber_free_fw']) : null;
		    $freeData['deck_line_amount'] = isset($params['deck_line_amount']) ? ($params['deck_line_amount'] == "" ? null : $params['deck_line_amount']) : null;
		    $freeData['deck_line_content'] = isset($params['deck_line_content']) ? $params['deck_line_content'] : null;
		    $freeData->save();

    		return true;
//	    } catch (\Exception $exception) {
//    		return false;
//	    }
    }

	public function saveShipMachineryData($params, $shipId) {
    	//try {
		    if($shipId > 0) {
			    $shipData = ShipRegister::find($shipId);
		    } else {
			    $shipData = new ShipRegister();
		    }
//dump($shipData);die;
		    $shipData['No_TypeOfEngine'] = $params['No_TypeOfEngine'];
		    $shipData['Cylinder_Bore_Stroke'] = $params['Cylinder_Bore_Stroke'];
		    $shipData['Power'] = $params['Power'];
		    $shipData['rpm'] = $params['rpm'];
		    $shipData['EngineManufacturer'] = $params['EngineManufacturer'];
		    $shipData['AddressEngMaker'] = $params['AddressEngMaker'];
		    $shipData['EngineDate'] = $params['EngineDate'];
		    $shipData['Speed'] = $params['Speed'] == null ? 0 : $params['Speed'];
		    $shipData['PrimeMover'] = $params['PrimeMover'];
		    $shipData['GeneratorOutput'] = $params['GeneratorOutput'];
		    $shipData['Boiler'] = $params['Boiler'];
		    $shipData['BoilerPressure'] = $params['BoilerPressure'];
		    $shipData['BoilerManufacturer'] = $params['BoilerManufacturer'];
		    $shipData['AddressBoilerMaker'] = $params['AddressBoilerMaker'];
		    $shipData['BoilerDate'] = $params['BoilerDate'];
		    $shipData['FOSailCons_S'] = $params['FOSailCons_S'] == null ? 0 : $params['FOSailCons_S'];
		    $shipData['FOL/DCons_S'] = $params['FOL/DCons_S'] == null ? 0 : $params['FOL/DCons_S'];
		    $shipData['FOIdleCons_S'] = $params['FOIdleCons_S'] == null ? 0 : $params['FOIdleCons_S'];
		    $shipData['DOSailCons_S'] = $params['DOSailCons_S'] == null ? 0 : $params['DOSailCons_S'];
		    $shipData['DOL/DCons_S'] = $params['DOL/DCons_S'] == null ? 0 : $params['DOL/DCons_S'];
		    $shipData['DOIdleCons_S'] = $params['DOIdleCons_S'] == null ? 0 : $params['DOIdleCons_S'];
		    $shipData['LOSailCons_S'] = $params['LOSailCons_S'] == null ? 0 : $params['LOSailCons_S'];
		    $shipData['LOL/DCons_S'] = $params['LOL/DCons_S'] == null ? 0 : $params['LOL/DCons_S'];
		    $shipData['LOIdleCons_S'] = $params['LOIdleCons_S'] == null ? 0 : $params['LOIdleCons_S'];
		    $shipData['FOSailCons_W'] = $params['FOSailCons_W'] == null ? 0 : $params['FOSailCons_W'];
		    $shipData['FOL/DCons_W'] = $params['FOL/DCons_W'] == null ? 0 : $params['FOL/DCons_W'];
		    $shipData['FOIdleCons_W'] = $params['FOIdleCons_W'] == null ? 0 : $params['FOIdleCons_W'];
		    $shipData['DOSailCons_W'] = $params['DOSailCons_W'] == null ? 0 : $params['DOSailCons_W'];
		    $shipData['DOL/DCons_W'] = $params['DOL/DCons_W'] == null ? 0 : $params['DOL/DCons_W'];
		    $shipData['DOIdleCons_W'] = $params['DOIdleCons_W'] == null ? 0 : $params['DOIdleCons_W'];
		    $shipData['LOSailCons_W'] = $params['LOSailCons_W'] == null ? 0 : $params['LOSailCons_W'];
		    $shipData['LOL/DCons_W'] = $params['LOL/DCons_W'] == null ? 0 : $params['LOL/DCons_W'];
		    $shipData['LOIdleCons_W'] = $params['LOIdleCons_W'] == null ? 0 : $params['LOIdleCons_W'];
		    $shipData->save();

		    return true;
	    //} catch (\Exception $exception) {
    	//	return false;
	    //}
	}

    public function saveShipRemarksData($params, $shipId) {
    	//try {
		    if($shipId > 0) {
			    $shipData = ShipRegister::find($shipId);
		    } else {
			    $shipData = new ShipRegister();
		    }

		    $shipData['Remarks'] = $params['Remarks'];
		    $shipData->save();

		    return true;
	    //} catch(\Exception $exception) {
    	//	return false;
	    //}
    }



    //배삭제
    public function deleteShipData(Request $request)
    {
        $dataId = $request->get('dataId');
        $shipData = ShipRegister::find($dataId);
        if(is_null($shipData)) {
            return -1;
        } else {
            $shipData->delete();
        }

        return 1;
    }

    public function loadShipTypePage() {
        $list = ShipType::all();
        return view('shipManage.ship_type', ['list'=>$list]);
    }

    public function loadShipTypeData() {
        $list = ShipType::all();
        return response()->json($list);
    }

    public function loadShipTypeModifyPage(Request $request) {
        $typeId = $request->get('typeId') * 1;
        if($typeId > 0)
            $type = ShipType::find($typeId);
        else
            $type = new ShipType();

        return view('shipManage.ship_type_setting', ['type'=>$type]);

    }

    public function dynamicList(Request $request) {
        $params = $request->all();
        $shipName = '';
		if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else {
            $firstShipInfo = ShipRegister::first();
            if($firstShipInfo == null && $firstShipInfo == false)
                return redirect()->back();

            $shipId = $firstShipInfo->IMO_No;
        }

        $shipInfo = ShipRegister::where('IMO_No', $shipId)->first();
        if($shipInfo == null || $shipInfo == false)
            return redirect()->back();
        else {
            $shipName = $shipInfo->shipName_En;
        }

        $shipList = ShipRegister::all();
        return view('shipManage.dynamic_list', [
            'shipList'          => $shipList,
            'shipInfo'          => $shipInfo,
            'shipId'            => $shipId,
            'shipName'          => $shipName,
        ]);
    }


    public function ctmAnalytics(Request $request) {
        $shipRegList = ShipRegister::all();

        $params = $request->all();
        $shipId = $request->get('shipId'); 
	    $shipNameInfo = null;
        if(isset($shipId)) {
	        $shipNameInfo = ShipRegister::where('IMO_No', $shipId)->first();
        } else {
	        $shipNameInfo = ShipRegister::first();
	        $shipId = $shipNameInfo['IMO_No'];
        }

        $ctmTbl = new Ctm();
        $yearList = $ctmTbl->getYearList($shipId);

        if(isset($params['year']) && $params['year'] != '')
            $activeYear = $params['year'];
        else {
            $activeYear = $yearList[0];
        }

        if(isset($params['type']) && $params['type'] != '')
            $type = $params['type'];
        else {
            $type = 'total';
        }

        return view('shipManage.ctm_analytics', [
        	    'shipList'      =>  $shipRegList,
                'shipName'      =>  $shipNameInfo,
                'shipId'        =>  $shipId,
                'yearList'      =>  $yearList,

                'activeYear'    =>  $activeYear,
                'type'          =>  $type,
            ]);
    }

    public function shipDataTabPage(Request $request) {
        $shipId = $request->get('shipId');
        $tabName = $request->get('tabName');

        if(is_null($shipId))
            $shipId = 0;

        $shipInfo = ShipRegister::find($shipId);
        if($tabName == '#general') {
            $shipList = Ship::all();
            $shipType = ShipType::all();
            return view('shipManage.tab_general', ['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]);
        } else if($tabName == '#hull') {
            $freeBoard = ShipFreeBoard::where('shipId', $shipId)->first();
            return view('shipManage.tab_hull', ['shipInfo'=>$shipInfo, 'freeBoard' => $freeBoard]);
        } else if($tabName == '#machiery') {
            return view('shipManage.tab_machinery', ['shipInfo'=>$shipInfo]);
        } else if($tabName == '#remarks') {
            return view('shipManage.tab_remarks', ['shipInfo'=>$shipInfo]);
        }
    }

    public function saveShipSafetyData(Request $request) {
        $posId = $request->get('id');
        $shipId = $request->get('shipId');
        $ship = ShipRegister::find($shipId);
        $shipRegNo = $ship['RegNo'];

        if(isset($posId)){
            $position = ShipPosReg::find($posId);
        } else {
            $position = new ShipPosReg();
            $position['RegNo'] = $shipRegNo;
        }

        $position['DutyID'] = $request->get('DutyID');
        $isExist = ShipPosReg::where('RegNo', $shipRegNo)->where('DutyID', $position['DutyID'])->first();
        if(isset($isExist) && ($isExist['id'] != $posId))
            return -1;

        $position['Priority'] = $request->get('Priority');
        $position['STCWRegCodeID'] = $request->get('STCWRegCodeID');
        $position['PersonNum'] = $request->get('PersonNum');
        $position->save();
        $last = ShipPosReg::all(['id'])->last();
        return $last['id'];
    }

    public function deleteShipSafetyData(Request $request) {
        $posId = $request->get('posId');
        $position = ShipPosReg::find($posId);
        if(is_null($position))
            return -1;

        $position->delete();
        return 1;
    }

    public function uploadShipPicture(Request $request) {
        $file = $request->file('photo');
        $shipId = $request->get('shipId');
        $ship = ShipRegister::find($shipId);
        $shipRegNo = $ship['RegNo'];

        $imagePath = '';
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $imagePath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/ship'), $imagePath);
        }

        $photo = new ShipPhoto();
        $photo['RegNo'] = $shipRegNo;
        $photo['path'] = $imagePath;
        $photo->save();

        $imageList = ShipPhoto::where('RegNo', $shipRegNo)->get();
        return view('shipManage.tab_photo', ['imageList'=>$imageList]);
    }

    public function deleteShipPhotoImage(Request $request) {
        $imageId = $request->get('imageId');
        $shipId = $request->get('shipId');
        $ship = ShipRegister::find($shipId);
        $shipRegNo = $ship['RegNo'];

        $photo = ShipPhoto::find($imageId);
        if($photo)
            $photo->delete();

        $imageList = ShipPhoto::where('RegNo', $shipRegNo)->get();
        return view('shipManage.tab_photo', ['imageList'=>$imageList]);
    }

    //배증서목록
    public function shipCertList(Request $request) {
        $shipRegList = ShipRegister::all();

        $shipId = $request->get('id'); 
	    $shipNameInfo = null;
        if(isset($shipId)) {
	        $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);
	        $shipNameInfo = ShipRegister::find($shipId);
        } else {
	        $shipNameInfo = ShipRegister::first();
	        $shipId = $shipNameInfo['IMO_No'];
        }

        $certType = ShipCertList::all();
        $certList = ShipCertRegistry::where('ship_id', $shipId)->get();

        return view('shipManage.ship_cert_registry', [
        	    'shipList'  =>  $shipRegList,
                'shipName'  =>  $shipNameInfo,
                'shipId'    =>  $shipId,
            ]);
    }

    public function saveShipCertList(Request $request) {
    	$params = $request->all();

    	if(!isset($params['id']))
    		return redirect()->back();

    	$ids = $params['id'];
    	foreach($ids as $key => $item) {
    		if(!isset($params['cert_id'][$key]) || $params['cert_id'][$key] == '') continue;
    		$shipCertTbl = new ShipCertRegistry();
    		if($item != '' && $item > 0) {
			    $shipCertTbl = ShipCertRegistry::find($item);
		    }

		    $shipCertTbl['ship_id']     = $params['ship_id'];
		    $shipCertTbl['cert_id']     = isset($params['cert_id'][$key]) ? $params['cert_id'][$key] : 1;
			if(isset($params['issue_date'][$key]) && $params['issue_date'][$key] != '' && $params['issue_date'][$key] != EMPTY_DATE)
			    $shipCertTbl['issue_date']  = $params['issue_date'][$key];

			if(isset($params['expire_date'][$key]) && $params['expire_date'][$key] != '' && $params['expire_date'][$key] != EMPTY_DATE)
			    $shipCertTbl['expire_date'] = isset($params['expire_date'][$key]) ? $params['expire_date'][$key] : null;

			if(isset($params['due_endorse'][$key]) && $params['due_endorse'][$key] != '' && $params['due_endorse'][$key] != EMPTY_DATE)
			    $shipCertTbl['due_endorse'] = isset($params['due_endorse'][$key]) ? $params['due_endorse'][$key] : null;

		    $shipCertTbl['issuer']      = isset($params['issuer'][$key]) ? $params['issuer'][$key] : '';
		    $shipCertTbl['remark']      = isset($params['remark'][$key]) ? $params['remark'][$key] : '';

		    // Attachment Upload
		    if($params['is_update'][$key] == IS_FILE_UPDATE) {
			    if($request->hasFile('attachment')) {
			    	$file = $request->file('attachment')[$key];
				    $fileName = $file->getClientOriginalName();
				    $name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
				    $file->move(public_path() . '/shipCertList/', $name);
					if($shipCertTbl['attachment'] != '' && $shipCertTbl['attachment'] != null) {
						if(file_exists($shipCertTbl['attachment']))
							@unlink($shipCertTbl['attachment']);
					}

				    $shipCertTbl['attachment'] = public_path('/shipCertList/') . $name;
				    $shipCertTbl['attachment_link'] = url() . '/shipCertList/' . $name;
				    $shipCertTbl['file_name'] = $fileName;
			    }
		    }

		    $shipCertTbl->save();
	    }


	    $shipRegList = ShipRegister::all();
	    $shipId = $params['ship_id'];

	    $shipNameInfo = null;
	    if(isset($shipId))
		    $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);
	    else {
		    $shipNameInfo = ShipRegister::first();
		    $shipId = $shipNameInfo['id'];
	    }

	    $certType = ShipCertList::all();
	    $certList = ShipCertRegistry::where('ship_id', $shipId)->get();

	    return redirect('shipManage/shipCertList?id=' . $shipId);
    }

	public function saveShipCertType(Request $request) {
		$params = $request->all();

		$cert_ids = $params['id'];
		foreach($cert_ids as $key => $item) {
			$certTbl = new ShipCertList();
			if($item != '' && $item != null) {
				$certTbl = ShipCertList::find($item);
			}

			if($params['order_no'][$key] != '' && $params['code'][$key] != '' && $params['name'][$key] != "") {
				$certTbl['order_no'] = $params['order_no'][$key];
				$certTbl['code'] = $params['code'][$key];
				$certTbl['name'] = $params['name'][$key];

				$certTbl->save();
			}
		}

		$retVal = ShipCertList::all();

		return response()->json($retVal);
	}

    // 배증서 정보얻기
    public function getShipCertInfo(Request $request) {
        $shipId = $request->get('shipId');
        $certId = $request->get('certId') * 1;

        if($certId == 0) {
            $certInfo = new ShipCertRegistry();
            $certInfo['ShipName'] = $shipId;
        } else {
            $certInfo = ShipCertRegistry::find($certId);
        }

        if(!empty($certInfo)) {
            $cert = ShipCertList::where('CertNo', $certInfo['CertNo'])->first();
            $certInfo['CertName_Cn'] = $cert['CertName_Cn'];
        }
        $certType = ShipCertList::query()->orderBy('CertNo')->get();

        return view('shipManage.ship_cert_modify', ['info'=>$certInfo, 'certList'=>$certType]);
    }

    // 배증서 添加 및 수정
    public function updateCertInfo(Request $request) {
        $certId = $request->get('id');
        $shipName = $request->get('ShipName');
        $certName = $request->get('certName');
        $issuUnit = $request->get('issuUnit');
        $expireMonth = $request->get('expireMonth');

        $file = $request->file('copy-photo');
        $photoPath = '';

        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $photoPath = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/ship-cert'), $photoPath);
        }


        if(empty($certId)) {
            $certInfo = new ShipCertRegistry();
            $shipInfo = ShipRegister::where('RegNo', $shipName)->first();
            $certInfo['ShipName'] = $shipInfo['RegNo'];
        } else {
            $certInfo = ShipCertRegistry::find($certId);
        }

        $certInfo['CertNo'] = $request->get('CertNo');
        $certInfo['IssuedAdmin_Cn'] = $request->get('IssuedAdmin_Cn');
        $certInfo['IssuedAdmin_En'] = $request->get('IssuedAdmin_En');
        $certInfo['CertLevel'] = $request->get('CertLevel');
        $certInfo['IssuedDate'] = $request->get('IssuedDate');
        if(empty($request->get('IssuedDate')))
            $certInfo['IssuedDate'] = null;
        $certInfo['ExpiredDate'] = $request->get('ExpiredDate');
        if(empty($request->get('ExpiredDate')))
            $certInfo['ExpiredDate'] = null;

        $certInfo['Remark'] = $request->get('Remark');
        if(!empty($photoPath))
            $certInfo['Scan'] = $photoPath;

        $certInfo->save();


        return redirect('shipManage/shipCertList?shipId='.$shipName.'&certName='.$certName.'&issuUnit='.$issuUnit.'&expireMonth='.$expireMonth);
    }

    public function deleteShipCert(Request $request) {
        $certId = $request->get('certId');
        $certInfo = ShipCertRegistry::find($certId);
        if(is_null($certInfo))
            return -1;

        $certInfo->delete();
        return 1;
    }

    //증서종류관리
    public function shipCertManage(Request $request) {
	    $shipRegList = ShipRegister::all();

	    $shipId = $request->get('id');
	    $shipNameInfo = null;
	    if(isset($shipId)) {
		    $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);
		    $shipNameInfo = ShipRegister::find($shipId);
	    } else {
		    $shipNameInfo = ShipRegister::first();
		    $shipId = $shipNameInfo['IMO_No'];
	    }

	    return view('shipManage.ship_cert_list', [
		    'shipList'  =>  $shipRegList,
		    'shipName'  =>  $shipNameInfo,
		    'shipId'    =>  $shipId,
	    ]);
    }

	public function shipCertExcel(Request $request) {
		$shipId = $request->get('id');
		$shipName = '';
		if(isset($shipId)) {
			$retVal = ShipCertRegistry::where('ship_id', $shipId)->get();
			$shipName = ShipRegister::where('IMO_No', $shipId)->first()->shipName_En;
		} else {
			return redirect()->back();
		}

		$certTypeList = ShipCertList::all();
		foreach($retVal as $key => $item) {
			foreach($certTypeList as $cert) {
				if($item->cert_id == $cert->id) {
					$retVal[$key]->order_no = $cert->order_no;
					$retVal[$key]->code = $cert->code;
					$retVal[$key]->cert_name = $cert->name;
					break;
				}
			}
		}

		return view('shipManage.ship_cert_list_excel', [
			'list'          =>  $retVal,
			'shipName'      =>  $shipName,
			'certList'      =>  $certTypeList,
			'excel_name'    => $shipName . '_船舶证书_' . date('Ymd'),
		]);
	}

    public function getCertType(Request $request) {
        $certId = $request->get('certId') * 1;

        if($certId == 0) {
            $certInfo = new ShipCertList();
        } else {
            $certInfo = ShipCertList::find($certId);
        }

        return view('shipManage.cert_modify', ['info'=>$certInfo]);
    }

    // 증서 添加 및 수정
    public function updateCertType(Request $request) {
        $certId = $request->get('id');
        $cert = $request->get('cert');
        if(empty($certId))
            $certInfo = new ShipCertList();
        else
            $certInfo = ShipCertList::find($certId);

        $certInfo['CertNo'] = $request->get('CertNo');
        $certInfo['CertName_Cn'] = $request->get('CertName_Cn');
        $certInfo['CertName_En'] = $request->get('CertName_En');

        $isExist = ShipCertList::where('CertNo', $certInfo['CertNo'])->orWhere('CertName_Cn', $certInfo['CertName_Cn'])->orWhere('CertName_En', $certInfo['CertName_En'])->first();

        if(isset($isExist) && ($isExist['id'] != $certId)) {
            $error = "错误!  做成的船舶证书已经登记了。";
            return back()->with(['error' => $error]);
        }
        $certInfo['CertKind'] = $request->get('CertKind');
        $certInfo['Details'] = $request->get('Details');
        $certInfo->save();

        return redirect('shipManage/shipCertManage?cert='.$cert);
    }

    // 증서종류삭제
    public function deleteShipCertType(Request $request) {
        $certId = $request->get('certId');
        $certInfo = ShipCertList::find($certId);
        if(is_null($certInfo))
            return -1;

        $certInfo->delete();
        return 1;
    }

    // 배설비관리
    public function shipEquipmentManage(Request $request) {
        Util::getMenuInfo($request);

        $shipRegList = ShipRegister::getShipListByOrigin();
        $shipId = $request->get('shipId');

        $mainKind = ShipEquipmentMainKind::all();
	    $kindList = array();
	    foreach($mainKind as $key => $item)
		    $kindList[$item['id']] = $item['Kind_Cn'];

        foreach($mainKind as $kind) {
            $subKinds = ShipEquipmentSubKind::subKindByShip($shipId, $kind['id']);
            $kind['subKind'] = $subKinds;
        }

        $allMainKind = ShipEquipmentMainKind::all();
        foreach($allMainKind as $kind) {
            $subKinds = ShipEquipmentSubKind::where('Kind', $kind['id'])->get();
            $kind['subKind'] = $subKinds;
        }

        $primaryShipId = null;
        if(isset($shipRegList) && count($shipRegList) > 0)
        	$primaryShipId = $shipRegList[0]->ShipRegNo;

        $registeredList = null;
        $diligenceList = null;
        
        $list = array();
	    if(isset($shipRegList) && count($shipRegList) > 0) {
		    if(empty($shipId)) {
				$shipId = $shipRegList[0]->RegNo;
		    }
		    $list = ShipEquipment::select('tb_ship_equipment_diligence.IssaCodeNo', 'tb_ship_equipment_diligence.remain_count', 'tb_ship_equipment_diligence.diligence_at', 'tb_ship_equipment_diligence.status as Status', 'tb_ship_equipment.*')
			    ->leftJoin('tb_ship_equipment_diligence', 'tb_ship_equipment_diligence.IssaCodeNo', '=', 'tb_ship_equipment.IssaCodeNo')
			    ->where('tb_ship_equipment.ShipRegNo', $shipId)
			    ->where('tb_ship_equipment.supplied_at', '!=', "")
			    ->orderBy('tb_ship_equipment.supplied_at', 'desc')
			    ->orderBy('tb_ship_equipment.create_at', 'desc')
			    ->get();

		    $registeredList = ShipEquipment::select('tb_ship_equipment.*')
			    ->where('tb_ship_equipment.ShipRegNo', $shipId)
			    ->where('tb_ship_equipment.supplied_at', '=', null)
			    ->orWhere('tb_ship_equipment.supplied_at', 'like', "%0000-00-00%")
			    ->orderBy('tb_ship_equipment.supplied_at', 'desc')
			    ->orderBy('tb_ship_equipment.create_at', 'desc')
			    ->get();

		    $diligenceList = ShipDiligence::select('tb_ship_equipment_diligence.*')
			    ->where('tb_ship_equipment_diligence.ShipRegNo', $shipId)
			    ->orderBy('tb_ship_equipment_diligence.diligence_at', 'desc')
			    ->orderBy('tb_ship_equipment_diligence.create_at', 'desc')
			    ->get();
	    }

	    $shipNameInfo = null;
	    if(!empty($shipId))
		    $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);

	    $unitList = ShipEquipmentUNits::all();

        $status = Session::get('status');
        
        return view('shipManage.ship_equipment_manage',
            [   'shipName'      =>  $shipNameInfo,
                'shipList'      =>  $shipRegList,
                'allKind'       =>  $allMainKind,
                'kindList'      =>  $mainKind,
                'kindLabelList' =>  $kindList,
                'shipId'        =>  $shipId,

                'unitList'      => $unitList,

                'list'          =>  $list,
                'registeredList'    => $registeredList,
	            'diligenceList'     => $diligenceList,

                'shipID'        =>  $shipId,
                'kindId'        =>  null,
                'status'        =>  $status
            ]);
    }

    public function shipEquepmentByKind(Request $request) {
        $kindId = $request->get('kindId');
        $shipId = $request->get('shipId');
        $equipmentName = $request->get('keyword');
	    $params = json_decode($request['params'], true);

		$shipEquipTbl = new ShipEquipment();
		$equipmentList = $shipEquipTbl->getShipEquipList($shipId, $params);

		foreach($equipmentList as $key => $value) {
			$equipmentList[$key] = _objectToArray($value);
		}

		$kindList = array();
	    $mainKind = ShipEquipmentMainKind::all();
		foreach($mainKind as $key => $item)
			$kindList[$item['id']] = $item['Kind_Cn'];

        return view('shipManage.ship_equipment_table', [
        	'list'          =>  $equipmentList,
	        'shipId'        =>  $shipId,
	        'kindLabelList' =>  $kindList
        ]);
    }

    public function appendNewShipEquipment(Request $request) {
//        $equipId = $request->get('equipId');
        $mainKind = $request->get('mainKind');
        $id = $request->get('id');
        $subKind = 1;
        $name_cn = $request->get('Euipment_Cn');
        $name_en = $request->get('Euipment_En');
        $shipId = $request->get('shipId');

        if (isset($id))
            $equipment = ShipEquipment::find($id);
        else
            $equipment = new ShipEquipment();

        $equipment['KindOfEuipmentId'] = $mainKind;
	    $equipment['ShipRegNo'] = $shipId;
//        $equipment['PIC'] = $request->get('PIC');
        $equipment['Euipment_Cn'] = $request->get('Euipment_Cn');
        $equipment['Euipment_En'] = $request->get('Euipment_En');
        $equipment['Label'] = $request->get('Label');
        $equipment['Type'] = $request->get('Type');
        $equipment['SN'] = $request->get('SN');
        $equipment['IssaCodeNo'] = $request->get('IssaCodeNo');
        $equipment['Qty'] = $request->get('Qty');
        $equipment['Unit'] = $request->get('Unit');
//        $equipment['ManufactureDate'] = $request->get('ManufactureDate');
        $equipment['Remark'] = $request->get('Remark');
        $equipment->save();

        if(!isset($equipId))
            return redirect('shipManage/shipEquipmentManage?shipId='.$shipId);
        else {
            $last = ShipEquipment::all()->last('id');
            $lastId = $last['id'];
            return redirect('shipManage/getEquipmentDetail?equipId='.$lastId);
        }
    }

	public function appendNewShipDiligenceEquipment(Request $request) {
		$mainKind = $request->get('mainKind');
		$subKind = 1;
		$name_cn = $request->get('Euipment_Cn');
		$name_en = $request->get('Euipment_En');
		$shipId = $request->get('shipId');
		$id = $request->get('id');

		if (isset($id))
			$equipment = ShipDiligence::find($id);
		else
			$equipment = new ShipDiligence();

		$equipment['KindOfEuipmentId'] = $mainKind;
		$equipment['ShipRegNo'] = $shipId;
        $equipment['remain_count'] = $request->get('remain_count');
		$equipment['Euipment_Cn'] = $request->get('Euipment_Cn');
		$equipment['Euipment_En'] = $request->get('Euipment_En');
		$equipment['Label'] = $request->get('Label');
		$equipment['Type'] = $request->get('Type');
		$equipment['SN'] = $request->get('SN');
		$equipment['IssaCodeNo'] = $request->get('IssaCodeNo');
		$equipment['remain_count'] = $request->get('remain_count');
		$equipment['Unit'] = $request->get('Unit');
		$equipment['Status'] = $request->get('Status');
        $equipment['diligence_at'] = $request->get('diligenceDate');
		$equipment['Remark'] = $request->get('Remark');
		$equipment->save();

		if(!isset($equipId))
			return redirect('shipManage/shipEquipmentManage?shipId='.$shipId);
		else {
			$last = ShipEquipment::all()->last('id');
			$lastId = $last['id'];
			return redirect('shipManage/getEquipmentDetail?equipId='.$lastId);
		}
	}

    public function deleteShipEquipment(Request $request) {
        $deviceId = $request->get('deviceId');
        $type = $request->get('type');
        if($type == 'supply')
            $device = ShipEquipment::find($deviceId);
        else
	        $device = ShipDiligence::find($deviceId);

        if(is_null($device))
            return -1;

        $device->delete();
        return $deviceId;
    }

    public function shipSubEquipemntList(Request $request) {
        $kindId = $request->get('mainKind');
        $subKinds = ShipEquipmentSubKind::where('Kind', $kindId)->orderBy('id')->get();

        $optionHtml = '';
        foreach($subKinds as $kind) {
            $optionHtml .= '<option value="'.$kind['id'].'">'.$kind['GroupOfEuipment_Cn'].'</option>';
        }

        return $optionHtml;
    }

    public function getEquipmentDetail(Request $request) {

        $GLOBALS['selMenu'] = 54;  // 설비登记메뉴표
        $GLOBALS['submenu'] = 107; // 배별 설비목록메뉴표

        $deviceId = $request->get('equipId');
        $device = ShipEquipment::find($deviceId);

        $parts = ShipEquipmentPart::loadEquipmentParts($deviceId);
        $partPaginate = Util::makePaginateHtml(0,0,$parts);
        $propertys = ShipEquipmentProperty::where('EuipmentID', $deviceId)->paginate(100)->setPath('');
        $propertyPaginate = Util::makePaginateHtml(0,0,$propertys);
        $units = EquipmentUnit::all();

        $shipId = $request->get('shipId');
        if(isset($shipId)) {
            $shipName = ShipRegister::getShipFullNameByRegNo($shipId);
        }

        $kindInfo = ShipEquipmentRegKind::find($device['KindOfEuipmentId']);

        $allMainKind = ShipEquipmentMainKind::all();
        $allSubKind = ShipEquipmentSubKind::select('id', 'GroupOfEuipment_Cn')
                                ->where('Kind', $kindInfo['KindId'])
                                ->get();
        $list = ShipIssaCodeNo::getAllItems('','','','');


        if($request->ajax())
            return view('shipManage.ship_equipment_detail',
                [   'deviceId'      =>  $deviceId,
                    'parts'         =>  $parts,
                    'propertys'     =>  $propertys,
                    'deviceName'    =>  $device['Euipment_Cn'],
                    'partPaginate'  =>  $partPaginate,
                    'propertyPaginate'=>$propertyPaginate
                ]);
        else
            return view('shipManage.ship_equipment_modify',
                [   'parts'         =>  $parts,
                    'propertys'     =>  $propertys,
                    'kindInfo'      =>  $kindInfo,
                    'mainKinds'     =>  $allMainKind,
                    'subKinds'      =>  $allSubKind,
                    'device'        =>  $device,
                    'shipId'        =>  $shipId,
                    'shipName'      =>  $shipName,
                    'units'         =>  $units,
                    'partPaginate'  =>  $partPaginate,
                    'propertyPaginate'=>$propertyPaginate,
                    'list'          =>  $list
                ]);
    }

    public function getSupplyHistory(Request $request) {
    	$params = $request->all();
		$shipId = $params['shipId'];
	    $equipName = $params['equipName'];
		$issa_code = $params['issa_code'];

	    $supplyList = ShipEquipment::select('tb_ship_equipment.*')
		    ->where('tb_ship_equipment.ShipRegNo', $shipId)
		    ->where('tb_ship_equipment.IssaCodeNo', $issa_code)
		    ->where('tb_ship_equipment.supplied_at', '!=', "")
		    ->orderBy('tb_ship_equipment.supplied_at', 'desc')
		    ->orderBy('tb_ship_equipment.create_at', 'desc')
		    ->get();

	    $diligenceList = ShipDiligence::select('tb_ship_equipment_diligence.*')
		    ->where('tb_ship_equipment_diligence.ShipRegNo', $shipId)
		    ->where('tb_ship_equipment_diligence.IssaCodeNo', $issa_code)
		    ->orderBy('tb_ship_equipment_diligence.diligence_at', 'desc')
		    ->orderBy('tb_ship_equipment_diligence.create_at', 'desc')
		    ->get();

	    $mainKind = ShipEquipmentMainKind::all();
	    foreach($mainKind as $key => $item)
		    $kindList[$item['id']] = $item['Kind_Cn'];

	    return view('shipManage.ship_equipment_detail',
		    [   'equipName'         => $equipName,
			    'registeredList'    => $supplyList,
			    'diligenceList'     => $diligenceList,
			    'kindLabelList'     => $kindList,
			    'shipId'            => $shipId
		    ]);
    }

    public function getDiligenceDetail(Request $request) {
    	$params = $request->all();
    	if(!isset($params['equipId'])) {
    		return redirect()->back();
	    } else {
    		$device = ShipDiligence::find($params['equipId']);
		    $allMainKind = ShipEquipmentMainKind::all();
		    $shipName = ShipRegister::getShipFullNameByRegNo($params['shipId']);
		    return view('shipManage.ship_diligence_equipment_modify',
			    [   'mainKinds'     =>  $allMainKind,
				    'device'        =>  $device,
				    'shipId'        =>  $params['shipId'],
				    'shipName'      =>  $shipName,
//				    'units'         =>  $units,
//				    'partPaginate'  =>  $partPaginate,
//				    'propertyPaginate'=>$propertyPaginate,
//				    'list'          =>  $list
			    ]);
	    }
    }

    public function propertyTableEquipmentByDeviceID(Request $request) {

        $deviceId = $request->get('equipId');

        $propertys = ShipEquipmentProperty::where('EuipmentID', $deviceId)->paginate(5)->setPath('');
        $propertyPaginate = Util::makePaginateHtml(0,0, $propertys);

        return view('shipManage.equipment_property_table',
            [   'deviceId'      =>  $deviceId,
                'propertys'     =>  $propertys,
                'propertyPaginate'=>$propertyPaginate
            ]);
    }

    public function partTableEquipmentByDeviceID(Request $request) {

        $deviceId = $request->get('equipId');

        $parts = ShipEquipmentPart::loadEquipmentParts($deviceId);
        $partPaginate = Util::makePaginateHtml(0,0,$parts);

        return view('shipManage.equipment_part_table',
            [   'deviceId'      =>  $deviceId,
                'parts'         =>  $parts,
                'partPaginate'  =>  $partPaginate,
            ]);
    }

    public function propertyTabEquipmentByDeviceID(Request $request) {

        $deviceId = $request->get('equipId');

        $propertys = ShipEquipmentProperty::where('EuipmentID', $deviceId)->paginate(5)->setPath('');
        $propertyPaginate = Util::makePaginateHtml(0,0, $propertys);

        return view('shipManage.equipment_property_tab',
            [
                'propertys'     =>  $propertys,
                'propertyPaginate'=>$propertyPaginate
            ]);
    }

    public function partTabEquipmentByDeviceID(Request $request) {

        $deviceId = $request->get('equipId');

        $parts = ShipEquipmentPart::loadEquipmentParts($deviceId);
        $partPaginate = Util::makePaginateHtml(0, 0, $parts);
        $units = EquipmentUnit::all();

        return view('shipManage.equipment_part_tab',
            [
                'parts'         =>  $parts,
                'partPaginate'  =>  $partPaginate,
                'units'         =>  $units
            ]);
    }

    public function updateEquipmentProperty(Request $request) {
        $propertyId = $request->get('id');
        if(isset($propertyId))
            $property = ShipEquipmentProperty::find($propertyId);
        else {
            $property = new ShipEquipmentProperty();
            $property['EuipmentID'] = $request->get('equipId');
        }

        $property['Items_Cn'] = $request->get('Items_Cn');
        $property['Items_En'] = $request->get('Items_En');

        $isExist = ShipEquipmentProperty::where('Items_Cn', $property['Items_Cn'])->where('Items_En', $property['Items_En'])->first();
        if(isset($isExist) && ($propertyId != $isExist['id']))
            return -1;

        $property['Particular'] = $request->get('Particular');
        $property['Remark'] = $request->get('Remark');
        $property->save();
        return 1;
    }

    public function deleteEquipmentProperty(Request $request) {
        $propertyId = $request->get('propertyId');
        $property = ShipEquipmentProperty::find($propertyId);
        if(is_null($property))
            return -1;

        $property->delete();
        return 1;
    }

    public function updateEquipmentPart(Request $request) {
        $partId = $request->get('id');
        if(isset($partId))
            $part = ShipEquipmentPart::find($partId);
        else {
            $part = new ShipEquipmentPart();
            $part['EuipmentID'] = $request->get('equipId');
        }

        $part['PartName_Cn'] = $request->get('PartName_Cn');
        $part['PartName_En'] = $request->get('PartName_En');

        $isExist = ShipEquipmentPart::where('PartName_Cn', $part['PartName_Cn'])->where('PartName_En', $part['PartName_En'])->first();
        if(isset($isExist) && ($partId != $isExist['id']))
            return -1;

        $part['Special'] = $request->get('Special');
        $part['PartNo'] = $request->get('PartNo');
        $part['IssaCodeNo'] = $request->get('IssaCodeNo');
        $part['Unit'] = $request->get('Unit');
        $part['Qtty'] = $request->get('Qtty');
        $part['Remark'] = $request->get('Remark');
        $part->save();
        $lastId = ShipEquipmentPart::all(['id'])->last();

        return $lastId['id'];
    }

    public function deleteEquipmentPart(Request $request) {
        $partId = $request->get('partId');
        $part = ShipEquipmentPart::find($partId);
        if(is_null($part))
            return -1;

        $part->delete();
        return 1;
    }



    // 배 설비종류관리
    public function equipmentTypeManage(Request $request) {
        Util::getMenuInfo($request);

        $mainKind = ShipEquipmentMainKind::all();
        $subKind = ShipEquipmentSubKind::all();
        foreach($subKind as $kind) {
            $kindId = $kind['Kind'];
            $kindInfo = ShipEquipmentMainKind::find($kindId);
            $kind['kind_name'] = $kindInfo['Kind_Cn'];
        }

	    $unitsList = ShipEquipmentUnits::all();

        return view('shipManage.equipment_type_manage', ['mainKind'=>$mainKind, 'subKind'=>$subKind, 'unitsList'    => $unitsList]);
    }

    public function updateEquipmentType(Request $request) {
        $typeId = $request->get('main_type_id') * 1;
        if($typeId == 0)
            $type = new ShipEquipmentMainKind();
        else
            $type = ShipEquipmentMainKind::find($typeId);

        $type['Kind_Cn'] = $request->get('type_name');
        $type['Kind_En'] = $request->get('type_name_en');
        $type['Remark'] = $request->get('type_descript');
        $type->save();

        return back();
    }

    public function UpdateMainEquipment(Request $request) {
        $equipId = $request->get('equip_id') * 1;
        if($equipId == 0)
            $equipment = new ShipEquipmentSubKind();
        else
            $equipment = ShipEquipmentSubKind::find($equipId);

        $equipment['GroupOfEuipment_Cn'] = $request->get('sub_name_Cn');
        $equipment['GroupOfEuipment_En'] = $request->get('sub_name_en');
        $equipment['Kind'] = $request->get('main_type');
        $remark = $request->get('sub_type_remark');
        if(empty($remark))
            $remark = null;
        $equipment['Remark'] = $remark;
        if(is_null($equipment['order'])) {
            $count = ShipEquipmentSubKind::where('Kind', $equipment['Kind'])->count() + 1;
            $countStr = sprintf("%02s", $count.'');
            $kindStr = sprintf("%02s", $equipment['Kind']);
            $equipment['order'] = $kindStr.'-'.$countStr;
        }

        $equipment->save();

        return back();
    }

	public function UpdateEquipmentUnits(Request $request) {
		$unitId = $request->get('unit_id') * 1;
		if($unitId == 0)
			$equipment = new ShipEquipmentUnits();
		else
			$equipment = ShipEquipmentUnits::find($unitId);

		$equipment['unit_cn'] = $request->get('unit_cn');
		$equipment['unit_en'] = $request->get('unit_en');
		$equipment['remark'] = $request->get('remark');
		$equipment->save();

		return back();
	}

    public function deleteEquipmentMainType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipEquipmentMainKind::find($typeId);
        if(is_null($type))
            return -1;
        $type->delete();
        return 1;
    }

    public function deleteEquipmentSubType(Request $request) {
        $kindId = $request->get('kindId');
        $kind = ShipEquipmentSubKind::find($kindId);
        if(is_null($kind))
            return -1;

        $kind->delete();
        return 1;
    }

	public function deleteEquipmentUnits(Request $request) {
		$unitId = $request->get('unit_id');
		$units = ShipEquipmentUNits::find($unitId);
		if(is_null($units))
			return -1;

		$units->delete();
		return 1;
	}

    public function shipISSACodeManage(Request $request) {
        Util::getMenuInfo($request);

        $code = $request->get('code');
        $codeNo = $request->get('codeNo');
        $content = $request->get('content');

        $list = ShipIssaCodeNo::getAllItems($code, $codeNo, $content, 10);

        if(isset($code))
            $list->appends(['code'=>$code]);
        if(isset($codeNo))
            $list->appends(['codeNo'=>$codeNo]);
        if(isset($content))
            $list->appends(['content'=>$content]);

        $codeList = ShipIssaCode::all();

        return view('shipManage.ship_issacode_manage', ['list'=>$list, 'codeList'=>$codeList, 'code'=>$code, 'codeNo'=>$codeNo, 'content'=>$content]);
    }

    public function updateIssaCode(Request $request) {
        $codeId = $request->get('codeId');
        if(empty($codeId))
            $code = new ShipIssaCodeNo();
        else
            $code = ShipIssaCodeNo::find($codeId);

        $code['Code'] = $request->get('sel_type');
        $code['CodeNo'] = $request->get('CodeNo');
        $code['Content_Cn'] = $request->get('Content_Cn');
        $code['Content_En'] = $request->get('Content_En');
        $code['Capacity'] = $request->get('Capacity');
        $code->save();

        return back();
    }

    public function deleteIssaCode(Request $request) {
        $codeId = $request->get('codeId');
        $code = ShipIssaCodeNo::find($codeId);
        if(is_null($code)) {
            return -1;
        }

        $code->delete();

        return 1;
    }

    public function shipNameManage(Request $request) {

        Util::getMenuInfo($request);
        $shipnames = Ship::getAllItem();
        $error = Session::get('error');
        return view('shipManage.ship_name_manage', ['list'=>$shipnames, 'error'=>$error]);
    }

    public function deleteOriginShip(Request $request) {
        $shipId = $request->get('shipId');
        $ship = Ship::find($shipId);
        if(is_null($ship))
            return -1;

        $ship->delete();
        return 1;
    }

    public function registerShipOrigin(Request $request) {
        $shipId = $request->get('shipId');
        $ship = Ship::find($shipId);
        if(is_null($ship))
            $ship = new Ship();

        $ship['name'] = $request->get('origin_name');
        $ship['shipNo'] = $request->get('shipNo');
        $isExist = Ship::where('name', $ship['name'])->orWhere('shipNo', $ship['shipNo'])->first();
        if(isset($isExist) && ($shipId != $isExist['id'])) {
            $error = $ship['name'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $ship['person_num'] = $request->get('ship-person');
        $ship->save();

        return back();
    }

    public function shipPositionManage(Request $request) {

        Util::getMenuInfo($request);

        $posName = $request->get('name');
        $list = ShipPosition::getShipPositionList($posName);
        $error = Session::get('error');
        return view('shipManage.ship_position_manage', ['list'=>$list, 'posName'=>$posName, 'error'=>$error]);
    }

    public function deleteShipPosition(Request $request) {
        $posId = $request->get('posId');
        $position = ShipPosition::find($posId);
        if(is_null($position))
            return -1;

        $position->delete();
        return 1;
    }

    public function registerShipPosition(Request $request) {
        $posId = $request->get('posId');
        $position = ShipPosition::find($posId);
        if(is_null($position))
            $position = new ShipPosition();

        $position['Duty'] = $request->get('Duty');
        $position['Duty_En'] = $request->get('Duty_En');
        $isExist = ShipPosition::where('Duty', $position['Duty'])->orWhere('Duty_En', $position['Duty_En'])->first();
        if(isset($isExist) && ($posId != $isExist['id'])) {
            $error = $position['Duty'].' 职务是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $position['Description'] = $request->get('pos_descript');
        $position->save();

        return back();
    }

    public function shipTypeManage(Request $request) {

        Util::getMenuInfo($request);
        $list = ShipType::orderBy('ShipType_Cn')->get();
        $error = Session::get('error');
        return view('shipManage.ship_type_manage', ['list'=>$list, 'error'=>$error]);
    }

    public function deleteShipType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipType::find($typeId);
        if(is_null($type))
            return -1;

        $type->delete();
        return 1;
    }

    public function registerShipType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipType::find($typeId);
        if(is_null($type))
            $type = new ShipType();

        $type['ShipType_Cn'] = $request->get('ShipType_Cn');
        $type['ShipType'] = $request->get('ShipType');

        $isExist = ShipType::where('ShipType_Cn', $type['ShipType_Cn'])->orWhere('ShipType', $type['ShipType'])->first();
        if(isset($isExist) && ($typeId != $isExist['id'])) {
            $error = $type['ShipType_Cn'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $type->save();

        return back();
    }

    public function shipISSACodeType(Request $request) {

        Util::getMenuInfo($request);
        $list = ShipIssaCode::orderBy('Code_Cn')->get();
        $error = Session::get('error');

        return view('shipManage.ship_issacode_type', ['list'=>$list, 'error'=>$error]);
    }

    public function deleteISSACodeType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipIssaCode::find($typeId);
        if(is_null($type))
            return -1;

        $type->delete();
        return 1;
    }

    public function registerISSACodeType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipIssaCode::find($typeId);
        if(is_null($type))
            $type = new ShipIssaCode();

        $type['Code'] = $request->get('Code');
        $type['Code_Cn'] = $request->get('Code_Cn');

        $isExist = ShipIssaCode::where('Code', $type['Code'])->orWhere('Code_Cn', $type['Code_Cn'])->first();
        if(isset($isExist) && ($typeId != $isExist['id'])) {
            $error = $type['Code_Cn'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $type['Code_En'] = $request->get('Code_En');
        $type['Details'] = $request->get('Details');
        $type->save();

        return back();
    }

    public function shipSTCWManage(Request $request) {

        Util::getMenuInfo($request);
        $list = ShipSTCWCode::getCodeList();
        $typeList = ShipTrainingCourse::all();
        $error = Session::get('error');

        return view('shipManage.ship_stcw_type', ['list'=>$list, 'typeList'=>$typeList, 'error'=>$error]);
    }

    public function deleteSTCWType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipSTCWCode::find($typeId);
        if(is_null($type))
            return -1;

        $type->delete();
        return 1;
    }

    public function registerSTCWType(Request $request) {
        $typeId = $request->get('typeId');
        $type = ShipSTCWCode::find($typeId);
        if(is_null($type))
            $type = new ShipSTCWCode();

        $type['STCWRegCode'] = $request->get('STCWRegCode');
        $type['Contents'] = $request->get('Contents');

        $isExist = ShipSTCWCode::where('STCWRegCode',$type['STCWRegCode'])->orWhere('Contents', $type['Contents'])->first();
        if(isset($isExist) && ($typeId != $isExist['id'])) {
            $error = $type['Contents'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $type['Contents_En'] = $request->get('Contents_En');
        $type['TrainingCourseID'] = $request->get('TrainingCourseID');
        $type->save();

        return back();
    }

    public function memberCapacityManage(Request $request) {
        Util::getMenuInfo($request);
        $STCWCodes = ShipSTCWCode::all();
        $list = ShipMemberCapacity::getData();
        return view('shipManage.member_capacity_manage', ['list' => $list, 'STCWCodes' => $STCWCodes]);
    }

    public function registerMemberCapacity(Request $request) {
        $capacityId = $request->get('capacityId');
        $capacity = ShipMemberCapacity::find($capacityId);
        if(is_null($capacity))
            $capacity = new ShipMemberCapacity();

        $capacity['Capacity'] = $request->get('capacity_Cn');
        $capacity['Capacity_En'] = $request->get('capacity_en');

        $isExist = ShipMemberCapacity::where('Capacity',$capacity['Capacity'])->orWhere('Capacity_En', $capacity['Capacity_En'])->first();
        if(isset($isExist) && ($capacityId != $isExist['id'])) {
            $error = $capacity['Capacity'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }

        $capacity['STCWRegID'] = $request->get('STCWRegID');
        $capacity['Grade'] = $request->get('grade');
        $capacity['Remarks'] = $request->get('remarks');
        $capacity['Gen_Remarks'] = $request->get('gen_remarks');
        $capacity->save();

        return back();
    }

    public function deleteMemberCapacity(Request $request) {
        $capacityId = $request->get('capacityId');
        $capacity = ShipMemberCapacity::find($capacityId);
        if(is_null($capacity))
            return -1;

        $capacity->delete();
        return 1;
    }

    public function shipOthersManage(Request $request) {
        Util::getMenuInfo($request);

        $list = ShipOthers::all();

        return view('shipManage.ship_others_manage', ['list'=>$list]);
    }

    public function registerShipOthers(Request $request) {
        $othersId = $request->get('typeId');
        $others = ShipOthers::find($othersId);
        if(is_null($others))
            $others = new ShipOthers();

        $others['Others_Cn'] = $request->get('Others_Cn');
        $others['Others_En'] = $request->get('Others_En');

        $isExist = ShipOthers::where('Others_Cn',$others['Others_Cn'])
            ->orWhere('Others_En', $others['Others_En'])
            ->first();

        if(isset($isExist) && ($othersId != $isExist['OthersId'])) {
            $error = $others['Others_Cn'].' 是已经被登记了。';
            return back()->with(['error'=>$error]);
        }
        $others['Special'] = $request->get('Special');
        $others['Remark'] = $request->get('Remark');
        $others->save();

        return back();
    }

    public function deleteShipOthers(Request $request) {
        $othersId = $request->get('OthersId');
        $others = ShipOthers::find($othersId);
        if(is_null($others))
            return -1;

        $others->delete();
        return 1;
    }
    
    // 설비 일반부속자재
    public function shipPartManage(Request $request) {
        Util::getMenuInfo($request);

        $list = ShipOthers::orderBy('Others_Cn')->OrderBy('Special')->get();
        return view('shipManage.part_manage', ['list'=>$list]);
    }

    public function ajaxSupplyDateUpdate(Request $request) {
    	$params = $request->all();

    	$equipId = $params['id'];
    	$supplyDate = $params['date'];

    	$shipEquipment = ShipEquipment::find($equipId);
    	$shipEquipment['supplied_at'] = $supplyDate;
    	$shipEquipment->save();

    	return response()->json(1);
    }

    public function getShipGeneralInfo() {
        $ship_infolist = ShipRegister::select('tb_ship_register.*', 'tb_ship.name', 'tb_ship.shipNo', 'tb_ship.person_num', 'tb_ship_type.ShipType_Cn', 'tb_ship_type.ShipType', DB::raw('IFNULL(tb_ship.id, 100) as num'))
                        ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
                        ->leftJoin('tb_ship_type', 'tb_ship_register.ShipType', '=', 'tb_ship_type.id')
                        ->orderBy('id')
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

        return $ship_infolist;
    }

    public function ajaxShipCertList(Request $request) {
    	$params = $request->all();
    	$id = $params['ship_id'];

    	if($id == 0)
		    $retVal['ship'] = ShipCertRegistry::all();
    	else {
		    $retVal['ship'] = ShipCertRegistry::where('ship_id', $id)->orderBy('cert_id', 'asc')->get();
	    }

	    $shipCertRegTbl = new ShipCertRegistry();
	    if(isset($params['expire_date']) && $params['expire_date'] > 0) {
		    $retVal['ship'] = $shipCertRegTbl->getExpiredList($params['expire_date'], $id);
	    }

	    $retVal['cert_type'] = ShipCertList::all();

	    $retVal['ship_id'] = $params['ship_id'];
	    $retVal['ship_name'] = ShipRegister::where('IMO_No', $id)->first()->shipName_En;

    	return response()->json($retVal);
    }

    public function ajaxCertAdd(Request $request) {
    	$params = $request->all();

    	$order_no = $params['order_no'];
	    $code = $params['code'];
	    $name = $params['name'];

    	$certTbl = new ShipCertList();

    	$certTbl['order_no'] = $order_no;
	    $certTbl['code'] = $code;
	    $certTbl['name'] = $name;

	    $certTbl->save();

	    $retVal = ShipCertList::all();

	    return response()->json($retVal);
    }

	public function ajaxCertItemDelete(Request $request) {
		$params = $request->all();

		ShipCertList::where('id', $params['id'])->delete();
		ShipCertRegistry::where('cert_id', $params['id'])->delete();

		$retVal = ShipCertList::all();

		return response()->json($retVal);
	}

	public function ajaxShipCertDelete(Request $request) {
		$params = $request->all();

		ShipCertRegistry::where('id', $params['id'])->delete();

		return response()->json(1);
    }
    
    public function ajaxCtmTotal(Request $request) {
        $params = $request->all();

        $shipId = $params['shipId'];
        $year = $params['year'];

        $ctmTbl = new Ctm();
        $retVal = $ctmTbl->getCtmTotal($shipId, $year);

        return response()->json($retVal);
    }

    public function ajaxCtmDebit(Request $request) {
        $params = $request->all();

        $shipId = $params['shipId'];
        $year = $params['year'];

        $ctmTbl = new Ctm();
        $retVal = $ctmTbl->getCtmDebit($shipId, $year);

        return response()->json($retVal);
    }    
}