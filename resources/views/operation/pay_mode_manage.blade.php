@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>基础资料输入</b>
                        <small>
                            <i class="icon-double-angle-right"></i>Account
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius" style="width: 80px" tyle="border-radius: 3px"><i class="icon-plus-sign-alt"></i>添加</button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_port" class="hide">
                                <form class="form-horizontal" method="post" action="addPayMode" id="port-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="payId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">名称:</label>
                                        <div class="col-md-8"><input type="text" name="PayMode_Cn" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">名称(英文):</label>
                                        <div class="col-md-8"><input type="text" name="PayMode_En" class="form-control"></div>
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
                                <th class="center">No</th>
                                <th class="center">名称</th>
                                <th class="center">名称(英文)</th>
                                @if(!$isHolder)
                                    <th class="center" style="width: 70px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="port_table">
                            @if (count($list) > 0)
								<?php $index = 1; ?>
                                @foreach ($list as $pay)
                                    <tr>
                                        <td data-id="{{$pay['id']}}">{{$index++}}</td>
                                        <td class="center">{{$pay['PayMode_Cn']}}</td>
                                        <td class="center">{{$pay['PayMode_En']}}</td>
                                        @if(!$isHolder)
                                            <td class="center action-buttons">
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
    <script>
        var token = '<?php echo csrf_token() ?>';

        function editPayMode() {
            var portId = $('[name=payId]').val();
            var title = '添加';
            if(portId != '')
                title = '修改';
            var dialog = $( "#dialog_update_port" ).removeClass('hide').dialog({
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
            $("#port-form").validate({
                rules: {
                    PayMode_Cn: "required",
                    PayMode_En: "required"
                },
                messages: {
                    PayMode_Cn: "请输入名称。",
                    PayMode_En: "请输入英文名称。"
                }
            });

            $('.new_btn').on('click', function () {
                $('[name=payId]').val('0');
                $('[name=PayMode_Cn]').val('');
                $('[name=PayMode_En]').val('');

                editPayMode();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=payId]').val(obj.eq(0).data('id'));
                $('[name=PayMode_Cn]').val(obj.eq(1).text());
                $('[name=PayMode_En]').val(obj.eq(2).text());

                editPayMode();
            })

            $('.del_btn').on('click', function () {
                var trObj = $(this).closest('tr');
                var obj = trObj.children();
                var payId = obj.eq(0).data('id') * 1;

                bootbox.confirm("项目真要删掉吗?", function (result) {
                    if (result) {
                        //确认단추를 눌렀을 때의 처리
                        $.post('deletePayMode', {'_token':token, 'payId':payId}, function (result) {
                            trObj.fadeOut();
                        });
                    }
                });
            })
        });
    </script>
@stop
