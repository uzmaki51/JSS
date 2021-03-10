@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <link rel="stylesheet" href="/assets/css/fullcalendar.css" />
        <style>
            .day-content {
                min-height: 400px;
            }

            .fc-widget-content {
                background: white;
            }
            .fc-state-highlight {
                background: #E4C172;
            }
        </style>

        <div class="page-content">
            <div class="col-md-12">
                <div class="row">
                    <div class="page-header">
                        <div class="col-md-6">
                            <h4><b>{{transBusinessManage("title.Schedule")}}</b>
                            </h4>
                        </div>
                    </div>
                    <div id="dialog-add-modify" class="hide">
                        <form class="form-horizontal">
                            <input class="hidden" id="schId" value="">
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedule_name")}}:</label>

                                <div class="col-md-8"><input type="text" id="schedule_name" class="form-control"
                                                             style="width: 100%" value=""></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedule_content")}}:</label>

                                <div class="col-md-8"><textarea id="schedule_descript" class="form-control"
                                                                style="width: 100%"></textarea></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.startdate")}}:</label>
                                <div class="input-group col-md-4">
                                    <input id="schedule_start_date" class="form-control date-picker" type="text"
                                           data-date-format="yyyy-mm-dd" value="">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                                <label class="col-md-1 control-label no-padding-right">{{transBusinessManage("captions.time")}}:</label>
                                <div class="input-group bootstrap-timepicker col-md-3">
                                    <input id="schedule_start_time" class="time-picker" type="text" class="form-control" style="width:80px"/>
                                    <span class="input-group-addon">
                                        <i class="icon-time bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.enddate")}}:</label>
                                <div class="input-group col-md-4">
                                    <input id="schedule_end_date" class="form-control date-picker" type="text"
                                           data-date-format="yyyy-mm-dd" value="">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-7">
                                    <span>{{transBusinessManage("captions.memberlist")}}</span>
                                </div>
                                <div class="col-md-5">
                                    <span>{{transBusinessManage("captions.invited_schedule_member")}}</span>
                                </div>
                                <div class="col-md-6">
                                    <div style="border:1px solid #eee;height:200px;overflow-y: auto">
                                        {!! $result !!}
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding:70px 2px;width:25px">
                                    <a class="btn btn-xs btn-white" id="add_user">
                                        <i class="icon-arrow-right icon-on-right"></i>
                                    </a>
                                    <div class="space-2"></div>
                                    <a class="btn btn-xs btn-white" id="del_user">
                                        <i class="icon-arrow-left bigger-50"></i>
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <select id="attend_user" multiple="multiple" style="height: 200px;width:100%">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <span style="color:red;margin-left:30px" id="message_out"></span>
                            </div>
                        </form>
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
                </div>

                <div class="row">
                    <div class="fc fc-ltr">
                        <table class="fc-header" style="width:100%">
                            <tbody>
                            <tr>
                                <td class="fc-header-left"></td>
                                <td class="fc-header-center">
                                        <span class="fc-header-title">
                                            <h2>{{ $titleDate }}</h2>
                                        </span>
                                </td>
                                <td class="fc-header-right">
                                    <span class="fc-button fc-button-today fc-state-default fc-corner-left fc-corner-right fc-state-disabeled"  style="width: 80px; text-align: center">
                                    {{transBusinessManage("captions.today")}}
                                    </span>
                                    <span class="fc-header-space"></span>
                                        <span class="fc-button fc-button-prev fc-state-default fc-corner-left" style="width: 80px; text-align: center">
                                            <i class="icon-chevron-left"></i>
                                            {{transBusinessManage("captions.prevweek")}}
                                        </span>
                                        <span class="fc-button fc-button-next fc-state-default fc-corner-left" style="width: 80px; text-align: center">
                                        {{transBusinessManage("captions.nextweek")}}
                                            <i class="icon-chevron-right"></i>
                                        </span>
                                    <span class="fc-header-space"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="fc-content">
                            <div class="fc-view fc-view-agendaWeek fc-agenda" unselectable="on">
                                <table style="width:100%" class="fc-agenda-days fc-border-separate" cellspacing="0">
                                    <thead>
                                    <tr class="fc-first fc-last">
                                        <th class="fc-agenda-axis fc-widget-header fc-first" style="width: 50px;">&nbsp;</th>
                                        <?php $week = ['日', '月','火', '水', '木', '金', '土']; ?>
                                        @for($i=0; $i < 7; $i++)
                                            <?php $date = new DateTime($firstDate);
                                                  $curDate = $date->modify("+$i days");
                                                  $weekNum = $date->format('w'); $weekStr = $week[$weekNum]; ?>
                                            <th class="fc-sun fc-col0 fc-widget-header @if($i == 6) fc-last @endif">{{ $curDate->format('n / j (').$weekStr.')' }}</th>
                                        @endfor
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="fc-first fc-last">
                                        <th class="fc-agenda-axis fc-widget-header fc-first">{{transBusinessManage("captions.schedule")}}</th>
                                        @for($i=0; $i < 7; $i++)
                                            <?php $date = new DateTime($firstDate);
                                                $curDate = $date->modify("+$i days");
                                                $datestr = $curDate->format('Y-m-d'); ?>
                                            <td class="fc-widget-content @if($datestr == date('Y-m-d')) fc-today fc-state-highlight @endif @if($i == 0) fc-first @elseif($i==6) fc-last @endif">
                                                <div class="day-content" data-date="{{ $datestr }}">
                                                    <div class="fc-day-content">
                                                        @foreach($list as $schedule)
                                                            @if($datestr == $schedule->startDate)
                                                            <div style="padding-bottom: 5px">
                                                                <?php $start = new DateTime($schedule->startDate);
                                                                    $startStr = $start->format('Y年n月j日');
                                                                    $startTime = substr($schedule->startTime, 0, 5); ?>
                                                                <a data-id="{{ $schedule->id }}" data-auth="{{$schedule->auth}}" title="(시작)[{{$startStr.'('.$startTime.')'}}] {{$schedule->descript}}">- {{ $schedule->title. ' '.$startTime}}<br>&nbsp;&nbsp;{{ $schedule->attend_user }}</a>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        @endfor
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="/assets/js/date-time/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript">

        var token = '<?php echo csrf_token() ?>';
        var userId = '{{ \Illuminate\Support\Facades\Auth::user()->id }}';
        $(function() {

            $('.time-picker').timepicker({
                minuteStep: 1,
                showSeconds: false,
                showMeridian: false
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

            $('.day-content').on('click', function () {
                var date = $(this).data('date');
                $('#schId').val('');
                $('#schedule_start_date').val(date);
                $('#schedule_end_date').val(date);
                var checkitems = $('.chkUser');
                for (var i = 0; i < checkitems.length; i++)
                    checkitems.eq(i).prop('checked', false);
                $('#attend_user').html('');

                $('#schedule_name').val('');
                $('#schedule_descript').val('');
                $('#schedule_end_date').removeAttr('disabled');
                editSchedule();
            });

            $('#add_user').on('click', function () {
                var checkitems = $('.chkUser:checked');
                var userlist = new Array();
                for (var i = 0; i < checkitems.length; i++) {
                    var user = new Array();
                    user['id'] = checkitems.eq(i).parent().attr('id');
                    user['realname'] = checkitems.eq(i).next().text();
                    userlist.push(user);
                }

                //선택된 리용자들을 목록에 追加
                var listitem = $('#attend_user').find('option');
                for( var i = 0; i < userlist.length; i++)
                {
                    var isExist = 0;
                    for(var j=0;j<listitem.length; j++) {
                        var selId = listitem.eq(j).val();
                        if(userlist[i]['id'] == selId) {
                            isExist = 1;
                            break;
                        }
                    }
                    if(isExist)
                        continue;

                    var html = '<option value="' + userlist[i]['id'] + '">' + userlist[i]['realname'] + '</option>';
                    $('#attend_user').append(html);
                }
            });

            $('#del_user').on('click', function () {
                var selectedItems = $('#attend_user').find('option:selected');
                var memberList = $('.chkUser');
                for (var i = 0; i < selectedItems.length; i++) {
                    var userId = selectedItems.eq(i).attr('value');
                    for(var j=0; j<memberList.length; j++) {
                        var memberId = memberList.eq(j).parent().attr('id');
                        if(memberId == userId) {
                            memberList.eq(j).prop('checked', false);
                            break;
                        }
                    }
                    selectedItems.eq(i).remove();
                }
            });

            $('.fc-button-today').on('click', function () {
                location.href = 'personSchedule';
            });
            $('.fc-button-prev').on('click', function () {
                location.href = 'personSchedule?selDate={{ $prev }}';
            });
            $('.fc-button-next').on('click', function () {
                location.href = 'personSchedule?selDate={{ $next }}';
            });

            $('.fc-day-content a').on('click', function () {
                var schId = $(this).data('id');
                var schAuth = $(this).data('auth');

                $.post("getScheduleInfo", {'_token': token, 'schId':schId}, function (data) {

                    if(schAuth == 0) {
                        $.gritter.add({
                            title: '错误',
                            text: '不是你创造的日程。',
                            class_name: 'gritter-error'
                        });
                        $("#dialog-add-modify").dialog("close");
                        return;
                    }
                    $('#schId').val(schId);
                    $('#schedule_name').val(data.title);
                    $('#schedule_descript').val(data.descript);
                    $('#schedule_start_date').val(data.startDate);
                    $('#schedule_end_date').val(data.endDate);
                    $('#schedule_start_time').val(data.startTime);
                    $('#schedule_end_date').prop('disabled', 'disabled');

                    var checkitems = $('.chkUser');
                    var userlist = new Array();
                    for (var i = 0; i < checkitems.length; i++) {
                        var userId = checkitems.eq(i).parent().attr('id');
                        var isEqual = 0;
                        for(var j=0;j<data.attend_user.length;j++) {
                            if(userId == data.attend_user[j]) {
                                isEqual = 1;
                                break;
                            }
                        }

                        if(!isEqual) {
                            checkitems.eq(i).prop('checked', false);
                            continue;
                        }

                        var user = new Array();
                        user['id'] = checkitems.eq(i).parent().attr('id');
                        user['realname'] = checkitems.eq(i).next().text();
                        userlist.push(user);
                        checkitems.eq(i).prop('checked', true);
                    }

                    //선택된 리용자들을 목록에 追加
                    var listitem = $('#attend_user').find('option');
                    for( var i = 0; i < userlist.length; i++)
                    {
                        var isExist = 0;
                        for(var j=0;j<listitem.length; j++) {
                            var selId = listitem.eq(j).val();
                            if(userlist[i]['id'] == selId) {
                                isExist = 1;
                                break;
                            }
                        }

                        if(isExist)
                            continue;
                        var html = '<option value="' + userlist[i]['id'] + '">' + userlist[i]['realname'] + '</option>';
                        $('#attend_user').append(html);
                    }

                    $('.ui-dialog-buttonset .ui-button-text:first').html('日称取消');
                });
            })

        });

        function editSchedule() {
            var dialog = $("#dialog-add-modify").removeClass('hide').dialog({
                modal: true,
                title: "日程计划",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class": "btn btn-xs",
                        click: function () {
                            var schId = $('#schId').val();
                            if(schId.length > 0) {
                                $.post("deleteScheduleInfo", {'_token': token, 'schId':schId}, function (data) {
                                    if(data.status == 'error') {
                                        var newName = $("#schedule_name").val();
                                        $.gritter.add({
                                            title: '错误',
                                            text: '你没有关于' + '[' + newName + '] 的取消权限。',
                                            class_name: 'gritter-error'
                                        });
                                    } else {
                                        location.reload();
                                    }
                                });
                            }

                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "保存",
                        "class": "btn btn-primary btn-xs",
                        click: function () {
                            var newName = $("#schedule_name").val();
                            var newDesc = $("#schedule_descript").val();
                            var newStart = $("#schedule_start_date").val();
                            var newEnd = $("#schedule_end_date").val();
                            var startTime = $("#schedule_start_time").val();
                            var schId = $('#schId').val();

                            if (newName.length < 1) {
                                $("#message_out").html("请输入日程题目。");
                                return;
                            }
                            if (newDesc.length < 1) {
                                $("#message_out").html("请输入日程的说明内容。");
                                return;
                            }
                            if (newStart.length < 1) {
                                $("#message_out").html("请输入开始日期。");
                                return;
                            }
                            if (startTime.length < 1) {
                                $("#message_out").html("请输入开始时间。");
                                return;
                            }
                            if (newEnd.length < 1) {
                                $("#message_out").html("请输入完成日期。");
                                return;
                            }
                            var startdate = new Date(newStart);
                            var enddate = new Date(newEnd);
                            if (schId.length == 0) {
                                if(startdate > enddate) {
                                    $("#message_out").html("错误输入日程日期。");
                                    return;
                                }
                            }
                            if(startTime.length < 0) {
                                $("#message_out").html("请输入日程时间。");
                                return;
                            }

                            var selAttend = $('#attend_user').find('option');
                            var attendList = new Array();
                            var isExist = 0;
                            for(var i=0;i<selAttend.length; i++) {
                                var itemId = selAttend.eq(i).attr('value');
                                attendList.push(itemId);
                                if(itemId == userId) {
                                    isExist = 1;
                                    break;
                                }
                            }

                            if(!isExist) {
                                $("#message_out").html("把自己也要邀请。");
                                return;
                            }

                            $.post("updateSchedule", {'_token': token, 'schId':schId, 'title':newName,'descript': newDesc,
                                'start': newStart, 'end': newEnd, 'startTime':startTime, 'attend_user[]':attendList
                            }, function (data) {

                                if(data)
                                    window.location.reload();
                            });

                        }
                    }
                ]
            });
        }
    </script>

@stop