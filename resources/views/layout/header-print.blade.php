<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:04 GMT -->
<head>
    <meta charset="utf-8"/>
    <title>{{ t('Title') }}</title>

    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- basic styles -->

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap-overrides.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome-ie7.min.css') }}"/>
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->


    <!-- ace styles -->
    <link href="{{ asset('/assets/css/chosen.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/colorbox.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace-rtl.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace-skins.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/jquery.gritter.css') }}" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace-ie.min.css') }}"/>
    <![endif]-->

    <!-- inline styles related to this page -->


    <link href="{{ asset('/assets/css/datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/bootstrap-timepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/colorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/jquery-ui-1.10.3.full.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/assets/css/jquery.treeview.css') }}" rel="stylesheet">

    <!--自己정의 스타일-->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('/css/style.css') }}" rel="stylesheet">--}}

    <!-- ace settings handler -->

    <script src="{{ asset('/assets/js/ace-extra.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery-2.0.3.min.js') }}"></script>

    <script src="{{ asset('/assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('/assets/js/fuelux/fuelux.tree.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.treeview.js') }}"></script>
    <script src="{{ asset('/assets/js/bootbox.min.js') }}"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="{{ asset('/assets/js/html5shiv.js') }}"></script>
    <script src="{{ asset('/assets/js/respond.min.js') }}"></script>

    <![endif]-->

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
    <script src="{{ asset('/assets/js/excanvas.min.js') }}"></script>
    <![endif]-->

    <!-- ace scripts -->

    <script src="{{ asset('/assets/js/ace.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/typeahead-bs2.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.gritter.min.js')}}"></script>

    <style>
        .main-content {
            margin-left: 0px;
        }
         input[type="text"], select {
             border: none !important;
             background: transparent !important;
             -webkit-appearance: none;
         }
        .main-container{
            margin-top: -30px!important;
        }
        td{
            font-size: 12px !important;
            line-height: 1.0 !important;
            height: 20px !important;
        }
        table>tbody>tr>td{
            padding: 0px !important;
        }
        h5{
            margin-top: 0px !important;
            margin-bottom: 0px !important;
        }
    </style>
</head>

<body>

<div class="main-container" id="main-container">
    <div class="main-container-inner">

        @yield('content')

    </div>
</div>
<!-- /.main-container -->

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
</script>
<![endif]-->

<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/js/ship_process.js') }}"></script>



</body>

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:31 GMT -->
</html>
