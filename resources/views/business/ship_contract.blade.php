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

        <div class="page-content">
            <div class="row">
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
                            <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;">
                                @foreach($shipList as $ship)
                                    <option value="{{ $ship['IMO_No'] }}"
                                            {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                    </option>
                                @endforeach
                            </select>
                            @if(isset($shipName['shipName_En']))
                                <strong class="f-right" style="font-size: 16px; padding-top: 6px;">"<span id="ship_name">{{ $shipName['shipName_En'] }}</span>" CERTIFICATES</strong>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <div class="btn-group f-right">
                                <button class="btn btn-primary btn-sm search-btn" onclick="addNewItem()"><i class="icon-plus"></i>添加</button>
                                <button class="btn btn-warning btn-sm excel-btn d-none"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                                <a href="#modal-wizard" class="only-cargo-modal-show d-none" role="button" data-toggle="modal"></a>
                                <a href="#modal-port-wizard" class="only-port-modal-show d-none" role="button" data-toggle="modal"></a>
                                @if(!$isHolder)
                                    <button class="btn btn-sm btn-warning" id="submit">
                                        <i class="icon-save"></i>保存
                                    </button>
                                @endif
                                <button type="button" class="btn btn-success btn-sm d-none" onclick="reportSubmit">
                                    <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">申请
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 4px;">
                    <div class="col-lg-12">
                        <div class="head-fix-div d-line-height" style="height: 122px;">
                                <table id="voy_list" v-cloak>
                                    <thead class="">
                                    <tr>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_no') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.voy_no') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_tc') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.voy_tc') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cp_date') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.cp_date') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cargo') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.cargo') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.qty') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.qty') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.loading_port') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.loading_port') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.discharge_port') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.discharge_port') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.l_rate') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.l_rate') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cargo') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.d_rate') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.frt_rate') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.frt_rate') !!}</span></th>
                                        <th class="text-center style-header" style="width: 60px;">{!! trans('business.table.cn.anticipate') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.contract_attach') !!}</th>
                                        <th class="text-center style-header" rowspan="2" style="width:20px;word-break: break-all;">{!! trans('common.label.delete') !!}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center style-header" style="width:20px;word-break: break-all; border-top: unset!important;">{!! trans('business.table.cn.daily_profit') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in list">
                                            <td class="text-center">@{{ item.Voy_No }}</td>
                                            <td class="text-center">@{{ item.CP_kind }}</td>
                                            <td class="text-center">@{{ item.CP_Date }}</td>
                                            <td class="text-left"><div class="fixed-td">@{{ getCargoName(item.Cargo) }}</div></td>
                                            <td class="text-center">@{{ item.Cgo_Qtty }}</td>
                                            <td class="text-center"><div class="fixed-td">@{{ getPortName(item.LPort) }}</div></td>
                                            <td class="text-center"><div class="fixed-td">@{{ getPortName(item.DPort) }}</div></td>
                                            <td class="text-center">@{{ item.L_Rate }}</td>
                                            <td class="text-center">@{{ item.D_Rate }}</td>
                                            <td class="text-center">@{{ item.Freight }}</td>
                                            <td class="text-center">@{{ item.daily_net_profit }}</td>
                                            <td class="text-center">@{{ item.daily_net_profit }}</td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <a class="red" @click="deleteItem(item.id)">
                                                        <i class="icon-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    
                                    </tbody>
                                </table>
                        </div>

                        <ul class="nav nav-tabs ship-register">
                            <li class="active">
                                <a data-toggle="tab" href="#voy_contract_div" onclick="changeTab('voy')">
                                    程租<span style="font-style: italic;">(VOY)</span>
                                </a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tc_contract_div" onclick="changeTab('tc')">
                                    期租<span style="font-style: italic;">(TC)</span>
                                </a>
                            </li>
                            <li>
                                <div class="alert alert-block alert-success center visuallyhidden">
                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                    <strong id="msg-content"> Please register a new ship contract.</strong>
                                </div>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="voy_contract_div" class="tab-pane active">
                                @include('business.ship_voy_contract')
                            </div>
                            <div id="tc_contract_div" class="tab-pane">
                                @include('business.ship_tc_contract')
                            </div>
                        </div>

                        <div id="modal-wizard" class="modal modal-draggable" aria-hidden="true" style="display: none; margin-top: 15%;">
                            <div class="dynamic-modal-dialog">
                                <div class="dynamic-modal-content" style="border: 0;">
                                    <div class="dynamic-modal-header" data-target="#modal-step-contents">
                                        <div class="table-header">
                                            <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                                <span class="white">&times;</span>
                                            </button>
                                            船舶证书种类登记
                                        </div>
                                    </div>
                                    <div id="cargo_list_div" class="dynamic-modal-body step-content">
                                        <div class="row">
                                            <form action="saveCargoList" method="post" id="cargoListForm">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="head-fix-div col-md-12" style="height:300px;">
                                                    <table class="table-bordered rank-table">
                                                        <thead>
                                                        <tr class="rank-tr" style="background-color: #c9dfff;height:18px;">
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:80%">Name</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff; width: 20%;"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="rank-table">
                                                        <tr class="no-padding center" v-for="(item, index) in list">
                                                            <td class="d-none">
                                                                <input type="hidden" name="id[]" v-model="item.id">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="name[]" v-model="item.name" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <div class="action-buttons">
                                                                    <a class="red" @click="deleteItem(item.id)"><i class="icon-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                            <div class="row">
                                                <div class="btn-group f-right mt-20 d-flex">
                                                    <button type="button" class="btn btn-success small-btn ml-0" @click="ajaxFormSubmit">
                                                        <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                                    </button>
                                                    <div class="between-1"></div>
                                                    <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="modal-port-wizard" class="modal modal-draggable" aria-hidden="true" style="display: none; margin-top: 15%;">
                            <div class="dynamic-modal-dialog">
                                <div class="dynamic-modal-content" style="border: 0;">
                                    <div class="dynamic-modal-header" data-target="#modal-step-contents">
                                        <div class="table-header">
                                            <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                                <span class="white">&times;</span>
                                            </button>
                                            船舶证书种类登记
                                        </div>
                                    </div>
                                    <div id="port_list_div" class="dynamic-modal-body step-content">
                                        <div class="row">
                                            <form action="savePortList" method="post" id="portListForm">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="head-fix-div col-md-12" style="height:300px;">
                                                    <table class="table-bordered rank-table">
                                                        <thead>
                                                        <tr class="rank-tr" style="background-color: #c9dfff;height:18px;">
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:40%">Port Name</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:40%">Country Code</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff; width: 20%;"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="rank-table">
                                                        <tr class="no-padding center" v-for="(item, index) in list">
                                                            <td class="d-none">
                                                                <input type="hidden" name="id[]" v-model="item.id">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="Port_En[]" v-model="item.Port_En" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="Port_Cn[]" v-model="item.Port_Cn" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <div class="action-buttons">
                                                                    <a class="red" @click="deleteItem(item.id)"><i class="icon-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                            <div class="row">
                                                <div class="btn-group f-right mt-20 d-flex">
                                                    <button type="button" class="btn btn-success small-btn ml-0" @click="ajaxFormSubmit">
                                                        <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                                    </button>
                                                    <div class="between-1"></div>
                                                    <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
	echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
	echo '</script>';
	?>

    <script>
        var ship_id = '{!! $shipId !!}';
        var ACTIVE_TAB = 'voy';
        var isChangeStatus = false;
        var cargoListObj = null;
        var portListObj = null;
        var voyListObj = null;

        var voyInputObjTmp = new Array();
        var voyContractObjTmp = new Array();
        var tcInputObjTmp = new Array();
        var tcContractObjTmp = new Array();

        var submitted = false;

        $("form").submit(function() {
            submitted = true;
        });


        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';

            if(ACTIVE_TAB == 'voy') {
                let currentObj = JSON.parse(JSON.stringify(voyInputObj.input));
                if(JSON.stringify(voyInputObjTmp) != JSON.stringify(currentObj))
                    isChangeStatus = true;
                else
                    isChangeStatus = false;
            } else {
                let currentObj = JSON.parse(JSON.stringify(tcInputObj.input));
                if(JSON.stringify(tcInputObjTmp) != JSON.stringify(currentObj))
                    isChangeStatus = true;
                else
                    isChangeStatus = false;
            }

            if (!submitted && isChangeStatus) {
                (e || window.event).returnValue = confirmationMessage;
            }

            return confirmationMessage;
        });

        Vue.component('my-currency-input', {
            props: ["value", "fixednumber", 'prefix', 'type'],
            template: `
                    <input type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true" @change="calcPreview"/>
                `,
            data: function() {
                return {
                    isInputActive: false
                }
            },
            computed: {
                displayValue: {
                    get: function() {
                        if (this.isInputActive) {
                            // Cursor is inside the input field. unformat display value for user
                            return this.value.toString()
                        } else {
                            let fixedLength = 2;
                            let prefix = '$ ';
                            if(this.fixednumber != undefined)
                                fixedLength = this.fixednumber;

                            if(this.prefix != undefined)
                                prefix = this.prefix;
                                
                            return prefix + this.value.toFixed(fixedLength).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                        }
                    },
                    set: function(modifiedValue) {
                        let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))

                        if (isNaN(newValue)) {
                            newValue = 0
                        }

                        this.$emit('input', newValue)
                    },
                }
            },
            methods: {
                calcPreview: function() {
                    if(this.type == 'tc')
                        tcInputObj.calcContractPreview();
                    else
                        voyInputObj.calcContractPreview();
                }
            }
        });

        $(function() {
            initialize();
            initializeVoy();
            initializeTc();
        });

        function initialize() {
            $('.alert').toggleClass('visuallyhidden');
                setTimeout(function() {
                    $('.alert').toggleClass('visuallyhidden');
                }, 2000);

            voyListObj = new Vue({
                el: '#voy_list',
                data: {
                    list: [],
                    shipId: ship_id,
                },
                filter: {
                    getCargoName: function(ids) {
                        let tmp = ids.split(',');
                        let tmpStr = '';
                        cargoListObj.list.forEach(function(value, key) {
                            tmp.forEach(function(tmpValue) {
                                if(tmpValue == value['id'])
                                    tmpStr += value['name'] + ', ';
                            })
                        });
                        
                        return tmpStr;
                    }
                },
                methods: {
                    getVoyList: function() {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/cp/list',
                            type: 'post',
                            data: {
                                shipId: this.shipId,
                            },
                            success: function(data, status, xhr) {
                                voyListObj.list = data;
                            }
                        })
                    },
                    getCargoName: function(ids) {
                        if(ids == '' || ids == undefined) return '';
                        let tmp = ids.split(',');
                        let tmpStr = '';
                        if(tmp.length <= 1) {
                            cargoListObj.list.forEach(function(value, key) {
                                if(tmp[0] == value['id']) {
                                    tmpStr = value['name'];
                                }
                            });

                            return tmpStr;
                        } else {
                            cargoListObj.list.forEach(function(value, key) {
                                tmp.forEach(function(tmpValue) {
                                    if(tmpValue == value['id'])
                                        tmpStr += value['name'] + ', ';
                                })
                            });

                            return tmpStr.slice(0,-2);
                        }
                    },
                    getPortName: function(ids) {
                        if(ids == '' || ids == undefined) return '';
                        let tmp = ids.split(',');
                        let tmpStr = '';
                        if(tmp.length <= 1) {
                            portListObj.list.forEach(function(value, key) {
                                if(tmp[0] == value['id']) {
                                    tmpStr = value['Port_En'] + '(' + value['Port_Cn'] + ')';
                                }
                            });

                            return tmpStr;
                        } else {
                            portListObj.list.forEach(function(value, key) {
                                tmp.forEach(function(tmpValue) {
                                    if(tmpValue == value['id'])
                                        tmpStr += value['Port_En'] + '(' + value['Port_Cn'] + ')' + ', ';
                                })
                            });

                            return tmpStr.slice(0,-2);
                        }
                    },
                    deleteItem: function(id) {
                        alertAudio();
                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                                isChangeStatus = true;
                                $.ajax({
                                    url: BASE_URL + 'ajax/business/cp/delete',
                                    type: 'post',
                                    data: {
                                        id: id,
                                    },
                                    success: function(data, status, xhr) {
                                        voyListObj.list = data;
                                    }
                                })
                            }
                        });
                    }
                },
                mounted() {
                    this.getVoyList();
                },
            });

            cargoListObj = new Vue({
            el: '#cargo_list_div',
            data: {
                list:       []
            },
            methods: {
                deleteItem(index) {
                    if(index == undefined || index == '')
                        return false;

                    alertAudio();
                    bootbox.confirm("Are you sure you want to delete?", function (result) {
                        if (result) {
                            isChangeStatus = true;
                            $.ajax({
                                url: BASE_URL + 'ajax/business/cargo/delete',
                                type: 'post',
                                data: {
                                    id: index
                                },
                                success: function(data) {
                                    let result = data;
                                    cargoListObj.list = [];
                                    tcContractObj.cargoList = [];
                                    tcContractObj.cargoList = Object.assign([], [], result);
                                    voyContractObj.cargoList = [];
                                    voyContractObj.cargoList = Object.assign([], [], result);
                                    cargoListObj.list = Object.assign([], [], result);
                                    cargoListObj.list.push([]);
                                }
                            })
                        }
                    });
                },
                ajaxFormSubmit() {
                    let form = $('#cargoListForm').serialize();
                    $.post('saveCargoList', form).done(function (data) {
                        let result = data;
                        cargoListObj.list = [];
                        tcContractObj.cargoList = [];
                        tcContractObj.cargoList = Object.assign([], [], result);
                        voyContractObj.cargoList = [];
                        voyContractObj.cargoList = Object.assign([], [], result);
                        cargoListObj.list = Object.assign([], [], result);
                        cargoListObj.list.push([]);
                        $('.close').click();
                    });
                },
                addNewRow(e) {
                    isChangeStatus = true;
                    cargoListObj.list.push([]);
                }
            }

        })


        portListObj = new Vue({
            el: '#port_list_div',
            data: {
                list: []
            },
            methods: {
                deleteItem(index) {
                    if(index == undefined || index == '')
                        return false;

                    alertAudio();
                    bootbox.confirm("Are you sure you want to delete?", function (result) {
                        if (result) {
                            isChangeStatus = true;
                            $.ajax({
                                url: BASE_URL + 'ajax/business/port/delete',
                                type: 'post',
                                data: {
                                    id: index
                                },
                                success: function(data) {
                                    let result = data;
                                    portListObj.list = [];
                                    voyContractObj.portList = [];
                                    voyContractObj.portList = Object.assign([], [], result);
                                    tcContractObj.portList = [];
                                    tcContractObj.portList = Object.assign([], [], result);
                                    portListObj.list = Object.assign([], [], result);
                                    portListObj.list.push([]);
                                }
                            })
                        }});
                },
                ajaxFormSubmit() {
                    let form = $('#portListForm').serialize();
                    $.post('savePortList', form).done(function (data) {
                        let result = data;
                        portListObj.list = [];
                        voyContractObj.portList = [];
                        voyContractObj.portList = Object.assign([], [], result);
                        tcContractObj.portList = [];
                        tcContractObj.portList = Object.assign([], [], result);
                        portListObj.list = Object.assign([], [], result);
                        portListObj.list.push([]);
                        $('.close').click();
                    });
                },
                addNewRow(e) {
                    portListObj.list.push([]);
                }
            }
        });

        getInitInfo(ship_id);
        }

        function getInitInfo(ship_id) {
        $.ajax({
            url: BASE_URL + 'ajax/business/contract/info',
            type: 'post',
            data: {
                shipId: ship_id
            },
            success: function(data, status, xhr) {
                let shipInfo = data['shipInfo'];
                let portList = data['portList'];
                let cargoList = data['cargoList'];
                voyInputObj.input['fo_sailing'] = shipInfo['FOSailCons_S'];
                voyInputObj.input['do_sailing'] = shipInfo['DOSailCons_S'];
                voyInputObj.input['fo_up_shipping'] = shipInfo['FOL/DCons_S'];
                voyInputObj.input['do_up_shipping'] = shipInfo['DOL/DCons_S'];
                voyInputObj.input['fo_waiting'] = shipInfo['FOIdleCons_S'];
                voyInputObj.input['do_waiting'] = shipInfo['DOIdleCons_S'];

                tcInputObj.input['fo_sailing'] = shipInfo['FOSailCons_S'];
                tcInputObj.input['do_sailing'] = shipInfo['DOSailCons_S'];
                tcInputObj.input['fo_up_shipping'] = shipInfo['FOL/DCons_S'];
                tcInputObj.input['do_up_shipping'] = shipInfo['DOL/DCons_S'];
                tcInputObj.input['fo_waiting'] = shipInfo['FOIdleCons_S'];
                tcInputObj.input['do_waiting'] = shipInfo['DOIdleCons_S'];
                
                voyContractObj.portList = Object.assign([], [], portList);
                tcContractObj.portList = Object.assign([], [], portList);

                voyContractObj.cargoList = Object.assign([], [], cargoList);
                tcContractObj.cargoList = Object.assign([], [], cargoList);

                cargoListObj.list = Object.assign([], [], cargoList);
                cargoListObj.list.push([]);

                portListObj.list = Object.assign([], [], portList);
                portListObj.list.push([]);

                voyInputObjTmp = JSON.parse(JSON.stringify(voyInputObj.input));
                tcInputObjTmp = JSON.parse(JSON.stringify(tcInputObj.input));
            }
        });
    }

    function alertAudio() {
        document.getElementById('warning-audio').play();
    }

    function changeTab(type) {
        ACTIVE_TAB = type;
    }

    $('#select-ship').on('change', function() {
        let ship_id = $(this).val();
        location.href = '/business/contract?shipId=' + ship_id;
    });

    function addNewItem() {
        location.href = '/business/contract?shipId=' + ship_id;
    }

    $('#submit').on('click', function(e) {
        if(ACTIVE_TAB == 'voy') {
            if(voyContractObj.validate_voy_no == true && voyContractObj.voy_no != '') {
                submitted = true;
                $('#voyContractForm').submit();
            } else {
                alert('Please input data correclty.');
                return false;
            }
        } else {
            if(tcContractObj.validate_voy_no == true && tcContractObj.voy_no != '') {
                submitted = true;
                $('#tcContractForm').submit();
            } else {
                alert('Please input data correclty.');
                return false;
            }
        }
    });

    </script>

@endsection