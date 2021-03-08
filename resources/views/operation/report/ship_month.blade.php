<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h5>
                        <select name="selectYears" class="selectYearCtrl">
                            @foreach($yearList as $year)
                                <option value="{{ $year->Yearly }}"  <?php echo $year->Yearly == $currentYear ? 'selected' : ''; ?>>{{ $year->Yearly }}</option>
                            @endforeach
                        </select>
                        <b>년</b>&nbsp;&nbsp;
                        <select name="selectShip" class="selectShipCtrl">
                            <?php $shipName = ''; ?>
                            @foreach($shipList as $list)
                                <option value="{{ $list->RegNo }}"  <?php if($list->RegNo == $ship) { echo 'selected'; $shipName = $list->shipName_Cn;} ?>>{{$list->shipName_En}} | {{$list->shipName_Cn}}</option>
                            @endforeach
                        </select>&nbsp;&nbsp;
                        <b>월별 계획 및 실적종합</b>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <div style="float:right">
                        <button class="btn btn-success btn-sm" id="btn-pie-chart" data-year="{{ $currentYear }}" data-ship="{{ $shipName }}" style="width: 80px">
                            <i class="icon-trello" ></i>통계
                        </button>
                        <button class="btn btn-success btn-sm" id="btn-bar-chart" data-year="{{ $currentYear }}" data-ship="{{ $shipName }}" style="width: 80px">
                            <i class="icon-signal"></i>통계
                        </button>
                        <button class="btn btn-warning btn-sm btn_excel" style="width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                <div class="table-responsive" id="user_list_table">
@else
    @include('layout.excel-style')
