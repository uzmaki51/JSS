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
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>船舶证书记录</b>
                    </h4>
                </div>

            </div>
            <div class="col-md-12">
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
                    <div class="col-lg-12 head-fix-div d-line-height" style="height: 121px;">
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
                                                    <labe>&nbsp;</labe>
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
                                                    <labe>&nbsp;</labe>
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
                                                    <labe>&nbsp;</labe>
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
                                                    <labe>&nbsp;</labe>
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
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/zh.js"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>

	<?php
	echo '<script>';
	echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
	echo '</script>';
	?>
    <script>
        var certListObj = null;
        var certTypeObj = null;
        var shipCertTypeList = [];
        var shipCertListTmp = new Array();
        var certIdList = [];
        var certIdListTmp = [];
        var IS_FILE_KEEP = '{!! IS_FILE_KEEP !!}';
        var IS_FILE_DELETE = '{!! IS_FILE_DELETE !!}';
        var IS_FILE_UPDATE = '{!! IS_FILE_UPDATE !!}';
        var ship_id = '{!! $shipId !!}';
        var isChangeStatus = false;
        var initLoad = true;

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
            // Initialize
            initialize();

        });


        function initialize() {
            // Create Vue Obj
            certListObj = new Vue({
                el: '#cert_list',
                data() { return {
                    cert_array: [],
                    certListTmp: [],
                    certTypeList: [],
                    zh: vdp_translation_zh.js,
                    issuer_type: IssuerTypeData
                }
                },
                components: {
                    vuejsDatepicker
                },
                methods: {
                    certTypeChange: function(event) {
                        let hasClass = $(event.target).hasClass('open');
                        if($(event.target).hasClass('open')) {
                            $(event.target).removeClass('open');
                            $(event.target).siblings(".dynamic-options").removeClass('open');
                        } else {
                            $(event.target).addClass('open');
                            $(event.target).siblings(".dynamic-options").addClass('open');
                        }
                    },
                    setCertInfo: function(array_index, cert) {
                        var values = $("input[name='cert_id[]']")
                            .map(function(){return parseInt($(this).val());}).get();

                        if(values.includes(cert)) {alert('Can\'t register duplicate certificate.'); return false;}

                        isChangeStatus = true;
                        setCertInfo(cert, array_index);
                        $(".dynamic-select__trigger").removeClass('open');
                        $(".dynamic-options").removeClass('open');
                    },
                    customFormatter(date) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                    dateModify(e, index, type) {
                        $(e.target).on("change", function() {
                            certListObj.cert_array[index][type] = $(this).val();
                        });
                    },
                    customInput() {
                        return 'form-control';
                    },
                    onFileChange(e) {
                        let index = e.target.getAttribute('data-index');
                        certListObj.cert_array[index]['is_update'] = IS_FILE_UPDATE;
                        certListObj.cert_array[index]['file_name'] = 'updated';
                        isChangeStatus = true;
                        this.$forceUpdate();
                    },
                    openShipCertList(e) {
                        // Object.assign(certTypeObj.list, shipCertTypeList);
                        // certTypeObj.list.push([]);
                        $('.only-modal-show').click();
                    },
                    getImage: function(file_name) {
                        if(file_name != '' && file_name != undefined)
                            return '/assets/images/document.png';
                        else
                            return '/assets/images/paper-clip.png';
                    },
                    deleteCertItem(cert_id, is_tmp, array_index) {
                        document.getElementById('warning-audio').play();
                        if (is_tmp == 0) {
                            bootbox.confirm("Are you sure you want to delete?", function (result) {
                                if (result) {
                                    $.ajax({
                                        url: BASE_URL + 'ajax/shipManage/shipCert/delete',
                                        type: 'post',
                                        data: {
                                            id: cert_id,
                                        },
                                        success: function (data, status, xhr) {
                                            certListObj.cert_array.splice(array_index, 1);
                                        }
                                    })
                                }
                            });
                        } else {
                            certListObj.cert_array.splice(array_index, 1);
                        }
                    }

                },
                updated() {
                    // console.log(initLoad);
                    // console.log('-----------');
                    // if(initLoad == true) {
                        console.log('++++++++++++++++');
                        $('.date-picker').datepicker({
                            autoclose: true,
                        }).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                        console.log(initLoad);
                        initLoad = false;
                        console.log(initLoad);
                    }
                // }
            });

            certTypeObj = new Vue({
                el: '#modal-cert-type',
                data() {
                    return {
                        list: [],
                    }
                },
                methods: {
                    deleteShipCert(index) {
                        if(index == undefined || index == '')
                            return false;

                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                                isChangeStatus = true;
                                $.ajax({
                                    url: BASE_URL + 'ajax/shipManage/cert/delete',
                                    type: 'post',
                                    data: {
                                        id: index
                                    },
                                    success: function(data) {
                                        certTypeObj.list = data;
                                        // certTypeObj.list.push([]);
                                        certTypeObj.$forceUpdate();
                                        getShipInfo(ship_id);

                                    }
                                })
                            }});
                    },
                    ajaxFormSubmit() {
                        let form = $('#shipCertForm').serialize();
                        $.post('shipCertType', form).done(function (data) {
                            let result = data;
                            let result1 = data;
                            let result2 = data;
                            certTypeObj.list = result;
                            certTypeObj.$forceUpdate();
                            certListObj.certTypeList = result1;
                            shipCertTypeList = result2;
                            getShipInfo(ship_id);
                            $('.close').click();
                        });
                    },
                    addNewRow(e) {
                        isChangeStatus = true;
                        certTypeObj.list.push([]);
                    }
                }
            });

            getShipInfo(ship_id);

        }

        function getShipInfo(ship_id) {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/cert/list',
                type: 'post',
                data: {
                    ship_id: ship_id
                },
                success: function(data, status, xhr) {
                    let ship_id = data['ship_id'];
                    let ship_name = data['ship_name'];
                    let typeList = data['cert_type'];
                    shipCertTypeList = data['cert_type'];

                    $('[name=ship_id]').val(ship_id);
                    $('#ship_name').text(ship_name);
                    //certListObj.cert_array = data['ship'];
                    Object.assign(certListObj.cert_array, data['ship']);
                    certListObj.certTypeList = typeList;

                    Object.assign(certTypeObj.list, shipCertTypeList);
                    certTypeObj.list.push([]);
                    certIdList = [];
                    certListObj.cert_array.forEach(function(value, index) {
                        certIdList.push(value['cert_id']);
                        certListObj.cert_array[index]['is_update'] = IS_FILE_KEEP;
                        certListObj.cert_array[index]['is_tmp'] = 0;
                        setCertInfo(value['cert_id'], index);
                    });

                    shipCertListTmp = JSON.parse(JSON.stringify(certListObj.cert_array));
                }
            })
        }

        function addCertItem() {
            let reportLen = certListObj.cert_array.length;
            let newCertId = 0;
            if(reportLen == 0) {
                reportLen = 0;
                newCertId = 0;
            } else {
                newCertId = certListObj.cert_array[reportLen - 1]['cert_id'];
            }

            newCertId = getNearCertId(newCertId);

            if(shipCertTypeList.length <= reportLen && reportLen > 0)
                return false;

            if(newCertId == '') {
                newCertId = getNearCertId(0);
            }

            certListObj.cert_array.push([]);
            certListObj.cert_array[reportLen]['cert_id']  = newCertId;
            certListObj.cert_array[reportLen]['is_tmp']  = 1;
            setCertInfo(newCertId, reportLen);
            certListObj.cert_array[reportLen]['issue_date']  = $($('[name^=issue_date]')[reportLen - 1]).val();
            certListObj.cert_array[reportLen]['expire_date']  = $($('[name^=expire_date]')[reportLen - 1]).val();
            certListObj.cert_array[reportLen]['due_endorse']  = $($('[name^=due_endorse]')[reportLen - 1]).val();
            certListObj.cert_array[reportLen]['issuer']  = 1;
            $($('[name=cert_id]')[reportLen - 1]).focus();
            certIdList.push(certListObj.cert_array[reportLen]['cert_id']);

            $('[date-issue=' + reportLen + ']').datepicker({
                autoclose: true,
            }).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });

            isChangeStatus = true;
        }

        function getNearCertId(cert_id) {
            var values = $("input[name='cert_id[]']")
                .map(function(){return parseInt($(this).val());}).get();
            let tmp = 0;
            tmp = cert_id;
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] - tmp > 0 && !values.includes(value['id'])) {
                    if(value['id'] - cert_id <= value['id'] - tmp)
                        tmp = value['id'];
                }
            });

            return tmp == cert_id ? 0 : tmp;
        }

        function setCertInfo(certId, index = 0) {
            let status = 0;
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    certListObj.cert_array[index]['order_no'] = value['order_no'];
                    certListObj.cert_array[index]['cert_id'] = certId;
                    certListObj.cert_array[index]['code'] = value['code'];
                    certListObj.cert_array[index]['cert_name'] = value['name'];
                    certListObj.$forceUpdate();
                    status ++;
                }
            });
        }

        $('#select-ship').on('change', function() {
            let ship_id = $(this).val();
            location.href = '/business/contract?shipId=' + ship_id;
        });

        $('#submit').on('click', function() {
            $('#certList-form').submit();
        });

        $(document).mouseup(function(e) {
            var container = $(".dynamic-options-scroll");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".dynamic-options").removeClass('open');
                $(".dynamic-options").siblings('.dynamic-select__trigger').removeClass('open')
            }
        });

        $(".ui-draggable").draggable({
            helper: 'move',
            cursor: 'move',
            tolerance: 'fit',
            revert: "invalid",
            revert: false
        });
    </script>
@endsection