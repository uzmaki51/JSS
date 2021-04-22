<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/13
 * Time: 9:39
 */

namespace App\Http\Controllers\ShipManage;

use App\Http\Controllers\Controller;
use App\Models\ShipMember\ShipMember;
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

use Auth;
use Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Lang;

class WageController extends Controller
{
    protected $userInfo;
    private $control = 'shipManage';
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $shipList = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship_register.NickName', 'tb_ship.name')
                        ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->get();

        return view('shipMember.member_wages', [
        	'shipList'      => $shipList,
        ]);
    }
}