@endif
                    <table class="table table-striped table-bordered table-hover" id="user-table">
                        <thead>
                        <tr class="black br-hblue">
                            <th rowspan="2">{{transShipOperation("shipMonthReport.Month")}}</th>
                            <th colspan="3">{{transShipOperation("shipMonthReport.Profit")}}</th>
                            <th colspan="3">{{transShipOperation("shipMonthReport.Income")}}</th>
                            <th colspan="3">{{transShipOperation("shipMonthReport.Expense")}}</th>
                            <th colspan="8">{{transShipOperation("shipMonthReport.Main Expense Iiem")}}</th>
                        </tr>
                        <tr class="black br-hblue">
                            <th>{{transShipOperation("shipMonthReport.Plan")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Actual results")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Achive")}}[%]</th>
                            <th>{{transShipOperation("shipMonthReport.Plan")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Actual results")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Achive")}}[%]</th>
                            <th>{{transShipOperation("shipMonthReport.Plan")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Actual results")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Month")}}[%]</th>
                            <th>{{transShipOperation("shipMonthReport.PD")}}</th>
                            <th>{{transShipOperation("shipMonthReport.FO")}}</th>
                            <th>{{transShipOperation("shipMonthReport.DO")}}</th>
                            <th>{{transShipOperation("shipMonthReport.LO")}}</th>
                            <th>{{transShipOperation("shipMonthReport.S&S")}}</th>
                            <th>{{transShipOperation("shipMonthReport.CTM")}}</th>
                            <th>{{transShipOperation("shipMonthReport.Other")}}</th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $sumPlanProfit=$sumYearlyMonthProfit=$sumPlanIncome=$sumYearlyMonthIncome=$sumPlanExpense=$sumYearlyMonthExpense=$sumYearlyMonthPD=$sumYearlyMonthFO=$sumYearlyMonthDO=$sumYearlyMonthLO=$sumYearlyMonthFW=$sumYearlyMonthSS=$sumYearlyMonthCTM=$sumYearlyMonthOTHERS=0;
                        $planProfit = $pracProfit = $planIncome = $pracIncome = $planExpense = $pracExpense = array();
                                $monthList = array();
                        ?>

                        <?php $monthIndex = 0; ?>
                        @for($month = 1; $month < 13; $month++)
                            <tr>
                                <?php if(isset($data[$monthIndex])) $list = $data[$monthIndex]; ?>
                                @if($list->CP_Month == $month)
                                    <?php $monthIndex++ ?>
                                        <?php  $profit = $list->YearlyMonthIncome - $list->YearlyMonthExpense;
                                        $other = $list->YearlyMonthExpense - $list->YearlyMonthPD - $list->YearlyMonthFO- $list->YearlyMonthDO - $list->YearlyMonthLO -
                                                $list->YearlyMonthSS - $list->YearlyMonthCTM; ?>
                                        <td>{{ $list->CP_Month }}</td><?php array_push($monthList,$list->CP_Month.'월'); ?>
                                        <td style="text-align: right">{{ data_print($list->PlanProfit) }}</td><?php $sumPlanProfit+=$list->PlanProfit;  array_push($planProfit, $list->PlanProfit); ?>
                                        <td style="text-align: right;@if($profit < 0) color:red; @endif">{{ data_print($profit) }}</td><?php $sumYearlyMonthProfit += $profit; array_push($pracProfit, 2); ?>
                                        <td style="text-align: right">{{ $list->PlanProfit == '' | $list->PlanProfit == 0 ? '' : round(($profit/$list->PlanProfit)*100) }}</td>
                                        <td style="text-align: right">{{ data_print($list->PlanIncome) }}</td><?php $sumPlanIncome+=$list->PlanIncome; array_push($planIncome, $list->PlanIncome);?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthIncome) }}</td><?php $sumYearlyMonthIncome+=$list->YearlyMonthIncome; array_push($pracIncome, $list->YearlyMonthIncome);?>
                                        <td style="text-align: right">{{ $list->PlanIncome == '' | $list->PlanIncome == 0 ? '' : round(($list->YearlyMonthIncome/$list->PlanIncome)*100) }}</td>
                                        <td style="text-align: right">{{ data_print($list->PlanExpense) }}</td><?php $sumPlanExpense+=$list->PlanExpense; array_push($planExpense, (float)$list->PlanExpense); ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthExpense) }}</td><?php $sumYearlyMonthExpense+=$list->YearlyMonthExpense; array_push($pracExpense, $list->YearlyMonthExpense);?>
                                        <td style="text-align: right">{{ $list->PlanExpense == '' | $list->PlanExpense == 0 ? '' : round(($list->YearlyMonthExpense/$list->PlanExpense)*100) }}</td>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthPD) }}</td><?php $sumYearlyMonthPD += $list->YearlyMonthPD; ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthFO) }}</td><?php $sumYearlyMonthFO += $list->YearlyMonthFO; ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthDO) }}</td><?php $sumYearlyMonthDO += $list->YearlyMonthDO; ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthLO) }}</td><?php $sumYearlyMonthLO += $list->YearlyMonthLO; ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthSS) }}</td><?php $sumYearlyMonthSS += $list->YearlyMonthSS; ?>
                                        <td style="text-align: right">{{ data_print($list->YearlyMonthCTM) }}</td><?php $sumYearlyMonthCTM += $list->YearlyMonthCTM; ?>
                                        <td style="text-align: right">{{ data_print($other) }}</td><?php $sumYearlyMonthOTHERS += $other; ?>
                                @else
                                        <td>{{ $month }}</td><?php array_push($monthList,$month.'월'); ?>
                                        <td style="text-align: right"></td><?php array_push($planProfit, $list->PlanProfit); ?>
                                        <td style="text-align: right"></td><?php array_push($pracProfit, 2); ?>
                                        <td style="text-align: right"></td>
                                        <td style="text-align: right"></td><?php array_push($planIncome, $list->PlanIncome);?>
                                        <td style="text-align: right"></td><?php array_push($pracIncome, $list->YearlyMonthIncome);?>
                                        <td style="text-align: right"></td>
                                        <td style="text-align: right"></td><?php array_push($planExpense, (float)$list->PlanExpense); ?>
                                        <td style="text-align: right"></td><?php array_push($pracExpense, $list->YearlyMonthExpense);?>
                                        <td style="text-align: right"></td>
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
                        </tbody>
                        <tfoot>
                        <tr>
                            <td style="font-weight: bold;text-align: center">{{transShipOperation("shipMonthReport.TotalSum")}}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanProfit) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthProfit) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanProfit == 0 ? '' : round(($sumYearlyMonthProfit/$sumPlanProfit)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanIncome) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthIncome) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumYearlyMonthIncome/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumPlanExpense) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthExpense) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanExpense == 0 ? '' : round(($sumYearlyMonthExpense/$sumPlanExpense)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthPD) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthFO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthDO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthLO) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthSS) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthCTM) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ data_print($sumYearlyMonthOTHERS) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;text-align: center">{{transShipOperation("shipMonthReport.Percent About Income")}}[%]</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumPlanProfit/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthProfit/$sumYearlyMonthIncome)*100) }}</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td style="font-weight: bold;text-align: right">100</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">{{ $sumPlanIncome == 0 ? '' : round(($sumPlanExpense/$sumPlanIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthExpense/$sumYearlyMonthIncome)*100) }}</td>
                            <td></td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthPD/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthFO/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthDO/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthLO/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthSS/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthCTM/$sumYearlyMonthIncome)*100) }}</td>
                            <td style="font-weight: bold;text-align: right">{{ $sumYearlyMonthIncome == 0 ? '' : round(($sumYearlyMonthOTHERS/$sumYearlyMonthIncome)*100) }}</td>

                        </tr>
                        </tfoot>
                    </table>
