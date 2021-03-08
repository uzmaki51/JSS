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
                    <h4><b>기초자료입력</b>
                        <small>
                            <i class="icon-double-angle-right"></i>Account
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-info no-radius"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus-sign-alt"></i>추가</button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_port" class="hide">
                                <form class="form-horizontal" method="post" action="addAccount" id="port-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="accountId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">이름:</label>
                                        <div class="col-md-8"><input type="text" name="AccountName_Cn" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">이름(영문):</label>
                                        <div class="col-md-8"><input type="text" name="AccountName_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">운임계산리용:</label>
                                        <div class="col-md-8"><input type="checkbox" name="isUse" style="width:17px;height:17px"></div>
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
                                <th class="center">이름</th>
                                <th class="center">이름(영문)</th>
                                <th class="center">운임계산에 리용</th>
                                @if(!$isHolder)
                                    <th class="center" style="width: 70px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="port_table">
                            @if (count($list) > 0)
								<?php $index = 1; ?>
                                @foreach ($list as $account)
                                    <tr>
                                        <td data-id="{{$account['id']}}">{{$index++}}</td>
                                        <td class="center">{{$account['AccountName_Cn']}}</td>
                                        <td class="center">{{$account['AccountName_En']}}</td>
                                        <td class="center" data-use="{{$account['isUse']}}">
                                            <input type="checkbox" @if($account['isUse']) checked @endif disabled>
                                        </td>
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

        function editAccount() {
            var portId = $('[name=accountId]').val();
            var title = '추가';
            if(portId != '')
                title = '수정';
            var dialog = $( "#dialog_update_port" ).removeClass('hide').dialog({
                modal: true,
                title: title,
                title_html: true,
                buttons: [
                    {
                        text: "취소",
                        "class" : "btn btn-xs",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "보관",
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
                title: '오유',
                text: '{{$error}}',
                class_name: 'gritter-error'
            });
            @endif

            $("#port-form").validate({
                rules: {
                    AccountName_Cn: "required",
                    AccountName_En: "required"
                },
                messages: {
                    AccountName_Cn: "이름을 입력하시오.",
                    AccountName_En: "영문이름을 입력하시오."
                }
            });

            $('.new_btn').on('click', function () {
                $('[name=accountId]').val('0');
                $('[name=AccountName_Cn]').val('');
                $('[name=AccountName_En]').val('');
                $('[name=isUse]').prof('ckecked');
                editAccount();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=accountId]').val(obj.eq(0).data('id'));
                $('[name=AccountName_Cn]').val(obj.eq(1).text());
                $('[name=AccountName_En]').val(obj.eq(2).text());
                var flag = obj.eq(3).data('use');
                if(flag)
                    $('[name=isUse]').prop('checked', 'checked');
                else
                    $('[name=isUse]').prop('checked');

                editAccount();
            })

            $('.del_btn').on('click', function () {
                var trObj = $(this).closest('tr');
                var obj = trObj.children();
                var accountId = obj.eq(0).data('id') * 1;

                bootbox.confirm("항목을 삭제하겠습니까?", function (result) {
                    if (result) {
                        //확인단추를 눌렀을 때의 처리
                        $.post('deleteAccount', {'_token':token, 'accountId':accountId}, function (result) {
                            trObj.fadeOut();
                        });
                    }
                });
            })
        });
    </script>
@stop
