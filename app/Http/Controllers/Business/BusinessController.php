<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/10/2017
 * Time: 4:15 PM
 */
namespace App\Http\Controllers\Business;

use App\Helpers\calDate; //주계산 (수정:콤대->정은혁)


use App\Http\Controllers\Controller;
use App\Models\Member\Post;
use App\Models\Operations\Cargo;
use App\Models\Operations\Cp;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipTechnique\ShipPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Util;
use App\Models\Menu;
use App\Models\UserInfo;
use App\Models\Member\Unit;

use App\Models\Plan\MainPlan;
use App\Models\Plan\SubPlan;
use App\Models\Plan\ReportPerson;
use App\Models\Plan\ReportPersonWeek;
use App\Models\Plan\ReportPersonMonth;
use App\Models\Plan\UnitWeekReport;
use App\Models\Plan\UnitMonthReport;

use App\Models\Board\News;
use App\Models\Board\NewsTema;
use App\Models\Board\NewsResponse;
use App\Models\Board\NewsRecommend;
use App\Models\Board\NewsHistory;

//선원출근일보登记
use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendTime;
use App\Models\Attend\AttendRest;
use App\Models\Attend\AttendShip;

use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipMember;

// 일정계획
use App\Models\Schedule;
use App\Models\Decision\DecEnvironment;
use Illuminate\Support\Str;
//결재관리
use Auth;


