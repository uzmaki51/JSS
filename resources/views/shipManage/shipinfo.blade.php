<?php
if(isset($is_excel))
	$header = 'excel-header';
else
	$header = 'sidebar';

$isShareHolder = Auth::user()->isAdmin == IS_SHAREHOLDER ? true : false;
$shipList = explode(',', Auth::user()->shipList);
?>
<<<<<<< Updated upstream
@extends('layout.' . $header)
=======
@extends('layout.'.$header)

@section('styles')
{{--    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet">--}}
@endsection

@section('content')
>>>>>>> Stashed changes

@section('scripts')
    <script>
        $('#ship_list').on('change', function(e) {
           location.href = '/shipManage/shipinfo?id=' + $(this).val();
        });
    </script>
@endsection

@section('content')
    @if(!isset($is_excel))
        <div class="main-content">
<<<<<<< Updated upstream
=======
            <style>
                /*.table tr {*/
                    /*height: auto!important;*/
                /*}*/
                /*.table th {*/
                    /*white-space: nowrap;*/
                    /*background: #3498db;*/
                    /*color: white;*/
                    /*padding: 4px!important;*/
                    /*font-weight: bold!important;*/
                /*}*/
                /*.ship-list thead tr th {*/
                    /*height: 20px!important;*/
                    /*padding: 4px 0!important;*/
                    /*font-weight: normal;*/
                    /*background: #c9dfff;*/
                    /*color: black;*/
                    /*font-size: 12px!important;*/
                    /*font-style: italic;*/
                    /*border-left: 1px solid #484f5b!important;*/
                /*}*/
                /*.ship-list tr {*/
                    /*border: unset!important;*/
                    /*width: 100%;*/
                    /*box-sizing: border-box;*/
                /*}*/
                /*.ship-list tr td {*/
                    /*border-bottom: 1px solid #484f5b!important;*/
                    /*border-left: 1px solid #484f5b!important;*/
                    /*padding: 4px 0!important;*/
                /*}*/
                /*.ship-list tbody::-webkit-scrollbar {*/
                    /*display: none;*/
                /*}*/

            </style>
>>>>>>> Stashed changes
            <div class="page-content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <h4><b>Ship List</b></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-1">
                        <select class="form-control" id="ship_list">
                            @foreach($list as $key => $item)
                                <option value="{{ $item->id }}" {{ isset($id) && $id == $item->id ? 'selected' : '' }}>{{ empty($item->NickName) ? $item->shipName_En : $item->NickName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <div class="btn-group f-right">
                            <a href="exportShipInfo?id={{ $id }}" class="btn btn-warning btn-sm">
                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6"></div>
                </div>
<<<<<<< Updated upstream

                <div class="row">
                    <div class="col-lg-4">
                        <ul class="nav nav-tabs ship-register">
                            <li class="active">
                                <a data-toggle="tab" href="#general">
                                    规范
                                </a>
                            </li>

                            <li class="">
                                <a data-toggle="tab" href="#form_a">
                                    FORM A
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="general" class="tab-pane active">
=======
                <div class="col-md-12">
                    <div class="row">
                        @else
                            @include('layout.excel-style')
                        @endif
                        <div class="col-md-12 no-padding">
                            <table class="table table-bordered table-hover ship-list">
                                <thead>
                                    <tr class="black br-hblue">
                                        <th class="text-center" style="width: 2%;"><span>No</span></th>
                                        <th class="text-center" style="width: 10%;"><span>ShipName</span></th>
                                        <th class="text-center" style="width: 8%;"><span>IMO NO</span></th>
                                        <th class="text-center" style="width: 7%;"><span>Flag</span></th>
                                        <th class="text-center" style="width: 8%;"><span>Port of Registry</span></th>
                                        <th class="text-center" style="width: 7%;"><span>Class</span></th>
                                        <th class="text-center" style="width: 6%;"><span>GT</span></th>
                                        <th class="text-center" style="width: 6%;"><span>NT</span></th>
                                        <th class="text-center" style="width: 6%;"><span>DWT</span></th>
                                        <th class="text-center" style="width: 9%;"><span>ShipType</span></th>
                                        <th class="text-center" style="width: 7%;"><span>LOA</span></th>
                                        <th class="text-center" style="width: 8%;"><span>MB</span></th>
                                        <th class="text-center" style="width: 6%;"><span>DM</span></th>
                                        <th class="text-center" style="width: 6%;"><span>Draught</span></th>
                                        <th style="width: 4%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $index = 1; ?>
                                @if(isset($list) && count($list) > 0)
                                    @foreach ($list as $shipInfo)
                                        @if(!$isShareHolder || ($isShareHolder == true && in_array($shipInfo['id'], $shipList)))
                                            <tr>
                                                <td class="text-center" style="width: 2%;">{{ $index }}</td>
                                                <td class="text-center" style="width: 10%;">{{ $shipInfo['shipName_Cn'] }}</td>
                                                <td class="text-center" style="width: 8%;">{{ $shipInfo['IMO_No'] }}</td>
                                                <td class="text-center" style="width: 7%;">{{ $shipInfo['Flag'] }}</td>
                                                <td class="text-center" style="width: 8%;">{{ $shipInfo['PortOfRegistry'] }}</td>
                                                <td class="text-center" style="width: 7%;">{{ $shipInfo['Class'] }}</td>
                                                <td class="text-center" style="width: 6%;">{{ $shipInfo['GrossTon'] }}</td>
                                                <td class="text-center" style="width: 6%;">{{ $shipInfo['NetTon'] }}</td>
                                                <td class="text-center" style="width: 6%;">{{ $shipInfo['Deadweight'] }}</td>
                                                <td class="text-center" style="width: 9%;">{{ $shipInfo['ShipType'] }}</td>
                                                <td class="text-center" style="width: 7%;">{{ $shipInfo['LOA'] }}</td>
                                                <td class="text-center" style="width: 8%;">{{ $shipInfo['BM'] }}</td>
                                                <td class="text-center" style="width: 6%;">{{ $shipInfo['DM'] }}</td>
                                                <td class="text-center" style="width: 6%;">{{ $shipInfo['Draught'] }}</td>
                                                <td class="text-center" style="width: 4%;" id="{{ $shipInfo['id'] }}" name="{{ $shipInfo['shipName_Cn'] }}">
                                                    <div class="action-buttons">
                                                        <a class="blue" href="registerShipData?shipId={{ $shipInfo->id }}">
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                        @if(!$isShareHolder)
                                                            <a class="red" href="javascript:deleteItem('{{ $shipInfo['id'] }}', '{{ $shipInfo['shipName_Cn'] }}')">
                                                                <i class="icon-trash"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        <?php $index ++; ?>
                                    @endforeach
>>>>>>> Stashed changes
                                @else
                                    @include('layout.excel-style')
                                @endif
                                <table class="table table-bordered excel-output" id="excel-output">
                                    <thead>
                                        <tr>
                                            <th class="title" colspan="2" style="font-size: 16px;">SHIP PARTICULARS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP NAME</td>
                                        <td>@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td  style="background-color: #f8f8f8;" class="font-bold">IMO NO</td>
                                        <td>@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CLASS</td>
                                        <td>@if(isset($shipInfo['Class'])){{$shipInfo['Class']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CALL SIGN</td>
                                        <td>@if(isset($shipInfo['CallSign'])){{$shipInfo['CallSign']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MMSI NO</td>
                                        <td>@if(isset($shipInfo['MMSI'])){{$shipInfo['MMSI']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">INMARSAT Number (1/2)</td>
                                        <td>@if(isset($shipInfo['INMARSAT'])){{$shipInfo['INMARSAT']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ORIGINAL NAME</td>
                                        <td>@if(isset($shipInfo['OriginalShipName'])){{$shipInfo['OriginalShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FORMER NAME</td>
                                        <td>@if(isset($shipInfo['FormerShipName'])){{$shipInfo['FormerShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FLAG</td>
                                        <td>@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">REGISTRY PORT</td>
                                        <td>@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OWNER</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ISM COMPANY</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['ISM_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP TYPE</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['ShipType']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP BUILDER</td>
                                        <td>@if(isset($shipInfo['ShipBuilder'])){{$shipInfo['ShipBuilder']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BUILD DATE/PLACE</td>
                                        <td>@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GT</td>
                                        <td>@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">NT</td>
                                        <td>@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DWT</td>
                                        <td>@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LDT</td>
                                        <td>@if(isset($shipInfo['Displacement'])){{$shipInfo['Displacement']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LOA</td>
                                        <td>@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BM</td>
                                        <td>@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DM</td>
                                        <td>@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SUMMER DRAFT</td>
                                        <td>@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">TPC</td>
                                        <td>@if(isset($shipInfo['DeckErection_F'])){{$shipInfo['DeckErection_F']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FW_Allowance</td>
                                        <td>{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">M/E NO_TYPE</td>
                                        <td>{{ isset($shipInfo['No_TypeOfEngine']) ? $shipInfo['No_TypeOfEngine'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">POWER</td>
                                        <td>@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">RPM</td>
                                        <td>@if(isset($shipInfo['rpm'])){{$shipInfo['rpm']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MADE YEAR</td>
                                        <td>@if(isset($shipInfo['EngineDate'])){{$shipInfo['EngineDate']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SERVICE SPEED (Kn)</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PROPELLER DIA/PITCH...?</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GENERATOR SET</td>
                                        <td>{{ isset($shipInfo['PrimeMover']) ? $shipInfo['PrimeMover'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OUTPUT</td>
                                        <td>{{ isset($shipInfo['GeneratorOutput']) ? $shipInfo['GeneratorOutput'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER NO_TYPE</td>
                                        <td>@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER MAKER</td>
                                        <td>@if(isset($shipInfo['BoilerManufacturer'])){{$shipInfo['BoilerManufacturer']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PRESSURE</td>
                                        <td>{{ isset($shipInfo['BoilerPressure']) ? $shipInfo['BoilerPressure'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['FOSailCons_S'])){{$shipInfo['FOSailCons_S']}}@endif/@if(isset($shipInfo['FOL/DCons_S'])){{$shipInfo['FOL/DCons_S']}}@endif/@if(isset($shipInfo['FOIdleCons_S'])){{$shipInfo['FOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">>MDO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['DOSailCons_S'])){{$shipInfo['DOSailCons_S']}}@endif/@if(isset($shipInfo['DOL/DCons_S'])){{$shipInfo['DOL/DCons_S']}}@endif/@if(isset($shipInfo['DOIdleCons_S'])){{$shipInfo['DOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO/DO TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['FuelBunker'])){{$shipInfo['FuelBunker']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BALLAST TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['Ballast'])){{$shipInfo['Ballast']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLDS/HATCHES NO</td>
                                        <td>@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif / @if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD CAPACITY(G/B)㎥</td>
                                        <td>@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif / @if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HATCH COVER SIZE/TYPE</td>
                                        <td>{{ isset($shipInfo['SizeOfHatchways']) ? $shipInfo['SizeOfHatchways'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD SIZE</td>
                                        <td>@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CARGO GEAR</td>
                                        <td>@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HEIGHT FM KEEL TO MAST</td>
                                        <td>@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MAX PERMISSBLE LOAD(TANK TOP/ON DECK/HATCH COVER)</td>
                                        <td>@if(isset($shipInfo['TK_TOP'])){{ $shipInfo['TK_TOP'] . '/' }}@endif  @if(isset($shipInfo['ON_DECK'])){{ $shipInfo['ON_DECK'] . '/' }}@endif @if(isset($shipInfo['H_COVER'])){{$shipInfo['H_COVER']}}@endif</td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if(!isset($is_excel))
                            </div>
                            <div id="form_a" class="tab-pane">
                                <table class="table table-bordered excel-output" id="excel-output">
                                    <thead>
                                    <tr>
                                        <th class="title" colspan="2" style="font-size: 16px;">SHIP PARTICULARS</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP NAME</td>
                                        <td>@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td  style="background-color: #f8f8f8;" class="font-bold">IMO NO</td>
                                        <td>@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CLASS</td>
                                        <td>@if(isset($shipInfo['Class'])){{$shipInfo['Class']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CALL SIGN</td>
                                        <td>@if(isset($shipInfo['CallSign'])){{$shipInfo['CallSign']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MMSI NO</td>
                                        <td>@if(isset($shipInfo['MMSI'])){{$shipInfo['MMSI']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">INMARSAT Number (1/2)</td>
                                        <td>@if(isset($shipInfo['INMARSAT'])){{$shipInfo['INMARSAT']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ORIGINAL NAME</td>
                                        <td>@if(isset($shipInfo['OriginalShipName'])){{$shipInfo['OriginalShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FORMER NAME</td>
                                        <td>@if(isset($shipInfo['FormerShipName'])){{$shipInfo['FormerShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FLAG</td>
                                        <td>@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">REGISTRY PORT</td>
                                        <td>@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OWNER</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ISM COMPANY</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['ISM_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP TYPE</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['ShipType']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP BUILDER</td>
                                        <td>@if(isset($shipInfo['ShipBuilder'])){{$shipInfo['ShipBuilder']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BUILD DATE/PLACE</td>
                                        <td>@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GT</td>
                                        <td>@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">NT</td>
                                        <td>@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DWT</td>
                                        <td>@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LDT</td>
                                        <td>@if(isset($shipInfo['Displacement'])){{$shipInfo['Displacement']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LOA</td>
                                        <td>@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">>BM</td>
                                        <td>@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DM</td>
                                        <td>@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SUMMER DRAFT</td>
                                        <td>@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">TPC</td>
                                        <td>@if(isset($shipInfo['DeckErection_F'])){{$shipInfo['DeckErection_F']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FW_Allowance</td>
                                        <td>{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">M/E NO_TYPE</td>
                                        <td>{{ isset($shipInfo['No_TypeOfEngine']) ? $shipInfo['No_TypeOfEngine'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">POWER</td>
                                        <td>@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">RPM</td>
                                        <td>@if(isset($shipInfo['rpm'])){{$shipInfo['rpm']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MADE YEAR</td>
                                        <td>@if(isset($shipInfo['EngineDate'])){{$shipInfo['EngineDate']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SERVICE SPEED (Kn)</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PROPELLER DIA/PITCH...?</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GENERATOR SET</td>
                                        <td>{{ isset($shipInfo['PrimeMover']) ? $shipInfo['PrimeMover'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OUTPUT</td>
                                        <td>{{ isset($shipInfo['GeneratorOutput']) ? $shipInfo['GeneratorOutput'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER NO_TYPE</td>
                                        <td>@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER MAKER</td>
                                        <td>@if(isset($shipInfo['BoilerManufacturer'])){{$shipInfo['BoilerManufacturer']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PRESSURE</td>
                                        <td>{{ isset($shipInfo['BoilerPressure']) ? $shipInfo['BoilerPressure'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['FOSailCons_S'])){{$shipInfo['FOSailCons_S']}}@endif/@if(isset($shipInfo['FOL/DCons_S'])){{$shipInfo['FOL/DCons_S']}}@endif/@if(isset($shipInfo['FOIdleCons_S'])){{$shipInfo['FOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">>MDO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['DOSailCons_S'])){{$shipInfo['DOSailCons_S']}}@endif/@if(isset($shipInfo['DOL/DCons_S'])){{$shipInfo['DOL/DCons_S']}}@endif/@if(isset($shipInfo['DOIdleCons_S'])){{$shipInfo['DOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO/DO TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['FuelBunker'])){{$shipInfo['FuelBunker']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BALLAST TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['Ballast'])){{$shipInfo['Ballast']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLDS/HATCHES NO</td>
                                        <td>@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif / @if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD CAPACITY(G/B)㎥</td>
                                        <td>@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif / @if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HATCH COVER SIZE/TYPE</td>
                                        <td>{{ isset($shipInfo['SizeOfHatchways']) ? $shipInfo['SizeOfHatchways'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD SIZE</td>
                                        <td>@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CARGO GEAR</td>
                                        <td>@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HEIGHT FM KEEL TO MAST</td>
                                        <td>@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MAX PERMISSBLE LOAD(TANK TOP/ON DECK/HATCH COVER)</td>
                                        <td>@if(isset($shipInfo['TK_TOP'])){{ $shipInfo['TK_TOP'] . '/' }}@endif  @if(isset($shipInfo['ON_DECK'])){{ $shipInfo['ON_DECK'] . '/' }}@endif @if(isset($shipInfo['H_COVER'])){{$shipInfo['H_COVER']}}@endif</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="space-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif
@endsection
