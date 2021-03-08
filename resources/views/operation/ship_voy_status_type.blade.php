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
                    <h4>
                        <b>배동태</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            항차일수항목
                        </small>
                    </h4>
                </div>
                <div class="col-md-6" style="text-align: right;padding-top:50px;">
                    @if(!$isHolder)
                        <button class="btn btn-sm btn-primary no-radius" style="width: 80px">
                            <i class="icon-plus-sign-alt"></i>
                            추가
                        </button>
                    @endif
                    <div id="dialog-modify" class="hide">
                        <form class="form-horizontal" action="updateVoyStatusType" id="VoyStatusTypeForm" method="POST">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="typeId" value="">
                            <input type="submit" class="hidden" id="submit_btn">
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">항차일수항목:</label>
                                <div class="col-md-8"><input type="text" name="ItemName" class="form-control"></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">항차일수항목(영문):</label>
                                <div class="col-md-8"><input type="text" name="VoyItem" class="form-control"></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">종류:</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="Type">
                                        <option value="1">경제일수</option>
                                        <option value="2">비경제일수</option>
                                        <option value="0">기타일수</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- #dialog-message -->
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-4"></div>
                    <table class="table table-bordered table-striped table-hover" id="ship_cert_table">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center">No</th>
                            <th class="center">항차일수항목</th>
                            <th class="center">항차일수항목(영문)</th>
                            <th class="center">종류</th>
                            @if(!$isHolder)
                                <th class="center" style="width:70px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="type-table">
						<?php $index = ($list->currentPage() - 1) * 15 + 1; ?>
                        @foreach($list as $type)
                            <tr>
                                <td class="center"data-id="{{$type['id']}}">{{$index++}}</td>
                                <td class="center">{{$type['ItemName']}}</td>
                                <td class="center">{{$type['VoyItem']}}</td>
                                <td class="center" data-type="{{$type['Type']}}">
                                    @if($type['Type'] == 1)
                                        경제일수
                                    @elseif($type['Type'] == 2)
                                        비경제일수
                                    @else
                                        기타
                                    @endif
                                </td>
                                @if(!$isHolder)
                                    <td class="action-buttons">
                                        <a class="blue edit-btn" href="javascript:void(0)">
                                            <i class="icon-edit bigger-130"></i>
                                        </a>
                                        <a class="red remove-btn" href="javascript:void(0)">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $list->render() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = '{!! csrf_token() !!}';

        $(document).ready(function () {

            $('.search-btn').on('click', function () {
                $('[name=typeId]').val('');
                $('[name=ItemName]').val('');
                $('[name=VoyItem]').val('');
                $('[name=Type]').val('');
                showModifyDialog('항목추가');
            });

            $('.edit-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                $('[name=typeId]').val(obj.eq(0).data('id'));
                $('[name=ItemName]').val(obj.eq(1).html());
                $('[name=VoyItem]').val(obj.eq(2).html());
                $('[name=Type]').val(obj.eq(3).data('type'));
                showModifyDialog('항목수정');
            });

            $('.remove-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var typeId = obj.eq(0).data('id');
                var itemName = obj.eq(1).html();
                var tableBody = document.getElementById('type-table');
                var rows = tableBody.children;
                var len = rows.length;
                var row = 0;
                for(;row<len;row++) {
                    var td = rows[row].children[0];
                    var selId = td.getAttribute('data-id');
                    if(typeId == selId)
                        break;
                }

                bootbox.confirm('['+ itemName + ']  ' + "  항목을 삭제하겠습니까?", function (result) {
                    if (result) {
                        //확인단추를 눌렀을 때의 처리
                        $.post('deleteVoyStatusType', {'_token':token, 'typeId':typeId}, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '오유',
                                    text: '['+ itemName + ']' + ' 항목은 이미 삭제되였습니다.',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });
            });

            $("#VoyStatusTypeForm").validate({
                rules: {
                    ItemName : "required",
                    VoyItem: "required",
                    Type : "required",
                },
                messages: {
                    ItemName : "항차일수항목을 입력하십시오.",
                    VoyItem: "사건이름을 입력하십시오.",
                    Type : "경제일수를 선택하십시오.",
                }
            });

            @if(isset($error))
            $.gritter.add({
                title: '오유',
                text: '{{$error}}',
                class_name: 'gritter-error'
            });
            @endif
        });

        function showModifyDialog(title) {
            var dialog = $("#dialog-modify").removeClass('hide').dialog({
                modal: true,
                title: title,
                title_html: true,
                buttons: [
                    {
                        text: "취소",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "확인",
                        "class": "btn btn-primary btn-xs",
                        click: function () {
                            $('#submit_btn').click();
                        }
                    }
                ]
            });
        }
    </script>
@endsection