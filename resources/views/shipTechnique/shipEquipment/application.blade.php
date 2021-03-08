<?php
if (isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
<?php
    $isHolder = Session::get('IS_HOLDER');
    $ships = Session::get('shipList');
?>
@extends('layout.'.$header)

@section('content')
    @if(!isset($excel))
        <div class="main-content">
            <style>
                html {
                    overflow-x: hidden;
                }
                .chosen-container.chosen-container-single a {
                    height: 26px;
                }
                #Others_Cn .chosen-drop, #Content_Cn .chosen-drop, #PartName_Cn .chosen-drop,
                #Euipment_Cn .chosen-drop {
                    width: 700% !important;
                    text-align: left;
                }
                .chosen-drop {
                    width: 300% !important;
                    max-height: 140px;
                    text-align: left;
                }
                .chosen-results {
                    max-height: 100px !important;
                }
                #addNew select, #addNew input {
                    padding: 0px;
                }

                .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tfoot > tr > td {
                    padding: 5px;
                    font-size: 12px;
                }
                
                input {
                    text-align: center;
                }
            </style>
            <div class="page-header">
                <div class="col-md-3">
                    <h4><b>설비부속자재</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            신천공급등록
                        </small>
                    </h4>
                </div>
            </div>

            <div id="loading" class="modal" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" data-target="#modal-step-contents">
                            알림
                        </div>
                        <div id="modal-body-content" class="modal-body step-content">
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-content">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-6 form-horizontal">
                                <label class="control-label no-padding-right" style="float: left;">배이름</label>

                                <div class="col-sm-10">
                                    <select id="shipName" class="form-control chosen-select"
                                            style="height: 25px">
                                        @foreach($shipInfos as $shipInfo)
                                            @if(!$isHolder)
                                                <option value="{{$ship['RegNo']}}"
                                                        @if(isset($shipId) && ($shipId == $shipInfo['RegNo'])) selected @endif>{{ $shipInfo['shipName_Cn'] .' | ' .$shipInfo['shipName_En']}}
                                                </option>
                                            @elseif(in_array($shipInfo->shipID, $ships))
                                                <option value="{{$shipInfo['RegNo']}}"
                                                        @if(isset($shipId) && ($shipId == $shipInfo['RegNo'])) selected @endif>{{ $shipInfo['shipName_Cn'] .' | ' .$shipInfo['shipName_En']}}
                                                </option>
                                            @endif

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 form-horizontal">
                                <label class="control-label no-padding-right" style="float: left;">신청항차</label>

                                <div class="col-md-9" id="voyList">
                                    <select id="voy" class="form-control chosen-select" style="height: 25px">
                                        @foreach($voyInfos as $voyInfo)
                                            <?php if(empty($voyInfo['id'])) continue; ?>
                                            <option value="{{$voyInfo['id']}}"
                                                @if (isset($voy)) @if ($voyInfo['id']==$voy) selected @endif @endif>
                                                {{$voyInfo['Voy_No']}} | {{ $voyInfo['CP_No'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="text-align: right">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="onSearch()" style="width: 80px">
                                        <i class="icon-search"></i>
                                        검색
                                    </button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-warning btn-sm" type="button" onclick="onExcel()" style="width: 80px">
                                        <i class="icon-table"></i>
                                        Excel
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="space-10"></div>
                    <div class="row">
                        <div class="col-md-6" style="color:#126EB9;">
                            <b>상선항:</b>
                            <strong>
                                @if(!empty($cpInfo))
                                    {{ $cpInfo->lPortName() }}
                                @endif
                            </strong>
                            <b>: 하선항:</b>
                            <strong>
                                @if(!empty($cpInfo))
                                    {{ $cpInfo->dPortName() }}
                                @endif
                            </strong>
                        </div>
                    </div>
                    <div class="space-2"></div>
                    <div class="row">
                        <div style="width: 100%;overflow-x: scroll">
    @else
                            @include('layout.excel-style')
                            <br>
                            <div>상선항: @if(!empty($cpInfo)){{ $cpInfo->lPortName() }}@endif
                                하선항: @if(!empty($cpInfo)){{ $cpInfo->dPortName() }}@endif</div>
    @endif
                            <div style="overflow-y: scroll;width: 150%;">
                                <table id="tbl_app" class="table table-striped table-bordered table-hover"
                                       style="font-size: 10px !important;margin-bottom: 0px !important;">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center" colspan="8"
                                            style="width:38%;">{{transShipTech("supplyRecord.Application")}}</th>

                                        <th class="center" colspan="5"
                                            style="width:20%;">{{transShipTech("supplyRecord.Quotation")}}</th>

                                        <th class="center" colspan="3"
                                            style="width:12%;">{{transShipTech("supplyRecord.Instruction")}}</th>

                                        <th class="center" colspan="7"
                                            style="width:30%;">{{transShipTech("supplyRecord.Recipt")}}</th>
                                    </tr>
                                    <tr class="black br-hblue">
                                        <th class="center" style="width:6%;">{{transShipTech("supplyRecord.Ship")}}</th>
                                        <th rowspan="2" class="center"
                                            style="width:2%;">{{transShipTech("supplyRecord.No")}}</th>
                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Dept")}}</th>
                                        <th class="center"
                                            style="width:9%;">{{transShipTech("supplyRecord.Equipment")}}</th>
                                        <th class="center"
                                            style="width:9%;">{{transShipTech("supplyRecord.Part/Issa/Others")}}</th>
                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Qtty")}}</th>
                                        <th class="center" style="width:2%;">{{transShipTech("supplyRecord.Unit")}}</th>
                                        <th class="center" style="width:2%;">Y/N</th>

                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Date")}}</th>
                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Qtty")}}</th>
                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Price")}}</th>
                                        <th class="center" style="width:2%;">{{transShipTech("supplyRecord.Cr")}}</th>
                                        <th class="center" style="width:6%;">{{transShipTech("supplyRecord.Money")}}</th>

                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Date")}}</th>
                                        <th class="center" style="width:4%;">{{transShipTech("supplyRecord.Qtty")}}</th>
                                        <th class="center" style="width:4%;">Y/N</th>

                                        <th class="center" style="width:3%;">{{transShipTech("supplyRecord.Voy")}}</th>
                                        <th class="center" style="width:10%;">{{transShipTech("supplyRecord.Place")}}</th>
                                        <th class="center" style="width:3%;">{{transShipTech("supplyRecord.Qtty")}}</th>
                                        <th class="center" style="width:3%;">{{transShipTech("supplyRecord.Rate")}}</th>
                                        <th class="center" style="width:3%;">{{transShipTech("supplyRecord.Price")}}</th>
                                        <th class="center" style="width:3%;">Y/N</th>
                                        <th rowspan="2" class="center"
                                            style="width:5%;">{{transShipTech("supplyRecord.Remark")}}</th>
                                    </tr>
                                    <tr class="black br-hblue">
                                        <th class="center">{{transShipTech("supplyRecord.Voy")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.Kind")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.SN")}}</th>
                                        <th class="center" style="word-break: break-all;">
                                            <?php print_r(transShipTech("supplyRecord.PartNo/IssaCodeNo/Special"));?>
                                        </th>
                                        <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                                        <th class="center">{{transShipTech("supplyRecord.Opposit")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.NewKind")}}</th>
                                        <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                                        <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                                        <th class="center">{{transShipTech("supplyRecord.Date")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.Supplier")}}</th>
                                        <th colspan="2" class="center">{{transShipTech("supplyRecord.Money")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.SupplyMoney")}}</th>
                                        <th class="center">{{transShipTech("supplyRecord.Total")}}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div style="overflow-y: scroll;height: 400px;width: 150%;">
                                <table id="tbl_app" class="table table-striped table-bordered table-hover"
                                       style="font-size: 11px">
                                    <tbody id="supplyrecord">
                                    @if(isset($supplyInfos))
                                        @foreach($supplyInfos as $supplyInfo)
                                            <tr data-id="{{ $supplyInfo['id'] }}"
                                                @if($supplyInfo['ApplCheck'] == 1)
                                                    @if($supplyInfo['SupplyApplCheck'] != 1 || $supplyInfo['ReciptCheck'] != 1)
                                                        style="color: red;"
                                                    @endif
                                                @endif
                                            >
                                                <td class="center" id="shipName_Cn" data-value="{{ $supplyInfo['ShipName'] }}"
                                                    style="width:6%;">
                                                    {{$supplyInfo['shipName_En']}}</td>
                                                <td rowspan="2" class="center" id="No" data-value="{{ $supplyInfo['No'] }}"
                                                    style="width:2%;">
                                                    {{$supplyInfo['No']}}</td>
                                                <td class="center" id="Dept_Cn" data-value="{{ $supplyInfo['Dept'] }}"
                                                    style="width:4%;">
                                                    {{$supplyInfo['Dept_Cn']}}@if(isset($excel))({{$supplyInfo['Dept_En']}})@endif</td>
                                                <td class="center" id="Euipment_Cn"
                                                    data-value="{{ $supplyInfo['Euipment'] }}"
                                                    style="width:9%;">
                                                    @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                        <a href="javascript:void(0);" onclick="getHistory({{ $supplyInfo['id'] }})">
                                                            {{$supplyInfo['Euipment_Cn']}}@if(isset($excel))({{$supplyInfo['Euipment_En']}})@endif
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="center" id="PCO" style="width:9%;"
                                                    data-value="{{ $supplyInfo['Part'].';'.$supplyInfo['IssaCodeContent'].';'.$supplyInfo['Others'] }}">
                                                    <a href="javascript:void(0);" onclick="getHistory({{ $supplyInfo['id'] }})">
                                                        @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                            {{ $supplyInfo['PartName_Cn'] }}@if(isset($excel))({{$supplyInfo['PartName_En']}})@endif
                                                        @elseif($supplyInfo['SSkind'] == 3)
                                                            {{ $supplyInfo['Content_Cn'] }}@if(isset($excel))({{$supplyInfo['Content_En']}})@endif
                                                        @else
                                                            {{ $supplyInfo['Others_Cn'] }}@if(isset($excel))({{$supplyInfo['Others_En']}})@endif
                                                        @endif
                                                    </a>
                                                </td>
                                                <td class="center" id="ApplQtty" data-value="{{ $supplyInfo['ApplQtty'] }}"
                                                    style="width:4%;">
                                                    {{$supplyInfo['ApplQtty']}}</td>
                                                <td class="center" id="Unit_Cn" data-value="{{ $supplyInfo['Unit'] }}"
                                                    style="width:2%;">
                                                    {{$supplyInfo['Unit_Cn']}}@if(isset($excel))({{$supplyInfo['Unit_En']}}@endif</td>
                                                <td class="center" id="ApplCheck"
                                                    data-value="{{ $supplyInfo['ApplCheck'] }}"
                                                    style="width:2%;">
                                                    @if(!isset($excel))
                                                        <input type="checkbox" disabled
                                                               @if($supplyInfo['ApplCheck'] == 1) checked @endif>
                                                    @else
                                                        @if($supplyInfo['ApplCheck'] == 1)신청@endif
                                                    @endif
                                                </td>
                                                <td class="center" id="QuotDate" data-value="{{ $supplyInfo['QuotDate'] }}"
                                                    style="width:4%;">
                                                    {{$supplyInfo['QuotDate']}}</td>
                                                <td class="center" id="QuotQtty" data-value="{{ $supplyInfo['QuotQtty'] }}"
                                                    style="width:4%;">
                                                    {{$supplyInfo['QuotQtty']}}</td>
                                                <td class="center" id="QuotPrice"
                                                    data-value="{{ $supplyInfo['QuotPrice'] }}"
                                                    style="width:4%;">
                                                    {{$supplyInfo['QuotPrice']}}</td>
                                                <td class="center" id="Currency" data-value="{{ $supplyInfo['Currency'] }}"
                                                    style="width:2%;">
                                                    {{$supplyInfo['Currency']}}</td>
                                                <td class="center" id="QuotAmount"
                                                    data-value="{{ $supplyInfo['QuotAmount'] }}"
                                                    style="width:6%;">
                                                    {{$supplyInfo['QuotAmount']}}</td>

                                                <td class="center" id="SupplyApplDate"
                                                    data-value="{{ $supplyInfo['SupplyApplDate'] }}" style="width:4%;">
                                                    {{$supplyInfo['SupplyApplDate']}}</td>
                                                <td class="center" id="SupplyApplQtty"
                                                    data-value="{{ $supplyInfo['SupplyApplQtty'] }}" style="width:4%;">
                                                    {{$supplyInfo['SupplyApplQtty']}}</td>
                                                <td class="center" id="SupplyApplCheck"
                                                    data-value="{{ $supplyInfo['SupplyApplCheck'] }}" style="width:4%;">
                                                    @if(!isset($excel))
                                                        <input type="checkbox" disabled
                                                               @if($supplyInfo['SupplyApplCheck']==1) checked @endif>
                                                    @else
                                                        @if($supplyInfo['SupplyApplCheck']==1)지시받음@endif
                                                    @endif
                                                </td>

                                                <td class="center" id="Recipt" data-value="{{ $supplyInfo['ReciptVoy'] }}"
                                                    style="width:3%;">
                                                    @if(isset($supplyInfo->Recipt->Voy_No)) {{ $supplyInfo->Recipt->Voy_No }} @endif</td>
                                                <td class="center" id="ReciptPlace"
                                                    data-value="{{ $supplyInfo['ReciptPlace'] }}" style="width:10%;">
                                                    {{$supplyInfo['Port_Cn']}}@if(isset($excel))({{$supplyInfo['Port_En']}})@endif</td>
                                                <td class="center" id="ReciptQtty"
                                                    data-value="{{ $supplyInfo['ReciptQtty'] }}"
                                                    style="width:3%;">
                                                    {{$supplyInfo['ReciptQtty']}}</td>
                                                <td class="center" id="MarketCondition_Usd"
                                                    data-value="{{ $supplyInfo['MarketCondition_Usd'] }}" style="width:3%;">
                                                    {{$supplyInfo['MarketCondition_Usd']}}</td>
                                                <td class="center" id="ReciptPrice"
                                                    data-value="{{ $supplyInfo['ReciptPrice'] }}" style="width:3%;">
                                                    {{$supplyInfo['ReciptPrice']}}</td>
                                                <td class="center" id="ReciptCheck"
                                                    data-value="{{ $supplyInfo['ReciptCheck'] }}" style="width:3%;">
                                                    @if(!isset($excel))
                                                        <input type="checkbox" disabled
                                                               @if($supplyInfo['ReciptCheck'] == 1) checked @endif>
                                                    @else
                                                        @if($supplyInfo['ReciptCheck'] == 1)접수@endif
                                                    @endif
                                                </td>
                                                <td rowspan="2" class="center" id="ReciptRemark"
                                                    data-value="{{ $supplyInfo['ReciptRemark'] }}" style="width:5%;">
                                                    {{$supplyInfo['ReciptRemark']}}</td>
                                            </tr>
                                            <tr data-id="{{ $supplyInfo['id'] }}"
                                                @if($supplyInfo['ApplCheck'] == 1)
                                                    @if($supplyInfo['SupplyApplCheck'] != 1 || $supplyInfo['ReciptCheck'] != 1)
                                                        style="color: red;"
                                                    @endif
                                                @endif
                                            >
                                                <td class="center" id="ApplicationVoy">
                                                    {{$supplyInfo->Application->Voy_No}}</td>
                                                <td class="center" id="Kind_Cn" data-value="{{ $supplyInfo['SSkind'] }}">
                                                    {{$supplyInfo['Kind_Cn']}}@if(isset($excel))({{$supplyInfo['Kind_En']}})@endif</td>
                                                <td class="center">
                                                    @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                        @if(!empty($supplyInfo['SN']))
                                                            <a href="javascript:void(0);" onclick="getHistory({{ $supplyInfo['id'] }})">
                                                                {{$supplyInfo['SN']}}
                                                            </a>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="center">
                                                    <a href="javascript:void(0);" onclick="getHistory({{ $supplyInfo['id'] }})">
                                                        @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                            {{ $supplyInfo['PartNo'] }}
                                                        @elseif($supplyInfo['SSkind'] == 3)
                                                            {{ $supplyInfo['CodeNo'] }}
                                                        @else
                                                            {{ $supplyInfo['Special'] }}
                                                        @endif
                                                    </a>
                                                </td>
                                                <td colspan="3" class="center" id="AppRemark"
                                                    data-value="{{ $supplyInfo['AppRemark'] }}">
                                                    {{$supplyInfo['AppRemark']}}</td>

                                                <td class="center" id="QuotObject"
                                                    data-value="{{ $supplyInfo['QuotObject'] }}">
                                                    {{$supplyInfo['QuotObject']}}</td>
                                                <td class="center" id="QuotState"
                                                    data-value="{{ $supplyInfo['QuotState'] }}">
                                                    {{$supplyInfo['QuotState']}}</td>
                                                <td colspan="3" class="center" id="QuotRemark"
                                                    data-value="{{ $supplyInfo['QuotRemark'] }}">
                                                    {{$supplyInfo['QuotRemark']}}</td>

                                                <td colspan="3" class="center" id="SupplyRemark"
                                                    data-value="{{ $supplyInfo['SupplyRemark'] }}">
                                                    {{$supplyInfo['SupplyRemark']}}</td>

                                                <td class="center" id="ReciptDate"
                                                    data-value="{{ $supplyInfo['ReciptDate'] }}">
                                                    {{$supplyInfo['ReciptDate']}}</td>
                                                <td class="center" id="Supplier" data-value="{{ $supplyInfo['Supplier'] }}">
                                                    {{$supplyInfo['Supplier']}}</td>
                                                <td colspan="2" class="center" id="Amount"
                                                    data-value="{{ $supplyInfo['Amount'] }}">
                                                    {{$supplyInfo['Amount']}}</td>
                                                <td class="center" id="DeliveryAmount"
                                                    data-value="{{ $supplyInfo['DeliveryAmount'] }}">
                                                    {{$supplyInfo['DeliveryAmount']}}</td>
                                                <td class="center" id="TotalAmount"
                                                    data-value="{{ $supplyInfo['TotalAmount'] }}">
                                                    {{$supplyInfo['TotalAmount']}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if(!isset($excel) && !$isHolder)
                                        <tr data-id="0">
                                            <td colspan="23">새 신청항목 추가</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @if(!isset($excel))
                                    @if(isset($supplyInfos))
                                        {!! $supplyInfos->render() !!}
                                    @endif
                            </div>
                        </div>
                        </div>
                    <div class="row">
                        <div class="space-10"></div>
                        <div style="width: 100%; height: 45vh; overflow-x: auto; display: none;" id="addNew">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var token = '<?php echo csrf_token() ?>';
            var confirmWindow;
            var isHolder = '{!! $isHolder !!}';

            $('#shipName').change(function () {
                var curShipId = $(this).val();
                var voy = $('#voy').val();
                $.post('{{url('shipTechnique/getVoyListOfShip')}}', {
                    shipId: curShipId, _token: token
                }, function (data) {
                    $('#voy').chosen('destroy').html(data).chosen();
                });
            });

            function onSearch() {
                var curShipId = $('#shipName').val();
                var voy = $('#voy').val();
                if (curShipId == null || curShipId == '') {
                    $.gritter.add({
                        title: '오유',
                        text: '배이름을 선택해야 합니다.',
                        class_name: 'gritter-error'
                    });
                } else if (voy == null || voy == '') {
                    $.gritter.add({
                        title: '오유',
                        text: '항차번호를 선택해야 합니다.',
                        class_name: 'gritter-error'
                    });
                } else {
                    location.href = '{{url('shipTechnique/supplyRecord')}}' + '?shipId=' + curShipId + '&voy=' + voy;
                }
            }

            function onExcel() {
                var curShipId = $('#shipName').val();
                var voy = $('#voy').val();
                if (curShipId == null || curShipId == '') {
                    $.gritter.add({
                        title: '오유',
                        text: '배이름을 선택해야 합니다.',
                        class_name: 'gritter-error'
                    });
                } else if (voy == null || voy == '') {
                    $.gritter.add({
                        title: '오유',
                        text: '항차번호를 선택해야 합니다.',
                        class_name: 'gritter-error'
                    });
                } else {
                    location.href = '{{url('shipTechnique/supplyRecordExcel')}}' + '?shipId=' + curShipId + '&voy=' + voy;
                }
            }

            $('#supplyrecord tr').on('click', function () {
                if(isHolder == true) return;
                var id = $(this).data('id');
                $('#supplyrecord tr').removeClass("table-row-selected");
                $('#supplyrecord tr[data-id="' + id + '"]').addClass('table-row-selected');
                var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>'+'자료 적재중입니다. 잠시 기다려주십시오.';
                $("#modal-body-content").html(htm);
                $('#loading').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('shipTechnique/addApplication') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        shipId: '{{ $shipId }}',
                        supplyId: id
                    },
                    success: function (data) {
                        $('#addNew').html(data).fadeIn('fast');
                        $('#addNew .chosen-select').chosen('destroy').chosen();
                        $('#loading').hide();
                        $('.date-picker').datepicker({autoclose: true});
                    }
                });
            });

            function changeValue(type, value) {
                switch (type) {
                    case "equip":
                        $('select[name="Euipment_Cn"]').val(value);
                        $('#Equipment_Cn .chosen-container a span').text($("[name=Euipment_Cn] option[value=" + value + "]").text());
                        $('select[name="SN"]').val(value);
                        $('#SN .chosen-container a span').text($("[name=SN] option[value=" + value + "]").text());
                        break;
                    case "part":
                        $('select[name="PartName_Cn"]').val(value);
                        $('#PartName_Cn .chosen-container a span').text($("[name=PartName_Cn] option[value=" + value + "]").text());
                        $('select[name="PartNo"]').val(value);
                        $('#PartNo .chosen-container a span').text($("[name=PartNo] option[value=" + value + "]").text());
                        break;
                    case "content":
                        $('select[name="Content_Cn"]').val(value);
                        $('#Content_Cn .chosen-container a span').text($("[name=Content_Cn] option[value=" + value + "]").text());
                        $('select[name="CodeNo"]').val(value);
                        $('#CodeNo .chosen-container a span').text($("[name=CodeNo] option[value=" + value + "]").text());
                        break;
                    case "other":
                        $('select[name="Others_Cn"]').val(value);
                        $('#Others_Cn .chosen-container a span').text($("[name=Others_Cn] option[value=" + value + "]").text());
                        $('select[name="Special"]').val(value);
                        $('#Special .chosen-container a span').text($("[name=Special] option[value=" + value + "]").text());
                        break;
                }
            }

            function getVoyInfo() {
                var shipId = $('[name=shipName_Cn]').val();
                var kindId = $('[name=Kind_Cn]').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('shipTechnique/getInfo') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: 'voy',
                        shipId: shipId,
                        kindId: kindId
                    },
                    success: function(data) {
                        var temp = data.split('*****');
                        $('[name=ApplicationVoy]').html(temp[0]);
                        $('[name=Recipt]').html(temp[0]);
                        if(kindId == 1 || kindId == 2) {
                            $('[name=Euipment_Cn]').html(temp[1]);
                            $('[name=SN]').html(temp[2]);
                            $('[name=PartName_Cn]').html(temp[3]);
                            $('[name=PartNo]').html(temp[4]);
                        }
                        $('#addNew .chosen-select').chosen('destroy').chosen();
                    }
                });
            }

            function getEquipmentInfo() {
                var shipId = $('select[name="shipName_Cn"]').val();
                var kindId = $('select[name="Kind_Cn"]').val();
                if(kindId == 1 || kindId == 2) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('shipTechnique/getInfo') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: 'equipment',
                            shipId: shipId,
                            kindId: kindId
                        },
                        success: function (data) {
                            var temp = data.split('*****');
                            $('[name=Euipment_Cn]').html(temp[0]);
                            $('[name=SN]').html(temp[1]);
                            $('[name=PartName_Cn]').html(temp[2]);
                            $('[name=PartNo]').html(temp[3]);
                            $('.Equipment').show();
                            $('.Part').show();
                            $('.Code').hide();
                            $('.Other').hide();
                            $('#addNew .chosen-select').chosen('destroy').chosen();
                        }
                    });
                } else if(kindId == 3) {
                    $('.Equipment').hide();
                    $('.Part').hide();
                    $('.Code').show();
                    $('.Other').hide();
                } else {
                    $('.Equipment').hide();
                    $('.Part').hide();
                    $('.Code').hide();
                    $('.Other').show();
                }
            }

            function getPartInfo() {
                var equipId = $('select[name="Euipment_Cn"]').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('shipTechnique/getInfo') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: 'part',
                        equipId: equipId
                    },
                    success: function (data) {
                        var temp = data.split('*****');
                        $('select[name="PartName_Cn"]').html(temp[0]);
                        $('select[name="PartNo"]').html(temp[1]);
                        $('#addNew .chosen-select').chosen('destroy').chosen();
                    }
                });
            }

            function calcQuot() {
                var QuotQtty = $('input[name="QuotQtty"]').val();
                var QuotPrice = $('input[name="QuotPrice"]').val();
                var QuotAmount = (QuotQtty * QuotPrice).toFixed(2);
                console.log(QuotAmount);
                $('input[name="QuotAmount"]').val(QuotAmount);
                var MarketCondition_Usd = $('input[name="MarketCondition_Usd"]').val();
                var ReciptPrice;
                if(MarketCondition_Usd != 0 && MarketCondition_Usd != '')
                    ReciptPrice = (QuotPrice / MarketCondition_Usd).toFixed(2);
                else
                    ReciptPrice = 0;
                $('input[name="ReciptPrice"]').val(ReciptPrice);
                var ReciptQtty = $('input[name="ReciptQtty"]').val();
                var Amount = (ReciptQtty * ReciptPrice).toFixed(2);
                $('input[name="Amount"]').val(Amount);
                var DeliveryAmount = $('input[name="DeliveryAmount"]').val();
                var TotalAmount;
                if (DeliveryAmount == '' || DeliveryAmount == 0) {
                    TotalAmount = Amount;
                } else {
                    if(MarketCondition_Usd != 0 && MarketCondition_Usd != '')
                        TotalAmount = (parseFloat(Amount) + (DeliveryAmount / MarketCondition_Usd)).toFixed(2);
                    else
                        TotalAmount = 0;
                }
                $('input[name="TotalAmount"]').val(TotalAmount);
            }

            function getHistory(id) {
                var myWindow = window.open(
                        "{{ url('shipTechnique/getHistory') }}?action=history&supplyId=" + id,
                        "설비부속자재 신청 및 공급리력",
                        "width=" + screen.width +
                        ",height=" + screen.height +
                        "left=0px,top=0px"
                );
            }

            function confirmAdd() {
                var shipId = $('[name=shipName_Cn]').val();
                var kindId = $('[name=Kind_Cn]').val();
                var equipId = null;
                var partId = null;
                var issaId = null;
                var otherId = null;
                if(kindId == 1 || kindId == 2) {
                    equipId = $('[name=Euipment_Cn]').val();
                    partId = $('[name=PartName_Cn]').val();
                } else if(kindId == 3) {
                    issaId = $('[name=Content_Cn]').val();
                } else {
                    otherId = $('[name=Others_Cn]').val();
                }
                confirmWindow = window.open(
                        "{{ url('shipTechnique/getHistory') }}?action=confirm&shipId=" + shipId + "&kindId=" + kindId +
                        "&equipId=" + equipId + "&partId=" + partId + "&issaId=" + issaId + "&otherId=" + otherId,
                        "설비부속자재 신청 및 공급리력",
                        "width=" + screen.width +
                        ",height=" + screen.height +
                        "left=0px,top=0px"
                );
            }

            function deleteSupplyInfo() {
                var supplyId = $('[name="supplyId"]').val();
                bootbox.confirm("공급자료에 대한 삭제를 진행하겠습니까?", function(result) {
                    if(result) {
                        $.post('deleteSupplyInfo', {'_token': token, 'supplyId': supplyId}, function(data){
                            if(data == 'success') {
                                alert('자료가 삭제 되였습니다.');
                            } else {
                                alert('오유가 발생하여 삭제 할수 없습니다. 다시 시도해보십시오.');
                            }
                            window.location.reload(true);
                        });
                    }
                });
            }

            function addNewSupplyInfo() {
                confirmWindow.close();
                document.addNewSupplyInfo.submit();
            }

            function cancelNewSupplyInfo() {
                confirmWindow.close();
            }
        </script>
    @endif

@endsection