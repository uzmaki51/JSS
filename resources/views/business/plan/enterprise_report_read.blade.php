@extends('layout.sidebar')
@section('content')
    <body onload="ShowLists('month');">
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transBusinessManage("title.EnterpriseReport")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.EnterpriseReport_Read")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable" style="height: 34px">
                        <ul class="nav nav-tabs" id="myTab">
                            {{--<li class="active">--}}
                                {{--<a data-toggle="tab" href="#week" onclick="ShowLists('week')">--}}
                                    {{--기업소주보열람--}}
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
                            {{--@include('business.plan.unit_week_read', with(['list'=>$list, 'cur_date'=>$curDate]))--}}
                        {{--</div>--}}

                        <div id="month" class="tab-pane active">
                        </div>
                        <p id="err_message_out" class="error-message"></p>
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
                    var year = $("#cur_year_week").val();
                    var month = $("#cur_month_week").val();
                    var selDate = year + '-' + month + '-1';
                    $.post('reportEnterpriseWeekRead', {'_token': token, 'selDate':selDate}, function (data) {
                        $('#week').html(data);
                        bindWeekTabAction();
                    });
                    break;
                case 'month':
                    var year = $('#cur_year_month').val();
                    $.get('reportEnterpriseMonthRead', {'year': year}, function (data) {
                        $('#month').html(data);
                    });
                    break;
            }
        }

        function ShowListsExcel( type) {

            switch (type){
                case 'week':
                    var year = $("#cur_year_week").val();
                    var month = $("#cur_month_week").val();
                    var selDate = year + '-' + month + '-1';
                    location.href = 'reportEnterpriseWeekReadExcel?selDate='+selDate;
                    break;
                case 'month':
                    var year = $('#cur_year_month').val();
                    location.href = 'reportEnterpriseMonthReadExcel?year='+year;
                    break;
            }
        }

    </script>
@endsection