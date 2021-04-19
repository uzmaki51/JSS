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
                    <h5><select name="selectYears" class="selectYearCtrl">
                            @foreach($yearList as $year)
                        <option value="{{ $year->Yearly }}"  <?php echo $year->Yearly == $currentYear ? 'selected' : ''; ?>>{{ $year->Yearly }}</option>
                                @endforeach
                    </select>
                    <b>综合按船的年业绩</b> </h5>
                </div>
                <div class="col-sm-6">
                    <div style="float:right">
                        <button class="btn btn-success btn-sm" id="btn-chart" data-year="{{ $currentYear }}" data-ship="" style="width: 80px">
                            <i class="icon-signal"></i>统计
                        </button>
                        <button class="btn btn-warning btn-sm excel-btn" style="width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
@else
    @include('layout.excel-style')
@endif
                    <table class="table table-striped table-bordered table-hover" id="user-table">
                        <thead>
                        <tr class="black br-hblue">
                            <th rowspan="2">{{transShipOperation("ShipyearPlan.ShipName")}}</th>
                            <th colspan="3">{{transShipOperation("ShipyearPlan.Profit")}}</th>
                            <th colspan="3">{{transShipOperation("ShipyearPlan.Income")}}</th>
                            <th colspan="3">{{transShipOperation("ShipyearPlan.Expense")}}</th>
                            <th colspan="10">{{transShipOperation("ShipyearPlan.Main Expense Iiem")}}</th>
                        </tr>
                        <tr class="black br-hblue">
                            <th>{{transShipOperation("ShipyearPlan.Plan")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Achive")}}[%]</th>
                            <th>{{transShipOperation("ShipyearPlan.Plan")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Achive")}}[%]</th>
                            <th>{{transShipOperation("ShipyearPlan.Plan")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Actual results")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Achive")}}[%]</th>
                            <th>{{transShipOperation("ShipyearPlan.PD")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.FO")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.DO")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.LO")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.S&S")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.CTM")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.Other")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.M")}}</th>
                            <th>{{transShipOperation("ShipyearPlan.V")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sumPlanProfit=$sumYearlyProfit=$sumPlanIncome=$sumYearlyIncome=$sumPlanExpense=$sumYearlyExpense=$sumYearlyPD=$sumYearlyFO=$sumYearlyDO=$sumYearlyLO=$sumYearlyFW=$sumYearlySS=$sumYearlyCTM=$sumYearlyOTHERS=0;
                        $planProfit = $pracProfit = $planIncome = $pracIncome = $planExpense = $pracExpense = array();
                        $yearList = array();
                        ?>
                        @foreach($lists as $list)
                            <?php
                            $profit = $list->YearlyIncome - $list->YearlyExpense;
                            $other = $list->YearlyExpense - $list->YearlyPD - $list->YearlyFO - $list->YearlyDO - $list->YearlyLO - $list->YearlySS - $list->YearlyCTM; ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" class="btn_year" data-year="2016">{{ $list->shipName_Cn }}</a>
                                </td>
                                <td style="text-align: right">{{ data_print($list->PlanProfit) }}</td><?php $sumPlanProfit+=$list->PlanProfit;  array_push($planProfit, $list->PlanProfit); ?>
                                <td style="text-align: right">{{ data_print($list->YearlyProfit) }}</td><?php $sumYearlyProfit += $profit; array_push($pracProfit, 2); ?>
                                <td style="text-align: right">{{ $list->PlanProfit == 0 ? '' : round(($list->YearlyProfit/$list->PlanProfit)*100)}}</td>
                                <td style="text-align: right">{{ data_print($list->PlanIncome) }}</td><?php $sumPlanIncome+=$list->PlanIncome; array_push($planIncome, $list->PlanIncome);?>
                                <td style="text-align: right">{{ data_print($list->YearlyIncome) }}</td><?php $sumYearlyIncome+=$list->YearlyIncome; array_push($pracIncome, $list->YearlyIncome);?>
                                <td style="text-align: right">{{ $list->PlanIncome == 0 ? '' : round(($list->YearlyIncome/$list->PlanIncome)*100)}}</td>
                                <td style="text-align: right">{{ data_print($list->PlanExpense) }}</td><?php $sumPlanExpense+=$list->PlanExpense; array_push($planExpense, (float)$list->PlanExpense); ?>
                                <td style="text-align: right">{{ data_print($list->YearlyExpense) }}</td><?php $sumYearlyExpense+=$list->YearlyExpense; array_push($pracExpense, $list->YearlyExpense);?>
                                <td style="text-align: right">{{ $list->PlanExpense == 0 ? '' : round(($list->YearlyExpense/$list->PlanExpense)*100)}}</td>
                                <td style="text-align: right">{{ data_print($list->YearlyPD) }}</td><?php $sumYearlyPD += $list->YearlyPD; ?>
                                <td style="text-align: right">{{ data_print($list->YearlyFO) }}</td><?php $sumYearlyFO += $list->YearlyFO; ?>
                                <td style="text-align: right">{{ data_print($list->YearlyDO) }}</td><?php $sumYearlyDO += $list->YearlyDO; ?>
                                <td style="text-align: right">{{ data_print($list->YearlyLO) }}</td><?php $sumYearlyLO += $list->YearlyLO; ?>
                                <td style="text-align: right">{{ data_print($list->YearlySS) }}</td><?php $sumYearlySS += $list->YearlySS; ?>
                                <td style="text-align: right">{{ data_print($list->YearlyCTM) }}</td><?php $sumYearlyCTM += $list->YearlyCTM; ?>
                                <td style="text-align: right">{{ data_print($other) }}</td><?php $sumYearlyOTHERS += $other; ?>
                                <td class="action-buttons center"><a href="shipMonthReport?year={{$currentYear}}&shipId={{$list->ShipID}}"><i class="icon-search bigger-110"></i></a></td>
                                <td class="action-buttons center"><a href="shipCountReport?year={{$currentYear}}&shipId={{$list->ShipID}}"><i class="icon-search bigger-110"></i></a></td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td style="font-weight: bold;text-align: center">{{transShipOperation("ShipyearPlan.TotalSum")}}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanProfit) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyProfit) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanProfit == 0 ? '' : round(($sumYearlyProfit/$sumPlanProfit)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanIncome) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyIncome) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumYearlyIncome/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanExpense) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyExpense) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanExpense == 0 ? '' : round(($sumYearlyExpense/$sumPlanExpense)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyPD) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyFO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyDO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyLO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlySS) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyCTM) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyOTHERS) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;text-align: center">{{transShipOperation("ShipyearPlan.Percent About Income")}}[%]</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumPlanProfit/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyProfit/$sumYearlyIncome)*100) }}</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumPlanExpense/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyExpense/$sumYearlyIncome)*100) }}</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyPD/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyFO/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyDO/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyLO/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlySS/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyCTM/$sumYearlyIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyIncome == 0 ? '' : round(($sumYearlyOTHERS/$sumYearlyIncome)*100) }}</td>

                        </tr>
                        </tfoot>
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
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('/assets/js/highcharts.js') }}"></script>
    <?php
    $profitList = $incomeList = $expenseList = array();
    foreach($lists as $list){
        array_push($profitList, array('name'=>$list->shipName_Cn,'data'=>array((float)$list->PlanIncome,(float)$list->YearlyIncome)));
        array_push($incomeList, array('name'=>$list->shipName_Cn,'data'=>array((float)$list->PlanExpense,(float)$list->YearlyExpense)));
        array_push($expenseList, array('name'=>$list->shipName_Cn,'data'=>array((float)$list->PlanProfit,(float)$list->YearlyProfit)));
    }
    ?>
<script>

    jQuery(function(e){

        var profitList = <?php echo json_encode($profitList); ?>;
        var incomeList = <?php echo json_encode($incomeList); ?>;
        var expenseList = <?php echo json_encode($expenseList); ?>;

        $('.selectYearCtrl').on('change', function(){
            var year = $(this).val();
            location.href = "shipYearReport?year=" + year;
        });
        $('.excel-btn').on('click', function() {
            var year = $('.selectYearCtrl').val();
            location.href = "shipYearReportExcel?year=" + year;
        });

        // open dialog of report chart view
        $("#btn-chart").on('click', function (e) {

            e.preventDefault();
            var year = $(this).data('year');
            var dialog = $("#dialog-report-chart").removeClass('hide').dialog({
                modal: true,
                title: year + "年 按船的业绩综合图表",
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