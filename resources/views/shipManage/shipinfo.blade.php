<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
$isShareHolder = Auth::user()->isAdmin == IS_SHAREHOLDER ? true : false;
$shipList = explode(',', Auth::user()->shipList);
?>
@extends('layout.'.$header)

@section('styles')
{{--    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet">--}}
@endsection

@section('content')

    @if(!isset($excel))

        <div class="main-content">
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
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>Ship List</b></h4>
                    </div>
                    <div class="col-sm-6">
                        <div class="btn-group f-right">
                        @if(!$isShareHolder)
                            <a href="{{ url('shipManage/registerShipData') }}" data-toggle="modal" class="btn btn-sm btn-primary no-radius"><i class="icon-plus"></i>{{ trans('common.label.add') }}</a>
                        @endif
                            <a href="shipInfoExcel" class="btn btn-warning btn-sm">
                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                            </a>
                        </div>
                    </div>
                </div>
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
                                @else
                                    <div>
                                        {{ trans('common.message.no_data') }}
                                    </div>
                                @endif
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
            function deleteItem(shipId, shipName) {
                bootbox.confirm(shipName + "的船舶规范真要删除吗?", function (result) {
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
            }

        </script>
    @endif
@stop
