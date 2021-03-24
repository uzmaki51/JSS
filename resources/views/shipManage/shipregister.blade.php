@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>Ship Register</b>
                        <!--small>
                            <i class="icon-double-angle-right"></i>
                        </small-->
                    </h4>
                </div>
                <div class="col-sm-6"></div>
                <div class="col-sm-3">
                    @if(!$isHolder)
                        <div class="btn-group f-right">
                            <a href="/shipManage/registerShipData" class="btn btn-sm btn-primary btn-add" style="width: 80px">
                                <i class="icon-plus"></i>{{ trans('common.label.add') }}
                            </a>
                            <button type="submit" id="btnRegister" class="btn btn-sm btn-info" style="width: 80px">
                                <i class="icon-save"></i>{{ trans('common.label.save') }}
                            </button>
                        </div>
                    @endif
                </div>

            </div>
            <div class="col-md-12">
                <div id="item-manage-dialog" class="hide"></div>
                <div class="row">
                    <div class="head-fix-div">
                        <table>
                            <thead>
                            <tr>
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
                                <th class="text-center" style="width: 7%;"><span>DM</span></th>
                                <th class="text-center" style="width: 7%;"><span>Draught</span></th>
                                <th style="width: 2%;"></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php $index = 1; ?>
                            @if(isset($list) && count($list) > 0)
                                @foreach ($list as $item)
                                    @if(!$isHolder || ($isHolder == true && in_array($item['id'], $shipList)))
                                        <tr class="ship-item {{ $item['id'] == $shipInfo['id'] ? 'selected' : '' }}" data-index="{{ $item['id'] }}">
                                            <td class="text-center" style="width: 2%;">{{ $index }}</td>
                                            <td class="text-center" style="width: 10%;">{{ $item['shipName_Cn'] }}</td>
                                            <td class="text-center" style="width: 8%;">{{ $item['IMO_No'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['Flag'] }}</td>
                                            <td class="text-center" style="width: 8%;">{{ $item['PortOfRegistry'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['Class'] }}</td>
                                            <td class="text-center" style="width: 6%;">{{ $item['GrossTon'] }}</td>
                                            <td class="text-center" style="width: 6%;">{{ $item['NetTon'] }}</td>
                                            <td class="text-center" style="width: 6%;">{{ $item['Deadweight'] }}</td>
                                            <td class="text-center" style="width: 9%;">{{ $item['ShipType'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['LOA'] }}</td>
                                            <td class="text-center" style="width: 8%;">{{ $item['BM'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['DM'] }}</td>
                                            <td class="text-center" style="width: 7%;">{{ $item['Draught'] }}</td>
                                            <td class="text-center" style="width: 2%;">
                                                <div class="action-buttons">
                                                    @if(!$isHolder)
                                                        <a class="red" href="javascript:deleteItem('{{ $item['id'] }}', '{{ $item['shipName_Cn'] }}')">
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
                    </div>
                </div>

                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs ship-register" id="myTab">
                            <li class="{{ !isset($tabName) || $tabName == 'general' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#general" onclick="ShowTabPage('general')">
                                    {{ transShipManager('tabMenu.General') }}
                                </a>
                            </li>

                            <li class="{{ $tabName == 'hull' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#hull"  onclick="ShowTabPage('hull')">
                                    {{ transShipManager('tabMenu.Hull/Cargo') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == 'machiery' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#machiery" onclick="ShowTabPage('machiery')">
                                    {{ transShipManager('tabMenu.Machinery') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == 'remarks' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#remarks" onclick="ShowTabPage('remarks')">
                                    {{ transShipManager('tabMenu.Remarks') }}
                                </a>
                            </li>
                            <li>
                                <div class="alert alert-block alert-success center visuallyhidden">
                                    <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                                    <strong id="msg-content"> Please register a new ship.</strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <form role="form" method="POST" action="{{url('shipManage/saveShipData')}}" enctype="multipart/form-data" id="general-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="shipId"
                               value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
                        <input type="hidden" name="_tabName" value="general">
                        <div class="tab-content">
                            <div id="general" class="tab-pane {{ !isset($tabName) || $tabName == 'general' ? 'active' : '' }}">
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            </div>
                            <div id="hull" class="tab-pane {{ $tabName == 'hull' ? 'active' : '' }}">
                                @include('shipManage.tab_hull', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo, 'freeBoard'=>$freeBoard]))
                            </div>
                            <div id="machiery" class="tab-pane {{ $tabName == 'machiery' ? 'active' : '' }}">
                                @include('shipManage.tab_machinery', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            </div>
                            <div id="remarks" class="tab-pane {{ $tabName == 'remarks' ? 'active' : '' }}">
                                @include('shipManage.tab_remarks', with(['shipInfo'=>$shipInfo]))
                            </div>
                            <div class="space-4"></div>
                        </div>
                    </form>
                </div>
                <div class="vspace-xs-12"></div>
            </div>
            <a href="#modify-dialog" role="button" class="hidden" data-toggle="modal" id="dialog-show-btn"></a>
            <div id="modify-dialog" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" id="item-modify-dialog">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable-photo.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ajaxfileupload.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.colorbox-min.js') }}"></script>

    <script type="text/javascript">

        var token = '{!! csrf_token() !!}';
        var shipId = '{!! $shipInfo['id'] !!}';
        var activeTabName = '{{ $tabName }}';

        //editables on first profile page
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit"><i class="icon-ok icon-white"></i></button>';

        function ShowTabPage(tabName) {
            $("[name=_tabName]").val(tabName);
        }


        $('#btnRegister').on('click', function() {
            $('form').validate();
            $('form').submit();
        });

        $('.ship-item').on('click', function() {
            if($(this).hasClass('selected'))
                return;

            let ship_id = $(this).attr('data-index');
            location.href = BASE_URL + 'shipManage/registerShipData?shipId=' + ship_id;
        });

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



        $(function() {
            if(shipId.length < 1) {
                $('.alert').toggleClass('visuallyhidden');
                setTimeout(function() {
                    $('.alert').toggleClass('visuallyhidden');
                }, 2000);
                $('[name=shipName_Cn]').focus();
            }
        })

    </script>
@stop