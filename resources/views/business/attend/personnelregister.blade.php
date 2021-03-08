@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        {{transBusinessManage("title.PersonalnelRegister")}}
                    </small>
                </h4>
            </div>
            <div class="space-10"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{transBusinessManage("title.PersonalnelRegister_Small")}}</div>
                            <div class="panel-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>{{transBusinessManage("captions.warning")}}!</strong>请输入便条<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form-horizontal" role="form" method="POST"
                                      action="{{ url('business/personnelregister') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="form-group">
                                        <label class="col-md-4 control-label" style="padding-top:10px">时间</label>

                                        <div class="col-md-6">
                                            <div id="time_spanid" class="panel-heading">
                                                {{$firsttime->year}}{{transBusinessManage("captions.year")}}
                                                {{$firsttime->month}}{{transBusinessManage("captions.month")}}
                                                {{$firsttime->day}}{{transBusinessManage("captions.day")}}
                                                {{$firsttime->hour}}{{transBusinessManage("captions.hour")}}
                                                {{$firsttime->minute}}{{transBusinessManage("captions.minute")}}
                                                {{$firsttime->second}}{{transBusinessManage("captions.second")}}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-md-4 control-label">{{transBusinessManage("captions.memo")}}</label>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="memo" id="memo"
                                                   value="{{$memo}}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6" style="text-align: right">
                                            <button type="button" id="attend_submit"
                                                    class="btn btn-sm btn-info no-radius"
                                                    onclick="savepersonelregisterinfo()"
                                                    style="width: 80px">
                                               <i class="icon-save"></i>{{transBusinessManage("captions.register")}}
                                            </button>
                                            <i class="icon-spinner icon-spin orange bigger-125"
                                               style="display: none"></i>
                                        </div>
                                        <div class="col-md-4 red" style="margin-top: 15px" id="savedmsg">
                                            @if ($status == 0)
                                                <span>今天是休息日。</span>
                                            @elseif ($status == 1)
                                                <span>上班时间已经过了。</span>
                                            @elseif ($status == 3)
                                                <span>登录不了了。</span>
                                            @elseif($status == 4)
                                                <span>已经上班登录了。</span>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = '<?php echo csrf_token() ?>';
        var year = month = day = hour = min = sec = 0;
        var servertime = new Date({{$firsttime->year}}, {{$firsttime->month}} - 1, {{$firsttime->day}}, {{$firsttime->hour}}, {{$firsttime->minute}}, {{$firsttime->second}}, 0);
        var clienttime = new Date();
        var serverStamp = servertime.getTime();
        var clientStamp = clienttime.getTime();
        var diff = serverStamp - clientStamp;
        year = servertime.getFullYear();
        month = servertime.getMonth() + 1;
        day = servertime.getDate();
        hour = servertime.getHours();
        min = servertime.getMinutes();
        sec = servertime.getSeconds();

        $(function () {
            DisplayTime();


            setInterval(function () {
                everyTime();
            }, 1000);

        });

        function everyTime() {
            sec++;
            if (sec == 60) {
                sec = 0;
                min++;
                if (min == 60) {
                    min = 0;
                    hour++;
                    if (hour == 24) {
                        servertime = new Date();
                        servertime += diff;
                        year = servertime.getYear();
                        month = servertime.getMonth();
                        day = servertime.getDay();
                        hour = servertime.getHours();
                        min = servertime.getMinutes();
                        sec = servertime.getSeconds();
                    }
                }
            }
            DisplayTime();
        }

        function DisplayTime() {
            $("#time_spanid").empty();
            var pp = phour = mm = ss = "";
            if (min < 10) {
                mm = "0" + min;
            } else {
                mm = min;
            }

            if (sec < 10) {
                ss = "0" + sec;
            } else {
                ss = sec;
            }

            if (hour > 12) {
                phour = hour - 12;
                if (phour < 10) phour = "0" + phour;
                pp = "下午";
            } else if (hour < 10) {
                phour = "0" + hour;
                pp = "上午";
            } else {
                phour = hour;
                pp = "上午";
            }
            var time_result = '<div>' + year + '年 ' + month + '月 ' + day + '日 ' + phour + '点 ' + mm + '分 ' + ss + '秒' + '&nbsp' + pp + '</div>';
            $("#time_spanid").html(time_result);
        }

        function savepersonelregisterinfo() {
            var memo = $("#memo").val();
            $.post('setPersonAttend', {'_token':token, 'memo': memo}, function (data) {
                var code = parseInt(data);
                if (code == 1) {
                    //보관이 성공한 경우의 처리
                    $('#attend_submit').addClass('disabled');
                    $('#savedmsg').html("<span>上班登录成功了。</span>");
                }
                else if (code == -1) {
                    //공작일이 아닌경우의 처리
                    $('#attend_submit').addClass('disabled')
                    $('#savedmsg').html("<span>今天是休息日。</span>");
                }
                else if (code == -2) {
                    //공작시간이 아닌 경우의 처리
                    $('#attend_submit').addClass('disabled')
                    $('#savedmsg').html("<span>不是工作时间。</span>");
                }
                else if (code == 0) {
                    //이미 보관된 경우의 처리
                    $('#attend_submit').addClass('disabled')
                    $('#savedmsg').html("<span>已经上班登录了。</span>");
                }
            });
        }
    </script>
@endsection