<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/10/19
 * Time: 10:19
 */
namespace App\Http\Controllers\Convert;

use App\Http\Controllers\Controller;

use App\Models\Operations\Account;
use App\Models\Operations\Invoice;
use App\Models\Operations\ShipOilSupply;
use App\Models\Operations\VoyProfit;
use App\Models\Operations\VoyProgramPractice;
use App\Models\ShipManage\ShipType;
use App\Models\ShipMember\ShipCapacityRegister;
use App\Models\ShipMember\ShipMember;
use App\Models\ShipMember\ShipMemberCapacity;
use App\Models\ShipMember\ShipMemberCapacityCareer;
use App\Models\ShipMember\ShipMemberExaming;
use App\Models\ShipMember\ShipMemberFamily;
use App\Models\ShipMember\ShipMemberSchool;
use App\Models\ShipMember\ShipMemberSocial;
use App\Models\ShipMember\ShipMemberSubExaming;
use App\Models\ShipMember\ShipMemberTraining;
use App\Models\ShipTechnique\ShipSupply;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Controllers\Util;

use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipBoardCareer;


use App\Models\Operations\VoyStatus;
use App\Models\Operations\VoyStatusEvent;
use App\Models\Operations\VoyStatusType;

use App\Models\Operations\Cp;
use App\Models\Operations\StandardCp;

use App\Models\Convert\VoyLog;


use Auth;

class convertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showIcon() {
        $topMenu = Menu::where('parentId', '0')->orderBy('id')->get();
        $menulist = Menu::where('parentId', '=', '3')->orderBy('id')->get();
        //$GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 3;
        $GLOBALS['menulist'] = $menulist;
        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;
        return view('operation.supply.icons');
    }

    public function convertCrewId() {

        $memberList =  ShipMemberTraining::groupBy('SeafarerID')->get();

        foreach($memberList as $member) {

            echo '<br>'.$member['SeafarerID'];

            $crowNum = $member['SeafarerID'];

            $memberInfo = ShipMember::where('crewNum', $crowNum)->first();
            if(empty($memberInfo)) {
                continue;
            }

            ShipMemberTraining::where('SeafarerID', $crowNum)->update(['SeafarerID'=>$memberInfo->id]);
        }
    }

    public function updateMemberSubExam() {

        $list = ShipMemberSubExaming::groupBy('SeafarerId')->groupBy('ExamId')->get();

        foreach($list as $submask) {
            $exam = ShipMemberExaming::where('memberId', $submask->SeafarerId)->where('ExamCode', $submask->ExamId)->first();
            if(!empty($exam)) {
                ShipMemberSubExaming::where('SeafarerId', $submask->SeafarerId)
                                    ->where('ExamId', $submask->ExamId)
                                    ->update(['ExamId'=>$exam->id]);
            }

        }
    }

    public function convertInvoiceId() {

        $voyLog = ShipOilSupply::get();
        foreach($voyLog as $log) {
            $cp = Invoice::where('Ref_No', $log->INVOICE_ID)->first();
            if(empty($cp))
                continue;
            echo '<br>'.$log->INVOICE_ID . '=>' . $cp['id'];
            ShipOilSupply::where('INVOICE_ID', $log->INVOICE_ID)->update(['INVOICE_ID'=>$cp['id']]);
        }
    }

    public function convertVoyId() {

        $voyLog = VoyLog::groupBy('CP_ID')->get();
        foreach($voyLog as $log) {
            $cp = Cp::where('CP_No', $log->CP_ID)->first();
            if(empty($cp))
                continue;
            echo '<br>'.$log->CP_ID. '=>' . $cp['id'];
            VoyLog::where('CP_ID', $log->CP_ID)->update(['CP_ID'=>$cp['id']]);
        }
    }

    public function convertInvoiceAccountId() {

        $voyLog = Invoice::groupBy('Account')->get();
        foreach($voyLog as $log) {
            $cp = Account::where('AccountName_En', $log->Account)->first();
            if(empty($cp))
                continue;
            echo '<br>'.$log->Account. '=>' . $cp['id'];
            Invoice::where('Account', $log->Account)->update(['Account'=>$cp['id']]);
        }
    }

    public function createVoylogUpdate_at() {

        $voyLog = VoyLog::get();
        foreach($voyLog as $log) {
            $log['update_at'] = $log['create_at'];
            $log->save();
        }
    }

    public function converArvdPortState() {
        $voyLog = VoyLog::where('Voy_Status', 2)->orWhere('Voy_Status', 71)->get();

        foreach($voyLog as $log) {
            if(is_null($log['Cargo_Qtty']) || ($log['Cargo_Qtty'] < 1))
                $log['Voy_Status'] = 2;
            else
                $log['Voy_Status'] = 71;

            $log->save();
        }
    }

    public function converBerthPortState() {
        $voyLog = VoyLog::where('Voy_Status', 4)->orWhere('Voy_Status', 72)->get();

        foreach($voyLog as $log) {
            if(is_null($log['Cargo_Qtty']) || ($log['Cargo_Qtty'] < 1))
                $log['Voy_Status'] = 4;
            else
                $log['Voy_Status'] = 72;

            $log->save();
        }
    }

    public function calculateVoyLogItem() {
        $voyLog = VoyLog::groupBy('CP_ID')->get();
        foreach($voyLog as $log) {
            $cpId = $log['CP_ID'];

            $stopLogs = VoyLog::select('tbl_voy_log.*', 'tbl_voy_status_event.Event', 'tbl_voy_status_event.Description')
                ->join('tbl_voy_status', 'tbl_voy_log.Voy_Status', '=', 'tbl_voy_status.id')
                ->join('tbl_voy_status_event', 'tbl_voy_status.Related_Other', '=', 'tbl_voy_status_event.id')
                ->where('tbl_voy_log.CP_ID', $cpId)
                ->where('tbl_voy_status_event.Event', 'like', '%Stop%')
                ->orderBy('tbl_voy_log.Voy_Date')
                ->get();

            foreach($stopLogs as $stop) {
                $eventName = str_replace('Stop', 'Start', $stop['Event']);
                $startLog = VoyLog::select('tbl_voy_log.*')
                    ->join('tbl_voy_status', 'tbl_voy_log.Voy_Status', '=', 'tbl_voy_status.id')
                    ->join('tbl_voy_status_event', 'tbl_voy_status.Related_Other', '=', 'tbl_voy_status_event.id')
                    ->where('tbl_voy_log.CP_ID', $cpId)
                    ->where('tbl_voy_status_event.Event', '=', $eventName)
                    ->where('tbl_voy_log.Voy_Date', '<', $stop['Voy_Date'])
                    ->orderBy('tbl_voy_log.Voy_Date', 'DESC')
                    ->first();

                $startDate =  new \DateTime($startLog['Voy_Date']);
                $startStamp = date_timestamp_get($startDate);
                $endDate =  new \DateTime($stop['Voy_Date']);
                $endStamp = date_timestamp_get($endDate);
                $round = round(($endStamp - $startStamp) / 86400, 2);
                echo $stop['id'].'-name:'.$eventName.' :  start :'.$startLog['Voy_Date'].'--- end:'.$stop['Voy_Date'].' ---- interval = '.$round;
                echo "<br>";

                if($round < 0)
                    $round = 0;
                $stopLog = VoyLog::find($stop['id']);
                $stopLog['timesum'] = $round;
                $stopLog->save();

            }
        }
    }

    // ???? ??????
    public function calculateVoyLogLKGItem() {
        $voyLog = VoyLog::groupBy('CP_ID')->get();
        foreach($voyLog as $log) {
            $cpId = $log['CP_ID'];

            $stopLogs = VoyLog::select('tbl_voy_log.*', 'tbl_voy_status_event.Event', 'tbl_voy_status_event.Description')
                ->join('tbl_voy_status', 'tbl_voy_log.Voy_Status', '=', 'tbl_voy_status.id')
                ->join('tbl_voy_status_event', 'tbl_voy_status.Related_UnEconomy', '=', 'tbl_voy_status_event.id')
                ->where('tbl_voy_log.CP_ID', $cpId)
                ->where('tbl_voy_status_event.Event', 'LKGStop')
                ->orderBy('tbl_voy_log.Voy_Date')
                ->get();

            foreach($stopLogs as $stop) {
                $eventName = str_replace('Stop', 'Start', $stop['Event']);
                $startLog = VoyLog::select('tbl_voy_log.*')
                    ->where('tbl_voy_log.CP_ID', $cpId)
                    ->where('tbl_voy_log.Voy_Status', 9)
                    ->where('tbl_voy_log.Ship_Position', '=', 'WSL')
                    ->where('tbl_voy_log.Voy_Date', '<', $stop['Voy_Date'])
                    ->orderBy('tbl_voy_log.Voy_Date', 'DESC')
                    ->first();

                $startDate =  new \DateTime($startLog['Voy_Date']);
                $startStamp = date_timestamp_get($startDate);
                $endDate =  new \DateTime($stop['Voy_Date']);
                $endStamp = date_timestamp_get($endDate);
                $round = round(($endStamp - $startStamp) / 86400, 2);
                echo $stop['id'].'-name:'.$eventName.' :  start :'.$startLog['Voy_Date'].'--- end:'.$stop['Voy_Date'].' ---- interval = '.$round;
                echo "<br>";

                if($round < 0)
                    $round = 0;
                $stopLog = VoyLog::find($stop['id']);
                $stopLog['timesum'] = $round;
                $stopLog->save();
            }
        }
    }

}