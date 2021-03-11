@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{ transShipManager('title.input_basic_data') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>其他设备配件管理
                        </small>
                    </h4>
                </div>
                <div class="col-md-6" style="text-align: right;margin-top: 10px">
                    <button class="btn btn-primary btn-sm new_btn"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus">添加</i></button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_type" class="hide">
                                <form class="form-horizontal" method="post" action="registerShipOthers" id="type-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="OthersId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">类型名称:</label>
                                        <div class="col-md-8"><input type="text" name="Others_Cn" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">类型名称(英文):</label>
                                        <div class="col-md-8"><input type="text" name="Others_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">Special:</label>
                                        <div class="col-md-8"><input type="text" name="Special" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">Remark:</label>
                                        <div class="col-md-8"><input type="text" name="Remark" class="form-control"></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div style="width: 100%; overflow-y: scroll;">
                        <table class="table table-bordered table-striped table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:5%">No</th>
                                <th class="center" style="width:20%">类型</th>
                                <th class="center" style="width:25%">类型(英文)</th>
                                <th class="center" style="width:20%">Special</th>
                                <th class="center" style="width:20%">Remark</th>
                                <th class="center" style="width:10% "></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="div_contents" style="overflow-x:hidden; overflow-y: scroll; width:100%; height:67vh">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="type_table">
                            @if (count($list) > 0)
                                @foreach ($list as $type)
                                    <tr>
                                        <td class="center" style="width:5%">{{$type['OthersId']}}</td>
                                        <td class="center" style="width:20%; word-break: break-all;">{{$type['Others_Cn']}}</td>
                                        <td class="center" style="width:25%; word-break: break-all;">{{$type['Others_En']}}</td>
                                        <td class="center" style="width:20%; word-break: break-all;">{{$type['Special']}}</td>
                                        <td class="center" style="width:20%; word-break: break-all;">{{$type['Remark']}}</td>
                                        <td class="center action-buttons" style="width:10%">
                                            <a class="blue edit_btn">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                            <a class="red del_btn">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script src="{{ asset('/assets/js/jquery.gritter.min.js')}}"></script>
    <script>
        var token = '<?php echo csrf_token() ?>';

        function editShipType() {
            var othersId = $('[name=OthersId]').val();
            var title = '添加类型';
            if(othersId != '')
                title = '修改类型';
            var dialog = $( "#dialog_update_type" ).removeClass('hide').dialog({
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

            $("#position-form").validate({
                        rules: {
                            Others_Cn: "required",
                            Others_En: "required",
                        },
                        messages: {
                            Others_Cn: "请输入名称。",
                            Others_En: "请输入英文名称。",
                        }
                    });

            $('.new_btn').on('click', function (){
                $('[name=OthersId]').val('');
                $('[name=Others_Cn]').val('');
                $('[name=Others_En]').val('');
                $('[name=Special]').val('');
                $('[name=Remark]').val('');

                editShipType();
            });

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=OthersId]').val(obj.eq(0).text());
                $('[name=Others_Cn]').val(obj.eq(1).text());
                $('[name=Others_En]').val(obj.eq(2).text());
                $('[name=Special]').val(obj.eq(3).text());
                $('[name=Remark]').val(obj.eq(4).text());

                editShipType();
            });

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var OthersId = obj.eq(0).text();
                var Others_Cn = obj.eq(1).text();

                bootbox.confirm(Others_Cn + " 真要删掉吗?", function (result) {
                    if (result) {
                        $.post('deleteShipOthers', {'_token':token, 'OthersId':OthersId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                var tbody = document.getElementById('type_table');
                                var len = tbody.children.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var tds = tbody.children[row];
                                    var rowTypeId = Math.floor(tds.children[0].innerText);
                                    if(OthersId == rowTypeId)
                                        break;
                                }
                                tbody.deleteRow(row);
                                $.gritter.add({
                                    title: '成功',
                                    text: typeName + ' 删掉成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: typeName + ' 是已经被删掉的。',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                    }
                });
            })
        });

    </script>
@stop
