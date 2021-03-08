@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{ transShipManager('title.equipment') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>国际船用品规格分类
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;float:right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius new_btn"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus"> 追加</i></button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_type" class="hide">
                                <form class="form-horizontal" method="post" action="registerISSACodeType" id="issacode-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="typeId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">分类号码:</label>
                                        <div class="col-md-7"><input type="text" name="Code" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">分类名:</label>
                                        <div class="col-md-7"><input type="text" name="Code_Cn" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">分类名(英文):</label>
                                        <div class="col-md-7"><input type="text" name="Code_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">备注:</label>
                                        <div class="col-md-7"><input type="text" name="Details" class="form-control"></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div style="overflow-y: scroll; width: 100%">
                        <table class="table table-bordered table-striped table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:5%">No</th>
                                <th class="center" style="width:7%">分类号码</th>
                                <th class="center" style="width:20%">分类名</th>
                                <th class="center" style="width:20%">分类名(英文)</th>
                                <th class="center" style="width:31%">备注</th>
                                <th class="center" style="width:7%"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="div_contents" style="overflow-x:hidden; overflow-y:auto; width:100%; height:67vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="type_table">
                            @if (count($list) > 0)
								<?php $index = 1; ?>
                                @foreach ($list as $type)
                                    <tr>
                                        <td class="center" style="width:5%">{{$index++}}</td>
                                        <td class="hidden">{{$type['id']}}</td>
                                        <td class="center" style="width:7%">{{$type['Code']}}</td>
                                        <td class="center" style="width:20%">{{$type['Code_Cn']}}</td>
                                        <td class="center" style="width:20%">{{$type['Code_En']}}</td>
                                        <td class="center" style="width:31%">{{$type['Details']}}</td>
                                        <td class="center action-buttons" style="width:7%">
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

    <script>
        var token = '<?php echo csrf_token() ?>';

        function editIssaCodeType() {
            var typeId = $('[name=typeId]').val();
            var title = '追加';
            if(typeId != '0')
                title = '修改';
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

            $("#issacode-form").validate({
                rules: {
                    Code : "required",
                    Code_Cn: "required",
                    Code_En: "required",
                },
                messages: {
                    Code : "请输入分类代码。",
                    Code_Cn: "请输入分类名称。",
                    Code_En: "请输入英文名称。",
                }
            });

            $('.new_btn').on('click', function (){
                var obj = $(this).closest('tr').children();

                $('[name=typeId]').val('0');
                $('[name=Code]').val('');
                $('[name=Code_Cn]').val('');
                $('[name=Code_En]').val('');
                $('[name=Details]').val('');

                editIssaCodeType();
            });

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=typeId]').val(obj.eq(1).text());
                $('[name=Code]').val(obj.eq(2).text());
                $('[name=Code_Cn]').val(obj.eq(3).text());
                $('[name=Code_En]').val(obj.eq(4).text());
                $('[name=Details]').val(obj.eq(5).text());

                editIssaCodeType();
            });

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var typeId = obj.eq(1).text() * 1;
                var typeName = obj.eq(3).text();

                bootbox.confirm('[ ' + typeName + ' ] 真要删掉吗?', function (result) {
                    if (result) {
                        $.post('deleteISSACodeType', {'_token':token, 'typeId':typeId}, function (result) {
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
                                    text: '[ ' + typeName + ' ] 删掉成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '[ ' + typeName + '] 是已经被删掉的。',
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
