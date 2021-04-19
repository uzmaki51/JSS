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
                <div class="col-md-6">
                    <h4><b>综合业绩</b>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <div style="float:right">
                        <button class="btn btn-success btn-sm" id="btn-chart" data-year="" data-ship="" style="width: 80px">
                            <i class="icon-signal"></i>统计
                        </button>
                        <a href="yearPlanReportExcel" class="btn btn-warning btn-sm" style="width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></a>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                <div class="table-responsive" id="user_list_table">
@else
    @include('layout.excel-style')
    <div>综合业绩</div>
@endif
                    <table class="table table-striped table-bordered table-hover" id="user-table">
                        <thead>
                        <tr class="black br-hblue">
                            <th rowspan="2">{{transShipOperation("yearPlan.Year")}}</th>
                            <th colspan="3">{{transShipOperation("yearPlan.Profit")}}</th>
                            <th colspan="3">{{transShipOperation("yearPlan.Income")}}</th>
                            <th colspan="3">{{transShipOperation("yearPlan.Expense")}}</th>
@if(!isset($excel))
                            <th rowspan="2" class="center" style="width:100px;line-height:1.3;padding: 0"><?php print_r(transShipOperation("yearPlan.Total by ship")); ?></th>
@endif
                        </tr>
                        <tr class="black br-hblue">
                            <th>{{transShipOperation("yearPlan.Plan")}}</th>
                            <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                            <th>{{transShipOperation("yearPlan.Plan")}}</th>
                            <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                            <th>{{transShipOperation("yearPlan.Plan")}}</th>
                            <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $c1 = $c2 = $p1= $p2 = $d1 = $d2= $ii = $ee = $pp = 0;
                                $selYear = 0; ?>
                        @foreach($allList as $list)
                            <?php
                                $c1 +=   $list->planProfit;
                                $c2 +=   $list->total_profit;
                                $p1 +=   $list->planIncome;
                                $p2 +=   $list->total_income;
                                $d1 +=   $list->planExpense;
                                $d2 +=   $list->total_expense;
                                $selYear = $list->CP_Year;
                            ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" class="btn_year">{{ $list->CP_Year }}</a>
                                </td>
                                <td style="text-align: right">{{ data_print($list->planProfit)}}</td>
                                <td @if($list->total_profit < 0) style="font-weight: bold; color: red;" @endif style="text-align: right">{{ data_print($list->total_profit) }}</td>
                                <td style="text-align: right">{{ round($list->total_profit /$list->planProfit*100) }}</td>
                                <td style="text-align: right">{{ data_print($list->planIncome) }}</td>
                                <td style="text-align: right">{{ data_print($list->total_income) }}</td>
                                <td style="text-align: right">{{ round($list->total_income /$list->planIncome*100) }}</td>
                                <td style="text-align: right">{{ data_print($list->planExpense) }}</td>
                                <td style="text-align: right">{{ data_print($list->total_expense) }}</td>
                                <td style="text-align: right">{{ round($list->total_expense /$list->planExpense*100) }}</td>
@if(!isset($excel))
                                <td class="action-buttons center"><a href="shipYearReport?year={{$list->CP_Year}}"><i class="icon-search bigger-130"></i></a></td>
@endif
                            </tr>
                        @endforeach
                        <tr>
                            <td style="font-weight: bold">{{transShipOperation("yearPlan.TotalSum")}}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($c1) }}</td>
                            <td style="font-weight: bold;text-align: right">{{data_print($c2) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $c1 == 0 ? '' : round(($c2 /$c1)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($p1) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($p2) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($p2 /$p1)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($d1) }}</td>
                            <td style="font-weight: bold;text-align: right">{{data_print($d2) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $d1 == 0 ? '' : round(($d2 /$d1)*100) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">{{transShipOperation("yearPlan.Percent About Income")}}[%]</td>
                            <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($c1/$p1)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $p2 == 0 ? '' : round(($c2/$p2)*100) }}</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($d1/$p1)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $p2 == 0 ? '' : round(($d2/$p2)*100) }}</td>
                            <td></td>
                        </tr>


                        </tbody>
                    </table>
@if(!isset($excel))
                    {{-- dialog of chart view --}}
                    <div id="dialog-report-chart" class="hide">
                        <form class="form-horizontal">
                            <div class="col-md-4" id="container-profit" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                            <div class="col-md-4" id="container-income" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                            <div class="col-md-4" id="container-expense" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                        </form>
                    </div>
