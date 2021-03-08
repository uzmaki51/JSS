<?php
if (isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
    $isHolder = Session::get('IS_HOLDER');
    $ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))
        {{--왼쪽의 부분메뉴에--}}
        <div class="main-content">
            <script src="/assets/js/fuelux/fuelux.spinner.min.js"></script>
            <style>
                tr[data-id="0"] td {
                    height: 24px;
                }

                .spinner-input.form-control {
                    line-height: 0px;
                }

                .total td{
                    background: #BDC1BB !important;
                }

                .total1 td{
                    background: #6C716B !important;
                }
            </style>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4>
                            <b>설비, 부속, 자재</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                공급계획등록
                            </small>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab4">
                            <li class="active">
                                <a data-toggle="tab" href="#planinput">계획입력</a>
                            </li>

                            <li class="">
                                <a data-toggle="tab" href="#plancollection">계획종합</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="planinput" class="tab-pane active">
                                <div class="space-10"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3 form-horizontal">
                                            <label class="control-label padding-left" style="float: left;">년도</label>

                                            <div class="col-sm-8">
                                                <select style="width:70%" id="year">
                                                    <option value="0" @if(!isset($year)) selected @endif>전체</option>
                                                    @for( $i = $year_range->min; $i <= $year_range->max; $i++)
                                                        <option value="{{$i}}"
                                                                @if(isset($year)&&($year==$i)) selected @endif>{{$i}}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-horizontal">
                                            <label class="control-label no-padding-right"
                                                   style="float: left;">배이름</label>

                                            <div class="col-sm-8">
                                                <select style="width:70%" id="shipName">
                                                    <option value="0"></option>
                                                    @foreach($shipList as $ship)
                                                        @if(!$isHolder)
                                                            <option value="{{$ship['RegNo']}}"
                                                                    @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                                            </option>
                                                        @elseif(in_array($ship->shipID, $ships))
                                                            <option value="{{$ship['RegNo']}}"
                                                                    @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-horizontal" style="text-align: right">
                                            <button class="btn btn-xs btn-primary" id="id-btn-search" style="width: 80px">
                                                <i class="icon-search"></i>
                                                검색
                                            </button>
                                            <button class="btn btn-xs btn-warning" id="id-btn-excel" style="width: 80px">
                                                <i class="icon-table"></i>
                                                Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="space-10"></div>
                                        <div class="col-md-12">
                                            @else
                                                @include('layout.excel-style')
                                            @endif
                                            @if(!isset($excel) || (isset($type) && $type == 1))
                                                <table class="table table-bordered table-striped" id="ship_supplyplan_table">
                                                    <thead>
                                                    <tr class="black br-hblue">
                                                        <th class="center" style="width: 5%;">년도</th>
                                                        <th class="center" style="width: 5%;">월</th>
                                                        <th class="center" style="width: 10%;">배이름</th>
                                                        <th class="center" style="width: 10%;">부문</th>
                                                        <th class="center" style="width: 25%;">계획내용</th>
                                                        <th class="center" style="width: 10%;">금액[$]</th>
                                                        <th class="center" style="width: 35%;">상세내용</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($supplyplanlist as $supplyplan)
                                                        <tr data-id="{{$supplyplan->Id}}"
                                                            onclick="supplyPlanListAdd($(this))">
                                                            <td class="center">{{$supplyplan->Yearly}}</td>
                                                            <td class="center">{{$supplyplan->Monthly}}</td>
                                                            <td class="center"
                                                                data-id="{{$supplyplan->ShipName}}">{{$supplyplan->shipName_Cn}}</td>
                                                            <td class="center"
                                                                data-id="{{$supplyplan->Dept}}">{{$supplyplan->Dept_Cn}}</td>
                                                            <td class="center">{{$supplyplan->PlanContent}}</td>
                                                            <td class="right" data-amount="{{$supplyplan->Amount}}">{{\App\Http\Controllers\Util::getNumberFt($supplyplan->Amount)}}</td>
                                                            <td class="center">{{$supplyplan->Remark}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr data-id="0" onclick="supplyPlanListAdd($(this))" id="add">
                                                        <td class="center" colspan="7">새로 추가</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                            @if(!isset($excel))
                                                &nbsp;&nbsp;{!! $supplyplanlist->render() !!}
                                        </div>
                                        <div class="space-10"></div>
                                        @if(!$isHolder)
                                        <div class="col-md-12">
                                            <table id="tbl_app" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr class="black br-hblue">
                                                    <th class="center">년도</th>
                                                    <th class="center">월</th>
                                                    <th class="center">배이름</th>
                                                    <th class="center">부문</th>
                                                    <th class="center">계획내용</th>
                                                    <th class="center" style="width: 80px">금액[$]</th>
                                                    <th class="center">비고</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <form id="supplyPlanListAdd">
                                                    <input type="hidden" id="supplyPlanId" value="">
                                                    <td class="center">
                                                        <select class="form-control" id="yearly">
                                                            @for($year=2015; $year<intval(date('Y'))+4; $year++)
                                                                <option value="{{$year}}">{{$year}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                    <td class="center">
                                                        <select class="form-control" id="monthly">
                                                            @for($month=1; $month < 13; $month++)
                                                                <option value="{{$month}}">{{$month}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                    <td class="center">
                                                        <select class="form-control" id="ShipName">
                                                            <option value=""></option>
                                                            @foreach($shipInfos as $shipInfo)
                                                                <option value="{{$shipInfo['RegNo']}}">{{$shipInfo['shipName_Cn']}} @if(!empty($shipInfo['name']))
                                                                        | {{$shipInfo['name']}}@endif</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="center">
                                                        <select class="form-control" id="dept">
                                                            <option value=""></option>
                                                            @foreach($deptInfos as $deptInfo)
                                                                <option value="{{$deptInfo['id']}}">{{$deptInfo['Dept_Cn']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="center">
                                                        <input type="text" id="PlanContent" class="form-control">
                                                    </td>
                                                    <td class="center">
                                                        <input type="number" id="PlanAmount">
                                                    </td>
                                                    <td class="center">
                                                        <textarea id="PlanRemark" class="form-control"
                                                                  style="height: 24px;"></textarea>
                                                    </td>
                                                </form>
                                                </tbody>
                                            </table>
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3">
                                                <button class="btn btn-xs btn-primary" id="supplyPlanAdd"
                                                        @if($supplyplanlist->currentPage() < $supplyplanlist->lastPage()) disabled @endif>
                                                    <i class="icon-plus-sign bigger-50"></i>
                                                    추가
                                                </button>
                                            </div>
                                        </div>
                                            @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="space-10"></div>
                                    </div>
                                </div>

                            </div>
                            <div id="plancollection" class="tab-pane">
                                <div class="space-10"></div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="col-md-3 form-horizontal">
                                            <label class="control-label no-padding-right padding-left"
                                                   style="float: left;">년도</label>

                                            <div class="col-sm-8">
                                                <select style="width:70%" id="yearCol">
                                                    @for($i = $year_range->min; $i <= $year_range->max; $i++)
                                                        <option value="{{$i}}"
                                                                @if(isset($yearCol)&&($yearCol==$i)) selected @endif>{{$i}}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-horizontal">
                                            <label class="control-label no-padding-right"
                                                   style="float: left;">배이름</label>

                                            <div class="col-sm-8">
                                                <select style="width:70%" id="shipNameCol">
                                                    <option value="0"></option>
                                                    @foreach($shipList as $ship)
                                                        @if(!$isHolder)
                                                            <option value="{{$ship['RegNo']}}"
                                                                    @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                                            </option>
                                                        @elseif(in_array($ship->shipID, $ships))
                                                            <option value="{{$ship['RegNo']}}"
                                                                    @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>{{ $ship['shipName_Cn'] .' | ' .$ship['shipName_En']}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-horizontal" style="text-align: right">
                                            <button class="btn btn-xs btn-primary" id="id-btn-collection-search" style="width: 80px">
                                                <i class="icon-search"></i>
                                                검색
                                            </button>
                                            <button class="btn btn-xs btn-warning" id="id-btn-collection-excel" style="width: 80px">
                                                <i class="icon-table"></i>
                                                Excel
                                            </button>
                                            <button class="btn btn-xs btn-primary" id="id-btn-collection-print" style="width: 80px">
                                                <i class="icon-print"></i>
                                                Print
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="space-10"></div>
                                        <div class="col-md-12">
                                            @endif
                                            @if(!isset($excel) || (isset($type) && $type == 2))
                                                <table class="table table-bordered table-striped table-hover"
                                                       id="ship_supplyplancollection_table">
                                                    <thead>
                                                    <tr class="black br-hblue">
                                                        <th class="center">년도</th>
                                                        <th class="center">배이름</th>
                                                        <th class="center">부문</th>
                                                        <th class="center">1월</th>
                                                        <th class="center">2월</th>
                                                        <th class="center">3월</th>
                                                        <th class="center">4월</th>
                                                        <th class="center">5월</th>
                                                        <th class="center">6월</th>
                                                        <th class="center">7월</th>
                                                        <th class="center">8월</th>
                                                        <th class="center">9월</th>
                                                        <th class="center">10월</th>
                                                        <th class="center">11월</th>
                                                        <th class="center">12월</th>
                                                        <th class="center">합계</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="center"
                                                            rowspan="{{$nShips*($nDepts+1)}}">{{$yearCol}}</td>
                                                    <?php $shipIndex = 1;?>
                                                    @foreach($shipcolList as $ship)
                                                        <?php $shipRow = 0;?>
                                                        @if($shipIndex!=1)
                                                        <tr>
                                                        @endif
                                                            <td class="center"
                                                                rowspan="{{$nDepts+1}}">{{$ship['shipName_Cn']}}</td>
                                                            <?php $deptIndex = 1;?>
                                                            @foreach($deptInfos as $deptInfo)
                                                                <?php $shipRow = 1;?>
                                                                @if($deptIndex!=1)
                                                                    <tr>@endif
                                                                        <td class="center">{{$deptInfo['Dept_Cn']}}</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'1']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'1'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'2']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'2'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'3']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'3'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'4']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'4'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'5']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'5'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'6']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'6'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'7']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'7'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'8']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'8'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'9']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'9'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'10']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'10'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'11']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'11'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'12']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'12'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.$ship['ShipName'].$deptInfo['id'].'0'])}}@endif</td>
                                                                    </tr>

                                                                    <?php $deptIndex++?>
                                                                    @endforeach
                                                                    <tr class="total">
                                                                        <td class="center">합계</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'1'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'1'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'2'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'2'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'3'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'3'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'4'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'4'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'5'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'5'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'6'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'6'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'7'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'7'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'8'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'8'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'9'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'9'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'10'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'10'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'11'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'11'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'12'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'12'.$ship['ShipName']])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'0'.$ship['ShipName']]))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'0'.$ship['ShipName']])}}@endif</td>
                                                                    </tr>
                                                                    <?php $shipIndex++;?>
                                                                    <?php $shipRow = 0;?>
                                                                    @endforeach
                                                                    </tr>
                                                                    <tr class="total1">
                                                                        <td class="center" colspan="3">계</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'1'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'1'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'2'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'2'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'3'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'3'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'4'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'4'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'5'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'5'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'6'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'6'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'7'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'7'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'8'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'8'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'9'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'9'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'10'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'10'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'11'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'11'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'12'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'12'.'0'])}}@endif</td>
                                                                        <td class="right">@if(isset($supplyAmounts[$yearCol.'0'.'0']))
                                                                                {{\App\Http\Controllers\Util::getNumberFtNZ($supplyAmounts[$yearCol.'0'.'0'])}}@endif</td>
                                                                    </tr>

                                                    </tbody>
                                                </table>
                                            @endif
                                            @if(!isset($excel))
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript">

            $(function () {
                var tabId = Math.floor('{{$tab}}');
                if (tabId == 1) {
                    $('#myTab4 li:eq(1) a').tab('show');
                } else {
                    $('#myTab4 li:eq(0) a').tab('show');
                }

            });
            $('#id-btn-search').click(function () {
                var curyearCol = $('#yearCol').val();
                var shipnameCol = $("#shipNameCol").val();
                var curyear = $('#year').val();
                var shipname = $("#shipName").val();
                location.href = 'supplyplan?year=' + curyear + '&shipid=' + shipname + '&menuId=' + '{{$GLOBALS['selMenu']}}' + '&yearCol=' + curyear +
                        '&shipNameCol=' + shipnameCol + '&tab=' + 0;
            });
            $('#id-btn-excel').click(function () {
                var curyearCol = $('#yearCol').val();
                var shipnameCol = $("#shipNameCol").val();
                var curyear = $('#year').val();
                var shipname = $("#shipName").val();
                location.href = 'supplyplanExcel?year=' + curyear + '&shipid=' + shipname + '&menuId=' + '{{$GLOBALS['selMenu']}}' + '&yearCol=' + curyear +
                        '&shipNameCol=' + shipnameCol + '&tab=' + 0 + '&type=1';
            });
            $('#id-btn-collection-search').click(function () {
                var curyearCol = $('#yearCol').val();
                var shipnameCol = $("#shipNameCol").val();
                var curyear = $('#year').val();
                var shipname = $("#shipName").val();
                location.href = 'supplyplan?year=' + curyear + '&shipid=' + shipname + '&menuId=' +
                        '{{$GLOBALS['selMenu']}}' + '&yearCol=' + curyearCol + '&shipNameCol=' + shipnameCol + '&tab=' + 1;
            });
            $('#id-btn-collection-excel').click(function () {
                var curyearCol = $('#yearCol').val();
                var shipnameCol = $("#shipNameCol").val();
                var curyear = $('#year').val();
                var shipname = $("#shipName").val();
                location.href = 'supplyplanExcel?year=' + curyear + '&shipid=' + shipname + '&menuId=' +
                        '{{$GLOBALS['selMenu']}}' + '&yearCol=' + curyearCol + '&shipNameCol=' + shipnameCol + '&tab=' + 1 + '&type=2';
            });
            $('#id-btn-collection-print').click(function () {
                var curyearCol = $('#yearCol').val();
                var shipnameCol = $("#shipNameCol").val();
                var curyear = $('#year').val();
                var shipname = $("#shipName").val();
                window.open('supplyplanPrint?year=' + curyear + '&shipid=' + shipname + '&menuId=' +
                        '{{$GLOBALS['selMenu']}}' + '&yearCol=' + curyearCol + '&shipNameCol=' + shipnameCol);
            });

            function supplyPlanListAdd(that) {
                $('#ship_supplyplan_table tr').removeClass("table-row-selected");
                that.addClass('table-row-selected');
                var id = that.data('id');
                $('#supplyPlanId').val(id);
                if (id == 0) {
                    document.getElementById("supplyPlanListAdd").reset();
                    $('#supplyPlanAdd').html('<i class="icon-plus-sign bigger-50"></i> 추가').removeAttr('disabled');
                } else {
                    $('#yearly').val(that.find('td').eq(0).text());
                    $('#monthly').val(that.find('td').eq(1).text());
                    $('#ShipName').val(that.find('td').eq(2).data('id'));
                    $('#dept').val(that.find('td').eq(3).data('id'));
                    $('#PlanContent').val(that.find('td').eq(4).text());
                    $('#PlanAmount').val(that.find('td').eq(5).data("amount"));
                    $('#PlanRemark').val(that.find('td').eq(6).text());
                    $('#supplyPlanAdd').html('<i class="icon-edit bigger-50"></i> 변경').removeAttr('disabled');
                }
            }

