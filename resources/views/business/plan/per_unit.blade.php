@extends('layout.sidebar')
@section('content')
    <body onload="ShowLists('month')">
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transBusinessManage("title.EnterpriseReport")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.PerUnit")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            {{--<li class="active">--}}
                                {{--<a data-toggle="tab" href="#week" onclick="ShowLists('week')">--}}
                                    {{--부서별주보열람--}}
                                {{--</a>--}}
                            {{--</li>--}}

                            <li class="active">
                                <a data-toggle="tab" href="#month" onclick="ShowLists('month')">
                                {{transBusinessManage("captions.monthreport_read")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        {{--<div id="week" class="tab-pane active">--}}
                            {{--@include('business.plan.per_unit_week', with(['list'=>$list, 'weekList'=>$weekList, 'cur_date'=>$curDate]))--}}
                        {{--</div>--}}

                        <div id="month" class="tab-pane active">
                        </div>
                    </div>
                </div><!-- /span -->

                <div class="vspace-xs-12"></div>
            </div>
        </div>
    </div>
    </body>

    <script>
        var token = '{!! csrf_token() !!}';
        function ShowLists( type) {

            switch (type){
                case 'week':
                    var year = $("#week_year").val();
                    var month = $("#week_month").val();
                    var week = $("#cur_week").val();
                    $.get('reportPerUnit', {'year':year, 'month':month, 'week':week}, function (data) {
                        $('#week').html(data);
                    });
                    break;
                case 'month':
                    var year = $("#cur_year").val();
                    var month = $("#cur_month").val();

                    $.get('reportPerUnitMonth', {'year': year, 'month':month}, function (data) {
                        $('#month').html(data);
                    });
                    break;
            }
        }
        function ShowListsExcel( type) {

            switch (type){
                case 'week':
                    var year = $("#week_year").val();
                    var month = $("#week_month").val();
                    var week = $("#cur_week").val();
                    location.href = 'reportPerUnitExcel?year='+year+'&month='+month+'&week='+week;
                    break;
                case 'month':
                    var year = $("#cur_year").val();
                    var month = $("#cur_month").val();
                    location.href = 'reportPerUnitMonthExcel?year='+year+'&month='+month;
                    break;
            }
        }


    </script>
@endsection