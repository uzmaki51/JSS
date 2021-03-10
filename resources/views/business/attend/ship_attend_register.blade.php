@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <style>
            td {height : 38px;}
        </style>

        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        {{transBusinessManage("title.ShipAttendRegister")}}
                    </small>
                </h4>
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
                    <div class="col-md-2">
                        <label style="float: left; padding-top: 5px">{{transBusinessManage("captions.date")}} :</label>
                        <div class="input-group" style="padding-left:10px">
                            <input class="form-control date-picker" id="search-date" type="text" style="float: left;"
                                   data-date-format="yyyy/mm/dd" value="@if(isset($date)){{convert_date($date)}}@endif">
                            <span class="input-group-addon">
                            <i class="icon-calendar bigger-110"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2 form-horizontal">
                        <label class="col-sm-3 control-label no-padding-right" for="chosen-select">{{transBusinessManage("captions.shipname")}}</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="ship_select">
                                <option value="">{{transBusinessManage("captions.all")}}</option>
                                @foreach($shipList as $ship)
                                    <option value="{{$ship['RegNo']}}" @if($shipId==$ship['RegNo']) selected @endif>{{$ship['shipName_Cn']}} @if(!empty($ship['name']))| {{$ship['name']}} @endif</option>
                                @endforeach
                                <option value="empty" @if($shipId == 'empty') selected @endif>{{transBusinessManage("captions.waitmember")}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 form-horizontal">
                        <label class="col-sm-3 control-label no-padding-right" for="chosen-select">{{transBusinessManage("captions.duty")}}</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="pos_select">
                                <option value="">{{transBusinessManage("captions.all")}}</option>
                                @foreach($posList as $pos)
                                    <option value="{{$pos['id']}}" @if($posId == $pos['id']) selected @endif>{{ $pos['Duty'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 form-horizontal">
                        <label class="col-sm-5 control-label no-padding-right" for="chosen-select">{{transBusinessManage("captions.name")}}</label>
                        <div class="col-sm-7"><input class="form-control" id="ship_member" value="{{$memberName}}"></div>
                    </div>
                    <button class="btn btn-primary btn-sm search-btn" style="float: left; width: 80px;"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                </div>
                <div class="space-6"></div>
                <div class="row">
                    <div style="@if(count($attendUsers) > 10) overflow-y: scroll;@endif width: 100%">
                        <table class="table table-striped table-bordered table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th @if(is_null($shipId) || ($allRegistered == 1) || ($isRest == 1)) class="hide" @endif style="width:4%"><label><input type="checkbox" class="ace" id="all-check"><span class="lbl"></span></label></th>
                                <th class="center" style="width:4%">No</th>
                                <th class="center" style="width:8%">{{transBusinessManage("captions.shipname")}}</th>
                                <th class="center" style="width:8%">{{transBusinessManage("captions.duty")}}</th>
                                <th class="center" style="width:10%">{{transBusinessManage("captions.name")}}</th>
                                <th class="center" style="width:15%">
                                {{transBusinessManage("captions.enterstate")}}
                                    <select class="form-control" id="all-select" style="display: none " disabled>
                                        @foreach($attendType as $type)
                                            <option value="{{$type->id}}">{!! $type->name !!}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="center" width="40%">
                                {{transBusinessManage("captions.remark")}}
                                    <input type="text" placeholder="请输入便条。" id="all-memo" style="width:100%;display: none">
                                </th>
                                <th style="width:5%">{{transBusinessManage("captions.registerman")}}</th>
                                <th style="width:5%"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="overflow-x:hidden; overflow-y: auto; width:100%; height:58vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="attend_table">
                                <?php $index = 1; ?>
                                @foreach($attendUsers as $member)
                                    <?php if($shipId == 'empty' && (!empty($member->shipName_Cn) || $member->RegStatus == 0)) continue; ?>
                                    <tr data-id="{{$member->id}}">
                                        <td @if(is_null($shipId) || ($allRegistered == 1) || ($isRest == 1)) class="hide" @endif style="width:4%">
                                            @if(($allRegistered != 1) && ($isRest != 1) && (is_null($member->statusId) || ($member->statusId == 4)))
                                                <label><input type="checkbox" class="ace sub-check"><span class="lbl"></span></label>
                                            @endif
                                        </td>
                                        <td class="center" style="width:4%">{{$index++}}</td>
                                        <td class="center" style="width:8%">{{$member->shipName_Cn}}</td>
                                        <td class="center" style="width:8%">{{$member->Duty}}</td>
                                        <td class="center" style="width:10%">{{$member->realname}}</td>
                                        <td class="center" data-status="{{$member->statusId}}" style="width:15%">
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
                                        <td style="width:40%">
                                            @if(is_null($member->statusId) || ($member->statusId == 4))
                                                <input type="text" placeholder="请填写留下便条。" style="width:100%;" value="{{$member->memo}}">
                                            @else
                                                <span>{{$member->memo}}</span>
                                            @endif
                                        </td>
                                        <td style="width:5%;text-align: center">{{ $member->creator }}</td>
                                        <td class="action-buttons center" style="width:5%">
                                            @if(isset($member->statusId) && ($member->statusId != 4))
                                                <a href="javascript:void(0)" class="attend-edit"><i class="blue icon-edit bigger-130"></i></a>
                                                <a href="javascript:void(0)" class="edit-cancel" style="display: none"><i class="blue icon-remove bigger-130"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="hr hr-18 dotted hr-double"></div>
                    <div class="center">
                        <a href="javascript:void(0)" id="submitBtn" class="btn btn-sm btn-primary no-radius" style="width: 80px"><i class="icon-save"></i>{{transBusinessManage("captions.register")}}</a>
                        <span id="restAlarm" class="red" style="display: none">&nbsp;&nbsp;&nbsp;今天是休息日。</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = '<?php echo csrf_token() ?>';
        var shipMemberName = '{!! $memberName !!}';
        var typeHtml = '{!! $typeHtml !!}';

        $(function () {

            $('.search-btn').on('click', function () {
                var selDate = $("#search-date").val();
                var url = 'shipmemberregister?date=' + selDate;

                var selShip = $("#ship_select").val();
                if(selShip.length > 0)
                    url += '&shipId=' + selShip;

                var selPos = $("#pos_select").val();
                if(selPos.length > 0)
                    url += '&pos=' + selPos;

                var memberName = $('#ship_member').val();
                if(memberName.length > 0)
                    url += '&name=' + memberName;

                location.href = url;
            });

            $("#search-date").bind("change", function () {
                var selDate = $("#search-date").val();
                if(selDate.length < 1) {
                    bootbox.confirm("请选择日期！", function(result) {});
                    return;
                }
                var url = 'shipmemberregister?date=' + selDate;

                var selShip = $("#ship_select").val();
                if(selShip.length > 0)
                    url += '&shipId=' + selShip;
                var memberName = $('#ship_member').val();
                if(memberName.length > 0)
                    url += '&name=' + memberName;

                location.href = url;
            });

            $("#ship_select").bind('change', function () {
                var selDate = $("#search-date").val();
                var url = 'shipmemberregister?date=' + selDate;

                var selShip = $("#ship_select").val();
                if(selShip.length > 0)
                    url += '&shipId=' + selShip;
                var memberName = $('#ship_member').val();
                if(memberName.length > 0)
                    url += '&name=' + memberName;

                location.href = url;
            });

            $('#all-check').on('change', function () {
                var checked = this.checked;
                var trs = $("#attend_table").children();
                for (var i = 0; i < trs.length; i++) {
                    var checkCtl = trs[i].children[0].children[0];
                    if(checkCtl == null)
                        continue;

                    trs[i].children[0].children[0].children[0].checked = checked;
                    if(checked) {
                        trs[i].children[5].children[0].setAttribute('disabled', 'disabled');
                        trs[i].children[6].children[0].setAttribute('disabled', 'disabled');
                    } else {
                        trs[i].children[5].children[0].removeAttribute('disabled');
                        trs[i].children[6].children[0].removeAttribute('disabled');
                    }
                }

                if(checked) {
                    $('#all-select').removeAttr('disabled');
                    $('#all-memo').removeAttr('disabled');
                    $('#all-select').css('display', 'block');
                    $('#all-memo').css('display', 'block');
                } else {
                    $('#all-select').attr('disabled', 'disabled');
                    $('#all-memo').attr('disabled', 'disabled');
                    $('#all-select').css('display', 'none');
                    $('#all-memo').css('display', 'none');
                }
            });

            $('.sub-check').on('change', function () {
                var trs = $("#attend_table").children();
                var checkCount = 0;
                for (var i = 0; i < trs.length; i++) {
                    var checkCtl = trs[i].children[0].children[0];
                    if(checkCtl == null)
                        continue;
                    if(trs[i].children[0].children[0].children[0].checked) {
                        trs[i].children[5].children[0].setAttribute('disabled', 'disabled');
                        trs[i].children[6].children[0].setAttribute('disabled', 'disabled');
                        checkCount++;
                    } else {
                        trs[i].children[5].children[0].removeAttribute('disabled');
                        trs[i].children[6].children[0].removeAttribute('disabled');
                    }
                }
                if(checkCount) {
                    $('#all-select').removeAttr('disabled');
                    $('#all-memo').removeAttr('disabled');
                    $('#all-select').css('display', 'block');
                    $('#all-memo').css('display', 'block');
                    var allcheck = document.getElementById('all-check');
                    if(checkCount == trs.length)
                        allcheck.checked = true;
                    else
                        allcheck.checked = false;
                } else {

                    $('#all-select').attr('disabled', 'disabled');
                    $('#all-memo').attr('disabled', 'disabled');
                    $('#all-select').css('display', 'none');
                    $('#all-memo').css('display', 'none');

                    var allcheck = document.getElementById('all-check');
                    allcheck.checked = false;
                }
            });

            $('.attend-edit').on('click', function () {
                var obj = $(this).closest('tr').children();

                var statusId = obj.eq(5).data('status');
                var statusName = obj.eq(5).text();
                obj.eq(0).html(' <label><input type="checkbox" class="ace sub-check"><span class="lbl"></span></label>');
                obj.eq(5).html(typeHtml);
                obj.eq(5).find('select').val(statusId);
                obj.eq(5).find('select').attr('data-text', statusName);

                var memo = obj.eq(6).find('span').html();
                obj.eq(6).html('<input type="text" style="width:100%" data-old="'+ memo + '" value="' + memo +'">');
                obj.eq(8).find('.attend-edit').hide();
                obj.eq(8).find('.edit-cancel').show();

                changeButtonState(0);
            });

            $('.edit-cancel').on('click', function () {
                var obj = $(this).closest('tr').children();

                var status = obj.eq(5).find('select').data('text');
                obj.eq(0).html('');
                obj.eq(5).html(status);
                var memo = obj.eq(6).find('input').data('old');
                obj.eq(6).html('<span>' + memo + '</span>');
                obj.eq(8).find('.attend-edit').show();
                obj.eq(8).find('.edit-cancel').hide();

                var trs = $("#attend_table").children();
                var ctrlCount = 0;
                for (var i = 0; i < trs.length; i++) {
                    var control = trs[i].children[5].children[0];
                    if (control == null)
                        continue;
                    ctrlCount++;
                }

                if(ctrlCount == 0)
                    changeButtonState(1);
                else
                    changeButtonState(0);

            });

            $('#submitBtn').on('click', function () {
                saveShipMemberAttendStatus();
            });

            var editable = '{!! $allRegistered !!}' * 1;
            changeButtonState(editable);

            var isRest = '{!! $isRest !!}' * 1;
            showRestAlarmString(isRest);
        });

        function saveShipMemberAttendStatus() {
            var selDate = $("#search-date").val();
            if(selDate.length < 10) {
                bootbox.confirm("你选择的日期不正确。", function(result) {});
                return;
            }

            var allcheck = $('#all-check').prop('checked');
            if((shipMemberName.length < 1) && allcheck) { // 전체선원들을 선택한 경우
                var attendType = $('#all-select').val();
                var memo = $('#all-memo').val();
                if((attendType == 4) && (memo.length < 1)) {

                    $.gritter.add({
                        title: '错误',
                        text: "请输入上班情况和便条。",
                        class_name: 'gritter-error'
                    });
                    return;
                }
                var shipId = $('#ship_select').val();
                var selDate = $('#search-date').val();
                $.post('registerShipAllMember', {'_token':token,'selDate':selDate, 'shipId':shipId, 'status':attendType, 'memo':memo}, function (data) {

                    var code = parseInt(data);
                    if (isNaN(code)) {//AJAX요청이 성공한 경우의 처리
                        location.reload();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: "服务器错误。请稍后再试试。",
                            class_name: 'gritter-error'
                        });
                    }
                });
            } else {
                var trs = $("#attend_table").children();
                var content = Array();
                for (var i = 0; i < trs.length; i++) {
                    var checkCtl = trs[i].children[0].children[0];
                    if(checkCtl == null)
                        continue;

                    var control = trs[i].children[5].children[0];
                    if (control == null)
                        continue;

                    var attendType = control.value;
                    var memo = trs[i].children[6].children[0].value;

                    if(trs[i].children[0].children[0].children[0].checked) {
                        attendType = $('#all-select').val();
                        memo = $('#all-memo').val();
                    }

                    if((attendType == 4) && (memo.length < 1))
                        continue;

                    var rcontent = Array();
                    rcontent.push(trs[i].getAttribute('data-id'));
                    rcontent.push(attendType);
                    rcontent.push(memo);

                    content.push(rcontent.toString());
                }

                attendStr = content.toString();
                $.post('registerShipMemberAttend', {_token: token, attend: attendStr, selDate:selDate}, function (data) {
                    $("#modal-wizard").attr('aria-hidden', 'true');
                    $("#modalback").attr('class', 'in');
                    $("#modal-wizard").hide();

                    var code = parseInt(data);
                    if (isNaN(code)) {//AJAX요청이 성공한 경우의 처리
                        location.reload();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: "服务器错误。请稍后再试试。",
                            class_name: 'gritter-error'
                        });
                    }
                })
            }

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