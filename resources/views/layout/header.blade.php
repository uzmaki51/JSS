<!DOCTYPE html>
<html lang="cn">
    <head>
        <title>{{ env('APP_NAME') }}</title>
        <meta charset="utf-8"/>
        <meta name="description" content="overview &amp; stats"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- STYLES -->
        <link rel="stylesheet" href="{{ asset('/assets/css/theme.css') }}"/>
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/css/bootstrap-overrides.css') }}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}"/>
        <link rel="icon" type="image/png" href="{{ cAsset('/assets/css/img/logo.png') }}" sizes="192x192">
        <link href="{{ asset('/assets/css/chosen.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/assets/css/colorbox.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/css/ace.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/assets/css/ace-rtl.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/assets/css/ace-skins.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/assets/css/jquery.gritter.css') }}" />
        <link rel="stylesheet" href="{{ asset('/assets/css/base.css') }}" />
        <link href="{{ asset('/assets/css/datepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/bootstrap-timepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/daterangepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/colorpicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/jquery-ui-1.10.3.full.min.css')}}" rel="stylesheet">
        <link href="{{ asset('/assets/css/jquery.treeview.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/common.css') }}" rel="stylesheet">

        <!-- SCRIPTS -->
        @yield('styles')
        <script src="{{ asset('/assets/js/ace-extra.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery-2.0.3.min.js') }}"></script>
        <script src="{{ asset('/assets/js/ace-elements.min.js') }}"></script>
        <script src="{{ asset('/assets/js/fuelux/fuelux.tree.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.treeview.js') }}"></script>
        <script src="{{ asset('/assets/js/bootbox.min.js') }}"></script>
        <script src="{{ asset('/assets/js/ace.min.js') }}"></script>
        <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/assets/js/typeahead-bs2.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
        <script src="{{ asset('/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('/assets/js/chosen.jquery.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.gritter.min.js')}}"></script>
        <script src="{{ asset('/assets/js/jquery.toast.min.js')}}"></script>
        <script src="{{ asset('/assets/js/jquery.slides.js')}}"></script>
        <script src="{{ asset('/assets/js/util.js')}}"></script>
    </head>

    <body class="skin-1">
        <header id="header">
            <div class="navbar navbar-inverse navbar-static-top" role="navigation">
                <div class="container">
                    <div class="navbar-header" style="width:10%;">
                        <a href="/home" class="navbar-brand">
                            <img class="navbar-img" src="{{ asset('/assets/avatars/logo.png') }}" alt=""/>
                        </a>

	                    <?php
                            $routeName = Route::getCurrentRoute()->getPath();/*var_dump($routeName);die;*/
                            $menuList = Session::get('menusList');
                            $id = Request::get('menuId');
                        ?>
                        <div class="parent-menu">
                            <ul id="topmenu" class="nav navbar-nav">
                                <li class="{{ $routeName == 'home' ? 'active' : '' }}">
                                    <a href="/">{{ trans('home.title.dashboard') }}</a>
                                </li>
                                @foreach($menuList as $key => $item)
                                    @if($item['parent'] == 0)
                                        <li style="background: {{ in_array($id, $item['ids']) ? '#9ecbff' : '' }}!important">
                                            @if($item['controller'] == '')
                                                <a href="/{{ $item['children'][0]['controller'] . '?menuId=' . $item['id'] }}">{{ $item['title'] }}</a>
                                            @else
                                                <a href="/{{ $item['controller'] . '?menuId=' . $item['id'] }}">{{ $item['title'] }}</a>
                                            @endif
                                            <ul class="sub-menu" style="display: none;">
                                                @foreach($item['children'] as $key => $sub)
                                                    <li>
                                                        <a href="/{{ $sub['controller'] . '?menuId=' . $sub['id']  }}">{{ $sub['title'] }}</a>
                                                        <ul class="third-menu clearfix">
                                                            @foreach($sub['children'] as $value)
                                                                <li><a href="/{{ $value['controller'] }}">{{ $value['title'] }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="collapse navbar-collapse navbar-ex1-collapse" role="navigation">
                        <ul class="nav navbar-nav navbar-right" style="position: absolute; right: 2%;">
                            <li class="dropdown" style="height: auto;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background: transparent;">
                                    <img src="{{url('img/user.png')}}" height="24" width="24" style="vertical-align: middle; border-radius: 50%;">
                                    欢迎 | {{ Auth::user()->realname }}<b class="caret"></b></a>
                                <ul class="dropdown-menu" style="background: #5b79a5;">
                                    <li><a href="{{ url('/home/resetPassword') }}"><i class="icon-lock"></i>&nbsp;&nbsp;&nbsp;{{ trans('common.label.change_pass') }}</a></li>
                                    <hr style="margin: 4px 0!important;">
                                    <li><a href="/auth/logout"><i class="icon-signout"></i>&nbsp;&nbsp;{{ trans('common.label.logout') }}</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <script>
            var PUBLIC_URL = '{{ cAsset('/') . '/' }}';
            var BASE_URL = PUBLIC_URL;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

        </script>

        <div class="main-container" id="main-container">
            <div class="main-container-inner">
                @yield('sidebar')
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="icon-double-angle-up icon-only bigger-110"></i>
            </a>
        </div>

        <footer class="footer d-none">
            <p class="footer-title">
                <a href="/"><span class="blue bolder" style="line-height: 1;">JSS</span></a>&nbsp; &nbsp;船舶管理信息系统 ©  {{ (date('Y') - 1) . ' ~ ' . date('Y')  }}</span>&nbsp; &nbsp;
            </p>
        </footer>

        <script type="text/javascript">
            window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
        </script>
        <script src="{{ asset('js/__common.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.inputlimiter.min.js') }}"></script>
        <script src="{{ asset('/assets/js/jquery.maskedinput.min.js') }}"></script>
        <script src="{{ asset('/assets/js/ship_process.js') }}"></script>
    </body>
</html>
