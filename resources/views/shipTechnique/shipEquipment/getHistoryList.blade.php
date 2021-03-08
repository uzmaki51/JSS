@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <style>
            .chosen-container.chosen-container-single a {
                height: 26px;
            }

            .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tfoot > tr > td {
                padding: 5px;
                font-size: 12px;
            }
            header#header, div.sidebar#sidebar {
                display: none;
            }
            div.main-content {
                margin: 0px;
            }
            #main-container {
                margin-top: 0px;
            }
        </style>
        <div class="page-content">
            <div class="page-year-view">

                <div class="col-md-12">
                    <div class="row" style="width: 100%;">
                        <div class="col-md-12" style="text-align: center;">
                            @if($action == 'history')
                                <h2>설비부속자재 신청 및 공급리력</h2>
                            @else
                                <h2>설비부속자재 신청 확인</h2>
                            @endif
                        </div>
                        <div style="width: 100%;">
                            <div style="width: 100%; overflow-y: scroll;">
                                <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0px;">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("getHistory.Ship")}}</th>
                                        <th class="center" style="width: 4%; word-break: break-all;">{{transShipTech("getHistory.Dept")}}</th>
                                        <th class="center" colspan="3" style="width: 15%; word-break: break-all;">{{transShipTech("getHistory.Equipment")}}</th>
                                        <th class="center" style="width: 15%; word-break: break-all;">{{transShipTech("getHistory.Part/Issa/Others")}}</th>
                                        <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("getHistory.Suggest Date")}}</th>
                                        <th class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{transShipTech("getHistory.QuotObject")}}</th>
                                        <th class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{transShipTech("getHistory.Receipt Voy")}}</th>
                                        <th class="center" style="width: 10%; word-break: break-all;">{{transShipTech("getHistory.Place")}}</th>
                                        <th class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{transShipTech("getHistory.Supply Date")}}</th>
                                        <th class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{transShipTech("getHistory.Qtty")}}</th>
                                        <th class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{transShipTech("getHistory.Unit")}}</th>
                                        <th class="center" rowspan="2" style="width: 4%; word-break: break-all;">{{transShipTech("getHistory.Price")}}[$]</th>
                                        <th class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{transShipTech("getHistory.Amount")}}[$]</th>
                                        <th class="center" rowspan="2" style="width: 4%; word-break: break-all;">{{transShipTech("getHistory.Total")}}[$]</th>
                                        <th rowspan="2" class="center" style="width: 9%; word-break: break-all;">{{transShipTech("getHistory.Remark")}}</th>
                                    </tr>
                                    <tr class="black br-hblue">
                                        <th class="center" style="word-break: break-all;">{{transShipTech("getHistory.AppVoy")}}</th>
                                        <th class="center" style="word-break: break-all;">{{transShipTech("getHistory.Kind")}}</th>
                                        <th class="center" colspan="3" style="word-break: break-all;">{{transShipTech("getHistory.Label/Type/SerialNo")}}</th>
                                        <th class="center" style="word-break: break-all;">{{transShipTech("getHistory.PartNo/IssaCodeNo/Special")}}</th>
                                        <th class="center" style="word-break: break-all;">{{transShipTech("getHistory.Order Date")}}</th>
                                        <th class="center" style="word-break: break-all;">{{transShipTech("getHistory.Supplier")}}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div style="width: 100%; overflow-y: scroll; height: 65vh">
                                <table id="tbl_app" class="table table-striped table-bordered table-hover">
                                    <tbody id="supplyHistory">
                                    @foreach($supplyInfos as $supplyInfo)
                                        <tr>
                                            <td class="center" style="width: 6%; word-break: break-all;">{{$supplyInfo['shipName_Cn']}}</td>
                                            <td class="center" style="width: 4%; word-break: break-all;">{{ $supplyInfo['Dept_Cn'] }}</td>
                                            <td class="center" colspan="3" style="width: 15%; word-break: break-all;">{{ $supplyInfo['Euipment_Cn'] }}</td>
                                            @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['PartName_Cn'] }}</td>
                                            @elseif($supplyInfo['SSkind'] == 3)
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['Content_Cn'] }}</td>
                                            @else
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['Others_Cn'] }}</td>
                                            @endif
                                            <td class="center" style="width: 6%; word-break: break-all;">{{ $supplyInfo['QuotDate'] }}</td>
                                            <td class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{ $supplyInfo['QuotObject'] }}</td>
                                            <td class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{ $supplyInfo->Recipt->Voy_No }}</td>
                                            <td class="center" style="width: 10%; word-break: break-all;">{{ $supplyInfo['Port_Cn'] }}</td>
                                            <td class="center" rowspan="2" style="width: 6%; word-break: break-all;">{{ $supplyInfo['ReciptDate'] }}</td>
                                            <td class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{ $supplyInfo['ReciptQtty'] }}</td>
                                            <td class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{ $supplyInfo['Unit_Cn'] }}</td>
                                            <td class="center" rowspan="2" style="width: 4%; word-break: break-all;">{{ $supplyInfo['ReciptPrice'] }}</td>
                                            <td class="center" rowspan="2" style="width: 3%; word-break: break-all;">{{ $supplyInfo['Amount'] }}</td>
                                            <td class="center" rowspan="2" style="width: 4%; word-break: break-all;">{{ $supplyInfo['TotalAmount'] }}</td>
                                            <td rowspan="2" rowspan="2" class="center" style="width: 9%; word-break: break-all;">{{ $supplyInfo['ReciptRemark'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="center" style="word-break: break-all;">{{ $supplyInfo->Application->Voy_No }}</td>
                                            <td class="center" style="word-break: break-all;">{{ $supplyInfo['Kind_Cn'] }}</td>
                                            <td class="center" style="width: 5%; word-break: break-all;">{{ $supplyInfo['Label'] }}</td>
                                            <td class="center" style="width: 5%; word-break: break-all;">{{ $supplyInfo['Type'] }}</td>
                                            <td class="center" style="width: 5%; word-break: break-all;">{{ $supplyInfo['SN'] }}</td>
                                            @if($supplyInfo['SSkind'] == 1 || $supplyInfo['SSkind'] == 2)
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['PartNo'] }}</td>
                                            @elseif($supplyInfo['SSkind'] == 3)
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['CodeNo'] }}</td>
                                            @else
                                                <td class="center" style="width: 15%; word-break: break-all;">{{ $supplyInfo['Special'] }}</td>
                                            @endif
                                            <td class="center">{{ $supplyInfo['SupplyApplDate'] }}</td>
                                            <td class="center">{{ $supplyInfo['Supplier'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if($action != 'history')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4"></div>
                                <div class="col-md-2" style="text-align: center;">
                                    <a class="btn btn-xs btn-info no-radius" onclick="window.opener.addNewSupplyInfo()">
                                        확인
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-xs btn-danger no-radius" onclick="window.opener.cancelNewSupplyInfo()">
                                        취소
                                    </a>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection