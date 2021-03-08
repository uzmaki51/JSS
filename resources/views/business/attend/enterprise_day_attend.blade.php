<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <style>
            .number_td {
                width:5%;
            }

        </style>
        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        {{transBusinessManage("title.EnterpriseDayAttend_Small")}}
                    </small>
                </h4>
            </div>
            <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" data-target="#modal-step-contents">
                        {{transBusinessManage("captions.warning")}}
                        </div>
                        <div id="modal-body-content" class="modal-body step-content">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="input-group col-md-1">
                        <input class="form-control date-picker" id="search-date" name="search-date" type="text"
                               data-date-format="yyyy/mm/dd" value="{{$selDate}}">
                        <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                    </div>
                    <button type="button" class="btn btn-info btn-sm" style="float:left; width: 80px;" onclick="showAttendByDate()"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                    <button type="button" class="btn btn-warning btn-sm" style="float:left; margin-left:10px; width: 80px" onclick="excelAttendByDate()"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                </div>
                <div class="space-6"></div>
                <div class="row">
                    <div class=" table-responsive" id="attend_list_table">
@else
    @include('layout.excel-style')
@endif
                        <table id="attend_info_table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="black br-hblue">
                                    <th rowspan="2" class="center number_td">{{transBusinessManage("captions.no")}}</th>
                                    <th rowspan="2" class="center">{{transBusinessManage("captions.departmentName")}}</th>
                                    <th rowspan="2" class="center number_td">{{transBusinessManage("captions.department")}}<br>{{transBusinessManage("captions.membercount")}}</th>
                                    <th rowspan="2" class="center number_td">{{transBusinessManage("captions.enter")}}<br>{{transBusinessManage("captions.membercount")}}</th>
                                    <th colspan="12" class="center number_td">{{transBusinessManage("captions.absencecount")}}</th>
                                    <th rowspan="2" class="center number_td">{{transBusinessManage("captions.late")}}</th>
                                    <th rowspan="2" class="center number_td">{{transBusinessManage("captions.earlyleave")}}</th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th class="center number_td">{{transBusinessManage("captions.sum")}}</th>
                                    @foreach($typeList as $type)
                                        @if($type['id'] > 3)
                                            <th class="center number_td">{{$type['name']}}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            <?php $index = 1;?>
                            @foreach($units as $unit)
                                <tr>
                                    <td class="center">{{$index++}}</td>
                                    <td class="center">
                                        @if($unit['unitType'] == 1)
                                            <a href='unitAttendDayPage?unit={{$unit['id']}}&selDate={{$selDate}}'>{{$unit['title']}}</a>
                                        @else
                                            <a href='shipAttendDayPage?ship={{$unit['id']}}&selDate={{$selDate}}'>{{$unit['title']}}</a>
                                        @endif
                                    </td>
                                    <td class="celldata center">{{$unit['userCount']}}</td>
                                    <td class="celldata center">{{$unit['attend']}}</td>
                                    <td class="celldata center">{{$unit['absence']}}</td>
                                    <?php $count = count($unit['valueList']) + 1; ?>
                                    @for($i=4;$i<$count;$i++)
                                        <td class="celldata center">{{$unit['valueList'][$i]}}</td>
                                    @endfor
                                    <td class="celldata center">{{$unit['valueList'][2]}}</td>
                                    <td class="celldata center">{{$unit['valueList'][3]}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="center" style="font-weight: bold">{{transBusinessManage("captions.totalsum")}}</td>
                                @for($i=0;$i<16;$i++)
                                    <td class="center" style="font-weight: bold;background: #D3F9E4" id="total{{$i}}"></td>
                                @endfor
                            </tr>

                            </tbody>
                        </table>
@if(!isset($excel))
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function clearZeroData() {
            var celldata = $(".celldata");
            for (var i = 0; i < celldata.length; i++) {
                if (celldata[i].textContent == '0') {
                    celldata[i].textContent = '';
                }
            }
        }
        function getTotal() {
            var celldata = $(".celldata");
            var restdays = Array();
            for (var i = 0; i < celldata.length; i++) {
                if (i < 16) restdays[i % 16] = 0;
                restdays[i % 16] += parseInt(celldata[i].textContent);
                $('#total' + (i % 16)).html(restdays[i % 16]==0?'':restdays[i % 16]);
            }
        }

        function showAttendByDate() {
            var selDate = $("#search-date").val();
            if(selDate.length < 10)
                return;
            location.href = 'enterpriseDayAttend?selDate=' + selDate;
        }

        function excelAttendByDate() {
            var selDate = $("#search-date").val();
            if(selDate.length < 10)
                return;
            location.href = 'enterpriseDayAttendExcel?selDate=' + selDate;
        }

        $(function () {
            getTotal();
            clearZeroData();

        });

    </script>
@endif
@endsection