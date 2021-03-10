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
                            {{transBusinessManage("title.UnitReportRead_Write")}}
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
                                    {{--부서주보登记--}}
                                {{--</a>--}}
                            {{--</li>--}}

                            <li class="active">
                                <a data-toggle="tab" href="#month" onclick="ShowLists('month')">
                                {{transBusinessManage("captions.monthreport_register")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        {{--<div id="week" class="tab-pane">--}}
                            {{--@include('business.plan.unit_report_week', with(['list'=>$list, 'cur_date'=>$curDate]))--}}
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

        $(function () {
            bindWeekTabAction();
        });

        function bindWeekTabAction() {
            $('.week-edit').on('click', function () {
                var row = $(this).closest('tr');
                var selectedRow = $('.editedRow');
                var childCount = selectedRow.children().length;
                if (childCount > 0) {
                    selectedRow.find('.plan').html(selectedRow.find('.plan_input').val());
                    selectedRow.find('.report').text(selectedRow.find('.report_input').val());
                    selectedRow.find('.week-edit').css('display', 'block');
                    selectedRow.find('.week-save').css('display', 'none');
                    selectedRow.removeClass('editedRow');
                }
                row.addClass('editedRow');
                row.find('.plan').html("<textarea rows='10' class='plan_input' style='width:100%;'>" + row.find('.plan-plan').val() + "</textarea>");
                row.find('.report').html("<textarea rows='10' class='report_input' style='width:100%;'>" + row.find('.plan-report').val() + "</textarea>");
                row.find('.week-edit').css('display', 'none');
                row.find('.week-save').css('display', 'block');
            });

            $('.week-save').on('click', function () {
                var month = $('#cur_month_week').val();
                var row = $(this).closest('tr');
                var newplan = row.find('.plan_input').val();
                var newreport = row.find('.report_input').val();
                var reportId = row.find('.plan-id').val();
                var planYear = row.find('.plan-year').val();
                var planWeek = row.find('.plan-week').val();

                $.post('reportUnitUpdateWeekList', {
                    '_token': token,
                    'plan': newplan,
                    'report': newreport,
                    'reportId': reportId,
                    'year': planYear,
                    'week': planWeek,
                    'month': month
                }, function (data) {
                    $('#UnitWeekReport').html(data);
                    bindWeekTabAction();
                    $.gritter.add({
                        title: '成功',
                        text: '登记部门周报成功！',
                        class_name: 'gritter-success'
                    });
                });
            });
        }

        function bindMonthTabAction() {
            $('.month-edit').on('click', function () {
                var row = $(this).closest('tr');
                var selectedRow = $('.editedMonth');
                var childCount = selectedRow.children().length;
                if (childCount > 0) {
                    selectedRow.find('.plan').html(selectedRow.find('.plan_input').val());
                    selectedRow.find('.report').text(selectedRow.find('.report_input').val());
                    selectedRow.find('.month-edit').css('display', 'block');
                    selectedRow.find('.month-save').css('display', 'none');
                    selectedRow.removeClass('editedMonth');
                }
                row.addClass('editedMonth');
                row.find('.plan').html("<textarea rows='10' class='plan_input' style='width:100%;'>" + row.find('.plan-plan').val() + "</textarea>");
                row.find('.report').html("<textarea rows='10' class='report_input' style='width:100%;'>" + row.find('.plan-report').val() + "</textarea>");
                row.find('.month-edit').css('display', 'none');
                row.find('.month-save').css('display', 'block');
            });

            $('.month-save').on('click', function () {
                var month = $('#cur_month_week').val();
                var row = $(this).closest('tr');
                var newplan = row.find('.plan_input').val();
                var newreport = row.find('.report_input').val();
                var reportId = row.find('.plan-id').val();
                var year = row.find('.plan-year').val();
                var month = row.find('.plan-month').val();

                $.post('reportUnitUpdateMonthList', {
                    '_token': token,
                    'plan': newplan,
                    'report': newreport,
                    'reportId': reportId,
                    'year': year,
                    'month': month
                }, function (data) {
                    $('#UnitMonthReport').html(data);
                    bindMonthTabAction();
                    $.gritter.add({
                        title: '成功',
                        text: '登记月报成功',
                        class_name: 'gritter-success'
                    });
                });
            });
        }

        function ShowLists( type) {

            switch (type){
                case 'week':
                    var year = $("#cur_year_week").val();
                    var month = $("#cur_month_week").val();
                    var selDate = year + '-' + month + '-1';
                    $.get('reportUnitWeek', {'_token': token, 'selDate':selDate}, function (data) {
                        $('#week').html(data);
                        bindWeekTabAction();
                    });
                    break;
                case 'month':
                    var year = $('#cur_year').val();
                    $.get('reportUnitMonth', {'year': year}, function (data) {
                        $('#month').html(data);
                    });
                    break;
            }
        }

        function UpdateLists( str) {
            var reportId = id;
            switch (str){
                case 'week':
                    var year = $("#cur_year_week").val();
                    var month = $("#cur_month_week").val();

                    $.post('reportUnitUpdateWeekList', {
                        '_token': token, 'year':year, 'month':month
                    }, function (data) {
                        $('#UnitWeekReport').html(data);
                    });
                    break;
                case 'month':
                    var year = $("#cur_year").val();
                    $('#err_message_out').html('');
                    $.post('reportUnitUpdateMonthList', {
                        '_token': token, 'year':year
                    }, function (data) {
                        $('#UnitMonthReport').html(data);
                    });
                    break;
            }
        }


    </script>
@endsection