@endif
                    {{-- dialogs of report depend on year --}}
                    @foreach($allList as $list)
                        <?php $monthList = $list->monthList; $year = $list->CP_Year; ?>
                        <div class="year-report-table @if($year <> $selYear) hide @endif" id="year_{{ $list->CP_Year }}">
                        <h5>{{ $year }}<?php print_r(transShipOperation("yearPlan.Total by Plan"));?></h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th rowspan="2">{{transShipOperation("yearPlan.Month")}}</th>
                                <th colspan="3">{{transShipOperation("yearPlan.Profit")}}</th>
                                <th colspan="3">{{transShipOperation("yearPlan.Income")}}</th>
                                <th colspan="3">{{transShipOperation("yearPlan.Expense")}}</th>
                            </tr>
                            <tr class="black br-hblue">
                                <th>{{transShipOperation("yearPlan.Plan")}}</th>
                                <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                                <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                                <th>{{transShipOperation("yearPlan.Plan")}}</th>
                                <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                                <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                                <th>{{transShipOperation("yearPlan.Plan")}}</th>
                                <th>{{transShipOperation("yearPlan.Actual results")}}</th>
                                <th>{{transShipOperation("yearPlan.Achive")}}[%]</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $c1 = $c2 = $p1= $p2 = $d1 = $d2= $ii = $ee = $pp = 0;$selYear = 0; $monthIndex = 0; ?>
                            @for($month = 1; $month < 13; $month++)
                                <tr>
                                    <?php if(isset($monthList[$monthIndex])) $item = $monthList[$monthIndex] ?>
                                    @if($item->CP_Month == $month)
                                        <?php
                                            $c1 +=   $item->planProfit;
                                            $c2 +=   $item->total_profit;
                                            $p1 +=   $item->planIncome;
                                            $p2 +=   $item->total_income;
                                            $d1 +=   $item->planExpense;
                                            $d2 +=   $item->total_expense;
                                            $selMonth = $item->CP_Month;
                                            $monthIndex++;  ?>
                                            <td>{{ $item->CP_Month }}</td>
                                            <td style="text-align: right">{{ data_print($item->planProfit)}}</td>
                                            <td @if($item->total_profit < 0) style="font-weight: bold; color: red;text-align: right" @endif style="text-align: right">{{ data_print($item->total_profit) }}</td>
                                            <td style="text-align: right">{{ $item->planProfit == 0 ? '' : round($item->total_profit /$item->planProfit*100) }}</td>
                                            <td style="text-align: right">{{ data_print($item->planIncome) }}</td>
                                            <td style="text-align: right">{{ data_print($item->total_income) }}</td>
                                            <td style="text-align: right">{{ $item->planIncome == 0 ? '' : round($item->total_income /$item->planIncome*100) }}</td>
                                            <td style="text-align: right">{{ data_print($item->planExpense) }}</td>
                                            <td style="text-align: right">{{ data_print($item->total_expense) }}</td>
                                            <td style="text-align: right">{{ $item->planExpense == 0 ? '' : round($item->total_expense /$item->planExpense*100) }}</td>
                                    @else
                                            <td>{{ $month }}</td>
                                            <td style="text-align: right"></td>
                                            <td></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                            <td style="text-align: right"></td>
                                    @endif
                                </tr>
                            @endfor
                            <tr>
                                <td style="font-weight: bold">{{transShipOperation("yearPlan.TotalSum")}}</td>
                                <td style="font-weight: bold;text-align: right">{{ data_print($c1) }}</td>
                                <td style="font-weight: bold;text-align: right">{{data_print($c2) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ $c1 == 0 ? '' : round(($c2 /$c1)*100) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ data_print($p1) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ data_print($p2) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($p2 /$p1)*100) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ data_print($d1) }}</td>
                                <td style="font-weight: bold;text-align: right">{{data_print($d2) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ $d1 == 0 ? '' : round(($d2 /$d1)*100) }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;text-align: center">{{transShipOperation("yearPlan.Percent About Income")}}[%]</td>
                                <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($c1/$p1)*100) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ $p2 == 0 ? '' : round(($c2/$p2)*100) }}</td>
                                <td></td>
                                <td style="font-weight: bold;text-align: right">100</td>
                                <td style="font-weight: bold;text-align: right">100</td>
                                <td></td>
                                <td style="font-weight: bold;text-align: right">{{ $p1 == 0 ? '' : round(($d1/$p1)*100) }}</td>
                                <td style="font-weight: bold;text-align: right">{{ $p2 == 0 ? '' : round(($d2/$p2)*100) }}</td>
                                <td></td>
                            </tr>


                            </tbody>
                        </table>
                        </div>
                    @endforeach
@if(!isset($excel))
                </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('/assets/js/highcharts.js') }}"></script>
    <?php
    $profitList = $incomeList = $expenseList = array();
    foreach($allList as $list){
        array_push($profitList, array('name'=>$list->CP_Year, 'data'=>array($list->planProfit,(float)$list->total_profit)));
        array_push($incomeList, array('name'=>$list->CP_Year, 'data'=>array($list->planIncome,(float)$list->total_income)));
        array_push($expenseList, array('name'=>$list->CP_Year,'data'=>array($list->planExpense,(float)$list->total_expense)));
    }
    ?>

    <script>

    var profitList = <?php echo json_encode($profitList); ?>;
    var incomeList = <?php echo json_encode($incomeList); ?>;
    var expenseList = <?php echo json_encode($expenseList); ?>;

    jQuery(function(e){

        // open dialog of report depend on year
        $(".btn_year").on('click', function (e) {

            e.preventDefault();

            var year = $(this).text();
            if($("#year_" + year).hasClass('hide')) {
                $('.year-report-table').addClass('hide');
                $("#year_" + year).removeClass('hide');
            }
        });

        // open dialog of report chart view
        $("#btn-chart").on('click', function (e) {

            e.preventDefault();

            var dialog = $("#dialog-report-chart").removeClass('hide').dialog({
                modal: true,
                title: "业绩图表",
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
            $('#container-profit').highcharts({
                chart: {
                    type: 'column'
                },
                credits:{
                    enabled:false
                },
                title: {
                    text: '利益'
                },
                xAxis: {
                    categories: [
                        '计划',
                        '业绩'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} $</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: profitList
            });
            $('#container-income').highcharts({
                chart: {
                    type: 'column'
                },
                credits:{
                    enabled:false
                },
                title: {
                    text: '存款'
                },
                xAxis: {
                    categories: [
                        '计划',
                        '业绩'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} $</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: incomeList
            });
            $('#container-expense').highcharts({
                chart: {
                    type: 'column'
                },
                credits:{
                    enabled:false
                },
                title: {
                    text: '支出'
                },
                xAxis: {
                    categories: [
                        '计划',
                        '业绩'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} $</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: expenseList
            });

        });


    });
</script>
@endif
@stop

