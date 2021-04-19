<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'header';
?>
@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-9">
                    <h5>
                        <select name="selectYears" class="selectYearCtrl">
                            @foreach($yearList as $year)
                                <option value="{{ $year->Yearly }}"  <?php echo $year->Yearly == $currentYear ? 'selected' : ''; ?>>{{ $year->Yearly }}</option>
                            @endforeach
                        </select>
                        <b>年</b>&nbsp;&nbsp;
                        <?php $shipName = ''; ?>
                        <select name="selectShip" class="selectShipCtrl">
                            @foreach($shipList as $shipNo)
                                <option value="{{ $shipNo->RegNo }}"  <?php echo $shipNo->RegNo == $ship ? 'selected' : ''; ?>>{{ $shipNo->shipName_En }} | {{ $shipNo->shipName_Cn }}</option>
                                <?php if($shipNo->RegNo == $ship) $shipName = $shipNo->shipName_Cn;?>
                            @endforeach
                        </select>&nbsp;&nbsp;
                        <b>安航次业绩</b> &nbsp;&nbsp;
                        {{--航次:
                        <select name="selectShips" id="">
                            <option value="">12</option>
                        </select>
                        ~
                        <select name="selectShips" id="">
                            <option value="">24</option>
                        </select>--}}
                    </h5>
                </div>
                <div class="col-sm-3">
                    <div style="float:right">
                        <button class="btn btn-success btn-sm" id="btn-chart" data-year="{{$currentYear}}" data-ship="{{$shipName}}" style="width: 80px">
                            <i class="icon-signal"></i>统计
                        </button>
                        <button class="btn btn-warning btn-sm btn_excel" style="width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="overflow-x: scroll;">
                <div class="row">
@else
    @include('layout.excel-style')
