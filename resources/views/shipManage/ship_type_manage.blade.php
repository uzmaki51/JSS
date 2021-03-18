@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@section('content')
    <style>
        #div_contents::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{ transShipManager('title.input_basic_data') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>船舶类型管理
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius new_btn"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus"> 添加</i></button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_type" class="hide">
                                <form class="form-horizontal" method="post" action="registerShipType" id="type-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="typeId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">名称:</label>
                                        <div class="col-md-7"><input type="text" name="ShipType_Cn" class="form-control" required></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">名称(英文):</label>
                                        <div class="col-md-7"><input type="text" name="ShipType" class="form-control" required></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div style="width: 100%">
                        <table class="table table-bordered table-striped table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:10%">No</th>
                                <th class="center" style="width:25%">类型</th>
                                <th class="center" style="width:55%">类型(英文)</th>
                                @if(!$isHolder)
                                    <th class="center" style="width:10% "></th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="div_contents" style="overflow-x:hidden; overflow-y:auto; width:100%; height:67vh">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="type_table">
                            @if (count($list) > 0)
								<?php $index = 1; ?>
                                @foreach ($list as $type)
                                    <tr>
                                        <td class="center" style="width:10%">{{$index++}}</td>
                                        <td class="hidden">{{$type['id']}}</td>
                                        <td class="center" style="width:25%">{{$type['ShipType_Cn']}}</td>
                                        <td class="center" style="width:55%">{{$type['ShipType']}}</td>
                                        @if(!$isHolder)
                                            <td class="center action-buttons" style="width:10%">
                                                <a class="blue edit_btn">
                                                    <i class="icon-edit bigger-130"></i>
                                                </a>

                                                <a class="red del_btn">
                                                    <i class="icon-trash bigger-130"></i>
                                                </a>
                                            </td>
                                        @endif
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
            var typeId = $('[name=typeId]').val();
            var title = '添加类型';
            if(typeId != '')
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
                            $("#type-form").validate({
                                rules: {
                                    ShipType_Cn : "required",
                                    ShipType: "required",
                                },
                                messages: {
                                    ShipType_Cn : "请输入类型。",
                                    ShipType: "请输入类型(英文)。",
                                }
                            });
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
                    ShipType_Cn: "required",
                    ShipType: "required",
                },
                messages: {
                    ShipType_Cn: "请输入名称。",
                    ShipType: "请输入英文名称。",
                }
            });

            $('.new_btn').on('click', function (){
                $('[name=ShipType_Cn]').val('');
                $('[name=ShipType]').val('');
                $('[name=typeId]').val('');

                editShipType();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=typeId]').val(obj.eq(1).text());
                $('[name=ShipType_Cn]').val(obj.eq(2).text());
                $('[name=ShipType]').val(obj.eq(3).text());

                editShipType();
            })

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var typeId = obj.eq(1).text() * 1;
                var typeName = obj.eq(2).text();

                bootbox.confirm(typeName + " 真要删除吗?", function (result) {
                    if (result) {
                        $.post('deleteShipType', {'_token':token, 'typeId':typeId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                var tbody = document.getElementById('type_table');
                                var len = tbody.children.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var tds = tbody.children[row];
                                    var rowTypeId = Math.floor(tds.children[1].innerText);
                                    if(typeId == rowTypeId)
                                        break;
                                }
                                tbody.deleteRow(row);
                                $.gritter.add({
                                    title: '成功',
                                    text: typeName + ' 删除成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: typeName + ' 是已经被删除的。',
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
