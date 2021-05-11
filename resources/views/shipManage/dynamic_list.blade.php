<?php
    if(isset($excel)) $header = 'excel-header';
    else $header = 'header';
?>

<?php
    $isHolder = Session::get('IS_HOLDER');
    $ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/vue.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>

@endsection

@section('scripts')

@endsection


@section('content')
    <link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ cAsset('assets/js/chartjs/flot.css') }}">
    
    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/flot.js') }}"></script>
    <div class="main-content">
        <style>
            .filter_row {
                background-color: #45f7ef;
            }
            .chosen-drop {
                width : 350px !important;
            }

            .c3 path {
            stroke-width: 3px;
        }

        #chartist-h-bars .ct-series-a line {
            stroke: #81afe4;
            /*stroke-width: 5px;
            stroke-dasharray: 10px 20px;*/
        }

        #chartist-h-bars .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars-02 .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-a line {
            stroke: #81afe4;
        }

        .ship-item:hover {
            background-color: #ffe3e082;
        }

        .c3-legend-item text {
            font-size:14px;
        }
        .c3-xgrid-line line {
            stroke: blue;
        }
        .c3-xgrid-line.grid4 line {
            stroke: pink;
        }
        .c3-xgrid-line.grid4 text {
            fill: pink;
        }
        .c3-ygrid-line line {
            stroke: red;
        }
        .c3-ygrid-line.grid800 line {
            stroke: green;
        }
        .c3-ygrid-line.grid800 text {
            fill: green;
        }
        </style>
        <div class="page-header">
            <div class="col-md-3">
                <h4>
                    <b>动态记录</b>
                </h4>
            </div>
        </div>
        <div class="page-content" id="search-div">
            <div class="row">
                <div class="col-md-12 align-bottom" v-cloak>
                    <div class="col-md-5">
                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                        <select class="custom-select d-inline-block" style="padding: 4px;max-width: 100px;" @change="changeShip" v-model="shipId">
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['IMO_No'] }}"
                                        {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="btn-group ml-1">
                            <div class="d-flex">
                                <input type="radio" class="width-auto mt-0" id="all" name="record_type" @change="onTypeChange('all')" :checked="true">
                                <label for="all" class="ml-1">全部记录</label>
                            </div>
                            <div class="d-flex mt-2">
                                <input type="radio" class="width-auto mt-0" id="analyze" name="record_type" @change="onTypeChange('analyze')">
                                <label for="analyze" class="ml-1">记录分析</label>
                            </div>
                        </div>

                        <select class="text-center ml-1" style="width: 60px;" name="year_list" @change="onChangeYear" v-model="activeYear">
                            <option value="0">全部</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                        </select>

                        <label class="font-bold ml-1 text-danger" v-show="record_type == 'all'">航次:</label>
                        <select class="text-center" style="width: 60px;" name="voy_list" @change="onChangeVoy" v-model="activeVoy" v-show="record_type == 'all'">
                            <option value="0">全部</option>
                            <template v-for="voyItem in voy_list">
                                <option :value="voyItem.Voy_No">@{{ voyItem.Voy_No }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex f-left">
                            <strong class="f-right" style="font-size: 16px; padding-top: 6px;">
                                <span id="search_info">"{{ $shipName }}"</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font-bold">动态记录</span>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-5 d-none">
                        <div class="" style="margin-right: 12px; padding-top: 2px;">
                            <table class="contract-table mt-2 table-layout-fixed" style="min-height: auto;">
                            <tr>
                                <td class="width-10">装港</td>
                                <td class="font-style-italic width-40">LOADING PORT</td>
                                <td class="font-style-italic width-50 text-ellipsis white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['loading'] }}</td>
                            </tr>
                            <tr>
                                <td style="width-10">卸港</td>
                                <td class="font-style-italic width-40">DISCHARGING PORT</td>
                                <td class="font-style-italic width-50 text-ellipsis white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['discharge'] }}</td>
                            </tr>                            
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group f-right">
                            <button class="btn btn-report-search btn-sm search-btn d-none" click="doSearch()"><i class="icon-search"></i>搜索</button>
                            <a class="btn btn-sm btn-danger refresh-btn-over d-none" type="button" click="refresh">
                                <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                            </a>
                            <button class="btn btn-warning btn-sm save-btn" @click="submitForm"><i class="icon-table"></i> {{ trans('common.label.excel') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Contents Begin -->
            <div class="row" style="margin-top: 4px;">
                <div class="col-md-12">
                    <table class="table-bordered dynamic-table" v-show="record_type == 'all'">
                        <thead>
                            <tr>
                                <th class="text-center font-style-italic">VOY No</th>
                                <th class="text-center font-style-italic">DATE</th>
                                <th class="text-center font-style-italic" colspan="2">TIME[LT]</th>
                                <th class="text-center font-style-italic" rowspan="2">GMT</th>
                                <th class="text-center font-style-italic">STATUS</th>
                                <th class="text-center font-style-italic">动态</th>
                                <th class="text-center font-style-italic">POSITION</th>
                                <th class="text-center font-style-italic">DTG[NM]</th>
                                <th class="text-center font-style-italic">SPEED</th>
                                <th class="text-center font-style-italic">RPM</th>
                                <th class="text-center font-style-italic">CGO QTY</th>
                                <th class="text-center font-style-italic" colspan="2">ROB</th>
                                <th class="text-center font-style-italic" colspan="2">BUNKERING</th>
                                <th class="text-center font-style-italic">REMARK</th>
                            </tr>
                            <tr>
                                <th class="text-center">航次</th>
                                <th class="text-center font-style-italic">YY/MM/DD</th>
                                <th class="text-center font-style-italic">hh</th>
                                <th class="text-center font-style-italic">mm</th>
                                <th class="text-center font-style-italic">动态</th>
                                <th class="text-center">种类</th>
                                <th class="text-center">港口(坐标)</th>
                                <th class="text-center">距离</th>
                                <th class="text-center">速度</th>
                                <th class="text-center">转数</th>
                                <th class="text-center">存货量</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="prev-voy d-none">
                                <td class="text-center">@{{ prevData['CP_ID'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Date'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Hour'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Minute'] }}</td>
                                <td class="text-center">@{{ prevData['GMT'] }}</td>
                                <td style="padding-left: 8px!important">@{{ prevData['Voy_Status'] }}</td>
                                <td style="padding-left: 8px!important">@{{ prevData['Voy_Type'] }}</td>
                                <td style="padding-left: 4px!important">@{{ prevData['Ship_Position'] }}</td>
                                <td class="text-center">@{{ prevData['Sail_Distance'] }}</td>
                                <td class="text-center">@{{ prevData['Speed'] }}</td>
                                <td class="text-center">@{{ prevData['RPM'] }}</td>
                                <td class="text-right font-weight-bold text-danger">@{{ prevData['Cargo_Qtty'] }}</td>
                                <td class="text-center font-weight-bold text-danger">@{{ prevData['ROB_FO'] }}</td>
                                <td class="text-center font-weight-bold text-danger">@{{ prevData['ROB_DO'] }}</td>
                                <td class="text-center">@{{ prevData['BUNK_FO'] }}</td>
                                <td class="text-center">@{{ prevData['BUNK_DO'] }}</td>
                                <td class="text-center">@{{ prevData['Remark'] }}</td>
                            </tr>
                            <template v-for="(currentItem, index) in currentData">
                                <tr>
                                    <td class="d-none"><input type="hidden" :value="currentItem.id" name="id[]"></td>
                                    <td class="text-center voy-td">@{{ currentItem.CP_ID }}</td>
                                    <td class="text-center date-width">@{{ currentItem.Voy_Date }}</td>
                                    <td class="text-center time-width">@{{ currentItem.Voy_Hour }}</td>
                                    <td class="text-center time-width">@{{ currentItem.Voy_Minute }}</td>
                                    <td class="text-center time-width">@{{ currentItem.GMT }}</td>
                                    <td>@{{ currentItem.Voy_Status_Name }}</td>
                                    <td>@{{ currentItem.Voy_Type_Name }}</td>
                                    <td class="position-width">@{{ currentItem.Ship_Position }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.Sail_Distance, 0) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.Speed, 1) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.RPM, 0) }}</td>
                                    <td class="text-right">@{{ number_format(currentItem.Cargo_Qtty, 2) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.ROB_FO, 2) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.ROB_DO, 2) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.BUNK_FO, 2) }}</td>
                                    <td class="text-center">@{{ number_format(currentItem.BUNK_DO, 2) }}</td>
                                    <td class="position-width">@{{ currentItem.Remark }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <table class="dynamic-result-table" v-show="record_type == 'all'">
                        <tbody>
                            <tr class="dynamic-footer">
                                <td class="text-center" rowspan="2">航次</td>
                                <td class="text-center" rowspan="2">报告次</td>
                                <td class="text-center" rowspan="2" colspan="2">时间</td>
                                <td class="text-center" rowspan="2">航次用时</td>
                                <td class="text-center" rowspan="2">距离<br>[NM]</td>
                                <td class="text-center" rowspan="2">平均<br>速度</td>
                                <td class="text-center">经济天</td>
                                <td class="text-center"><span class="text-warning">@{{ number_format(economic_rate) }}%</span></td>
                                <td class="text-center" colspan="2">总消耗</td>
                                <td class="text-center" colspan="2">加油量</td>
                                <td class="text-center" colspan="2">标准消耗</td>
                                <td class="text-center" colspan="2">-节约/+超过</td>
                            </tr>
                            <tr class="dynamic-footer">
                                <td class="text-center">航行</td>
                                <td class="text-center">装卸货</td>
                                <td class="text-center">FO</td>
                                <td class="text-center">DO</td>
                                <td class="text-center">FO</td>
                                <td class="text-center">DO</td>
                                <td class="text-center">FO</td>
                                <td class="text-center">DO</td>
                                <td class="text-center">FO</td>
                                <td class="text-center">DO</td>
                            </tr>
                            <tr class="dynamic-footer-result">
                                <td>@{{ activeVoy }}</td>
                                <td>@{{ number_format(this.currentData.length, 0) }}</td>
                                <td colspan="2">@{{ sail_term['min_date'] }} ~ @{{ sail_term['max_date'] }}</td>
                                <td>@{{ number_format(sail_time, 2) }}</td>
                                <td>@{{ number_format(total_distance, 0) }}</td>
                                <td>@{{ number_format(average_speed) }}</td>
                                <td>@{{ number_format(total_sail_time, 2) }}</td>
                                <td>@{{ number_format(total_loading_time) }}</td>
                                <td>@{{ number_format(rob_fo) }}</td>
                                <td>@{{ number_format(rob_do) }}</td>
                                <td>@{{ number_format(bunker_fo) }}</td>
                                <td>@{{ number_format(bunker_do) }}</td>
                                <td>@{{ number_format(used_fo) }}</td>
                                <td>@{{ number_format(used_do) }}</td>
                                <td>@{{ number_format(save_fo) }}</td>
                                <td>@{{ number_format(save_do) }}</td>
                            </tr>
                        </tbody>
                    </table>




                    <table class="dynamic-result-table analyze-table" v-show="record_type == 'analyze'">
                            <tbody>
                            <tr class="dynamic-footer">
                                <td class="text-center" rowspan="2" style="width: 45px;">航次</td>
                                <td class="text-center" rowspan="2" style="width: 45px;">报告次</td>
                                <td class="text-center" rowspan="2" style="width: 145px;">期间</td>
                                <td class="text-center">航次</td>
                                <td class="text-center" style="width: 260px;">装港</td>
                                <td class="text-center" style="width: 260px;">卸港</td>
                                <td class="text-center">距离</td>
                                <td class="text-center">平均</td>
                                <td class="text-center" colspan="5">经济天数</td>
                                <td class="text-center" colspan="6">非经济天数</td>
                            </tr>
                            <tr class="dynamic-footer">
                                <td class="text-center">用时</td>
                                <td class="text-center font-style-italic font-weight-bold">LOADING PORT</td>
                                <td class="text-center font-style-italic font-weight-bold">DISCHG PORT</td>
                                <td class="text-center">[NM]</td>
                                <td class="text-center">速度</td>
                                <td class="text-center">合计</td>
                                <td class="text-center">占率</td>
                                <td class="text-center">航次</td>
                                <td class="text-center">装货</td>
                                <td class="text-center">卸货</td>
                                <td class="text-center">合计</td>
                                <td class="text-center">待泊</td>
                                <td class="text-center">天气</td>
                                <td class="text-center">修理</td>
                                <td class="text-center">供应</td>
                                <td class="text-center">其他</td>
                            </tr>
                            <template v-for="(item, index) in analyze.list">
                            <tr class="dynamic-footer-result">
                                <td>@{{ item.voy_no }}</td>
                                <td>@{{ item.voy_count }}</td>
                                <td>@{{ item.voy_start }} ~ @{{ item.voy_end }}</td>
                                <td>@{{ item.sail_time }}</td>
                                <td style="text-align: left">@{{ item.lport }}</td>
                                <td style="text-align: left">@{{ item.dport }}</td>
                                <td>@{{ number_format(item.total_distance, 0) }}</td>
                                <td>@{{ number_format(item.average_speed, 2) }}</td>
                                <td>@{{ number_format(item.total_loading_time, 2) }}</td>
                                <td>@{{ number_format(item.economic_rate, 1) }}%</td>
                                <td>@{{ number_format(item.total_sail_time, 2) }}</td>
                                <td>@{{ number_format(item.loading_time, 2) }}</td>
                                <td>@{{ number_format(item.disch_time, 2) }}</td>
                                <td>@{{ number_format(item.sail_time - item.total_loading_time, 2) }}</td>
                                <td>@{{ number_format(item.total_waiting_time, 2) }}</td>
                                <td>@{{ number_format(item.total_weather_time, 2) }}</td>
                                <td>@{{ number_format(item.total_repair_time, 2) }}</td>
                                <td>@{{ number_format(item.total_supply_time, 2) }}</td>
                                <td>@{{ number_format(item.total_else_time, 2) }}</td>
                            </tr>
                            </template>
                        </tbody>
                    </table>

                    <table class="dynamic-result-table analyze-table" v-show="record_type == 'analyze'">
                            <tbody>
                            <tr class="dynamic-footer">
                                <td class="text-center" rowspan="2" style="width: 45px;">航次数</td>
                                <td class="text-center" rowspan="2" style="width: 45px;">航次数</td>
                                <td class="text-center" rowspan="2" style="width: 145px;">期间</td>
                                <td class="text-center">航次</td>
                                <td class="text-center" style="width: 260px;">装港</td>
                                <td class="text-center" style="width: 260px;">卸港</td>
                                <td class="text-center">距离</td>
                                <td class="text-center">平均</td>
                                <td class="text-center" colspan="5">经济天数</td>
                                <td class="text-center" colspan="6">非经济天数</td>
                            </tr>
                            <tr class="dynamic-footer">
                                <td class="text-center">用时</td>
                                <td class="text-center font-style-italic font-weight-bold">LOADING PORT</td>
                                <td class="text-center font-style-italic font-weight-bold">DISCHG PORT</td>
                                <td class="text-center">[NM]</td>
                                <td class="text-center">速度</td>
                                <td class="text-center">合计</td>
                                <td class="text-center">占率</td>
                                <td class="text-center">航次</td>
                                <td class="text-center">装货</td>
                                <td class="text-center">卸货</td>
                                <td class="text-center">合计</td>
                                <td class="text-center">待泊</td>
                                <td class="text-center">天气</td>
                                <td class="text-center">修理</td>
                                <td class="text-center">供应</td>
                                <td class="text-center">其他</td>
                            </tr>
                            <template>
                            <tr class="dynamic-footer-result">
                                <td>@{{ analyze.total.voy_count }}</td>
                                <td>@{{ analyze.total.voy_count }}</td>
                                <td>@{{ analyze.total.voy_start }} ~ @{{ analyze.total.voy_end }}</td>
                                <td>@{{ analyze.total.sail_time }}</td>
                                <td></td>
                                <td></td>
                                <td>@{{ number_format(analyze.total.total_distance, 0) }}</td>
                                <td>@{{ number_format(analyze.total.average_speed, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_loading_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.economic_rate, 1) }}%</td>
                                <td>@{{ number_format(analyze.total.total_sail_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.loading_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.disch_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.sail_time - analyze.total.total_loading_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_waiting_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_weather_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_repair_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_supply_time, 2) }}</td>
                                <td>@{{ number_format(analyze.total.total_else_time, 2) }}</td>
                            </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Main Contents End -->
        </div>
        <div>
            <div id="economic-chart" style="height: 250px"></div>
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue-numeral-filter.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>

	<?php
	echo '<script>';
    echo 'var DynamicStatus = ' . json_encode(g_enum('DynamicStatus')) . ';';
    echo 'var DynamicSub = ' . json_encode(g_enum('DynamicSub')) . ';';
	echo '</script>';
	?>

    <script>
        var searchObj = null;
        var shipId = '{!! $shipId !!}';
        var shipInfo = JSON.parse('{!! $shipInfo !!}');
        var DYNAMIC_SUB_SALING = '{!! DYNAMIC_SUB_SALING !!}';
        var DYNAMIC_SUB_LOADING = '{!! DYNAMIC_SUB_LOADING !!}';
        var DYNAMIC_SUB_DISCH = '{!! DYNAMIC_SUB_DISCH !!}';
        var DYNAMIC_SUB_WAITING = '{!! DYNAMIC_SUB_WAITING !!}';
        var DYNAMIC_SUB_WEATHER = '{!! DYNAMIC_SUB_WEATHER !!}';
        var DYNAMIC_SUB_REPAIR = '{!! DYNAMIC_SUB_REPAIR !!}';
        var DYNAMIC_SUB_SUPPLY = '{!! DYNAMIC_SUB_SUPPLY !!}';
        var DYNAMIC_SUB_ELSE = '{!! DYNAMIC_SUB_ELSE !!}';

        
        var DYNAMIC_SAILING = '{!! DYNAMIC_SAILING !!}';
        var DYNAMIC_CMPLT_DISCH = '{!! DYNAMIC_CMPLT_DISCH !!}';
        const DAY_UNIT = 1000 * 3600;
        const COMMON_DECIMAL = 2;
        var economic_graph = null;

        $(function() {
            initialize();
        });

        function initialize() {
            searchObj = new Vue({
                el: '#search-div',
                data: {
                    shipId: 0,
                    shipName: '',
                    ship_list: [],
                    voy_list: [],
                    port: {
                        loading: '',
                        discharge: '',
                    },
                    activeVoy: 0,
                    activeYear: 0,

                    prevData: [],
                    currentData: {

                    },

                    dynamicStatus: DynamicStatus,

                    sail_term: {
                        min_date: '0000-00-00',
                        max_date: '0000-00-00',
                    },

                    sail_time:              0,
                    total_distance:         0,
                    total_sail_time:        0,
                    total_loading_time:     0,
                    economic_rate:          0,
                    average_speed:          0,

                    rob_fo:                 0,
                    rob_do:                 0,
                    bunker_fo:              0,
                    bunker_do:              0,

                    used_fo:                0,
                    used_do:                0,
                    save_fo:                0,
                    save_do:                0,

                    record_type:            'all',

                    analyze: {
                        list: [],
                        total: [],
                        xAxis: ['pv'],
                        xAxisLabel: ['x'],
                        yAxis: ['pv'],
                        yAxisLabel: ['x'],
                    }
                },
                init: function() {
                    this.changeShip();
                },
                methods: {
                    changeShip: function(evt) {
                        location.href = '/shipManage/dynamicList?shipId=' + $(evt.target).val();
                    },
                    getShipName: function(shipName, EnName) {
                        return shipName == '' ? EnName : shipName;
                    },
                    getVoyList: function(shipId) {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/voy/list',
                            type: 'post',
                            data: {
                                shipId: shipId,
                                year: this.activeYear
                            },
                            success: function(result) {
                                searchObj.voy_list = [];
                                searchObj.voy_list = Object.assign([], [], result);
                                searchObj.activeVoy = 0;
                            }
                        });
                    },
                    number_format: function(value, decimal = 1) {
                        return isNaN(value) || value == 0 ? '' : number_format(value, decimal);
                    },
                    onChangeVoy: function(evt) {
                        this.setPortName();
                        this.getData();
                    },
                    onTypeChange(val) {
                        this.record_type = val;
                        if(this.record_type == 'all') {
                            this.getData();
                        } else {
                            this.getAnalyzeData();
                        }
                        
                    },
                    onChangeYear: function(e) {
                        this.activeYear = e.target.value;
                        this.getVoyList(this.shipId);
                        if(this.record_type == 'all') {
                            this.getData();
                        } else {
                            this.getAnalyzeData();
                        }
                    },
                    getAnalyzeData() {
                        let $_this = this.analyze.list;
                        $_this = [];
                        $.ajax({
                            url: BASE_URL + 'ajax/business/dynamic/search',
                            type: 'post', 
                            data: {
                                shipId: searchObj.shipId,
                                voyId: searchObj.activeVoy,
                                type: searchObj.record_type,
                                year: searchObj.activeYear
                            },
                            success: function(result) {
                                let data = result['currentData'];
                                let voyData = result['voyData'];
                                let cpData = result['cpData'];

                                searchObj.analyze.list = [];
                                console.log(voyData);
                                console.log(data);
                                let realData = [];
                                let footerData = [];
                                footerData['voy_count'] = 0;
                                footerData['voy_count'] = 0;
                                // footerData['voy_start'] = 0;
                                footerData['sail_time'] = 0;
                                footerData['total_distance'] = 0;
                                footerData['total_sail_time'] = 0;
                                footerData['total_loading_time'] = 0;
                                footerData['loading_time'] = 0;
                                footerData['disch_time'] = 0;
                                footerData['total_waiting_time'] = 0;
                                footerData['total_weather_time'] = 0;
                                footerData['total_repair_time'] = 0;
                                footerData['total_supply_time'] = 0;
                                footerData['total_else_time'] = 0;

                                voyData.forEach(function(value, key) {
                                    let tmpData = data[value];
                                    let total_sail_time = 0;
                                    let total_loading_time = 0;
                                    let loading_time = 0;
                                    let disch_time = 0;
                                    let total_waiting_time = 0;
                                    let total_weather_time = 0;
                                    let total_repair_time = 0;
                                    let total_supply_time = 0;
                                    let total_else_time = 0;
                                    let total_distance = 0;



                                    realData = [];
                                    realData['voy_no'] = value;
                                    realData['voy_count'] = tmpData.length;
                                    realData['voy_start'] = tmpData[0]['Voy_Date'];
                                    realData['voy_end'] = tmpData[tmpData.length - 1]['Voy_Date'];
                                    realData['lport'] = cpData[value]['LPort'];
                                    realData['dport'] = cpData[value]['DPort'];
                                    realData['sail_time'] = __getTermDay(realData['voy_start'], realData['voy_end'], tmpData[0]['GMT'], tmpData[tmpData.length - 1]['GMT']);

                                    // searchObj.setTotalInfo(data);
                                    tmpData.forEach(function(data_value, data_key) {
                                        total_distance += parseInt(data_value["Sail_Distance"]);


                                        if(data_key > 0) {
                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_sail_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_LOADING) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                loading_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                disch_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_waiting_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_WEATHER) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_weather_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_REPAIR) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_repair_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_SUPPLY) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_supply_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                            if(data_value['Voy_Type'] == DYNAMIC_SUB_ELSE) {
                                                let preKey = data_key - 1;
                                                let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                                let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                                total_else_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                            }

                                        }
                                    });

                                    realData.total_sail_time = total_sail_time.toFixed(2);
                                    realData.total_distance = total_distance;
                                    realData.average_speed = BigNumber(realData.total_distance).div(realData.total_sail_time).div(24).toFixed(1);
                                    realData.loading_time = loading_time.toFixed(COMMON_DECIMAL);
                                    realData.disch_time = disch_time.toFixed(COMMON_DECIMAL);
                                    realData.total_loading_time = BigNumber(loading_time).plus(disch_time).plus(total_sail_time).toFixed(2);
                                    realData.economic_rate = BigNumber(loading_time).plus(disch_time).plus(realData.total_sail_time).div(realData.sail_time).multipliedBy(100).toFixed(1);
                                    realData.total_waiting_time = total_waiting_time.toFixed(COMMON_DECIMAL);
                                    realData.total_weather_time = total_weather_time.toFixed(COMMON_DECIMAL);
                                    realData.total_repair_time = total_repair_time.toFixed(COMMON_DECIMAL);
                                    realData.total_supply_time = total_supply_time.toFixed(COMMON_DECIMAL);
                                    realData.total_else_time = total_else_time.toFixed(COMMON_DECIMAL);

                                    // Calc Footer data
                                    footerData['voy_count'] += parseInt(realData['voy_count']);
                                    footerData['sail_time'] += parseInt(realData['sail_time']);
                                    footerData['total_distance'] += parseInt(realData['total_distance']);
                                    footerData['total_sail_time'] += parseFloat(realData['total_sail_time']);
                                    footerData['total_loading_time'] += parseFloat(realData['total_loading_time']);
                                    footerData['loading_time'] += parseFloat(realData['loading_time']);
                                    footerData['disch_time'] += parseFloat(realData['disch_time']);
                                    footerData['total_waiting_time'] += parseFloat(realData['total_waiting_time']);
                                    footerData['total_weather_time'] += parseFloat(realData['total_weather_time']);
                                    footerData['total_repair_time'] += parseFloat(realData['total_repair_time']);
                                    footerData['total_supply_time'] += parseFloat(realData['total_supply_time']);
                                    footerData['total_else_time'] += parseFloat(realData['total_else_time']);

                                    footerData['average_speed'] = parseFloat(BigNumber(realData['average_speed']).div(voyData.length).toFixed(2));
                                    footerData['economic_rate'] = BigNumber(realData['loading_time']).plus(realData['disch_time']).plus(realData['total_sail_time']).div(realData['sail_time']).multipliedBy(100).div(voyData.length).toFixed(1);

                                    searchObj.analyze.list.push(realData);
                                    searchObj.analyze.xAxis.push(parseFloat(realData.economic_rate));
                                    searchObj.analyze.xAxisLabel.push(realData['voy_no']);
                                });

                                searchObj.analyze.total = footerData;
                                let displayData = Object.assign([], [], searchObj.analyze.graph_value);console.log(displayData)
                                var gridColor = '#aaaaaa';
                                var gridBorder = '#eeeeee';
                                var legendBg = '#f5f5f5';

                                $.plot($('#economic-chart'), [
                                    {
                                    data: [
                                        [ 6, 196 ], [ 7, 175 ], [ 8, 212 ], [ 9, 247 ], [ 10, 152 ], [ 11, 225 ], [ 12, 155 ], [ 13, 203 ], [ 14, 166 ], [ 15, 151 ]
                                    ]
                                    },
                                ], {
                                    series: {
                                    shadowSize: 0,
                                    lines: {
                                        show: true
                                    },
                                    points: {
                                        show: true,
                                        radius: 4
                                    }
                                    },

                                    grid: {
                                    color: gridColor,
                                    borderColor: gridBorder,
                                    borderWidth: 1,
                                    hoverable: true,
                                    clickable: true
                                    },

                                    xaxis: { tickColor: gridBorder, },
                                    yaxis: { tickColor: gridBorder, },
                                    legend: { backgroundColor: legendBg },
                                    tooltip: { show: true },
                                    colors: ["red"]
                                });
                            }
                        });
                    },
                    getData: function() {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/dynamic/search',
                            type: 'post',
                            data: {
                                shipId: searchObj.shipId,
                                voyId: searchObj.activeVoy,
                                type: searchObj.record_type,
                                year: searchObj.activeYear
                            },
                            success: function(result) {
                                let data = result;
                                searchObj.currentData = [];
                                searchObj.prevData = [];
                                if(data['prevData'] != undefined && data['prevData'] != null) {
                                    searchObj.prevData = Object.assign([], [], data['prevData']);
                                    // searchObj.prevData['Voy_Type'] = DynamicSub[searchObj.prevData['Voy_Type']];
                                    // searchObj.prevData['Voy_Status'] = DynamicStatus[searchObj.prevData['Voy_Status']][0];
                                }
                                
                                if(data['currentData'] != undefined && data['currentData'] != null && data['currentData'].length > 0) {
                                    searchObj.currentData = Object.assign([], [], data['currentData']);

                                    let total_sail_time = 0;
                                    let total_loading_time = 0;
                                    let total_waiting_time = 0;

                                    searchObj.setTotalInfo(data);
                                    searchObj.currentData.forEach(function(value, key) {
                                        searchObj.currentData[key]['Voy_Status_Name'] = DynamicStatus[value['Voy_Status']][0];
                                        searchObj.currentData[key]['Voy_Type_Name'] = DynamicSub[value['Voy_Type']];
                                        searchObj.total_distance += parseInt(value["Sail_Distance"]);
                                        searchObj.bunker_fo += value['BUNK_FO'];
                                        searchObj.bunker_do += value['BUNK_DO'];

                                        if(key > 0) {
                                            // Calc Sail Count
                                            if(value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_sail_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }
                                            // Calc Sail Count
                                            if(value['Voy_Type'] == DYNAMIC_SUB_LOADING || value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_loading_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }

                                            if(value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_waiting_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }
                                        }
                                    });

                                    searchObj.total_sail_time = total_sail_time.toFixed(2);
                                    searchObj.total_loading_time = total_loading_time.toFixed(2);
                                    searchObj.average_speed = BigNumber(searchObj.total_distance).div(searchObj.total_sail_time).div(24).toFixed(1);
                                    
                                    searchObj.economic_rate = BigNumber(total_loading_time).plus(searchObj.total_sail_time).div(searchObj.sail_time).multipliedBy(100).toFixed(1);
                                    searchObj.prevData['ROB_FO'] = searchObj.prevData['ROB_FO'] == null || searchObj.prevData['ROB_FO'] == undefined ? 0 : searchObj.prevData['ROB_FO'];
                                    searchObj.prevData['ROB_DO'] = searchObj.prevData['ROB_DO'] == null || searchObj.prevData['ROB_DO'] == undefined ? 0 : searchObj.prevData['ROB_DO'];

                                    searchObj.rob_fo = BigNumber(searchObj.prevData['ROB_FO']).plus(searchObj.bunker_fo).minus(data['max_date']['ROB_FO']).toFixed(1);
                                    searchObj.rob_do = BigNumber(searchObj.prevData['ROB_DO']).plus(searchObj.bunker_do).minus(data['max_date']['ROB_DO']).toFixed(1);

                                    let usedFoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['FOSailCons_S']);
                                    let usedFoTmp2 = BigNumber(searchObj.total_loading_time).multipliedBy(shipInfo['FOL/DCons_S']);
                                    let usedFoTmp3 = BigNumber(total_waiting_time).multipliedBy(shipInfo['FOIdleCons_S']);

                                    let usedDoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['DOSailCons_S']);
                                    let usedDoTmp2 = BigNumber(searchObj.total_loading_time).multipliedBy(shipInfo['DOL/DCons_S']);
                                    let usedDoTmp3 = BigNumber(total_waiting_time).multipliedBy(shipInfo['DOIdleCons_S']);

                                    searchObj.used_fo = BigNumber(usedFoTmp1).plus(usedFoTmp2).plus(usedFoTmp3).toFixed(2);
                                    searchObj.used_do = BigNumber(usedDoTmp1).plus(usedDoTmp2).plus(usedDoTmp3).toFixed(2);

                                    searchObj.save_fo = BigNumber(searchObj.used_fo).minus(searchObj.rob_fo).toFixed(2);
                                    searchObj.save_do = BigNumber(searchObj.used_do).minus(searchObj.rob_do).toFixed(2);

                                }
                            }
                        })
                    },
                    setTotalInfo: function(data) {
                        searchObj.sail_term['min_date'] = data['min_date'] == false ? '' : data['min_date']['Voy_Date'];
                        searchObj.sail_term['max_date'] = data['max_date'] == false ? '' : data['max_date']['Voy_Date'];
                        let start_date = data['min_date']['Voy_Date'] + ' ' + data['min_date']['Voy_Hour'] + ':' + data['min_date']['Voy_Minute'];
                        let end_date = data['max_date']['Voy_Date'] + ' ' + data['max_date']['Voy_Hour'] + ':' + data['max_date']['Voy_Minute'];
                        
                        this.sail_time = __getTermDay(start_date, end_date, data['min_date']['GMT'], data['max_date']['GMT']);
                    },
                    setPortName: function() {
                        searchObj.voy_list.forEach(function(value, index) {
                            if(searchObj.activeVoy == value['Voy_No']) {
                                searchObj.port['loading'] = value['LPort'] == false ? '-' : value['LPort'];
                                searchObj.port['discharge'] = value['DPort'] == false ? '-' : value['DPort'];
                                status = 1;
                            }
                        });
                    },
                    dateModify(e, index) {
                        $(e.target).on("change", function() {
                            searchObj.currentData[index]['Voy_Date'] = $(this).val();
                        });
                    },
                    onChangeStatus: function(e, index) {
                        let voyStatus = $(e.target).val();
                        searchObj.currentData[index]['dynamicSub'] = getSubList(voyStatus);
                        searchObj.currentData[index]['Voy_Type'] = getSubList(voyStatus)[0][0];
                        searchObj.$forceUpdate();
                    },
                    submitForm: function() {
                        submitted = true;
                        if(!this.validateForm()) {
                            alert('Please input ROB/FO, ROB/DO value.');
                            return;
                        } else
                            $('#dynamic-form').submit();
                    },
                    validateForm() {
                        let $this = this.currentData;
                        let retVal = true;
                        $this.forEach(function(value, key) {
                            if($this[key]['Voy_Status'] == DYNAMIC_CMPLT_DISCH) {
                                if($this[key]['Cargo_Qtty'] == 0) {
                                    if($this[key]['ROB_FO'] == undefined || $this[key]['ROB_DO'] == undefined) {
                                        retVal = false;
                                    }
                                }
                            }
                        });

                        return retVal;

                    },
                    getToday: function(symbol) {
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + symbol + mm + symbol + dd;

                        return today;
                    },
                    addRow: function() {
                        this.setDefaultData();
                    },
                    setDefaultData() {
                        let length = searchObj.currentData.length;
                        searchObj.currentData.push([]);
                        searchObj.currentData[length]['Voy_Status'] = DYNAMIC_SAILING;
                        searchObj.currentData[length]['dynamicSub'] = getSubList(DYNAMIC_SAILING);
                        searchObj.currentData[length]['Voy_Type'] = DYNAMIC_SUB_SALING;
                        searchObj.currentData[length]['GMT'] = 8;
                        searchObj.currentData[length]['Voy_Hour'] = 8;
                        searchObj.currentData[length]['Voy_Minute'] = 0;
                        searchObj.currentData[length]['Voy_Date'] = this.getToday('-');
                        searchObj.$forceUpdate();
                    }
                },
                updated() {
                    $('.date-picker').datepicker({
                        autoclose: true,
                    }).next().on(ace.click_event, function () {
                        $(this).prev().focus();
                    });
                }
            });


            searchObj.shipId = shipId;
            getInitInfo();
        }

        function getInitInfo() {
            $.ajax({
                url: BASE_URL + 'ajax/business/voy/list',
                type: 'post',
                data: {
                    shipId: shipId,
                },
                success: function(result) {
                    searchObj.voy_list = [];
                    searchObj.voy_list = Object.assign([], [], result);
                    // if(searchObj.voy_list.length > 0) {
                    //     searchObj.activeVoy = searchObj.voy_list[0]['Voy_No'];
                    // }

                    searchObj.setPortName();
                    searchObj.getData();
                }
            });
            // $.ajax({
            //     url: BASE_URL + 'ajax/business/dynamic',
            //     type: 'post',
            //     data: {
            //         shipId: searchObj.shipId,
            //         voyNo: searchObj.voyNo,
            //     }
            //     success: function(result) {
            //         let data = result['shipList'];
            //         searchObj.ship_list = data;
            //     }
            // });
        }

        function getSubList(type) {
            let tmp = DynamicStatus[type][1];
            let retVal = [];
            tmp.forEach(function(value) {
                retVal.push([value, DynamicSub[value]]);
            });

            return retVal;
        }

        function __getTermDay(start_date, end_date, start_gmt = 8, end_gmt = 8) {
            let currentDate = moment(end_date).valueOf();
            let currentGMT = DAY_UNIT * end_gmt;
            let prevDate = moment(start_date).valueOf();
            let prevGMT = DAY_UNIT * start_gmt;
            let diffDay = 0;
            currentDate = BigNumber(currentDate).minus(currentGMT).div(DAY_UNIT);
            prevDate = BigNumber(prevDate).minus(prevGMT).div(DAY_UNIT);
            diffDay = currentDate.minus(prevDate);
            return parseFloat(diffDay.div(24));
        }
        

    </script>

@endsection