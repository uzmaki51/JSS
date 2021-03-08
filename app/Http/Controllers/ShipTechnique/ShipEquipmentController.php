<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 5/22/2017
 * Time: 2:51 PM
 */
namespace App\Http\Controllers\shipTechnique;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\ShipTechnique\ShipSupply;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\ShipManage\ShipRegister;
use App\Models\SupplyPlan\SupplyPlan;
use App\Models\ShipTechnique\ShipDept;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ShipEquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;

        $admin = Session::get('admin');
        $query = Menu::where('parentId', '0');
        if($admin == 0)
            $query = $query->where('admin', '0');
        $topMenu = $query->get();
        $GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 7;

        $menulist = Menu::where('parentId', '=', '7')->get();
        foreach ($menulist as $menu) {
            $menuId = $menu['id'];
            $submenus = Menu::where('parentId', '=', $menuId)->get();
            $menu['submenu'] = $submenus;
        }
        $GLOBALS['menulist'] = $menulist;
    }

    public function supplyPlan(Request $request)
    {
        Util::getMenuInfo($request);

        $years_range = new \stdClass();
        $years_range->min = SupplyPlan::getMinYear();
        $years_range->max = SupplyPlan::getMaxYear();
//        배목록
        $sel_year = $request->get('year');
        $shipid=$request->get('shipid');
        $deptcount=ShipDept::count();
        $shipInfo=ShipRegister::getShipListByOrigin();
        $deptInfo=ShipDept::all(['id','Dept_Cn']);

        $shipList=SupplyPlan::getPlanedShipList(0);
        $shipNameCol=$request->get('shipNameCol');

        $nShips=count($shipList);

        $colYear = $request->get('yearCol');

        $tab=$request->get('tab');
        if(!empty($sel_year)) {
            $supplyplanlist = SupplyPlan::getPlanList($sel_year, $shipid);
            $supplyplanlist->appends(['tab' => $tab, 'year' => $sel_year, 'shipid' => $shipid, 'menuId' => $GLOBALS['selMenu']]);
        } else {
            $supplyplanlist = SupplyPlan::getPlanList(0, 0);
        }
        if(empty($colYear)) $colYear = $years_range->min;
        if(!empty($shipNameCol)){
            $nShips = 1;
            $ship = ShipRegister::find($shipNameCol);
            $shipNames=$ship['RegNo'];
            $shipcolList=array();
            $shipcolList[]=array('shipName_Cn'=>$ship['shipName_Cn'], 'ShipName_En' => $ship['shipName_En'],'ShipName'=>$shipNames);
            $supplyAmounts=SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->join('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyplan.ShipName')
                ->where('tbl_supplyplan.Yearly', $colYear)
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->where('tbl_supplyplan.ShipName', $shipNames)
                ->get(['tbl_supplyplan.Yearly','tbl_supplyplan.Monthly','tbl_supplyplan.ShipName',
                    'tbl_supplyplan.Dept','tbl_supplyplan.Amount']);
        }else{
            $shipcolList=$shipList;
            $supplyAmounts=SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                ->join('tb_ship_register', 'tb_ship_register.RegNo', '=', 'tbl_supplyplan.ShipName')
                ->where('tbl_supplyplan.Yearly', $colYear)
                ->where('tbl_supplyplan.Monthly', '<>', 'NULL')
                ->get(['tbl_supplyplan.Yearly','tbl_supplyplan.Monthly','tbl_supplyplan.ShipName',
                    'tbl_supplyplan.Dept','tbl_supplyplan.Amount']);
        }

        $amounts=array();
        $year = $colYear;
        foreach($supplyAmounts as $supplyAmount)
        {
            $shipName=$supplyAmount['ShipName'];
            $Month=$supplyAmount['Monthly'];
            $amounts[$year.$shipName.$supplyAmount['Dept'].$Month]=$supplyAmount['Amount'];
            if(!isset($amounts[$year.$shipName.$supplyAmount['Dept'].'0']))
                $amounts[$year.$shipName.$supplyAmount['Dept'].'0'] = 0;
            $amounts[$year.$shipName.$supplyAmount['Dept'].'0'] += $supplyAmount['Amount'];
        }
        for($Month = 1; $Month < 13; $Month++) {
            foreach($shipcolList as $shipListItem) {
                $shipName = $shipListItem['ShipName'];
                $amounts[$year . $Month . $shipName] =
                    SupplyPlan::join('tbl_dept', 'tbl_dept.id', '=', 'tbl_supplyplan.Dept')
                        ->where('tbl_supplyplan.Yearly', '=', $year)
                        ->where('tbl_supplyplan.Monthly', '=', $Month)
                        ->where('tbl_supplyplan.ShipName', $shipName)
                        ->sum('tbl_supplyplan.Amount');
                if (!isset($amounts[$year . '0' . $shipName]))
                    $amounts[$year . '0' . $shipName] = 0;
                $amounts[$year . '0' . $shipName]
                    += $amounts[$year . $Month . $shipName];
                if(!isset($amounts[$year.$Month.'0']))
                    $amounts[$year.$Month.'0'] = 0;
                $amounts[$year.$Month.'0']
                    += $amounts[$year.$Month.$shipName];
            }
            if(!isset($amounts[$year.'00']))
                $amounts[$year.'00'] = 0;
            $amounts[$year.'00']
                += $amounts[$year.$Month.'0'];
        }
        return view('shipTechnique.shipEquipment.supplyPlan',
            ['tab'=>$tab,'shipcolList'=>$shipcolList,'year_range'=>$years_range,'shipList'=>$shipList,'supplyplanlist'=>$supplyplanlist,
                'year'=>$sel_year,'shipid'=>$shipid,'nShips'=>$nShips,'nDepts'=>$deptcount,'shipInfos'=>$shipInfo,'deptInfos'=>$deptInfo,
                'supplyAmounts'=>$amounts,'yearCol'=>$colYear,'shipNameCol'=>$shipNameCol]);
    }

    public function supplyPlanAdd(Request $request)
    {
        $id = $request->get('id');
        if(empty($id) || $id == 0) {
            $count = SupplyPlan::where('Yearly', $request->get('yearly'))
                ->where('Monthly', $request->get('monthly'))
                ->where('ShipName', $request->get('shipName'))
                ->where('Dept', $request->get('dept'))
                ->get()->count();
            if($count > 0) return -1;
            $supplyPlan = new SupplyPlan();
        } else {
            $supplyPlan = SupplyPlan::find($id);
        }
        $supplyPlan['Yearly'] = $request->get('yearly');
        $supplyPlan['Monthly'] = $request->get('monthly');
        $supplyPlan['ShipName'] = $request->get('shipName');
        $supplyPlan['Dept'] = $request->get('dept');
        $supplyPlan['PlanContent'] = $request->get('planContent');
        $supplyPlan['Amount'] = $request->get('planAmount');
        $supplyPlan['Remark'] = $request->get('planRemark');
        $supplyPlan->save();

        $lastId = 0;
        if(empty($id) || $id == 0) {
            $last = SupplyPlan::all()->last(['Id']);
            $lastId = $last['Id'];
        } else {
            $lastId = $id;
        }
        return $lastId;
    }

    public function supplyReport(Request $request)
    {
        Util::getMenuInfo($request);

        $this_year = $request->get('yearly');
        if(empty($this_year)) $this_year = date('Y');
        $years = SupplyPlan::groupBy('Yearly')->orderBy('Yearly')->get();
        $ships = ShipRegister::getShipListByOrigin();
        $deptInfos = ShipDept::all(['id','Dept_Cn']);
        $plans = array(); $supplies = array();
        for($month = 1; $month < 13; $month++) {
            foreach($deptInfos as $deptInfo) {
                foreach ($ships as $ship) {
                    $plan = SupplyPlan::where('Yearly', $this_year)
                        ->where('Monthly', $month)
                        ->where('Dept', $deptInfo['id'])
                        ->where('ShipName', $ship['RegNo'])
                        ->first();
                    if($month < 10) $date = $this_year.'-0'.$month;
                    else $date = $this_year.'-'.$month;
                    $supply = ShipSupply::where('ReciptDate', 'like', $date.'%')
                        ->where('Dept', $deptInfo['id'])
                        ->where('ShipName', $ship['RegNo'])
                        ->sum('TotalAmount');
                    array_push($plans, empty($plan) ? 0 : $plan['Amount']);
                    array_push($supplies, $supply);
                }
            }
        }

        return view('shipTechnique.shipEquipment.supplyReport')
            ->with(compact('years', 'this_year', 'ships', 'deptInfos', 'plans', 'supplies'));
    }

}