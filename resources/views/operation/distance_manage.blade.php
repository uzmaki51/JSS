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
                        <b>基础资料输入</b>
                        <small id="parent_Item">
                            <i class="icon-double-angle-right"></i>
                            航海距离
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <form action="navigtionDistance" method="get">
                        <div class="col-md-3">
                            <label class="control-label no-padding-right" style="float: left;padding-top: 6px">出发港口</label>
                            <div class="col-sm-9">
                                <select class="form-control chosen-select" name="lp">
                                    <option value="" @if(empty($lport)) selected @endif>&nbsp;</option>
                                    @foreach($ports as $port)
                                        <option value="{{$port['id']}}"
                                                @if(isset($lport) && ($lport == $port['id'])) selected @endif>{{$port->Port_Cn}} | {{$port->Port_En}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label no-padding-right" style="float: left;padding-top: 6px">目的港口</label>
                            <div class="col-sm-9">
                                <select class="form-control chosen-select" name="dp">
                                    <option value="" @if(empty($dport)) selected @endif>&nbsp;</option>
                                    @foreach($ports as $port)
                                        <option value="{{$port['id']}}"
                                                @if(isset($dport) && ($dport == $port['id'])) selected @endif>{{$port->Port_Cn}} | {{$port->Port_En}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" style="float: left; width :80px">
                            <i class="icon-search"></i>搜索</button>
                    </form>
                    @if(!$isHolder)
                        <div class="col-md-3" style="float:right;text-align: right;">
                            <button class="btn btn-sm btn-primary no-radius" style="width: 80px" tyle="border-radius: 3px"><i class="icon-plus-sign-alt"></i>添加</button>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div id="dialog_update_distance" class="hide">
                        <form class="form-horizontal" method="post" action="updateDistance" id="form-distance">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="distanceId">
                            <button type="submit" class="hidden" id="submit_btn"></button>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">出发港口名:</label>
                                <div class="col-md-8">
                                    <select class="form-control chosen-select" name="LPort">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}">{{$port->Port_Cn}} | {{$port->Port_En}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">目的港口明:</label>
                                <div class="col-md-8">
                                    <select class="form-control chosen-select" name="DPort">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}">{{$port->Port_Cn}} | {{$port->Port_En}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-2"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label no-padding-right">航海距离:</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="distance">
                                </div>
                            </div>
                        </form>
                    </div><!-- #dialog-message -->
                </div>
                <div class="row">
                    <div class="space-6"></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center" style="width:50px">No</th>
                            <th class="center">出发港口名</th>
                            <th class="center">目的港口名</th>
                            <th class="center">航海距离</th>
                            @if(!$isHolder)
                                <th class="center" style="width: 70px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
						<?php $index = ($list->currentPage() - 1) * $list->perPage() + 1; ?>
                        @foreach ($list as $port)
                            <tr>
                                <td data-id="{{$port['ID']}}">{{$index++}}</td>
                                <td class="center" data-id="{{$port['LPortID']}}">{{$port['LPortName']['Port_Cn']}} | {{$port['LPortName']['Port_En']}}</td>
                                <td class="center" data-id="{{$port['DPortID']}}">{{$port['DPortName']['Port_Cn']}} | {{$port['DPortName']['Port_En']}}</td>
                                <td class="center">{{$port['SailDistance']}}</td>
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
                        </tbody>
                    </table>
                    {!! $list->render() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>

    <script>

        var token = '{!! csrf_token() !!}';

        $(function() {

            @if(isset($error))
            $.gritter.add({
                title: '错误',
                text: '{{$error}}',
                class_name: 'gritter-error'
            });
            @endif

            $("#form-distance").validate({
                rules: {
                    LPort: "required",
                    DPort: "required",
                    distance: "required | min:10"
                },
                messages: {
                    LPort: "出发请选择港口。.",
                    LPort: "目的请选择港口。.",
                    distance: "请输入航海距离。"
                }
            });

            $('.new_btn').on('click', function(){
                $('[name=LPort]').chosen('destroy');
                $('[name=DPort]').chosen('destroy');

                $('[name=distanceId]').val('');
                $('[name=LPort]').val('');
                $('[name=DPort]').val('');
                $('[name=distance]').val('0');

                $('[name=LPort]').chosen();
                $('[name=DPort]').chosen();

                editNavegatorDistance();
            });

            $('.edit_btn').on('click',function(){

                $('[name=LPort]').chosen('destroy');
                $('[name=DPort]').chosen('destroy');
                var obj = $(this).closest('tr').children();
                $('[name=distanceId]').val(obj.eq(0).data('id'));
                $('[name=LPort]').val(obj.eq(1).data('id'));
                $('[name=DPort]').val(obj.eq(2).data('id'));
                $('[name=distance]').val(obj.eq(3).text());

                $('[name=LPort]').chosen();
                $('[name=DPort]').chosen();

                editNavegatorDistance();
            });

            $('.del_btn').on('click',function(){
                var tr = $(this).closest('tr');
                var obj = tr.children();
                var distanceId = obj.eq(0).data('id');
                var LName = obj.eq(1).text();
                var DName = obj.eq(2).text();

                bootbox.confirm(LName + " - " + DName + "的距离项目真要删除吗?", function (result) {
                    if (result) {
                        //确认단추를 눌렀을 때의 처리
                        $.post('deleteDistance', {'_token':token, 'distanceId':distanceId}, function (result) {
                            tr.fadeOut();
                        });
                    }
                });
            });
        });

        function editNavegatorDistance() {
            var distanceId = $('[name=distanceId]').val();
            var title = '添加';
            if(distanceId != '')
                title = '修改';
            var dialog = $( "#dialog_update_distance" ).removeClass('hide').dialog({
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

    </script>
@endsection
