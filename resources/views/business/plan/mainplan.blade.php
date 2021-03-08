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
                text-align: left;
            }

        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transBusinessManage("title.MainPlan")}}</b>
                    </h4>
                </div>
            </div>
            <div id="modalback" class="in"></div>
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
                    <div class="col-sm-6">
                        <label class="col-md-2" style="padding-top:7px">{{transBusinessManage("title.MainPlan")}} :</label>
                        <input type="text" class="form-control" id="search_plan" style="float:left;width:60%" value="@if(isset($name)){{$name}}@endif">
                        <button class="btn btn-sm btn-primary no-radius col-md-1" type="button" style="margin-left: 20px; float: left; width: 80px;" onclick="filterByPlanKeyword()">
                            <i class="icon-search"></i>{{transBusinessManage("captions.search")}}
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <div style="text-align: right">
                            <a href="#" id="btn-add-tema" class="btn btn-primary btn-sm" style="width: 80px;"><i class="icon-plus-sign-alt"></i>{{transBusinessManage("captions.add")}}</a>
                            <div class="space-6"></div>
                            <div id="dialog-add-modify-tema" class="hide">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedulename")}}:</label>
                                        <div class="col-md-8"><input type="text" id="tema-name" class="form-control" style="width: 100%" value=""></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.explain")}}:</label>
                                        <div class="col-md-8"><textarea id="tema-descript" class="form-control" style="width: 100%"></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedule_startdate")}}:</label>
                                        <div class="col-md-8">
                                            <div class="input-group" style="width: 80%">
                                                <input id="tema-start-date" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" value="">
                                                <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">{{transBusinessManage("captions.schedule_completedate")}}:</label>
                                        <div class="col-md-8">
                                            <div class="input-group" style="width: 80%">
                                                <input id="tema-end-date" class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" value="">
                                                <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p id="plan_message_out" style="text-align: center; color: red; "></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-striped table-bordered table-hover">
                        @if ( $main_plans->count() == 0 )
                            <thead>
                            <tr>
                                <th><p style="text-align: center; color: red;"><b> 没有存在设置的计划项目。</b></p></th>
                            </tr>
                            </thead>
                        @else
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center">{{transBusinessManage("captions.schedulename")}}</th>
                                <th class="center">{{transBusinessManage("captions.explain")}}</th>
                                <th class="center" style="width:160pt">{{transBusinessManage("captions.performperiod")}}</th>
                                <th class="center" width="50px"></th>
                            </tr>
                            </thead>

                            <tbody id="MainPlan_table_body">
                            @foreach($main_plans as $plan)
                                <tr id="{{'plan_'.$plan->id}}">
                                    <td style="text-align: left">
                                        <a href="javascript:onUpdate({{$plan->id}})">
                                            <span id="{{'name_'.$plan->id}}"
                                                  value="{{$plan->name}}">{{$plan->name}}</span>
                                        </a>
                                    </td>
                                    <td style="text-align: left">
                                        <div style="width: 100%">
                                            <span id="{{'desc_'.$plan->id}}"
                                                  value="{{$plan->descript}}">{{$plan->descript}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width: 100%">
                                            <span id="{{'start_'.$plan->id}}"
                                                  value="{{convert_date($plan->startDate)}}">{{convert_date($plan->startDate)}}</span>
                                            &nbsp;~&nbsp;
                                            <span id="{{'end_'.$plan->id}}"
                                                  value="{{convert_date($plan->endDate)}}">{{convert_date($plan->endDate)}}</span>
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <a class="red" href="javascript:onDelete({{$plan->id}})">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                    {!! $main_plans->render() !!}
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var token = '<?php echo csrf_token() ?>';

        jQuery(function ($) {

            $("#btn-add-tema").on('click', function (e) {

                e.preventDefault();

                $("#tema-name").val("");
                document.getElementById("tema-descript").innerHTML = "";
                $("#tema-start-date").val("");
                $("#tema-end-date").val("");

                var dialog = $("#dialog-add-modify-tema").removeClass('hide').dialog({
                    modal: true,
                    title: "追加计划",
                    title_html: true,
                    buttons: [
                        {
                            text: "取消",
                            "class": "btn btn-xs",
                            click: function () {
                                $(this).dialog("close");
                            }
                        },
                        {
                            text: "保存",
                            "class": "btn btn-primary btn-xs",
                            click: function () {
                                var newName = $("#tema-name").val();
                                var newDesc = document.getElementById("tema-descript").value;
                                var newStart = $("#tema-start-date").val();
                                var newEnd = $("#tema-end-date").val();
                                var err_flag = 0;

                                if (newName.length < 1) {
                                    err_flag = 1;
                                    document.getElementById("plan_message_out").innerHTML = "请输入计划名称。";
                                }
                                if (newDesc.length < 1) {
                                    err_flag = 1;
                                    document.getElementById("plan_message_out").innerHTML = "请输入关于计划的说明。";
                                }
                                if (newStart.length < 1) {
                                    err_flag = 1;
                                    document.getElementById("plan_message_out").innerHTML = "请输入计划的开始日子。";
                                }
                                if (newEnd.length < 1) {
                                    err_flag = 1;
                                    document.getElementById("plan_message_out").innerHTML = "请输入计划的完成日子。";
                                }
                                var startdate = new Date(newStart);
                                var enddate = new Date(newEnd);
                                if (startdate > enddate) {
                                    err_flag = 1;
                                    document.getElementById("plan_message_out").innerHTML = "错误输入计划完成日子。";
                                }
                                if (err_flag == 1) {
                                    return;
                                }

                                $.post("planAdd", {
                                    '_token': token,
                                    'name': newName, 'desc': newDesc,
                                    'start': newStart, 'end': newEnd
                                }, function (data) {
                                    var returnCode = parseInt(data);
                                    if (returnCode > 0) {
                                        window.location.reload();
                                    } else if (returnCode == -1) {
                                        document.getElementById("plan_message_out").innerHTML = "已经存在设置的计划项目。";
                                    }
                                });

                            }
                        }
                    ]
                });
                document.getElementById("plan_message_out").innerHTML = "";
            });
        });

        function filterByPlanKeyword() {
            var keyword = $("#search_plan").val();
            if(keyword.length > 0)
                location.href = 'mainplan?name=' + keyword;
            else
                location.href = 'mainplan';
        }


        function onUpdate(id) {

            var plan_name = document.getElementById("name_" + id).getAttribute('value');
            var plan_desc = document.getElementById("desc_" + id).getAttribute('value');
            var plan_start = document.getElementById("start_" + id).getAttribute('value');
            var plan_end = document.getElementById("end_" + id).getAttribute('value');

            $("#tema-name").val(plan_name);
            document.getElementById("tema-descript").innerHTML = plan_desc;
            $("#tema-start-date").val(plan_start);
            $("#tema-end-date").val(plan_end);

            var dialog = $("#dialog-add-modify-tema").removeClass('hide').dialog({
                modal: true,
                title: "计划 变更",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "保存",
                        "class": "btn btn-primary btn-xs",
                        click: function () {
                            var newName = $("#tema-name").val();
                            var newDesc = document.getElementById("tema-descript").value;
                            var newStart = $("#tema-start-date").val();
                            var newEnd = $("#tema-end-date").val();
                            var err_flag = 0;

                            if (newName.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入计划名称。";
                            }
                            if (newDesc.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入关于计划的说明。";
                            }
                            if (newStart.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入计划的开始日子。";
                            }
                            if (newEnd.length < 1) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "请输入计划的完成日子。";
                            }
                            if (plan_name == newName && plan_desc == newDesc &&
                                    plan_start == newStart && plan_end == newEnd) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "变更计划资料失败！";
                            }
                            var startdate = new Date(newStart);
                            var enddate = new Date(newEnd);
                            if (startdate > enddate) {
                                err_flag = 1;
                                document.getElementById("plan_message_out").innerHTML = "错误输入计划的完成期间。";
                            }
                            if (err_flag == 1) {
                                return;
                            }

                            $.post("planUpdate", {
                                '_token': token, 'id': id,
                                'name': newName, 'desc': newDesc,
                                'start': newStart, 'end': newEnd
                            }, function (data) {
                                var returnCode = parseInt(data);
                                if (returnCode > 0) {
                                    window.location.reload();
                                } else if (returnCode == -1) {
                                    document.getElementById("plan_message_out").innerHTML = "已经存在设置的计划项目。
";
                                }
                            });

                        }
                    }
                ]
            });
            document.getElementById("plan_message_out").innerHTML = "";
        }
        function onDelete(id) {
            bootbox.confirm("真要删掉计划项目吗？", function (result) {
                if (result) {
                    $("#modal-wizard").attr('aria-hidden', 'false');
                    $("#modal-wizard").addClass('in');
                    $("body").addClass('modal-open');
                    $("#modalback").addClass('modal-backdrop ');
                    var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在删掉计划设置项目。';
                    $("#modal-body-content").html(htm);
                    $("#modal-wizard").show();
                    $.post('planDelete', {'_token': token, 'id': id}, function (data) {
                        if (parseInt(data) == 1) {
                            location.reload();
                        }
                    });
                }
            });
        }

    </script>
@endsection