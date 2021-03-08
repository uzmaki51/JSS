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

use App\Models\ShipTechnique\EquipmentUnit;

use Auth;
use Config;
use Illuminate\Support\Facades\App;

class ShipRegController extends Controller
{
    protected $userInfo;
    private $control = 'shipManage';

    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;
        $this->userInfo = Auth::user();

        $locale = Session::get('locale');
        if(empty($locale)) {
            $locale = Config::get('app.locale');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        $admin = Session::get('admin');
        if($admin > 0){
            $topMenu = Menu::where('parentId', '0')->get();
        } else {
            $topMenu = Util::getTopMemu($this->userInfo['menu']);
        }
        $GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 4;

        if ($admin > 0) {
            $menulist = Menu::where('parentId', '=', '4')->orderBy('id')->get();
            foreach ($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {
			$user = Auth::user();
			if(in_array(4, explode(',', $user['menu']))) {
				$menulist = Menu::where('parentId', '=', '4')->where('admin', '=', '0')->get();
				foreach ($menulist as $menu) {
					$menuId = $menu['id'];
					$submenus = Menu::where('parentId', '=', $menuId)->get();
					$menu['submenu'] = $submenus;
				}
				$GLOBALS['menulist'] = $menulist;
			} else {
				$menulist = Menu::where('parentId', '=', '4')->where('admin', '=', '0')->whereIn('id', explode(',', $user['menu']))->get();
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
        return redirect('shipManage/shipinfo');
    }

    //배제원현시부분
    public function loadShipGeneralInfos(Request $request)
    {
        Util::getMenuInfo($request);
        $ship_infolist = ShipRegister::select('tb_ship_register.*', 'tb_ship.name', 'tb_ship.shipNo', 'tb_ship.person_num', 'tb_ship_type.ShipType_Cn', 'tb_ship_type.ShipType', DB::raw('IFNULL(tb_ship.id, 100) as num'))
                        ->leftJoin('tb_ship', 'tb_ship_register.Shipid', '=', 'tb_ship.id')
                        ->leftJoin('tb_ship_type', 'tb_ship_register.ShipType', '=', 'tb_ship_type.id')
                        ->orderBy('num')
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

        return view('shipManage.shipinfo', array('list'=> $ship_infolist));
    }

    //배등록
    public function registerShipData(Request $request)
    {
        $GLOBALS['selMenu'] = 52;
        $GLOBALS['submenu'] = 0;

        $shipList = Ship::all();
        $shipType = ShipType::all();

        $shipId = $request->get('shipId');
        if(is_null($shipId))
            $shipId = '0';

        if($shipId != '0')
            $shipInfo = ShipRegister::where('id', $shipId)->first();
        else
            $shipInfo = new ShipRegister();

        $status = Session::get('status');

        $tabName = Session::get('tabName');
        Session::forget('tabName');
        $tabName = isset($tabName) ? $tabName : "#general";
        return view('shipManage.shipregister', ['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo, 'status'=>$status, 'tabName'   => $tabName]);
    }

    public function saveShipGenaralData(Request $request) {
        $shipId = trim($request->get('shipId')) * 1;
        $tabName = $request->get('_tabName');
        Session::put('tabName', $tabName);
        if($shipId > 0) {
            $shipData = ShipRegister::find($shipId);
        } else {
            $shipData = new ShipRegister();
        }

        $shipData['shipName_Cn'] = $request->get('shipName_Cn');
        $shipData['shipName_En'] = $request->get('shipName_En');
        $shipData['Shipid'] = $request->get('Shipid');
        $shipData['Class'] = $request->get('Class');
        $shipData['RegNo'] = $request->get('RegNo');
        $shipData['RegStatus'] = $request->get('RegStatus');
        $shipData['SerialNo'] = $request->get('SerialNo');
        $shipData['CallSign'] = $request->get('CallSign');
        $shipData['MMSI'] = $request->get('MMSI');
        $shipData['IMO_No'] = $request->get('IMO_No');
        $shipData['INMARSAT'] = $request->get('INMARSAT');
        $shipData['OriginalShipName'] = $request->get('OriginalShipName');
        $shipData['Flag_Cn'] = $request->get('Flag_Cn');
        $shipData['Flag'] = $request->get('Flag');
        $shipData['PortOfRegistry_Cn'] = $request->get('PortOfRegistry_Cn');
        $shipData['PortOfRegistry'] = $request->get('PortOfRegistry');
        $shipData['Owner_Cn'] = $request->get('Owner_Cn');
        $shipData['Owner_En'] = $request->get('Owner_En');
        $shipData['OwnerAddress_Cn'] = $request->get('OwnerAddress_Cn');
        $shipData['OwnerAddress_En'] = $request->get('OwnerAddress_En');
        $shipData['OwnerTelnumber'] = $request->get('OwnerTelnumber');
        $shipData['OwnerFax'] = $request->get('OwnerFax');
        $shipData['OwnerEmail'] = $request->get('OwnerEmail');
        $shipData['ISM_Cn'] = $request->get('ISM_Cn');
        $shipData['ISM_En'] = $request->get('ISM_En');
        $shipData['ISMAddress_Cn'] = $request->get('ISMAddress_Cn');
        $shipData['ISMAddress_En'] = $request->get('ISMAddress_En');
        $shipData['ISMTelnumber'] = $request->get('ISMTelnumber');
        $shipData['ISMFax'] = $request->get('ISMFax');
        $shipData['ISMEmail'] = $request->get('ISMEmail');
        $shipData['ShipType'] = $request->get('ShipType');
        $shipData['GrossTon'] = $request->get('GrossTon');
        $shipData['LOA'] = $request->get('LOA');
        $shipData['NetTon'] = $request->get('NetTon');
        $shipData['LBP'] = $request->get('LBP');
        $shipData['Deadweight'] = $request->get('Deadweight');
        $shipData['Length'] = $request->get('Length');
        $shipData['Displacement'] = $request->get('Displacement');
        $shipData['BM'] = $request->get('BM');
        $shipData['Ballast'] = $request->get('Ballast');
        $shipData['DM'] = $request->get('DM');
        $shipData['FuelBunker'] = $request->get('FuelBunker');
        $shipData['ShipBuilder'] = $request->get('ShipBuilder');
        $shipData['KeelDate'] = $request->get('KeelDate');
        $shipData['DeckErection_B'] = $request->get('DeckErection_B');
        $shipData['LaunchDate'] = $request->get('LaunchDate');
        $shipData['DeckErection_F'] = $request->get('DeckErection_F');
        $shipData['DeliveryDate'] = $request->get('DeliveryDate');
        $shipData['DeckErection_P'] = $request->get('DeckErection_P');
        $shipData['ConversionDate'] = $request->get('ConversionDate');
        $shipData['DeckErection_H'] = $request->get('DeckErection_H');
        $shipData['RegDate'] = $request->get('RegDate');
        $shipData['RenewDate'] = $request->get('RenewDate');
        $shipData['KCExpiryDate'] = $request->get('KCExpiryDate');
        $shipData['ConditionalDate'] = $request->get('ConditionalDate');
        $shipData['DelDate'] = $request->get('DelDate');
        $shipData['Draught'] = $request->get('Draught');
        $shipData['BuildPlace_Cn'] = $request->get('BuildPlace_Cn');
        $shipData->save();

        if($shipId == 0)
            $shipId = ShipRegister::all()->last()->id;

        return redirect('shipManage/registerShipData?shipId='.$shipId)->with('status', 'success');
    }

    public function saveShipHullData(Request $request) {
        $shipId = trim($request->get('shipId')) * 1;
        if($shipId > 0) {
            $shipData = ShipRegister::find($shipId);
        } else {
            $shipData = new ShipRegister();
        }

        $shipData['HullNo'] = $request->get('HullNo');
        $shipData['Decks'] = $request->get('Decks');
        $shipData['Bulkheads'] = $request->get('Bulkheads');
        $shipData['NumberOfHolds'] = $request->get('NumberOfHolds');
        $shipData['CapacityOfHoldsG'] = $request->get('CapacityOfHoldsG');
        $shipData['CapacityOfHoldsB'] = $request->get('CapacityOfHoldsB');
        $shipData['HoldsDetail'] = $request->get('HoldsDetail');
        $shipData['NumberOfHatchways'] = $request->get('NumberOfHatchways');
        $shipData['SizeOfHatchways'] = $request->get('SizeOfHatchways');
        $shipData['ContainerOnDeck'] = $request->get('ContainerOnDeck');
        $shipData['ContainerInHold'] = $request->get('ContainerInHold');
        $shipData['LiftingDevice'] = $request->get('LiftingDevice');
        $shipData->save();

        if($shipId == 0)
            $shipId = ShipRegister::all()->last()->id;

        return redirect('shipManage/registerShipData?shipId='.$shipId)->with('status', 'success');
    }

    public function saveShipMahcineryData(Request $request) {
        $shipId = trim($request->get('shipId')) * 1;
        if($shipId > 0) {
            $shipData = ShipRegister::find($shipId);
        } else {
            $shipData = new ShipRegister();
        }

        $shipData['No_TypeOfEngine'] = $request->get('No_TypeOfEngine');
        $shipData['Cylinder_Bore_Stroke'] = $request->get('Cylinder_Bore_Stroke');
        $shipData['Power'] = $request->get('Power');
        $shipData['rpm'] = $request->get('rpm');
        $shipData['EngineManufacturer'] = $request->get('EngineManufacturer');
        $shipData['AddressEngMaker'] = $request->get('AddressEngMaker');
        $shipData['EngineDate'] = $request->get('EngineDate');
        $shipData['Speed'] = $request->get('Speed');
        $shipData['PrimeMover'] = $request->get('PrimeMover');
        $shipData['GeneratorOutput'] = $request->get('GeneratorOutput');
        $shipData['AnchorageType'] = $request->get('AnchorageType');
        $shipData['AnchoragePower'] = $request->get('AnchoragePower');
        $shipData['AnchorageRPM'] = $request->get('AnchorageRPM');
        $shipData['Boiler'] = $request->get('Boiler');
        $shipData['BoilerPressure'] = $request->get('BoilerPressure');
        $shipData['BoilerManufacturer'] = $request->get('BoilerManufacturer');
        $shipData['AddressBoilerMaker'] = $request->get('AddressBoilerMaker');
        $shipData['BoilerDate'] = $request->get('BoilerDate');
        $shipData['FOSailCons_S'] = $request->get('FOSailCons_S');
        $shipData['FOL/DCons_S'] = $request->get('FOL/DCons_S');
        $shipData['FOIdleCons_S'] = $request->get('FOIdleCons_S');
        $shipData['DOSailCons_S'] = $request->get('DOSailCons_S');
        $shipData['DOL/DCons_S'] = $request->get('DOL/DCons_S');
        $shipData['DOIdleCons_S'] = $request->get('DOIdleCons_S');
        $shipData['LOSailCons_S'] = $request->get('LOSailCons_S');
        $shipData['LOL/DCons_S'] = $request->get('LOL/DCons_S');
        $shipData['LOIdleCons_S'] = $request->get('LOIdleCons_S');
        $shipData['FOSailCons_W'] = $request->get('FOSailCons_W');
        $shipData['FOL/DCons_W'] = $request->get('FOL/DCons_W');
        $shipData['FOIdleCons_W'] = $request->get('FOIdleCons_W');
        $shipData['DOSailCons_W'] = $request->get('DOSailCons_W');
        $shipData['DOL/DCons_W'] = $request->get('DOL/DCons_W');
        $shipData['DOIdleCons_W'] = $request->get('DOIdleCons_W');
        $shipData['LOSailCons_W'] = $request->get('LOSailCons_W');
        $shipData['LOL/DCons_W'] = $request->get('LOL/DCons_W');
        $shipData['LOIdleCons_W'] = $request->get('LOIdleCons_W');
        $shipData['FO_tank_capacity'] = $request->get('FO_tank_capacity');
        $shipData['FO_tank_desc'] = $request->get('FO_tank_desc');
        $shipData['DO_tank_capacity'] = $request->get('DO_tank_capacity');
        $shipData['DO_tank_desc'] = $request->get('DO_tank_desc');
        $shipData['BW_tank_capacity'] = $request->get('BW_tank_capacity');
        $shipData['BW_tank_desc'] = $request->get('BW_tank_desc');
        $shipData['FW_tank_capacity'] = $request->get('FW_tank_capacity');
        $shipData['FW_tank_desc'] = $request->get('FW_tank_desc');
        $shipData->save();

        if($shipId == 0)
            $shipId = ShipRegister::all()->last()->id;

        return redirect('shipManage/registerShipData?shipId='.$shipId)->with('status', 'success');
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
            return view('shipManage.tab_hull', ['shipInfo'=>$shipInfo]);
        } else if($tabName == '#machiery') {
            return view('shipManage.tab_machinery', ['shipInfo'=>$shipInfo]);
        } else if($tabName == '#safety') {
            $posList = ShipPosReg::getPostionListByShip($shipInfo['RegNo']);
            $position = ShipPosition::all();
            $codeList = ShipSTCWCode::all();

            return view('shipManage.tab_safety', ['shipInfo'=>$shipInfo, 'posList'=>$posList, 'shipPos'=>$position, 'codeList'=>$codeList]);
        } else if($tabName == '#photo') {
            $imageList = ShipPhoto::where('RegNo', $shipInfo['RegNo'])->get();
            return view('shipManage.tab_photo', ['shipIs'=>$shipInfo['id'], 'imageList'=>$imageList]);
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
    public function shipCertList(Request $request)
    {
        Util::getMenuInfo($request);
        $shipRegList = ShipRegister::getShipListByOrigin();

        $shipId = $request->get('shipId');
        $certName = $request->get('certName');
        $issuUnit = $request->get('issuUnit');
        $expireMonth = $request->get('expireMonth');

        $shipNameInfo = null;
        if(isset($shipId))
            $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);

        $certType = ShipCertList::all();
        $certList = ShipCertRegistry::getShipCertList($shipId, $certName, $issuUnit, $expireMonth);

        return view('shipManage.ship_cert_registry',
            [   'shipList'  =>  $shipRegList,
                'shipName'  =>  $shipNameInfo,
                'list'      =>  $certList,
                'typeList'  =>  $certType,
                'shipId'    =>  $shipId,
                'certName'  =>  $certName,
                'issuUnit'  =>  $issuUnit,
                'expireMonth'=> $expireMonth,
            ]);
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

    // 배증서 추가 및 수정
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
    public function shipCertManage(Request $request)
    {
        Util::getMenuInfo($request);
        $cert = $request->get('cert');

        $query = ShipCertList::query();
        if(isset($cert))
            $query->where('CertName_Cn', 'like', '%'.$cert.'%');

        $query=$query->orderby('CertNo');
        $certList = $query->get();

        $error = Session::get('error');

        return view('shipManage.cert_manage', ['list'=>$certList, 'cert'=>$cert, 'error'=>$error]);
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

    // 증서 추가 및 수정
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
            $error = "错误!  做成的船证书已经登陆了。";
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

        $GLOBALS['selMenu'] = 54;  // 설비등록메뉴표
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
            $error = $ship['name'].' 是已经被登陆了。';
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
            $error = $position['Duty'].' 职务是已经被登陆了。';
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
            $error = $type['ShipType_Cn'].' 是已经被登录了。';
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
            $error = $type['Code_Cn'].' 是已经被登陆了。';
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
            $error = $type['Contents'].' 是已经被登录了。';
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
            $error = $capacity['Capacity'].' 是已经被登录了。';
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
            $error = $others['Others_Cn'].' 是已经被登录了。';
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

}