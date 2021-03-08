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
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h5>
                        <b>항차일수분석</b>
                    </h5>
                </div>
                @if(isset($shipName['name']))
                    <div class="col-md-6 alert alert-block center" style="font-size: 16px">
                        <strong>《&nbsp;{{$shipName['name']}}({{$shipName['shipName_Cn']}})&nbsp;》호&nbsp;({{ $shipName['shipName_En'] }}) 항차일수분석 </strong>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8 form-horizontal">
                        <div class="col-md-6">
                            <label class="control-label no-padding-right" style="float: left;"><?php print_r(transShipOperation("analysis.ShipName1"));?>:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="shipName">
                                    @foreach($shipList as $ship)
                                        @if(!$isHolder)
                                        <option value="{{$ship['RegNo']}}"
                                                @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_En']}} | {{$ship['shipName_Cn']}}
                                        </option>
                                        @elseif($isHolder)
                                            @if(in_array($ship->shipID, $ships))
                                                <option value="{{$ship['RegNo']}}"
                                                        @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_En']}} | {{$ship['shipName_Cn']}}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label no-padding-right" style="float: left;">{{transShipOperation("analysis.Voy No")}}:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="firstVoy">
                                    <option value="" @if(is_null($firstVoy)) selected @endif></option>
                                    @foreach($voyList as $voy)
                                        <option value="{{$voy['id']}}"
                                                @if(isset($firstVoy) && ($firstVoy == $voy['id'])) selected @endif>{{$voy['Voy_No']}} | {{$voy['CP_No']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label no-padding-right" style="float: left;">~</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="endVoy">
                                    <option value="" @if(is_null($endVoy)) selected @endif></option>
                                    @foreach($voyList as $voy)
                                        <option value="{{$voy['id']}}"
                                                @if(isset($endVoy) && ($endVoy == $voy['id'])) selected @endif>{{$voy['Voy_No']}} | {{$voy['CP_No']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm btn_search" style="float: left;width :80px"><i class="icon-search"></i>검색</button>
                    <button class="btn btn-warning btn-sm btn_excel" style="float: left;margin-left: 5px; width :80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                </div>
                <div class="space-4"></div>
                <div class="row">
                    <div style="overflow-x: scroll;;width:100%">
                        <div style="overflow-y: scroll;" id="header_view">
                            @else
                                @include('layout.excel-style')
                            @endif
                            <?php
                            $economyCount = 0;
                            $uneconomyCount = 0;
                            $otherCount = 0;
                            foreach ($typeList as $type) {
                                if($type['Type'] == 1)
                                    $economyCount = $type['typeCount'];
                                else if($type['Type'] == 2)
                                    $uneconomyCount = $type['typeCount'];
                                else
                                    $otherCount = $type['typeCount'];
                            }
                            ?>
                            <table class="table table-bordered table-hover arc-std-table">
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center" rowspan="2" style="width:3%">{{transShipOperation("analysis.No")}}</th>
                                    <th class="center" rowspan="2" style="width:3.3%"><?php print_r(transShipOperation("analysis.ShipName"));?></th>
                                    <th class="center" colspan="5" style="line-height: 0.9">{{transShipOperation("analysis.Voy_Relative")}}</th>
                                    <th class="center" rowspan="2" style="width:3%">{{transShipOperation("analysis.Duration")}}</th>
                                    <th class="center" colspan="{{$economyCount+1}}" style="line-height: 0.9;">{{transShipOperation("analysis.Economic Day")}}</th>
                                    <th class="center" colspan="{{$uneconomyCount+2}}" style="line-height: 0.9;">{{transShipOperation("analysis.UnEconomic Day")}}</th>
                                    <th class="center" colspan="{{$otherCount}}" style="line-height: 0.9;">{{transShipOperation("analysis.Other")}}</th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th class="center" style="line-height: 1.0;width:3.9%">{{transShipOperation("analysis.Voy No")}}</th>
                                    <th class="center" style="width:13.5%">{{transShipOperation("analysis.Voy Period")}}</th>
                                    <th class="center" style="width:9.1%">{{transShipOperation("analysis.Lp")}}</th>
                                    <th class="center" style="width:8.3%">{{transShipOperation("analysis.Dp")}}</th>
                                    <th class="center" style="width:3.6%">{{transShipOperation("analysis.Sail Distance")}}</th>
                                    <th class="center" style="width:3%">{{transShipOperation("analysis.Total")}}</th>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 1)
                                            <th class="center" style="width:3%">{{transShipOperation("analysis.{$event['ItemName']}")}}</th>
                                        @endif
                                    @endforeach
                                    <th class="center" style="width: 3%">{{transShipOperation("analysis.Total")}}</th>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 2)
                                            <th class="center" style="width:3%">{{transShipOperation("analysis.{$event['ItemName']}")}}</th>
                                        @endif
                                    @endforeach
                                    <th class="center" style="width:3%">{{transShipOperation("analysis.Other")}}</th>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 0)
                                            <th class="center" style="width:3%">{{transShipOperation("analysis.{$event['ItemName']}")}}</th>
                                        @endif
                                    @endforeach
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div style="overflow-y: scroll;height: 550px;" id="alanyseTable">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                <?php $index = ($page - 1) * 15 + 1; ?>
                                @foreach($list as $voy)
                                    <tr>
                                        <td class="center" style="width:3%">{{$index++}}</td>
                                        <td class="center" style="width:3.5%">{{$voy['shipName']}}</td>
                                        <td class="center" style="width:3.9%">{{$voy['voyNo']}}</td>
                                        <td class="center" style="width:13.5%">{{convert_date($voy['startDate'])}}~{{convert_date($voy['endDate'])}}</td>
                                        <td class="center" style="width:9.1%">{{$voy['L_Port']}}</td>
                                        <td class="center" style="width:8.3%">{{$voy['D_Port']}}</td>
                                        <?php ?>
                                        <td class="celldata center" style="width:3.6%">@if($voy['distance'] != 0){{$voy['distance']}}@endif</td>
                                        <td class="celldata center" style="width:3%;font-weight: bold;">{{$voy['sailTime']}}</td>
                                        <?php $sum_economy = 0;
                                        foreach ($eventList as $event)
                                        if($event['Type'] == 1)
                                        $sum_economy += $voy[$event['Event']];
                                        ?>
                                        <td class="celldata center" style="width:3%;color: #0b66c1;font-weight: bold;">{{$sum_economy}}</td>
                                        @foreach($eventList as $event)
                                            @if($event['Type'] == 1)
                                                <td class="celldata center" style="width:3%;color: #0b66c1;@if($voy[$event['Event']]<0){{'background-color: lightpink'}}@endif">{{\App\Http\Controllers\Util::getRoundFt($voy[$event['Event']])}}</td>
                                            @endif
                                        @endforeach
                                        <?php $sum_uneconomy = 0;
                                        foreach ($eventList as $event)
                                        if($event['Type'] == 2)
                                        $sum_uneconomy += $voy[$event['Event']];
                                        ?>
                                        <td class="celldata center" style="width:3%;color: #804b1f;font-weight: bold;">{{\App\Http\Controllers\Util::getRoundFt($sum_uneconomy)}}</td>
                                        @foreach($eventList as $event)
                                            @if($event['Type'] == 2)
                                                <td class="celldata center" style="width:3%;color: #801c18;@if($voy[$event['Event']]<0){{'background-color: lightpink'}}@endif">{{\App\Http\Controllers\Util::getRoundFt($voy[$event['Event']])}}</td>
                                            @endif
                                        @endforeach
                                        <?php
                                        $sumOther = 0;
                                        foreach($eventList as $event) {
                                        if($event['Type'] == 0)
                                        $sumOther += $voy[$event['Event']];
                                        }
                                        $un_economy_other = $voy['sailTime'] - $sum_economy - $sum_uneconomy - $sumOther;
                                        ?>
                                        <td class="celldata center" style="width:3%;color: #801c18;@if($un_economy_other<0){{'background-color: lightpink'}}@endif">{{\App\Http\Controllers\Util::getRoundFt($un_economy_other, 2)}}</td>
                                        @foreach($eventList as $event)
                                            @if($event['Type'] == 0)
                                                <td class="celldata center" style="width:3%;color: green;">{{\App\Http\Controllers\Util::getRoundFt($voy[$event['Event']])}}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr id="sum_tr">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center;font-weight: bold;">{{transShipOperation("analysis.Total Voys")}}: {{$index - 1}}</td>
                                    <td></td>
                                    <td style="text-align: center;font-weight: bold;">{{transShipOperation("analysis.SumTotal")}}</td>
                                    <?php $index = 0; ?>
                                    <td class="center" id="total{{$index++}}" style="font-weight: bold;"></td>
                                    <td class="center" id="total{{$index++}}" style="font-weight: bold;"></td>
                                    <td class="center" id="total{{$index++}}" style="font-weight: bold;color: #0b66c1;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 1)
                                            <td class="center" id="total{{$index++}}" style="font-weight: bold;color: #0b66c1;"></td>
                                        @endif
                                    @endforeach
                                    <td class="center" id="total{{$index++}}" style="font-weight: bold;color: #804b1f;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 2)
                                            <td class="center" id="total{{$index++}}" style="font-weight: bold;color: #804b1f;"></td>
                                        @endif
                                    @endforeach
                                    <td class="center" id="total{{$index++}}" style="font-weight: bold;color: #804b1f;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 0)
                                            <td class="center" id="total{{$index++}}" style="font-weight: bold;color: green;"></td>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr id="persent_tr">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center;font-weight: bold;">{{transShipOperation("analysis.About Duration")}}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="center" style="font-weight: bold;"></td>
                                    <td class="center" style="font-weight: bold;">100%</td>
                                    <td class="center" style="font-weight: bold;color: #0b66c1;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 1)
                                            <td class="center" style="font-weight: bold;color: #0b66c1;"></td>
                                        @endif
                                    @endforeach
                                    <td class="center" style="font-weight: bold;color: #804b1f;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 2)
                                            <td class="center" style="font-weight: bold;color: #804b1f;"></td>
                                        @endif
                                    @endforeach
                                    <td class="center" style="font-weight: bold;color: #804b1f;"></td>
                                    @foreach($eventList as $event)
                                        @if($event['Type'] == 0)
                                            <td class="center" style="font-weight: bold;color: green;"></td>
                                        @endif
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
@if(!isset($excel))
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var pageNum = '{{$page}}' * 1;
        var token = '{!! csrf_token() !!}';
        var tdCount = '<?php echo ($economyCount + $uneconomyCount + $otherCount + 5); ?>' * 1;
        var nonTdCount = '<?php echo $uneconomyCount; ?>' * 1;

        $(document).ready(function () {
            var width = $('#alanyseTable').width();
            if(width < 1600) {
                $('#alanyseTable').css('width', '1600px');
                $('#header_view').css('width', '1600px');
            }

            getTotal();

            $('#shipName').on('change', function() {
                var shipId = $(this).val();
                if(shipId.length < 1) {
                    $('#firstVoy').html('');
                    $('#endVoy').html();
                    return;
                }

                $.post('/operation/getVoyList', {'_token':token, 'shipId':shipId}, function(data) {
                    if(data) {
                        var list = jQuery.parseJSON(data);
                        var html = '<option value=""></option>';
                        for(var i=0; i<list.length; i++) {
                            var voyItem = list[i];
                            html += '<option value="' + voyItem.id + '">' + voyItem.Voy_No + ' | ' + voyItem.CP_No + '</option>';
                        }

                        $('#firstVoy').html(html);
                        $('#endVoy').html(html);
                    }
                });
            });


            $('.btn_search').on('click', function () {
                pageReload(1);
            });

            $('.btn_excel').on('click', function() {
                var shipId = $('#shipName').val();
                var firstVoy = $('#firstVoy').val() * 1;
                var endVoy = $('#endVoy').val() * 1;
                var param = '?shipId=' + shipId;
                if((firstVoy > 0) && (endVoy > 0) && ((endVoy - firstVoy) < 0)) {
                    $.gritter.add({
                        title: '오유',
                        text: '항차번호구간을 바로 설정하십시오.',
                        class_name: 'gritter-error '
                    });
                    return;
                }
                if((firstVoy > 0) || (endVoy > 0))
                    param += '&first=' + firstVoy + '&end=' + endVoy;
                location.href = 'shipVoyAnalysisExcel' + param;
            });

            $('.prev').on('click', function () {
                pageNum--;
                pageReload(pageNum);
            });

            $('.next').on('click', function () {
                pageNum++;
                pageReload(pageNum);
            });
            $('.page').on('click', function () {
                pageNum = $(this).html();
                pageReload(pageNum);
            });
        });

        function pageReload($page) {
            var shipId = $('#shipName').val();
            var firstVoy = $('#firstVoy').val() * 1;
            var endVoy = $('#endVoy').val() * 1;
            var param = '?shipId=' + shipId;
            if((firstVoy > 0) && (endVoy > 0) && ((endVoy - firstVoy) < 0)) {
                $.gritter.add({
                    title: '오유',
                    text: '항차번호구간을 바로 설정하십시오.',
                    class_name: 'gritter-error '
                });
                return;
            }
            if((firstVoy > 0) || (endVoy > 0))
                param += '&first=' + firstVoy + '&end=' + endVoy;
            if($page > 1)
                param += '&page=' + $page;
            location.href = 'shipVoyAnalysis' + param;
        }

        function getTotal() {
            var celldata = $(".celldata");
            var sumValue = Array();
            for (var i = 0; i < celldata.length; i++) {
                if (i < tdCount) sumValue[i % tdCount] = 0;
                sumValue[i % tdCount] += celldata[i].textContent == '' ? 0: parseFloat(celldata[i].textContent);
                var value = 0;
                if(sumValue[i % tdCount]) {
                    var floatValue = Math.round((sumValue[i % 17] - Math.floor(sumValue[i % 17])) * 100);
                    value = String(Math.floor(sumValue[i % 17])) + '.' + String(floatValue);
                }
                $('#total' + (i % 17)).html(value == 0? '': Math.round(value));
            }
            calculatePersent();
        }

        function calculatePersent() {
            var trObj = $('#sum_tr').children();
            var persent_row = $('#persent_tr').children();
            var total = trObj.eq(7).text() * 1;
            var navTime = trObj.eq(8).text() * 1;

            persent_row.eq(8).html( Math.round(navTime / total * 100) + '%');
            persent_row.eq(9).html( Math.round((trObj.eq(9).text() * 1) / navTime * 100) + '%');
            persent_row.eq(10).html( Math.round((trObj.eq(10).text() * 1) / navTime * 100) + '%');
            persent_row.eq(11).html( Math.round((trObj.eq(11).text() * 1) / navTime * 100) + '%');

            persent_row.eq(12).html( Math.round((trObj.eq(12).text() * 1) / total * 100) + '%');
            var unEco = trObj.eq(12).text() * 1;
            for(var i=0; i<nonTdCount; i++) {
                persent_row.eq(13+i).html( Math.round((trObj.eq(13+i).text() * 1) / unEco * 100) + '%');
            }
            persent_row.eq(13+nonTdCount).html( Math.round((trObj.eq(13+nonTdCount).text() * 1) / unEco * 100) + '%');
            persent_row.eq(14+nonTdCount).html( Math.round((trObj.eq(14+nonTdCount).text() * 1) / total * 100) + '%');

        }

    </script>
@endif
@endsection