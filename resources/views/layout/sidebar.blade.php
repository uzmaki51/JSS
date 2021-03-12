@extends('layout.header')
@section('sidebar')
    <style>
        .spinner-preview {
            width:100px;
            height:100px;
            text-align:center;
            margin-top:60px;
        }

        .dropdown-preview {
            margin:0 5px;
            display:inline-block;
        }
        .dropdown-preview  > .dropdown-menu {
            display: block;
            position: static;
            margin-bottom: 5px;
        }
    </style>

    <div class="sidebar menu-min" id="sidebar">

    <ul class="nav nav-list">
        @foreach($GLOBALS['menulist'] as $menu)
            @if ($menu['id'] == $GLOBALS['selMenu'])
                @if (count($menu['submenu']) > 0)
                    <li class="active open">
                @else
                    <li class="active">
                @endif
            @else
                <li>
            @endif

            @if (count($menu['submenu']) > 0)
                <a href="@if(!empty($menu['controller'])){{ url($menu['controller']) }}@endif" class="dropdown-toggle">
                    <i class={{$menu['icon']}}></i>
                    <span class="menu-text"> {{ $menu['title'] }}</span>
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul class="submenu">
                    @foreach($menu['submenu'] as $submenu)
                        @if ($submenu['id'] == $GLOBALS['submenu'])
                            <li class="active">
                        @else
                            <li>
                        @endif
                            <a href="{{ url($submenu['controller']).'?menuId='.$menu['id'].'&submenu='.$submenu['id'] }}">
                                {{ $submenu['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <a href="{{ url($menu['controller']).'?menuId='.$menu['id'] }}">
                    <i class={{$menu['icon']}}></i>
                    <span class="menu-text"> {{ $menu['title'] }}</span>
                </a>
            @endif
        </li>
        @endforeach
    </ul>
    <!-- /.nav-list -->

    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>

    <script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'collapsed')
        } catch (e) {
        }
    </script>

</div>
@yield('content')
@endsection
