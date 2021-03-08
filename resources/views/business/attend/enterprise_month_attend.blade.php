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
                width:4%;
            }

        </style>
        <div class="page-content">
            <div class="page-header">
                <h4>
                    <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        {{transBusinessManage("title.EnterpriseMonthAttend_Small")}}
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
                    <div class="input-group col-md-3">
                        <div class="form-group col-md-7">
                            <select class="col-md-8" id="selyear">
                                @for($i=2015;$i<=2020;$i++)
                                    <option value="{{$i}}" @if($i == $year)) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <label class="col-md-2 control-label">{{transBusinessManage("captions.year")}}</label>
                        </div>
                        <div class="form-group col-md-5">
                            <select class="col-md-9" id="selmonth">
                                @for ($i=1;$i<13;$i++)
                                    <option value="{{$i}}" @if($i == $month)selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <label class="col-md-1 control-label">{{transBusinessManage("captions.month")}}</label>
                        </div>
                    </div>
                    <div class="col-sm-2 form-group" style="padding: 0">
                        <label style="float:left;padding:7px 5px">{{transBusinessManage("captions.department")}}:</label>
                        <div class="col-md-9" style="padding:0">
                            <select class="form-control" id="unit_select" @if(!empty($shipId)) disabled @endif>
                                <option value="">{{transBusinessManage("captions.all")}}</option>
                                @foreach($units as $unit)
                                    <option value="{{$unit['id']}}" @if($unit['id'] == $unitId) selected @endif>{{ $unit['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 form-group" style="padding: 0">
                        <label style="float:left;padding:7px 5px">{{transBusinessManage("captions.shipname")}}:</label>
                        <div class="col-md-8" style="padding:0">
                            <select class="form-control" id="ship_select" @if(!empty($unitId)) disabled @endif>
                                <option value="">{{transBusinessManage("captions.all")}}</option>
                                @foreach($ships as $ship)
                                    <option value="{{$ship['RegNo']}}" @if($ship['RegNo'] == $shipId) selected @endif>{{ $ship['name'] }}</option>
                                @endforeach
                                <option value="READY">{{transBusinessManage("captions.waitmember")}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 form-horizontal">
                        <label class="col-sm-3 control-label no-padding-right">{{transBusinessManage("captions.name")}}</label>
                        <div class="col-sm-8"><input class="form-control" id="member_name" value="{{$memberName}}"></div>
                    </div>
                    <button type="button" class="btn btn-info btn-sm search-btn" style="float:left; width: 80px"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                    <button class="btn btn-warning btn-sm excel-btn" style="float:left; margin-left: 20px; width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                </div>
                <div class="row">
                    <div style="overflow-y: scroll;width: 100%">
@else
    @include('layout.excel-style')
@endif

                        <table class="table table-striped table-bordered table-hover arc-std-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th rowspan="2" class="center" style="width:3%">{{transBusinessManage("captions.no")}}</th>
                                <th rowspan="2" class="center" style="width:7%">{{transBusinessManage("captions.departmentName")}}</th>
                                <th rowspan="2" class="center" style="width:6%">{{transBusinessManage("captions.duty")}}</th>
                                <th rowspan="2" class="center" style="width:4%">{{transBusinessManage("captions.shipname")}}</th>
                                <th rowspan="2" class="center" style="width:7%">{{transBusinessManage("captions.embarkduty")}}</th>
                                <th rowspan="2" class="center" style="width:7%">{{transBusinessManage("captions.name")}}</th>
                                <th rowspan="2" class="center number_td" style="width:3%">{{transBusinessManage("captions.year_days")}}</th>
                                <th rowspan="2" class="center number_td" style="width:3%">{{transBusinessManage("captions.rest")}}</br>{{transBusinessManage("captions.days")}}</th>
                                <th rowspan="2" class="center number_td" style="width:3%">{{transBusinessManage("captions.legal")}}</br>{{transBusinessManage("captions.operate")}}</br>{{transBusinessManage("captions.days")}}</th>
                                <th rowspan="2" class="center number_td" style="width:3%">{{transBusinessManage("captions.enter")}}</br>{{transBusinessManage("captions.days")}}</th>
                                <th rowspan="2" class="center number_td" style="width:3%">{{transBusinessManage("captions.absencecount")}}</th>
                                <th colspan="11" class="center" style="width: 33%;">{{transBusinessManage("captions.among")}}</th>
                                <th rowspan="2" class="center number_td" style="width: 3%;">{{transBusinessManage("captions.late")}}</th>
                                <th rowspan="2" class="center number_td" nowrap="nowrap" style="width: 3%;">{{transBusinessManage("captions.earlyleave")}}</th>
                            </tr>
                            <tr class="black br-hblue">
                                @foreach($typeList as $type)
                                    @if($type['id'] > 3)
                                        <th class="center number_td" style="width: 3%;">{{$type['name']}}</th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
@if(!isset($excel))
                        </table>
                    </div>
                    <div style="overflow-x:hidden; overflow-y: scroll; width:100%; height:65vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
@endif
                            <tbody>
                            <?php $index = 1; ?>
                            @foreach($list as $member)
                                <tr>
                                    <td class="celldata center" style="width:3%">{{$index++}}</td>
                                    <td class="celldata center" style="width:7%">
										@if($member['isShip'] == 0)
											{{$member['unitName']}}
										@endif
									</td>
                                    <td class="celldata center" style="width:6%">@if($member['isShip'] == 0){{$member['pos']}}@endif</td>
                                    <td class="celldata center" style="width:4%">@if($member['isShip'] == 1){{$member['unitName']}}@endif</td>
                                    <td class="celldata center" style="width:7%">
										@if($member['isShip'] == 1)
											{{$member['pos']}}
										@endif</td>
                                    <td class="celldata center" style="width:7%">
                                        @if($member['isShip'] == 0)
                                            <a id="monthDetailMember" style="cursor: pointer;" data-userid="{{$member['id']}}" data-year="{{$year}}" data-month="{{$month}}">{{$member['realName']}}</a>
                                        @else
                                            <a id="monthDetailShip" style="cursor: pointer;" data-userid="{{$member['id']}}" data-year="{{$year}}" data-month="{{$month}}">{{$member['realName']}}</a>
                                        @endif
                                    </td>
                                    <td class="celldata center number_td" data-days="{{$dates['days']}}" style="width:3%">{{$dates['days']}}</td>
                                    <td class="celldata center number_td" data-rest="{{$dates['rest']}}" style="width:3%">{{$dates['rest']}}</td>
                                    <td class="celldata center number_td" data-work="{{$dates['work']}}" style="width:3%">{{$dates['work']}}</td>
                                    <td class="celldata center number_td" data-attend="{{$member['attend']}}" style="width:3%">{{$member['attend']}}</td>
                                    <td class="celldata center number_td" data-absence="{{$member['absence']}}" style="width:3%">{{$member['absence']}}</td>
                                    @foreach($typeList as $type)
                                        @if($type['id'] > 3)
                                            <td class="celldata center number_td" data-type_{{$type['id']}}="{{$member['type_'.$type['id']]}}" style="width:3%">{{$member['type_'.$type['id']]}}</td>
                                        @endif
                                    @endforeach
                                    <td class="celldata center number_td" data-type_2="{{$member['type_2']}}" style="width:3%">{{$member['type_2']}}</td>
                                    <td class="celldata center number_td" data-type_3="{{$member['type_3']}}" style="width:3%">{{$member['type_3']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
@if(!isset($excel))
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function clearZeroData(){
            var celldata = $(".celldata");
            for (var i = 0; i < celldata.length; i++) {
                if (celldata[i].textContent == '0') {
                    celldata[i].textContent = '';
                }
            }
        }

        $(function () {

            $('#unit_select').on('change', function() {
                var unitId = $(this).val();
                if(unitId.length < 1)
                    $('#ship_select').removeAttr('disabled');
                else
                    $('#ship_select').attr('disabled', 'disabled');
            });
            $('#ship_select').on('change', function() {
                var shipId = $(this).val();
                if(shipId.length < 1)
                    $('#unit_select').removeAttr('disabled');
                else
                    $('#unit_select').attr('disabled', 'disabled');
            });

            $('.search-btn').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                var url = 'enterpriseMonthAttend?selYear='+selyear + '&selMonth=' + selmonth;

                var selUnit = $("#unit_select").val();
                if(selUnit.length > 0)
                    url += '&unit=' + selUnit;
                else {
                    var selShip = $("#ship_select").val();
                    if(selShip.length > 0)
                        url += '&ship=' + selShip;
                }

                var memberName = $('#member_name').val();
                if(memberName.length > 0)
                    url += '&name=' + memberName;

                location.href = url;
            });

            $('.excel-btn').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                var url = 'enterpriseMonthAttendExcel?selYear='+selyear + '&selMonth=' + selmonth;

                var selUnit = $("#unit_select").val();
                if(selUnit.length > 0)
                    url += '&unit=' + selUnit;

                var selShip = $("#ship_select").val();
                if(selShip.length > 0)
                    url += '&ship=' + selShip;

                var memberName = $('#member_name').val();
                if(memberName.length > 0)
                    url += '&name=' + memberName;

                location.href = url;
            });

            clearZeroData();
        });

        $("a#monthDetailMember").on("click", function(){
            $token = '{!! csrf_token() !!}';
            $thisObj = $(this);
            $userId = $thisObj.data("userid");
            $year = $thisObj.data("year");
            $month = $thisObj.data("month");
            $parentObj = $thisObj.parent("td").parent("tr").children("td");
            $days = $parentObj.eq(6).data("days");
            $rest = $parentObj.eq(7).data("rest");
            $work = $parentObj.eq(8).data("work");
            $attend = $parentObj.eq(9).data("attend");
            $absence = $parentObj.eq(10).data("absence");
            $typeUrl = "";
            for($i=4; $i<=14; $i++){
                $type_$i = $parentObj.eq(7+$i).data("type_"+$i);
                $typeUrl += "&type_"+$i+"="+$type_$i;
            }
            $type_2 = $parentObj.eq(22).data("type_2");
            $type_3 = $parentObj.eq(23).data("type_3");
            $param = "_token="+$token+"&userId="+$userId+"&year="+$year+"&month="+$month+
                    "&days="+$days+"&rest="+$rest+"&work="+$work+
                    "&attend="+$attend+"&absence="+$absence+"&type_2="+$type_2+"&type_3="+$type_3+$typeUrl;

            $url = "memberMonthAttend";
            submitData($url, $param, "POST", "");
        });

        $("a#monthDetailShip").on("click", function(){
            $token = '{!! csrf_token() !!}';
            $thisObj = $(this);
            $userId = $thisObj.data("userid");
            $year = $thisObj.data("year");
            $month = $thisObj.data("month");
            $parentObj = $thisObj.parent("td").parent("tr").children("td");
            $days = $parentObj.eq(6).data("days");
            $rest = $parentObj.eq(7).data("rest");
            $work = $parentObj.eq(8).data("work");
            $attend = $parentObj.eq(9).data("attend");
            $absence = $parentObj.eq(10).data("absence");
            $typeUrl = "";
            for($i=4; $i<=14; $i++){
                $type_$i = $parentObj.eq(7+$i).data("type_"+$i);
                $typeUrl += "&type_"+$i+"="+$type_$i;
            }
            $type_2 = $parentObj.eq(22).data("type_2");
            $type_3 = $parentObj.eq(23).data("type_3");
            $param = "_token="+$token+"&userId="+$userId+"&year="+$year+"&month="+$month+
                    "&days="+$days+"&rest="+$rest+"&work="+$work+
                    "&attend="+$attend+"&absence="+$absence+"&type_2="+$type_2+"&type_3="+$type_3+$typeUrl;

            $url = "shipMemberMonthAttend";
            submitData($url, $param, "POST", "");
        });

    </script>
@endif

@endsection