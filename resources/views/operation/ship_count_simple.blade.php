@extends('layout.sidebar')

@section('content')
<div class="main-content">
    <style>
        .port_select{width:70%}
        .chosen-drop{width: 250px !important;}
    </style>
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-6">
                <h4><b>항차타산</b>
                    <small>
                        <i class="icon-double-angle-right"></i>초본
                    </small>
                </h4>
            </div>
            <div class="col-md-6">
                <div style="float:right">
                    <a href="javascript:history.back()">이전페지</a>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="padding-left:5px;padding-right:5px">
            <?php
                $saildistance = 0;
                if(isset($data)) {
                    $saildistance = $data['way_type'] == 1 ? $data['distance'] : $data['distance']*2;
                }
                $interval = 0; $sail_time = 0;
                if(isset($data)){
                    $sail_time = ($data->voyspeed == 0 or $data->voyspeed == NULL) ? 0 : Round($saildistance/($data->voyspeed*24),2);
                    $interval = $sail_time + $data->ld_time + $data->idle_time;
                }
                $total_frt = isset($data) ? round($data->frt * $data->qtty, 2) : 0;
                $comm = isset($data) ? round($data->broker * $total_frt, 2) : 0;
                $income = isset($data) ? $total_frt + $data->addincome + $data->demurrage - $comm : 0 ;
                $fo_qtty = isset($data) ? Round($data->shipReg->FOSailCons_S * $sail_time, 2) : 0;
                $do_qtty = isset($data) ? Round($data->shipReg->DOSailCons_S * ($sail_time + 0.6) + $data['shipReg']['DOL/DCons_S'] * $data->ld_time + $data->shipReg->DOIdleCons_S * $data->ld_time,2) : 0;
                $lo_qtty = isset($data) ? Round($data->shipReg->LOSailCons_S * $sail_time + $data['shipReg']['LOL/DCons_S'] * $data->ld_time + $data->shipReg->LOIdleCons_S * $data->idle_time,2) : 0;
                $fo = isset($data) ? Round($fo_qtty * $data->fo_price,2) : 0;
                $do = isset($data) ? Round($do_qtty * $data->do_price,2) : 0;
                $lo = isset($data) ? Round($lo_qtty * $data->lo_price,2) : 0;
                $pd = isset($data) ? Round($data->pd_l + $data->pd_d + $data->lkt, 2) : 0;
                $ss = isset($data) ? $data->ss : 0;
                $ctm = isset($data) ? $data->ctm : 0;
                $insurance = isset($data) ? $data->insurance : 0;
                $ism = isset($data) ? $data->ism : 0;
                $other = isset($data) ? $data->other : 0;
                $expense = isset($data) ? Round($fo + $do + $lo + $pd + $ss + $ctm + $insurance + $ism + $other, 2) : 0;
                $profit = Round($income - $expense, 2);
                $profit_day = $interval == 0 ? 0 : Round($profit/$interval,2);
            ?>
            <form action="updateStandardCp" method="post">
                <input class="hidden" name="_token" value="{{csrf_token()}}">
                <input class="hidden" name="baseId" value="@if(!empty($data)) {{$data['id']}} @else 0 @endif">
                <div class="row">
                    <div class="col-md-9 widget-box">
                        <div class="widget-header" style="min-height: 35px"><h5>{{transShipOperation("simple.ContractData")}}</h5></div>
                        <div class="widget-body">
                            <table class="table" width="100%">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.ShipName")}}:</td>
                                    <td>
                                        <div style="width: 100px;">
                                            <select name="ship_name" class="ship_select chosen-select">
                                                @foreach($shipList as $ship)
                                                    <option value="{{$ship['RegNo']}}" data-speed="{{$ship['Speed']}}" @if(isset($data) && ($ship['RegNo'] == $data['shipid'])) selected @endif>{{$ship['shipName_Cn']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="right" nowrap="nowrap">
                                        {{transShipOperation("simple.Contract")}}:
                                    </td>
                                    <td nowrap="nowrap">
                                        <div style="width: 140px;">
                                            <select name="ship_contract" class="chosen-select">
                                                @foreach($contractList as $contract)
                                                    <option value="{{$contract['id']}}" @if(isset($data) && ($contract['id'] == $data['typeofcp'])) selected @endif>{{$contract['Contract_Cn']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right !important;" nowrap="nowrap">{{transShipOperation("simple.Lp")}}:</td>
                                    <td>
                                        <div style="width: 100px;">
                                            <select name="lport" class="port_select chosen-select">
                                                @foreach($portList as $port)
                                                    <option value="{{$port['id']}}" @if(isset($data) && ($data['lport'] == $port['id'])) selected @endif>{{$port['Port_En']}} | {{$port['Port_Cn']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Dp")}}:</td>
                                    <td>
                                        <div style="width: 100px;">
                                            <select name="dport" class="port_select chosen-select">
                                                @foreach($portList as $port)
                                                    <option value="{{$port['id']}}" @if(isset($data) && ($data['dport'] == $port['id'])) selected @endif>{{$port['Port_En']}} | {{$port['Port_Cn']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Voy_Mthd")}}:</td>
                                    <td>
                                        <div style="width: 100px;">
                                            <select class="way_select chosen-select">
                                                <option value="1" @if(isset($data) && ($data['way_type'] == 1)) selected @endif>편도</option>
                                                <option value="2" @if(isset($data) && ($data['way_type'] == 2)) selected @endif>왕복</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Distance")}}:</td>
                                    <td><input type="number" class="center" style="width:80px" name="distance" value="@if(isset($data)){{$data['distance']}}@endif"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td  style="text-align: right !important;" nowrap="nowrap">{{transShipOperation("simple.Count")}}:</td>
                                    <td style="width: 100px;"><input type="number" class="center" style="width:100%" name="qtty" value="@if(isset($data)){{$data['qtty']}}@endif"></td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Freight")}}:</td>
                                    <td style="width: 100px;"><input type="number" style="width:100px;text-align: center;" name="frt" value="@if(isset($data)){{$data['frt']}}@endif"></td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Demurrage")}}:</td>
                                    <td style="width: 100px;"><input type="number" style="width:100%;text-align: right;" name="demurrage" value="@if(isset($data)){{$data['demurrage']}}@endif"></td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Fee")}}:</td>
                                    <td style="width: 100px;"><input type="number" style="width:100%;text-align: right;" name="broker" value="@if(isset($data)){{$data['broker']}}@endif"></td>
                                    <td class="right" nowrap="nowrap">{{transShipOperation("simple.Add Income")}}:</td>
                                    <td><input type="number" style="width:100%;text-align: right;" name="addincome" value="@if(isset($data)){{$data['addincome']}}@endif"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3 widget-box" style="width:23%;margin-left:15px">
                        <div class="widget-header" style="min-height:35px;"><h5>{{transShipOperation("simple.Duration Day")}}</h5></div>
                        <div class="widget-body">
                            <table class="no-padding table-bordered" style="width:100%;font-size: 12px;padding-bottom: 0">
                                <tr>
                                    <td style="width:25%;height: 27pt">{{transShipOperation("simple.Sail Distance")}}</td>
                                    <td class="center" style="width:25%" name="sail_distance">{{$saildistance}}</td>
                                    <td style="width:25%;text-align: center">{{transShipOperation("simple.Duration Day")}}</td>
                                    <td class="center" name="back_day">{{$interval}}</td>
                                </tr>
                                <tr>
                                    <td>{{transShipOperation("simple.Ship Speed")}}</td>
                                    <?php $shipSpeed = 0;
                                    if(!empty($data))
                                        $shipSpeed = $data['voyspeed'];
                                    else if(count($shipList))
                                        $shipSpeed = $shipList[0]['Speed'];  ?>
                                    <td><input type="text" class="form-control center" name="voyspeed" value="{{ $shipSpeed }}"></td>
                                    <td class="center">{{transShipOperation("simple.Sail Day")}}</td>
                                    <td class="center" name="nav_day">{{$sail_time}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="center">{{transShipOperation("simple.L/P Day")}}</td>
                                    <?php  $ld_time = !empty($data) ? $data['ld_time'] : 2;
                                    $idle_time =  !empty($data) ? $data['idle_time'] : 6; ?>
                                    <td><input type="number" class="form-control center" name="ld_time" value="{{ $ld_time }}"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="center">{{transShipOperation("simple.Consuming Day")}}</td>
                                    <td><input type="number" class="form-control center" name="idle_time" value="{{ $idle_time }}"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="space-4"></div>
                <div class="row">
                    <div class="col-md-3 widget-box" style="width:19%;margin-right:10px;">
                        <div class="widget-header" style="min-height:35px;"><h5>{{transShipOperation("simple.Predict Income")}}</h5></div>
                        <div class="widget-body">
                            <table class="no-padding table-bordered" style="width:100%;padding-bottom: 0">
                                <tr>
                                    <td style="width:50%;height: 23pt"><b>{{transShipOperation("simple.Total Income")}}</b></td>
                                    <td class="right" style="font-weight:bold;font-size:14px;" name="income">{{$total_frt+$data['demurrage']+$data['addincome']+$comm}}</td>
                                </tr>
                                <tr>
                                    <td style="height: 23pt">{{transShipOperation("simple.Freight Income")}}</td>
                                    <td class="right" name="nav_income">{{$total_frt}}</td>
                                </tr>
                                <tr>
                                    <td style="height: 23pt">{{transShipOperation("simple.Demurrage")}}</td>
                                    <td class="right" name="demurrage_label">@if(isset($data)){{$data['demurrage']}}@endif</td>
                                </tr>
                                <tr>
                                    <td style="height: 23pt">{{transShipOperation("simple.Add Income")}}</td>
                                    <td class="right" name="addincome_label">@if(isset($data)){{$data['addincome']}}@endif</td>
                                </tr>
                                <tr>
                                    <td style="height: 23pt">{{transShipOperation("simple.Fee")}}</td>
                                    <td class="right" name="comm">{{$comm}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 widget-box" style="width:55%;">
                        <div class="widget-header" style="min-height:35px;"><h5>{{transShipOperation("simple.Expense Income")}}</h5></div>
                        <div class="widget-body">
                            <div class="row" style="margin: 0">
                                <div class="space-4"></div>
                                <table class="table-bordered col-md-4" style="margin-left:10px">
                                    <tr>
                                        <td style="width:50%;"><b>{{transShipOperation("simple.Expense")}}</b></td>
                                        <td class="right" style="font-weight:bold;font-size:14px" name="expense">{{$expense}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="space-4"></div>
                            <div class="row" style="margin: 0">
                                <table class="table-bordered col-md-3" style="margin-left: 10px">
                                    <tr>
                                        <td class="center" style="width:50%;font-weight: bold;">{{transShipOperation("simple.Port Money")}}</td>
                                        <td class="right" style="font-weight: bold;" name="pd_ld">@if(isset($data)){{round($data['pd_l'] + $data['pd_d'] + $data['lkt'], 2)}}@endif</td>
                                    </tr>
                                    <?php   $pd_l = !empty($data) ? $data['pd_l'] : 1000;
                                            $pd_d = !empty($data) ? $data['pd_l'] : 6000;
                                            $lkt = !empty($data) ? $data['pd_l'] : 1000;   ?>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Lp")}}</td>
                                        <td><input class="form-control" style="text-align: right" name="pd_l" value="{{ $pd_l }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Dp")}}</td>
                                        <td><input class="form-control" style="text-align: right" name="pd_d" value="{{ $pd_d }}"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Gate")}}</td>
                                        <td><input class="form-control" style="text-align: right" name="lkt" value="{{ $lkt }}"></td>
                                    </tr>
                                </table>
                                <table class="table-bordered col-md-6" style="margin-left: 10px">
                                    <tr>
                                        <td class="center" style="width:25%;">{{transShipOperation("simple.Fuel Money")}}</td>
                                        <td class="right" style="width:25%;" name="fuel_price">{{Round($fo+$do+$lo,2)}}</td>
                                        <td class="center" style="width: 25%">{{transShipOperation("simple.FO")}}</td>
                                        <td class="right" name="sail_fo_price">{{$fo}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="center">{{transShipOperation("simple.DO")}}</td>
                                        <td class="right" name="sail_do_price">{{$do}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="center">{{transShipOperation("simple.LO")}}</td>
                                        <td class="right" name="sail_lo_price">{{$lo}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="space-4"></div>
                            <div class="row" style="margin: 0">
                                <table class="table-bordered col-md-3" style="margin-left: 10px">
                                    <tr>
                                        <td class="center" style="width:60%;">{{transShipOperation("simple.Equipment money")}}</td>
                                        <td class="right"><input class="form-control" style="text-align: right;" name="ss" value="@if(!empty($data)) {{$data['ss']}} @endif"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Person moneny")}}</td>
                                        <td class="right"><input class="form-control" style="text-align: right;" name="ctm" value="@if(!empty($data)) {{$data['ctm']}} @endif"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Insbiz Money")}}</td>
                                        <td class="right"><input class="form-control" style="text-align: right;" name="insurance" value="@if(!empty($data)) {{$data['insurance']}} @endif"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.SafeManage Money")}}</td>
                                        <td class="right"><input class="form-control" style="text-align: right;" name="ism" value="@if(!empty($data)) {{$data['ism']}} @endif"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Other")}}</td>
                                        <td class="right"><input class="form-control" style="text-align: right;" name="other" value="@if(!empty($data)) {{$data['other']}} @endif"></td>
                                    </tr>
                                </table>
                                <table class="table-bordered col-md-8" style="margin-left: 10px;font-size: 13px;width: 70%">
                                    <a class="btn btn-primary btn-sm btn-consum" style="float: right;margin-top:-30px;margin-right:15px">{{transShipOperation("standard.Bunker consum level")}}</a>
                                    <thead>
                                    <tr>
                                        <td rowspan="2"></td>
                                        <td class="center" colspan="3">{{transShipOperation("simple.Daily Consuming")}}[{{transShipOperation("simple.mt,kg/day")}}]</td>
                                        <td class="center" rowspan="2">{{transShipOperation("simple.Counsuming Amount")}}<br>[{{transShipOperation("simple.mt,kg")}}]</td>
                                        <td class="center" rowspan="2">{{transShipOperation("simple.Unit")}}<br>[{{transShipOperation("simple.$/mt,kg")}}]</td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.Sail")}}</td>
                                        <td class="center">{{transShipOperation("simple.L/D")}}</td>
                                        <td class="center">{{transShipOperation("simple.StandBy")}}</td>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="center" style="width:14%">{{transShipOperation("simple.FO")}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="fo_sail_consum" value="@if(isset($data)){{$data['shipReg']['FOSailCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="fo_ld_consum" value="@if(isset($data)){{$data['shipReg']['FOL/DCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="fo_idle_consum" value="@if(isset($data)){{$data['shipReg']['FOIdleCons_S']}}@endif">
                                        </td>
                                        <td class="center" name="fo_qtty">{{$fo_qtty}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="fo_price" value="@if(isset($data)){{$data['fo_price']}}@endif">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.DO")}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="do_sail_consum" value="@if(isset($data)){{$data['shipReg']['DOSailCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="do_ld_consum" value="@if(isset($data)){{$data['shipReg']['DOL/DCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="do_idle_consum" value="@if(isset($data)){{$data['shipReg']['DOIdleCons_S']}}@endif">
                                        </td>
                                        <td class="center" name="do_qtty">{{$do_qtty}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="do_price" value="@if(isset($data)){{$data['do_price']}}@endif">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("simple.LO")}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="lo_sail_consum" value="@if(isset($data)){{$data['shipReg']['LOSailCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="lo_ld_consum" value="@if(isset($data)){{$data['shipReg']['LOL/DCons_S']}}@endif">
                                        </td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="lo_idle_consum" value="@if(isset($data)){{$data['shipReg']['LOIdleCons_S']}}@endif">
                                        </td>
                                        <td class="center" name="lo_qtty">{{$lo_qtty}}</td>
                                        <td>
                                            <input type="number" style="text-align: center" class="form-control" name="lo_price" value="@if(isset($data)){{$data['lo_price']}}@endif">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="space-6"></div>
                        </div>
                    </div>
                    <div class="col-md-3 widget-box" style="margin-left:15px; width: 23%">
                        <div class="widget-header" style="min-height:35px;"><h5>{{transShipOperation("simple.Profit Income")}}</h5></div>
                        <div class="widget-body">
                            <table width="100%">
                                <tr>
                                    <td><b>{{transShipOperation("simple.Total Profit")}}</b></td>
                                    <td><label class="form-control" style="margin:7px 12px;width:80%;float:left;font-size:14px;font-weight:bold;text-align: right;" name="profit">{{$profit}}</label></td>
                                </tr>
                                <tr>
                                    <td><b>{{transShipOperation("simple.Daily Profit")}}</b></td>
                                    <td><label class="form-control" style="margin:7px 12px;width:80%;float:left;font-size:14px;font-weight:bold;text-align: right;" name="profit_day">{{$profit_day}}</label></td>
                                </tr>
                            </table>
                            <div class="space-6"></div>
                        </div>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <div class="space-10"></div>
                        <button class="btn btn-primary btn-sm" type="submit" style="float:left" id="submit_btn" disabled> 보 관 </button>
                    </div>
                </div>
            </form>
        </div>
        <a href="#consum-dialog" role="button" class="hidden" data-toggle="modal" id="dialog_show"></a>
        <div id="consum-dialog" class="modal fade" tabindex="-1">
        </div>

    </div>
</div>


    <script>
        var token = '<?php echo csrf_token() ?>';
        var personNum = '@if(isset($data)){{$data->sewage->PersonNumber}}@else 1 @endif' * 10;
        var year_ss = '{{ $yearPlan->SS }}' * 1;
        var year_ctm = '{{ $yearPlan->CTM }}' * 1;
        var year_ism = '{{ $yearPlan->ISM }}' * 1;
        var year_insurance = '{{ $yearPlan->INSURANCE }}' * 1;
        var year_other = '{{ $yearPlan->OTHERS }}' * 1;
        var year_day = '{{ $yearPlan['YEARLY VOY DAY'] }}' * 1;

        jQuery(function(e) {
            $("[name=ship_name]").on('change', function () {
                var shipId = $(this).val();
                var selShip = 'option[value="' + shipId + '"]';
                var speed = $(this).find(selShip).data('speed');
                $('[name=voyspeed]').val(speed);
                $.post("getShipYearPlan", {'_token': token, "shipId": shipId}, function (result) {
                    if (result.status == 'success') {
                        year_ss = result.SS * 1;
                        year_ctm = result.CTM * 1;
                        year_ism = result.ISM * 1;
                        year_insurance = result.INSURANCE * 1;
                        year_day = result.year_day * 1;
                    } else {
                        year_ss = 0;
                        year_ctm = 0;
                        year_ism = 0;
                        year_insurance = 0;
                        year_day = 0;
                    }
                    caculateSailProfit();
                })
            }),
            $(".port_select").on('change', function () {
                var lport = $("[name=lport]").val();
                var dport = $("[name=dport]").val();
                $.post("getSailDistance", {'_token': token, "lport": lport, "dport": dport}, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $("[name=distance]").val(result.distance);
                    } else {
                        $("[name=distance]").val('0');
                    }
                    caculateSailProfit();
                })
            });

            $('.way_select').on('change', function () {
                caculateSailProfit();
            });

            $("input").on('change', function () {
                caculateSailProfit();
            });

            $('.btn-consum').on('click', function () {
                var shipId = $('[name=ship_name]').val();

                if(shipId.length < 1) {
                    $.gritter.add({
                        title: '오유',
                        text: '배이름을 먼저 선택하여야 합니다.',
                        class_name: 'gritter-error'
                    });
                    return;
                }

                $.post('shipFuelCondition', {'_token':token, 'shipId':shipId}, function (data) {
                    $('#consum-dialog').html(data);
                    $('#dialog_show').click();
                    bindConsumTable();
                })
            });



        });

        function caculateSailProfit(){
            $('#submit_btn').removeAttr('disabled');

            var way = $(".way_select").val() * 1;
            var distance = $("[name=distance]").val() * way;

            var speed = $("[name=voyspeed]").val();
            if(speed.length < 1)
                speed = 0;
            var sail_time = distance / (speed * 24);
            var ld_time = $("[name=ld_time]").val() * 1;
            var idle_time = $("[name=idle_time]").val() * 1;
            var interval = sail_time + ld_time + idle_time;
            var total_frt = $("[name=frt]").val() * 1 * $("[name=qtty]").val();
            var comm = ($("[name=broker]").val() / 100) * total_frt;
            var income = total_frt + $("[name=addincome]").val() * 1 + $("[name=demurrage]").val() * 1 - comm;
            var fo_qtty = $("[name=fo_sail_consum]").val() * sail_time;
            var do_qtty = $("[name=do_sail_consum]").val() * (sail_time + 0.6) + $("[name=do_ld_consum]").val() * ld_time + $("[name=do_idle_consum]").val() * ld_time;
            var lo_qtty = $("[name=lo_sail_consum]").val() * sail_time + $("[name=lo_ld_consum]").val() * ld_time + $("[name=lo_idle_consum]").val() * idle_time;
            var fo_price = $("[name=fo_price]").val() * 1;
            var do_price = $("[name=do_price]").val() * 1;
            var lo_price = $("[name=lo_price]").val() * 1;
            var fo = fo_qtty * fo_price;
            var ddo = do_qtty * do_price;
            var lo = lo_qtty * lo_price;
            var pd = $("[name=pd_l]").val() * 1 + $("[name=pd_d]").val() * 1 + $("[name=lkt]").val() * 1;
            var ss = year_ss / year_day * interval;
            var ctm = year_ctm / year_day * interval;
            var insurance = year_insurance / year_day * interval;
            var ism = year_ism / year_day * interval;
            var other = year_other / year_day * interval;
            var expense = fo + ddo + lo + pd + ss + ctm + insurance + ism + other;
            var profit = income - expense;
            var profit_day = interval == 0 ? 0 : profit / interval;

            $("[name=sail_distance]").html(round(distance));
            $("[name=back_day]").html(round(interval));
            $("[name=nav_day]").html(round(sail_time));
            $("[name=income]").html(round(income));
            $("[name=nav_income]").html(round(total_frt));
            $("[name=demurrage_label]").html($("[name=demurrage]").val());
            $("[name=addincome_label]").html($("[name=addincome]").val());
            $("[name=comm]").html(round(comm));
            $("[name=expense]").html(round(expense));
            $("[name=pd_ld]").html(round(pd));
            $("[name=fuel_price]").html(round(fo + ddo + lo));
            $("[name=sail_fo_price]").html(round(fo));
            $("[name=sail_do_price]").html(round(ddo));
            $("[name=sail_lo_price]").html(round(lo));
            $("[name=ss]").val(round(ss));
            $("[name=ctm]").val(round(ctm));
            $("[name=insurance]").val(round(insurance));
            $("[name=ism]").val(round(ism));
            $("[name=other]").val(round(other));
            $("[name=fo_qtty]").html(round(fo_qtty));
            $("[name=do_qtty]").html(round(do_qtty));
            $("[name=lo_qtty]").html(round(lo_qtty));
            $("[name=profit]").html(round(profit));
            $("[name=profit_day]").html(round(profit_day));
        }

        function round(decimal) {
            var str = decimal + '';
            var index = str.indexOf('.');
            if(index < 0)
                return str;
            var length = str.length;
            if(length > (index + 3))
                str = str.substr(0, index + 3);

            return str;
        }

        function bindBtnClickEvent() {
            $('.consum-table td input:checkbox').on('click' , function(){
                var that = this;
                var condi = this.className;

                $(this).closest('table').find('tr > td:first-child input:checkbox')
                        .each(function(){
                            if(this != that)
                                this.checked = false;
                        });

                if(that.checked == true) {
                    for(var i=0;i<3;i++) {
                        var className = condi;
                        if(i == 0){
                            className += '_fo';
                            $(this).closest('table').find('.'+className)
                                    .each(function(){
                                        var tr = $(this).children();
                                        $('[name=fo_sail_consum]').val(tr.eq(3).text());
                                        $('[name=fo_ld_consum]').val(tr.eq(4).text());
                                        $('[name=fo_idle_consum]').val(tr.eq(5).text());
                                    });
                        } else if(i == 1) {
                            className += '_do';
                            $(this).closest('table').find('.'+className)
                                    .each(function(){
                                        var tr = $(this).children();
                                        $('[name=do_sail_consum]').val(tr.eq(1).text());
                                        $('[name=do_ld_consum]').val(tr.eq(2).text());
                                        $('[name=do_idle_consum]').val(tr.eq(3).text());
                                    });
                        } else {
                            className += '_lo';
                            $(this).closest('table').find('.'+className)
                                    .each(function(){
                                        var tr = $(this).children();
                                        $('[name=lo_sail_consum]').val(tr.eq(1).text());
                                        $('[name=lo_ld_consum]').val(tr.eq(2).text());
                                        $('[name=lo_idle_consum]').val(tr.eq(3).text());
                                    });
                        }
                    }
                } else {
                    $('[name=fo_sail_consum]').val('');
                    $('[name=fo_ld_consum]').val('');
                    $('[name=fo_idle_consum]').val('');
                    $('[name=do_sail_consum]').val('');
                    $('[name=do_ld_consum]').val('');
                    $('[name=do_idle_consum]').val('');
                    $('[name=lo_sail_consum]').val('');
                    $('[name=lo_ld_consum]').val('');
                    $('[name=lo_idle_consum]').val('');
                }
            });
        }

    </script>

@stop
