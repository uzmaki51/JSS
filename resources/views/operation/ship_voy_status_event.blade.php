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
                        <b>船舶动态</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            经济日数项目
                        </small>
                    </h4>
                </div>
                <div class="col-md-6" style="text-align:right;padding-top:50px;float:right;">
                    @if(!$isHolder)
                        <button class="btn btn-sm btn-primary no-radius" style="width: 80px">
                            <i class="icon-plus-sign-alt"></i>
                            追加
                        </button>
                    @endif
                    <div id="dialog-modify" class="hide">
                        <form class="form-horizontal" action="updateVoyStatusEvent" id="VoyEventUpdateForm" method="POST">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="eventId" value="">
                            <input type="submit" class="hidden" id="submit_btn">
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">航次形态:</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="TypeId">
                                        @foreach($typeList as $type)
                                            <option value="{{$type['id']}}">{{$type['ItemName']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">事件:</label>
                                <div class="col-md-8"><input type="text" name="Event" class="form-control"></div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">说明:</label>
                                <div class="col-md-8"><input type="text" name="Description" class="form-control"></div>
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
                            <th class="center">航次形态</th>
                            <th class="center">事件</th>
                            <th class="center">说明</th>
                            @if(!$isHolder)
                                <th class="center" style="width:70px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="event-table">
                        <?php $index = ($list->currentPage() - 1) * 15 + 1; ?>
                        @foreach($list as $event)
                            <tr>
                                <td class="center"data-id="{{$event['id']}}">{{$index++}}</td>
                                <td class="center" data-type="{{$event['TypeId']}}">{{$event['typeName']['ItemName']}}</td>
                                <td class="center">{{$event['Event']}}</td>
                                <td class="center">{{$event['Description']}}</td>
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

    <script src="{{ asset('/assets/js/jquery.gritter.min.js')}}"></script>

    <script>
        var token = '{!! csrf_token() !!}';

        $(document).ready(function () {

            $('.search-btn').on('click', function () {
                $('[name=eventId]').val('');
                $('[name=TypeId]').val('');
                $('[name=Event]').val('');
                $('[name=Description]').val('');
                showModifyDialog('追加事件');
            });

            $('.edit-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                $('[name=eventId]').val(obj.eq(0).data('id'));
                $('[name=TypeId]').val(obj.eq(1).data('type'));
                $('[name=Event]').val(obj.eq(2).html());
                $('[name=Description]').val(obj.eq(3).html());
                showModifyDialog('修改事件');
            });

            $('.remove-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var eventId = obj.eq(0).data('id');
                var eventName = obj.eq(2).html();
                var tableBody = document.getElementById('event-table');
                var rows = tableBody.children;
                var len = rows.length;
                var row = 0;
                for(;row<len;row++) {
                    var td = rows[row].children[0];
                    var selId = td.getAttribute('data-id');
                    if(eventId == selId)
                        break;
                }

                bootbox.confirm('['+ eventName + ']  ' + "  事件真要删掉吗?", function (result) {
                    if (result) {
                        //确认단추를 눌렀을 때의 처리
                        $.post('deleteVoyStatusEvent', {'_token':token, 'eventId':eventId}, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '['+ eventName + ']' + ' 是已经被删掉了。',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });
            });

            $("#VoyEventUpdateForm").validate({
                rules: {
                    TypeId : "required",
                    Event: "required",
                },
                messages: {
                    TypeId : "请选择航次的形态。",
                    Event: "请输入时间的名称。",
                }
            });

            @if(isset($error))
                $.gritter.add({
                        title: '错误',
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
                        text: "取消",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "确认",
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