@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <style>
            .table tbody > tr > td {
                vertical-align: middle;
                text-align: center;;
            }
            .form-control {
                display: inline;
                width: 55px;
                text-align: center;
            }
        </style>

        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.EnterAndExit")}}</b>
                </h4>
            </div>
            <div id="modalback" class="in"></div>
            <div class="space-10"></div>
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
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li id="AttendTimeTab" class="@if ( $page_id == '1' ) active @endif">
                                <a data-toggle="tab" href="#AttendTime">{{transBusinessManage("captions.set_enter_exit_time")}}</a>
                            </li>

                            <li id="RestDayTab" class="@if ( $page_id == '2' ) active @endif">
                                <a data-toggle="tab" href="#RestDay">{{transBusinessManage("captions.set_rest_time")}}</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div id="AttendTime" class="tab-pane @if ($page_id == '1') active @endif" style="padding:10px">
                                <div class="space-10"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            @if($total_count==0)
                                                <th>{{transBusinessManage("captions.msg_can_not_set_enter_time")}}
                                                    <span id="startH"></span>
                                                    <span id="startM"></span>
                                                    <span id="endH"></span>
                                                    <span id="endM"></span>
                                                </th>
                                            @else
                                                <th><span style="color: #f54e32">{{transBusinessManage("captions.now_set_time")}}</span>
                                                {{transBusinessManage("captions.enter")}} <span id="startH">{{$starttime['hour']}}</span>
                                                    <span>{{transBusinessManage("captions.hour")}}  </span>
                                                    <span id="startM">{{$starttime['minute']}}</span>{{transBusinessManage("captions.minute")}} --
                                                    {{transBusinessManage("captions.exit")}} <span id="endH"> {{$endtime['hour']}}</span>
                                                    <span>{{transBusinessManage("captions.hour")}}  </span>
                                                    <span id="endM">{{$endtime['minute']}}</span>{{transBusinessManage("captions.minute")}}
                                                </th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="{{'row_start_time'}}">
                                            <td>
                                                <span class="tree-folder-header">{{transBusinessManage("captions.entertime")}}:</span>
                                                <select class="form-control" id="start-hour" value="{{$starttime['hour']}}">
                                                    @for($i=0; $i<24; $i++)
                                                        <option value="{{$i}}" @if($i==$starttime['hour']) selected @endif>{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <span>  :  </span>
                                                <select class="form-control" id="start-min" value="{{$starttime['minute']}}">
                                                    @for($i=0; $i<60; $i++)
                                                        <option value="{{$i}}" @if($i==$starttime['minute']) selected @endif >{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <span id="start_time_message"></span>
                                            </td>
                                        </tr>
                                        <tr id="{{'row_end_time'}}">
                                            <td>
                                                <span class="tree-folder-header">{{transBusinessManage("captions.exittime")}}:</span>
                                                <select class="form-control" id="end-hour" value="{{$endtime['hour']}}">
                                                    @for($i=0; $i<24; $i++)
                                                        <option value="{{$i}}" @if($i==$endtime['hour']) selected @endif >{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <span>  :  </span>
                                                <select class="form-control" id="end-min" value="{{$endtime['minute']}}">
                                                    @for($i=0; $i<60; $i++)
                                                        <option value="{{$i}}" @if($i==$endtime['minute']) selected @endif >{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <span id="end_time_message"></span>
                                            </td>
                                        </tr>
                                        <tr id="{{'row_button'}}">
                                            <td>
                                                <button class="btn btn-info btn-sm radius-4" id="button_update_time" style="margin-top:6px; width: 80px;">
                                                    <i class="icon-save bigger-130"></i>
                                                    {{transBusinessManage("captions.register")}}
                                                </button>
                                                <p id="message_out"></p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="RestDay" class="tab-pane @if ($page_id == '2') active @endif">
                                <div class="space-10"></div>
                                <div class="widget-box">
                                    <div class="widget-header center">
                                        <div class="col-md-6">
                                            <h5 style="color: #0b66c1;font-weight: bold">{{$cur_month}}</h5>
                                        </div>
                                        <div class="col-md-6" style="padding-top: 7px;font-size: 14px">
                                            <select name="restYear" id="restYear" onchange="getRestList();">
                                                @for($i=2017; $i<2025; $i ++)
                                                    <option value="{{$i}}" @if($selY == $i) selected="selected" @endif>{{$i}}</option>
                                                @endfor
                                            </select>
                                            {{transBusinessManage("captions.year")}}
                                            <select name="restMonth" id="restMonth" onchange="getRestList();">
                                                @for($i=1; $i<=12; $i++)
                                                    <option value="{{$i}}" @if($selM == $i) selected="selected" @endif>{{$i}}</option>
                                                @endfor
                                            </select>
                                            {{transBusinessManage("captions.month")}}
                                        </div>
                                    </div>
                                    <div class="widget-body" style="padding:10px">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 200px; text-align: center">{{transBusinessManage("captions.date")}}</th>
                                                    <th style="width: 200px; text-align: center">{{transBusinessManage("captions.kind")}}</th>
                                                    <th style="text-align: center;">{{transBusinessManage("captions.explain")}}</th>
                                                    <th style="width: 70px; text-align: center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($total_rest as $rest)
                                                <tr id="{{'row_rest_'.$rest->id}}">
                                                    <td>
                                                        <div style="width: 100%">
                                                            <div class="input-group">
                                                                <input id="{{'rest_day'.$rest->id}}" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd"
                                                                       value="{{convert_date($rest->day)}}" oldvalue="{{convert_date($rest->day)}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select id="{{'rest_state'.$rest->id}}" class="form-control" placeholder="请选择休息内容。" style="width: 100%"
                                                                value="{{$rest->state}}" oldvalue="{{$rest->state}}">
                                                            @for($i=1;$i<4;$i++)
                                                                <option value="{{$i}}" @if($i==$rest->state) selected @endif>{{$rest_state[$i]}}</option>
                                                            @endfor
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input id="{{'rest_desc'.$rest->id}}" type="text" placeholder="请填写说明内容。" style="width:100%;"
                                                               value="{{$rest->descript}}" oldvalue="{{$rest->descript}}">
                                                    </td>
                                                    <td class="action-buttons" style="text-align: left">
                                                        <a href="javascript:onUpdate({{$rest->id}})" class="blue">
                                                            <i class="icon-save bigger-130"></i>
                                                        </a>
                                                        <a href="javascript:onDelete({{$rest->id}})" class="red" >
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>
                                                    <div style="width: 100%">
                                                        <div class="input-group">
                                                            <input id="rest_day" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" value="">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select id="rest_state" class="form-control"  id="postname" style="width: 100%" value="">
                                                        @for($i=1;$i<4;$i++)
                                                            <option value="{{$i}}">{{$rest_state[$i]}}</option>
                                                        @endfor
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="rest_desc" placeholder="请填写说明内容。" style="width:100%;">
                                                </td>
                                                <td class="action-buttons" style="text-align: left">
                                                    <a href="javascript:onAddRest()" class="red">
                                                        <i class="icon-plus bigger-130"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <p id="rest_message_out"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
        {{--var $assets = "assets";//this will be used in fuelux.tree-sampledata.js--}}
        var token = '<?php echo csrf_token() ?>';

        jQuery(function($){

            $("#button_update_time").on(ace.click_event, function() {
                var startH = parseInt(document.getElementById("start-hour").value);
                var startM = parseInt(document.getElementById("start-min").value);
                var endH = parseInt(document.getElementById("end-hour").value);
                var endM = parseInt(document.getElementById("end-min").value);
                var startH_old = parseInt(document.getElementById("startH").innerHTML);
                var startM_old = parseInt(document.getElementById("startM").innerHTML);
                var endH_old = parseInt(document.getElementById("endH").innerHTML);
                var endM_old = parseInt(document.getElementById("endM").innerHTML);
                var err_flag=0;
                if ( startH==0 && startM==0 ){
                    err_flag=1;
                    document.getElementById("message_out").innerHTML="<br/>请设置上班时间。";
                }
                if ( endH==0 && endM==0 ){
                    err_flag=1;
                    document.getElementById("message_out").innerHTML="<br/>请设置下班时间。";
                }
                if ( startH==startH_old && startM==startM_old && endH==endH_old && endM==endM_old){
                    err_flag=1;
                    document.getElementById("message_out").innerHTML="<br/>上下班时间没有变更了。";
                }
                if ( startH>endH){
                    err_flag=1;
                    document.getElementById("message_out").innerHTML="<br/>上班时间比下班时间更早了。";
                }
                else if(startH==endH) {
                    if( startM>=endM) {
                        err_flag = 1;
                        document.getElementById("message_out").innerHTML = "<br/>错定上下班时间了。";
                    }
                }
                if ( err_flag==1 ){  return; }
                else{
                    document.getElementById("message_out").innerHTML="";
                }
                bootbox.confirm("上下班时间要更新到规定的时间吗？", function(result) {
                    if(result) {
                        $("#modal-wizard").attr('aria-hidden', 'false');
                        $("#modal-wizard").addClass('in');
                        $("body").addClass('modal-open');
                        $("#modalback").addClass('modal-backdrop ');
                        var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在更新上下班时间。';
                        $("#modal-body-content").html(htm);
                        $("#modal-wizard").show();
                        $.post('timeUpdate', {'_token':token, 'startH': startH, 'startM': startM, 'endH': endH, 'endM': endM}, function(data){
                            if( parseInt(data)==1){
                                document.getElementById("startH").innerHTML=startH;
                                document.getElementById("startM").innerHTML=startM;
                                document.getElementById("endH").innerHTML=endH;
                                document.getElementById("endM").innerHTML=endM;
                                $("#modal-wizard").attr('aria-hidden', 'true');
                                $("#modalback").attr('class', 'in');
                                $("#modal-wizard").hide();

                                $.gritter.add({
                                    title: '成功',
                                    text: '设置上下班时间成功！',
                                    class_name: 'gritter-success'
                                });

                            }
                        })
                    }
                });

            });

            $('ul li a[data-toggle=tab]').click(function(){
                $nowTab = $(this).attr("href");
                window.localStorage.setItem("entryTab",$nowTab);
            });

            $initTab = window.localStorage.getItem("entryTab");
            if ($initTab != null) {
                $('ul li a[data-toggle=tab]').each(function(){
                    $href = $(this).attr("href");
                    $(this).parent("li").prop("class","");
                    $($href).prop("class", "tab-pane");
                    if($initTab == $href) {
                        $($initTab).prop("class", "tab-pane active");
                        $(this).parent("li").prop("class","active");
                    }
                });
            }
        });
        function onUpdate(id) {
            var rest_day = document.getElementById('rest_day' + id).value;
            var rest_state = document.getElementById('rest_state' + id).value;
            var rest_desc = document.getElementById('rest_desc' + id).value;
            var rest_day_old = document.getElementById('rest_day' + id).getAttribute("oldvalue");
            var rest_state_old = document.getElementById('rest_state' + id).getAttribute("oldvalue");
            var rest_desc_old = document.getElementById('rest_desc' + id).getAttribute("oldvalue");
            var err_flag = 0;
            if (rest_day == rest_day_old && rest_state == rest_state_old && rest_desc == rest_desc_old) {
                err_flag=1;
                document.getElementById("rest_message_out").innerHTML="休息设置未更改。";
            }
            if ( err_flag==1 ){  return; }
            else {
                document.getElementById("rest_message_out").innerHTML="";
            }
            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在变更休息日设定项目。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();
            $.post('restUpdate', {'_token': token, 'id':id, 'rest_day': rest_day, 'rest_state': rest_state, 'rest_desc': rest_desc}, function (data) {
                if ( parseInt(data) == 1) {
                    document.getElementById('rest_day' + id).setAttribute("oldvalue", rest_day);
                    document.getElementById('rest_state' + id).setAttribute("oldvalue", rest_state);
                    document.getElementById('rest_desc' + id).setAttribute("oldvalue", rest_desc);
                    $("#modal-wizard").attr('aria-hidden', 'true');
                    $("#modalback").attr('class', 'in');
                    $("#modal-wizard").hide();
                } else {
                    err_flag=1;
                    document.getElementById("rest_message_out").innerHTML= " " + rest_day + "的休息已经设置了。";

                    $("#modal-wizard").attr('aria-hidden', 'true');
                    $("#modalback").attr('class', 'in');
                    $("#modal-wizard").hide();
                }
            });
        }
        function onDelete(id) {
            var uri = decodeURI('{{$_SERVER['REQUEST_URI']}}');
            bootbox.confirm("真要删掉休息日设置项目吗？", function(result) {
                if(result) {
                    $("#modal-wizard").attr('aria-hidden', 'false');
                    $("#modal-wizard").addClass('in');
                    $("body").addClass('modal-open');
                    $("#modalback").addClass('modal-backdrop ');
                    var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在删掉休息日设置项目。';
                    $("#modal-body-content").html(htm);
                    $("#modal-wizard").show();
                    $.post('restDelete', {'_token': token, 'id':id}, function (data) {
                        if ( parseInt(data) == 1) {
                            if ( uri.search('page_id=2') == -1 ) {
                                uri = uri + '&page_id=2';
                            }
                            uri = uri.replace('&amp;', '&');
                            location.href = uri;
                        }
                    });
                }
            });
        }

        function onAddRest() {
            var uri = decodeURI('{{$_SERVER['REQUEST_URI']}}');
            var rest_day = document.getElementById('rest_day').value;
            var rest_state = document.getElementById('rest_state').value;
            var rest_desc = document.getElementById('rest_desc').value;
            var err_flag=0;
            if (rest_day == '') {
                err_flag=1;
                document.getElementById("rest_message_out").innerHTML="请设置休息日。";
            }
            if (err_flag==1)
                return;
            document.getElementById("rest_message_out").innerHTML = "";

            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在追加休息日设置项目。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();
            $.post('restAdd', {'_token' : token , 'rest_day' : rest_day , 'rest_state' : rest_state , 'rest_desc' : rest_desc } , function (data) {
                if ( parseInt(data) == 1) {
                    if ( uri.search('page_id=2') == -1 ) {
                        uri = uri + '&page_id=2';
                    }
                    uri = uri.replace('&amp;', '&');
                    location.href = uri;
                } else {
                    err_flag=1;
                    document.getElementById("rest_message_out").innerHTML= " " + rest_day + "的休息日设置已经被进行了。";

                    $("#modal-wizard").attr('aria-hidden', 'true');
                    $("#modalback").attr('class', 'in');
                    $("#modal-wizard").hide();
                }
            });
        }

        function getRestList(){
            $restYear = $("#restYear").val();
            $restMonth = $("#restMonth").val();
            $url = "entryandexit?restYear="+$restYear+"&restMonth="+$restMonth;
            location.href = $url;
        }
    </script>
@endsection