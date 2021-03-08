@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{ transShipManager('title.equipment') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{ transShipManager('title.eqip_preg_management') }}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div id="main_type_modify" class="modify_dialog">
                        <form class="form-horizontal" action="updateEquipmentType" method="POST" id="form_type">
                            <input type="text" class="hidden" name="main_type_id" value="">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="space-4"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.type_Cn') }}</label>
                                <div class="col-md-8"><input type="text" name="type_name" class="form-control" value=""></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.type_en') }}</label>
                                <div class="col-md-8"><input type="text" name="type_name_en" class="form-control" value=""></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.description') }}</label>
                                <div class="col-md-8"><input type="text" name="type_descript" class="form-control" value=""></div>
                            </div>
                            <div style="padding-right: 20px;padding-bottom:10px;text-align: right">
                                <input class="btn btn-sm btn-warning cancel_btn" value="{{ transShipManager('captions.cancel') }}" style="width:80px">
                                <button class="btn btn-sm btn-info no-radius" style="width: 80px">{{ transShipManager('captions.register') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="widget-box">
                        <div class="widget-header  ">
                            <h4 class="lighter smaller col-md-4" style="color: #ffffff">{{ transShipManager('title.equip_mark_table') }}</h4>
                            @if(!$isHolder)
                                <div class="widget-toolbar action-buttons col-md-2">
                                    <button class="btn btn-xs btn-primary new_btn" style="width: 80px">
                                        <i class="icon-plus-sign-alt"></i>
                                        {{ trans('common.label.add') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="center">No</th>
                                        <th class="center">{{ transShipManager('EquipmentTypeManage.type_Cn') }}</th>
                                        <th class="center">{{ transShipManager('EquipmentTypeManage.type_en') }}</th>
                                        <th class="center" style="width:180px">{{ transShipManager('EquipmentTypeManage.description') }}</th>
                                        @if(!$isHolder)
                                            <th class="center" style="width:65px"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php $index = 1; ?>
                                    @foreach($mainKind as $kind)
                                        <tr>
                                            <td class="center">{{$index}}</td>
                                            <td class="hidden">{{$kind['id']}}</td>
                                            <td>{{$kind['Kind_Cn']}}</td>
                                            <td>{{$kind['Kind_En']}}</td>
                                            <td><span class="simple_text" style="width:90%">{{$kind['Remark']}}</span></td>
                                            @if(!$isHolder)
                                                <td>
                                                    <div class="action-buttons">
                                                        <a class="blue type_edit">
                                                            <i class="icon-edit bigger-130"></i>
                                                        </a>

                                                        <a class="red type_del">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
										<?php $index++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="sub_type_modify" class="modify_dialog">
                        <form class="form-horizontal" action="UpdateEquipmentUnits" method="POST" id="form_device">
                            <input type="text" class="hidden" name="unit_id" value="">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.unit_name') . '(' . trans('common.label.chinese') . ')'}}</label>
                                <div class="col-md-8"><input type="text" name="unit_cn" class="form-control" value=""></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.unit_name') . '(' . trans('common.label.english') . ')'}}</label>
                                <div class="col-md-8"><input type="text" name="unit_en" class="form-control" value=""></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">{{ transShipManager('EquipmentTypeManage.Remark') }}</label>
                                <div class="col-md-8"><input type="text" name="remark" class="form-control" value=""></div>
                            </div>
                            <div style="padding-right: 20px;padding-bottom:10px;text-align: right">
                                <input class="btn btn-sm btn-danger cancel_sub_btn" value="{{ trans('common.label.cancel') }}" style="width:80px">
                                <button class="btn btn-sm btn-primary btn-inverse save_sub_btn" style = "width :80px" >{{ trans('common.label.register') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="widget-box">
                        <div class="widget-header  ">
                            <h4 class="lighter smaller col-md-4" style="color: #ffffff">{{ transShipManager('title.equipment_unit_table') }}</h4>
                            @if(!$isHolder)
                                <div class="widget-toolbar action-buttons col-md-2">
                                    <button class="btn btn-xs btn-primary new_sub_btn" id="id-btn-adddialog" style="width: 100%">
                                        <i class="icon-plus-sign-alt"></i>
                                        {{ trans('common.label.add') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-striped table-bordered table-hover" id="equip_table">
                                    <thead>
                                    <tr>
                                        <th class="center">No</th>
                                        <th class="center">{{ transShipManager('EquipmentTypeManage.unit_name') . '(' . trans('common.label.chinese') . ')'}}</th>
                                        <th class="center">{{ transShipManager('EquipmentTypeManage.unit_name') . '(' . trans('common.label.chinese') . ')'}}</th>
                                        <th class="center">{{ transShipManager('EquipmentTypeManage.Remark') }}</th>
                                        @if(!$isHolder)
                                            <th class="center" style="width:65px"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php $index = 1; ?>
                                    @foreach($unitsList as $unit)
                                        <tr>
                                            <td data-sub="{{$unit['id']}}" data-kind="{{$unit['unit_cn']}}" class="center">{{$index}}</td>
                                            <td class="center">{{$unit['unit_cn']}}</td>
                                            <td>{{$unit['unit_en']}}</td>
                                            <td data-remark="{{$unit['remark']}}">{{$unit['remark']}}</td>
                                            @if(!$isHolder)
                                                <td>
                                                    <div class="action-buttons">
                                                        <a class="blue edit_sub_type">
                                                            <i class="icon-edit bigger-130"></i>
                                                        </a>

                                                        <a class="red del_sub_type">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
										<?php $index++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>

    <script>

        var token = '{!! csrf_token() !!}';
        var deleteMsg = '{!! trans('common.message.delete') !!}';

        $(function() {

            var oTable1 = $('#equip_table').dataTable( {
                "aoColumns":[ null, null,{"bSortable": false}, {"bSortable": false}, {"bSortable": false}]
            });

            $('.new_btn').on('click', function() {
                $('[name=main_type_id]').val('0');
                $('[name=type_name]').val('');
                $('[name=type_name-en]').val('');
                $('[name=type_descript]').val('');
                $("#main_type_modify").fadeIn();
            });

            $('.cancel_btn').on('click', function () {
                $("#main_type_modify").fadeOut();
            });

            $('.type_edit').on('click',function(){
                var obj = $(this).closest('tr').children();
                $('[name=main_type_id]').val(obj.eq(1).text());
                $('[name=type_name]').val(obj.eq(2).text());
                $('[name=type_name_en]').val(obj.eq(3).text());
                $('[name=type_descript]').val(obj.eq(4).text());
                $("#main_type_modify").fadeIn();
            });

            $('.type_del').on('click',function(){
                var obj = $(this).closest('tr').children();
                var typeId = obj.eq(1).text();
                var typeName = obj.eq(2).text();
                bootbox.confirm("设备配件种类 " + typeName + "真要删掉吗?", function (result) {
                    if (result) {
                        $.post('deleteEquipmentMainType', {'_token':token, 'typeId':typeId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                location.reload();
                            } else {
                                alert(typeName + "是已经被删掉的。");
                            }
                        });
                    }
                });
            });

            $('.new_sub_btn').on('click', function() {
                $('[name=equip_id]').val('0');
                $('[name=main_type]').val('');
                $('[name=sub_name_Cn]').val('');
                $('[name=sub_name_en]').val('');
                $('[name=sub_type_remark]').val('');
                $("#sub_type_modify").fadeIn();
            });

            $('.cancel_sub_btn').on('click', function () {
                $("#sub_type_modify").fadeOut();
            });

            $('.edit_sub_type').on('click',function(){
                var obj = $(this).closest('tr').children();
                $('[name=unit_id]').val(obj.eq(0).data('sub'));
                $('[name=unit_cn]').val(obj.eq(2).text());
                $('[name=unit_en]').val(obj.eq(3).text());
                $('[name=remark]').val(obj.eq(3).data('remark'));
                $("#sub_type_modify").fadeIn();
            });

            $('.del_sub_type').on('click',function(){
                var obj = $(this).closest('tr').children();
                var unitId = obj.eq(0).data('sub');
                var subName = obj.eq(2).text();
                bootbox.confirm(deleteMsg, function (result) {
                    if (result) {
                        $.post('deleteEquipmentUnits', {'_token':token, 'unit_id': unitId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                location.reload();
                            } else {
                                alert(typeName + "是已经被删掉了。");
                            }
                        });
                    }
                });
            });

            $('#form_type').validate({
                rules: {
                    type_name: "required",
                    type_name_en: "required",
                },
                messages: {
                    type_name: "请输入配件材料的名称。",
                    type_name_en: "请输入配件材料的英文名称。",
                }
            });

            $('#form_device').validate({
                rules: {
                    main_type: "required",
                    type_name: "required",
                    type_name_en: "required",
                },
                messages: {
                    main_type:"请选择设备的种类。",
                    type_name: "请输入设备名称。",
                    type_name_en: "请输入设备的英文名称。",
                }
            });
        })

    </script>
@endsection
