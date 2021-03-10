@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>航次盘算</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            草案(目录)
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">{{transShipOperation("simple.ShipName")}}</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="shipName">
                                <option value="0" @if(is_null($shipId)) selected @endif>全部</option>
                                @foreach($shipList as $ship)
                                    <option value="{{$ship['RegNo']}}"
                                            @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn']}} @if(!empty($ship['name'])) | {{$ship['name']}} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary btn-sm btn_search" style="float: left; width :80px"><i class="icon-search"></i>搜索</button>
                    </div>
                    @if(!$isHolder)
                        <div class="col-md-4" style="text-align: right">
                            <button class="btn btn-purple btn-sm btn_add" style="float: right; width :100px"><i class="icon-circle-arrow-right"></i>重新计算</button>
                        </div>
                    @endif
                </div>
                <div class="space-4"></div>
                <div class="row">
                    <table id="" class="table table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center">{{transShipOperation("simple.calcDate")}}</th>
                            <th class="center">{{transShipOperation("simple.ShipName")}}</th>
                            <th class="center">{{transShipOperation("simple.Lp")}}</th>
                            <th class="center">{{transShipOperation("simple.Dp")}}</th>
                            <th class="center">{{transShipOperation("simple.Count")}}</th>
                            <th class="center">{{transShipOperation("simple.Freight")}}</th>
                            <th class="center">{{transShipOperation("simple.Income")}}</th>
                            <th class="center">{{transShipOperation("simple.Expense")}}</th>
                            <th class="center">{{transShipOperation("simple.Profit")}}</th>
                            <th class="center">{{transShipOperation("simple.Daily Profit")}}</th>
                            @if(!$isHolder)
                                <th class="center"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($voyProRandom as $list)
                            <tr>
                                <td class="center" data-id="{{ $list->id }}">{{ $list->caldate }}</td>
                                <td class="center">{{$list->ShipName->shipName_En}} | {{$list->ShipName->shipName_Cn}}</td>
                                <td class="center">@if(!empty($list->lPortName->Port_Cn)){{ $list->lPortName->Port_Cn }}@endif</td>
                                <td class="center">@if(!empty($list->dPortName->Port_Cn)){{ $list->dPortName->Port_Cn }}@endif</td>
                                <td style="text-align: center">{{ \App\Http\Controllers\Util::getNumberFtNZ($list->qtty) }}</td>
                                <td class="center">{{ $list->frt }}</td>
								<?php
								$total_frt = round($list->frt * $list->qtty, 2);
								$comm = round($list->broker * $total_frt, 2);
								$income = $total_frt + $list->addincome + $list->demurrage - $comm;
								?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFtNZ($income) }}</td>
								<?php
								$saildistance = $list->way_type == 1 ? $list->distance : $list->distance*2;
								$Sail_time = ($list->voyspeed == 0 or $list->voyspeed == NULL) ? 0 : Round($saildistance/($list->voyspeed*24),2);
								$interval = $Sail_time + $list->ld_time + $list->idle_time;
								$fo_qtty = Round($list->shipReg->FO_Sail_Cons_S *$Sail_time,2);
								$do_qtty = Round($list->shipReg->DO_Sail_Cons_S*($Sail_time+0.6)+$list->shipReg->DO_L_D_Cons_S*$list->ld_time+$list->shipReg->DO_Idle_Cons_S*$list->ld_time,2);
								$lo_qtty = Round($list->shipReg->LO_Sail_Cons_S*$Sail_time+$list->shipReg->LO_L_D_Cons_S*$list->ld_time+$list->shipReg->LO_Idle_Cons_S*$list->idle_time,2);
								$fo = Round($fo_qtty*$list->fo_price,2);
								$do = Round($do_qtty*$list->do_price,2);
								$lo = Round($lo_qtty*$list->lo_price,2);
								$pd = Round($list->pd_l+$list->pd_d+$list->lkt,2);
								$ss = !empty($data) ? $data->ss : 0;
								$ctm = !empty($data) ? $data->ctm : 0;
								$insurance = !empty($data) ? $data->insurance : 0;
								$ism = !empty($data) ? $data->ism : 0;
								$other = !empty($data) ? $data->other : 0;
								$expense = Round($fo + $do + $lo + $pd + $ss + $ctm + $insurance + $ism + $other, 2);
								?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFtNZ($expense) }}</td>
								<?php
								$profit = Round($income - $expense, 2);
								?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFtNZ($profit) }}</td>
								<?php
								$profit_day = $interval == 0 ? 0 : Round($profit/$interval,2);
								?>
                                <td style="text-align: right">{{ \App\Http\Controllers\Util::getNumberFtNZ($profit_day) }}</td>
                                @if(!$isHolder)
                                    <td class="center action-buttons">
                                        <a class="blue row_detail_btn"><i class="icon-search bigger-130"></i></a>
                                        <a class="red row_trash_btn"><i class="icon-trash bigger-130"></i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $voyProRandom->render() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('.btn_search').on('click', function () {
                var shipId = $('#shipName').val();
                location.href = 'shipCountSimpleList?shipId=' + shipId;
            });

            $('.row_detail_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var calcId = obj.eq(0).data('id');
                location.href = 'shipCountSimple?id=' + calcId;
            });

            $('.btn_add').on('click', function () {
                location.href = 'shipCountSimple';
            });
        });

    </script>
@stop