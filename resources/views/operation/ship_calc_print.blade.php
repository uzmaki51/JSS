@extends('layout.header-print')
<style>
    .main-container{
        margin-top: -30px!important;
    }
    td{
        font-size: 12px !important;
        line-height: 1.0 !important;
        height: 20px !important;
    }
    table>tbody>tr>td{
        padding: 0px !important;
    }
    h5{
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }
</style>
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-8 col-md-offset-2">
                <h3 class="center">운임계산서</h3>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row" style="text-align: center">
                <div class="col-md-6 col-md-offset-3">
                    <h5><b>{{transShipOperation("shipCalc.ShipName")}}:</b>
                        <select name="selectShips" class="selectShipCtrl">
                            @foreach($shipList as $ship)
                                <option value="{{ $ship->RegNo }}" {{ $shipID != $ship->RegNo ? '' : 'selected' }}> {{ $ship->shipName_Cn }}</option>
                            @endforeach
                        </select>
                        &nbsp;&nbsp;
                        <b style="margin-left:100px;">{{transShipOperation("shipCalc.Voy")}}:</b>
                        <select name="selectVoys" class="selectVoysCtrl">
                            @foreach($voyList as $voy)
                                <option value="{{ $voy->id }}" @if($voyId == $voy->id) selected @endif }}>{{ $voy->Voy_No }}</option>
                            @endforeach
                        </select>
                    </h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if($voyId != 0)
                        <div class="row" style="margin: 0">
                            <div class="widget-box">
                                <div class="widget-header widget-header-flat">
                                    <div class="col-md-12" style="text-align: center">
                                        <span>
                                            {{transShipOperation("shipCalc.CalcDate")}}:&nbsp;
                                            <input type="text" style="width:100px" value="{{ $data->CP_Date }}">
                                        </span>
                                        <span>
                                            {{transShipOperation("shipCalc.CpType")}}:&nbsp;
                                            <select>
                                                <option value="1" @if($data->CP_kind == 1) selected="selected" @endif>항차용선계약</option>
                                                <option value="2" @if($data->CP_kind == 2) selected="selected" @endif>기간용선계약</option>
                                            </select>
                                        </span>
                                        <span>
                                            {{transShipOperation("shipCalc.Ref No")}}:&nbsp;
                                            <input type="text" style="width:100px" value="{{ $data->CP_No }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        @if($data->CP_kind == 1)
                                            <table>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap; width: 20%;">{{transShipOperation("shipCalc.Charterer")}}:</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: left;width: 250px;" value="{{ $data->Charterer }}"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Cargo and Count")}}:</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->Cargo_Name }} / {{ $data->Cgo_Qtty }}"> {{transShipOperation("shipCalc.Ton")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp")}} / {{transShipOperation("shipCalc.Dp")}}:</td>
                                                <td style="width: 80%;padding-left: 10% !important;white-space: nowrap;">{{ $data->L_Port }} / {{ $data->D_Port }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Freight Fee")}}</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input  type="text" style="text-align: left;width: 50px;" value="{{ $data->Freight }}"> $ / {{transShipOperation("shipCalc.Ton")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Good Day")}}</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: left;width: 100px;" value="{{ $data->LayCan_Date1 }}">&nbsp;~&nbsp;<input type="text" style="text-align: right;width: 100px;" value="{{ $data->LayCan_Date1 }}"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp&DpCondition")}}</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text"  style="text-align: left;width: 100px;" value="{{ $data->L_Rate }}"> / <input type="text"  style="text-align: right;width: 100px;" value="{{ $data->D_Rate }}"></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Demurrage")}}</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: left;width: 100px;"  value="{{ $data->Demurrage }}"> $ / {{transShipOperation("shipCalc.Day")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Brokerage")}}</td>
                                                <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: left;width: 100px;" value="{{ $data->Brokerage }}"> %</td>
                                            </tr>
                                        </table>
                                        @else
                                            <table>
                                                <tr>
                                                    <td style="text-align: right;white-space: nowrap; width: 20%;">{{transShipOperation("shipCalc.Charterer")}}:</td>
                                                    <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: left;width: 250px;" value="{{ $data->Charterer }}"></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Cargo and Count")}}:</td>
                                                    <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->Cargo_Name }} / {{ $data->Cgo_Qtty }}"> {{transShipOperation("shipCalc.Ton")}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp")}} / {{transShipOperation("shipCalc.Dp")}}:</td>
                                                    <td style="width: 80%;padding-left: 10% !important;white-space: nowrap;">{{ $data->L_Port }} / {{ $data->D_Port }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Brokerage Fee")}}:</td>
                                                    <td style="width: 80%;padding-left: 10% !important;"><input  type="text" style="text-align: left;width: 50px;" value="{{ $data->Freight }}"> $ / {{$data->Unit}}</td>
                                                </tr>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin: 0">
                            <table class="table table-striped table-bordered" style="font-size: 13px">
                                <thead>
                                <tr class="black br-hblue">
                                    <th style="text-align: center">{{transShipOperation("shipCalc.Income/Expense Content")}}</th>
                                    <th style="text-align: center">{{transShipOperation("shipCalc.Income Money")}}</th>
                                    <th style="text-align: center">{{transShipOperation("shipCalc.Expense Money")}}</th>
                                    <th style="text-align: center">{{transShipOperation("shipCalc.Status")}}</th>
                                    <th style="text-align: center">{{transShipOperation("shipCalc.CmpltDate")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $incomeSum = 0; $expenseSum = 0; ?>
                                @foreach($invoiceList as $invoice)
                                    <?php
                                    if($invoice->C_D == 'Credit') $incomeSum += $invoice->Amount;
                                    if($invoice->C_D == 'Debit')  $expenseSum += $invoice->Amount; ?>
                                    <tr>
                                        <td>{{ $invoice->Discription }}</td>
                                        <td style="text-align: right">@if($invoice->C_D == 'Credit'){{ $invoice->Amount }}@endif</td>
                                        <td style="text-align: right">@if($invoice->C_D == 'Debit'){{ $invoice->Amount }}@endif</td>
                                        <td style="text-align: center"><input type="checkbox" {{ $invoice->Recipt == 1 ? 'checked' : '' }}></td>
                                        <td style="text-align: center">{{ $invoice->Recipt_Date }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="text-align: right"><b>계</b></td>
                                    <td style="text-align: right"><b>{{ \App\Http\Controllers\Util::getNumberFt($incomeSum) }}</b></td>
                                    <td style="text-align: right"><b>{{ \App\Http\Controllers\Util::getNumberFt($expenseSum) }}</b></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-block alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="icon-remove"></i>
                                    </button>
                                    <i class="icon-exclamation-sign red"></i>
                                    현재 선택한 배에 대한 운임자료를 얻을수 없습니다.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@stop
