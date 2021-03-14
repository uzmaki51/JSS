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
                .table tr {
                    height: auto!important;
                }
                .table th {
                    white-space: nowrap;
                    background: #3498db;
                    color: white;
                    padding: 4px!important;
                    font-weight: bold!important;
                }

                td, th {
                    border: 1px solid #ccc!important;
                }
            </style>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>船舶规范</b>
                            <small>
                                <i class="icon-double-angle-right"></i>船舶目录
                            </small>
                        </h4>
                    </div>
                    <div class="col-sm-6" style="text-align: right; margin-top: 50px">
                        @if(!$isShareHolder)
                        <a href="{{ url('shipManage/registerShipData') }}" data-toggle="modal" class="btn btn-sm btn-primary no-radius"
                           style="width: 80px"
                        ><i class="icon-plus-sign-alt"></i>添加</a>
                        @endif
                        <a href="shipInfoExcel" class="btn btn-warning btn-sm"
                        ><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="space-4"></div>
                        @else
                            @include('layout.excel-style')
                        @endif
                        <div class="col-md-12 no-padding">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr class="black">
                                        <th>No</th>
                                        <th>ShipName</th>
                                        <th>IMO NO</th>
                                        <th>Flag</th>
                                        <th>Port of Registry</th>
                                        <th>Class</th>
                                        <th>GT</th>
                                        <th>NT</th>
                                        <th>DWT</th>
                                        <th>ShipType</th>
                                        <th>LOA</th>
                                        <th>MB</th>
                                        <th>DM</th>
                                        <th>Draught</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $index = 1; ?>
                                @if(isset($list) && count($list) > 0)
                                    @foreach ($list as $shipInfo)
                                        @if(!$isShareHolder || ($isShareHolder == true && in_array($shipInfo['id'], $shipList)))
                                            <tr>
                                                <td>{{ $index }}</td>
                                                <td>{{ $shipInfo['shipName_Cn'] }}</td>
                                                <td>{{ $shipInfo['IMO_No'] }}</td>
                                                <td>{{ $shipInfo['Flag'] }}</td>
                                                <td>{{ $shipInfo['PortOfRegistry'] }}</td>
                                                <td>{{ $shipInfo['Class'] }}</td>
                                                <td>{{ $shipInfo['GrossTon'] }}</td>
                                                <td>{{ $shipInfo['NetTon'] }}</td>
                                                <td>{{ $shipInfo['Deadweight'] }}</td>
                                                <td>{{ $shipInfo['ShipType'] }}</td>
                                                <td>{{ $shipInfo['LOA'] }}</td>
                                                <td>{{ $shipInfo['BM'] }}</td>
                                                <td>{{ $shipInfo['DM'] }}</td>
                                                <td>{{ $shipInfo['Draught'] }}</td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a class="blue" href="registerShipData?shipId={{ $shipInfo->id }}">
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

            $(function() {
                $('.del-btn').on('click', function () {
                    var trObj = $(this).closest('tr');
                    var shipId = trObj.data('id');
                    var shipName = trObj.data('name');

                    bootbox.confirm(shipName + "的船舶规范真要删掉吗?", function (result) {
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
