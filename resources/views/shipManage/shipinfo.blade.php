<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
$isShareHolder = Auth::user()->isAdmin == IS_SHAREHOLDER ? true : false;
$shipList = explode(',', Auth::user()->shipList);
?>
@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <style>
                .table th {height: 50px !important;white-space: nowrap}
                .table td {height: 27px;overflow: hidden;white-space: nowrap}
            </style>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>船舶诸元</b>
                            <small>
                                <i class="icon-double-angle-right"></i>船目录
                            </small>
                        </h4>
                    </div>
                    <div class="col-sm-6" style="text-align: right; margin-top: 50px">
                        @if(!$isShareHolder)
                        <a href="{{ url('shipManage/registerShipData') }}" data-toggle="modal" class="btn btn-sm btn-primary no-radius"
                           style="width: 80px"
                        ><i class="icon-plus-sign-alt"></i>追加</a>
                        @endif
                        <a href="shipinfoExcel" class="btn btn-warning btn-sm"
                        ><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="space-4"></div>
                        @else
                            @include('layout.excel-style')
                        @endif
                        <div class="col-md-2 no-padding">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr class="black br-hblue">
                                    <th rowspan="2" style="width:50px;">{{ transShipManager('shipinfo.No') }}</th>
                                    <th>{!! transShipManager('shipinfo.ShipName(structure)') !!}</th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th>{!! transShipManager('shipinfo.shipName') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php $index = 1; ?>
                                @foreach ($list as $shipinfo)
                                    @if(!$isShareHolder)
                                        <tr data-id="{{ $shipinfo->id }}" data-name="{{ $shipinfo['shipName_Cn'] }}">
                                            <td rowspan="2">{{ $index++ }}</td>
                                            <td class="center">
                                                @if(Config::get('app.locale') == 'en'){{$shipinfo['shipNo']}}@else{{$shipinfo['name']}}@endif
                                                <div class="action-buttons" style="float: right">
                                                    <a class="blue" href="registerShipData?shipId={{ $shipinfo->id }}">
                                                        <i class="icon-edit"></i>
                                                    </a>

                                                    <a class="red del-btn" href="#">
                                                        <i class="icon-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipinfo['shipName_En'] }}@else{{ $shipinfo['shipName_Cn'] }}@endif</td>
                                        </tr>
                                    @elseif($isShareHolder)
                                        @if(in_array($shipinfo['id'], $shipList))
                                            <tr data-id="{{ $shipinfo->id }}" data-name="{{ $shipinfo['shipName_Cn'] }}">
                                                <td rowspan="2">{{ $index++ }}</td>
                                                <td class="center">
                                                    @if(Config::get('app.locale') == 'en'){{$shipinfo['shipNo']}}@else{{$shipinfo['name']}}@endif
                                                    <div class="action-buttons" style="float: right">
                                                        <a class="blue" href="registerShipData?shipId={{ $shipinfo->id }}">
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                        @if(!$isShareHolder)
                                                            <a class="red del-btn" href="#">
                                                                <i class="icon-trash"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipinfo['shipName_En'] }}@else{{ $shipinfo['shipName_Cn'] }}@endif</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-10 no-padding" style="overflow-x: scroll;width:82%">
                            <table class="table table-striped table-bordered table-hover" style="width:2850px">
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center">{!! transShipManager('shipinfo.Owner') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Flag') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.IMO') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Class') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Builder') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.MMSI') !!}</th>
                                    <th class="center" rowspan="2">{!! transShipManager('shipinfo.LOA') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.BM') !!}</th>
                                    <th class="center" rowspan="2">{!! transShipManager('shipinfo.Draught') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.GT') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Displacement') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Hold') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Lifting Device') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.M/E') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.A/E') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Anchorage Engine') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Boiler') !!}</th>
                                    <th class="center" colspan="2">{!! transShipManager('shipinfo.Fuel') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Persons') !!}</th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th class="center">{!! transShipManager('shipinfo.ISM') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Port of Registry') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Ship Type') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Call Sign') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Build Date') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.INMARSAT') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.DM') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.NT') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.DeadWeight') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.HatchWays') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Container') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.M/E number') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.A/E number') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Anchorage number') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Boiler number') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Summer') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.Winder') !!}</th>
                                    <th class="center">{!! transShipManager('shipinfo.MSMN') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($list as $shipInfo)
                                    @if(!$isShareHolder)
                                        <tr>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['Owner_En'] }} @else {{ $shipInfo['Owner_Cn'] }}@endif</td>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['Flag'] }} @else {{ $shipInfo['Flag_Cn'] }} @endif</td>
                                            <td class="center">{{ $shipInfo['IMO_No'] }}</td>
                                            <td class="center">{{ $shipInfo['Class'] }}</td>
                                            <td class="center">{{ $shipInfo['ShipBuilder'] }}</td>
                                            <td class="center">{{ $shipInfo['MMSI'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['LOA'] .'/' .$shipInfo['LBP'] .'/' .$shipInfo['Length'] }}</td>
                                            <td class="center">{{ $shipInfo['BM'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['Draught'] }}</td>
                                            <td class="center">{{ $shipInfo['GrossTon'] }}</td>
                                            <td class="center">{{ $shipInfo['Displacement'] }}</td>
                                            <td class="center">{{ $shipInfo['NumberOfHolds'] .'/' .$shipInfo['CapacityOfHoldsG'].'-'.$shipInfo['CapacityOfHoldsB'] }}</td>
                                            <td class="center">{{ $shipInfo['LiftingDevice'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['No_TypeOfEngine'] .'/' .$shipInfo['Power'] .'/' .$shipInfo['rpm'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['PrimeMover'] .'/' .$shipInfo['GeneratorOutput'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['AnchorageType'] .'/' .$shipInfo['AnchoragePower'] .'/' .$shipInfo['AnchorageRPM']}}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['Boiler'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['FOSailCons_S'] .'/' .$shipInfo['DOSailCons_S'] .'/' .$shipInfo['LOSailCons_S'] }}</td>
                                            <td class="center" rowspan="2">{{ $shipInfo['FOSailCons_W'] .'/' .$shipInfo['DOSailCons_W'] .'/' .$shipInfo['LOSailCons_W'] }}</td>
                                            <td class="center">{{ $shipInfo['navi_count'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{ $shipInfo['ISM_Cn'] }}</td>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['PortOfRegistry'] }}@else{{ $shipInfo['PortOfRegistry_Cn'] }}@endif</td>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['ShipType'] }}@else{{ $shipInfo['ShipType_Cn'] }}@endif</td>
                                            <td class="center">{{ $shipInfo['CallSign'] }}</td>
                                            <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['BuildPlace'] }}@else{{ $shipInfo['BuildPlace_Cn'] }}@endif</td>
                                            <td class="center">{{ $shipInfo['INMARSAT'] }}</td>
                                            <td class="center">{{ $shipInfo['DM'] }}</td>
                                            <td class="center">{{ $shipInfo['NetTon'] }}</td>
                                            <td class="center">{{ $shipInfo['Deadweight'] }}</td>
                                            <td class="center">{{ $shipInfo['NumberOfHatchways'] .'/' .$shipInfo['SizeOfHatchways'] }}</td>
                                            <td class="center">{{ $shipInfo['ContainerOnDeck'] .'/' .$shipInfo['ContainerInHold'] }}</td>
                                            <td class="center">{{ $shipInfo['personSum'] }}</td>
                                        </tr>
                                    @elseif($isShareHolder)
                                        @if(in_array($shipInfo['id'], $shipList))
                                            <tr>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['Owner_En'] }} @else {{ $shipInfo['Owner_Cn'] }}@endif</td>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['Flag'] }} @else {{ $shipInfo['Flag_Cn'] }} @endif</td>
                                                <td class="center">{{ $shipInfo['IMO_No'] }}</td>
                                                <td class="center">{{ $shipInfo['Class'] }}</td>
                                                <td class="center">{{ $shipInfo['ShipBuilder'] }}</td>
                                                <td class="center">{{ $shipInfo['MMSI'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['LOA'] .'/' .$shipInfo['LBP'] .'/' .$shipInfo['Length'] }}</td>
                                                <td class="center">{{ $shipInfo['BM'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['Draught'] }}</td>
                                                <td class="center">{{ $shipInfo['GrossTon'] }}</td>
                                                <td class="center">{{ $shipInfo['Displacement'] }}</td>
                                                <td class="center">{{ $shipInfo['NumberOfHolds'] .'/' .$shipInfo['CapacityOfHoldsG'].'-'.$shipInfo['CapacityOfHoldsB'] }}</td>
                                                <td class="center">{{ $shipInfo['LiftingDevice'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['No_TypeOfEngine'] .'/' .$shipInfo['Power'] .'/' .$shipInfo['rpm'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['PrimeMover'] .'/' .$shipInfo['GeneratorOutput'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['AnchorageType'] .'/' .$shipInfo['AnchoragePower'] .'/' .$shipInfo['AnchorageRPM']}}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['Boiler'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['FOSailCons_S'] .'/' .$shipInfo['DOSailCons_S'] .'/' .$shipInfo['LOSailCons_S'] }}</td>
                                                <td class="center" rowspan="2">{{ $shipInfo['FOSailCons_W'] .'/' .$shipInfo['DOSailCons_W'] .'/' .$shipInfo['LOSailCons_W'] }}</td>
                                                <td class="center">{{ $shipInfo['navi_count'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="center">{{ $shipInfo['ISM_Cn'] }}</td>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['PortOfRegistry'] }}@else{{ $shipInfo['PortOfRegistry_Cn'] }}@endif</td>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['ShipType'] }}@else{{ $shipInfo['ShipType_Cn'] }}@endif</td>
                                                <td class="center">{{ $shipInfo['CallSign'] }}</td>
                                                <td class="center">@if(Config::get('app.locale') == 'en'){{ $shipInfo['BuildPlace'] }}@else{{ $shipInfo['BuildPlace_Cn'] }}@endif</td>
                                                <td class="center">{{ $shipInfo['INMARSAT'] }}</td>
                                                <td class="center">{{ $shipInfo['DM'] }}</td>
                                                <td class="center">{{ $shipInfo['NetTon'] }}</td>
                                                <td class="center">{{ $shipInfo['Deadweight'] }}</td>
                                                <td class="center">{{ $shipInfo['NumberOfHatchways'] .'/' .$shipInfo['SizeOfHatchways'] }}</td>
                                                <td class="center">{{ $shipInfo['ContainerOnDeck'] .'/' .$shipInfo['ContainerInHold'] }}</td>
                                                <td class="center">{{ $shipInfo['personSum'] }}</td>
                                            </tr>
                                        @endif
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                            @if(!isset($excel))
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.main-content -->
        <script>
            var pageNum = 0;
            var token = '<?php echo csrf_token() ?>';

            $(function() {
                $('.del-btn').on('click', function () {
                    var trObj = $(this).closest('tr');
                    var shipId = trObj.data('id');
                    var shipName = trObj.data('name');

                    bootbox.confirm(shipName + "的船舶诸元真要删掉吗?", function (result) {
                        if (result) {
                            $.post('deleteShipData', {'_token':token, 'dataId':shipId}, function (result) {
                                var code = parseInt(result);
                                if (code > 0) {
                                    location.reload();
                                } else {

                                }
                            });
                        }
                    });
                })
            });

        </script>
    @endif
@stop