class BusinessController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

	public function contract(Request $request) {
        $params = $request->all();
		if(isset($params['shipId']))
			$shipId = $params['shipId'];
		else {
            $firstShipInfo = ShipRegister::first();
            if($firstShipInfo == null && $firstShipInfo == false)
                return redirect()->back();

            $shipId = $firstShipInfo->IMO_No;
        }

		$shipList = ShipRegister::all();
        $cp_list = CP::where('Ship_ID', $shipId)->orderBy('Voy_No', 'desc')->get();
        $tmp = CP::orderBy('net_profit_day', 'desc')->first();
        if($tmp == null || $tmp == false) {
            $maxVoyNo = '';
            $maxFreight = 0;
        } else {
            $maxVoyNo = $tmp['Voy_No'];
            $maxFreight = $tmp['net_profit_day'] == null ? 0 : $tmp['net_profit_day'];
        }

        $tmp = CP::whereNotNull('net_profit_day')->orderBy('net_profit_day', 'asc')->first();
        if($tmp == null || $tmp == false) {
            $minVoyNo = 0;
            $minFreight = 0;
        } else {
            $minVoyNo = $tmp['Voy_No'];
            $minFreight = $tmp['net_profit_day'] == null ? 0 : $tmp['net_profit_day'];
        }

		return view('business.ship_contract', array(
			'shipId'	    =>  $shipId,
			'shipList'      =>  $shipList,
            'cp_list'       =>  $cp_list,
            
            'maxVoyNo'      => $maxVoyNo,
            'maxFreight'    => $maxFreight,
            'minVoyNo'      => $minVoyNo,
            'minFreight'    => $minFreight,
		));
    }

    public function dynRecord(Request $request) {
        $shipList = ShipRegister::all();
        return view('business.dynamic.record', [
            'shipList'          => $shipList,
        ]);
    }
    
    public function saveVoyContract(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else 
            return redirect()->back();

        $cpTbl = new CP;
        $cpTbl['CP_kind'] = $params['cp_type'];
        $cpTbl['CP_Date'] = $params['cp_date'];
        $cpTbl['Voy_No'] = $params['voy_no'];
        $cpTbl['Ship_ID'] = $shipId;
        $cpTbl['Cargo'] = $params['cargo'];
        $cpTbl['Cgo_Qtty'] = $params['qty_amount'];
        $cpTbl['Qtty_Type'] = $params['qty_type'];
        $cpTbl['LPort'] = $params['up_port'];
        $cpTbl['DPort'] = $params['down_port'];
        $cpTbl['LayCan_Date1'] = $params['lay_date'];
        $cpTbl['LayCan_Date2'] = $params['can_date'];
        $cpTbl['L_Rate'] = $params['load_rate'];
        $cpTbl['D_Rate'] = $params['disch_rate'];
        $cpTbl['Freight'] = $params['freight_rate'];
        $cpTbl['total_Freight'] = $params['lumpsum'];
        $cpTbl['net_profit_day'] = $params['net_profit_day'];
        $cpTbl['deten_fee'] = $params['deten_fee'];
        $cpTbl['dispatch_fee'] = $params['dispatch_fee'];
        $cpTbl['com_fee'] = $params['com_fee'];
        $cpTbl['charterer'] = $params['charterer'];
        $cpTbl['tel_number'] = $params['tel_number'];
        $cpTbl['Remarks'] = $params['remark'];

        if($request->hasFile('attachment')) {
            $cpTbl['is_attachment'] = 1;
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $name = date('Ymd_His') . '_VOY_' . Str::random(10). '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/contract/', $name);
            $cpTbl['attachment_url'] = url() . '/contract/' . $name;
        }
        
        $cpTbl->save();

        return redirect()->back();
        
    }

    public function saveTcContract(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else 
            return redirect()->back();

        $cpTbl = new CP;
        $cpTbl['CP_kind'] = $params['cp_type'];
        $cpTbl['CP_Date'] = $params['cp_date'];
        $cpTbl['Voy_No'] = $params['voy_no'];
        $cpTbl['Ship_ID'] = $shipId;
        $cpTbl['Cargo'] = $params['cargo'];
        $cpTbl['Cgo_Qtty'] = $params['hire_duration'];
        $cpTbl['LPort'] = $params['up_port'];
        $cpTbl['DPort'] = $params['down_port'];
        $cpTbl['LayCan_Date1'] = $params['lay_date'];
        $cpTbl['LayCan_Date2'] = $params['can_date'];
        $cpTbl['L_Rate'] = $params['dely'];
        $cpTbl['D_Rate'] = $params['redely'];
        $cpTbl['Freight'] = $params['hire'];
        $cpTbl['net_profit_day'] = $params['net_profit_day'];
        $cpTbl['ilohc'] = $params['ilohc'];
        $cpTbl['c_v_e'] = $params['c_v_e'];
        $cpTbl['com_fee'] = $params['com_fee'];
        $cpTbl['charterer'] = $params['charterer'];
        $cpTbl['tel_number'] = $params['tel_number'];
        $cpTbl['Remarks'] = $params['remark'];

        if($request->hasFile('attachment')) {
            $cpTbl['is_attachment'] = 1;
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $name = date('Ymd_His') . '_TC_' . Str::random(10). '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/contract/', $name);
            $cpTbl['attachment_url'] = url() . '/contract/' . $name;
        }

        $cpTbl->save();

        return redirect()->back();
        
    }

    public function saveCargoList(Request $request) {
        $params = $request->all();
        $cargo_ids = $params['id'];
		foreach($cargo_ids as $key => $item) {
			$cargoTbl = new Cargo();
			if($item != '' && $item != null) {
				$cargoTbl = Cargo::find($item);
			}

			if($params['name'][$key] != "") {
				$cargoTbl['name'] = $params['name'][$key];

				$cargoTbl->save();
			}
		}

        return Cargo::all();
    }

    public function savePortList(Request $request) {
        $params = $request->all();
        $port_ids = $params['id'];
		foreach($port_ids as $key => $item) {
			$portTbl = new ShipPort();
			if($item != '' && $item != null) {
				$portTbl = ShipPort::find($item);
			}

			if($params['Port_Cn'][$key] != "" || $params['Port_En'][$key] != "") {
				$portTbl['Port_Cn'] = $params['Port_Cn'][$key];
                $portTbl['Port_En'] = $params['Port_En'][$key];
				$portTbl->save();
			}
		}

        return ShipPort::all();
    }

    public function newsTemaPage(Request $request) {
        $keyword = $request->get('keyword');
        $temaList = NewsTema::getNewsTemaList($keyword);

        $admin = Session::get('admin');
        if ($admin > 0) {
            $GLOBALS['selMenu'] = 24;
            $GLOBALS['submenu'] = 0;

            return view('notice.news.news_admin', array('list'=>$temaList, 'keyword'=>$keyword));
        } else {
            $GLOBALS['selMenu'] = 31;
            $GLOBALS['submenu'] = 0;
            return view('notice.news.news_browser', array('list'=>$temaList, 'keyword'=>$keyword));
        }
    }

    public function saveNewNewsTema(Request $request) {
        $temaName = $request->get('name');
        $temaDescript = $request->get('descript');
        $temaId = $request->get('temaId');

        if($temaId) {
            $list = NewsTema::where('id', '<>', $temaId)->where('tema', $temaName)->get();
            if(count($list) > 0)
                return -1;  // 이미 같은 토론마당이 존재함

            $tema = NewsTema::find($temaId);
            $tema->tema = $temaName;
            $tema->descript = $temaDescript;
            $tema->save();
        } else {
            $list = NewsTema::all()->where('tema', $temaName);
            if(count($list) > 0)
                return -1;  // 이미 같은 토론마당이 존재함

            $tema = new NewsTema;
            $tema->tema = $temaName;
            $tema->descript = $temaDescript;
            $tema->save();
        }
        return 1;  // 성공
    }

    public function getNewsTemaInfo(Request $request) {
        $temaId = $request->get('temaId');
        $tema = NewsTema::find($temaId);
        return $tema;
    }

    public function deleteNewsTema(Request $request) {
        $temaId = $request->get('temaId');
        $tema = NewsTema::find($temaId);
        $tema->delete();
        return 1;
    }

    //---------------      전자게시판   ---------------------
    public function recommendNews(Request $request){
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = 0;

        $keyword = $request->get('keyword');
        $temaList = NewsTema::getNewsTemaList($keyword);

        return view('notice.news.news_browser', array('list'=>$temaList, 'keyword'=>$keyword));
    }

    // 주제에 따르는 기사목록을 귀환한다.
    public function showNewsListForTema(Request $request) {

        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $temaId = $request->get('temaId'); // 주제아이디

        $tema = NewsTema::find($temaId);
        $list = News::getNewsListForTema($temaId);

        return view('business.news.news_viewer', array('tema'=>$tema, 'list'=>$list));
    }

    public function showNewsDetail($newsId) {

        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $news = News::find($newsId);
        if(empty($news)) {
            abort('404');
            exit(0);
        }

        if((Auth::user()->isAdmin == 1)) {
            return redirect('/business/createNewsPage/'.$newsId.'.htm');
        }

        // 열람수를 증가시킨다.
        $news = News::find($newsId);
        $news['view'] = $news['view']  + 1;
        $news->save();

        $created = $news['create_at'];
        $created = Util::convertDate($created);

        $user = $news->newsUser()->find($news['userId']);
        $news['creator'] = $user['name'];
        $news['create_date'] = $created;

        // 열람리력을 보관
        $history = new NewsHistory();
        $history['newsId'] = $newsId;
        $history['userId'] = $user['id'];
        $history->save();

        // 이전에 추천을 했는가를 검사한다.
        $isRecommend = NewsRecommend::where('newsId', $newsId)->where('userId', $user['id'])->get();
        if(count($isRecommend) > 0)
            $news['isRecommend'] = 1;
        else
            $news['isRecommend'] = 0;

        $temaId = $news['temaId'];
        $tema = NewsTema::find($temaId);

        $page = 0;
        $list = NewsResponse::where('newsId', $newsId)->orderBy('id', 'desc')->get()->forPage($page, 10);
        foreach($list as $response) {
            $user = $response->responseUser()->find($response['userId']);
            $response['user'] = $user['name'];
            $create_date = $response['create_at'];
            $response['datetime'] = Util::caclulateTimeInteval($create_date);
        }

        return view('business.news.news_detail', array('news'=>$news, 'tema'=>$tema, 'list'=>$list));
    }

    public function updateNewsPage($newsId) {
        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $news = News::find($newsId);
        if(empty($news)) {
            abort('404');
            exit(0);
        }

        $temaId = $news->temaId;
        $tema = NewsTema::find($temaId);

        return view('business.news.news_write', array('tema'=>$tema, 'news'=>$news));
    }


    public function newsRecommend(Request $request) {
        $newsId = $request->get('newsId');

        $user = Auth::user();
        $userId = $user['id'];

        $isRecommend = NewsRecommend::where('newsId', $newsId)->where('userId', $user['id'])->get();
        if(count($isRecommend) > 0)
            return -1;

        $recommend = new NewsRecommend();
        $recommend['newsId'] = $newsId;
        $recommend['userId'] = $userId;
        $recommend->save();

        $news = News::find($newsId);
        $news['recommend'] = $news['recommend'] + 1;
        $news->save();

        return 1;
    }

    public function newsResponse(Request $request) {

        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $newsId = $request->get('newsId');
        $content = $request->get('message');

        $user = Auth::user();
        $userId = $user['id'];

        $response = new NewsResponse();
        $response['userId'] = $userId;
        $response['newsId'] = $newsId;
        $response['content'] = $content;

        $response->save();

        $news = News::find($newsId);
        $news['response'] = $news['response'] + 1;
        $news->save();

        $page = 0;
        $list = NewsResponse::where('newsId', $newsId)->orderBy('id', 'desc')->get()->forPage($page, 10);
        foreach($list as $response) {
            $user = $response->responseUser()->find($response['userId']);
            $response['user'] = $user['name'];
            $create_date = $response['create_at'];
            $response['datetime'] = Util::caclulateTimeInteval($create_date);
        }

        return view('business.news.news_response', array('list'=>$list));
    }

    public function createNewsPage(Request $request) {

        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $temaId = $request->get('tema');
        $tema = NewsTema::find($temaId);

        return view('business.news.news_write', array('tema'=>$tema));
    }

    public function createNewsContent(Request $request) {
        $temaId = $request->get('temaId');
        $newsId = $request->get('newsId');
        $title = $request->get('newstitle');
        $content = $request->get('newscontent');

        $file = $request->file('addfile');
        $filename = '';
        $originFileName = '';
        if(isset($file))
        {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/news'), $filename);
            $originFileName = $file->getClientOriginalName();
        }

        if($newsId) {
            $news = News::find($newsId);
        } else {
            $user = Auth::user();
            $userId = $user['id'];
            $news = new News();
            $news->userId = $userId;
        }

        $news->temaId = $temaId;
        $news->title = $title;
        $news->content = $content;
        if($request->deletedFile == 1) {
            $news->filePath = '';
            $news->fileName = '';
        }
        if(!empty($filename)) {
            $news->filePath = $filename;
            $news->fileName = $originFileName;
        }

        $news->save();

        return redirect('business/showNewsListForTema?temaId='.$temaId);
    }

    // ---------------     기관정보     --------------------
    public function showTotalMemberList(Request $request) {

        Util::getMenuInfo($request);

        $shipId = $request->get('ship');
        $unitId = $request->get('unit');
        $posId = $request->get('pos');
        $dutyId = $request->get('duty');
        $isParty = $request->get('party');
        $status = $request->get('status');
        $username = $request->get('username');
        $address = $request->get('address');
        $phone = $request->get('phone');
        $birthStart = $request->get('start-date');
        $birthEnd = $request->get('end-date');
        $entryStart = $request->get('entry-start');
        $entryEnd = $request->get('entry-end');
        $cardNum = $request->get('cardNum');

        $units = Unit::query()->orderBy('orderkey')->orderBy('id')->get();
        $unitPos = Post::select('id', 'title')->orderBy('orderNum')->get();

        $ships = ShipRegister::getShipListOnlyOrigin();
        $shipDuty = ShipPosition::select('id', 'Duty')->orderBy('id')->get();

        $idList = UserInfo::totalMemberIDList($unitId, $posId, $shipId, $dutyId, $isParty, $username,
            $address, $phone, $status, $birthStart, $birthEnd, $entryStart, $entryEnd, $cardNum);
        $list = UserInfo::getMemberProfilelist($idList);

        $enterprise = Unit::where('parentId', 0)->first();
        $unitList = Unit::where('parentId', $enterprise['id'])->orderBy('orderkey')->orderBy('id')->get();
        $totalUnitList = array();

        $struct = array();
        $members = UserInfo::select('tb_users.id', 'tb_users.realname', 'tb_pos.title')
            ->join('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')
            ->where('tb_users.unit', $enterprise['id'])
            ->orderBy('tb_pos.orderNum')
            ->get();
        $struct['unit'] = $enterprise['title'];
        $struct['members'] = $members;
        $totalUnitList[] = $struct;

        foreach($unitList as $unit) {
            $struct = array();
            $members = UserInfo::select('tb_users.id', 'tb_users.realname', 'tb_pos.title')
                ->join('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')
                ->where('tb_users.unit', $unit['id'])
                ->orderBy('tb_pos.orderNum')
                ->get();
            $struct['unit'] = $unit['title'];
            $struct['members'] = $members;
            $struct['type'] = 1;

            $subUnits = Unit::where('parentId', $unit['id'])->get();
            $subStruct = array();
            foreach ($subUnits as $subUnit) {
                $members = UserInfo::select('tb_users.id', 'tb_users.realname', 'tb_pos.title')
                    ->join('tb_pos', 'tb_users.pos', '=', 'tb_pos.id')
                    ->where('tb_users.unit', $subUnit['id'])
                    ->orderBy('tb_pos.orderNum')
                    ->get();

                $subStruct['unit'] = $unit['title'];
                $subStruct['members'] = $members;
            }

            $struct['subStruct'] = $subStruct;
            $totalUnitList[] = $struct;
        }

        foreach($ships as $ship) {
            $members = ShipMember::select('tb_ship_member.id', 'tb_ship_duty.Duty', 'tb_ship_member.realname')
                ->leftJoin('tb_ship_duty', 'tb_ship_duty.id', '=', 'tb_ship_member.pos')
                ->where('tb_ship_member.ShipID_organization', $ship['id'])
                ->orderBy('tb_ship_duty.id')
                ->get();

            $struct['unit'] = $ship['name'];
            $struct['members'] = $members;
            $struct['type'] = 2;
            $totalUnitList[] = $struct;
        }

        return view('business.allmember',
            [   'units' =>  $units,
                'unitPos' => $unitPos,
                'ships' =>  $ships,
                'shipDuty' => $shipDuty,
                'list'  =>  $list,
                'shipId'=>  $shipId,
                'unitId'=>  $unitId,
                'posId' => $posId,
                'dutyId' => $dutyId,
                'party' =>  $isParty,
                'username' => $username,
                'address'   => $address,
                'phone'     => $phone,
                'status'=>  $status,
                'birthStart' => $birthStart,
                'birthEnd'  => $birthEnd,
                'entryStart'    => $entryStart,
                'entryEnd' => $entryEnd,
                'cardNum' => $cardNum,
                'originStruct'=> json_encode($totalUnitList)
            ]);
    }

    // ---------------     일정관리     --------------------
    public function personSchedule(Request $request) {
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = 0;

        $user = Auth::user();
        $selDate = $request->get('selDate');
        if(empty($selDate)) {
            $today = new \DateTime(date('Y-m-d'));
            $week = date('w');
            date_modify($today, "-$week days");
            $selDate = $today->format("Y-m-d");
        }

        $weekDate = new \DateTime($selDate);
        date_modify($weekDate, "+6 days");
        $endDate = $weekDate->format('Y-m-d');

        //부서조직구조별 인원보기 현시자료처리부분
        $str = Util::loadMember('checkbox');

        $list = Schedule::whereBetween('startDate', [$selDate, $endDate])
            ->where('attend_user', 'like', '%,'.$user->id.',%')->orderBy('startDate')->get();
        foreach($list as $schedule) {
            $schedule['attend_user'] = $this->getUserNames($schedule['attend_user']);
            if($schedule['userId'] == $user['id'])
                $schedule['auth'] = 1;
            else
                $schedule['auth'] = 0;
        }

        $date = new \DateTime($selDate);
        $selYear = $date->format('Y');
        $selMonth = $date->format('n');

        $prev = $date->modify("-7 days")->format('Y-m-d');
        $next = $date->modify("+14 days")->format('Y-m-d');

        $firstDate = new \DateTime($selDate);
        $dateStr = $firstDate->format('Y年 n月 j日').' ~ ';
        $weekDate = new \DateTime($endDate);
        if(($selYear == $weekDate->format('Y')) && ($selMonth == $weekDate->format('n')))
            $dateStr .= $weekDate->format('j日');
        else if(($selYear == $weekDate->format('Y')))
            $dateStr .= $weekDate->format('n月 j日');
        else
            $dateStr .= $weekDate->format('Y年 n月 j日');

        return view('business.schedule', ['list'=>$list, 'result'=>$str, 'firstDate'=>$selDate, 'titleDate'=>$dateStr, 'prev'=>$prev, 'next'=>$next]);
    }

    public function getScheduleInfo(Request $request) {
        $schId = $request->get('schId');
        $userId = Auth::user()->id;

        $schedule = Schedule::find($schId);
        if($schedule->userId != $userId)
            return response()->json(['status'=>'error']);

        $schedule->attend_user = explode(',', $schedule->attend_user);
        return response()->json($schedule);
    }

    public function deleteScheduleInfo(Request $request) {
        $schId = $request->get('schId');
        $sDate = $request->get('start');
        $eDate = $request->get('end');

        $userId = Auth::user()->id;

        $schedule = Schedule::find($schId);
        if($schedule->userId != $userId)
            return response()->json(['status'=>'error']);

        $old_title = $schedule['title'];
        $old_attend = $schedule['attend_user'];
        $old_time = $schedule['startTime'];

        Schedule::where('userId', $userId)
            ->where('title', $old_title)
            ->where('attend_user', $old_attend)
            ->where('startDate', '>=' ,$sDate)
            ->where('startDate', '<=' ,$eDate)
            ->where('startTime', $old_time)
            ->delete();

        return response()->json(['status'=>'success']);
    }

    public function updateSchedule(Request $request) {

        $schId = $request->get('schId');
        $title = $request->get('title');
        $descript = $request->get('descript');
        $sDate = $request->get('start');
        $eDate = $request->get('end');
        $startTime = $request->get('startTime');
        $attendUser = $request->get('attend_user');

        $userId = Auth::user()->id;

        if(!empty($schId)) {
            $schedule = Schedule::find($schId);
            $old_title = $schedule['title'];
            $old_attend = $schedule['attend_user'];
            $old_time = $schedule['startTime'];

            $startDate = new \DateTime($sDate);
            $endDate = new \DateTime($eDate);
            while(1) {
                $dateStr = $startDate->format('Y-m-d');
                $isBreak = false;
                if ($startDate >= $endDate)
                    $isBreak = true;

                $schedule = Schedule::where('userId', $userId)
                    ->where('title', $old_title)
                    ->where('attend_user', $old_attend)
                    ->where('startDate', $dateStr)
                    ->where('startTime', $old_time)
                    ->first();
                if (empty($schedule))
                    $schedule = new Schedule();

                $schedule['userId'] = $userId;
                $schedule['startDate'] = $dateStr;
                $schedule['title'] = $title;
                $schedule['descript'] = $descript;
                $schedule['startTime'] = $startTime;
                $schedule['attend_user'] = ',' . implode(',', $attendUser) . ',';
                $schedule->save();

                date_modify($startDate, "+1 days");
                if ($isBreak)
                    break;
            }

            $result['status'] = 'success';

            return response()->json($result);
        }

        $startDate = new \DateTime($sDate);
        $endDate = new \DateTime($eDate);
        while(1) {
            $dateStr = $startDate->format('Y-m-d');
            $isBreak = false;
            if($startDate >= $endDate)
                $isBreak = true;

            $schedule = new Schedule();
            $schedule['userId'] = $userId;
            $schedule['startDate'] = $dateStr;
            $schedule['title'] = $title;
            $schedule['descript'] = $descript;
            $schedule['startTime'] = $startTime;
            $schedule['attend_user'] = ','.implode(',', $attendUser).',';
            $schedule->save();

            date_modify($startDate, "+1 days");
            if($isBreak)
                break;
        }

        $result['status'] = 'success';

        return response()->json($result);
    }

    public function checkPersonSchedule() {
        $user = Auth::user();

        $today = date('Y-m-d');

        $list = Schedule::where('startDate', $today)
            ->where('attend_user', 'like', '%,'.$user->id.',%')
            ->get();

        $currentSchedule = [];
        $timeStr = date('Y-m-d H:i').':00';
        $currentStamp = date_timestamp_get(date_create($timeStr));
        foreach($list as $schedule) {
            $scheduleTime = $schedule['startDate'].' '.$schedule['startTime'];

            $datetime = date_create_from_format('Y-m-d H:i:s', $scheduleTime);
            $stampTime = date_timestamp_get($datetime);
            $diff = $stampTime - $currentStamp;

            if(($diff < 900) && ($diff > 59) && ($schedule['isAlert'] == 0)) {
                $schedule['isAlert'] = 1;
                $schedule->save();

                $currentSchedule[] = $schedule;
            } else if($diff == 0)
                $currentSchedule[] = $schedule;
        }

        return response()->json($currentSchedule);
    }

    //ID목록으로从 이름목록을 얻기
    private function getUserNames($idList)
    {
        $idList = explode(',', $idList);
        $count = 0;
        foreach ($idList as $userid) {
            if(empty($userid))
                continue;
            $count++;
        }

        $user = Auth::user();

        $namestr = $user->name;
        if($count > 1)
            $namestr .=  ' 外 '.($count - 1).'名';
        return $namestr;
    }

    // -------------------      계획관리   ----------------------

    // 관리자페지에서 계획관리
    public function mainPlan(Request $request)
    {
        Util::getMenuInfo($request);

        $plan_name = $request->get('name');
        if ( $plan_name == null )
            $main_plans = MainPlan::orderBy('name')->paginate()->setPath('');
        else {
            $main_plans = MainPlan::where('name', 'like', '%'.$plan_name.'%')->orderBy('name')->paginate()->setPath('');
            $main_plans->appends(['name'=>$plan_name]);
        }

        return view('business.plan.mainplan', ['main_plans'=>$main_plans, 'name'=>$plan_name]);
    }

    //계획项目변경요청처리
    public function planUpdate(Request $request)
    {
        $admin = Session::get('admin');

        if ($admin > 0) {
            $plan = MainPlan::where('name', '=', $request->get('name'))->
            where('descript', '=', $request->get('desc'))->
            where('startDate', '=', $request->get('start'))->
            where('endDate', '=', $request->get('end'))->get();
            if ($plan->count() > 0) {
                return -1;
            }
            $id = $request->get('id');
            $plan = MainPlan::find($id);
            $plan['name'] = $request->get('name');
            $plan['descript'] = $request->get('desc');
            $plan['startDate'] = $request->get('start');
            $plan['endDate'] = $request->get('end');
            $plan->save();
            return 1;
        }
        return 0;
    }

    //계획项目添加요청처리
    public function planAdd(Request $request)
    {
        $admin = Session::get('admin');

        if ($admin > 0) {
            $plan = MainPlan::where('name', '=', $request->get('name'))->
            where('descript', '=', $request->get('desc'))->
            where('startDate', '=', $request->get('start'))->
            where('endDate', '=', $request->get('end'))->get();
            if ($plan->count() > 0) {
                return -1;
            }
            $id = MainPlan::max('id') +1;
            $plan = new MainPlan;
            $plan['id'] = $id;
            $plan['name'] = $request->get('name');
            $plan['descript'] = $request->get('desc');
            $plan['startDate'] = $request->get('start');
            $plan['endDate'] = $request->get('end');
            $plan->save();
            return 1;
        }
        return 0;
    }

    //계획项目삭제요청처리
    public function planDelete(Request $request)
    {
        $id = $request->get('id');
        $rest=MainPlan::find($id);
        $rest->delete();
        return 1;
    }

    // ----------------     출퇴근관리   --------------------
    public function EntryAndExit(Request $request)
    {
        $GLOBALS['selMenu'] = 25;
        $GLOBALS['submenu'] = 0;

        $admin = Session::get('admin');

        if ($admin > 0) {
            $total_count = AttendTime::max('id');
            if ($total_count == 0) {
                $attend_time = 0;
                $start_time = date_parse('00:00:00');
                $end_time = date_parse('00:00:00');
            } else {
                $attend_time = AttendTime::find($total_count);
                $start_time = date_parse($attend_time['start']);
                $end_time = date_parse($attend_time['end']);
            }
            if (isset($request['restYear']) && isset($request['restMonth'])) {
                $day_1 = "{$request['restYear']}-{$request['restMonth']}-01";
                $day_2 = date_parse("{$request['restYear']}-{$request['restMonth']}-31");
                $juche = date_parse("{$request['restYear']}-{$request['restMonth']}-31");
            }else {
                $day_1 = date("Y-m-01");
                $day_2 = date_parse(date("Y-m-d"));
                $juche = date_parse(date('Y-m-d'));
            }
            $day_2 = $day_2['month'] + 1;
            $day_2 = date("Y-m-01", mktime(0, 0, 0, $day_2, 1, $juche['year']));

            $total_rest = AttendRest::where('day', '>=', $day_1)->where('day', '<', $day_2)->orderBy('day')->get();
            $selY = $juche['year'];
            $selM = $juche['month'];
            $juche = $juche['year'] - 1911;
            $cur_month = "{$selY} 年  {$selM} 月";
            $rest_state['1'] = "星期日";
            $rest_state['2'] = "节日";
            $rest_state['3'] = "其他";
            if ($request->get('page_id') == null) {
                $page_id = 1;
            } else {
                $page_id = 2;
            }
            return view('business.attend.entryandexit', array('total_count' => $total_count,
                'starttime' => $start_time, 'endtime' => $end_time,
                'total_rest' => $total_rest, 'cur_month' => $cur_month, 'rest_state' => $rest_state,
                'page_id' => $page_id, 'selY' => $selY, 'selM' => $selM));
        } else {
            return redirect('business/showTotalMemberList');
        }

    }

    public function restUpdate(Request $request)
    {
        $id = $request->get('id');
        $rest = AttendRest::find($id);
        $rest['day'] = $request->get('rest_day');
        $rest['state'] = $request->get('rest_state');
        $rest['descript'] = $request->get('rest_desc');

        $isExist = AttendRest::where('day', '=', $rest['day'])
            ->where('descript', $rest['descript'])
            ->where('state', $rest['state'])
            ->count();
        if($isExist) {
            return -1;
        }

        $rest->save();
        return 1;
    }

    public function restAdd(Request $request)
    {
        $rest = new AttendRest;
        $rest['day'] = $request->get('rest_day');

        $isExist = AttendRest::where('day', $rest['day'])->count();
        if($isExist) {
            return -1;
        }

        $rest['state'] = $request->get('rest_state');
        $rest['descript'] = $request->get('rest_desc');
        $rest->save();
        return 1;
    }

    public function restDelete(Request $request)
    {
        $id = $request->get('id');
        $rest = AttendRest::find($id);
        $rest->delete();
        return 1;
    }

    public function timeUpdate(Request $request)
    {
        $attend_time = new AttendTime;
        $attend_time->id = AttendTime::max('id') + 1;
        $attend_time->start = date("H:i:s", mktime($request->startH, $request->startM, 0));
        $attend_time->end = date("H:i:s", mktime($request->endH, $request->endM, 0));
        $attend_time->create_at = date("Y-m-d H:i:s");
        $attend_time->save();
        return 1;
    }

    //개인출근일보登记첫페지
    public function personnelRegister(Request $request)
    {
        Util::getMenuInfo($request);

        $curtime = microtime(true) * 1000;
        $result = new \stdClass();
        $result->year = date('Y');
        $result->month = date('n');
        $result->day = date('d');
        $result->hour = date('H');
        $result->minute = date('i');
        $result->second = date('s');
        $result->half = date('A');

        //현재날자와 가입한 리용자에 대한처리
        //현재 가입한 리용자의 식별자
        $user = Auth::user();
        $today = date("Y-m-d");

//        $curtime = microtime(true) * 1000;
        $currentTime =  new \DateTime(date('Y-m-d H:m:s'));
        $curtime = date_timestamp_get($currentTime) * 1000;

        $attendance = AttendUser::where('regDay', $today)->where('userId', $user->id)->first();
        if ($attendance == null) {
            //현재 가입한 리용자의 부서식별자
            if (!$this->isWorkingDay()) {
                return View('business.attend.personnelregister', array('curtime' => $curtime, 'firsttime' => $result, 'registeredstatus' => 0, 'memo' => "", 'status' => 0));//명절일,휴식일
            }
            if (!$this->isValidateOfTime()) {
                return View('business.attend.personnelregister', array('curtime' => $curtime, 'firsttime' => $result, 'registeredstatus' => 1, 'memo' => "", 'status' => 1));//지각생
            }
            return View('business.attend.personnelregister', array('curtime' => $curtime, 'firsttime' => $result, 'registeredstatus' => 1, 'memo' => "", 'status' => 2));//정상출근
        } else {
            $memo = $attendance->memo;
            return View('business.attend.personnelregister', array('curtime' => $curtime, 'firsttime' => $result, 'registeredstatus' => 0, 'memo' => $memo, 'status' => 4));
        }
    }

    //개인출근登记정보보관
    public function savePersonRegisterInfo(Request $request)
    {
        $curtime = date("H:i:s");
        $today = date("Y-m-d");
        //현재 가입한 리용자의 식별자
        $user = Auth::user();
        $attendance = AttendUser::where('regDay', $today)->where('userId', $user->id)->get();
        if (count($attendance) > 0) {
            return 0; // 이미 登记하였음
        }

        $attendance = new AttendUser;

        if ($this->isWorkingDay())
            $attendance->regDay = $today;
        else
            return -1;

        if ($this->isValidateOfTime())
            $attendance->statusId = 1;// 정상출근
        else {
            $attendance->regTime = $curtime;
            $attendance->statusId = 2; // 지 각
        }

        $attendance->userId = $user['id'];
        $attendance->memo = $request['memo'];
        $attendance->creator = $user['id'];
        $attendance->save();

        return 1;
    }

    //오늘이 공작일인가를 검사한다.
    private function isWorkingDay($selDate = null)
    {
        if(is_null($selDate))
            $selDate = date("Y-m-d");
        $restList = AttendRest::where('day', $selDate)->get();
        if(count($restList) > 0)
            return false;

        return true;
    }

    //현재시간이 로동시간에 포함되는가를 검사한다.//지각생판정
    public function isValidateOfTime()
    {
        $id = AttendTime::max('id');
        $attendtime = AttendTime::find($id);
        $attend = date('Y-m-d').' '. $attendtime->start;

        $time1 = date_timestamp_get(date_create());
        $time2 = date_timestamp_get(date_create($attend));

        if ( $time1 < $time2) {
            return true;
        }
        return false;
    }

    //선원출근일보登记첫페지(현재날자, 모든 배에 대하여)
    public function shipMemberRegister(Request $request)
    {
        Util::getMenuInfo($request);

        $shipId = $request->get('shipId');
        $attendDate = $request->get('date');
        if(empty($attendDate))
            $attendDate = date('Y-m-d');
        $pos = $request->get('pos');
        $memberName = $request->get('name');

        $shipList = ShipRegister::getShipListByOrigin();//全部 배목록얻기
        $attendType = AttendType::all();
        $posList = ShipPosition::all();

        $typeHtml = '<select class="form-control">';
        foreach ($attendType as $type)
            $typeHtml .= '<option value="' .$type['id']. '">' .$type['name'] .'</option>';
        $typeHtml .= '</select>';

        //선원출근표에서 오늘 登记된 목록을 얻는다.
        $attendMemberList = AttendShip::getAttendShipMemberListByDate($attendDate, $shipId, $pos, $memberName);
        $completeCount = 0;
        foreach ($attendMemberList as $member) {
            if(isset($member->statusId) && ($member->statusId != 4))
                $completeCount++;
        }

        $allRegistered = 0;
        if(count($attendMemberList) == $completeCount)
            $allRegistered = 1;
        $isRest = 0;
        if(!$this->isWorkingDay($attendDate)) {
            $isRest = 1;
            $allRegistered = 1;
        }

        return View('business.attend.ship_attend_register',
            [   'attendUsers'   =>  $attendMemberList,
                'allRegistered' =>  $allRegistered,
                'shipList'      =>  $shipList,
                'posList'       =>  $posList,
                'attendType'    =>  $attendType,
                'typeHtml'      =>  $typeHtml,
                'isRest'        =>  $isRest,
                'date'          =>  $attendDate,
                'shipId'        =>  $shipId,
                'posId'         =>  $pos,
                'memberName'    =>  $memberName,
            ]);
    }

    //선원출근일보보관
    public function registerShipMemberAttend(Request $request)
    {
        $attendStr = $request->get('attend');
        $selDate = $request->get('selDate');
        if (empty($attendStr))
            return -1;

        $user = Auth::user();

        $valueList = explode(',',$attendStr);
        $valueCount = count($valueList) / 3;
        for ($i = 0; $i < $valueCount; $i++) {
            $memberId = $valueList[$i * 3];
            $type = $valueList[$i * 3 + 1];
            $memo = $valueList[$i * 3 + 2];
            $isExist = AttendShip::where('memberId', $memberId)->where('regDay', $selDate)->first();
            if($isExist) {
                $attend = $isExist;
                $attend['statusId'] = $type;
                $attend['memo'] = $memo;
                $attend['creator'] = $user['id'];
                $attend->save();
            } else {
                $attend = new AttendShip();
                $attend['memberId'] = $memberId;
                $attend['regDay'] = $selDate;
                $attend['statusId'] = $type;
                $attend['memo'] = $memo;
                $attend['creator'] = $user['id'];
                $attend->save();
            }
        }
        return;
    }

    public function registerShipAllMember(Request $request) {
        $selDate = $request->get('selDate');
        $shipId = $request->get('shipId');
        $attendType = $request->get('status');
        $memo = $request->get('memo');

        $user = Auth::user();
        $memberList = ShipMember::where('ShipId', $shipId)->where('RegStatus', 1)->get();
        foreach($memberList as $member) {
            $isExist = AttendShip::where('memberId', $member['id'])->where('regDay', $selDate)->first();
            if($isExist) {
                $attend = $isExist;
                $attend['statusId'] = $attendType;
                $attend['memo'] = $memo;
                $attend['creator'] = $user['id'];
                $attend->save();
            } else {
                $attend = new AttendShip();
                $attend['memberId'] = $member['id'];
                $attend['regDay'] = $selDate;
                $attend['statusId'] = $attendType;
                $attend['memo'] = $memo;
                $attend['creator'] = $user['id'];
                $attend->save();
            }
        }

        return;
    }

    //부서출근일보登记
    public function unitAttendPage(Request $request)
    {
        Util::getMenuInfo($request);

        $attendDate = $request->get('selDate');
        if(is_null($attendDate))
            $attendDate = date('Y-m-d');

        $page = $request->get('page');
        $user = Auth::user();
        $unit = UserInfo::find($user->id);
        $unitId = $unit['unit'];
        $unitName = $unit['unitName']['title'];

        if($user->isAdmin == 0)
            $paginate = UserInfo::where('unit', $unitId)->paginate()->setPath(''); // 리용자의 부서에 속한 직원목록을 얻는다.
        else
            $paginate = UserInfo::select('tb_users.*')->paginate('100')->setPath('');
        $attendType = AttendType::all();

        $typeHtml = '<select class="form-control">';
        foreach ($attendType as $type)
            $typeHtml .= '<option value="' .$type['id']. '">' .$type['name'] .'</option>';
        $typeHtml .= '</select>';

        //직원출근표에서 오늘 登记된 목록을 얻는다.
        if($user->isAdmin == 0)
            $attendMemberList = AttendUser::getAttendMemberListByDate($attendDate, $unitId, $page);
        else
            $attendMemberList = AttendUser::getAttendMemberListByDate($attendDate, null, $page);
        $completeCount = 0;
        foreach ($attendMemberList as $member) {
            if(isset($member->statusId) && ($member->statusId != 4))
                $completeCount++;
        }

        $allRegistered = 0;
        if(count($attendMemberList) == $completeCount)
            $allRegistered = 1;

        $isRest = 0;
        if (!$this->isWorkingDay($attendDate)) {
            $isRest = 1;
            $allRegistered = 1;
        }

        return View('business.attend.unit_attend_register',
            [   'attendUsers'   =>  $attendMemberList,
                'paginate'      =>  $paginate,
                'allRegistered' =>  $allRegistered,
                'attendType'    =>  $attendType,
                'isRest'        =>  $isRest,
                'date'          =>  $attendDate,
                'unitName'      =>  $unitName,
                'typeHtml'     =>  $typeHtml
            ]);
    }

    //부서출근일보登记
    public function unitAttendDayPage(Request $request)
    {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 41;

        $attendDate = $request->get('selDate');
        if(is_null($attendDate))
            $attendDate = date('Y-m-d');

        $page = $request->get('page');
        $unitId = $request->get('unit');
        $unit = Unit::find($unitId);
        $unitName = $unit['title'];

        $paginate = UserInfo::where('unit', $unitId)->paginate()->setPath(''); // 리용자의 부서에 속한 직원목록을 얻는다.

        //직원출근표에서 오늘 登记된 목록을 얻는다.
        $attendMemberList = AttendUser::getAttendMemberListByDate($attendDate, $unitId, $page);

        $isRest = 0;
        if (!$this->isWorkingDay($attendDate)) {
            $isRest = 1;
            $allRegistered = 1;
        }

        return View('business.attend.unit_attend_browser',
            [   'attendUsers'   =>  $attendMemberList,
                'paginate'      =>  $paginate,
                'date'          =>  $attendDate,
                'isRest'        =>  $isRest,
                'unitName'      =>  $unitName,
                'unitId'        =>  $unitId,
            ]);
    }

    //부서성원들의 출근정형을 보관한다.
    public function registerUnitMemberAttend(Request $request)
    {
        $attendStr = $request->get('attend');
        $selDate = $request->get('selDate');
        if (empty($attendStr))
            return -1;

        $user = Auth::user();
        $valueList = explode(',',$attendStr);
        $valueCount = count($valueList) / 3;
        for ($i = 0; $i < $valueCount; $i++) {
            $memberId = $valueList[$i * 3];
            $type = $valueList[$i * 3 + 1];
            $memo = $valueList[$i * 3 + 2];

            $isExist = AttendUser::where('userId', $memberId)->where('regDay', $selDate)->first();
            if($isExist) {
                $attend = $isExist;
            } else {
                $attend = new AttendUser();
                $attend['userId'] = $memberId;
            }

            $attend['statusId'] = $type;
            $attend['regDay'] = $selDate;
            $attend['regTime'] = date('H:i:s');
            $attend['memo'] = $memo;
            $attend['creator'] = $user['id'];
            $attend->save();
        }

        return;
    }

    /*부서월출근열람
     *보기에 넘겨주어야 할자료
     *매성원별로 이름, 월수, 휴식일수, 법적가동일수, 결근수, 구체화된 자료, 지각, 조퇴수
     *
     */
    public function unitAttendMonthShow(Request $request)
    {
        Util::getMenuInfo($request);

        $page = is_null($request->get('page')) ? 1 : $request->get('page');

        //매달의 월일수계산
        $year = is_null($request->get('selYear')) ? date('Y') : $request->get('selYear');
        $month = is_null($request->get('selMonth')) ? date('n') : $request->get('selMonth');
        $days = Util::getDaysOfMonth($year, $month);

        //휴식일수 계산
        $day_1 = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)); // 매월의 시작날자
        $day_2 = date('Y-m-d', mktime(0, 0, 0, $month, $days, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $day_1)->where('day', '<=', $day_2)->get()->count(); // 매월의 휴식일수

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if(($year == date('Y')) && ($month == date('n'))){
            $work_days = date('j') * 1 - $total_rest;
        }

        //현재 가입한 리용자가 소속된 부서성원들의 목록을 얻는다.
        $user = Auth::user();
        $userInfo = UserInfo::find($user->id);
        $unitId = $userInfo['unit'];
        $unitName = $userInfo['unitName']['title'];

        $idStr = UserInfo::getUserListByUnit($unitId, $page);
        $memberList = AttendUser::getAttendDaysOfMonthByAttendType($idStr, $day_1, $day_2);
        $pageCount = UserInfo::countUserListByUnit($unitId);

        $list = array();
        $totl_absen = 0;
        $totl_attend = 0;

        $typeList = AttendType::all();
        $attendMember = array();
        if(count($memberList) > 0) {
            $attendMember['id'] = $memberList[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($memberList as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // 마지막기록에 대한 添加
        if(count($memberList) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
        }

        $list[] = $attendMember;
        $pageHtml = Util::makePaginateHtml($pageCount, $page);

        return View('business.attend.unit_month_attend',
            [   'dates'     =>  $dates,
                'unitName'  =>  $unitName,
                'year'      =>  $year,
                'month'     =>  $month,
                'list'      =>  $list,
                'typeList'  =>  $typeList,
                'pageHtml'  =>  $pageHtml,
                'page'      =>  $page
            ]);
    }

    // 직원의 월출근통계
    public function memberMonthAttend(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 43;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        $month = $request->get('month');

        $memberInfo = UserInfo::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

        $attendList = AttendUser::getMemberMonthAttend($memberId, $year, $month);
        $monthDay = Util::getDaysOfMonth($year, $month);
        $curr_day = $monthDay;
        if(($year == date('Y')) && ($month == date('n'))){
            $curr_day = date('j') * 1;
        }

        $list = [];
        for($day = 0; $day<$monthDay; $day++){
            $isRegister = 0;
            $isRest = 0;
            foreach($restList as $rest) {
                $restDay = $rest->day;
                $date = new \DateTime($restDay);
                $restDay = $date->format('j') * 1;
                if($restDay == ($day + 1)) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                    $userAttend['memo'] = $rest->descript;
                    $userAttend['rest'] = 1;
                    $list[] = $userAttend;
                    $isRest = 1;
                    break;
                }
            }

            if($isRest)
                continue;
            if(($day + 1) > $curr_day) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
                continue;
            }
            foreach($attendList as $attend) {
                $attendDay = $attend->regDay;
                $date = new \DateTime($attendDay);
                $attendDay = $date->format('j') * 1;
                if($attendDay == ($day + 1)) {
                    $isRegister = 1;
                    $userAttend['day'] = $attendDay;
                    $userAttend['name'] = $attend->name;
                    $userAttend['memo'] = $attend->memo;
                    $userAttend['rest'] = 0;
                    $list[] = $userAttend;
                    break;
                }
            }
            if($isRegister == 0) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '未确认';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
            }
        }

        //해당수표도장을 얻는다.
        $decEnv = DecEnvironment::find($memberId);
        if(isset($decEnv->signPath))
            $signPath = $decEnv->signPath;
        else
            $signPath = null;

        $days = Util::getDaysOfMonth($year, $month);

        //휴식일수 계산
        $day_1 = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)); // 매월의 시작날자
        $day_2 = date('Y-m-d', mktime(0, 0, 0, $month, $days, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $day_1)->where('day', '<=', $day_2)->get()->count(); // 매월의 휴식일수

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if(($year == date('Y')) && ($month == date('n'))){
            $work_days = date('j') * 1 - $total_rest;
        }
        $memberList = AttendUser::getAttendDaysOfMonthByAttendType($memberId, $day_1, $day_2);
        $totl_absen = 0;
        $totl_attend = 0;

        $typeList = AttendType::all();
        $attendMember = array();
        $attendMember['id'] = $memberList[0]->id;
        foreach($typeList as $attendType)
            $attendMember['type_'.$attendType['id']] = 0;
        foreach ($memberList as $member) {
            $attendMember['realName'] = $member->realname;
            $attendMember['pos'] = $member->title;
            if($member->statusId < 4)
                $totl_attend += $member->attendCount;
            else
                $totl_absen += $member->attendCount;
            $attendMember['type_'.$member->statusId] = $member->attendCount;
        }
        if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
            $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
            $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
        }
        $attendMember['absence'] = $totl_absen;
        $attendMember['attend'] = $totl_attend;

        //출근合计값을 구한다.
        $totalData = array(
            "days"  =>  $dates['days'],
            "rest"  =>  $dates['rest'],
            "work"  =>  $dates['work'],
            "attend"  =>  $attendMember['attend'],
            "absence"  =>  $attendMember['absence'],
        );
        foreach($typeList as $attendType)
            $totalData["type_{$attendType['id']}"] = $attendMember['type_'.$attendType['id']];

        return view('business.attend.member_month_attend', ['info'=>$memberInfo, 'year'=>$year, 'month'=>$month, 'monthDay'=>$monthDay, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }

    // 선원의 월출근통계
    public function shipMemberMonthAttend(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 104;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        $month = $request->get('month');

        $memberInfo = ShipMember::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
        $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

        $attendList = AttendShip::getMemberMonthAttend($memberId, $year, $month);
        $monthDay = Util::getDaysOfMonth($year, $month);
        $curr_day = $monthDay;
        if(($year == date('Y')) && ($month == date('n'))){
            $curr_day = date('j') * 1;
        }

        $list = [];
        for($day = 0; $day<$monthDay; $day++){
            $isRegister = 0;
            $isRest = 0;
            foreach($restList as $rest) {
                $restDay = $rest->day;
                $date = new \DateTime($restDay);
                $restDay = $date->format('j') * 1;
                if($restDay == ($day + 1)) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = constant("REST_TYPE_".$rest->state);;
                    $userAttend['memo'] = $rest->descript;
                    $userAttend['rest'] = 1;
                    $list[] = $userAttend;
                    $isRest = 1;
                    break;
                }
            }

            if($isRest)
                continue;
            if(($day + 1) > $curr_day) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
                continue;
            }
            foreach($attendList as $attend) {
                $attendDay = $attend->regDay;
                $date = new \DateTime($attendDay);
                $attendDay = $date->format('j') * 1;
                if($attendDay == ($day + 1)) {
                    $isRegister = 1;
                    $userAttend['day'] = $attendDay;
                    $userAttend['name'] = $attend->name;
                    $userAttend['memo'] = $attend->memo;
                    $userAttend['rest'] = 0;
                    $list[] = $userAttend;
                    break;
                }
            }
            if($isRegister == 0) {
                $userAttend['day'] = $day + 1;
                $userAttend['name'] = '未确定';
                $userAttend['memo'] = '';
                $userAttend['rest'] = 0;
                $list[] = $userAttend;
            }
        }

        if (isset($memberInfo['signPhoto']))
            $signPath = $memberInfo['signPhoto'];
        else
            $signPath = null;

        $days = Util::getDaysOfMonth($year, $month);

        //월첫일과 마지막일수 계산
        $start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, $month, $days, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if(($year == date('Y')) && ($month == date('n'))){
            $work_days = date('j') * 1 - $total_rest;
        }
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($memberId, $start, $end);
        $totl_absen = 0;
        $totl_attend = 0;

        $typeList = AttendType::all();
        $attendMember = array();
        $attendMember['id'] = $crewAttend[0]->id;
        foreach($typeList as $attendType)
            $attendMember['type_'.$attendType['id']] = 0;
        foreach ($crewAttend as $member) {
            $attendMember['realName'] = $member->realname;
            $attendMember['unitName'] = $member->name;
            $attendMember['pos'] = $member->Duty;
            $attendMember['isShip'] = 1;
            if($member->statusId < 4)
                $totl_attend += $member->attendCount;
            else
                $totl_absen += $member->attendCount;
            $attendMember['type_'.$member->statusId] = $member->attendCount;
        }
        if (($totl_attend + $totl_absen) < $work_days) {
            $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
            $totl_absen += $work_days - ($totl_attend + $totl_absen);
        }
        $attendMember['absence'] = $totl_absen;
        $attendMember['attend'] = $totl_attend;

        //출근合计값을 구한다.
        $totalData = array(
            "days"  =>  $dates['days'],
            "rest"  =>  $dates['rest'],
            "work"  =>  $dates['work'],
            "attend"  =>  $attendMember['attend'],
            "absence"  =>  $attendMember['absence'],
        );
        foreach($typeList as $attendType)
            $totalData["type_{$attendType['id']}"] = $attendMember['type_'.$attendType['id']];

        return view('business.attend.ship_member_month_attend', ['info'=>$memberInfo, 'year'=>$year, 'month'=>$month, 'monthDay'=>$monthDay, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }

    //기업소출근월보열람
    public function enterpriseMonthAttend(Request $request)
    {
        Util::getMenuInfo($request);

        //매달의 월일수계산
        $year = is_null($request->get('selYear')) ? date('Y') : $request->get('selYear');
        $month = is_null($request->get('selMonth')) ? date('n') : $request->get('selMonth');
        $days = Util::getDaysOfMonth($year, $month);

        //월첫일과 마지막일수 계산
        $start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, $month, $days, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if(($year == date('Y')) && ($month == date('n'))){
            $work_days = date('j') * 1 - $total_rest;
        }

        $shipId = $request->get('ship');
        $unitId = $request->get('unit');
        $memberName = $request->get('name');

        // 기업소에 소속된 모든 성원(직원 및 선원)들의 목록을 얻는다.
        $memberList = UserInfo::enterpriseTotalMemberList($unitId, $shipId, $memberName);

        $userStr = '';
        $crewStr = '';
        foreach($memberList as $members) {
            if($members->memberType == 1)
                $userStr = $members->idStr;
            else
                $crewStr = $members->idStr;
        }

        $userAttend = AttendUser::getAttendDaysOfMonthByAttendType($userStr, $start, $end);
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($crewStr, $start, $end);
        $typeList = AttendType::all();

        $list = array();
        $totl_absen = 0;
        $totl_attend = 0;

        // ---------  직원들의 월간 출근정형을 종합한다.  ------------
        $attendMember = array();
        if(count($userAttend) > 0) {
            $attendMember['id'] = $userAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($userAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                $attendMember['isShip'] = 0;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 0;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // 마지막기록에 대한 添加
        if(count($userAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        // ---------  선원들의 월간 출근정형을 종합한다.  ------------
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        if(count($crewAttend) > 0) {
            $attendMember['id'] = $crewAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($crewAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                $attendMember['isShip'] = 1;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 1;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // 마지막기록에 대한 添加
        if(count($crewAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        $ships = ShipRegister::getShipListOnlyOrigin();
        $units = Unit::unitFullNameList();

        return View('business.attend.enterprise_month_attend',
            [   'dates'     =>  $dates,
                'year'      =>  $year,
                'month'     =>  $month,
                'list'      =>  $list,
                'typeList'  =>  $typeList,
                'units'     =>  $units,
                'ships'     =>  $ships,
                'unitId'    =>  $unitId,
                'shipId'    =>  $shipId,
                'memberName'=>  $memberName,
            ]);
    }

    //기업소출근일보종합
    public function enterpriseDayAttend(Request $request)
    {
        Util::getMenuInfo($request);

        $selDate = is_null($request->get('selDate')) ? date('Y/m/d') : $request->get('selDate');

        //부서목록을 얻는다.
        $enterprise = Unit::where('parentId', '0')->orderBy('id')->first();
        if(is_null($enterprise))
            return;

        $units = array();
        $subUnits = Unit::where('parentId', $enterprise['id'])->get();
        $enterprise['unitType'] = 1;
        $units[] = $enterprise;
        foreach($subUnits as $unit){
            $units[] = $unit;
        }
        $typeList = AttendType::all();
        foreach($units as $unit) {
            if($unit['parentId'] == 0) {
                $memberList = UserInfo::getDirectlyUserList($unit['id']); // 직속부서의 리용자들의 ID를 반점으로 区分하여 얻는다.
                $unit['title'] = '경송선박회사';
            } else {
                $memberList = UserInfo::getUserListByUnit($unit['id']); // 해당부서의 리용자들의 ID를 반점으로 区分하여 얻는다.
            }
            $resultList = AttendUser::getAttendStateByDate($memberList, $selDate);
            $valueList = array();
            $attendCount = 0;
            $absenceCount = 0;
            $memberCount = 0;
            foreach ($typeList as $type) {
                $valueList[$type['id']] = 0;
                foreach ($resultList as $result) {
                    if ($result['statusId'] == $type['id'])
                        $valueList[$type['id']] = $result['ucount'];
                }
                if ($type['id'] < 4)
                    $attendCount += $valueList[$type['id']];
                else
                    $absenceCount += $valueList[$type['id']];
            }

            if(!empty($memberList))
                $memberCount = count(explode(',', $memberList));

            if(($attendCount + $absenceCount) < $memberCount) {
                $valueList[4] += $memberCount - ($attendCount + $absenceCount);
                $absenceCount = $memberCount - $attendCount;
            }
            $unit['unitType'] = 1;
            $unit['attend'] = $attendCount;
            $unit['absence'] = $absenceCount;
            $unit['userCount'] = $memberCount;
            $unit['valueList'] = $valueList;
        }

        $shipList = ShipRegister::getShipListOnlyOrigin();
        foreach($shipList as $ship){
            $shipMemberList = ShipMember::getMemberListByCommar($ship['RegNo']); // 해당배의 선원들의 ID를 반점으로 区分하여 얻는다.
            $resultList = AttendShip::getAttendStateByDate($shipMemberList, $selDate);
            $valueList = array();
            $attendCount = 0;
            $absenceCount = 0;
            $memberCount = 0;
            foreach ($typeList as $type) {
                $valueList[$type['id']] = 0;
                foreach ($resultList as $result) {
                    if ($result['statusId'] == $type['id'])
                        $valueList[$type['id']] = $result['ucount'];
                }
                if ($type['id'] < 4)
                    $attendCount += $valueList[$type['id']];
                else
                    $absenceCount += $valueList[$type['id']];
            }

            if(!empty($shipMemberList))
                $memberCount = count(explode(',', $shipMemberList));

            if(($attendCount + $absenceCount) < $memberCount) {
                $valueList[4] += $memberCount - ($attendCount + $absenceCount);
                $absenceCount = $memberCount - $attendCount;
            }

            $ship['title'] = $ship['name'];
            $ship['id'] = $ship['RegNo'];
            $ship['unitType'] = 0;
            $ship['attend'] = $attendCount;
            $ship['absence'] = $absenceCount;
            $ship['userCount'] = $memberCount;
            $ship['valueList'] = $valueList;

            $units[] = $ship;
        }

        $shipMemberList = ShipMember::getMemberListByCommar(); // 대기선원들을 반점으로 区分하여 얻는다.
        $resultList = AttendShip::getAttendStateByDate($shipMemberList, $selDate);
        $valueList = array();
        $attendCount = 0;
        $absenceCount = 0;
        $memberCount = 0;
        foreach ($typeList as $type) {
            $valueList[$type['id']] = 0;
            foreach ($resultList as $result) {
                if ($result['statusId'] == $type['id'])
                    $valueList[$type['id']] = $result['ucount'];
            }
            if ($type['id'] < 4)
                $attendCount += $valueList[$type['id']];
            else
                $absenceCount += $valueList[$type['id']];
        }

        if(!empty($shipMemberList))
            $memberCount = count(explode(',', $shipMemberList));

        if(($attendCount + $absenceCount) < $memberCount) {
            $valueList[4] += $memberCount - ($attendCount + $absenceCount);
            $absenceCount = $memberCount - $attendCount;
        }

        $ship = [];
        $ship['title'] = '等待海员';
        $ship['id'] = 'empty';
        $ship['unitType'] = 0;
        $ship['attend'] = $attendCount;
        $ship['absence'] = $absenceCount;
        $ship['userCount'] = $memberCount;
        $ship['valueList'] = $valueList;

        $units[] = $ship;

        return View('business.attend.enterprise_day_attend',
            [   'units'     => $units,
                'typeList'  =>  $typeList,
                'selDate'   =>  $selDate
            ]);
    }

    // 배별 선원들의 일출근정형보기
    public function shipAttendDayPage(Request $request) {

        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 44;

        $shipId = $request->get('ship');
        $attendDate = $request->get('selDate');
        if(empty($attendDate))
            $attendDate = date('Y-m-d');

        $shipNameInfo = ShipRegister::getShipFullNameByRegNo($shipId);

        //선원출근표에서 오늘 登记된 목록을 얻는다.
        $attendMemberList = AttendShip::getAttendShipMemberListByDate($attendDate, $shipId);

        $isRest = 0;
        if(!$this->isWorkingDay()) {
            $isRest = 1;
        }

        return view('business.attend.ship_attend_view',
            [   'shipName'  =>  $shipNameInfo,
                'attendMembers' =>  $attendMemberList,
                'isRest'        =>  $isRest,
                'date'          =>  $attendDate,
                'shipId'        =>  $shipId
            ]);
    }



    // ---------------------     계획보고   ---------------------
    // 개인사업보고 기본페지적재
    public function reportPerson(Request $request)
    {
        Util::getMenuInfo($request);

        $empty = false;
        $year = $request->get('year');
        $month = $request->get('month');
        $selWeek = $request->get('week');
        $today = new \DateTime();
        $curWeek = $today->format('W');

        if(empty($selWeek) && empty($month)) {
            $year =  $today->format('Y');
            $month =  $today->format('n');
            $selWeek =  $today->format('W');
            $empty = true;
        }

        $firstDay = $year.'-'.$month.'-01';

        if($month == 12)
            $endDay = $year.'-12-29 02:00:00';
        else
            $endDay = $year.'-'.($month + 1).'-01 02:00:00';

        $firstDay = new \DateTime($firstDay);
        $startWeek = $firstDay->format('W');
        $endDay = new \DateTime($endDay);
        $endWeek =$endDay->format('W');

        $date = $firstDay;
        $firstDayWeek = $firstDay->format('w');

        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = $endDay->format('w');

        //made by kchs
        if ($firstDayWeek == 0 ) {
            $endWeek = $endWeek - 1;
            if ($empty == true)
                $selWeek --;
        }

        $cur_date = Array();
        $cur_date['year'] = $year;
        $cur_date['month'] = $month;
        $cur_date['week'] = $selWeek;

        if(($endDayWeek < 4) && ($endDayWeek > 0))
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weeklist = array();
        $index = 1;
        $start_date = $date->format('Y-m-d');

        for($week = $startWeek; $week <= $endWeek; $week++) {
            if($week == $selWeek)
                $start_date = $date->format('Y-m-d');

            $startStr = $date->format('m.d');
            $date->modify("+6 day");
            $endStr = $date->format('m.d');

            $weekObj['week'] = $week;
            $weekObj['title'] = $index . '주' . ' (' . $startStr . '~' . $endStr . ')';
            $weeklist[] = $weekObj;
            $index++;
            $date->modify("+1 day");
        }

        $weekdays = array("日","月","火","水","木","金","土");
        $date = new \DateTime($start_date);
        $date -> modify("+6 day");
        $end_date = $date -> format("Y-m-d");

        $user = Auth::user();
        $today = date('Y-m-d');

        // 표에 현시할 자료项目만들기
        $all_person_plans = array();
        for ($i = 0; $i < 7; $i++) {
            $date = new \DateTime($start_date);
            $date->modify("+$i day");
            $dateStr = $date->format('Y-m-d');

            if($curWeek >= $selWeek) {
                $reportList = ReportPerson::weekReportListAndUncompleteList($user['id'], $dateStr);
                foreach ($reportList as $person_report) {
                    $person_report->selDate = $dateStr;
                    $person_report->dateStr = $date->format("n月j日").'('.$weekdays[$date->format("w")].')';
                    if($dateStr == $today)
                        $person_report->active = 1;
                    else
                        $person_report->active = 0;
                    // 현재 날자에 보고된 자료가 존재하는 경우
                    $all_person_plans[] = $person_report;
                }
            } else {
                $reportList = ReportPerson::weekReportList($user['id'], $dateStr);
                foreach ($reportList as $person_report) {
                    // 현재 날자에 보고된 자료가 존재하는 경우
                    $person_report->selDate = $dateStr;
                    $person_report->dateStr = $date->format("n月j日").'('.$weekdays[$date->format("w")].')';
                    $person_report->active = 0;

                    $all_person_plans[] = $person_report;
                }
            }

            if(count($reportList) == 0) {
                $person_report = new \stdClass();
                $person_report->id = 0;
                $person_report->planId = 0;
                $person_report->selDate = $dateStr;
                $person_report->dateStr = $date->format("n月j日").'('.$weekdays[$date->format("w")].')';
                $person_report->name = '';
                $person_report->planTitle = '';
                $person_report->color = 'fff';
                $person_report->rate = '';
                $person_report->plan = '';
                $person_report->report = '';
                $person_report->update_at = '';
                if($dateStr == $today)
                    $person_report->active = 1;
                else
                    $person_report->active = 0;

                $all_person_plans[] = $person_report;
            }
        }
        // 기본과제 목록얻기
        $main_plans = MainPlan::getWeekMainPlan($start_date, $end_date);
        // 분과제 목록얻기
        $subReportList = SubPlan::getSubPlanByDate($user->id);

//        print_r($all_person_plans);die;
        return view('business.plan.reportPerson',
            [   'all_plans'     => $all_person_plans,
                'weeklist'      =>  $weeklist,
                'cur_date'       => $cur_date,
                'sub_plan_list'  => $subReportList,
                'main_plans'     => $main_plans,
            ]);
    }

    public function changeMangeTable(Request $request)
    {
        $type = $request->get('type');
        $user = Auth::user();
        $subReportList = SubPlan::getSubPlanByDate($user->id, $type);

        return view('business.plan.plan_manage_table', with(['sub_plan_list'=>$subReportList]));
    }

    //搜索요청처리
    public function reportPersonSearch(Request $request)
    {
        $keyword = $request->get('keyword');
        $page = $request->get('pageNum');
        $pageCount = $request->get('pageLength');
        if ($keyword != "") {
            $main_plans = MainPlan::where('name', 'like', $keyword)->
            orwhere('descript', 'like', $keyword)->
            orderBy('endDate', 'desc')->get()->forPage($page, $pageCount);
        }
        return view('business.plan.planlist', array('main_plans' => $main_plans));
    }

    //계획项目변경요청처리
    public function reportPersonUpdate(Request $request)
    {
        $user = Auth::user();
        $reportId = $request->get('reportId');
        if($reportId != 0) {
            $report = ReportPerson::find($request->get('reportId'));
            if(is_null($report)) {
                return -1;
            }
        } else {
            $report = new ReportPerson();
            $report['create_plan'] = $request->get('plan_date');
            $report['userId'] = $user['id'];
            $report['itemId'] = $request->get('planId');
        }

        $report['rate'] = $request->get('rate');
        $report['plan'] = $request->get('plan');
        $report['report'] = $request->get('report');
        $report->save();

        if(($report['rate'] * 1) == 100) {
            $subPlan = SubPlan::find($report['itemId']);
            $subPlan['comple_date'] = $request->get('plan_date');
            $subPlan->save();
        }
        return 1;
    }

    //분과제 添加
    public function addSubTask(Request $request)
    {
        $planId = $request->get('planId');
        $mainId = $request->get('plan');
        $user = Auth::user();
        $plan = MainPlan::find($mainId);
        if(is_null($plan)) {
            $returnData = array('err' => '-4');
            return $returnData;
        }

        $date_plan_s = new \DateTime($plan->startDate);
        $date_plan_e = new \DateTime($plan->endDate);
        $task_start = new \DateTime($request->get('start'));
        $task_end = new \DateTime($request->get('end'));
        $date = new \DateTime(date("Y-m-d"));

        if ($task_start < $date_plan_s || $task_end > $date_plan_e) {
            $returnData = array('err' => '-2', 'plan' => $plan);
            return $returnData;
        }
        if ($task_end < $date) {
            $returnData = array('err' => '-3', 'plan' => $plan);
            return $returnData;
        }
        $startDate = $request->get('start');
        $endDate = $request->get('end');

        if (SubPlan::checkAlreadExist($user['id'], $mainId, $request->get('name'), $startDate, $endDate, $planId)) {
            $returnData = array('err' => '-1');
            return $returnData;
        }
        if($planId == 0) {
            $task = new SubPlan();
            $task['creator'] = $user->id;
        } else
            $task = SubPlan::find($planId);

        $task['mainId'] = $mainId;
        $task['planTitle'] = $request->get('name');
        $task['color'] = $request->get('btn_color');
        $task['descript'] = $request->get('desc');
        $task['startDate'] = $startDate;
        $task['endDate'] = $endDate;
        $task->save();

        $returnData = array('err' => '1', 'plan' => $task);
        return $returnData;
    }

    // 분과제삭제
    public function deleteSubTask(Request $request) {
        $taskId = $request->get('taskId');
        $task = SubPlan::find($taskId);
        if(empty($task) || ($task->creator != Auth::user()->id)) {
            $data['msg'] = '任务未登记。请稍后再试试。';
            $data['state'] = -1; // 오유
            return response()->json($data);
        }

        $planCount = ReportPerson::where('itemId', $task->id)->count();
        if($planCount) {
            $data['msg'] = '['.$task->planTitle.'] 的日白正在心境中就而删除不了。';
            $data['state'] = -1; // 오유
            return response()->json($data);
        }

        $task->delete();
        $data['msg'] = '['.$task->planTitle.'] 任务删除成功。';
        $data['state'] = 1; // 삭제성공
        return $data;
    }

    public function person_plan_list(Request $request)
    {
        $userId = Auth::user()->id;

        $planList = SubPlan::where('creator', $userId)->orderBy('id')->paginate()->setPath('');

        return view('plan_manage', ['$sub_plans'=>$planList]);

    }

    //주보요청처리
    public function reportPersonUpdateWeekList(Request $request)
    {
        $user = Auth::user();
        if (!(empty($request->get('report')) && empty($request->get('plan')))) {
            $id = $request->get('reportId');
            if(empty($id)){
                $report = new ReportPersonWeek();
                $report['userId'] = $user['id'];
                $report['planYear'] = $request->get('year');
                $report['planWeek'] = $request->get('week');
            } else
                $report = ReportPersonWeek::find($id);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');

            $report->save();
        }

        $year = $request->get('year');
        $month = $request->get('month');

        if($month == 0) {
            $date = new \DateTime(date('Y-m-d'));
            $year = date('Y');
            $month = date('n');
            $selDate = mktime(0, 0, 0, $month, 1, $year);

        } else {
            $selDate = mktime(0, 0, 0, $month, 1 ,$year);
        }

        $cur_date = Array();
        $cur_date['year'] = $year;
        $cur_date['month'] = $month;
        $cur_date['week'] = date('W');
        if ($month == 12) {
            $endDate = mktime(0, 0, 0, $cur_date['month'], 29, $cur_date['year']);
        } else {
            $endDate = mktime(0, 0, 0, $cur_date['month'] + 1, 1, $cur_date['year']);
        }

        $startWeek = date('W',$selDate);
        $endWeek = date('W', $endDate);

        $date = new \DateTime(date("Y-n-j", $selDate));

        $firstDayWeek = date('w', $selDate);
        if($firstDayWeek > 3) {
            $startWeek = $startWeek + 1;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDate);

        if($endDayWeek < 4 && $endDayWeek > 0) {
            $endWeek = $endWeek - 1;
            if($endWeek == 0) {
                $endDate = strtotime('-1 day', $endDate);
                $endWeek = date('W', $endDate);
            }
        }

        //made by kchs
        if ($firstDayWeek == 0)
            $endWeek = $endWeek - 1;

        $all_plans = array();
        $index = 1;
        for ($week = $startWeek; $week <= $endWeek; $week++) {

            $plan = ReportPersonWeek::where('userId', $user->id)
                ->where('planYear', $year)
                ->where('planWeek', $week)
                ->first();

            if (is_null($plan)) {
                $plan = new ReportPersonWeek;
                $plan['planYear'] = $year;
                $plan['planWeek'] = $week;
            }

            $start = $date->format('n.j');
            $date->modify("+6 day");
            $end = $date->format('n.j');
            $date->modify('+1 day');

            $plan['dateStr'] = $index.'周 ('.$start.'~'.$end.')';
            $all_plans[] = $plan;
            $index++;
        }

        return view('business.plan.reportPersonList',
            [   'main_plans'=>  $all_plans,
                'cur_date'  =>  $cur_date,
            ]);
    }

    //월보요청처리
    public function reportPersonUpdateMonthList(Request $request)
    {
        $user = Auth::user();
        if (!(empty($request->get('report')) && empty($request->get('plan')))) {
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new ReportPersonMonth();
                $report['userId'] = $user['id'];
                $report['planYear'] = $request->get('year');
                $report['planMonth'] = $request->get('month');
            } else
                $report = ReportPersonMonth::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report->save();
        }

        $year = $request->get('year');
        $cur_date = Array();
        $cur_date['year'] = date('Y');
        $cur_date['month'] = date('n');
        $cur_date['selYear'] = $year;

        $all_plans = Array();
        for ($month = 1; $month < 13; $month++) {
            $plan = ReportPersonMonth::where('userId', '=', $user->id)
                ->where('planYear', $year)
                ->where('planMonth', $month)
                ->first();
            if(is_null($plan)) {
                $plan = new ReportPersonMonth();
                $plan['planYear'] = $year;
                $plan['planMonth'] = $month;
            }

            $all_plans[] = $plan;
        }
        return view('business.plan.reportPersonListMonth',
            [   'main_plans' => $all_plans,
                'cur_date'   => $cur_date
            ]);
    }

    // 기업소全部종업원의 일보열람
    public function reportPersonUpdateAllList(Request $request)
    {
        $selDate = $request->get('selDate');
        $unitName = $request->get('unit');
        $state = $request->get('state');
        if(is_null($selDate))
            $selDate = date('Y-m-d');
        if(!empty($state)) {
            if($state == 'prev') {
                $date = new \DateTime($selDate);
                $date->modify("-1 day");
            } else if($state == 'next') {
                $date = new \DateTime($selDate);
                $date->modify("+1 day");
            } else if($state == 'curr') {
                $selDate = date('Y-m-d');
                $date = new \DateTime($selDate);
            }

            $selDate = $date->format('Y-m-d');

        }

        $unitId = 0;
        if(!is_null($unitName) && (!empty($unitName))) {
            $unitId = Unit::where('title', $unitName)->first()->id;
        }

        $memberList = UserInfo::getUserSimpleListByUnit($unitId);
        $allList = array();
        foreach ($memberList as $member) {
            if (!isset($member['releaseDate'])) {
                $reportList = ReportPerson::where('userId', $member['id'])
                    ->where('create_plan', $selDate)
                    ->get();
                foreach($reportList as $report) {
                    $planReport = array();
                    $planReport['realname'] = $member['realname'];
                    $planReport['title'] = $member['title'];
                    $planReport['pos'] = $member['pos'];
                    $planReport['update_at'] = $report['update_at'];
                    $planReport['mainPlan'] = $report['subPlan']['mainPlan']['name'];
                    $planReport['task'] = $report['subPlan']['planTitle'];
                    $planReport['rate'] = $report['rate'];
                    $planReport['plan'] = $report['plan'];
                    $planReport['report'] = $report['report'];
                    $allList[] = $planReport;
                }
                if(count($reportList) == 0) {
                    $planReport = array();
                    $planReport['realname'] = $member['realname'];
                    $planReport['title'] = $member['title'];
                    $planReport['pos'] = $member['pos'];
                    $planReport['update_at'] = '';
                    $planReport['mainPlan'] = '';
                    $planReport['task'] = '';
                    $planReport['rate'] = '';
                    $planReport['plan'] = '';
                    $planReport['report'] = '';
                    $allList[] = $planReport;
                }
            }
        }
        return view('business.plan.reportAllPerson', ['list' => $allList, 'selDate'=>$selDate, 'unit'=>$unitName]);
    }

    // 날자에 따라 부서의 주보자료얻기
    private function getUnitWeekList($dateStr, $unitId) {

        $selDate = new \DateTime($dateStr);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');

        $today = new \DateTime();
        $selWeek = $today->format('W');

        $firstDay = mktime(0, 0, 0, $month, 1, $year);

        if ($month == 12) {
            $endDay = mktime(0, 0, 0, $month, 29, $year);
        } else {
            $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        }
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);

        //made by kchs
        if ($firstDayWeek == 0)
            $endWeek = $endWeek - 1;

        if(($endDayWeek > 0) && ($endDayWeek < 4)) {
            $endWeek = $endWeek - 1;
            if($endWeek == 0) {
                $endDay = strtotime('-1 day', $endDay);
                $endWeek = date('W', $endDay);
            }
        }

        $list = array();
        $index = 1;
        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('n.j');
            $date->modify("+6 day");
            $endStr = $date->format('n.j');
            $report = UnitWeekReport::where('unitId', $unitId)
                ->where('planYear', $year)
                ->where('planWeek', $week)
                ->first();
            if(is_null($report)) {
                $report = new UnitWeekReport();
                $report['planYear'] = $year;
                $report['planWeek'] = $week;
            }
            if($week == $selWeek)
                $report['select'] = 1;
            else
                $report['select'] = 0;
            $report['selDate'] = $month.'月'.$index.'周'.'('.$startStr.'~'.$endStr.')';
            $list[] = $report;
            $index++;
            $date->modify("+1 day");
        }

        return $list;
    }

    // 부서의 주보登记페지
    public function reportUnitWeek(Request $request) {
        Util::getMenuInfo($request);

        $selDate = $request->get('selDate');
        if(is_null($selDate) || empty($selDate))
            $selDate = date('Y-m-d');

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $user = Auth::user();
        $userInfo = UserInfo::find($user['id']);
        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), $userInfo['unit']);

        if(is_null($request->get('selDate'))) {
            return view('business.plan.unit_report', array('list'=>$list, 'curDate'=>$curDate));
        }else {
            return view('business.plan.unit_report_week', array('list'=>$list, 'cur_date'=>$curDate));
        }
    }

    // 수정된 부서의 주보내용을 페지로 내려보낸다.
    public function reportUnitUpdateWeekList(Request $request) {

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;
        $year = $request->get('year');
        $month = $request->get('month');

        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new UnitWeekReport();
                $report['unitId'] = $unitId;
                $report['planYear'] = $request->get('year');
                $report['planWeek'] = $request->get('week');
            } else
                $report = UnitWeekReport::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report['creator'] = $user->id;
            $report->save();
        }

        $selDate = mktime(0, 0, 0, $month, 1, $year);

        $list = $this->getUnitWeekList(date('Y-m-d', $selDate), $unitId);

        return view('business.plan.unit_report_week_table', array('list'=>$list));
    }

    // 날자에 따르는 부서의 월보자료얻기
    private function getUnitMonthList($year, $unitId) {

        $today = new \DateTime();
        $todayMonth = $today->format('n');
        $todayYear = $today->format('Y');

        $list = array();
        for($m = 1; $m<13; $m++) {

            $report = UnitMonthReport::where('unitId', $unitId)
                ->where('planYear', $year)
                ->where('planMonth', $m)
                ->first();
            if(is_null($report)) {
                $report = new UnitMonthReport();
                $report['planYear'] = $year;
                $report['planMonth'] = $m;
            }
            if(($todayYear == $year) && ($todayMonth == $m))
                $report['select'] = 1;
            else
                $report['select'] = 0;
            $report['selDate'] = $year.'年'.$m.'月';
            $list[] = $report;
        }

        return $list;
    }

    // 부서의 월보登记페지
    public function reportUnitMonth(Request $request) {
        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_report_month', array('list'=>$list, 'cur_date'=>$curDate));
    }

    // 수정된 부서의 월보페지를 내려보낸다.
    public function reportUnitUpdateMonthList(Request $request) {
        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;
        $year = $request->get('year');
        $month = $request->get('month');

        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new UnitMonthReport();
                $report['unitId'] = $unitId;
                $report['planYear'] = $year;
                $report['planMonth'] = $month;
            } else
                $report = UnitMonthReport::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report['creator'] = $user->id;
            $report->save();
        }

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_report_month_table', array('list'=>$list));
    }

    // 부서의 주보열람페지
    public function reportUnitWeekRead(Request $request) {

        Util::getMenuInfo($request);

        $selDate = $request->get('selDate');
        if(is_null($selDate) || empty($selDate))
            $selDate = date('Y-n-j');

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;
        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), $unitId);

        if(is_null($request->get('selDate')))
            return view('business.plan.unit_report_read', array('list'=>$list, 'curDate'=>$curDate));
        else
            return view('business.plan.unit_week_read', array('list'=>$list, 'cur_date'=>$curDate));
    }

    // 수정된 부서의 주보내용을 페지로 내려보낸다.
    public function reportUnitUpdateWeekReadList(Request $request) {

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;

        $selDate = date('Y-m').'-01';
        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(trim($reportId) == '0') {
                $report = new UnitWeekReport();
                $report['unitId'] = $unitId;
                $report['plan'] = $request->get('plan');
                $report['report'] = $request->get('report');
                $report['creator'] = $user->id;
                $report->save();
            } else {
                $report = UnitWeekReport::find($reportId);
                if(is_null($report)) {
                    return;
                }
                $report['plan'] = $request->get('plan');
                $report['report'] = $request->get('report');
                $report['creator'] = $user->id;
                $report->save();
            }
        } else if($request->get('delete') != null ) {
            $reportId = $request->get('delete');
            $task = UnitWeekReport::find($reportId);
            if ($task['unitId'] == $unitId)
                $task->delete();
        } else if(!empty($request->get('selDate'))) {
            $selDate = $request->get('selDate');
        }

        $list = $this->getUnitWeekList($selDate, $unitId);

        return view('business.plan.unit_week_read_table', array('list'=>$list));
    }

    // 부서의 월보登记페지
    public function reportUnitMonthRead(Request $request) {

        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_month_read', array('list'=>$list, 'cur_date'=>$curDate));
    }

    // 수정된 부서의 월보페지를 내려보낸다.
    public function reportUnitUpdateMonthReadList(Request $request) {
        $user = Auth::user();
        $unitId = UserInfo::find($user->id)->unit;

        $selDate = date('Y').'-01-01';
        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(trim($reportId) == '0') {
                $report = new UnitMonthReport();
                $report['unitId'] = $unitId;
                $report['plan'] = $request->get('plan');
                $report['report'] = $request->get('report');
                $report['creator'] = $user->id;
                $report->save();
            } else {
                $report = UnitMonthReport::find($reportId);
                if(is_null($report)) {
                    return;
                }
                $report['plan'] = $request->get('plan');
                $report['report'] = $request->get('report');
                $report['creator'] = $user->id;
                $report->save();
            }
        } else if($request->get('delete') != null ) {
            $reportId = $request->get('delete');
            $task = UnitMonthReport::find($reportId);
            if ($task['unitId'] == $unitId)
                $task->delete();
        } else if(!empty($request->get('selDate'))) {
            $selDate = $request->get('selDate');
        }

        $list = $this->getUnitMonthList($selDate, $unitId);

        return view('business.plan.unit_month_read_table', array('list'=>$list));
    }

    // 부서별 주월보 기록열람페지
    public function reportPerUnit(Request $request) {

        Util::getMenuInfo($request);

        if($request->ajax()) {
            $year = $request->get('year');
            $month = $request->get('month');
            $selWeek = $request->get('week');
        } else {
            $today = new \DateTime();
            $year =  $today->format('Y');
            $month =  $today->format('n');
            $selWeek =  $today->format('W');
        }

        $curDate['year'] = $year;
        $curDate['month'] = $month;
        $curDate['week'] = $selWeek;

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        if($month == 12)
            $endDay = mktime(0, 0, 0, $month, 29, $year);
        else
            $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if(($endDayWeek < 4) && ($endDayWeek > 0))
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weeklist = array();
        $index = 1;

        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('m.d');
            $date->modify("+6 day");
            $endStr = $date->format('m.d');

            $weekObj['week'] = $week;
            $weekObj['title'] = $index . '周' . ' (' . $startStr . '~' . $endStr . ')';
            $weeklist[] = $weekObj;
            $index++;
            $date->modify("+1 day");
        }

        $list = UnitWeekReport::getReportPerUnit($year, $selWeek);

        if($request->ajax())
            return view('business.plan.per_unit_week', array('list'=>$list, 'cur_date'=>$curDate, 'weekList'=>$weeklist));
        else
            return view('business.plan.per_unit', array('list'=>$list, 'curDate'=>$curDate, 'weekList'=>$weeklist));
    }

    // 부서별 월보를 현시한다.
    public function reportPerUnitMonth(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        if (empty($year)) {
            $year = date('Y');
            $month = date('n');
        }

        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $list = UnitMonthReport::getReportPerUnit($year, $month);

        if(is_null($request->get('selDate')))
            return view('business.plan.per_unit_month', array('list'=>$list, 'curDate'=>$curDate));
        else
            return view('business.plan.per_unit_week_table', array('list'=>$list));
    }


    // 기업소의 주월보를 관리
    // 기업소의 주보登记페지
    public function reportEnterPriseWeek(Request $request) {

        Util::getMenuInfo($request);

        if($request->ajax())
            $selDate = $request->get('selDate');
        else {
            $selDate = date('Y-n-j');
        }

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), 0);

        if($request->ajax())
            return view('business.plan.unit_report_week', array('list'=>$list, 'cur_date'=>$curDate));
        else
            return view('business.plan.enterprise_report', array('list'=>$list, 'curDate'=>$curDate));
    }

    // 수정된 부서의 주보내용을 페지로 내려보낸다.
    public function reportEnterpriseUpdateWeekList(Request $request) {

        $user = Auth::user();
        $unitId = 0;
        $year = $request->get('year');
        $month = $request->get('month');

        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new UnitWeekReport();
                $report['unitId'] = $unitId;
                $report['planYear'] = $request->get('year');
                $report['planWeek'] = $request->get('week');
            } else
                $report = UnitWeekReport::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report['creator'] = $user->id;
            $report->save();
        }

        $selDate = mktime(0, 0, 0, $month, 1, $year);

        $list = $this->getUnitWeekList(date('Y-m-d', $selDate), $unitId);

        return view('business.plan.unit_report_week_table', array('list'=>$list));
    }

    // 부서의 월보登记페지
    public function reportEnterpriseMonth(Request $request) {

        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $unitId = 0;

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_report_month', array('list'=>$list, 'cur_date'=>$curDate));
    }

    // 수정된 부서의 월보페지를 내려보낸다.
    public function reportEnterpriseUpdateMonthList(Request $request) {
        $user = Auth::user();
        $unitId = 0;
        $year = $request->get('year');
        $month = $request->get('month');

        if(!empty($request->get('plan'))) {
            $reportId = $request->get('reportId');
            if(empty($reportId)) {
                $report = new UnitMonthReport();
                $report['unitId'] = $unitId;
                $report['planYear'] = $year;
                $report['planMonth'] = $month;
            } else
                $report = UnitMonthReport::find($reportId);

            $report['plan'] = $request->get('plan');
            $report['report'] = $request->get('report');
            $report['creator'] = $user->id;
            $report->save();
        }

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_report_month_table', array('list'=>$list));
    }

    // 부서의 주보열람페지
    public function reportEnterpriseWeekRead(Request $request) {

        Util::getMenuInfo($request);

        if($request->ajax())
            $selDate = $request->get('selDate');
        else {
            $selDate = date('Y-n-j');
        }

        $selDate = new \DateTime($selDate);
        $year = $selDate->format('Y');
        $month = $selDate->format('n');
        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $list = $this->getUnitWeekList($selDate->format('Y-m-d'), 0);

        if($request->ajax())
            return view('business.plan.unit_week_read', array('list'=>$list, 'cur_date'=>$curDate));
        else
            return view('business.plan.enterprise_report_read', array('list'=>$list, 'curDate'=>$curDate));
    }

    // 부서의 월보登记페지
    public function reportEnterpriseMonthRead(Request $request) {

        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $curDate['year'] = $year;

        $user = Auth::user();
        $unitId = 0;

        $list = $this->getUnitMonthList($year, $unitId);

        return view('business.plan.unit_month_read', array('list'=>$list, 'cur_date'=>$curDate));
    }

    public function memberWeekAndMonthReport(Request $request) {
        Util::getMenuInfo($request);

        $year = date('Y');
        $month = date('n');
        $selWeek = date('W');

        $curDate['year'] = $year;
        $curDate['month'] = $month;
        $curDate['week'] = $selWeek;

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        if($month == 12)
            $endDay = mktime(0, 0, 0, $month, 29, $year);
        else
            $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if(($endDayWeek < 4) && ($endDayWeek > 0))
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weeklist = array();
        $index = 1;

        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('m.d');
            $date->modify("+6 day");
            $endStr = $date->format('m.d');

            $weekObj['week'] = $week;
            $weekObj['title'] = $index . '周' . ' (' . $startStr . '~' . $endStr . ')';
            $weeklist[] = $weekObj;
            $index++;
            $date->modify("+1 day");
        }

        $weekReportList = ReportPersonWeek::getMemberReportWeek($year, $selWeek);
        $monthReportList = ReportPersonMonth::getMemberReportMonth($year, $month);

        return view('business.plan.member_report',
            [
                'cur_date'  =>  $curDate,
                'weekList'  =>  $weeklist,
                'weekReport'    =>  $weekReportList,
                'monthReport'   =>  $monthReportList
            ]);

    }

    public function memberWeekReport(Request $request) {
        $year = $request->get('year');
        $month = $request->get('month');
        $selWeek = $request->get('week');

        $curDate['year'] = $year;
        $curDate['month'] = $month;
        $curDate['week'] = $selWeek;

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        if($month == 12)
            $endDay = mktime(0, 0, 0, $month, 29, $year);
        else
            $endDay = mktime(0, 0, 0, $month+1, 1, $year);
        $startWeek = date('W', $firstDay);
        $endWeek = date('W', $endDay);

        $dateStr = date('Y-m-d', $firstDay);
        $date = new \DateTime($dateStr);
        $firstDayWeek = date('w', $firstDay);
        if($firstDayWeek > 3) {
            $startWeek++;
            $week = 7 - $firstDayWeek;
            $date->modify("+$week day");
        } else
            $date->modify("-$firstDayWeek day");

        $endDayWeek = date('w', $endDay);
        if(($endDayWeek < 4) && ($endDayWeek > 0))
            $endWeek = $endWeek - 1;

        if(($selWeek < $startWeek) || ($selWeek > $endWeek))
            $selWeek = $startWeek;

        $weeklist = array();
        $index = 1;

        for($week = $startWeek; $week <= $endWeek; $week++) {
            $startStr = $date->format('m.d');
            $date->modify("+6 day");
            $endStr = $date->format('m.d');

            $weekObj['week'] = $week;
            $weekObj['title'] = $index . '周' . ' (' . $startStr . '~' . $endStr . ')';
            $weeklist[] = $weekObj;
            $index++;
            $date->modify("+1 day");
        }

        $weekReportList = ReportPersonWeek::getMemberReportWeek($year, $selWeek);

        return view('business.plan.per_member_week',
            [
                'cur_date'  =>  $curDate,
                'weekList'  =>  $weeklist,
                'list'    =>  $weekReportList,
            ]);
    }

    public function memberMonthReport(Request $request) {
        $year = $request->get('year');
        $month = $request->get('month');

        $curDate['year'] = $year;
        $curDate['month'] = $month;

        $monthReportList = ReportPersonMonth::getMemberReportMonth($year, $month);

        return view('business.plan.per_member_month',
            [
                'cur_date'  =>  $curDate,
                'list'   =>  $monthReportList
            ]);
    }

    public function enterpriseYearAttend(Request $request) {

        Util::getMenuInfo($request);

        //매달의 월일수계산
        $year = is_null($request->get('year')) ? date('Y') : $request->get('year');

        $days = 365;
        $remain = fmod($year, 4);
        if($remain == 0)
            $days = 366;

        //월첫일과 마지막일수 계산
        $start = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, 12, 31, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if($year == date('Y')){
            $days = 0;
            $months = [31,28,31,30,31,30,31,31,30,31,30,31];
            $month = date('n') - 1;
            for($i = 0; $i<$month; $i++)
                $days += $months[$i];
            $days += date('j') * 1;

            $work_days = $days - $total_rest;
        }

        $shipId = $request->get('ship');
        $unitId = $request->get('unit');
        $memberName = $request->get('name');

        // 기업소에 소속된 모든 성원(직원 및 선원)들의 목록을 얻는다.
        $memberList = UserInfo::enterpriseTotalMemberList($unitId, $shipId, $memberName);

        $userStr = '';
        $crewStr = '';
        foreach($memberList as $members) {
            if($members->memberType == 1)
                $userStr = $members->idStr;
            else
                $crewStr = $members->idStr;
        }

        $userAttend = AttendUser::getAttendDaysOfMonthByAttendType($userStr, $start, $end);
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($crewStr, $start, $end);
        $typeList = AttendType::all();

        $list = array();
        $totl_absen = 0;
        $totl_attend = 0;

        // ---------  직원들의 년간 출근정형을 종합한다.  ------------
        $attendMember = array();
        if(count($userAttend) > 0) {
            $attendMember['id'] = $userAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($userAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                $attendMember['isShip'] = 0;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 0;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->unit;
                $attendMember['pos'] = $member->title;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // 마지막기록에 대한 添加
        if(count($userAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        // ---------  선원들의 월간 출근정형을 종합한다.  ------------
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        if(count($crewAttend) > 0) {
            $attendMember['id'] = $crewAttend[0]->id;
            foreach($typeList as $attendType)
                $attendMember['type_'.$attendType['id']] = 0;
        }
        foreach ($crewAttend as $member) {
            if($attendMember['id'] == $member->id) {
                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                $attendMember['isShip'] = 1;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            } else {
                if(($totl_attend + $totl_absen) < $work_days) { // 자료기지에 登记되지 않은 출근은 未确定출근으로 본다.
                    $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                    $totl_absen +=  $work_days - ($totl_attend + $totl_absen);
                }
                $attendMember['absence'] = $totl_absen;
                $attendMember['attend'] = $totl_attend;

                $list[] = $attendMember;

                $attendMember = array();
                $totl_absen = 0;
                $totl_attend = 0;

                $attendMember['id'] = $member->id;
                $attendMember['isShip'] = 1;
                foreach($typeList as $attendType) {
                    $attendMember['type_' . $attendType['id']] = 0;
                }

                $attendMember['realName'] = $member->realname;
                $attendMember['unitName'] = $member->name;
                $attendMember['pos'] = $member->Duty;
                if($member->statusId < 4)
                    $totl_attend += $member->attendCount;
                else
                    $totl_absen += $member->attendCount;
                $attendMember['type_'.$member->statusId] = $member->attendCount;
            }
        }

        // 마지막기록에 대한 添加
        if(count($crewAttend) > 0) {
            if (($totl_attend + $totl_absen) < $work_days) {
                $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
                $totl_absen += $work_days - ($totl_attend + $totl_absen);
            }
            $attendMember['absence'] = $totl_absen;
            $attendMember['attend'] = $totl_attend;
            $list[] = $attendMember;
        }

        $ships = ShipRegister::getShipListOnlyOrigin();
        $units = Unit::unitFullNameList();

        return View('business.attend.enterprise_year_attend',
            [   'dates'     =>  $dates,
                'year'      =>  $year,
                'list'      =>  $list,
                'typeList'  =>  $typeList,
                'units'     =>  $units,
                'ships'     =>  $ships,
                'unitId'    =>  $unitId,
                'shipId'    =>  $shipId,
                'memberName'=>  $memberName,
            ]);

    }

    public function memberYearReport(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 136;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $memberInfo = UserInfo::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $selMonth = 13;
        if($year == date('Y')){
            $selMonth = date('n') + 1;
        }
        $list = [];
        $list['month'] = $selMonth - 1;

        for($month = 1; $month < $selMonth; $month++) {
            $attendList = AttendUser::getMemberMonthAttend($memberId, $year, $month);

            $monthDay = Util::getDaysOfMonth($year, $month);

            $list[$month.'']['data'] = [];

            $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
            $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

            for($day = 0; $day<$monthDay; $day++){
                $isRegister = 0;
                $isRest = 0;
                foreach($restList as $rest) {
                    $restDay = $rest->day;
                    $date = new \DateTime($restDay);
                    $restDay = $date->format('j') * 1;
                    if($restDay == ($day + 1)) {
                        $userAttend['day'] = $day + 1;
                        $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                        $userAttend['memo'] = $rest->descript;
                        $userAttend['rest'] = 1;
                        $list[$month.'']['data'][] = $userAttend;
                        $isRest = 1;
                        break;
                    }
                }

                if($isRest)
                    continue;

                $attendDay = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
                $curr_day = date('Y-m-d');
                if($attendDay > $curr_day) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                    continue;
                }
                foreach($attendList as $attend) {
                    $attendDay = $attend->regDay;
                    $date = new \DateTime($attendDay);
                    $attendDay = $date->format('j') * 1;
                    if($attendDay == ($day + 1)) {
                        $isRegister = 1;
                        $userAttend['day'] = $attendDay;
                        $userAttend['name'] = $attend->name;
                        $userAttend['memo'] = $attend->memo;
                        $userAttend['rest'] = 0;
                        $list[$month.'']['data'][] = $userAttend;
                        break;
                    }
                }
                if($isRegister == 0) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '未确定';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                }
            }
        }
        //해당수표도장을 얻는다.
        $decEnv = DecEnvironment::find($memberId);
        if(isset($decEnv->signPath))
            $signPath = $decEnv->signPath;
        else
            $signPath = null;

        $days = 365;
        $remain = fmod($year, 4);
        if($remain == 0)
            $days = 366;

        //월첫일과 마지막일수 계산
        $start = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, 12, 31, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if($year == date('Y')){
            $days = 0;
            $months = [31,28,31,30,31,30,31,31,30,31,30,31];
            $month = date('n') - 1;
            for($i = 0; $i<$month; $i++)
                $days += $months[$i];
            $days += date('j') * 1;

            $work_days = $days - $total_rest;
        }
        $userAttend = AttendUser::getAttendDaysOfMonthByAttendType($memberId, $start, $end);
        $typeList = AttendType::all();
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        $attendMember['id'] = $userAttend[0]->id;
        foreach($typeList as $attendType)
            $attendMember['type_'.$attendType['id']] = 0;
        foreach ($userAttend as $member) {
            $attendMember['realName'] = $member->realname;
            $attendMember['unitName'] = $member->unit;
            $attendMember['pos'] = $member->title;
            $attendMember['isShip'] = 0;
            if($member->statusId < 4)
                $totl_attend += $member->attendCount;
            else
                $totl_absen += $member->attendCount;
            $attendMember['type_'.$member->statusId] = $member->attendCount;
        }
        if (($totl_attend + $totl_absen) < $work_days) {
            $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
            $totl_absen += $work_days - ($totl_attend + $totl_absen);
        }
        $attendMember['absence'] = $totl_absen;
        $attendMember['attend'] = $totl_attend;

        //출근合计값을 구한다.
        $totalData = array(
            "days"  =>  $dates['days'],
            "rest"  =>  $dates['rest'],
            "work"  =>  $dates['work'],
            "attend"  =>  $attendMember['attend'],
            "absence"  =>  $attendMember['absence'],
        );
        foreach($typeList as $attendType)
            $totalData["type_{$attendType['id']}"] = $attendMember['type_'.$attendType['id']];

        return view('business.attend.member_year_attend', ['info'=>$memberInfo, 'year'=>$year, 'list'=>$list, 'signPath'=>$signPath, 'totalData'=>$totalData]);
    }

    public function shipMemberYearReport(Request $request) {
        $GLOBALS['selMenu'] = 37;
        $GLOBALS['submenu'] = 136;

        $memberId = $request->get('userId');
        $year = $request->get('year');
        if(empty($year))
            $year = date('Y');

        $memberInfo = ShipMember::find($memberId);
        if(empty($memberInfo)) {
            return back();
        }

        $selMonth = 13;
        if($year == date('Y')){
            $selMonth = date('n') + 1;
        }
        $list = [];
        $list['month'] = $selMonth - 1;

        for($month = 1; $month < $selMonth; $month++) {
            $attendList = AttendShip::getMemberMonthAttend($memberId, $year, $month);

            $monthDay = Util::getDaysOfMonth($year, $month);

            $list[$month.'']['data'] = [];

            $attendMonth = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
            $restList = AttendRest::where('day', 'like', $attendMonth.'-%')->get();

            for($day = 0; $day<$monthDay; $day++){
                $isRegister = 0;
                $isRest = 0;
                foreach($restList as $rest) {
                    $restDay = $rest->day;
                    $date = new \DateTime($restDay);
                    $restDay = $date->format('j') * 1;
                    if($restDay == ($day + 1)) {
                        $userAttend['day'] = $day + 1;
                        $userAttend['name'] = constant("REST_TYPE_".$rest->state);
                        $userAttend['memo'] = $rest->descript;
                        $userAttend['rest'] = 1;
                        $list[$month.'']['data'][] = $userAttend;
                        $isRest = 1;
                        break;
                    }
                }

                if($isRest)
                    continue;

                $attendDay = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));
                $curr_day = date('Y-m-d');
                if($attendDay > $curr_day) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                    continue;
                }
                foreach($attendList as $attend) {
                    $attendDay = $attend->regDay;
                    $date = new \DateTime($attendDay);
                    $attendDay = $date->format('j') * 1;
                    if($attendDay == ($day + 1)) {
                        $isRegister = 1;
                        $userAttend['day'] = $attendDay;
                        $userAttend['name'] = $attend->name;
                        $userAttend['memo'] = $attend->memo;
                        $userAttend['rest'] = 0;
                        $list[$month.'']['data'][] = $userAttend;
                        break;
                    }
                }
                if($isRegister == 0) {
                    $userAttend['day'] = $day + 1;
                    $userAttend['name'] = '未确定';
                    $userAttend['memo'] = '';
                    $userAttend['rest'] = 0;
                    $list[$month.'']['data'][] = $userAttend;
                }
            }
        }

        if (isset($memberInfo['signPhoto']))
            $signPath = $memberInfo['signPhoto'];
        else
            $signPath = null;

        $days = 365;
        $remain = fmod($year, 4);
        if($remain == 0)
            $days = 366;

        //월첫일과 마지막일수 계산
        $start = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
        $end = date('Y-m-d', mktime(0, 0, 0, 12, 31, $year)); // 매월의 마지막날자
        $total_rest = AttendRest::where('day', '>=', $start)->where('day', '<=', $end)->get()->count();

        //법적가동일수 계산
        $work_days = $days - $total_rest;
        $dates['days'] = $days;
        $dates['work'] = $work_days;
        $dates['rest'] = $total_rest;

        if($year == date('Y')){
            $days = 0;
            $months = [31,28,31,30,31,30,31,31,30,31,30,31];
            $month = date('n') - 1;
            for($i = 0; $i<$month; $i++)
                $days += $months[$i];
            $days += date('j') * 1;

            $work_days = $days - $total_rest;
        }
        $crewAttend = AttendShip::getAttendDaysOfMonthByAttendType($memberId, $start, $end);
        $typeList = AttendType::all();
        $totl_absen = 0;
        $totl_attend = 0;
        $attendMember = array();
        $attendMember['id'] = $crewAttend[0]->id;
        foreach($typeList as $attendType)
            $attendMember['type_'.$attendType['id']] = 0;
        foreach ($crewAttend as $member) {
            $attendMember['realName'] = $member->realname;
            $attendMember['unitName'] = $member->name;
            $attendMember['pos'] = $member->Duty;
            $attendMember['isShip'] = 1;
            if($member->statusId < 4)
                $totl_attend += $member->attendCount;
            else
                $totl_absen += $member->attendCount;
            $attendMember['type_'.$member->statusId] = $member->attendCount;
        }
        if (($totl_attend + $totl_absen) < $work_days) {
            $attendMember['type_4'] = $attendMember['type_4'] + $work_days - ($totl_attend + $totl_absen);
            $totl_absen += $work_days - ($totl_attend + $totl_absen);
        }
        $attendMember['absence'] = $totl_absen;
        $attendMember['attend'] = $totl_attend;

        //출근合计값을 구한다.
        $totalData = array(
            "days"  =>  $dates['days'],
            "rest"  =>  $dates['rest'],
            "work"  =>  $dates['work'],
            "attend"  =>  $attendMember['attend'],
            "absence"  =>  $attendMember['absence'],
        );
        foreach($typeList as $attendType)
            $totalData["type_{$attendType['id']}"] = $attendMember['type_'.$attendType['id']];

        return view('business.attend.ship_member_year_attend', ['info'=>$memberInfo, 'year'=>$year, 'list'=>$list, 'signPath'=>$signPath,'totalData'=>$totalData]);
    }

    // Ajax
    public function ajaxContractInfo(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        $retVal['shipInfo'] = ShipRegister::where('IMO_No', $shipId)->first();
        $retVal['portList'] = ShipPort::orderBy('Port_En', 'asc')->get();
        $retVal['cargoList'] = Cargo::orderBy('name', 'asc')->get();

        return response()->json($retVal);
    }

    public function ajaxVoyNoValid(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];
        $voyNo = $params['voyNo'];
        $type = $params['type'];

        $ret = false;
        $shipInfo = CP::where('Ship_ID', $shipId)->where('Voy_No', $voyNo)->where('CP_kind', $type)->first();
        if($shipInfo == false || $shipInfo == null)
            $ret = true;

        return response()->json($ret);
    }

    public function ajaxCargoDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        Cargo::where('id', $id)->delete();

        return response()->json(Cargo::all());
    }

    public function ajaxPortDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        ShipPort::where('id', $id)->delete();

        return response()->json(ShipPort::all());
    }

    public function ajaxVoyList(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        $cp_list = CP::where('Ship_ID', $shipId)->orderBy('Voy_No', 'desc')->get();

        return response()->json($cp_list);
    }

    public function ajaxVoyDelete(Request $request) {
        $params = $request->all();
        $id = $params['id'];

        $ret = CP::where('id', $id)->delete();

        return response()->json(CP::take(3)->get());
    }

    public function ajaxDynamic(Request $request) {
        $params = $request->all();
        $shipList = ShipRegister::all();

        $retVal['shipList'] = $shipList;

        return response()->json($retVal);
    }

    
    public function ajaxVoyAllList(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        $cp_list = CP::where('Ship_ID', $shipId)->orderBy('Voy_No', 'asc')->get();

        return response()->json($cp_list);
    }
}