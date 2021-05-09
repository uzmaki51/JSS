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
                                <a onclick="javascript:fnExcelReport();" class="btn btn-warning btn-sm excel-btn">
                                    <i class="icon-table"></i>{{ trans('common.label.excel') }}
                                </a>                                
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 4px;">
                    <div class="col-lg-12">
                        <div class="head-fix-div d-line-height" id="voy_div" style="height: 129px;">
                                <table id="voy_list" v-cloak>
                                    <thead id="list-header" class="">
                                    <tr>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_no') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.voy_no') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_tc') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.voy_tc') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cp_date') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.cp_date') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cargo') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.cargo') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.qty') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.qty') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.loading_port') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.loading_port') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.discharge_port') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.discharge_port') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.l_rate') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.l_rate') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.d_rate') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.d_rate') !!}</span></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.frt_rate') !!}<br><span class="style-bold-italic">{!! trans('business.table.en.frt_rate') !!}</span></th>
                                        <th class="text-center style-header lr-no-p" style="width: 60px;"><div class="horizontal-line"><span>{!! trans('business.table.cn.anticipate') !!}</span><span>{!! trans('business.table.cn.daily_profit') !!}</span></div></th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.contract_attach') !!}</th>
                                        <th class="text-center style-header" rowspan="2" style="width:20px;word-break: break-all;"><!--{!! trans('common.label.delete') !!}--></th>
                                    </tr>
                                    </thead>
                                    <tbody id="list-body">
                                        @if(isset($voy_id))
                                        <tr class="contract-item" v-bind:class="item.id == {{$voy_id}} ? 'selected' : ''" v-for="(item, index) in list" v-bind:data-index="item.id">
                                        @else
                                        <tr class="contract-item" v-for="(item, index) in list" v-bind:data-index="item.id">
                                        @endif
                                            <td class="text-center">@{{ item.Voy_No }}</td>
                                            <td class="text-center">@{{ item.CP_kind }}</td>
                                            <td class="text-center">@{{ item.CP_Date }}</td>
                                            <td class="text-left"><div class="fixed-td">@{{ getCargoName(item.Cargo) }}</div></td>
                                            <td class="text-center">@{{ item.Cgo_Qtty }}</td>
                                            <td class="text-center"><div class="fixed-td">@{{ getPortName(item.LPort) }}</div></td>
                                            <td class="text-center"><div class="fixed-td">@{{ getPortName(item.DPort) }}</div></td>
                                            <td class="text-center">@{{ item.L_Rate }}</td>
                                            <td class="text-center">@{{ item.D_Rate }}</td>
                                            <td class="text-center">@{{ getFrtRate(item.Freight, item.total_Freight) }}</td>
                                            <td class="text-center">@{{ item.net_profit_day }}</td>
                                            <td class="text-center">
                                                <a :href="item.attachment_url" target="_blank" v-bind:class="[item.is_attachment == 1 ? '' : 'd-none']">
                                                    <img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15">
                                                </a>
                                            </td>
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
        var voy_id = '{!! $voy_id !!}';
        var is_scrolled = false;
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
        var state = '@if(isset($status)){{$status}}@endif';

        $("form").submit(function() {
            submitted = true;
        });

        $('.head-fix-div').on('click', 'tr', function(evt) {
            let cell = $(evt.target).closest('td');
            if(!$(this).hasClass('contract-item')) return false;
            if(cell.index() < 11) {
                if($(this).hasClass('selected'))
                    return;

                let voy_id = $(this).attr('data-index');
                location.href = BASE_URL + 'business/contract?shipId=' + ship_id + '&voy_id=' + voy_id;
            }
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
                    <input type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true; $event.target.select()" @change="calcPreview" v-on:keyup="keymonitor" />
                `,
            data: function() {
                return {
                    isInputActive: false
                }
            },

            computed: {
                displayValue: {
                    get: function() {
                        this.setFocus;
                        if (this.isInputActive) {
                            if(isNaN(this.value))
                                return 0;

                            return this.value.toString();
                        } else {
                            let fixedLength = 2;
                            let prefix = '$ ';
                            if(this.fixednumber != undefined)
                                fixedLength = this.fixednumber;

                            if(this.prefix != undefined)
                                prefix = this.prefix + ' ';
                            
                            return prefix + number_format(this.value, fixedLength);
                        }
                    },
                    set: function(modifiedValue) {
                        if (isNaN(modifiedValue)) {
                            modifiedValue = 0
                        }

                        this.$emit('input', parseFloat(modifiedValue))
                        this.setFocus
                    },
                }
            },
            methods: {
                calcPreview: function(e) {
                    let fixedLength = 2;
                    let prefix = '$ ';
                    if(this.fixednumber != undefined)
                        fixedLength = this.fixednumber;

                    if(this.prefix != undefined)
                        prefix = this.prefix;
                    this.$emit('input', parseFloat(this.value ,10).toFixed(fixedLength));
                    if(this.type == 'tc')
                        tcInputObj.calcContractPreview();
                    else
                        voyInputObj.calcContractPreview();

                },
                keymonitor: function(e) {
                    if(e.keyCode == 9 || e.keyCode == 13)
                        $(e.target).select()
                },
                setValue: function() {

                }
            },
            watch: {
                setFocus: function(e) {
                    $(e.target).select();
                }
            }
        });

        $(function() {
            initialize();
            initializeVoy();
            initializeTc();

            if(state == 'error') {
                $.gritter.add({
                    title: '错误',
                    text: 'Voy_No不可以重复了!',
                    class_name: 'gritter-error'
                });
            }
        });

        function initialize() {
            if (voy_id < 1) {
                $('.alert').toggleClass('visuallyhidden');
                    setTimeout(function() {
                        $('.alert').toggleClass('visuallyhidden');
                    }, 2000);
            }

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
                    getFrtRate: function(a, b) {
                        console.log(a, b)
                        return parseFloat(a) == 0 || a == undefined ? b : a;
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
                        __alertAudio();
                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                                isChangeStatus = true;
                                $.ajax({
                                    url: BASE_URL + 'ajax/business/cp/delete',
                                    type: 'post',
                                    data: {
                                        id: id,
                                        shipId: ship_id,
                                    },
                                    success: function(data, status, xhr) {
                                        voyListObj.list = data;
                                    }
                                })
                            }
                        });
                    }
                },
                updated() {
                    if (!is_scrolled) {
                        if (voy_id > 0) {
                            var row = $(".contract-item.selected");
                            var headrow = $('#list-header');
                            $('#voy_div').scrollTop(row.position().top - headrow.innerHeight());
                        }
                        is_scrolled = true;
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

                    __alertAudio();
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

                    __alertAudio();
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
                voyContractObj.portList = Object.assign([], [], portList);
                tcContractObj.portList = Object.assign([], [], portList);

                voyContractObj.cargoList = Object.assign([], [], cargoList);
                tcContractObj.cargoList = Object.assign([], [], cargoList);

                cargoListObj.list = Object.assign([], [], cargoList);
                cargoListObj.list.push([]);

                portListObj.list = Object.assign([], [], portList);
                portListObj.list.push([]);

                var index = $('table > tbody> tr.selected').index();
                if (index >= 0)
                {
                    if (voyListObj.list[index].CP_kind == "VOY")
                    {
                        voyInputObj.input["speed"] = voyListObj.list[index].speed;
                        voyInputObj.input["distance"] = voyListObj.list[index].distance;

                        voyInputObj.input["up_ship_day"] = voyListObj.list[index].up_ship_day;
                        voyInputObj.input["down_ship_day"] = voyListObj.list[index].down_ship_day;
                        voyInputObj.input["wait_day"] = voyListObj.list[index].wait_day;
                        voyInputObj.input["fo_sailing"] = voyListObj.list[index].fo_sailing;
                        voyInputObj.input["fo_up_shipping"] = voyListObj.list[index].fo_up_shipping;
                        voyInputObj.input["fo_waiting"] = voyListObj.list[index].fo_waiting;
                        voyInputObj.input["fo_price"] = voyListObj.list[index].fo_price;
                        voyInputObj.input["do_sailing"] = voyListObj.list[index].do_sailing;
                        voyInputObj.input["do_up_shipping"] = voyListObj.list[index].do_up_shipping;
                        voyInputObj.input["do_waiting"] = voyListObj.list[index].do_waiting;
                        voyInputObj.input["do_price"] = voyListObj.list[index].do_price;
                        voyInputObj.input["cargo_amount"] = voyListObj.list[index].cargo_amount;
                        if (voyListObj.list[index].batch_manage == 1) {
                            voyInputObj.batchStatus = true;
                            voyInputObj.input["batch_price"] = voyListObj.list[index].batch_price;
                        }
                        else
                        {
                            voyInputObj.batchStatus = false;
                            voyInputObj.input["freight_price"] = voyListObj.list[index].freight_price;
                        }
                        voyInputObj.input["fee"] = voyListObj.list[index].fee;
                        voyInputObj.input["up_port_price"] = voyListObj.list[index].up_port_price;
                        voyInputObj.input["down_port_price"] = voyListObj.list[index].down_port_price;
                        voyInputObj.input["cost_per_day"] = voyListObj.list[index].cost_per_day;
                        voyInputObj.input["cost_else"] = voyListObj.list[index].cost_else;
                        voyInputObj.input["currency"] = voyListObj.list[index].currency;
                        voyInputObj.input["rate"] = voyListObj.list[index].rate;

                        voyContractObj.voy_no = voyListObj.list[index].Voy_No;
                        voyContractObj.cp_date = voyListObj.list[index].CP_Date;
                        
                        voyContractObj.cargo = voyListObj.list[index].Cargo;
                        voyContractObj.cargoNames = voyListObj.getCargoName(voyListObj.list[index].Cargo);
                        voyContractObj.cargoIDList = voyListObj.list[index].Cargo;

                        voyContractObj.qty_amount = voyListObj.list[index].Cgo_Qtty;
                        voyContractObj.qty_type = voyListObj.list[index].Qtty_Type;
                        
                        voyContractObj.up_port = voyListObj.list[index].LPort;
                        voyContractObj.upPortNames = voyListObj.getPortName(voyListObj.list[index].LPort);
                        voyContractObj.upPortIDList = voyListObj.list[index].LPort;
                        voyContractObj.down_port = voyListObj.list[index].DPort;
                        voyContractObj.downPortNames = voyListObj.getPortName(voyListObj.list[index].DPort);
                        voyContractObj.downPortIDList = voyListObj.list[index].DPort;

                        voyContractObj.lay_date = voyListObj.list[index].LayCan_Date1;
                        voyContractObj.can_date = voyListObj.list[index].LayCan_Date2;
                        voyContractObj.load_rate = voyListObj.list[index].L_Rate;
                        voyContractObj.disch_rate = voyListObj.list[index].D_Rate;
                        voyContractObj.freight_rate = voyListObj.list[index].Freight;
                        voyContractObj.lumpsum = voyListObj.list[index].total_Freight;
                        voyContractObj.deten_fee = voyListObj.list[index].deten_fee;
                        voyContractObj.dispatch_fee = voyListObj.list[index].dispatch_fee;
                        voyContractObj.com_fee = voyListObj.list[index].com_fee;
                        voyContractObj.charterer = voyListObj.list[index].charterer;
                        voyContractObj.tel_number = voyListObj.list[index].tel_number;
                        voyContractObj.remark = voyListObj.list[index].Remarks;
                        if (voyListObj.list[index].is_attachment == 1) {
                            voyContractObj.fileName = voyListObj.list[index].attachment_url.substr(voyListObj.list[index].attachment_url.lastIndexOf("contract/")+9);
                        }

                        voyInputObj.calcContractPreview();
                        
                        $($('.ship-register li')[1]).removeClass('active');
                        $($('.ship-register li')[0]).addClass('active');
                        $('#voy_contract_div').addClass('active');
                        $('#tc_contract_div').removeClass('active');
                        changeTab('voy');
                    }
                    else
                    {
                        tcInputObj.input["speed"] = voyListObj.list[index].speed;
                        tcInputObj.input["distance"] = voyListObj.list[index].distance;

                        tcInputObj.input["up_ship_day"] = voyListObj.list[index].up_ship_day;
                        tcInputObj.input["down_ship_day"] = voyListObj.list[index].down_ship_day;
                        tcInputObj.input["wait_day"] = voyListObj.list[index].wait_day;
                        tcInputObj.input["fo_sailing"] = voyListObj.list[index].fo_sailing;
                        tcInputObj.input["fo_up_shipping"] = voyListObj.list[index].fo_up_shipping;
                        tcInputObj.input["fo_waiting"] = voyListObj.list[index].fo_waiting;
                        tcInputObj.input["fo_price"] = voyListObj.list[index].fo_price;
                        tcInputObj.input["do_sailing"] = voyListObj.list[index].do_sailing;
                        tcInputObj.input["do_up_shipping"] = voyListObj.list[index].do_up_shipping;
                        tcInputObj.input["do_waiting"] = voyListObj.list[index].do_waiting;
                        tcInputObj.input["do_price"] = voyListObj.list[index].do_price;
                        
                        tcInputObj.input["daily_rent"] = voyListObj.list[index].cargo_amount;
                        tcInputObj.input["c_v_e"] = voyListObj.list[index].batch_price;
                        tcInputObj.input["ilohc"] = voyListObj.list[index].freight_price;
                        tcInputObj.input["fee"] = voyListObj.list[index].fee;

                        tcInputObj.input["up_port_price"] = voyListObj.list[index].up_port_price;
                        tcInputObj.input["down_port_price"] = voyListObj.list[index].down_port_price;
                        tcInputObj.input["cost_per_day"] = voyListObj.list[index].cost_per_day;
                        tcInputObj.input["cost_else"] = voyListObj.list[index].cost_else;
                        tcInputObj.input["currency"] = voyListObj.list[index].currency;
                        tcInputObj.input["rate"] = voyListObj.list[index].rate;

                        tcContractObj.voy_no = voyListObj.list[index].Voy_No;
                        tcContractObj.cp_date = voyListObj.list[index].CP_Date;
                        
                        tcContractObj.cargo = voyListObj.list[index].Cargo;
                        tcContractObj.cargoNames = voyListObj.getCargoName(voyListObj.list[index].Cargo);
                        tcContractObj.cargoIDList = voyListObj.list[index].Cargo;

                        tcContractObj.hire_duration = voyListObj.list[index].Cgo_Qtty;
                        
                        tcContractObj.up_port = voyListObj.list[index].LPort;
                        tcContractObj.upPortNames = voyListObj.getPortName(voyListObj.list[index].LPort);
                        tcContractObj.upPortIDList = voyListObj.list[index].LPort;
                        tcContractObj.down_port = voyListObj.list[index].DPort;
                        tcContractObj.downPortNames = voyListObj.getPortName(voyListObj.list[index].DPort);
                        tcContractObj.downPortIDList = voyListObj.list[index].DPort;

                        tcContractObj.lay_date = voyListObj.list[index].LayCan_Date1;
                        tcContractObj.can_date = voyListObj.list[index].LayCan_Date2;

                        tcContractObj.dely = voyListObj.list[index].L_Rate;
                        tcContractObj.redely = voyListObj.list[index].D_Rate;
                        tcContractObj.hire = voyListObj.list[index].Freight;
                        tcContractObj.first_hire = voyListObj.list[index].total_Freight;
                        tcContractObj.ilohc = voyListObj.list[index].ilohc;
                        tcContractObj.c_v_e = voyListObj.list[index].c_v_e;
                        tcContractObj.com_fee = voyListObj.list[index].com_fee;
                        tcContractObj.charterer = voyListObj.list[index].charterer;
                        tcContractObj.tel_number = voyListObj.list[index].tel_number;
                        tcContractObj.remark = voyListObj.list[index].Remarks;
                        if (voyListObj.list[index].is_attachment == 1) {
                            tcContractObj.fileName = voyListObj.list[index].attachment_url.substr(voyListObj.list[index].attachment_url.lastIndexOf("contract/")+9);
                        }

                        tcInputObj.calcContractPreview();
                        
                        $($('.ship-register li')[0]).removeClass('active');
                        $($('.ship-register li')[1]).addClass('active');
                        $('#voy_contract_div').removeClass('active');
                        $('#tc_contract_div').addClass('active');
                        changeTab('tc');
                    }
                }
                else
                {
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
                }
                voyInputObjTmp = JSON.parse(JSON.stringify(voyInputObj.input));
                tcInputObjTmp = JSON.parse(JSON.stringify(tcInputObj.input));
            }
        });
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
            if(/*voyContractObj.validate_voy_no == true && */ voyContractObj.voy_no != '' && voyContractObj.voy_no.length == 4) {
                submitted = true;
                $('#voyContractForm').submit();
            } else {
                alert('Please input VoyNo correctly.');
                $('[name=voy_no').focus();
                return false;
            }
        } else {
            if(/*tcContractObj.validate_voy_no == true && */ tcContractObj.voy_no != '' && tcContractObj.voy_no.length == 4) {
                submitted = true;
                $('#tcContractForm').submit();
            } else {
                alert('Please input VoyNo correctly.');
                $($('[name=voy_no')[1]).focus();
                return false;
            }
        }
    });

    function fnExcelReport() {
        if(ACTIVE_TAB == 'voy') {
            if(voyContractObj.validate_voy_no == true && voyContractObj.voy_no != '')
                fnExcelVoy();
        } else {
            if(tcContractObj.validate_voy_no == true && tcContractObj.voy_no != '') 
                fnExcelTc();
        }        
    }

    $('body').on('keydown', 'input, select', function(e) {
        if (e.key === "Enter") {
            var self = $(this), form, focusable, next;
            if(ACTIVE_TAB == 'voy') {
                form = $('#voy_contract_div');
            }
            else {
                form = $('#tc_contract_div');
            }
            focusable = form.find('input,a,select,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this)+1);
            if (next.length) {
                next.focus();
            }
            return false;
        }
    });

    function fnExcelTc()
    {
        var tab_text;
        tab_text = '<table id="excel" style="text-align:center;vertical-align:middle;">';
        tab_text += '<tbody>';
        tab_text += '<tr><td colspan="8" style="font-size:20px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:hidden;text-align:left;vertical-align:middle;"><b>合同分析</b></td></tr>';
        tab_text += '<tr><td colspan="8" style="font-size:18px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:hidden;text-align:left;vertical-align:middle;"></td></tr>';
        tab_text += '<tr><td colspan="8" style="font-size:16px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:1px solid #717171;text-align:left;vertical-align:middle;">合同扼要 <span style="font-style:italic;">CP MAIN TERMS</span></td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:SimSun;">';
        tab_text += '<td style="width:100px;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航次' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.voy_no + '</td>';
        tab_text += '<td style="width:100px;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '交船地点' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=dely]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '合同日期' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.cp_date + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '还船地点' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=redely]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '合同种类' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;"><b>' + 'TC' + '</b></td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '日租金' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">$ ' + $('[name=hire]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '货名' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.cargoNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '首付金' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=first_hire]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '期租' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.hire_duration + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '天' + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'ILOHC' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=ilohc]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装港' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.upPortNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'C/V/E' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=c_v_e]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸港' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.downPortNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '佣金' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcContractObj.com_fee + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '%' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + '受载期' + '</td>';
        tab_text += '<td style="width:100px;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + tcContractObj.lay_date + '</td>';
        tab_text += '<td style="width:100px;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + tcContractObj.can_date + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;">' + '租家' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + tcContractObj.charterer + '</td>';
        tab_text += '</tr>';

        tab_text += '<tr><td colspan="8" style="height:30px;border-bottom:1px solid #757575;border-top:1px solid black;">&nbsp;</td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '速度' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + tcInputObj.input['speed'] + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '日消耗 (MT)' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '航行' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '装/卸' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '等待' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '价格' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '距离(NM)' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=distance]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'FO' + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=fo_sailing]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=fo_up_shipping]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=fo_waiting]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=fo_price]')[1]).val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装货天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=up_ship_day]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'DO' + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=do_sailing]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=do_up_shipping]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=do_waiting]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=do_price]')[1]).val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸货天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=down_ship_day]')[1]).val() + '</td>';
        tab_text += '<td colspan="5"style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:1px solid #717171;">' + '油耗' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '等待天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=wait_day]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'FO' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=fo_mt]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + 'MT' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航行' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=sail_term]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'DO' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=do_mt]')[1]).val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + 'MT' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航次用时' + '</td>';
        tab_text += '<td colspan="2" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=sail_time]')[1]).val() + '</td>';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '油款' + '</td>';
        tab_text += '<td colspan="4" style="border-bottom:1px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=oil_money]')[1]).val() + '</td>';
        tab_text += '</tr>';

        tab_text += '<tr><td colspan="8" style="height:30px;border-bottom:1px solid #757575;border-top:1px solid black;">&nbsp' + ';</' + 'td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装港费' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=up_port_price]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '收入' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=credit]')[1]).val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸港费' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=down_port_price]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '支出' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=debit]')[1]).val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '日成本' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=cost_per_day]')[1]).val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '净利润' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=net_profit]')[1]).val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '其他费用' + '</td>';
        tab_text += '<td colspan="2" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=cost_else]')[1]).val() + '</td>';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '日净利润' + '</td>';
        tab_text += '<td colspan="4" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $($('[name=net_profit_day]')[1]).val() + '</td>';
        tab_text += '<tr><td colspan="8" style="height:30px;border-top:1px solid black;">&nbsp;</td></tr>';
        
        var filename = '{!! $shipName !!}' + '_' + tcContractObj.voy_no + '合同分析(TC)';
        exportExcel(tab_text, filename, filename);
        return 0;
    }

    function fnExcelVoy()
    {
        var tab_text;
        tab_text = '<table id="excel" style="text-align:center;vertical-align:middle;">';
        tab_text += '<tbody>';
        tab_text += '<tr><td colspan="8" style="font-size:20px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:hidden;text-align:left;vertical-align:middle;"><b>合同分析</b></td></tr>';
        tab_text += '<tr><td colspan="8" style="font-size:18px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:hidden;text-align:left;vertical-align:middle;"></td></tr>';
        tab_text += '<tr><td colspan="8" style="font-size:16px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;border-bottom:1px solid #717171;text-align:left;vertical-align:middle;">合同扼要 <span style="font-style:italic;">CP MAIN TERMS</span></td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:SimSun;">';
        tab_text += '<td style="width:100px;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航次' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=voy_no]').val() + '</td>';
        tab_text += '<td style="width:100px;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '装率' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=load_rate]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '合同日期' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=cp_date]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '卸率' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=disch_rate]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '租船种类' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;"><b>' + 'VOY' + '</b></td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '单价' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">$ ' + $('[name=freight_rate]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '货名' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + voyContractObj.cargoNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '包船' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=lumpsum]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '货量' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=qty_amount]').val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=qty_type]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '滞期费' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">$ ' + $('[name=deten_fee]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装港' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + voyContractObj.upPortNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '速追肥' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">$ ' + $('[name=dispatch_fee]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸港' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + voyContractObj.downPortNames + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '佣金' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=com_fee]').val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '%' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + '受载期' + '</td>';
        tab_text += '<td style="width:100px;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + $('[name=lay_date]').val() + '</td>';
        tab_text += '<td style="width:100px;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + $('[name=can_date]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;">' + '租家' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;padding:5px!important;">' + $('[name=charterer]').val() + '</td>';
        tab_text += '</tr>';

        tab_text += '<tr><td colspan="8" style="height:30px;border-bottom:1px solid #757575;border-top:1px solid black;">&nbsp;</td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '速度' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=speed]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '日消耗 (MT)' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '航行' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '装/卸' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '等待' + '</td>';
        tab_text += '<td style="width:80px;background:#f3f3f3;text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '价格' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '距离(NM)' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=distance]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'FO' + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=fo_sailing]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=fo_up_shipping]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=fo_waiting]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=fo_price]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装货天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=up_ship_day]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'DO' + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=do_sailing]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=do_up_shipping]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=do_waiting]').val() + '</td>';
        tab_text += '<td style="text-align:center;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=do_price]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸货天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=down_ship_day]').val() + '</td>';
        tab_text += '<td colspan="5"style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:1px solid #717171;">' + '油耗' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '等待天数' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=wait_day]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'FO' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=fo_mt]').val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + 'MT' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航行' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=sail_term]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + 'DO' + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=do_mt]').val() + '</td>';
        tab_text += '<td style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + 'MT' + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '航次用时' + '</td>';
        tab_text += '<td colspan="2" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=sail_time]').val() + '</td>';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '油款' + '</td>';
        tab_text += '<td colspan="4" style="border-bottom:1px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=oil_money]').val() + '</td>';
        tab_text += '</tr>';

        tab_text += '<tr><td colspan="8" style="height:30px;border-bottom:1px solid #757575;border-top:1px solid black;">&nbsp' + ';</' + 'td></tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '装港费' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=up_port_price]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '收入' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=credit]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '卸港费' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=down_port_price]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '支出' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=debit]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '日成本' + '</td>';
        tab_text += '<td colspan="2" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=cost_per_day]').val() + '</td>';
        tab_text += '<td style="background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '净利润' + '</td>';
        tab_text += '<td colspan="4" style="text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=net_profit]').val() + '</td>';
        tab_text += '</tr>';
        tab_text += '<tr style="font-size:14px;font-family:simsun;">';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + '其他费用' + '</td>';
        tab_text += '<td colspan="2" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=cost_else]').val() + '</td>';
        tab_text += '<td style="border-bottom:3px #484848 solid!important;background:#f3f3f3;text-align:right;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;">' + '日净利润' + '</td>';
        tab_text += '<td colspan="4" style="border-bottom:3px #484848 solid!important;text-align:left;vertical-align:middle;border-left:hidden;border-right:hidden;border-bottom:hidden;padding:5px!important;">' + $('[name=net_profit_day]').val() + '</td>';
        tab_text += '<tr><td colspan="8" style="height:30px;border-top:1px solid black;">&nbsp;</td></tr>';
        
        var filename = '{!! $shipName !!}' + '_' + tcContractObj.voy_no + '合同分析(TC)';
        var filename = '{!! $shipName !!}' + '_' + $('[name=voy_no]').val() + '合同分析(VOY)';
        exportExcel(tab_text, filename, filename);
        return 0;
    }
    </script>

@endsection