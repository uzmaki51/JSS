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
                            <i class="icon-double-angle-right"></i>乘船职务管理
                        </small>
                    </h4>
                </div>

            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label no-padding-right" style="float: left;padding-top:7px">职务</label>
                        <input type="text" class="form-control" id="posName" style="float:left;margin-left: 10px;width:70%" value="@if(isset($posName)){{$posName}}@endif">
                    </div>
                    <button class="btn btn-primary btn-sm search-btn" style="float:left; width :80px"><i class="icon-search"></i>搜索</button>
                    @if(!$isHolder)
                        <div class="col-md-3" style="text-align: right;float:right;margin-top:0px">
                            <button class="btn btn-sm btn-primary no-radius new_btn" style="width: 80px"><i class="icon-plus-sign-alt"></i>追加</button>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_position" class="hide">
                                <form class="form-horizontal" method="post" action="registerShipPosition" id="position-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="posId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">职务名称:</label>
                                        <label class="col-md-4 control-label no-padding-right" hidden></label>
                                        <div class="col-md-7"><input type="text" name="Duty" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" name="shipId">Duty:</label>
                                        <div class="col-md-7"><input type="text" name="Duty_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">说明:</label>
                                        <div class="col-md-7"><input type="text" name="pos_descript" class="form-control"></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div style="width: 100%">
                        <table id="ship_info_table" class="table table-striped table-bordered table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:4%">No</th>
                                <th class="center" style="width:20%">职务</th>
                                <th class="center" style="width:25%">责任</th>
                                <th class="center" style="width:45%">说明</th>
                                @if(!$isHolder)
                                    <th class="center" style="width:6%"></th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="div_contents" style="overflow-x:hidden; overflow-y:auto; width:100%; height:67vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody id="pos_table">
							<?php $index = 1; ?>
                            @foreach ($list as $pos)
                                <tr>
                                    <td class="center" style="width:4%">{{$index++}}</td>
                                    <td class="hidden">{{$pos['id']}}</td>
                                    <td class="center" style="width:20%">{{$pos['Duty']}}</td>
                                    <td class="center" style="width:25%">{{$pos['Duty_En']}}</td>
                                    <td class="center" style="width:45%">{{$pos['Description']}}</td>
                                    @if(!$isHolder)
                                        <td class="center" style="width:6%" class="action-buttons">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>
        var token = '<?php echo csrf_token() ?>';

        function editShipPosition() {
            var shipId = $('[name=shipId]').val();
            var title = '追加职务';
            if(shipId != '')
                title = '修改职务';
            var dialog = $( "#dialog_update_position" ).removeClass('hide').dialog({
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

            $('.search-btn').on('click', function () {
                var keyword = $('#posName').val();
                var param = '';
                if(keyword.length > 0)
                    param = '?name=' + keyword;

                location.href = 'shipPositionManage' + param;
            });

            $("#position-form").validate({
                rules: {
                    Duty: "required",
                    Duty_En: "required",
                },
                messages: {
                    Duty: "请输入名称。",
                    Duty_En: "请输入英文名称。",
                }
            });

            $('.new_btn').on('click', function (){
                $('[name=posId]').val('0');
                $('[name=Duty]').val('');
                $('[name=Duty_En]').val('');
                $('[name=pos_descript]').val('');
                $('[name=shipId]').val('');

                editShipPosition();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=posId]').val(obj.eq(1).text());
                $('[name=Duty]').val(obj.eq(2).text());
                $('[name=Duty_En]').val(obj.eq(3).text());
                $('[name=pos_descript]').val(obj.eq(4).text());

                editShipPosition();
            })

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var posId = obj.eq(1).text() * 1;
                var posName = obj.eq(2).text();

                bootbox.confirm(posName + " 真要删掉吗?", function (result) {
                    if (result) {
                        $.post('deleteShipPosition', {'_token':token, 'posId':posId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                var tbody = document.getElementById('pos_table');
                                var len = tbody.children.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var tds = tbody.children[row];
                                    var rowPosId = Math.floor(tds.children[1].innerText);
                                    if(posId == rowPosId)
                                        break;
                                }
                                tbody.deleteRow(row);
                                $.gritter.add({
                                    title: '成功',
                                    text: posName + ' 删掉成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: posName + ' 是已经被删掉的。',
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
