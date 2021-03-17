@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>

@section('content')

    <div class="main-content">
        <style>
            .custom-td-report-text{
                width: 25%;
            }
            .ship-list thead tr th {
                height: 20px!important;
                padding: 4px!important;
                font-weight: normal;
                background: #c9dfff;
                color: black;
                font-size: 12px!important;
                font-style: italic;
                /*border: unset!important;*/
            }
            .ship-list tr {
                border: unset!important;
                display: table; /* display purpose; th's border */
                width: 100%;
                box-sizing: border-box; /* because of the border (Chrome needs this line, but not FF) */
            }
            .ship-list tr td {
                border-bottom: 1px solid #cccccc!important;
                border-left: 1px solid #cccccc!important;
                border-bottom: none!important;
                border-left: none!important;
                padding: 4px 0!important;
            }
            .ship-list tbody::-webkit-scrollbar {
                display: none;
            }
            .ship-list tbody {
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
            }
            /*.ship-list tr span {*/
                /*border: 1px solid red;*/
                /*padding: 4px;*/
                /*display: block;*/
                /**/
            /*}*/
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>Ship Register</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6" style="visibility: hidden;">
                    <div class="col-md-8 alert alert-block alert-success center" @if(is_null($status)) style="display: none @endif">
                        <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                        <strong id="msg-content"> @if($status == 'success') 保存成功。 @else {{ $status }} @endif</strong>
                    </div>
                </div>
                <div class="col-sm-3">
                    <h5 style="float: right"><a href="shipinfo"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div id="item-manage-dialog" class="hide"></div>

                <div class="row">
                    <table class="table table-bordered ship-list">
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
                        <tbody style="max-height: 66px; overflow-y: scroll; display: block; width: 100%;">
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

                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs ship-register" id="myTab">
                            <li class="{{ $tabName == '#general' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#general" onclick="ShowTabPage('#general')">
                                    {{ transShipManager('tabMenu.General') }}
                                </a>
                            </li>

                            <li class="{{ $tabName == '#hull' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#hull" onclick="ShowTabPage('#hull')">
                                    {{ transShipManager('tabMenu.Hull/Cargo') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == '#machiery' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#machiery" onclick="ShowTabPage('#machiery')">
                                    {{ transShipManager('tabMenu.Machinery') }}
                                </a>
                            </li>
                            <li class="{{ $tabName == '#remarks' ? 'active' : '' }}">
                                <a data-toggle="tab" href="#remarks" onclick="ShowTabPage('#remarks')">
                                    {{ transShipManager('tabMenu.Remarks') }}
                                </a>
                            </li>
                            <li style="float: right;">
                                @if(!$isHolder)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" id="btnRegister" class="btn btn-sm btn-inverse" style="width: 80px">
                                                <i class="icon-save"></i>登记
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="general" class="tab-pane {{ $tabName == '#general' ? 'active' : '' }}">
                            @if($tabName == '#general')
                                @include('shipManage.tab_general', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="hull" class="tab-pane {{ $tabName == '#hull' ? 'active' : '' }}">
                            @if($tabName == '#hull')
                                @include('shipManage.tab_hull', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo, 'freeBoard'=>$freeBoard]))
                            @endif
                        </div>
                        <div id="machiery" class="tab-pane {{ $tabName == '#machiery' ? 'active' : '' }}">
                            @if($tabName == '#machiery')
                                @include('shipManage.tab_machinery', with(['shipList'=>$shipList, 'shipType'=>$shipType, 'shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                        <div id="remarks" class="tab-pane {{ $tabName == '#remarks' ? 'active' : '' }}">
                            @if($tabName == '#remarks')
                                @include('shipManage.tab_remarks', with(['shipInfo'=>$shipInfo]))
                            @endif
                        </div>
                    </div>
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
        var preTabName = activeTabName;

        //editables on first profile page
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit"><i class="icon-ok icon-white"></i></button>';

        function ShowTabPage(tabName) {
            if(shipId.length < 1) {
                $('#msg-content').html("  现需要保存一般信息(Ship's Particulars)。  ");
                $('.alert').show();
                if(tabName != '#general')
                    $('#btnRegister').attr('disabled', 'disabled');
                else
                    $('#btnRegister').removeAttr('disabled', 'disabled');

                return;
            }

            $('.ship-register li').css({'pointer-events': 'none'});
            if(preTabName != tabName) {
                $('[name=_tabName]').val(tabName);
                $(preTabName + '-form').submit();
            }

            $.post("shipDataTabPage", {'_token':token, 'shipId':shipId, 'tabName':tabName}, function(data) {
                switch (tabName) {
                    case '#general':
                        $('#general').html(data);
                        $('.chosen-select').chosen();
                        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                            $(this).prev().focus();
                        });
                        break;
                    case '#hull':
                        $('#hull').html(data);
                        break;
                    case '#machiery':
                        $('#machiery').html(data);
                        break;
                    case '#remarks':
                        $('#remarks').html(data);
                        break;
                }
                $('.ship-register li').css({'pointer-events': 'all'});
            });

            preTabName = tabName;
        }


        $('#btnRegister').on('click', function() {
            if(activeTabName == '#general') {
                $(activeTabName + '-form').validate();
            }

            $(activeTabName + '-form').submit();
        });

        $('.ship-item').on('click', function() {
            if($(this).hasClass('selected'))
                return;

            let ship_id = $(this).attr('data-index');
            location.href = BASE_URL + 'shipManage/registerShipData?shipId=' + ship_id;
        });

        function deleteItem(shipId, shipName) {
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
        }

        $(function() {
            ShowTabPage(activeTabName);
        })

    </script>
@stop