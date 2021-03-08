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
                            <i class="icon-double-angle-right"></i>화물명관리
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius"  tyle="border-radius: 3px" style="width: 80px"><i class="icon-plus-sign-alt"></i>추가</button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_port" class="hide">
                                <form class="form-horizontal" method="post" action="registerShipCargo" id="cargo-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="cargoId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">화물명:</label>
                                        <div class="col-md-8"><input type="text" name="cargo_name" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">화물명(영문):</label>
                                        <div class="col-md-8"><input type="text" name="cargo_name_en" class="form-control"></div>
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
                                <th class="center">항구명</th>
                                <th class="center">항구명(영문)</th>
                                @if(!$isHolder)
                                    <th class="center" style="width: 70px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="port_table">
                            @if (count($list) > 0)
								<?php $index = ($list->currentPage() - 1) * $list->perPage() + 1; ?>
                                @foreach ($list as $cargo)
                                    <tr>
                                        <td data-id="{{$cargo['id']}}">{{$index++}}</td>
                                        <td class="center">{{$cargo['CARGO_Cn']}}</td>
                                        <td class="center">{{$cargo['CARGO_En']}}</td>
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
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>
        var token = '<?php echo csrf_token() ?>';

        function editShipCargo() {
            var portId = $('[name=cargoId]').val();
            var title = '화물명 추가';
            if(portId != '')
                title = '화물명 수정';
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
                    port_name: "required",
                    port_name_en: "required"
                },
                messages: {
                    port_name: "화물명을 입력하시오.",
                    port_name_en: "화물명의 영문이름을 입력하시오."
                }
            });

            $('.new_btn').on('click', function (){
                $('[name=cargoId]').val('0');
                $('[name=cargo_name]').val('');
                $('[name=cargo_name_en]').val('');

                editShipCargo();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=cargoId]').val(obj.eq(0).data('id'));
                $('[name=cargo_name]').val(obj.eq(1).text());
                $('[name=cargo_name_en]').val(obj.eq(2).text());

                editShipCargo();
            })

            $('.del_btn').on('click', function () {
                var trObj = $(this).closest('tr');
                var obj = trObj.children();
                var cargoId = obj.eq(0).data('id') * 1;
                var cargoName = obj.eq(1).text();

                bootbox.confirm("[" + cargoName + "] 화물명을 삭제하겠습니까?", function (result) {
                    if (result) {
                        //확인단추를 눌렀을 때의 처리
                        $.post('deleteShipCargo', {'_token':token, 'cargoId':cargoId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                trObj.remove();
                                $.gritter.add({
                                    title: '성공',
                                    text: '['+ cargoName + '] 화물명이 성과적으로 삭제되였습니다.',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '['+ cargoName + '] 화물명이 이미 삭제되였습니다.',
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