//            $('#PlanAmount').ace_spinner({
//                value: 0,
//                min: 0,
//                max: 10000,
//                step: 10,
//                btn_up_class: 'btn-primary',
//                btn_down_class: 'btn-primary'
//            }).on('change', function () {
//                //alert(this.value)
//            });
            $('#supplyPlanAdd').on('click', function () {
                var supplyPlanId = $('#supplyPlanId').val();
                var yearly = $('#yearly').val();
                var monthly = $('#monthly').val();
                var shipName = $('#ShipName').val();
                var dept = $('#dept').val();
                var planContent = $('#PlanContent').val();
                var planAmount = $('#PlanAmount').val();
                var planRemark = $('#PlanRemark').val();
                if (yearly == '' || monthly == '' || shipName == '' || dept == '') return;
                $.ajax({
                    type: 'POST',
                    url: '/shipTechnique/supplyPlanAdd',
                    data: {
                        _token: "{{csrf_token()}}",
                        id: supplyPlanId,
                        yearly: yearly,
                        monthly: monthly,
                        shipName: shipName,
                        dept: dept,
                        planContent: planContent,
                        planAmount: planAmount,
                        planRemark: planRemark
                    },
                    success: function (data) {
                        console.log(data);
                        if (data == -1) {
                            $.gritter.add({
                                title: '오유',
                                text: '자료가 증복되므로 입력할수 없습니다.갱신을 진행하십시오.',
                                class_name: 'gritter-error'
                            });
                            return;
                        }
                        if (supplyPlanId == '') supplyPlanId = 0;
                        var that = $('tr[data-id="' + supplyPlanId + '"]');
                        that.find('td').eq(0).text($('#yearly').val());
                        that.find('td').eq(1).text($('#monthly').val());
                        that.find('td').eq(2).text($('#ShipName option[value="' + $('#ShipName').val() + '"]').text()).data('id', $('#ShipName').val());
                        that.find('td').eq(3).text($('#dept option[value="' + $('#dept').val() + '"]').text()).data('id', $('#dept').val());
                        that.find('td').eq(4).text($('#PlanContent').val());
                        that.find('td').eq(5).text($('#PlanAmount').val());
                        that.find('td').eq(6).text($('#PlanRemark').val());
                        that.attr('data-id', data).css('background-color', '#D3CCBF');
                        $('#supplyPlanId').val(data);

                        if (supplyPlanId == 0 || supplyPlanId == '') {
                            var tr = '<tr data-id="0" onclick="supplyPlanListAdd($(this))">\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                <td class="center"></td>\
                                </tr>';
                            $('#ship_supplyplan_table tbody').append(tr);
                        }
                        $('#supplyPlanAdd').html('<i class="icon-edit bigger-50"></i> 변경');
                    }
                });
            });

        </script>
    @endif
@endsection