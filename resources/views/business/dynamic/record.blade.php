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


@section('content')
    <div class="main-content">
        <style>
            .filter_row {
                background-color: #45f7ef;
            }
            .chosen-drop {
                width : 350px !important;
            }
        </style>
        <div class="page-header">
            <div class="col-md-3">
                <h4>
                    <b>合同记录</b>
                </h4>
            </div>
        </div>

        <div class="page-content" id="search-div">
            <div class="row">
                <div class="col-md-12 align-bottom" v-cloak>
                    <div class="col-md-3">
                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                        <select class="custom-select d-inline-block" style="padding: 4px;max-width: 100px;" @change="changeShip" v-model="shipId">
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['IMO_No'] }}"
                                        {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                </option>
                            @endforeach
                        </select>
                        <label class="font-bold">航次:</label>
                            <select class="text-center" style="width: 60px;" name="voy_list" @change="onChangeVoy" v-model="activeVoy">
                                <template v-for="voyItem in voy_list">
                                    <option :value="voyItem.Voy_No">@{{ voyItem.Voy_No }}</option>
                                </template>
                            </select>                        
                        @if(isset($shipName['shipName_En']))
                            <strong class="f-right" style="font-size: 16px; padding-top: 6px;">"<span id="ship_name">{{ $shipName['shipName_En'] }}</span>" CERTIFICATES</strong>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex f-right">
                            <span class="ship-title">{{ $shipName }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font-style-italic font-bold">DAILY REPORT</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="" style="margin-right: 12px; padding-top: 2px;">
                            <table class="contract-table mt-2" style="min-height: auto;">
                            <tr>
                                <td>装港</td>
                                <td class="font-style-italic auto-width"><div>LOADING PORT</div></td>
                                <td class="font-style-italic white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['loading'] }}</td>
                            </tr>
                            <tr>
                                <td>卸港</td>
                                <td class="font-style-italic auto-width">DISCHARGING PORT</td>
                                <td class="font-style-italic white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['discharge'] }}</td>
                            </tr>                            
                            </table>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="btn-group f-right">
                            <button class="btn btn-report-search btn-sm search-btn d-none" click="doSearch()"><i class="icon-search"></i>搜索</button>
                            <a class="btn btn-sm btn-danger refresh-btn-over d-none" type="button" click="refresh">
                                <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                            </a>
                            <button class="btn btn-inverse btn-sm save-btn" @click="submitForm"><i class="icon-save"></i> {{ trans('common.label.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Contents Begin -->
            <div class="row" style="margin-top: 4px;">
                <div class="col-md-12">
                <form action="saveDynamic" method="post" id="dynamic-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="shipId" value="{{ $shipId }}">
                    <table class="table-bordered">
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
                                <th class="text-center">type</th>
                                <th class="text-center">POSITION</th>
                                <th class="text-center">DTG[NM]</th>
                                <th class="text-center">速度</th>
                                <th class="text-center">RPM</th>
                                <th class="text-center">CGO QTY</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="prev-voy">
                                <td>@{{ prevData['CP_ID'] }}</td>
                                <td>@{{ prevData['Voy_Date'] }}</td>
                                <td>@{{ prevData['Voy_Hour'] }}</td>
                                <td>@{{ prevData['Voy_Minute'] }}</td>
                                <td>@{{ prevData['GMT'] }}</td>
                                <td>@{{ prevData['Voy_Status'] }}</td>
                                <td>@{{ prevData['Voy_Type'] }}</td>
                                <td>@{{ prevData['Ship_Position'] }}</td>
                                <td>@{{ prevData['Sail_Distance'] }}</td>
                                <td>@{{ prevData['Speed'] }}</td>
                                <td>@{{ prevData['RPM'] }}</td>
                                <td>@{{ prevData['Cargo_Qtty'] }}</td>
                                <td>@{{ prevData['ROB_FO'] }}</td>
                                <td>@{{ prevData['ROB_DO'] }}</td>
                                <td>@{{ prevData['BUNK_FO'] }}</td>
                                <td>@{{ prevData['BUNK_DO'] }}</td>
                                <td>@{{ prevData['Remark'] }}</td>
                            </tr>
                            <template v-for="(currentItem, index) in currentData">
                                <tr>
                                    <td class="d-none"><input type="hidden" :value="currentItem.id" name="id[]"></td>
                                    <td><input type="text" readonly  v-model="activeVoy" name="CP_ID[]" class="form-control"></td>
                                    <td><input type="text" class="date-picker form-control" name="lay_date" v-model="currentItem.Voy_Date" @click="dateModify($event, index)" data-date-format="yyyy-mm-dd"></td>
                                    <td><input type="number" class="form-control" name="Voy_Hour[]" v-model="currentItem.Voy_Hour"></td>
                                    <td><input type="number" class="form-control" name="Voy_Minute[]" v-model="currentItem.Voy_Minute"></td>
                                    <td><input type="number" class="form-control" name="GMT[]" v-model="currentItem.GMT"></td>
                                    <td>
                                        <select type="number" class="form-control" name="Voy_Status[]" v-model="currentItem.Voy_Status" @change="onChangeStatus($event, index)" style="width: 120px;">
                                            <option v-for="(item, index) in dynamicStatus" v-bind:value="index">@{{ item[0] }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select type="number" class="form-control" name="Voy_Type[]" v-model="currentItem.Voy_Type" style="width: 80px;">
                                            <option v-for="(item, index) in currentItem.dynamicSub" v-bind:value="item[0]">@{{ item[1] }}</option>
                                        </select>
                                    </td>
                                    <td><input type="text" maxlength="25" class="form-control" name="Ship_Position[]" v-model="currentItem.Ship_Position"></td>
                                    <td><input type="number" max="100000" class="form-control" name="Sail_Distance[]" v-model="currentItem.Sail_Distance"></td>
                                    <td><input type="number" class="form-control" name="Speed[]" v-model="currentItem.Speed"></td>
                                    <td><input type="number" class="form-control" name="RPM[]" v-model="currentItem.RPM"></td>
                                    <td><input type="number" class="form-control" name="Cargo_Qtty[]" v-model="currentItem.Cargo_Qtty"></td>
                                    <td><input type="number" class="form-control" name="ROB_FO[]" v-model="currentItem.ROB_FO"></td>
                                    <td><input type="number" class="form-control" name="ROB_DO[]" v-model="currentItem.ROB_DO"></td>
                                    <td><input type="number" class="form-control" name="BUNK_FO[]" v-model="currentItem.BUNK_FO"></td>
                                    <td><input type="number" class="form-control" name="BUNK_DO[]" v-model="currentItem.BUNK_DO"></td>
                                    <td><input type="text" class="form-control" name="Remark[]" maxlength="50" v-model="currentItem.Remark" @focus="addRow"></td>
                                </tr>
                            </template>
                            <tr>
                                <td class="text-center" rowspan="2">航次</td>
                                <td class="text-center" rowspan="2">报告次</td>
                                <td class="text-center" rowspan="2" colspan="2">时间</td>
                                <td class="text-center" rowspan="2">航次用时</td>
                                <td class="text-center" rowspan="2">距离<br>[NM]</td>
                                <td class="text-center" rowspan="2">平均<br>速度</td>
                                <td class="text-center">经济天</td>
                                <td class="text-center">48.7%</td>
                                <td class="text-center" colspan="2">总消耗</td>
                                <td class="text-center" colspan="2">加油量</td>
                                <td class="text-center" colspan="2">标准消耗</td>
                                <td class="text-center" colspan="2">-节约/+超过</td>
                            </tr>
                            <tr>
                                <td class="text-center">航次</td>
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
                            <tr>
                                <td>@{{ activeVoy }}</td>
                                <td>@{{ this.currentData.length }}</td>
                                <td colspan="2">@{{ sail_term['min_date'] }} ~ @{{ sail_term['max_date'] }}</td>
                                <td>@{{ sail_time }}</td>
                                <td>@{{ total_distance }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                                <td>@{{ average_speed }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
            <!-- Main Contents End -->
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
        var DYNAMIC_SUB_SALING = '{!! DYNAMIC_SUB_SALING !!}';
        var DYNAMIC_SAILING = '{!! DYNAMIC_SAILING !!}';
        var DYNAMIC_CMPLT_DISH = '{!! DYNAMIC_CMPLT_DISH !!}';
        const DAY_UNIT = 1000 * 3600;
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

                    prevData: [],
                    currentData: {

                    },

                    dynamicStatus: DynamicStatus,

                    sail_term: {
                        min_date: '0000-00-00',
                        max_date: '0000-00-00',
                    },
                    sail_time: 0,
                    total_distance: 0,
                    average_speed: 0
                },
                init: function() {
                    this.changeShip();
                },
                methods: {
                    changeShip: function(evt) {
                        location.href = '/business/dynRecord?shipId=' + $(evt.target).val();
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
                            },
                            success: function(result) {
                                searchObj.voy_list = [];
                                searchObj.voy_list = Object.assign([], [], result);
                            }
                        });
                    },
                    onChangeVoy: function(evt) {
                        this.setPortName();
                        this.getData();
                    },
                    getData: function() {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/dynamic/list',
                            type: 'post',
                            data: {
                                shipId: searchObj.shipId,
                                voyId: searchObj.activeVoy
                            },
                            success: function(result) {
                                let data = result;
                                searchObj.prevData = [];
                                searchObj.prevData = Object.assign([], [], data['prevData']);
                                searchObj.currentData = [];
                                searchObj.currentData = Object.assign([], [], data['currentData']);
                                searchObj.currentData.forEach(function(value, key) {
                                    searchObj.currentData[key]['dynamicSub'] = getSubList(value['Voy_Status']);
                                    searchObj.total_distance += value["Sail_Distance"];
                                });


                                searchObj.setTotalInfo(data);

                            }
                        })
                    },
                    setTotalInfo: function(data) {
                        searchObj.sail_term['min_date'] = data['min_date'] == false ? '' : data['min_date']['Voy_Date'];
                        searchObj.sail_term['max_date'] = data['max_date'] == false ? '' : data['max_date']['Voy_Date'];
                        let currentDate = moment(data['max_date']['Voy_Date'] + ' ' + data['max_date']['Voy_Hour'] + ':' + data['max_date']['Voy_Minute']).valueOf();
                        let currentGMT = DAY_UNIT * data['max_date']['GMT'];
                        let prevDate = moment(data['min_date']['Voy_Date'] + ' ' + data['min_date']['Voy_Hour'] + ':' + data['min_date']['Voy_Minute']).valueOf();
                        let prevGMT = DAY_UNIT * data['min_date']['GMT'];
                        let diffDay = 0;
                        console.log(currentDate, prevDate);
                        currentDate = BigNumber(currentDate).minus(currentGMT).div(DAY_UNIT);
                        prevDate = BigNumber(prevDate).minus(prevGMT).div(DAY_UNIT);
                        diffDay = currentDate.minus(prevDate);
                        console.log(currentDate.toNumber(), prevDate.toNumber());
                        this.sail_time = diffDay.div(24).toFixed(2);

                        this.average_speed = BigNumber(this.total_distance).div(10).toFixed(2)
                        

                    },
                    setPortName: function() {
                        searchObj.voy_list.forEach(function(value, index) {
                            if(searchObj.activeVoy == value['Voy_No']) {
                                searchObj.port['loading'] = value['LPort'];
                                searchObj.port['discharge'] = value['DPort'];
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
                    },
                    submitForm: function() {
                        $('#dynamic-form').submit();
                    },
                    addRow: function() {
                        let length = searchObj.currentData.length;
                        searchObj.currentData.push([]);
                        searchObj.currentData[length]['Voy_Status'] = DYNAMIC_SAILING;
                        searchObj.currentData[length]['Voy_Type'] = DYNAMIC_SUB_SALING;
                        searchObj.currentData[length]['dynamicSub'] = getSubList(DYNAMIC_SAILING);
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
                    if(searchObj.voy_list.length > 0) {
                        searchObj.activeVoy = searchObj.voy_list[0]['Voy_No'];
                    }

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
    </script>

@endsection