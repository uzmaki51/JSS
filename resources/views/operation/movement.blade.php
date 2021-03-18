<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

<div class="main-content">

    <link rel="stylesheet" href="/assets/css/bootstrap-timepicker.css" />
    <style>
        .chosen-drop{width:345px !important;}
    </style>
    <div class="page-content">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label no-padding-right" style="float: left;padding-top: 6px">{{ t('movement.ShipName', array(), 'operation') }}</label>
                    <div class="col-sm-3">
                        <select class="chosen-select" id="select_ship">
                            <?php $selectedShipName = ''; ?>
                            @foreach($shipList as $ship)
                                <?php
                                if($shipID == $ship->RegNo) $selectedShipName = $ship->shipName_Cn;
                                ?>
                                @if(!$isHolder)
                                        <option value="{{ $ship->RegNo }}" {{ $shipID != $ship->RegNo ? '' : 'selected' }}>
                                            {{ $ship->shipName_Cn }} | {{ $ship->shipName_En }}
                                        </option>
                                @elseif($isHolder)
                                    @if(in_array($ship->regId, $ships))
                                        <option value="{{ $ship->RegNo }}" {{ $shipID != $ship->RegNo ? '' : 'selected' }}>
                                            {{ $ship->shipName_Cn }} | {{ $ship->shipName_En }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                    &nbsp;&nbsp;
                    <label class="control-label no-padding-right" style="float: left;padding-top: 6px">{{ t('movement.VoyNo', array(), 'operation') }}</label>
                    <div class="col-sm-3">
                        <select class="chosen-select" id="select_voy">
                            <?php $voyNum = '0'; ?>
                            @foreach($voyList as $voy)
                                <option value="{{$voy['id']}}" <?php if($voy['id'] == $voyNo) {echo 'selected'; $voyNum = $voy->Voy_No;} ?>>{{ $voy->Voy_No }} | {{$voy->CP_No}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 pull-right">
                    <button class="btn btn-primary btn-sm select_ctrl" style="float: left; margin-left: 5px; width :80px"><i class="icon-search"></i>搜索</button>
                    @if(!$isHolder)
                        <button class="btn btn-primary btn-sm add-btn" style="float: left; margin-left: 5px; width :80px"><i class="icon-plus-sign-alt"></i>添加</button>
                    @endif
                    <button class="btn btn-warning btn-sm excel-btn" style="float: left; margin-left: 5px; width :80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                </div>
            </div>
            <div class="space-6"></div>
            <div class="row">
                <div style="overflow-y: scroll;width: 100%">
@else
    @include('layout.excel-style')
@endif
                    <table class="arc-std-table table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:10%" rowspan="2">{{ t('movement.Voy_Date', array(), 'operation') }}</th>
                                <th class="center" style="width:17%" rowspan="2">{{ t('movement.Status_Name', array(), 'operation') }}</th>
                                <th class="center" style="width:15%" rowspan="2">{{ t('movement.Ship_Position', array(), 'operation') }}</th>
                                <th class="center" style="width:6%" rowspan="2">{{ t('movement.Cargo_Qtty', array(), 'operation') }}</th>
                                <th class="center" style="width:6%" rowspan="2"><?php print_r(t('movement.Sail_Distance', array(), 'operation'));?></th>
                                <th class="center" colspan="5" style="width: 30%;">{{transShipOperation("movement.Balance")}}</th>
                                <th class="center" style="width:10%" rowspan="2">{{ t('movement.Remarks', array(), 'operation') }}</th>
                                @if(!isset($excel) && !$isHolder)
                                    <td style="width:6%" rowspan="2"></td>
                                @endif
                            </tr>
                            <tr class="black br-hblue">
                                <th class="center" style="width:6%"><?php print_r(t('movement.ROB_FO', array(), 'operation'));?></th>
                                <th class="center" style="width:6%"><?php print_r(t('movement.ROB_DO', array(), 'operation'));?></th>
                                <th class="center" style="width:6%"><?php print_r(t('movement.ROB_LO_M', array(), 'operation'));?></th>
                                <th class="center" style="width:6%"><?php print_r(t('movement.ROB_LO_A', array(), 'operation'));?></th>
                                <th class="center" style="width:6%"><?php print_r(t('movement.ROB_FW', array(), 'operation'));?></th>
                            </tr>
                        </thead>
@if(!isset($excel))
                    </table>
                </div>
                <div style="overflow-x:hidden; overflow-y: scroll; width:100%; height:45vh; border-bottom: 1px solid #eee">
                    <table class="table table-bordered table-striped table-hover">
@endif
                        <tbody id="log-table">
                            @foreach($data as $list)
                            <tr>
                                <td data-id="{{$list->id}}" style="width:10%;">
                                    {!! convert_datetime_origin($list->Voy_Date) !!}
                                </td>
                                <td class="center" data-id="{{$list->Voy_Status}}" style="width:17%">{{ $list->Voy_St }} | {{ $list->Status_Name }}</td>
                                <td class="center" style="width:15%;word-break: break-all;">{{ $list->Ship_Position }}</td>
                                <td class="center" style="width:6%" data-value="@if($list->Cargo_Qtty){{$list->Cargo_Qtty}}@endif">@if($list->Cargo_Qtty) {{ number_format($list->Cargo_Qtty, 0, '.', ',') }} @endif</td>
                                <td class="center" style="width:6%;word-break: break-all;" data-value="@if($list->Sail_Distance){{$list->Sail_Distance}}@endif">@if($list->Sail_Distance) {{ number_format($list->Sail_Distance, 0, '.', ',') }} @endif</td>
                                <td class="right" style="width:6%;word-break: break-all;">{{ $list->ROB_FO }}</td>
                                <td class="right" style="width:6%;word-break: break-all;">{{ $list->ROB_DO }}</td>
                                <td class="right" style="width:6%;word-break: break-all;">{{ $list->ROB_LO_M }}</td>
                                <td class="right" style="width:6%;word-break: break-all;">{{ $list->ROB_LO_A }}</td>
                                <td class="right" style="width:6%;word-break: break-all;">{{ $list->ROB_FW }}</td>
                                <td class="left" style="width:10%;word-break: break-all;"><a class="simple_text hide-option" style="width:100px;cursor: pointer;height: 15px;" title="{{ $list->Remarks }}">{{ $list->Remarks }}</a></td>
@if(!isset($excel) && !$isHolder)
                                <td class="action-buttons" style="width:6%">
                                    <a href="javascript:void(0);" class="blue row_modify_btn"><i class="icon-edit bigger-130"></i></a>
                                    <a href="javascript:void(0);" class="red row_trash_btn"><i class="icon-trash bigger-130"></i></a>
                                </td>
@endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
@if(!isset($excel))
                 </div>
            </div>
            <div class="space-10"></div>
            <div class="row">
                {{-- modify field --}}
                <div class="add-movement-box input" style="padding-bottom: 10px">
                    <form action="updateMovement" method="POST" id="voy-log-form">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="voyId" value="">
                        <table class="arc-std-table table table-bordered table-hover table-striped" style="margin-bottom: 8px;font-size: 13px">
                            <thead >
                            <tr class="black br-hblue">
                                <th width="150px">{{ t('movement.ShipName', array(), 'operation') }}</th>
                                <th nowrap>{{ t('movement.VoyNo', array(), 'operation') }}</th>
                                <th style="width:150px">{{ t('movement.Voy_Date', array(), 'operation') }}</th>
                                <th style="width:120px">{{ t('movement.Voy_Time', array(), 'operation') }}</th>
                                <th style="width:160px">{{ t('movement.Status_Name', array(), 'operation') }}</th>
                                <th>{{ t('movement.Ship_Position', array(), 'operation') }}</th>
                                <th>{{ t('movement.Cargo_Qtty', array(), 'operation') }}</th>
                                <th><?php print_r(t('movement.Sail_Distance', array(), 'operation'));?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="chosen-select" name="shipId" id="select_ship_">
                                        <?php $selectedShipName = ''; ?>
                                        @foreach($shipList as $ship)
                                            <?php
                                            if($shipID == $ship->RegNo) $selectedShipName = $ship->shipName_Cn;
                                            ?>
                                            <option style="text-align: left" value="{{ $ship->RegNo }}" {{ $shipID != $ship->RegNo ? '' : 'selected' }}>
                                                {{ $ship->shipName_Cn }} | {{ $ship->shipName_En }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{--<input type="text" name="shipId" value="{{$selectedShipName}}">--}}
                                </td>
                                <td>
                                    <select class="chosen-select" name="voyNo" id="select_voy_">
                                        <?php $voyNum = '0'; ?>
                                        @foreach($voyList as $voy)
                                            <option value="{{$voy['id']}}" <?php if($voy['id'] == $voyNo) {echo 'selected'; $voyNum = $voy->Voy_No;} ?>>{{ $voy->Voy_No }} | {{$voy->CP_No}}</option>
                                        @endforeach
                                    </select>
                                    {{--<input type="text" name="voyNo" value="{{$voyNum}}">--}}
                                </td>
                                <td>
                                    <div class="input-group" style="width:140px">
                                        <input class="form-control date-picker" id="search-date" name="voyDate" type="text"
                                               data-date-format="yyyy/mm/dd" value="">
                                        <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group bootstrap-timepicker" style="width:110px">
                                        <input name="voyTime" type="text" class="form-control" value=""/>
                                        <span class="input-group-addon">
                                            <i class="icon-time bigger-110"></i>
                                        </span>
                                    </div>
                                </td>
                                <td style="width:160px">
                                    <div style="width:160px">
                                        <select name="voyStatus" class="chosen-select">
                                            <option></option>
                                            @foreach($shipStatusList as $list)
                                                <option value="{{$list['id']}}">
                                                    {{$list['Voy_Status']}} | @if(!empty($list['Related_Economy'])){{$list['economyEvent']['Event']}} @else &nbsp;&nbsp;&nbsp; @endif
                                                    | @if(!empty($list['Related_UnEconomy'])){{$list['uneconomyEvent']['Event']}} @else &nbsp;&nbsp;&nbsp; @endif
                                                    | @if(!empty($list['Related_Other'])){{$list['otherEvent']['Event']}} @else &nbsp;&nbsp;&nbsp; @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td><input class="form-control" name="voyPos" id="voyPos-complex"></td>
                                <td><input type="number" name="voyQtty" style="width:100%"></td>
                                <td><input type="number" name="voyDistance" style="width:100%"></td>
                            </tr>
                            {{--@endforeach--}}
                            </tbody>
                            <thead>
                                <tr class="black br-hblue">
                                    <th><?php print_r(t('movement.ROB_FO', array(), 'operation'));?></th>
                                    <th><?php print_r(t('movement.ROB_DO', array(), 'operation'));?></th>
                                    <th><?php print_r(t('movement.ROB_LO_M', array(), 'operation'));?></th>
                                    <th><?php print_r(t('movement.ROB_LO_A', array(), 'operation'));?></th>
                                    <th><?php print_r(t('movement.ROB_FW', array(), 'operation'));?></th>
                                    <th colspan="2">{{ t('movement.Remarks', array(), 'operation') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" name="voyFO" style="width:100%"></td>
                                    <td><input type="number" name="voyDO" style="width:100%"></td>
                                    <td><input type="number" name="voyLOM" style="width:100%"></td>
                                    <td><input type="number" name="voyLOA" style="width:100%"></td>
                                    <td><input type="number" name="voyFW" style="width:100%"></td>
                                    <td colspan="2"><textarea name="voyRemark" row="1" style="width:100%;"></textarea></td>
                                    <td class="center">
                                        <button type="submit" class="btn btn-sm btn-primary" style="width: 80px"><i class="icon-plus-sign-alt"></i>添加</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="/assets/js/date-time/bootstrap-timepicker.min.js"></script>
    <script>
        jQuery(function(e){

            var token = '<?php echo csrf_token() ?>';

            var position = <?php
                echo '[';
                foreach($shipPositionList as $shipPosition) {
                    echo '{label:"' . str_replace('"', '\"', $shipPosition->Ship_Position) . '", category:"Position"}, ';
                }
                echo ']';
             ?>;

            $('[name=voyTime]').timepicker({
                minuteStep: 1,
                showSeconds: false,
                showMeridian: false
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

            $.widget( "custom.catcomplete", $.ui.autocomplete, {
                _renderMenu: function( ul, items ) {
                    var that = this,
                            currentCategory = "";
                    $.each( items, function( index, item ) {
                        if ( item.category != currentCategory ) {
                            ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                            currentCategory = item.category;
                        }
                        that._renderItemData( ul, item );
                    });
                }
            });

            $( "#voyPos-complex" ).catcomplete({
                delay: 0,
                source: position
            });

            $('.select_ctrl').on('click', function(){
                var ship = $('#select_ship').val();
                var voyNo = $('#select_voy').val();
                location.href = 'movement?shipId=' + ship + '&voyNo=' + voyNo;
            });

            $('.excel-btn').on('click', function(){
                var ship = $('#select_ship').val();
                var voyNo = $('#select_voy').val();
                location.href = 'movementExcel?shipId=' + ship + '&voyNo=' + voyNo;
            });

            $('.add-btn').on('click', function () {
                $("[name=voyId]").val('');
                $("[name=voyDate]").val('');
                $("[name=voyTime]").val('00:00');
                $("[name=voyStatus]").chosen('destroy');
                $("[name=voyStatus]").val(0);
                $("[name=voyStatus]").chosen();

                $("[name=voyPos]").val('');
                $("[name=voyQtty]").val('');
                $("[name=voyDistance]").val('');
                $("[name=voyFO]").val('');
                $("[name=voyDO]").val('');
                $("[name=voyLOM]").val('');
                $("[name=voyLOA]").val('');
                $("[name=voyFW]").val('');
                $("[name=voyRemark]").val('');
                $('button[type="submit"]').text('添加');
            });

            /**/
            $('.row_modify_btn').on('click', function(){
                // replace content
                var obj = $(this).closest('tr').children();
                $(this).closest('tr').addClass('selectedRow');
                //obj.css('background-color','#dff0d8');

                $("[name=voyId]").val(obj.eq(0).data('id'));
                var dateStr = String(obj.eq(0).text()).trim().substr(0,10);
                var timeStr = String(obj.eq(0).text()).trim().substr(10);
                $("[name=voyDate]").val(dateStr);
                $("[name=voyTime]").val(timeStr);

                $("[name=voyStatus]").chosen('destroy');
                $("[name=voyStatus]").val(obj.eq(1).data('id'));
                $("[name=voyStatus]").chosen();

                $("[name=voyPos]").val(obj.eq(2).text());
                $("[name=voyQtty]").val(obj.eq(3).data('value'));
                $("[name=voyDistance]").val(obj.eq(4).data('value'));
                $("[name=voyFO]").val(obj.eq(5).text());
                $("[name=voyDO]").val(obj.eq(6).text());
                $("[name=voyLOM]").val(obj.eq(7).text());
                $("[name=voyLOA]").val(obj.eq(8).text());
                $("[name=voyFW]").val(obj.eq(9).text());
                $("[name=voyRemark]").val(obj.eq(10).text());
                $('button[type="submit"]').text('保存');

            });

            // remove a row
            $('.row_trash_btn').on('click', function(){

                var obj = $(this).closest('tr').children();
                var logId = obj.eq(0).data('id');
                var logTime = obj.eq(0).html();
                bootbox.confirm("[" + logTime + "]船舶动态真要删除吗?", function(result) {
                    if(result) {
                        // save in db
                        $.post("removeMovement", {'_token': token, 'logId': logId
                        }, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                var tableBody = document.getElementById('log-table');
                                var rows = tableBody.children;
                                var len = rows.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var td = rows[row].children[0];
                                    var selId = td.getAttribute('data-id');
                                    if (selId == logId)
                                        break;
                                }
                                tableBody.deleteRow(row);
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: '['+ logTime + ']' + ' 船舶动态是已经被删除的。',
                                    class_name: 'gritter-error '
                                });
                            }
                        });
                    }
                });
            });

            $("#voy-log-form").validate({
                rules: {
                    voyDate :"required",
                    voyTime :"required",
                    voyStatus: "required",
                    voyPos : "required",
                },
                messages: {
                    voyDate : "请输入日期。",
                    voyTime : "请输入时间。",
                    voyStatus: "请选择状态。",
                    voyPos : "请选择船舶的位置。",
                }
            });

            @if(isset($error))
                $.gritter.add({
                        title: '错误',
                        text: '{{$error}}',
                        class_name: 'gritter-error'
                    });
            @endif

            $("#select_ship").on('change', function() {
                var shipId = $(this).val();
                if(shipId.length < 1) {
                    $('#select_voy').html('');
                    return;
                }
                $.post('/operation/getVoyList', {'_token':token, 'shipId':shipId}, function(data) {
                    if(data) {
                        var list = jQuery.parseJSON(data);
                        var html = '';
                        for(var i=0; i<list.length; i++) {
                            var voyItem = list[i];
                            html += '<option value="' + voyItem.id + '">' + voyItem.Voy_No + ' | ' + voyItem.CP_No + '</option>';
                        }
                        $('.chosen-select').chosen('destroy');
                        $('#select_voy').html(html);
                        $('.chosen-select').chosen();
                    }
                });
            });
            $("#select_ship_").on('change', function() {
                var shipId = $(this).val();
                if(shipId.length < 1) {
                    $('#select_voy_').html('');
                    return;
                }
                $.post('/operation/getVoyList', {'_token':token, 'shipId':shipId}, function(data) {
                    if(data) {
                        var list = jQuery.parseJSON(data);
                        var html = '';
                        for(var i=0; i<list.length; i++) {
                            var voyItem = list[i];
                            html += '<option value="' + voyItem.id + '">' + voyItem.Voy_No + ' | ' + voyItem.CP_No + '</option>';
                        }
                        $('.chosen-select').chosen('destroy');
                        $('#select_voy_').html(html);
                        $('.chosen-select').chosen();
                    }
                });
            });

        });
    </script>
@endif
@stop
