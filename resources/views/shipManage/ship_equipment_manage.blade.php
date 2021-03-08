@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <style>
            .filter_row {
                background-color: #d6ffcb;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{ transShipManager('title.equipment') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            各船设备登录
                        </small>
                    </h4>
                </div>
                @if(isset($shipName['name']))
                    <div class="col-md-6 alert alert-block center" style="font-size: 16px">
                        <strong>【&nbsp;{{$shipName['name']}}({{$shipName['shipName_Cn']}})&nbsp;】号&nbsp;({{ $shipName['shipName_En'] }}) 设备目录 </strong>
                    </div>
                @endif
                <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" data-target="#modal-step-contents">
                                通知
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div style="float:left;padding-left:12px;padding-top:3px">
                        <label class="control-label no-padding-right" style="float: left;padding-top: 4px">{{ transShipManager('shipinfo.shipName') }}</label>
                        <div style="float:left;width:250px;padding-left:10px">
                            <select class="form-control" onchange="shipNameChange()" id="shipName">
                                @foreach($shipList as $ship)
                                    <option value="{{$ship['RegNo']}}"
                                            @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn'] .' | '.$ship['shipName_En']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-4"></div>
                <div class="space-4"></div>
                <div class="row" id="search-bar">
                    <div class="col-md-1">
                        <label class="control-label no-padding-right" >{{transShipManager("EquipmentManage.Equipment_Cn")}}: </label>
                        <input type="text" class="form-control" id="equipmentName" value="" placeholder="{{ trans('common.label.chinese') }}" name="params[EquipName_Cn]">
                    </div>
                    <div class="col-md-1">
                        <label class="control-label no-padding-right"  >{{ transShipManager("EquipmentManage.Equipment_en")}}: </label>
                        <input type="text" class="form-control" id="equipmentName" value="" placeholder="{{ trans('common.label.english') }}" name="params[EquipName_En]">
                    </div>
                    <div class="col-md-1">
                        <label class="control-label no-padding-right">{{ transShipManager("EquipmentTypeManage.type")}}: </label>
                        <select class="form-control" name="params[EquipType]">
                            <option value="">{{ trans('common.message.please_select') }}</option>
                            @if(isset($kindLabelList))
                                @foreach($kindLabelList as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label no-padding-right">Issa Code: </label>
                        <input type="text" class="form-control" id="equipmentName" value="" placeholder="{{ trans('common.label.issa_code') }}" name="params[Issa_Code]">
                    </div>
                    <div class="col-md-1">
                        <label class="control-label no-padding-right">{{ trans('common.label.term') }}: </label>
                        <select class="form-control" name="params[EquipName_Term]">
                            <option value="">{{ trans('common.message.please_select') }}</option>
                            @foreach(g_enum('TermData') as $key => $data)
                                <option value="{{ $key }}">{{ $data[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label no-padding-right" >{{ transShipManager("EquipmentManage.diligence") . transShipManager("EquipmentManage.status")}}: </label>
                        <select class="form-control" name="params[Status]">
                            <option value="">Any</option>
                            @foreach(g_enum('InventoryStatusData') as $key => $item)
                                <option value="{{ $key }}">{{ $item[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button class="btn btn-info btn-sm search-btn width-100"><i class="icon-search"></i>{{ transShipManager('General.search') }}</button>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button class="btn btn-warning btn-sm excel-btn width-100"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                    </div>
                </div>
                <div class="space-4"></div>
                <div class="row" id="equipment_table">
                    @include('shipManage.ship_equipment_table', with(['list'=>$list, 'shipId'=>$shipId, 'page'=>1]))
                </div>

                <div class="row" id="equipment_register">
                    @include('shipManage.ship_equipment_register', with(['list'=>$list, 'shipId'=>$shipId, 'page'=>1]))
                </div>

                <a href="#detail_modal_table" role="button" data-toggle="modal" class="hidden" id="detail_btn">showDetail</a>
                <a href="#dialog-modify-equipment" role="button" data-toggle="modal" class="hidden" id="modify_btn">showDetail</a>
                <a href="#dialog-diligence-register" role="button" data-toggle="modal" class="hidden" id="register_diligence">Register Diligence</a>
                <div id="detail_modal_table" class="modal fade" tabindex="-1">
                </div>
                <div id="dialog-modify-equipment" class="modal fade" tabindex="-1">
                    <div class="modal-dialog" style="width:80%;padding-top:12%" >
                        <div class="modal-content">
                            <div class="modal-header no-padding">
                                <div class="table-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        <span class="white">&times;</span>
                                    </button>
                                    <span name="modify_title"></span>
                                </div>
                            </div>
                            <div class="modal-body no-padding">
                                <form action="appendNewShipEquipment" method="POST" id="equipment_form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="shipId" value="@if(isset($shipId)){{$shipId}}@endif">
                                    <table class="arc-std-table table table-striped table-bordered" style="text-align:center;">
                                        <thead>
                                        <tr>
                                            <td style="width:80px; ">{{ transShipManager('EquipmentManage.Equipment Type') }}<span class="require">*</span></td>
                                            {{--<td>{{ transShipManager('EquipmentManage.Tool Type') }}<span class="require">*</span></td>--}}
                                            {{--<td>{{ transShipManager('EquipmentManage.PIC') }}</td>--}}
                                            <td>{{ transShipManager('EquipmentManage.Equipment_Cn') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Equipment_en') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Label') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.S/N') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="mainKind">
                                                    @foreach($allKind as $kind)
                                                        <option value="{{$kind['id']}}">{{$kind['Kind_Cn']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{--<td><input type="text" class="form-control" name="PIC" style="width:100%;"></td>--}}
                                            <td><input type="text" class="form-control" name="Euipment_Cn" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Euipment_En" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Label" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="SN" style="width:100%;"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="arc-std-table table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td>{{ transShipManager('EquipmentManage.unit') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.Qty') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.IssaCode') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Maker') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.supplied_at') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.Remark') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="Unit" style="width:100%;">
                                                    @foreach($unitList as $key => $item)
                                                        <option value="{{ $item['unit_cn'] }}">{{ $item['unit_en'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="Qty" style="width:100%;" value="1"></td>
                                            <td><input type="text" class="form-control" name="IssaCodeNo" style="width:100%;" required></td>
                                            <td><input type="text" class="form-control" name="Maker" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="ManufactureDate" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Remark" style="width:100%;"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button class="hidden" id='submit_btn'></button>
                                </form>
                            </div>
                            <div class="modal-footer padding-8" style="text-align: right">
                                <button class="btn btn-primary btn-sm btn-warning" data-dismiss="modal">取消</button>
                                <button class="btn btn-primary btn-sm btn-danger save_btn">登录</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="dialog-diligence-register" class="modal fade" tabindex="-1">
                    <div class="modal-dialog" style="width:80%;padding-top:12%" >
                        <div class="modal-content">
                            <div class="modal-header no-padding">
                                <div class="table-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        <span class="white">&times;</span>
                                    </button>
                                    <span name="diligence_title"></span>
                                </div>
                            </div>
                            <div class="modal-body no-padding">
                                <form action="appendNewShipDiligenceEquipment" method="POST" id="equipment_diligence_form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="shipId" value="@if(isset($shipId)){{$shipId}}@endif">
                                    <table class="arc-std-table table table-striped table-bordered" style="text-align:center;">
                                        <thead>
                                        <tr>
                                            <td style="width:80px; ">{{ transShipManager('EquipmentManage.Equipment Type') }}<span class="require">*</span></td>
                                            {{--<td>{{ transShipManager('EquipmentManage.Tool Type') }}<span class="require">*</span></td>--}}
                                            {{--<td>{{ transShipManager('EquipmentManage.PIC') }}</td>--}}
                                            <td>{{ transShipManager('EquipmentManage.Equipment_Cn') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Equipment_en') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Label') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.S/N') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="mainKind">
                                                    @foreach($allKind as $kind)
                                                        <option value="{{$kind['id']}}">{{$kind['Kind_Cn']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{--<td>--}}
                                            {{--<select class="form-control" name="subKind">--}}
                                            {{--@foreach($allKind[0]['subKind'] as $kind)--}}
                                            {{--<option value="{{$kind['id']}}">{{$kind['GroupOfEuipment_Cn']}}</option>--}}
                                            {{--@endforeach--}}
                                            {{--</select>--}}
                                            {{--</td>--}}
                                            {{--<td><input type="text" class="form-control" name="PIC" style="width:100%;"></td>--}}
                                            <td><input type="text" class="form-control" name="Euipment_Cn" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Euipment_En" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Label" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="SN" style="width:100%;"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="arc-std-table table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td>{{ transShipManager('EquipmentManage.unit') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.remain_count') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.IssaCode') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.status') }}</td>
                                            <td>{{ transShipManager('EquipmentManage.diligence_at') }}<span class="require">*</span></td>
                                            <td>{{ transShipManager('EquipmentManage.Remark') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="Unit" style="width:100%;">
                                                    @foreach($unitList as $key => $item)
                                                        <option value="{{ $item['unit_cn'] }}">{{ $item['unit_en'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="remain_count" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="IssaCodeNo" style="width:100%;"></td>
                                            <td>
                                                <select  class="form-control" name="Status" style="width:100%;">
                                                    @foreach(g_enum('InventoryStatusData') as $key => $item)
                                                        <option value="{{ $key }}">{{ $item[0] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="diligenceDate" style="width:100%;"></td>
                                            <td><input type="text" class="form-control" name="Remark" style="width:100%;"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button class="hidden" id='diligence_submit_btn' type="submit"></button>
                                </form>
                            </div>
                            <div class="modal-footer padding-8" style="text-align: right">
                                <button class="btn btn-primary btn-sm btn-warning" data-dismiss="modal">取消</button>
                                <button class="btn btn-primary btn-sm btn-danger save_btn">登录</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('/assets/js/fuelux/data/fuelux.tree-sampledata.js')}}"></script>
    <script src="{{asset('/assets/js/fuelux/fuelux.tree.custom.min.js')}}"></script>

    <script>
        var token = '{!! csrf_token() !!}';
        var shipId = '{{ $shipId }}';
        var kindId = '';
        var registerType = true;

        var shipName_Cn = '';
        @if(isset($shipName['name']))
            shipName_Cn = '{!! $shipName['name'] !!}호';
                @endif

        var tree_data = new Object();
        @foreach($kindList as $kind)
            tree_data['{{$kind['Kind_Cn']}}'] = {name:'{{$kind['Kind_Cn']}}', type:'folder', 'icon-class':'blue'};
        tree_data['{{$kind['Kind_Cn']}}']['additionalParameters'] = new Object;
        tree_data['{{$kind['Kind_Cn']}}']['additionalParameters']['children'] = new Array();
        var subindex = 0;
        @foreach($kind['subKind'] as $subKind)
            tree_data['{{$kind['Kind_Cn']}}']['additionalParameters']['children'][subindex] =
            {name:'<input type="text" class="hidden" value="{{$subKind['id']}}"><i class="icon-archive green"></i>{{$subKind['GroupOfEuipment_Cn']}}', type:'item', value:'{{$subKind['id']}}'};
        subindex++;
                @endforeach
                @endforeach
        var treeDataSource = new DataSourceTree({data: tree_data});

        $(document).ready(function () {
            $('#tree').ace_tree({
                dataSource: treeDataSource,
                loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
                'open-icon' : 'icon-folder-open',
                'close-icon' : 'icon-folder-close',
                'selectable' : true,
                'selected-icon' : null,
                'unselected-icon' : null
            });

            $('[name=mainKind]').on('change', function () {
                var mainKind = $(this).val();
                $.post('shipSubEquipemntList', {'_token':token, 'mainKind':mainKind}, function (data) {
                    $('[name=subKind]').html(data);
                })
            });

            $('.save_btn').on('click', function() {console.log(registerType);
                registerType == true ? $('#submit_btn').click() : $('#equipment_diligence_form').submit();

            });

            $('#equipment_form').validate({
                rules: {
                    mainKind : "required",
                    // subKind : "required",
                    IssaCodeNo : "required",
                    // Type : "required",
                    Euipment_Cn: "required",
                    Euipment_En: "required",
                },
                messages: {
                    mainKind : "请选择设备种类。",
                    IssaCodeNo : "请输入Issa Code。",
                    Euipment_Cn: "请输入设备名称。",
                    Euipment_En: "请输入英文名称。",
                }
            });

            $("#equipmentName").keypress(function() {
                var keyword = $(this).val();
                if(keyword.length < 1)
                    return;
                var rows = $('#device_table').children();
                var len = rows.length;
                var row = 0;
                for(;row<len;row++) {
                    var tr = rows.eq(row);
                    tr.removeClass('filter_row');
                    var tdCount = rows[row].children.length;
                    var equipmentName = '';
                    if(tdCount > 9)
                        equipmentName = rows[row].children[3].innerText;
                    else
                        equipmentName = rows[row].children[0].innerText;

                    var index = equipmentName.indexOf(keyword);
                    if(index > -1) {
                        tr.addClass('filter_row');
                        if(tdCount > 9) {
                            tr = rows.eq(row+1);
                            tr.addClass('filter_row');
                            row++;
                        } else {
                            tr = rows.eq(row-1);
                            tr.addClass('filter_row');
                        }
                    }
                }

            });

            $('.search-btn').on('click', function () {
                showPrevPageShipEquipment(1);
            });

            $('.excel-btn').on('click', function() {
                var keyword = $('#equipmentName').val();
                location.href = 'shipEquepmentByKindExcel?shipId='+shipId+'&kindId='+kindId+'&keyword='+keyword;
            });

            $('.more-btn').on('click', function () {
                var child = $(this).children();
                if(child.eq(0).hasClass('icon-chevron-sign-up')) {
                    child.eq(0).removeClass('icon-chevron-sign-up');
                    child.eq(0).addClass('icon-chevron-sign-down');
                    $('.widget-body').fadeOut();
                } else {
                    child.eq(0).removeClass('icon-chevron-sign-down');
                    child.eq(0).addClass('icon-chevron-sign-up');
                    $('.widget-body').fadeIn();
                }
            });
            @if(isset($status))
            $.gritter.add({
                title: '오유',
                text: '{{$status}}',
                class_name: 'gritter-error'
            });
            @endif
        });

        function shipNameChange() {
            shipId = $('#shipName').val();
            location.href = 'shipEquipmentManage?shipId=' + shipId;
        }

        function loadShipEquipmentData(subtitle, subKind) {

            shipId = $('#shipName').val();
            kindId = subKind;
            $('[name=kindId]').val(subKind);

            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>[' + subtitle +']的设备资料正在加载中';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();

            showPrevPageShipEquipment(1);
        }

        function showPrevPageShipEquipment(page) {
            var keyword = $('#equipmentName').val();
            let params = $('[name^="params"]').serializeArray();
            let paramList = {};
            params.forEach(function(value, key) {
                paramList[value['name']] = value['value'];
            });

            $.post("shipEquepmentByKind", {'_token':token, 'shipId':shipId, 'kindId':kindId, 'params': JSON.stringify(paramList), 'page':page}, function(data) {
                if(data) {
                    $('#search-bar').show();
                    $('#equipment_table').html(data);
                } else {
                    $('#equipment_table').html('');
                    $("#new_btn").addClass('hidden');
                }

                $("#modal-wizard").attr('aria-hidden', 'true');
                $("#modalback").attr('class', 'in');
                $("#modal-wizard").hide();

                $("#new_btn").removeClass('hidden');
            });
        }

        function showEquepmentDetail(id, equipName, issaCode, shipId) {
            $.post("getSupplyHistory", {'_token':token, 'shipId': shipId, 'issa_code':issaCode, 'equipName': equipName}, function(data) {
                $('#detail_modal_table').html(data);
                $('#detail_btn').click();
                bindPropertyPaginate();
                bindPartPaginate();
            });
        }

        function newEquipment() {
            if(registerType == true) {
                $('[name=modify_title]').text('追加新设备');
                $('#modify_btn').click();
            } else {
                $('[name=diligence_title]').text('追歼事实资料');
                $('#register_diligence').click();
            }
        }

        function deleteShipEquipment(obj) {
            var deviceId = obj.eq(1).text() * 1;
            var deviceName = obj.eq(3).text();
            console.log(deviceId);
            bootbox.confirm(deviceName + " 真要删掉吗?", function (result) {
                if (result) {
                    $.post('deleteShipEquipment', {'_token':token, 'deviceId':deviceId}, function (result) {
                        var code = parseInt(result);
                        if (code > 0) {
                            var tbody = document.getElementById('device_table');
                            var len = tbody.children.length;
                            var row = 0;
                            for (; row < len; row++) {
                                var tds = tbody.children[row];
                                var rowDeviceId = Math.floor(tds.children[1].innerText);
                                if(deviceId == rowDeviceId)
                                    break;
                            }
                            tbody.deleteRow(row);
                            tbody.deleteRow(row+0.5);
                            $.gritter.add({
                                title: '성공',
                                text: '[' + deviceName + '] 删掉成功！',
                                class_name: 'gritter-success'
                            });
                        } else {
                            $.gritter.add({
                                title: '오유',
                                text: '[' + deviceName + '] 是已经被删掉的。',
                                class_name: 'gritter-error'
                            });
                        }
                    });
                }
            });

        }

        function bindPropertyPaginate() {
            $('.property-table .prev').on('click', function () {
                page1--;
                showEquipmentProperty(deviceId, page1);
            });

            $('.property-table .page').on('click', function () {
                page1 = $(this).html();
                showEquipmentProperty(deviceId, page1);
            });

            $('.property-table .next').on('click', function () {
                page1++;
                showEquipmentProperty(deviceId, page1);
            });
        }

        function bindPartPaginate() {
            $('.part-table .prev').on('click', function () {
                page2--;
                showEquipmentPart(deviceId, page2);
            });

            $('.part-table .page').on('click', function () {
                page2 = $(this).html();
                showEquipmentPart(deviceId, page2);
            });

            $('.part-table .next').on('click', function () {
                page2++;
                showEquipmentPart(deviceId, page2);
            });
        }

        function showEquipmentProperty(deviceId, page) {
            $.post('propertyTableEquipmentByDeviceID', {'_token':token, 'equipId':deviceId, 'page':page}, function (data) {
                $('.property-table').html(data);
                bindPropertyPaginate();
            })
        }

        function showEquipmentPart(deviceId, page) {
            $.post('partTableEquipmentByDeviceID', {'_token':token, 'equipId':deviceId, 'page':page}, function (data) {
                $('.part-table').html(data);
                bindPartPaginate();
            })
        }

    </script>

@endsection
