@extends('layout.sidebar')
@section('content')
    <style>
        .chosen-drop {
            width: 500px !important;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{ transShipManager('title.equipment') }}</b>
                        <label>
                            <i class="icon-double-angle-right"></i>
                            各船舶设备目录
                        </label>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            设备详细
                        </small>
                    </h4>
                </div>
                <div class="col-md-6 alert alert-block alert-info center" style="font-size: 16px">
                    <strong>[&nbsp;{{$shipName['name']}}({{$shipName['shipName_Cn']}}
                        )&nbsp;]号&nbsp;({{ $shipName['shipName_En'] }}) 设备 </strong>
                </div>
                <div class="col-sm-3">
                    <h5 style="float: right"><a href="javascript: history.back()"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="widget-box">
                        <div class="widget-header header-color-green3">
                            <h4 class="lighter smaller col-md-4">{{ transShipManager('EquipmentDetail.Equipment') }}</h4>
                        </div>
                        <form action="appendNewShipEquipment" method="POST" id="equipment_form">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="id" value="{{ $device['id'] }}">
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <form action="appendNewShipEquipment" method="POST" id="equipment_form">
                                        <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="text" class="hidden" name="equipId" value="{{$device['id']}}">
                                        <input type="text" class="hidden" name="shipId" value="{{$shipId}}">
                                        <input type="submit" class="hidden" id="submit_btn">
                                        <table class="arc-std-table table table-striped table-bordered">
                                            <thead>
                                            <tr class="black br-hblue">
                                                <th style="width:80px">{{ transShipManager('EquipmentDetail.Dept') }}<span
                                                            class="require">*</span></th>
                                                {{--<th>{{ transShipManager('EquipmentDetail.Kind') }}<span--}}
                                                {{--class="require">*</span></th>--}}
                                                {{--<th>{{ transShipManager('EquipmentDetail.PIC') }}</th>--}}
                                                <th>{{ transShipManager('EquipmentDetail.Equipment_Cn') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.Equipment_en') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.Label') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.S/N') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control chosen-select" name="mainKind">
                                                        @foreach($mainKinds as $kind)
                                                            <option value="{{$kind['id']}}"
                                                                    @if($kind['id'] == $kindInfo['KindId']) selected @endif>{{$kind['Kind_Cn']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                {{--<td>--}}
                                                {{--<select class="form-control chosen-select" name="subKind">--}}
                                                {{--@foreach($subKinds as $kind)--}}
                                                {{--<option value="{{$kind['id']}}"--}}
                                                {{--@if($kind['id'] == $kindInfo['KindofEuipmentId']) selected @endif>{{$kind['GroupOfEuipment_Cn']}}</option>--}}
                                                {{--@endforeach--}}
                                                {{--</select>--}}
                                                {{--</td>--}}
                                                {{--<td><input type="text" class="form-control" name="PIC"--}}
                                                {{--style="width:100%;text-align: center" value="{{$device['PIC']}}">--}}
                                                {{--</td>--}}
                                                <td><input type="text" class="form-control" name="Euipment_Cn"
                                                           style="width:100%;" value="{{$device['Euipment_Cn']}}"></td>
                                                <td><input type="text" class="form-control" name="Euipment_En"
                                                           style="width:100%;" value="{{$device['Euipment_En']}}"></td>
                                                <td><input type="text" class="form-control" name="Label"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Label']}}"></td>
                                                <td><input type="text" class="form-control" name="SN"
                                                           style="width:100%;text-align: center" value="{{$device['SN']}}">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="arc-std-table table table-striped table-bordered"
                                               style="border-top: 1px solid #e5e5e5;">
                                            <thead>
                                            <tr class="black br-hblue">
                                                {{--<th>{{ transShipManager('EquipmentDetail.Type/Model') }}<span--}}
                                                {{--class="require">*</span></th>--}}
                                                <th>{{ transShipManager('EquipmentManage.unit') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.Qty') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.IssaCode') }}</th>
                                                <th>{{ transShipManager('EquipmentManage.supplied_at') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.Remark') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                {{--<td><input type="text" class="form-control" name="Type"--}}
                                                {{--style="width:100%;text-align: center"--}}
                                                {{--value="{{$device['Type']}}"></td>--}}
                                                <td><input type="text" class="form-control" name="Unit"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Unit']}}"></td>
                                                <td><input type="text" class="form-control" name="Qty"
                                                           style="width:100%;text-align: center" value="{{$device['Qty']}}">
                                                </td>
                                                <td>
                                                    <div style="width: 100%;">
                                                        <input class="form-control" name="IssaCodeNo" id="IssaCodeNo" value="{{ $device['IssaCodeNo'] }}">
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control" name="ManufactureDate"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['supplied_at']}}"></td>
                                                <td><input type="text" class="form-control" name="Remark"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Remark']}}"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="space-6"></div>
                                        <div style="display: flex;">
                                            <button type="submit" class="btn btn-xs btn-inverse" style="width: 80px; margin-left: auto;">
                                                <i class="icon-save"></i>
                                                登记
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
            var token = '{!! csrf_token() !!}';
            var deviceId = '{!!$device['id']!!}';
            var index = 0;
            var unitList = new Array();
            var page1 = 1;
            var page2 = 1;

                    @foreach($units as $unit)
            var unit = new Object();
            unit.value = '{{$unit['id']}}';
            unit.text = '{{$unit['Unit_En']}}';
            unitList[index] = unit;
            index++;
            @endforeach

            $(function () {
                $('.new_btn').on('click', function () {
                    $('#submit_btn').click();
                });

                $('#equipment_form').validate({
                    rules: {
                        Euipment_Cn: "required",
                        Euipment_En: "required",
                        Label: "required",
                        Type: "required",
                    },
                    messages: {
                        Euipment_Cn: "请输入设备的名称。",
                        Euipment_En: "请输入英文名称。",
                        Label: "请输入设备的编号。",
                        Type: "请输入设备的形式/型。",
                    }
                });

                bindPropertyOperation();
                bindPropertyPaginate();
                bindPartOperation();
                bindPartPaginate();

                @if(isset($status) && ($status == 'success'))
                $.gritter.add({
                    title: '成功',
                    text: '{{$status}}',
                    class_name: 'gritter-success'
                });
                @elseif(isset($status))
                $.gritter.add({
                    title: '错误',
                    text: '{{$status}}',
                    class_name: 'gritter-error'
                });
                @endif




            });

            function bindPropertyOperation() {
                bindPropertyEdit();
                bindPropertySave();
                bindPropertyCancel();
                bindPropertyDelete();
                bindPropertyAdd();
            }

            function bindPropertyEdit() {
                $('.property_edit').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var id = obj.eq(1).text();
                    obj.eq(2).html('<input type="text" class="form-control" value="' + obj.eq(2).text() + '">');
                    obj.eq(3).html('<input type="text" class="form-control" value="' + obj.eq(3).text() + '">');
                    obj.eq(4).html('<input type="text" class="form-control" value="' + obj.eq(4).text() + '">');
                    obj.eq(5).html('<input type="text" class="form-control" value="' + obj.eq(5).text() + '">');
                    obj.eq(6).find('.row_edit').hide();
                    obj.eq(6).find('.row_apply').show();
                });
            }

            function bindPropertySave() {
                $('.property_save').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var property_id = obj.eq(1).text();
                    var Items_Cn = obj.eq(2).find('input').val();
                    var Items_En = obj.eq(3).find('input').val();
                    if ((Items_Cn.length < 1) || (Items_En.length < 1)) {
                        $.gritter.add({
                            title: '错误',
                            text: '请输入项目的名称。',
                            class_name: 'gritter-error'
                        });
                        return;
                    }

                    $.post('updateEquipmentProperty',
                        {
                            '_token': token,
                            'id': property_id,
                            'Items_Cn': Items_Cn,
                            'Items_En': Items_En,
                            'Particular': obj.eq(4).find('input').val(),
                            'Remark': obj.eq(5).find('input').val()
                        }, function (data) {
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                obj.eq(2).html(obj.eq(2).find('input').val());
                                obj.eq(3).html(obj.eq(3).find('input').val());
                                obj.eq(4).html(obj.eq(4).find('input').val());
                                obj.eq(5).html(obj.eq(5).find('input').val());
                                obj.eq(6).find('.row_edit').show();
                                obj.eq(6).find('.row_apply').hide();
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '名称重复!',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                });
            }

            function bindPropertyCancel() {
                $('.property_cancel').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var property_id = obj.eq(1).text();
                    obj.eq(2).html(obj.eq(2).find('input').val());
                    obj.eq(3).html(obj.eq(3).find('input').val());
                    obj.eq(4).html(obj.eq(4).find('input').val());
                    obj.eq(5).html(obj.eq(5).find('input').val());
                    obj.eq(6).find('.row_edit').show();
                    obj.eq(6).find('.row_apply').hide();
                });
            }

            function bindPropertyDelete() {
                $('.property_delete').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var propertyId = obj.eq(1).text();
                    var Items_Cn = obj.eq(2).text();

                    bootbox.confirm(Items_Cn + "真要删掉吗?", function (result) {
                        if (result) {
                            $.post('deleteEquipmentProperty', {
                                '_token': token,
                                'propertyId': propertyId
                            }, function (result) {
                                var code = parseInt(result);
                                if (code > 0) {
                                    var tbody = document.getElementById('property_table');
                                    var len = tbody.children.length;
                                    var row = 0;
                                    for (; row < len; row++) {
                                        var tds = tbody.children[row];
                                        var rowProId = Math.floor(tds.children[1].innerText);
                                        if (propertyId == rowProId)
                                            break;
                                    }
                                    tbody.deleteRow(row);
                                    $.gritter.add({
                                        title: '成功',
                                        text: Items_Cn + ' 删掉成功!',
                                        class_name: 'gritter-success'
                                    });
                                } else {
                                    $.gritter.add({
                                        title: '错误',
                                        text: Items_Cn + ' 是已经被删掉的。',
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            function bindPropertyAdd() {
                $('.add_property').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var property_id = obj.eq(1).text();
                    var Items_Cn = obj.eq(2).find('input').val();
                    var Items_En = obj.eq(3).find('input').val();
                    if ((Items_Cn.length < 1) || (Items_En.length < 1)) {
                        $.gritter.add({
                            title: '오유',
                            text: '기술적제원의 항목이름을 입력하여야 합니다.',
                            class_name: 'gritter-error'
                        });
                        return;
                    }

                    $.post('updateEquipmentProperty',
                        {
                            '_token': token,
                            'equipId': deviceId,
                            'Items_Cn': Items_Cn,
                            'Items_En': Items_Cn,
                            'Particular': obj.eq(4).find('input').val(),
                            'Remark': obj.eq(5).find('input').val()
                        }, function (data) {
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                obj.eq(1).text(returnCode);
                                obj.eq(2).html(obj.eq(2).find('input').val());
                                obj.eq(3).html(obj.eq(3).find('input').val());
                                obj.eq(4).html(obj.eq(4).find('input').val());
                                obj.eq(5).html(obj.eq(5).find('input').val());
                                var btnHtml = '<div class="row_edit">' +
                                    '<a class="blue property_edit"><i class="icon-edit bigger-130"></i></a>' +
                                    '<a class="red property_delete"><i class="icon-trash bigger-130"></i></a>' +
                                    '</div>' +
                                    '<div class="row_apply" style="display: none">' +
                                    '<a class="blue property_save"><i class="icon-save bigger-130"></i></a>' +
                                    '<a class="red property_cancel"><i class="icon-remove bigger-130"></i></a>' +
                                    '</div>';
                                obj.eq(6).html(btnHtml);

                                var tbody = document.getElementById('property_table');
                                var newtr = document.createElement('tr');
                                var leng = tbody.children.length;
                                if (leng < 1)
                                    index = 1;
                                else
                                    index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
                                var newHtml = '<td class="center">' + index + '</td>' +
                                    '<td class="hidden"><input type="text" class="form-control" name="propertyId"></td>' +
                                    '<td class="center"><input type="text" class="form-control" name="Items_Cn"></td>' +
                                    '<td class="center"><input type="text" class="form-control" name="Items_En"></td>' +
                                    '<td class="center"><input type="text" class="form-control" name="Particular"></td>' +
                                    '<td class="center"><input type="text" class="form-control" name="Remark"></td>' +
                                    '<td class="center action-buttons"><a class="blue add_property"><i class="icon-plus bigger-130"></i></a></td>';
                                newtr.innerHTML = newHtml;
                                tbody.appendChild(newtr);

                                unbindPropertyOperation();
                                bindPropertyOperation();
                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '기술적제원의 항목이름이 증복되였습니다.',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                });
            }

            function unbindPropertyOperation() {
                $('.property_edit').unbind('click');
                $('.property_save').unbind('click');
                $('.property_cancel').unbind('click');
                $('.property_delete').unbind('click');
                $('.add_property').unbind('click');
            }

            function bindPartOperation() {
                bindPartEdit();
                bindPartSave();
                bindPartCancel();
                bindPartDelete();
                bindPartAdd();
            }

            function bindPartEdit() {
                $('.part_edit').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var id = obj.eq(0).text();
                    var unitId = obj.eq(1).text();
                    obj.eq(3).html('<input type="text" class="form-control" value="' + obj.eq(3).html() + '">');
                    obj.eq(4).html('<input type="text" class="form-control" value="' + obj.eq(4).html() + '">');
                    obj.eq(5).html('<input type="text" class="form-control" value="' + obj.eq(5).html() + '">');
                    obj.eq(6).html('<input type="text" class="form-control" value="' + obj.eq(6).html() + '">');
                    var selectHtml = '<select class="form-control chosen-select">';
                    for (var i = 0; i < unitList.length; i++) {
                        unit = unitList[i];
                        selectHtml += '<option value="' + unit.value + '"';
                        if (unit.value == unitId) selectHtml += ' selected ';
                        selectHtml += '>' + unit.text + '</option>';
                    }
                    obj.eq(7).html(selectHtml);
                    obj.eq(8).html('<input type="number" class="form-control" value="' + obj.eq(8).html() + '">');
                    obj.eq(9).html('<input type="text" class="form-control" value="' + obj.eq(9).html() + '">');
                    obj.eq(10).html('<input type="text" class="form-control" value="' + obj.eq(10).html() + '">');
                    obj.eq(11).find('.row_edit').hide();
                    obj.eq(11).find('.row_apply').show();

                    obj.find('.chosen-select').chosen();
                });
            }

            function bindPartSave() {
                $('.part_save').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var part_id = obj.eq(0).text();
                    var Items_Cn = obj.eq(3).find('input').val();
                    var Items_En = obj.eq(4).find('input').val();
                    if ((Items_Cn.length < 1) || (Items_En.length < 1)) {
                        $.gritter.add({
                            title: '오유',
                            text: '부분품의 이름을 입력하여야 합니다.',
                            class_name: 'gritter-error'
                        });
                        return;
                    }

                    $.post('updateEquipmentPart',
                        {
                            '_token': token,
                            'id': part_id,
                            'PartName_Cn': Items_Cn,
                            'PartName_En': Items_En,
                            'Special': obj.eq(9).find('input').val(),
                            'PartNo': obj.eq(5).find('input').val(),
                            'IssaCodeNo': obj.eq(6).find('input').val(),
                            'Unit': obj.eq(7).find('select').val(),
                            'Qtty': obj.eq(8).find('input').val(),
                            'Remark': obj.eq(10).find('input').val()
                        }, function (data) {
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                var unitId = obj.eq(7).find('select').val();
                                var unitName = '';
                                for (var i = 0; i < unitList.length; i++) {
                                    unit = unitList[i];
                                    if (unit.value == unitId) {
                                        unitName = unit.text;
                                        break;
                                    }
                                }
                                obj.eq(3).html(obj.eq(3).find('input').val());
                                obj.eq(4).html(obj.eq(4).find('input').val());
                                obj.eq(5).html(obj.eq(5).find('input').val());
                                obj.eq(6).html(obj.eq(6).find('input').val());
                                obj.eq(7).html(unitName);
                                obj.eq(8).html(obj.eq(8).find('input').val());
                                obj.eq(9).html(obj.eq(9).find('input').val());
                                obj.eq(10).html(obj.eq(10).find('input').val());
                                obj.eq(11).find('.row_edit').show();
                                obj.eq(11).find('.row_apply').hide();
                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '부분품의 이름이 증복되였습니다.',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                });
            }

            function bindPartCancel() {
                $('.part_cancel').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var unitId = obj.eq(1).text();
                    var unitName = '';
                    for (var i = 0; i < unitList.length; i++) {
                        unit = unitList[i];
                        if (unit.value == unitId) {
                            unitName = unit.text;
                            break;
                        }
                    }
                    obj.eq(3).html(obj.eq(3).find('input').val());
                    obj.eq(4).html(obj.eq(4).find('input').val());
                    obj.eq(5).html(obj.eq(5).find('input').val());
                    obj.eq(6).html(obj.eq(6).find('input').val());
                    obj.eq(7).html(unitName);
                    obj.eq(8).html(obj.eq(8).find('input').val());
                    obj.eq(9).html(obj.eq(9).find('input').val());
                    obj.eq(10).html(obj.eq(10).find('input').val());
                    obj.eq(11).find('.row_edit').show();
                    obj.eq(11).find('.row_apply').hide();
                });
            }

            function bindPartDelete() {
                $('.part_delete').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var partId = obj.eq(0).text();
                    var Items_Cn = obj.eq(3).text();

                    bootbox.confirm(Items_Cn + " 부분품을 삭제하겠습니까?", function (result) {
                        if (result) {
                            //확인단추를 눌렀을 때의 처리
                            $.post('deleteEquipmentPart', {'_token': token, 'partId': partId}, function (result) {
                                var code = parseInt(result);
                                if (code > 0) {
                                    var tbody = document.getElementById('patrs_table_body');
                                    var len = tbody.children.length;
                                    var row = 0;
                                    for (; row < len; row++) {
                                        var tds = tbody.children[row];
                                        var rowPartId = Math.floor(tds.children[0].innerText);
                                        if (partId == rowPartId)
                                            break;
                                    }
                                    tbody.deleteRow(row);
                                    $.gritter.add({
                                        title: '성공',
                                        text: Items_Cn + ' 부분품이 성과적으로 삭제되였습니다.',
                                        class_name: 'gritter-success'
                                    });
                                } else {
                                    $.gritter.add({
                                        title: '오유',
                                        text: Items_Cn + ' 부분품이 이미 삭제되였습니다.',
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            function bindPartAdd() {
                $('.add_part').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var Items_Cn = obj.eq(3).find('input').val();
                    var Items_En = obj.eq(4).find('input').val();
                    if ((Items_Cn.length < 1) || (Items_En.length < 1)) {
                        $.gritter.add({
                            title: '오유',
                            text: '부분품의 이름을 입력하여야 합니다.',
                            class_name: 'gritter-error'
                        });
                        return;
                    }

                    $.post('updateEquipmentPart',
                        {
                            '_token': token,
                            'equipId': deviceId,
                            'PartName_Cn': Items_Cn,
                            'PartName_En': Items_Cn,
                            'Special': obj.eq(9).find('input').val(),
                            'PartNo': obj.eq(5).find('input').val(),
                            'IssaCodeNo': obj.eq(6).find('input').val(),
                            'Unit': obj.eq(7).find('select').val(),
                            'Qtty': obj.eq(8).find('input').val(),
                            'Remark': obj.eq(10).find('input').val()
                        }, function (data) {
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                var unitId = obj.eq(7).find('select').val();
                                var unitName = '';
                                for (var i = 0; i < unitList.length; i++) {
                                    unit = unitList[i];
                                    if (unit.value == unitId) {
                                        unitName = unit.text;
                                        break;
                                    }
                                }

                                obj.eq(0).html(returnCode);
                                obj.eq(1).html(unitId);
                                obj.eq(3).html(obj.eq(3).find('input').val());
                                obj.eq(4).html(obj.eq(4).find('input').val());
                                obj.eq(5).html(obj.eq(5).find('input').val());
                                obj.eq(6).html(obj.eq(6).find('input').val());
                                obj.eq(7).html(unitName);
                                obj.eq(8).html(obj.eq(8).find('input').val());
                                obj.eq(9).html(obj.eq(9).find('input').val());
                                obj.eq(10).html(obj.eq(10).find('input').val());
                                var btnHtml = '<div class="row_edit">' +
                                    '<a class="blue part_edit"><i class="icon-edit bigger-130"></i></a>' +
                                    '<a class="red part_delete"><i class="icon-trash bigger-130"></i></a>' +
                                    '</div>' +
                                    '<div class="row_apply" style="display: none">' +
                                    '<a class="blue part_save"><i class="icon-save bigger-130"></i></a>' +
                                    '<a class="red part_cancel"><i class="icon-remove bigger-130"></i></a>' +
                                    '</div>';
                                obj.eq(11).html(btnHtml);

                                var tbody = document.getElementById('patrs_table_body');
                                var newtr = document.createElement('tr');
                                var leng = tbody.children.length;
                                if (leng < 1)
                                    index = 1;
                                else
                                    index = Math.floor(tbody.children[leng - 1].children[2].innerText) + 1;
                                var newHtml = '<td class="hidden"></td>' +
                                    '<td class="hidden"></td>' +
                                    '<td class="center">' + index + '</td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center">';
                                var selectHtml = '<select class="form-control chosen-select"><option value=""></option>';
                                for (var i = 0; i < unitList.length; i++) {
                                    unit = unitList[i];
                                    selectHtml += '<option value="' + unit.value + '">' + unit.text + '</option>';
                                }
                                newHtml += selectHtml + '</td>' +
                                    '<td class="center"><input type="number" class="form-control"></td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center"><input type="text" class="form-control"></td>' +
                                    '<td class="center action-buttons"><a class="blue add_part"><i class="icon-plus bigger-130"></i></a></td>';
                                newtr.innerHTML = newHtml;
                                tbody.appendChild(newtr);

                                unbindPartOperation();
                                bindPartOperation();
                                $('#patrs_table_body').find('.chosen-select').chosen();

                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '부분품의 이름이 증복되였습니다.',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                });
            }

            function unbindPartOperation() {
                $('.part_edit').unbind('click');

                $('.part_save').unbind('click');

                $('.part_cancel').unbind('click');

                $('.part_delete').unbind('click');

                $('.add_part').unbind('click');
            }

            function bindPropertyPaginate() {
                $('#property .prev').on('click', function () {
                    page1--;
                    showEquipmentProperty(deviceId, page1);
                });

                $('#property .page').on('click', function () {
                    page1 = $(this).html();
                    showEquipmentProperty(deviceId, page1);
                });

                $('#property .next').on('click', function () {
                    page1++;
                    showEquipmentProperty(deviceId, page1);
                });
            }

            function bindPartPaginate() {
                $('#parts .prev').on('click', function () {
                    page2--;
                    showEquipmentPart(deviceId, page2);
                });

                $('#parts .page').on('click', function () {
                    page2 = $(this).html();
                    showEquipmentPart(deviceId, page2);
                });

                $('#parts .next').on('click', function () {
                    page2++;
                    showEquipmentPart(deviceId, page2);
                });
            }

            function showEquipmentProperty(deviceId, page) {
                $.post('propertyTabEquipmentByDeviceID', {
                    '_token': token,
                    'equipId': deviceId,
                    'page': page
                }, function (data) {
                    $('#property').html(data);
                    bindPropertyOperation();
                    bindPropertyPaginate();
                })
            }

            function showEquipmentPart(deviceId, page) {
                $.post('partTabEquipmentByDeviceID', {
                    '_token': token,
                    'equipId': deviceId,
                    'page': page
                }, function (data) {
                    $('#parts').html(data);
                    bindPartOperation();
                    bindPartPaginate();
                })
            }

        </script>
@endsection
