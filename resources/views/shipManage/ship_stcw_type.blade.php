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
                    <h4><b>{{ transShipManager('title.input_basic_data') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>STCW规定代码
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius new_btn"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus"> 追加</i></button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_type" class="hide" style="overflow:visible">
                                <form class="form-horizontal" method="post" action="registerSTCWType" id="stcw-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="typeId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">国际代码:</label>
                                        <div class="col-md-7"><input type="text" name="STCWRegCode" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">代码名:</label>
                                        <div class="col-md-7"><input type="text" name="Contents" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">代码名(英文):</label>
                                        <div class="col-md-7"><input type="text" name="Contents_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">培训形态:</label>
                                        <div class="col-md-7">
                                            <select name="TrainingCourseID" class="form-control chosen-select">
                                                @foreach($typeList as $type)
                                                    <option value="{{$type['id']}}">{{$type['Course']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div class="table-responsive" id="ship_list_table">
                        <table id="ship_info_table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:60px">No</th>
                                <th class="center">规格代码</th>
                                <th class="center">代码名</th>
                                <th class="center">代码名(英文)</th>
                                <th class="center">培训形态</th>
                                @if(!$isHolder)
                                    <th class="center" style="width: 70px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="type_table">
                            @if (count($list) > 0)
								<?php $index = 1; ?>
                                @foreach ($list as $type)
                                    <tr>
                                        <td class="center">{{$index++}}</td>
                                        <td class="hidden">{{$type['id']}}</td>
                                        <td class="center">{{$type['STCWRegCode']}}</td>
                                        <td class="center">{{$type['Contents']}}</td>
                                        <td class="center">{{$type['Contents_En']}}</td>
                                        <td class="center" data-id="{{$type['TrainingCourseID']}}">{{$type['Course']}}</td>
                                        @if(!$isHolder)
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
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>
        var token = '<?php echo csrf_token() ?>';

        function editSTCWCodeType() {
            var typeId = $('[name=typeId]').val();
            var title = '追加规格代码';
            if(typeId != '')
                title = '修改规格代码';
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

            $("#stcw-form").validate({
                rules: {
                    STCWRegCode : "required",
                    Contents: "required",
                    Contents_En: "required",
                    TrainingCourseID: "required",
                },
                messages: {
                    STCWRegCode : "请输入规格代码。",
                    Contents: "请输入代码名称。",
                    Contents_En: "请输入英文名称。",
                    TrainingCourseID: "请选择培训形态。",
                }
            });

            $('.new_btn').on('click', function (){
                $('[name=typeId]').val('');
                $('[name=STCWRegCode]').val('');
                $('[name=Contents]').val('');
                $('[name=Contents_En]').val('');

                editSTCWCodeType();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=typeId]').val(obj.eq(1).text());
                $('[name=STCWRegCode]').val(obj.eq(2).text());
                $('[name=Contents]').val(obj.eq(3).text());
                $('[name=Contents_En]').val(obj.eq(4).text());
                $('[name=TrainingCourseID]').chosen('destroy');
                $('[name=TrainingCourseID]').val(obj.eq(5).data('id'));
                $('[name=TrainingCourseID]').chosen();
                editSTCWCodeType();
            });

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var typeId = obj.eq(1).text() * 1;
                var typeName = obj.eq(3).text();

                bootbox.confirm('[ ' + typeName + ' ] 真要删掉吗?', function (result) {
                    if (result) {
                        $.post('deleteSTCWType', {'_token':token, 'typeId':typeId}, function (result) {
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
                                    text: '[ ' + typeName + '] 是已经被删掉的',
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
