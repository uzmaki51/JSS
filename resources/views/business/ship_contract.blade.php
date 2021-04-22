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
                    <b>合同书记录</b>
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
                                <button class="btn btn-primary btn-sm search-btn" onclick="addCertItem()"><i class="icon-plus"></i>添加</button>
                                <button class="btn btn-warning btn-sm excel-btn d-none"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                                <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
                                @if(!$isHolder)
                                    <button class="btn btn-sm btn-warning" id="submit">
                                        <i class="icon-save"></i>保存
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 4px;">
                    <div class="col-lg-12">
                        <div class="head-fix-div d-line-height" style="height: 121px;">
                            <form action="shipCertList" method="post" id="certList-form" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" value="{{ $shipId }}" name="ship_id">
                                <table>
                                    <thead class="">
                                    <tr>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_no') !!}<br>{!! trans('business.table.en.voy_no') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.voy_tc') !!}<br>{!! trans('business.table.en.voy_tc') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cp_date') !!}<br>{!! trans('business.table.en.cp_date') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cargo') !!}<br>{!! trans('business.table.en.cargo') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.qty') !!}<br>{!! trans('business.table.en.qty') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.loading_port') !!}<br>{!! trans('business.table.en.loading_port') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.discharge_port') !!}<br>{!! trans('business.table.en.discharge_port') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.l_rate') !!}<br>{!! trans('business.table.en.l_rate') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.cargo') !!}<br>{!! trans('business.table.en.d_rate') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.frt_rate') !!}<br>{!! trans('business.table.en.frt_rate') !!}</th>
                                        <th class="text-center style-header" style="width: 60px;">{!! trans('business.table.cn.anticipate') !!}</th>
                                        <th class="text-center style-header" rowspan="2">{!! trans('business.table.cn.contract_attach') !!}</th>
                                        <th class="text-center style-header" rowspan="2" style="width:20px;word-break: break-all;">{!! trans('common.label.delete') !!}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center style-header" style="width:20px;word-break: break-all;">{!! trans('business.table.cn.daily_profit') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($cp_list) && count($cp_list) > 0)
                                        @foreach($cp_list as $item)
                                            <tr>
                                                <td class="text-center">{{ $item['Voy_No'] }}</td>
                                                <td class="text-center">{{ g_enum('CPTypeData')[$item['CP_kind']] }}</td>
                                                <td class="text-center">{{ $item['CP_Date'] }}</td>
                                                <td class="text-left">{{ $item['Cargo'] }}</td>
                                                <td class="text-center">{{ $item['Cgo_Qtty'] }}</td>
                                                <td class="text-center">{{ $item['LPort'] }}</td>
                                                <td class="text-center">{{ $item['DPort'] }}</td>
                                                <td class="text-center">{{ $item['L_Rate'] }}</td>
                                                <td class="text-center">{{ $item['D_Rate'] }}</td>
                                                <td class="text-center">{{ $item['Freight'] }}</td>
                                                <td class="text-center">{{ $item['total_Freight'] }}</td>
                                                <td class="text-center">
                                                    @if($item['is_attachment'] == 1)
                                                        <img src="{{ cAsset('assets/images/document.png') }}" width="15" height="15" style="cursor: pointer;">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a class="red" href="javascript:deleteItem()">
                                                            <i class="icon-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="13">
                                                {!! trans('common.message.no_data') !!}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <ul class="nav nav-tabs ship-register">
                            <li class="active">
                                <a data-toggle="tab" href="#general" onclick="changeTab('general')">
                                    程租<span style="font-style: italic;">(VOY)</span>
                                </a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#form_a" onclick="changeTab('formA')">
                                    期租<span style="font-style: italic;">(TC)</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="general" class="tab-pane active">
                                <div class="d-flex" id="voy_input">
                                    <div class="tab-left contract-input-div"  id="voy_input_div">
                                        <div class="d-flex">
                                            <label class="font-bold ml-3">预计</label>

                                            <label class="ml-3">货币</label>
                                            <select class="ml-1" name="currency" v-model="input['currency']">
                                                <option value="USD">$</option>
                                                <option value="CNY">￥</option>
                                            </select>

                                            <div class="label-input ml-1" style="width: 120px;">
                                                <label>{!! trans('common.label.curr_rate') !!}</label>
                                                <input type="text" name="rate" v-model="input['rate']">
                                            </div>
                                        </div>
                                        <div class="d-flex mt-2">
                                            <div class="voy-input-left voy-child">
                                                <h5 class="ml-5 brown font-bold">输入</h5>
                                                <div class="d-flex mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>速度</label>
                                                        <input type="text" name="speed" v-model="input['speed']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>距离(NM)</label>
                                                        <input type="text" name="distance" v-model="input['distance']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装货天数</label>
                                                        <input type="text" name="up_ship_day" v-model="input['up_ship_day']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>卸货天数</label>
                                                        <input type="text" name="down_ship_day" v-model="input['down_ship_day']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>等待天数</label>
                                                        <input type="text" name="wait_day" v-model="input['wait_day']" @change="calcContractPreview">
                                                    </div>
                                                </div>

                                                <h5 class="mt-20">日消耗&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(MT)</h5>
                                                <div class="d-flex daily-use">
                                                    <div class="vertical">
                                                        <label>&nbsp;</label>
                                                        <label>FO</label>
                                                        <label>DO</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>航行</label>
                                                        <input type="text" class="output-text" name="fo_sailing" v-model="input['fo_sailing']" @change="calcContractPreview">
                                                        <input type="text" class="output-text" name="do_sailing" v-model="input['do_sailing']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装/卸</label>
                                                        <input type="text" class="output-text" name="fo_up_shipping" v-model="input['fo_up_shipping']" @change="calcContractPreview">
                                                        <input type="text" class="output-text" name="do_up_shipping" v-model="input['do_up_shipping']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>等待</label>
                                                        <input type="text" class="output-text" name="fo_waiting" v-model="input['fo_waiting']" @change="calcContractPreview">
                                                        <input type="text" class="output-text" name="do_waiting" v-model="input['do_waiting']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>价格</label>
                                                        <input type="text" name="fo_price" @change="calcContractPreview" v-model="input['fo_price']">
                                                        <input type="text" name="do_price" @change="calcContractPreview" v-model="input['do_price']">
                                                    </div>
                                                </div>
                                                <hr class="gray-dotted-hr">
                                                <div class="d-flex  mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>程租</label>
                                                        <label>&nbsp;</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>货量(MT)</label>
                                                        <input type="text" name="cargo_amount" v-model="input['cargo_amount']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>单价</label>
                                                        <input type="text" name="freight_price" v-model="input['freight_price']" :readonly="batchStatus == true" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label for="batch-manage" class="batch-manage"><input type="checkbox" v-model="batchStatus" id="batch-manage"  @change="calcContractPreview">包船</label>
                                                        <input type="text" v-bind:class="batchStatus == true ? '' : 'output-text'" name="batch_price" :readonly="batchStatus == false" v-model="input['batch_price']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>佣金(%)</label>
                                                        <input type="text" name="fee" v-model="input['fee']" @change="calcContractPreview">
                                                    </div>
                                                </div>
                                                <div class="d-flex  mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>&nbsp;</label>
                                                        <label>支出</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装港费</label>
                                                        <input type="text" name="up_port_price" v-model="input['up_port_price']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>卸港费</label>
                                                        <input type="text" name="down_port_price" v-model="input['down_port_price']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>日成本</label>
                                                        <input type="text" name="cost_per_day" v-model="input['cost_per_day']" @change="calcContractPreview">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>其他费用</label>
                                                        <input type="text" name="cost_else" v-model="input['cost_else']" @change="calcContractPreview">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="voy-input-right voy-child">
                                                <h5 class="ml-5 brown font-bold">输出</h5>
                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>航次用时</label>
                                                        <input type="text" class="text-right" readonly name="sail_time" v-model="output['sail_time']">
                                                        <span>天</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>航行</label>
                                                        <input type="text" class="text-right" readonly name="sail_term" v-model="output['sail_term']">
                                                        <span>天</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>停泊</label>
                                                        <input type="text" class="text-right" readonly name="moor" v-model="output['moor']">
                                                        <span>天</span>
                                                    </div>
                                                </div>
                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>油款</label>
                                                        <input type="text" class="text-left bigger-input" readonly name="oil_money" v-model="output['oil_money']">
                                                        <span></span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>FO</label>
                                                        <input type="text" class="text-right" readonly name="fo_mt" v-model="output['fo_mt']">
                                                        <span>MT</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>DO</label>
                                                        <input type="text" class="text-right" readonly name="do_mt" v-model="output['do_mt']">
                                                        <span>MT</span>
                                                    </div>
                                                </div>

                                                <hr class="gray-dotted-hr">

                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>收入</label>
                                                        <input type="text" class="text-left bigger-input" readonly name="credit" v-model="output['credit']">
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>支出</label>
                                                        <input type="text" class="text-left bigger-input" readonly name="debit" v-model="output['debit']">
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>净利润</label>
                                                        <input type="text" class="text-left bigger-input" readonly name="net_profit" v-model="output['net_profit']">
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>日净利润</label>
                                                        <input type="text" class="text-left bigger-input" readonly name="net_profit_day" v-model="output['net_profit_day']">
                                                        <span></span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>参考(最高)</label>
                                                        <input type="text" class="text-left double-input-left" readonly name="max_profit" v-model="output['max_profit']">
                                                        <input type="text" class="text-left double-input-right" readonly name="max_voy" v-model="output['max_voy']">
                                                        <span>航次</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>参考(最低)</label>
                                                        <input type="text" class="text-left double-input-left" readonly name="min_profit" v-model="output['min_profit']">
                                                        <input type="text" class="text-left double-input-right" readonly name="min_voy" v-model="output['min_voy']">
                                                        <span>航次</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-group f-right mt-20">
                                            <button class="btn btn-primary btn-sm" @click="onEditFinish">OK</button>
                                            <button class="btn btn-danger btn-sm" @click="onEditContinue">Cancel</button>
                                        </div>
                                    </div>
                                    <div class="tab-right contract-input-div" id="voy_contract_table">
                                        <label>航次</label>
                                        <input type="text">
                                        <table class="contract-table mt-2">
                                            <tr>
                                                <td>合同日期</td>
                                                <td class="font-style-italic">CP_DATE</td>
                                                <td><input type="text" class="date-picker form-control" name="cp_date" v-model="cp_date"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>租船种类</td>
                                                <td class="font-style-italic">CP TYPE</td>
                                                <td><input type="text" class="form-control" value="VOY" readonly></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>货名</td>
                                                <td class="font-style-italic">CARGO</td>
                                                <td colspan="2">
                                                    <select class="form-control" name="cargo" v-model="cargo">
                                                        <option>SODIUM</option>
                                                        <option>PHOSPHATE</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>货量</td>
                                                <td class="font-style-italic">QTTY</td>
                                                <td><input type="text" class="form-control" name="qty_amount" v-model="qty_amount"></td>
                                                <td>
                                                    <select class="form-control" name="qty_type" v-model="qty_type">
                                                        <option>MOLOO</option>
                                                        <option>MOLCO</option>
                                                    </selec>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>装港</td>
                                                <td class="font-style-italic">LOADING PORT</td>
                                                <td colspan="2">
                                                    <select class="form-control" name="up_port" v-model="up_port">
                                                        <option>MOLOO</option>
                                                        <option>MOLCO</option>
                                                    </selec>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>卸港</td>
                                                <td class="font-style-italic">DISCHARGING PORT</td>
                                                <td colspan="2">
                                                    <select class="form-control" name="down_port" v-model="down_port">
                                                        <option>MOLOO</option>
                                                        <option>MOLCO</option>
                                                    </selec>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>受载期</td>
                                                <td class="font-style-italic">LAY/CAN</td>
                                                <td><input type="text" class="date-picker form-control" name="lay_date" v-model="lay_date"></td>
                                                <td><input type="text" class="date-picker form-control" name="can_date" v-model="can_date"></td>
                                            </tr>
                                            <tr>
                                                <td>装率</td>
                                                <td class="font-style-italic">LOAD RATE</td>
                                                <td colspan="2"><input type="text" class="form-control" name="load_rate" v-model="load_rate"></td>
                                            </tr>
                                            <tr>
                                                <td>卸率</td>
                                                <td class="font-style-italic">DISCH RATE</td>
                                                <td colspan="2"><input type="text" class="form-control" name="disch_rate" v-model="disch_rate"></td>
                                            </tr>
                                            <tr>
                                                <td>单价</td>
                                                <td class="font-style-italic">FREGITH RATE</td>
                                                <td><input type="text" class="form-control" name="freight_rate" readonly v-model="freight_rate"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>包船</td>
                                                <td class="font-style-italic">LUMPSUM</td>
                                                <td><input type="text" class="form-control" name="lumpsum" readonly v-model="lumpsum"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>滞期费</td>
                                                <td class="font-style-italic">DEMURR/DETEN FEE</td>
                                                <td><input type="text" class="form-control" name="deten_fee" v-model="deten_fee"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>速追肥</td>
                                                <td class="font-style-italic">DISPATCH FEE</td>
                                                <td><input type="text" class="form-control" name="dispatch_fee" v-model="dispatch_fee"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>佣金</td>
                                                <td class="font-style-italic">COM</td>
                                                <td><input type="text" class="form-control" name="com_fee" readonly v-model="com_fee"></td>
                                                <td>%</td>
                                            </tr>
                                            <tr>
                                                <td>租家</td>
                                                <td class="font-style-italic">CHARTERER</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">
                                                    <textarea name="charterer" class="form-control" rows="2" v-model="charterer"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>电话</td>
                                                <td class="font-style-italic">TEL</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">
                                                    <input type="text" class="form-control" name="tel_number" v-model="tel_number">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>备注</td>
                                                <td class="font-style-italic">REMARK</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">
                                                    <textarea name="remark" class="form-control" rows="2" v-model="remark"></textarea>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="attachment-div d-flex mt-20">
                                            <img src="{{ cAsset('/assets/images/paper-clip.png') }}" width="15" height="15">
                                            <span class="ml-1">附&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件: </span>
                                            <label for="contract_attach" class="ml-1 blue contract-attach">添加附件</label>
                                            <input type="file" id="contract_attach" class="d-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="form_a" class="tab-pane">
                                <div class="d-flex" id="voy_input">
                                    <div class="tab-left contract-input-div">
                                        <div class="d-flex">
                                            <label class="font-bold ml-3">预计</label>

                                            <label class="ml-3">货币</label>
                                            <select class="ml-1">
                                                <option>$</option>
                                                <option>￥</option>
                                            </select>

                                            <div class="label-input ml-1" style="width: 120px;">
                                                <label>{!! trans('common.label.curr_rate') !!}</label>
                                                <input type="text" name="rate">
                                            </div>
                                        </div>
                                        <div class="d-flex mt-2">
                                            <div class="voy-input-left voy-child">
                                                <h5 class="ml-5 brown font-bold">输入</h5>
                                                <div class="d-flex mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>速度</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>距离(NM)</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装货天数</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>卸货天数</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>等待天数</label>
                                                        <input type="text">
                                                    </div>
                                                </div>

                                                <h5 class="mt-20">日消耗&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(MT)</h5>
                                                <div class="d-flex daily-use">
                                                    <div class="vertical">
                                                        <label>&nbsp;</label>
                                                        <label>FO</label>
                                                        <label>DO</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>航行</label>
                                                        <input type="text" class="output-text">
                                                        <input type="text" class="output-text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装/卸</label>
                                                        <input type="text" class="output-text">
                                                        <input type="text" class="output-text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>等待</label>
                                                        <input type="text" class="output-text">
                                                        <input type="text" class="output-text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>价格</label>
                                                        <input type="text">
                                                        <input type="text">
                                                    </div>
                                                </div>
                                                <hr class="gray-dotted-hr">
                                                <div class="d-flex  mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>程租</label>
                                                        <label>&nbsp;</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>货量(MT)</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>单价</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label for="batch-manage" class="batch-manage"><input type="checkbox" id="batch-manage">包船</label>
                                                        <input type="text" class="output-text" readonly>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>佣金(%)</label>
                                                        <input type="text">
                                                    </div>
                                                </div>
                                                <div class="d-flex  mt-20 attribute-div">
                                                    <div class="vertical">
                                                        <label>&nbsp;</label>
                                                        <label>支出</label>
                                                    </div>
                                                    <div class="vertical">
                                                        <label>装港费</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>卸港费</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>日成本</label>
                                                        <input type="text">
                                                    </div>
                                                    <div class="vertical">
                                                        <label>其他费用</label>
                                                        <input type="text">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="voy-input-right voy-child">
                                                <h5 class="ml-5 brown font-bold">输出</h5>
                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>航次用时</label>
                                                        <input type="text" class="text-right" readonly>
                                                        <span>天</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>航行</label>
                                                        <input type="text" class="text-right" readonly>
                                                        <span>天</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>停泊</label>
                                                        <input type="text" class="text-right" readonly>
                                                        <span>天</span>
                                                    </div>
                                                </div>
                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>油款</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span></span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>FO</label>
                                                        <input type="text" class="text-right" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>DO</label>
                                                        <input type="text" class="text-right" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                </div>

                                                <hr class="gray-dotted-hr">

                                                <div class="d-block mt-20">
                                                    <div class="d-flex horizontal">
                                                        <label>油款</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span></span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>FO</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>DO</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>油款</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span></span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>FO</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                    <div class="d-flex horizontal">
                                                        <label>DO</label>
                                                        <input type="text" class="text-right bigger-input" readonly>
                                                        <span>MT</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-group f-right mt-20">
                                            <button class="btn btn-primary btn-sm">OK</button>
                                            <button class="btn btn-danger btn-sm">Cancel</button>
                                        </div>
                                    </div>
                                    <div class="tab-right contract-input-div">
                                        <label>航次</label>
                                        <select>
                                            <option>2103</option>
                                            <option>2104</option>
                                            <option>2105</option>
                                        </select>
                                        <table class="contract-table mt-2">
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td>2021/04/20</td>
                                                <td>CP_DATE</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">SPORE INT'L MINERAL EXP<br> & IMP CO.LTD</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">2021/04/20</td>
                                            </tr>
                                            <tr>
                                                <td>合同日期</td>
                                                <td>CP_DATE</td>
                                                <td colspan="2" style="border-right: 1px solid #4c4c4c;">2021/04/20<br>2021/04/20</td>
                                            </tr>
                                        </table>

                                        <div class="attachment-div d-flex mt-20">
                                            <img src="{{ cAsset('/assets/images/paper-clip.png') }}" width="15" height="15">
                                            <span class="ml-1">附&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件: </span>
                                            <label for="contract_attach" class="ml-1 blue contract-attach">添加附件</label>
                                            <input type="file" id="contract_attach" class="d-none">
                                        </div>
                                    </div>
                                </div>
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
                                    <div id="modal-cert-type" class="dynamic-modal-body step-content">
                                        <div class="row">
                                            <form action="shipCertType" method="post" id="shipCertForm">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="head-fix-div col-md-12" style="height:300px;">
                                                    <table class="table-bordered rank-table">
                                                        <thead>
                                                        <tr class="rank-tr" style="background-color: #c9dfff;height:18px;">
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:20%">OrderNo</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:20%">Code</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:50%">Name</th>
                                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="rank-table">
                                                        <tr class="no-padding center" v-for="(typeItem, index) in list">
                                                            <td class="d-none">
                                                                <input type="hidden" name="id[]" v-model="typeItem.id">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="order_no[]" v-model="typeItem.order_no" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="code[]" v-model="typeItem.code" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <input type="text" @focus="addNewRow(this)" class="form-control" name="name[]" v-model="typeItem.name" style="width: 100%;text-align: center">
                                                            </td>
                                                            <td class="no-padding center">
                                                                <div class="action-buttons">
                                                                    <a class="red" @click="deleteShipCert(typeItem.id)"><i class="icon-trash"></i></a>
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
        var voyInputObj = null;
        var voyContractObj = null;
        var ship_id = '{!! $shipId !!}';
        var isChangeStatus = false;

        var DEFAULT_CURRENCY = '{!! USD_LABEL !!}';
        var DECIMAL_SIZE = 2;

        var submitted = false;
        if(isChangeStatus == false)
            submitted = false;

        $("form").submit(function() {
            submitted = true;
        });

        var $form = $('form'),
            origForm = $form.serialize();
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';
            let currentObj = JSON.parse(JSON.stringify(certListObj.cert_array));
            if(JSON.stringify(currentObj) == JSON.stringify(shipCertListTmp))
                isChangeStatus = false;
            else
                isChangeStatus = true;

            if ($form.serialize() !== origForm && !submitted && isChangeStatus) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });
        $(function () {
            
            initialize();

        });


        function initialize() {
            voyInputObj = new Vue({
                el: "#voy_input_div",
                data: {
                    batchStatus: false,
                    input: {
                        currency:           DEFAULT_CURRENCY,
                        rate:               0,
                        speed:              0,
                        distance:           0,
                        up_ship_day:        0,
                        down_ship_day:      0,
                        wait_day:           0,

                        fo_sailing:         0,
                        fo_up_shipping:     0,
                        fo_waiting:         0,
                        fo_price:           0,
                        do_sailing:         0,
                        do_up_shipping:     0,
                        do_waiting:         0,
                        do_price:           0,

                        cargo_amount:       0,
                        freight_price:      0,
                        fee:                0,
                        batch_price:        0,
                        up_port_price:      0,
                        down_port_price:    0,
                        cost_per_day:       0,
                        cost_else:          0
                    },
                    output: {
                        sail_time:          0,
                        sail_term:          0,
                        moor:               0,
                        oil_money:          0,
                        fo_mt:              0,
                        do_mt:              0,
                        credit:             0,
                        debit:              0,
                        net_profit:         0,
                        net_profit_day:     0,
                        max_profit:         0,
                        max_voy:            0,
                        min_profit:         0,
                        min_voy:            0,
                    }
                },
                ready: function() {
                    calcContractPreview();
                },
                methods: {
                    onEditFinish: function() {
                        voyContractObj.cp_date = this.getToday('-');
                        voyContractObj.qty_amount = this.input['cargo_amount'];
                        voyContractObj.freight_rate = this.input['fregith_price'];
                        if(this.batchStatus == true) {
                            voyContractObj.lumpsum = this.input['batch_price'];
                            voyContractObj.freight_rate = 0;
                        } else {
                            voyContractObj.freight_rate = this.input['freight_price'];
                        }
                        
                        voyContractObj.com_fee = this.input['fee'];
                        $('#voy_input_div input').attr('readonly', true);
                    },
                    onEditContinue: function() {
                        $('#voy_input_div input').attr('readonly', false);
                    },
                    getToday: function(symbol) {
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + symbol + mm + symbol + dd;

                        return today;
                    },
                    calcContractPreview: function() {
                        if(parseInt(this.input['speed']) != 0) {
                            let tmp = BigNumber(this.input['distance']).div(this.input['speed']);
                            this.output['sail_term'] = BigNumber(tmp).div(24).toFixed(DECIMAL_SIZE);
                        } else {
                            this.output['sail_term'] = 0;
                        }
                        
                        let moorTmp = BigNumber(this.input['up_ship_day']).plus(this.input['down_ship_day']);
                        let fo_sailTmp1 = moorTmp;
                        let fo_sailTmp2 = 0;
                        let fo_sailTmp3 = 0;
                        let do_sailTmp1 = moorTmp;
                        let do_sailTmp2 = 0;
                        let do_sailTmp3 = 0;

                        moorTmp = BigNumber(moorTmp).plus(this.input['wait_day']);
                        this.output['moor'] = BigNumber(moorTmp).toFixed(DECIMAL_SIZE);
                        this.output['sail_time'] = BigNumber(this.output['moor']).plus(this.output['sail_term']).toFixed(DECIMAL_SIZE);

                        // FO_MT
                        fo_sailTmp1 = fo_sailTmp1.multipliedBy(this.input['fo_up_shipping']);
                        fo_sailTmp2 = BigNumber(this.input['fo_sailing']).multipliedBy(this.output['sail_term']);
                        fo_sailTmp3 = BigNumber(this.input['fo_waiting']).multipliedBy(this.input['wait_day']);
                        this.output['fo_mt'] = BigNumber(fo_sailTmp1).plus(fo_sailTmp2).plus(fo_sailTmp3).toFixed(DECIMAL_SIZE);

                        // DO_MT
                        do_sailTmp1 = do_sailTmp1.multipliedBy(this.input['do_up_shipping']);
                        do_sailTmp2 = BigNumber(this.input['do_sailing']).multipliedBy(this.output['sail_term']);
                        do_sailTmp3 = BigNumber(this.input['do_waiting']).multipliedBy(this.input['wait_day']);
                        this.output['do_mt'] = BigNumber(do_sailTmp1).plus(do_sailTmp2).plus(do_sailTmp3).toFixed(DECIMAL_SIZE);

                        // Oil Price
                        let fo_oil_price = BigNumber(this.output['fo_mt']).multipliedBy(this.input['fo_price']);
                        let do_oil_price = BigNumber(this.output['do_mt']).multipliedBy(this.input['do_price']);
                        this.output['oil_money'] = BigNumber(fo_oil_price).plus(do_oil_price).toFixed(DECIMAL_SIZE);

                        // Credit
                        if(this.batchStatus) {
                            this.input['freight_price'] = 0;
                        }
                        let creditTmp = BigNumber(this.input['cargo_amount']).multipliedBy(this.input['freight_price']).plus(this.input['batch_price']);
                        let percent = BigNumber(1).minus(BigNumber(this.input['fee']).div(100));
                        creditTmp = BigNumber(creditTmp).multipliedBy(percent).toFixed(DECIMAL_SIZE);
                        this.output['credit'] = creditTmp;

                        // Debit
                        let debitTmp1 = BigNumber(this.input['cost_per_day']).multipliedBy(this.output['sail_time']);
                        let debitTmp2 = BigNumber(this.input['up_port_price']).plus(this.input['down_port_price']).plus(this.output['oil_money']).plus(this.input['cost_else']);
                        this.output['debit'] = BigNumber(debitTmp1).plus(debitTmp2).toFixed(DECIMAL_SIZE);

                        // Net Profit
                        let netProfit = BigNumber(this.output['credit']).minus(this.output['debit']).toFixed(DECIMAL_SIZE);
                        this.output['net_profit'] = netProfit;
                        
                        // Profit per day
                        this.output['net_profit_day'] = BigNumber(netProfit).div(this.output['sail_time']).toFixed(0);

                    }
                },
            });

            voyContractObj = new Vue({
                el: '#voy_contract_table',
                data: {
                    cp_date:        '',
                    cp_type:        'VOY',
                    cargo:          0,
                    qty_amount:    0,
                    qty_type:      'MOLOO',
                    up_port:        '',
                    down_port:      '',
                    lay_date:       '',
                    can_date:       '',
                    load_rate:      '',
                    disch_rate:     '',
                    freight_rate:   '',
                    lumpsum:        0,
                    demurr:         0,
                    deten_fee:      0,
                    dispatch_fee:   0,
                    com_fee:        0,
                    charterer:      '',
                    tel_number:     '',
                    remark:         '',
                },
            })

            Vue.filter("currency", {
                read: function(value) {
                return "$" + value.toFixed(2);
                },
                write: function(value) {
                var number = +value.replace(/[^\d.]/g, "");
                return isNaN(number) ? 0 : number;
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
                    let result = data;
                    console.log(result);
                    voyInputObj.input['fo_sailing'] = result['FOSailCons_S'];
                    voyInputObj.input['do_sailing'] = result['DOSailCons_S'];
                    voyInputObj.input['fo_up_shipping'] = result['FOL/DCons_S'];
                    voyInputObj.input['do_up_shipping'] = result['DOL/DCons_S'];
                    voyInputObj.input['fo_waiting'] = result['FOIdleCons_S'];
                    voyInputObj.input['do_waiting'] = result['DOIdleCons_S'];
                }
            })
        }





        $('#select-ship').on('change', function() {
            let ship_id = $(this).val();
            location.href = '/business/contract?shipId=' + ship_id;
        });
    </script>
@endsection