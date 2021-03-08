<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))
        <div class="main-content">
            <style>

                .form-control {
                    display: inline;
                    width: 70px;
                    text-align: left;
                }
                .row_height {
                    height: 60px;
                }

            </style>

            <div id="modalback" class="in"></div>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>{{transBusinessManage("title.EnterpriseReport")}}</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                {{transBusinessManage("title.ReportPerson")}}
                            </small>
                        </h4>
                    </div>
                </div>
                <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" data-target="#modal-step-contents">
                            {{transBusinessManage("captions.warning")}}
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class=tabbable">
                            <ul class="nav nav-tabs" id="myTab">
                                <li id="ReportPersonDayTab" class="active">
                                    <a data-toggle="tab" href="#ReportPersonDay" onclick="ShowLists('day', '{{date('Y')}}')">{{transBusinessManage("captions.reportregister")}}</a>
                                </li>
                                {{--<li id="ReportPersonWeekTab" class="">--}}
                                {{--<a data-toggle="tab" href="#ReportPersonWeek" onclick="ShowLists('week', '{{date('Y')}}')">{{transBusinessManage("captions.weekreportregister")}}</a>--}}
                                {{--</li>--}}
                                <li id="ReportPersonMonthTab" class="">
                                    <a data-toggle="tab" href="#ReportPersonMonth" onclick="ShowLists('month', '{{date('Y')}}')">{{transBusinessManage("captions.monthreport_register")}}</a>
                                </li>
                                <li id="ReportAllViewTab" class="">
                                    <a data-toggle="tab" href="#ReportAllView" onclick="ShowLists('all', '{{date('Y')}}')">{{transBusinessManage("captions.companymember_report")}}</a>
                                </li>
                                <li id="PlanManageTab" class="">
                                    <a data-toggle="tab" href="#PlanManage">{{transBusinessManage("captions.taskmanage")}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            {{--일보진행--}}
                            <div id="ReportPersonDay" class="tab-pane active">
                                <div class="space-6"></div>
                                <div>
                                    <div class="col-md-6" style="padding:0px; padding-left:5px">
                                        <select class="col-md-2 select-date" id="week_year">
                                            @for($i=2017; $i<2020; $i++)
                                                <option value="{{$i}}" @if($i==$cur_date['year']) selected @endif>{{$i}}</option>
                                            @endfor
                                        </select>
                                        <span class="col-md-2" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
                                        <select class="col-md-1 select-date" id="week_month">
                                            @for($i=1; $i<13; $i++)
                                                <option value="{{$i}}" @if($i==$cur_date['month']) selected @endif >{{$i}}</option>
                                            @endfor
                                        </select>
                                        <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
                                        <select class="col-md-4 select-date" id="cur_week">
                                            @foreach($weeklist as $week)
                                                <option value="{{$week['week']}}" @if($week['week'] == $cur_date['week']) selected @endif>{{$week['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-right:5px; float:right;">
                                        <button class="btn btn-xs btn-warning no-radius" style="width: 80px">
                                            <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-6" style="width: 100%"></div>
                                @else
                                    @include('layout.excel-style')
                                @endif
                                <table id="PersonDayTable" class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center" style="width: 5%;">{{transBusinessManage("captions.date")}}</th>
                                        <th class="center" style="width: 10%;">{{transBusinessManage("captions.schedulename")}}</th>
                                        <th class="center" style="width: 10%;">{{transBusinessManage("captions.taskname")}}</th>
                                        <th class="center" style="width: 5%">{{transBusinessManage("captions.processstate")}}(%)</th>
                                        <th class="center" style="width: 30%">{{transBusinessManage("captions.plain")}}</th>
                                        <th class="center" style="width: 40%">{{transBusinessManage("captions.report")}}</th>
                                        @if(!isset($excel))
                                            <th style="width: 40px"></th>
                                        @endif
                                    </tr>
                                    </thead>   {{--표머리부--}}
                                    <tbody id="MainPlan_table_body">
                                    @for($index=0; $index<count($all_plans); $index++)
                                        <?php $plan = $all_plans[$index]; ?>
                                        <tr style="height: 30px; @if($plan->active == 1) background-color:#e2fbf3 @endif">
                                            @if(!isset($excel))
                                                <input type="hidden" class="plan_id" value="{{$plan->planId}}">
                                                <input type="hidden" class="report_id" value="{{$plan->id}}">
                                                <input type="hidden" class="plan_date" value="{{$plan->selDate}}">
                                                <input type="hidden" class="rate_summary" value="{{$plan->rate}}">
                                                <input type="hidden" class="plan_summary" value="{{$plan->plan}}">
                                                <input type="hidden" class="report_summary" value="{{$plan->report}}">
                                                <input type="hidden" class="created_summary" value="{!!convert_datetime($plan->update_at)!!}">
                                            @endif
                                            <td class="center days" rowspan="1">
                                                <span id="{{'curDate_'.$plan->id}}">{{$plan->dateStr}}</span>
                                            </td>
                                            <td class="center items" style="background-color: white;">
                                                <span>{{$plan->name}}</span>
                                            </td>
                                            <td class="center tasks" style="background-color: #{{$plan->color}} !important;">
                                                {{$plan->planTitle}}
                                            </td>
                                            <td class="center rate" style="vertical-align: top;">
                                                <span>{{$plan->rate}}</span>@if(!empty($plan->rate)) % @endif<br>
                                                <span style="color:#aaa; font-size:0.8em;">{!!convert_datetime($plan->update_at)!!}</span>
                                            </td>
                                            <td class="plan" style="vertical-align: top;">
                                                {!! nl2br($plan->plan) !!}
                                            </td>
                                            <td class="report" style="vertical-align: top;">
                                                {!! nl2br($plan->report) !!}
                                            </td>
                                            @if(!isset($excel))
                                                <td class="center control action-buttons">
                                                    @if(!empty($plan->name))
                                                        <a class="edit-button" href="javascript:void(0);">
                                                            <i class="blue icon-edit bigger-130"></i>
                                                        </a>
                                                        <a class="save-button" href="javascript:void(0);" style="display:none;">
                                                            <i class="red icon-save bigger-130"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endfor
                                    </tbody>  {{--표내용--}}
                                </table> {{--기본표--}}
                                @if(!isset($excel))
                            </div>
                            {{--주보진행--}}
                            <div id="ReportPersonWeek" class="tab-pane"
                            ></div>
                            {{--월보진행--}}
                            <div id="ReportPersonMonth" class="tab-pane">
                            </div>
                            {{--성원별일보종합--}}
                            <div id="ReportAllView" class="tab-pane">
                            </div>
                            <div id="PlanManage" class="tab-pane">
                                @include('business.plan.plan_manage', with(['main_plans'=>$main_plans, 'sub_plan_list'=>$sub_plan_list]))
                            </div>
                            <p id="err_message_out" class="error-message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var token = '<?php echo csrf_token() ?>';

            var dateList = new Array();
            var index = 0;
                    @foreach($main_plans as  $plan)
            var dateObj = new Object();
            dateObj.start = '{!! convert_date($plan->startDate) !!}';
            dateObj.end = '{!! convert_date($plan->endDate) !!}';
            dateObj.id = '{!! $plan->id !!}';
            dateList[index] = dateObj;
            index++
            @endforeach

            $(function(){

                $('#plan-name').on('change', function () {
                    var selId = $(this).val();
                    for(var i=0;i<dateList.length;i++){
                        var dateObj = dateList[i];
                        if(dateObj.id == selId) {
                            var dateStr = '计划期间:' + dateObj.start + ' ~ ' + dateObj.end;
                            $('#main_plan_date').html(dateStr);
                            break;
                        }
                    }
                });

                $('.select-date').on('change', function () {
                    var year = $('#week_year').val();
                    var month = $('#week_month').val();
                    var week = $('#cur_week').val();

                    location.href = 'reportperson?year='+ year + '&month=' + month + '&week=' + week;
                });

                MergingCellsByDate(".days");
                MergingCellsByDate(".items");
            });

            function MergingCellsByDate(str_class) {
                var aa = $(str_class).children();

                var cnt = 0;
                var trObj = aa.eq(0).closest('tr').children();
                var trDate = trObj.eq(2);
                var oldDate = trDate.val();
                var old_item = aa.eq(0).text();
                var cur_item = aa.eq(0).text();
                var i = 0;
                for (i = 1; i < aa.length; i++) {
                    trObj = aa.eq(i).closest('tr').children();
                    trDate = trObj.eq(2);
                    var curDate = trDate.val();
                    cur_item = aa.eq(i).text();
                    if(oldDate != curDate) {
                        old_item = cur_item;
                        oldDate = curDate;
                        cnt = 0;
                        continue;
                    }
                    oldDate = curDate;
                    if ( cur_item == "" ){
                        old_item = cur_item;
                        continue;
                    }
                    if (cur_item != old_item) {
                        old_item = cur_item;
                        cnt = 0;
                        continue;
                    }
                    cnt++;
                    var tdObj = aa.eq(i).closest('td');
                    tdObj.remove();
                    aa.eq(i-cnt).closest('td').attr('rowspan', cnt + 1);
                }
            }

            function MergingCells(str_class) {
                var aa = $(str_class);

                var cnt = 0;
                var old_item = aa.eq(0).text();
                var cur_item = aa.eq(0).text();
                var i = 0;
                for (i = 1; i < aa.length; i++) {
                    cur_item = aa.eq(i).text();
                    if ( cur_item == "" ){
                        old_item = cur_item;
                        continue;
                    }
                    if (cur_item != old_item) {
                        old_item = cur_item;
                        cnt = 0;
                        continue;
                    }
                    cnt++;
                    old_item = cur_item;
                    var tdObj = aa.eq(i).closest('td');
                    tdObj.remove();
                    aa.eq(i-cnt).closest('td').attr('rowspan', cnt + 1);
                }
            }

            function ShowLists( id, year) {
                if ( year == 0 ){
                    year = $("#cur_year_week").val();
                    console.log(year);
                }
                switch (id){
                    case 'day':
                        location.reload();
                        break;
                    case 'week':
                        $('#ReportPersonMonth').html('');
                        var month = $("#cur_month_week").val();
                        if(month == null)
                            month = 0;
                        $('#err_message_out').html('');
                        $.post('reportPersonUpdateWeekList', {
                            '_token': token, 'year': year, 'month': month
                        }, function (data) {
                            $('#ReportPersonWeek').html(data);
                        });
                        break;
                    case 'month':
                        $('#ReportPersonWeek').html('');
                        $('#err_message_out').html('');
                        $.post('reportPersonUpdateMonthList', {
                            '_token': token, 'year': year
                        }, function (data) {
                            $('#ReportPersonMonth').html(data);
                        });
                        break;
                    case 'all':
                        $('#err_message_out').html('');
                        $.post('reportPersonUpdateAllList', {
                            '_token': token
                        }, function (data) {
                            $('#ReportAllView').html(data);

                            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                                $(this).prev().focus();
                            });

                            MergingCells(".unit");
                            MergingCells(".pos");
                            MergingCells(".realname");
                        });
                        break;
                }
            }

            function ShowListsExcel( id, year) {
                if ( year == 0 ){
                    year = $("#cur_year_week").val();
                }
                switch (id){
                    case 'week':
                        var month = $("#cur_month_week").val();
                        if(month == null)
                            month = 0;
                        location.href = 'reportPersonUpdateWeekListExcel?year=' + year + '&month=' + month;
                        break;
                    case 'month':
                        location.href = 'reportPersonUpdateMonthListExcel?year=' + year;
                        break;
                    case 'all':
                        location.href = 'reportPersonUpdateAllListExcel';
                        break;
                }
            }

            // 일보...
            $('.edit-button').on('click', function(){
                var row = $(this).closest('tr');
                var selectedRow = $('.editedRow');
                var childCount = selectedRow.children().length;
                if(childCount > 0) {
                    var rate_html = '<span>' + selectedRow.find('.rate_summary').val() + '</span><br><span style="color:#aaa; font-size:0.8em;">' + selectedRow.find('.created_summary').val() + '</span>';
                    selectedRow.find('.rate').html(rate_html);
                    selectedRow.find('.plan').text( selectedRow.find('.plan_summary').val());
                    selectedRow.find('.report').text( selectedRow.find('.report_summary').val());
                    selectedRow.find('.edit-button').css('display', 'block');
                    selectedRow.find('.save-button').css('display', 'none');
                    selectedRow.removeClass('editedRow');
                }
                row.addClass('editedRow');
                row.find('.rate').html("<input type=text class='rate_input' value='"+ row.find('.rate_summary').val() +"' style='width:100%;'>");
                row.find('.plan').html("<textarea rows='10' class='plan_input' style='width:100%;'>"+ row.find('.plan_summary').val() +"</textarea>");
                row.find('.report').html("<textarea rows='10' class='report_input' style='width:100%;'>"+ row.find('.report_summary').val() +"</textarea>");
                row.find('.edit-button').css('display','none');
                row.find('.save-button').css('display','block');
            });

            $('.save-button').on('click', function(){
                var row = $(this).closest('tr');
                var newplan = row.find('.plan_input').val();
                var newreport = row.find('.report_input').val();
                var rate = row.find('.rate_input').val();
                var planId = row.find('.plan_id').val();
                var reportId = row.find('.report_id').val();
                alert(reportId);
                var selDate = row.find('.plan_date').val();
                $.post('reportPersonUpdate', {
                    '_token': token, 'planId':planId, 'reportId':reportId, 'plan': newplan, 'report': newreport, 'rate':rate, 'plan_date':selDate
                }, function (data) {
                    var returnCode = parseInt(data);
                    if (returnCode > 0) {
                        window.location.reload();
                    } else if (returnCode == -1) {
                        document.getElementById("err_message_out").innerHTML = "设置的计划项目已经存在。";
                        return;
                    }
                });
            });

            function UpdateLists( str, id, task) {
                switch (str){
                    case 'day':
                        break;
                    case 'week':
                        break;
                    case 'month':
                        var newplan = $("#plan_" + id).val();
                        var newreport = $("#report_" + id).val();
                        var oldplan = $("#plan_" + id).attr("oldvalue");
                        var oldreport = $("#report_" + id).attr("oldvalue");
                        var err_flag = 0;
                        if ( newplan == oldplan && newreport == oldreport){
                            err_flag = 1;
                            document.getElementById("err_message_out").innerHTML = "报告资料未变更。";
                        }
                        if ( newplan == "" ){
                            err_flag = 1;
                            document.getElementById("err_message_out").innerHTML = "请输入计划资料。";
                        }
                        if ( newreport == "" ){
                            err_flag = 1;
                            document.getElementById("err_message_out").innerHTML = "要输入报告资料。";
                        }
                        if (err_flag == 1) return;
                        document.getElementById("err_message_out").innerHTML = "";
                        $.post('reportPersonUpdateMonthList', {
                            '_token': token, 'plan': newplan, 'report': newreport
                        }, function (data) {
                            $('#ReportPersonMonth').html(data);
                            $.gritter.add({
                                title: '成功',
                                text: '成功登陆月报告。',
                                class_name: 'gritter-success'
                            });
                        });
                        break;
                    case 'all':
                        document.getElementById("err_message_out").innerHTML = "";
                        $.post('reportPersonUpdateWeekList', {
                            '_token': token, 'year': year, 'month': month
                        }, function (data) {
                            $('#ReportAllView').html(data);
                        });
                        break;
                }
            }


            function onUpdate(id, plan) {
                var newtask = plan['task'];
                var newcreate_report = plan['create_report'];
                var newstartDate = plan['startDate'];
                var newendDate = plan['endDate'];
                var newrate = $("#rate_" + id).val();
                var newplan = $("#plan_" + id).val();
                var newreport = $("#report_" + id).val();
                var oldrate = $("#rate_" + id).attr("oldvalue");
                var oldplan = $("#plan_" + id).attr("oldvalue");
                var oldreport = $("#report_" + id).attr("oldvalue");
                err_flag=0;
                if ( newrate == oldrate && newplan == oldplan && newreport == oldreport){
                    err_flag = 1;
                    document.getElementById("err_message_out").innerHTML = "日报资料未变更。";
                }
                if ( newrate == "" ){
                    err_flag = 1;
                    document.getElementById("err_message_out").innerHTML = "要输入进行率。";
                }
                if ( newplan == "" ){
                    err_flag = 1;
                    document.getElementById("err_message_out").innerHTML = "请输入计划资料。";
                }
                if ( newreport == "" ){
                    err_flag = 1;
                    document.getElementById("err_message_out").innerHTML = "请输入日报。";
                }
                if (err_flag == 1) return;
                else document.getElementById("err_message_out").innerHTML = "";
                $.post("reportPersonUpdate", {
                    '_token': token, 'task': newtask, 'startDate': newstartDate, 'endDate': newendDate,
                    'rate': newrate, 'plan': newplan, 'report': newreport, 'create_report': newcreate_report
                }, function (data) {
                    var returnCode = parseInt(data);
                    if (returnCode > 0) {
                        window.location.reload();
                    } else if (returnCode == -1) {
                        document.getElementById("err_message_out").innerHTML = "你设置的计划项目已经存在。";
                        return;
                    }
                });
                document.getElementById("err_message_out").innerHTML = "";
            }

            function setHeightRowClass(str_class) {
                var divs = $(str_class).children();
                var i = 0;
                for (i; i < divs.length; i++) {
                    var div_control = divs[i];
                    var className = div_control.className;
                    if ( className == "row" ){
                        div_control.className = "row row_height";
                    }
                }
            }

            function showAllReportList() {
                var selDate = $("#search-date").val();
                var unitName = $("#search_unit").val();
                $.post('reportPersonUpdateAllList', {_token: token, selDate: selDate, unit:unitName}, function (data) {
                    if(data) {
                        $('#ReportAllView').html(data);
                        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                    }
                    MergingCells(".unit");
                    MergingCells(".realname");
                });

            }

            function showReportNextDate() {
                var selDate = $("#search-date").val();
                var unitName = $("#search_unit").val();
                $.post('reportPersonUpdateAllList', {_token: token, selDate: selDate, unit:unitName, state:'next'}, function (data) {
                    if(data) {
                        $('#ReportAllView').html(data);
                        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                    }

                    MergingCells(".unit");
                    MergingCells(".realname");
                });

            }
            function showReportPrevDate() {
                var selDate = $("#search-date").val();
                var unitName = $("#search_unit").val();
                $.post('reportPersonUpdateAllList', {_token: token, selDate: selDate, unit:unitName, state:'prev'}, function (data) {
                    if(data) {
                        $('#ReportAllView').html(data);
                        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                    }

                    MergingCells(".unit");
                    MergingCells(".realname");
                });

            }
            function showReportCurrDate() {
                var unitName = $("#search_unit").val();
                $.post('reportPersonUpdateAllList', {_token: token, selDate: '', unit:unitName, state:'curr'}, function (data) {
                    if(data) {
                        $('#ReportAllView').html(data);
                        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                    }

                    MergingCells(".unit");
                    MergingCells(".realname");
                });

            }
            function showAllReportListExcel() {
                var selDate = $("#search-date").val();
                var unitName = $("#search_unit").val();
                location.href = 'reportPersonUpdateAllListExcel?selDate='+selDate+'&unit='+unitName;
            }

        </script>
    @endif
@endsection