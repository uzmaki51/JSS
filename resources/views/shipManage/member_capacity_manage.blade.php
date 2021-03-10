<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))
        <div class="main-content">
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>{{ transShipManager('title.input_basic_data') }}</b>
                            <small>
                                <i class="icon-double-angle-right"></i>{{ transShipManager('title.ship_cert_management') }}
                            </small>
                        </h4>
                    </div>
                    <div class="col-md-6" style="text-align: right;margin-top: 10px">
                        <button class="btn btn-warning btn-sm excel_btn" style="width: 80px" ><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                        @if(!$isHolder)
                            <button class="btn btn-primary btn-sm new_btn" style="width: 80px" ><i class="icon-plus-sign-alt"></i>{{ transShipManager('captions.add') }}</button>
                        @endif
                        <div id="dialog_update_ship" class="hide">
                            <form class="form-horizontal" method="post" action="registerMemberCapacity" id="ship-form">
                                <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="text" class="hidden" name="capacityId">
                                <button type="submit" class="hidden" id="submit_btn"></button>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">No</label>
                                    <div class="col-md-7"><input type="number" step="1" name="orderNum" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">能力</label>
                                    <div class="col-md-7"><input type="text" name="capacity_Cn" class="form-control"></div>
                                </div>
                                <div class="space-2"></div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">能力(英文)</label>
                                    <div class="col-md-7"><input type="text" name="capacity_en" class="form-control"></div>
                                </div>
                                <div class="space-2"></div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">STCWCode</label>
                                    <div class="col-md-7">
                                        <select name="STCWRegID" class="form-control chosen-select">
                                            <option value=""></option>
                                            @foreach($STCWCodes as $STCWCode)
                                                <option value="{{ $STCWCode->id }}">{{ $STCWCode->STCWRegCode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="space-2"></div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">等级</label>
                                    <div class="col-md-7"><input type="text" name="grade" class="form-control"></div>
                                </div>
                                <div class="space-2"></div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">备注</label>
                                    <div class="col-md-7"><input type="text" name="remarks" class="form-control"></div>
                                </div>
                                <div class="space-2"></div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right">Gen_Remarks</label>
                                    <div class="col-md-7"><input type="checkbox" name="gen_remarks" value="1" class="form-control"></div>
                                </div>
                            </form>
                        </div><!-- #dialog-message -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        @else
                            @include('layout.excel-style')
                        @endif
                        <div class="table-responsive" id="ship_list_table">
                            <table id="ship_info_table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center">No</th>
                                    <th class="center">能力</th>
                                    <th class="center">能力(英文)</th>
                                    <th class="center">STCWCode</th>
                                    <th class="center">等级</th>
                                    <th class="center">备注</th>
                                    <th class="center">Gen_Remark</th>
                                    @if(!isset($excel) && !$isHolder)
                                        <th class="center"></th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody id="capacity_table">
                                @if (count($list) > 0)
                                    @foreach ($list as $capacity)
                                        <tr>
                                            <td class="center">{{$capacity['orderNum']}}</td>
                                            <td class="hidden">{{$capacity['id']}}</td>
                                            <td class="center">{{$capacity['Capacity']}}</td>
                                            <td class="center">{{$capacity['Capacity_En']}}</td>
                                            <td class="hidden">{{$capacity['STCWRegID']}}</td>
                                            <td class="center">{{$capacity['STCWRegCode']}}</td>
                                            <td class="center">{{$capacity['Grade']}}</td>
                                            <td class="center">{{$capacity['Remarks']}}</td>
                                            <td class="hidden">{{$capacity['Gen_Remarks']}}</td>
                                            <td class="center"><input type="checkbox" disabled @if($capacity['Gen_Remarks'] == 1) checked @endif></td>
                                            @if(!isset($excel) && !$isHolder)
                                                <td class="center">
                                                    <div class="action-buttons">
                                                        <a class="blue edit_btn">
                                                            <i class="icon-edit bigger-130"></i>
                                                        </a>

                                                        <a class="red del_btn">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if(!isset($excel))
                                {!! $list->render() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.main-content -->

        <script>
            var token = '<?php echo csrf_token() ?>';

            function editShipOrigin() {
                var capacityId = $('[name=capacityId]').val();
                var title = '追加海员适任';
                if(capacityId != '')
                    title = '修改海员适任';
                var dialog = $( "#dialog_update_ship" ).removeClass('hide').dialog({
                    modal: true,
                    title: title,
                    title_html: true,
                    buttons: [
                        {
                            text: "取消",
                            "class" : "btn btn-xs",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        },
                        {
                            text: "保存",
                            "class" : "btn btn-primary btn-xs",
                            click: function() {
                                $('#submit_btn').click();
                            }
                        }
                    ]
                });
            }

            $(function() {
                @if(isset($error))
                $.gritter.add({
                    title: '错误',
                    text: '{{$error}}',
                    class_name: 'gritter-error'
                });
                @endif

                $("#ship-form").validate({
                    rules: {
                        orderNum: "required",
                        capacity_Cn: "required",
                        capacity_en: "required",
                        STCWRegID : "required"
                    },
                    messages: {
                        orderNum: "请选择优先循序",
                        capacity_Cn: "请选择海员适任的名称。",
                        capacity_en: "请输入海员适任的英文名称。",
                        STCWRegID : "请选择STCW Code"
                    }
                });
                $('.new_btn').on('click', function (){
                    $('[name=capacityId]').val('0');
                    $('[name=orderNum]').val('');
                    $('[name=capacity_Cn]').val('');
                    $('[name=capacity_en]').val('');
                    $('[name=STCWRegID]').val('');
                    $('[name=grade]').val('');
                    $('[name=remarks]').val('');
                    $('[name=gen_remarks]').val('');

                    editShipOrigin();
                });

                $('.edit_btn').on('click', function () {
                    var obj = $(this).closest('tr').children();

                    $('[name=orderNum]').val(obj.eq(0).text());
                    $('[name=capacityId]').val(obj.eq(1).text());
                    $('[name=capacity_Cn]').val(obj.eq(2).text());
                    $('[name=capacity_en]').val(obj.eq(3).text());
                    $('[name=STCWRegID] option[value='+obj.eq(4).text()+']').attr('selected', 'selected');
                    $('[name=grade]').val(obj.eq(6).text());
                    $('[name=remarks]').val(obj.eq(7).text());
                    var gen_remarks = obj.eq(8).text();
                    if(gen_remarks == 1) $('[name=gen_remarks]').prop('checked', 'checked');

                    editShipOrigin();
                });

                $('.excel_btn').on('click', function () {

                    location.href = 'memberCapacityManageExcel';
                });

                $('.del_btn').on('click', function () {
                    var obj = $(this).closest('tr').children();
                    var capacityId = obj.eq(1).text() * 1;
                    var capacity_Cn = obj.eq(2).text();

                    bootbox.confirm(capacity_Cn + " 真要删掉吗?", function (result) {
                        if (result) {
                            $.post('deleteMemberCapacity', {'_token':token, 'capacityId':capacityId}, function (result) {
                                var code = parseInt(result);
                                if (code > 0) {
                                    var tbody = document.getElementById('capacity_table');
                                    var len = tbody.children.length;
                                    var row = 0;
                                    for (; row < len; row++) {
                                        var tds = tbody.children[row];
                                        var rowCapacityId = Math.floor(tds.children[1].innerText);
                                        if(capacityId == rowCapacityId)
                                            break;
                                    }
                                    tbody.deleteRow(row);
                                    $.gritter.add({
                                        title: '成功',
                                        text: capacity_Cn + ' 删掉成功！',
                                        class_name: 'gritter-success'
                                    });
                                } else {
                                    $.gritter.add({
                                        title: '错误',
                                        text: capacity_Cn + ' 是已经被删掉的。',
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    });
                })
            });

        </script>
    @endif
@stop
