@extends('layout.header')
@section('content')
    <div class="main-content">
        <style>
            td {
                height : 38px;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-4">
                    <h4>
                        <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.UnitAttendRegister")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-5">
                    <h4 class="blue"><strong> {{$unitName}} {{transBusinessManage("title.UnitAttendRegister_Small")}}</strong></h4>
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
                    <div class="input-group col-md-3">
                        <input class="form-control date-picker" id="search-date" name="search-date" type="text"
                               data-date-format="yyyy-mm-dd" value="{{convert_date($date)}}">
                        <span class="input-group-addon">
                            <i class="icon-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div class=" table-responsive" id="table-container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:60px">{{transBusinessManage("captions.no")}}</th>
                                <th class="center">{{transBusinessManage("captions.duty")}}</th>
                                <th class="center">{{transBusinessManage("captions.name")}}</th>
                                <th class="center">{{transBusinessManage("captions.enterstate")}}</th>
                                <th class="center" style="width:200px">{{transBusinessManage("captions.registertime")}}</th>
                                <th class="center" style="width: 50%">{{transBusinessManage("captions.memo")}}</th>
                                <th class="center">{{transBusinessManage("captions.registerman")}}</th>
                                <th class="center"></th>
                            </tr>
                            </thead>
                            <tbody id="attend_list_table">
                            <?php $index = ($paginate->currentPage() - 1) * $paginate->perPage() + 1;?>
                            @foreach($attendUsers as $member)
                                <tr id="{{$member->id}}">
                                    <td class="center">{{$index++}}</td>
                                    <td class="center">{{$member->title}}</td>
                                    <td class="center">{{$member->realname}}</td>
                                    <td class="center" data-status="{{$member->statusId}}">
                                        @if(is_null($member->statusId) || ($member->statusId == 4))
                                            <select class="form-control">
                                                @foreach($attendType as $type)
                                                    <option value="{{$type->id}}"
                                                            @if($type->id==4) selected @endif>{!! $type->name !!}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            {{$member->statusName}}
                                        @endif
                                    </td>
                                    <td class="center">
                                        {{convert_datestr($member->regDate)}}
                                    </td>
                                    <td class="center">
                                        @if(is_null($member->statusId) || ($member->statusId == 4))
                                            <input type="text" placeholder="请填写留下便条。" style="width:100%;"
                                                   value="{{$member->memo}}">
                                        @else
                                            <span>{{$member->memo}}</span>
                                        @endif
                                    </td>
                                    <td class="center">{{ $member->creator }}</td>
                                    <td class="action-buttons center">
                                        @if(isset($member->statusId) && ($member->statusId != 4))
                                            <a href="javascript:void(0)" class="attend-edit"><i class="blue icon-edit bigger-130"></i></a>
                                            <a href="javascript:void(0)" class="edit-cancel" style="display: none"><i class="blue icon-remove bigger-130"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $paginate->render() !!}
                    </div>
                    <div class="hr hr-18 dotted hr-double"></div>
                    <div class="center">
                        <button type="button" id="submitBtn" class="btn btn-sm btn-primary no-radius"  onclick="unitAttendStateListSave()" style="width: 80px"> <i class="icon-save"></i>{{transBusinessManage("captions.register")}}</button>
                        <span id="restAlarm" class="red" style="display: none">&nbsp;&nbsp;&nbsp;今天是休息日。</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>

        var token = '<?php echo csrf_token() ?>';
        var typeHtml = '{!! $typeHtml !!}';

        $(function () {

            $("#search-date").bind("change", function () {
                var selDate = $("#search-date").val();
                if(selDate.length < 10)
                    return;
                location.href = 'unitAttendPage?selDate=' + selDate;
            });

            var editable = '{!! $allRegistered !!}' * 1;
            changeButtonState(editable);

            var isRest = '{!! $isRest !!}' * 1;
            showRestAlarmString(isRest);

            $('.attend-edit').on('click', function () {
                var obj = $(this).closest('tr').children();

                var statusId = obj.eq(3).data('status');
                var statusName = obj.eq(3).text();
                obj.eq(3).html(typeHtml);
                obj.eq(3).find('select').val(statusId);
                obj.eq(3).find('select').attr('data-text', statusName);

                var memo = obj.eq(5).find('span').html();
                obj.eq(5).html('<input type="text" style="width:100%" data-old="'+ memo + '" value="' + memo +'">');
                obj.eq(7).find('.attend-edit').hide();
                obj.eq(7).find('.edit-cancel').show();

                changeButtonState(0);
            });

            $('.edit-cancel').on('click', function () {
                var obj = $(this).closest('tr').children();

                var status = obj.eq(3).find('select').data('text');
                obj.eq(3).html(status);
                var memo = obj.eq(5).find('input').data('old');
                obj.eq(5).html('<span>' + memo + '</span>');
                obj.eq(7).find('.attend-edit').show();
                obj.eq(7).find('.edit-cancel').hide();

                var trs = $("#attend_list_table").children();
                var ctrlCount = 0;
                for (var i = 0; i < trs.length; i++) {
                    var control = trs[i].children[3].children[0];
                    if (control == null)
                        continue;
                    ctrlCount++;
                }

                if(ctrlCount == 0)
                    changeButtonState(1);
                else
                    changeButtonState(0);

            });


        });

        function unitAttendStateListSave(){
            var selDate = $("#search-date").val();
            if(selDate.length < 10) {
                bootbox.confirm("你选择的日期不正确。", function(result) {});
                return;
            }

            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在登记中。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();

            var tbody = $("#attend_list_table").children();
            var content = Array();
            for (var i = 0; i < tbody.length; i++) {
                var control = tbody[i].children[3].children[0];
                if (control == null) {
                    continue;
                }

                var attendType = control.value;
                var memo = tbody[i].children[5].children[0].value;
                if((attendType == 4) && (memo.length < 1))
                    continue;

                var rcontent = Array();
                rcontent.push(tbody[i].getAttribute('id'));
                rcontent.push(control.value);
                rcontent.push( tbody[i].children[5].children[0].value);

                content.push(rcontent.toString());
            }

            attendStr = content.toString();
            $.post('registerUnitMemberAttend', {_token: token, attend: attendStr, selDate:selDate}, function (data) {
                $("#modal-wizard").attr('aria-hidden', 'true');
                $("#modalback").attr('class', 'in');
                $("#modal-wizard").hide();

                var code = parseInt(data);
                if (isNaN(code)) {//AJAX요청이 성공한 경우의 처리
                    var selDate = $("#search-date").val();
                    location.href = 'unitAttendPage?selDate=' + selDate;
                } else {
                    bootbox.confirm("服务器错误。请稍后再试试。", function(result) {});
                }
            })
        }

        function changeButtonState(editable) {
            $('#submitBtn').removeClass('disabled');
            if(editable == 1) {
                $('#submitBtn').addClass('disabled');
            }
        }

        function showRestAlarmString(isRest) {
            if(isRest)
                document.getElementById('restAlarm').style.display = '-webkit-inline-box';
            else
                document.getElementById('restAlarm').style.display = 'none';
        }

    </script>
@endsection