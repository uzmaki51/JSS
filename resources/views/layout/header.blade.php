<!DOCTYPE html>
<html lang="cn">

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:04 GMT -->
<head>
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8"/>
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

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
    <link rel="stylesheet" href="{{ asset('/assets/css/theme.css') }}"/>
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/common.css') }}" rel="stylesheet">
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
                <?php $routeName = Route::getCurrentRoute()->getPath();?>
                <div class="parent-menu">
                    <ul id="topmenu" class="nav navbar-nav">
                        <li class="{{ $routeName == 'home' ? 'active' : '' }}">
                            <a href="/">{{ trans('home.title.dashboard') }}</a>
                        </li>
                        @foreach($GLOBALS['topMenu'] as $topMenu)
                            @if ($routeName != 'home' && $GLOBALS['topMenuId'] == $topMenu->id)
                                <li class="" style="background: #9ecbff!important;">
                            @else
                                <li class="">
                            @endif
                                <a id="n0" class="home " href='{{$topMenu->controller}}'>{{$topMenu->title}}</a>
                                <ul class="sub-menu" style="display: none;">
                                    @foreach($topMenu['submenu'] as $submenu)
                                    <li>
                                        @if (count($submenu['thirdmenu']) > 0)
                                        <a href="{{ url($submenu['thirdmenu'][0]->controller).'?menuId='.$submenu['id'].'&submenu='.$submenu['thirdmenu'][0]['id']}}">{{$submenu->title}}</a>
                                        <ul class="third-menu clearfix" style="display: none;">
                                            @foreach($submenu['thirdmenu'] as $thirdmenu)
                                            <li><a href="{{ url($thirdmenu['controller']).'?menuId='.$submenu['id'].'&submenu='.$thirdmenu['id'] }}">{{$thirdmenu->title}}</a></li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <a href="{{ url($submenu['controller']).'?menuId='.$submenu['id'] }}">{{$submenu->title}}</a>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- /.navbar-header -->

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
        <audio id="alarm-sound" style="display: none">
            <source src="/img/notify.wav" type="audio/wav">
        </audio>
        <!-- /.container -->
    </div>
</header>

<div class="main-container" id="main-container">
    <div class="main-container-inner">

        @yield('sidebar')

    </div>

    <!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div>
<!-- /.main-container -->
<footer class="footer d-none">
    <p class="footer-title">
        <a href="/"><span class="blue bolder" style="line-height: 1;">JSS</span></a>&nbsp; &nbsp;船舶管理信息系统 ©  {{ (date('Y') - 1) . ' ~ ' . date('Y')  }}</span>&nbsp; &nbsp;
    </p>

</footer>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
</script>
<![endif]-->
<!-- Scripts -->
<script>
    var PUBLIC_URL = '{{ cAsset('/') . '/' }}';
    var BASE_URL = PUBLIC_URL;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

</script>
<script>
    var myAlarm = document.getElementById("alarm-sound");
</script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('/assets/js/ship_process.js') }}"></script>



</body>

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:31 GMT -->
</html>
