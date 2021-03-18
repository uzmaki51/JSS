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
                            <i class="icon-double-angle-right"></i>船舶结构行政管理
                        </small>
                    </h4>
                </div>
                @if(!$isHolder)
                    <div class="col-md-6" style="text-align: right;margin-top: 50px">
                        <button class="btn btn-sm btn-primary no-radius new_btn" style="width: 80px"><i class="icon-plus-sign-alt"></i>添加</button>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-12">
                        <div style="text-align: right">
                            <div id="dialog_update_ship" class="hide">
                                <form class="form-horizontal" method="post" action="registerShipOrigin" id="ship-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="shipId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('shipNameManage.ShipName of Structure_Cn') }}</label>
                                        <div class="col-md-7"><input type="text" name="origin_name" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('shipNameManage.ShipName of Structure_en') }}</label>
                                        <div class="col-md-7"><input type="text" name="shipNo" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('shipNameManage.Persons') }}</label>
                                        <div class="col-md-7"><input type="number" name="ship-person" class="form-control"></div>
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
                                <th class="center" style="width:150px">{{ transShipManager('shipNameManage.RegNo') }}</th>
                                <th class="center">{{ transShipManager('shipNameManage.ShipName of Structure_Cn') }}</th>
                                <th class="center">{{ transShipManager('shipNameManage.ShipName of Structure_en') }}</th>
                                <th class="center">{{ transShipManager('shipNameManage.Persons') }}</th>
                                @if(!$isHolder)
                                    <th class="center" style="width: 70px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="ship_table">
                            @if (count($list) > 0)
								<?php $index = 1 ?>
                                @foreach ($list as $shipinfo)
                                    <tr>
                                        <td class="center">{{$index}}</td>
                                        <td class="hidden">{{$shipinfo['id']}}</td>
                                        <td class="center">{{$shipinfo['name']}}</td>
                                        <td class="center">{{$shipinfo['shipNo']}}</td>
                                        <td class="center">{{$shipinfo['person_num']}}</td>
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
									<?php $index++?>
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

        function editShipOrigin() {
            var shipId = $('[name=shipId]').val();
            var title = '添加结构行政';
            if(shipId != '')
                title = '修改结构行政';
            var dialog = $( "#dialog_update_ship" ).removeClass('hide').dialog({
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

            $("#ship-form").validate({
                rules: {
                    origin_name: "required",
                    shipNo: "required",
                },
                messages: {
                    origin_name: "请输入名称。",
                    shipNo: "请输入英文名称。",
                }
            });
            $('.new_btn').on('click', function (){
                $('[name=shipId]').val('0');
                $('[name=origin_name]').val('');
                $('[name=shipNo]').val('');
                $('[name=ship-person]').val('');

                editShipOrigin();
            })

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=shipId]').val(obj.eq(1).text());
                $('[name=origin_name]').val(obj.eq(2).text());
                $('[name=shipNo]').val(obj.eq(3).text());
                $('[name=ship-person]').val(obj.eq(4).text());

                editShipOrigin();
            })

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var shipId = obj.eq(1).text() * 1;
                var shipName = obj.eq(2).text();

                bootbox.confirm(shipName + " 真要删除吗", function (result) {
                    if (result) {
                        $.post('deleteOriginShip', {'_token':token, 'shipId':shipId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                var tbody = document.getElementById('ship_table');
                                var len = tbody.children.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var tds = tbody.children[row];
                                    var rowShipId = Math.floor(tds.children[1].innerText);
                                    if(shipId == rowShipId)
                                        break;
                                }
                                tbody.deleteRow(row);
                                $.gritter.add({
                                    title: '成功',
                                    text: shipName + ' 删除成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: shipName + ' 是已经被删除的。',
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