@if(!isset($excel))
                    {{-- dialog of pie chart view --}}
                    <div id="dialog-report-pie-chart" class="hide">
                        <form class="form-horizontal">
                            <div class="" id="container-pie" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                        </form>
                    </div>
                    {{-- dialog of bar chart view --}}
                    <div id="dialog-report-bar-chart" class="hide">
                        <form class="form-horizontal">
                            <div class="" id="container-bar" style="min-width: 250px; height: 400px; margin: 0 auto"></div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('/assets/js/highcharts.js') }}"></script>
<?php
    $pieData = array(
    array('name'=>'PD','y'=>$sumYearlyMonthPD),
    array('name'=>'FO','y'=>$sumYearlyMonthFO),
    array('name'=>'DO','y'=>$sumYearlyMonthDO),
    array('name'=>'LO','y'=>$sumYearlyMonthLO),
    array('name'=>'SS','y'=>$sumYearlyMonthSS),
    array('name'=>'CTM','y'=>$sumYearlyMonthCTM),
    array('name'=>'Other','y'=>$sumYearlyMonthOTHERS,'sliced'=>'true','selected'=>'true'),
    );
        $lineChartData = array(
          array('name'=>'리익-계획','data'=>$planProfit),
          array('name'=>'리익-실적','data'=>$pracProfit),
          array('name'=>'수입-계획','data'=>$planIncome),
          array('name'=>'수입-실적','data'=>$pracIncome),
          array('name'=>'지출-계획','data'=>$planExpense),
          array('name'=>'지출-실적','data'=>$pracExpense)
        );
?>
    <script>

        jQuery(function(e){

            var pieData = <?php echo json_encode($pieData); ?>;
            var lineData = <?php echo json_encode($lineChartData); ?>;
            var monthList = <?php echo json_encode($monthList); ?>;

            $('.selectYearCtrl').on('change', function(){
                var year = $(this).val();
                var shipId = $('.selectShipCtrl').val();

                location.href = 'shipMonthReport?shipId=' + shipId + '&year=' + year;
            });

            $('.selectShipCtrl').on('change', function(){
                var shipId = $(this).val();
                var year = $('.selectYearCtrl').val();

                location.href = 'shipMonthReport?shipId=' + shipId + '&year=' + year;
            });
            $('.btn_excel').on('click', function() {
                var year = $('.selectYearCtrl').val();
                var shipId = $('.selectShipCtrl').val();
                location.href = 'shipMonthReportExcel?shipId=' + shipId + '&year=' + year;
            });

            // open dialog of pie chart view
            $("#btn-pie-chart").on('click', function (e) {

                e.preventDefault();

                var year = $(this).data('year');
                var ship = $(this).data('ship');

                var dialog = $("#dialog-report-pie-chart").removeClass('hide').dialog({
                    modal: true,
                    title: year + "년 [" + ship + "] 호 리윤, 실적 및 지출분석 그라프",
                    title_html: true,
                    width:500,
                    buttons: [
                        {
                            text: "닫기",
                            "class": "btn btn-xs",
                            click: function () {
                                $(this).dialog("close");
                            }
                        }
                    ]
                });
                // show pie chart
                $('#container-pie').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: ''
                    },
                    credits:{
                        enabled:false
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: 'Brands',
                        colorByPoint: true,
                        data: pieData
                    }]
                });
            });

            // open dialog of report chart view
            $("#btn-bar-chart").on('click', function (e) {

                e.preventDefault();

                var year = $(this).data('year');
                var ship = $(this).data('ship');

                var dialog = $("#dialog-report-bar-chart").removeClass('hide').dialog({
                    modal: true,
                    title: year + "년 [" + ship + "]호 월별 계획 및 실적종합 그라프",
                    title_html: true,
                    width:1024,
                    buttons: [
                        {
                            text: "닫기",
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
                        categories: monthList
                    },
                    yAxis: {
                        title: {
                            text: '금액($)'
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