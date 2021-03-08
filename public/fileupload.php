<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:04 GMT -->
<head>
    <meta charset="utf-8"/>
    <title>{{ t('Title') }}</title>

    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- basic styles -->

    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->


    <!-- ace styles -->
    <link href="/assets/css/chosen.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/colorbox.css" />
    <link rel="stylesheet" href="/assets/css/ace.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css"/>
    <link rel="stylesheet" href="/assets/css/jquery.gritter.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->


    <link href="/assets/css/datepicker.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-timepicker.css" rel="stylesheet">
    <link href="/assets/css/daterangepicker.css" rel="stylesheet">
    <link href="/assets/css/colorpicker.css" rel="stylesheet">
    <link href="/assets/css/jquery-ui-1.10.3.full.min.css" rel="stylesheet">
    <link href="/assets/css/jquery.treeview.css" rel="stylesheet">

    <!--자체정의 스타일-->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/ajh.css" rel="stylesheet">

    <!-- ace settings handler -->

    <script src="/assets/js/ace-extra.min.js"></script>
    <script src="/assets/js/jquery-2.0.3.min.js"></script>


    <script src="/assets/js/ace-elements.min.js"></script>
    <script src="/assets/js/fuelux/fuelux.tree.min.js"></script>
    <script src="/assets/js/jquery.treeview.js"></script>
    <script src="/assets/js/bootbox.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>

    <![endif]-->

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
    <script src="/assets/js/excanvas.min.js"></script>
    <![endif]-->

    <!-- ace scripts -->

    <script src="/assets/js/ace.min.js"></script>

    <!-- inline scripts related to this page -->
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/typeahead-bs2.min.js"></script>
    <script src="/assets/js/jquery-ui-1.10.3.full.min.js"></script>
    <script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/js/date-time/bootstrap-datepicker.min.js"></script>
    <script src="/assets/js/chosen.jquery.min.js"></script>

    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/jquery.dataTables.bootstrap.js"></script>

</head>

<body>

<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
</script>
<![endif]-->

<script src="/assets/js/jquery.validate.min.js"></script>



</body>

<!-- Mirrored from 192.69.216.111/themes/preview/ace/ by HTTrack Website Copier/3.x [XR&CO'2013], Tue, 10 Dec 2013 00:48:31 GMT -->
<div class="row">
    <p><strong><h2 style="text-align: center">파일압로드를 위한 시험페지</h2></strong></p>
    <div class="col-md-12">

        <div class="col-md-offset-6 col-md-3">
    <form metho="POST" id="uploadForm" name="uploadForm" enctype="multipart/form-data">
        <input type="file" name="uploadfile" id="uploadfile">
    </form>


        </div>

    </div>
    <div class="col-md-offset-3 col-md-6">
        <img src="" id="image" style="width:100px; height: 100px">

    </div>
</div>
<script type="text/javascript">
    $('#uploadfile').change(function(){
        console.log('adafasd');
        var f=new FileReader($(this).val());

    });
</script>
</html>