@endif
                    <table class="table table-striped table-bordered table-hover" style="width:1600px;">
                        <thead>
                        <tr class="black br-hblue">
                            <th rowspan="2">{{transShipOperation("shipCountReport.VOY")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Period")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.L/P")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.D/P")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Cargo")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Frt")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.B/L")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Mile")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Duration")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Profit")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.RealProfit")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Income")}}</th>
                            <th rowspan="2">{{transShipOperation("shipCountReport.Expense")}}</th>
                            <th colspan="7">{{transShipOperation("shipCountReport.Main Expense Iiem")}}</th>
                        </tr>
                        <tr class="black br-hblue">
                            <th>{{transShipOperation("shipCountReport.PD")}}</th>
                            <th>{{transShipOperation("shipCountReport.FO")}}</th>
                            <th>{{transShipOperation("shipCountReport.DO")}}</th>
                            <th>{{transShipOperation("shipCountReport.LO")}}</th>
                            <th>{{transShipOperation("shipCountReport.S&S")}}</th>
                            <th>{{transShipOperation("shipCountReport.CTM")}}</th>
                            <th>{{transShipOperation("shipCountReport.Other")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                                $sumFrt = $sumBL = $sumMile = $sumDuration = 0;
                                $sumProfit = $sumIncome = $sumExpense = $sumRealProfit = 0;
                                $sumPD = $sumFO = $sumDO =$sumLO = $sumFW =$sumSS =$sumCTM =$sumOther = 0;

                                $voyList = $incomeList = $expenseList = $profitList = $realProfitList = array();
                                $index = 0;
                                $voyCount = !empty($list) ? count($list) : 0;
                        ?>
                        @foreach($list as $voy)
                            <tr>
                                <?php $sum_other = $voy->total_expense - $voy->sum_pd - $voy->sum_fo - $voy->sum_do - $voy->sum_lo
                                        - $voy->sum_ctm - $voy->sum_ss;
                                      $profit = $voy->total_income - $voy->total_expense;  ?>
                                <td>{{ $voy->Voy_No }}</td><?php array_push($voyList, $voy->Voy_No); ?>
                                <td style="text-align: center">{{ convert_date($voy->StartDate).' ~ '.convert_date($voy->LastDate) }}</td>
                                <td style="text-align: center">{{ \App\Http\Controllers\Util::getPortName_Cn($voy->LPort) }}</td>
                                <td style="text-align: center">{{ \App\Http\Controllers\Util::getPortName_Cn($voy->DPort) }}</td>
                                <td style="text-align: center">{{ \App\Models\Operations\YearlyQuarterMonthPlan::carGoName($voy->Cargo) }}</td>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->Freight)}}</td><?php $sumFrt += $voy->Freight; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->B_L) }}</td><?php $sumBL += $voy->B_L; ?>
                                <td style="text-align: center">{{ $voy->SailDistance }}</td>   <?php $sumMile += $voy->SailDistance; ?>
                                <td style="text-align: center">{{ $voy->dateInteval }}</td> <?php $sumDuration += $voy->dateInteval; ?>
                                <td style="text-align: right;@if($profit<0) color:red;@endif">{{ \App\Http\Controllers\Util::getNumberFt($profit) }}</td> <?php $sumProfit += $profit; array_push($profitList, (float)$profit); $index++; ?>
                                <td style="text-align: right;@if($voy->Profit<0) color:red;@endif">{{ \App\Http\Controllers\Util::getNumberFt($voy->Profit) }}</td> <?php $sumRealProfit += $voy->Profit; array_push($realProfitList, (float)$voy->Profit); ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->total_income) }}</td> <?php $sumIncome += $voy->total_income; array_push($incomeList, (float)$voy->total_income); ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->total_expense) }}</td> <?php $sumExpense += $voy->total_expense; array_push($expenseList, (float)$voy->total_expense); ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_pd) }}</td><?php $sumPD+=$voy->sum_pd; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_fo) }}</td><?php $sumFO+=$voy->sum_fo; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_do) }}</td><?php $sumDO+=$voy->sum_do; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_lo) }}</td><?php $sumLO+=$voy->sum_lo; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_ss) }}</td><?php $sumSS+=$voy->sum_ss; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($voy->sum_ctm) }}</td><?php $sumCTM+=$voy->sum_ctm; ?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFt($sum_other) }}</td><?php $sumOther+=$sum_other; ?>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: center;font-weight: bold">{{transShipOperation("shipCountReport.Yearly voys")}}: {{$index}}{{transShipOperation("shipCountReport.Voy")}}</td>
                            <td colspan="3" style="text-align: center;font-weight: bold"> {{transShipOperation("shipCountReport.Total")}}</td>
                            <td style="text-align: right;font-weight: bold">@if($index == 0) {{ number_format($sumFrt) }} @else {{ number_format($sumFrt / $index) }} @endif</td>
                            <td style="text-align: right;font-weight: bold">@if($index == 0) {{ number_format($sumBL) }} @else {{ number_format($sumBL / $index) }}@endif</td>
                            <td style="text-align: center;font-weight: bold">{{ number_format($sumMile) }}</td>
                            <td style="text-align: center;font-weight: bold">{{ number_format($sumDuration) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumProfit, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumRealProfit, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumIncome, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumExpense, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumPD, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumFO, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumDO, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumLO, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumSS, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumCTM, 2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ number_format($sumOther, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align: center;font-weight: bold">{{transShipOperation("shipCountReport.Percent About Income")}} %</td>
                            @if($sumIncome != 0)
                            <td style="text-align: center;font-weight: bold">{{ Round(($sumProfit/$sumIncome)*100,2) }}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumRealProfit/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">100</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumExpense/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumPD/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumFO/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumDO/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumLO/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumSS/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumCTM/$sumIncome)*100,2)}}</td>
                            <td style="text-align: right;font-weight: bold">{{ Round(($sumOther/$sumIncome)*100,2)}}</td>
                                @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                                @endif
                        </tr>
                        </tfoot>
                    </table>
@if(!isset($excel))
                    {{-- dialog of chart view --}}
                    <div id="dialog-report-chart" class="hide">
                        <form class="form-horizontal">
                            <div class="" id="container-bar" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('/assets/js/highcharts.js') }}"></script>
<?php
$lineChartData = array(
        array('name'=>'利益','data'=>$profitList),
        array('name'=>'收入','data'=>$incomeList),
        array('name'=>'支出','data'=>$expenseList),
        array('name'=>'纯利益','data'=>$realProfitList),
);

?>
<script>

    jQuery(function(e){

        var lineData = <?php echo json_encode($lineChartData); ?>;
        var voyList = <?php echo json_encode($voyList); ?>;
        $('.selectYearCtrl').on('change', function(){
            var year = $(this).val();
            var shipId = $('.selectShipCtrl').val();
            location.href = "shipCountReport?year=" + year + "&shipId=" + shipId;
        });
        $('.selectShipCtrl').on('change', function(){
            var shipId = $(this).val();
            var year = $('.selectYearCtrl').val();
            location.href = "shipCountReport?year=" + year + "&shipId=" + shipId;
        });
        $('.btn_excel').on('click', function() {
            var year = $('.selectYearCtrl').val();
            var shipId = $('.selectShipCtrl').val();
            location.href = 'shipCountReportExcel?shipId=' + shipId + '&year=' + year;
        });

        // open dialog of report chart view
        $("#btn-chart").on('click', function (e) {

            e.preventDefault();

            var year = $(this).data('year');
            var ship = $(this).data('ship');

            var dialog = $("#dialog-report-chart").removeClass('hide').dialog({
                modal: true,
                title: year + "年 " + ship + "号 按航次业绩图表",
                title_html: true,
                width:1024,
                buttons: [
                    {
                        text: "关闭",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ]
            });

            // show chart views
            $('#container-bar').highcharts({
                credits:{
                    enabled:false
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: voyList
                },
                yAxis: {
                    title: {
                        text: '金额($)'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: '$'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: lineData
            });


        });


    });
</script>
@endif
@stop