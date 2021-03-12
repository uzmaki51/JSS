<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/10/2017
 * Time: 4:15 PM
 */
namespace App\Http\Controllers\Notice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Util;
use App\Models\Menu;
use App\Models\UserInfo;

use App\Models\Board\News;
use App\Models\Board\NewsTema;
use App\Models\Board\NewsResponse;
use App\Models\Board\NewsRecommend;
use App\Models\Board\NewsHistory;

use Auth;




class NoticeController extends Controller
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
        foreach($topMenu as $menu) {
            $menu['submenu'] = Menu::where('parentId', '=', $menu['id'])->get();
            foreach($menu['submenu'] as $submenu)
            {
                $submenu['thirdmenu'] = Menu::where('parentId', '=', $submenu['id'])->get();
            }
        }
		$GLOBALS['topMenu'] = $topMenu;
        $GLOBALS['topMenuId'] = 8;

        $user = Auth::user();
        if ($admin > 0) {
            $menulist = Menu::where('parentId', '=', '8')->get();
            foreach ($menulist as $menu) {
                $menuId = $menu['id'];
                $submenus = Menu::where('parentId', '=', $menuId)->get();
                $menu['submenu'] = $submenus;
            }
            $GLOBALS['menulist'] = $menulist;
        } else {
            $profile = UserInfo::find($user->id);
            if(in_array(3, explode(',', $user['menu']))) {
                $menulist = Menu::where('parentId', '=', '8')->where('admin', '=', '0')->get();
                foreach ($menulist as $menu) {
                    $menuId = $menu['id'];
                    if($menuId == 37 && !$profile->attend_admin)
                        $submenus = Menu::where('parentId', '=', $menuId)->whereIn('id', [39, 41, 44])->get();
                    else if($menuId == 38 && !$profile->report_admin)
                        $submenus = Menu::where('parentId', '=', $menuId)->whereIn('id', [45, 48, 51])->get();
                    else
                        $submenus = Menu::where('parentId', '=', $menuId)->get();

                    $menu['submenu'] = $submenus;
                }
                $GLOBALS['menulist'] = $menulist;
            } else {
                $menulist = Menu::where('parentId', '=', '8')->where('admin', '=', '0')->whereIn('id', explode(',', $user['menu']))->get();
                foreach ($menulist as $menu) {
                    $menuId = $menu['id'];
                    if($menuId == 37 && !$profile->attend_admin)
                        $submenus = Menu::where('parentId', '=', $menuId)->whereIn('id', [39, 41, 44])->get();
                    else if($menuId == 38 && !$profile->report_admin)
                        $submenus = Menu::where('parentId', '=', $menuId)->whereIn('id', [45, 48, 51])->get();
                    else
                        $submenus = Menu::where('parentId', '=', $menuId)->get();
                    $menu['submenu'] = $submenus;
                }
                $GLOBALS['menulist'] = $menulist;
            }
        }
    }
//-------------  전자게시판의 토론마당관리   ---------------------
    public function index()
    {
        return redirect('notice/newsTemaPage');
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
        if ($temaId != null) {
            Session::put('temaId', $temaId);
        } else {
            $temaId = Session::get('temaId');
        }
        $tema = NewsTema::find($temaId);
        $list = News::getNewsListForTema($temaId);

        return view('notice.news.news_viewer', array('tema'=>$tema, 'list'=>$list));
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
            return redirect('/notice/createNewsPage/'.$newsId.'.htm');
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

        return view('notice.news.news_detail', array('news'=>$news, 'tema'=>$tema, 'list'=>$list));
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

        return view('notice.news.news_write', array('tema'=>$tema, 'news'=>$news));
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

        return view('notice.news.news_response', array('list'=>$list));
    }

    public function createNewsPage(Request $request) {

        $GLOBALS['selMenu'] = 31;  // 계시판
        $GLOBALS['submenu'] = 0;

        $temaId = $request->get('tema');
        $tema = NewsTema::find($temaId);

        return view('notice.news.news_write', array('tema'=>$tema));
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

        return redirect('notice/showNewsListForTema?temaId='.$temaId);
    }

}