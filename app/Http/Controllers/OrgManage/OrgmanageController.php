<?php

namespace App\Http\Controllers\Orgmanage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Member\Career;
use App\Models\Menu;
use App\Models\ShipManage\ShipRegister;
use App\Models\UserInfo;
use App\Models\Member\Unit;
use App\Models\Member\Post;

use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class OrgmanageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');

        $GLOBALS['selMenu'] = 0;
        $GLOBALS['submenu'] = 0;

        $admin = Session::get('admin');
        $query = Menu::where('parentId', '0');
        if($admin == 0)
            $query = $query->where('admin', '0');
        $topMenu = $query->orderBy('id')->get();

		foreach($topMenu as $menu) {
            $menu['submenu'] = Menu::where('parentId', '=', $menu['id'])->orderBy('id')->get();
            foreach($menu['submenu'] as $submenu)
            {
                $submenu['thirdmenu'] = Menu::where('parentId', '=', $submenu['id'])->orderBy('id')->get();
            }
        }

        $GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 1;

        if($admin > 0) {
            $menulist = Menu::where('parentId', '=', '1')->orderBy('id')->get();
            foreach($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->orderBy('id')->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {

        }
    }

    /////////////////////////////  부서관리   ///////////////////////
    public function unitManage(Request $request) {
        Util::getMenuInfo($request);

        $units = Unit::unitList();

        return view('orgmanage.quartermanage', array('units' => $units));
    }

    public function unitDelete(Request $request) {
        $unitId = $request->get('unitId');

        $unit = Unit::find($unitId);
        if(is_null($unit))
            return;

        $unitKey = $unit['orderkey'];

        $unitIds = Unit::select(DB::raw('GROUP_CONCAT(id) as unitIds'))->where('orderkey', 'like', $unitKey.'%')->first();
        if(isset($unitIds)) {
            $idList = explode(',', $unitIds['unitIds']);
            UserInfo::whereIn('unit', $idList)->update(['unit'=> 0]);
        }
        Unit::where('orderkey', 'like', $unitKey.'%')->delete();
        return 'success';
    }

    public function unitUpdate(Request $request) {

        $unitId = $request->get('unitId');
        $unitName = $request->get('unitName');

        $isExist = Unit::where('title', $unitName)->first();
        if(isset($isExist) && ($unitId != $isExist['id']))
            return 'overlay';

        $unit = Unit::find($unitId);
        if(is_null($unit))
            return;

        $unit['title'] = $unitName;
        $unit->save();

        return 'success';
    }

    public function unitRegister(Request $request) {
        $parentId = $request->get('parentId');
        $name = $request->get('unitName');

        $isExist = Unit::where('title', $name)->first();
        
        if($isExist)
            return;
        
        //부서를 添加할때에 상위부서를 선택하지 않은 경우에 제일 상위부서로 添加한다.
        $last = Unit::where('parentId', $parentId)->orderBy('orderkey', 'desc')->first();
        
        if(is_null($last)) {
            $parent = Unit::find($parentId);
            $newKey = $parent['orderkey'].$this->int2keystr(1);
        } else {
            $lastKey = $last['orderkey'];
            $len = strlen($lastKey);
            $subkey = substr($lastKey, $len-3) * 1;
            $parentKey = substr($lastKey, 0, $len-3);
            $newKey = $parentKey.$this->int2keystr($subkey + 1);
        }

        $unit = new Unit();
        $unit['title'] = $name;
        $unit['parentId'] = $parentId;
        $unit['orderkey'] = $newKey;
        $unit->save();

        return 'success';
    }

    public function int2keystr($num) {
        return sprintf("%03d", $num);
    }

    public function keystr2int($str) {
        return number_format($str);
    }

    ///////////////////////////   직위관리  ////////////////////////////
    public function savepost(Request $request) {
        $orderNum = $request->get('orderNum');
        $postname = $request->get('postname');
        $post = new Post;
        $post->orderNum = $orderNum;
        $post->title = $postname;
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function showpostmanage(Request $request) {
        $GLOBALS['selMenu'] = $request->get('menuId');
        $GLOBALS['submenu'] = 0;

        $posts = Post::orderBy('orderNum')->get();
        $maxLevel = Post::all()->max('orderNum')+1;

        return view('orgmanage.postmanage', array('posts' => $posts, 'maxLevel'=>$maxLevel));
    }

    public function updatepost(Request $request) {
        $post = Post::find($request->id);
        $post->orderNum = $request->orderNum;
        $post->title = $request->title;
        $this->validate($request, [
            'orderNum' => 'required|max:255',
            'title' => 'required',
        ]);
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function  delpost(Request $request) {
        $post = Post::find($request->id);
        $post->delete();
        $result = array('result' => "success");
        return json_encode($result);
    }

    public function  addpost(Request $request) {
        $this->validate($request, [
            'orderNum' => 'required|max:255',
            'title' => 'required',
        ]);
        $post = new Post;
        $post->orderNum = $request->orderNum;
        $post->title = $request->title;
        $post->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    /////////////////////////////   부서책임자   ///////////////////////////////////
    public function showquartermanager(Request $request) {
        Util::getMenuInfo($request);

        $units = Unit::orderBy('orderkey')->get();
        $unitArray  = new \stdClass();
        $idx= 0;
        foreach ( $units as $unit) {
            $childcount = Unit::where("parentId", $unit->id)->count();
            $unit->childcount = $childcount;
            $unitArray->$idx = $unit;
            $idx++;
        }
        $users = User::get();
        return view('orgmanage.quartermanager', array('units' => $unitArray, 'users' => $users));
    }

    //수정
    public function updatequartermanager(Request $request) {
        $unit = Unit::find($request->id);
        $unit->manager = $request->manager;
        $unit->save();
        $result = array('result' => "success");
        return json_encode($result);
    }

    //삭제
    public function delquartermanager(Request $request) {
        $unit = Unit::find($request->id);
        $unit->manager = "";
        $unit->save();

        $result = array('result' => "success");
        return json_encode($result);

    }

    //부서책임자의 添加
    public function loadquartermanager() {
        $units = new Unit();
        $units = $units->orderBy('orderkey')->get();
        return view('orgmanage.quartermanager', array('units' => $units));
    }

    /////////////////////////////  직원정보관리   ///////////////////////
    public function userInfoListView(Request $request) {
        Util::getMenuInfo($request);

        $unitId = $request->get('unit');
        $pos = $request->get('pos');
        $realname = $request->get('realname');
        $status = $request->get('status');

        $unitList = Unit::all(['id', 'title']);
        $posList = Post::all(['id', 'title']);

        $userlist = User::getSimpleUserList($unitId, $pos, $realname, $status);

        if(isset($unitId))
            $userlist->appends(['unit'=>$unitId]);
        if(isset($pos))
            $userlist->appends(['pos'=>$pos]);
        if(isset($realname))
            $userlist->appends(['realname'=>$realname]);
        
        return view('orgmanage.memberinfo',
                ['list'         =>$userlist,
                'unitList'      =>$unitList,
                'posList'       =>$posList,
                'realname'      =>$realname,
                'unitId'        =>$unitId,
                'posId'         =>$pos,
                'status'        =>$status,
                'type'          => 'edit',
                'realname'      =>$realname
            ]);
    }

    // Go to Personal Info Edit screen
    public function updateMemberinfo(Request $request) {
        $userid = $request->get('userId');

        $userinfo = UserInfo::find($userid);
        $user = User::find($userid);

        return view('org/memberadd',   ['profile'=>$userinfo, 'user'=>$user]);
    }

    // 직원정보添加현시action
    public function addMemberinfo(Request $request) {
        $units = Unit::unitFullNameList();
        $posts = Post::orderBy('orderNum')->get();
        $pmenus = Menu::where('parentId', '=', 0)->get();
        $cmenus = array();
        $index = 0;

        $state = Session::get('state');

        $userid = $request->get('uid');
        if(empty($userid)) {
            if(isset($state) && ($state == 'success')) {
                $userid = Session::get('userId');
            }
        }

        $userinfo = User::find($userid);

        return view('orgmanage.addmember',
                [   'userid'    =>  $userid,
                    'userinfo'  =>  $userinfo,
                    'units'     =>  $units,
                    'pos'       =>  $posts,
                    'pmenus'    =>  $pmenus,
                    'cmenus'    =>  $cmenus,
                    'state'     =>  $state
                ]);
    }

    //직원정보 갱신
    public function updateMember(Request $request) {
        $file = $request->file('photopath');
        if(isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/logo'), $filename );
        } else
            $filename = null;

        $userid = $request->get('userid');

        $param = $request->all();

        $account = $param['account'];
        $isUser = User::where('account', $account)->where('id', '<>', $userid)->first();
        if(!is_null($isUser)) {
            $error = "错误!  登记识别者重复!";
            return back()->with(['state'=> $error]);
        }

        $user = User::find($userid);
	    $user->account = $param['account'];
	    $user->realname = $param['name'];
	    $user->unit = $param['unit'];
	    $user->phone = $param['phone'];
	    $user->pos = $param['pos'];
	    $user->entryDate = $param['enterdate'] == '' ? null : $param['enterdate'];
	    $releaseDate = $param['releaseDate'];
	    if(!empty($param['enterdate']))
		    $user->entryDate = $param['enterdate'];

	    if(!empty($param['releaseDate'])) {
		    $user->releaseDate = $param['releaseDate'];
		    $user->status = STATUS_BANNED;
	    } else
		    $user->status = STATUS_ACTIVE;

	    $user->isAdmin = (isset($param['isAdmin']) && $param['isAdmin'] == 1) ? 1 : ($param['pos'] == IS_SHAREHOLDER ? IS_SHAREHOLDER : 0);
        if(isset($param['password_reset']) && $param['password_reset'] == true)
	        $user->password = bcrypt(DEFAULT_PASS);

        $user->save();

        return redirect('org/userInfoListView');
    }

    public function addMember(Request $request) {
        $file = $request->file('photopath');

        if(isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $filename = Util::makeUploadFileName().'.'.$ext;
            $file->move(public_path('uploads/logo'),$filename );
        } else
            $filename = null;

        $param = $request->all();

        $account = $param['account'];
        $isUser = User::where('account', $account)->first();
        if(!is_null($isUser)) {
            $error = "错误!  用户ID重复!";
            return back()->with(['state'=>$error]);
        }

        $user = new User();
        $user->account = $param['account'];
	    $user->realname = $param['name'];
	    $user->password = bcrypt(DEFAULT_PASS);
	    $user->unit = $param['unit'];
	    $user->pos = $param['pos'];
	    $user->phone = $param['phone'];
	    if(!empty($param['enterdate']))
	        $user->entryDate = $param['enterdate'];

	    if(!empty($param['releaseDate'])) {
		    $user->releaseDate = $param['releaseDate'];
		    $user->status = STATUS_BANNED;
	    } else
		    $user->status = STATUS_ACTIVE;

        $user->isAdmin = (isset($param['isAdmin']) && $param['isAdmin'] == 1) ? 1 : ($param['pos'] == IS_SHAREHOLDER ? IS_SHAREHOLDER : 0);
        $user->save();

        return redirect('org/userInfoListView');
    }

    // 개인사진 업로드
    public function upload(Request $request) {
        $file = $request->files('photo');
        $desdir = '/upload';
        $desfilename = 'tmp';
        $file->move($desdir, $file->getClientOriginalName());
        $photo = $request->get('photo');
        $data = array('result' => "success");
        return json_encode($data);
    }

    public function deleteMember(Request $request) {
    	$params = $request->all();
    	$userid = $params['userid'];
	    $ret = User::where('id', $userid)->delete();
	    $ret = Career::where('userId', $userid)->delete();
	    $ret = UserInfo::where('id', $userid)->delete();

    	return response()->json($ret);
    }

	//////////////////////////////// 권한관리용 직원목록현시  /////////////////////////////////
	public function userPrivilege(Request $request) {
		Util::getMenuInfo($request);

		$unitId = $request->get('unit');
		$pos = $request->get('pos');
		$realname = $request->get('realname');
		$status = $request->get('status');

		$unitList = Unit::all(['id', 'title']);
		$posList = Post::all(['id', 'title']);

		$userlist = User::getSimpleUserList($unitId, $pos, $realname, $status);

		if(isset($unitId))
			$userlist->appends(['unit'=>$unitId]);
		if(isset($pos))
			$userlist->appends(['pos'=>$pos]);
		if(isset($realname))
			$userlist->appends(['realname'=>$realname]);

		return view('orgmanage.memberinfo_privilege',
			[   'list'          =>$userlist,
				'unitList'      =>$unitList,
				'posList'       =>$posList,
				'realname'      =>$realname,
				'unitId'        =>$unitId,
				'posId'         =>$pos,
				'status'        =>$status,
				'realname'      =>$realname
			]);
	}

	// 권한관리편집화면
	public function addPrivilege(Request $request) {
		$units = Unit::unitFullNameList();
		$posts = Post::orderBy('orderNum')->get();
		$pmenus = Menu::where('parentId', '=', 0)->get();
		$cmenus = array();
		$index = 0;

		foreach ($pmenus as $pmenu) {
			$cmenus[$index] = array();
			$cmenus[$index] = Menu::where('parentId', $pmenu['id'])->orderBy('id')->get();
			$index++;
		}

		$state = Session::get('state');

		$userid = $request->get('uid');
		if(empty($userid)) {
			if(isset($state) && ($state == 'success')) {
				$userid = Session::get('userId');
			}
		}

		$userinfo = User::find($userid);
		$shipList = ShipRegister::getShipListByOrigin();

		return view('orgmanage.privilege_manage',
			[   'userid'    =>  $userid,
				'userinfo'  =>  $userinfo,
				'shipList'  =>  $shipList,
				'units'     =>  $units,
				'pos'       =>  $posts,
				'pmenus'    =>  $pmenus,
				'cmenus'    =>  $cmenus,
				'state'     =>  $state
			]);
	}

	// Store privilege status
	public function storePrivilege(Request $request) {
		$param = $request->all();
		$userid = $param['userid'];
		if(User::find($userid) == null)
			return back()->with([
				'state' => '不存在的用户。',
				'userId' => $userid
			]);

		$menus = Menu::all();
		// Privilege Check List
		$allowmenus = '';
		foreach ($menus as $menu) {
			if (isset($param[$menu['id']])) {
				$allowmenus = empty($allowmenus) ? $menu['id'] : $allowmenus .','.$menu['id'];
			}
		}

		$insertData = array();
		$insertData = ['menu'     => $allowmenus];

		if(isset($param['shipList'])) {
			$shipList = $param['shipList'];
			$shipListInfo = '';
			foreach($shipList as $item)
				$shipListInfo .= $item . ',';

			$shipListInfo = substr($shipListInfo, 0, strlen($shipListInfo) - 1);
			$insertData['shipList']  = $shipListInfo;
		}

		$user = new User();
		User::where('id', $userid)->update($insertData);

		return redirect()->back()->with(['state'=>'success', 'userId'=>$userid]);
	}
}