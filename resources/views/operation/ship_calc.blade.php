@extends('layout.sidebar')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-4">
                <h5><b>운임계산서</b></h5>

            </div>
            <div class="col-sm-8">
                <h5><b>{{transShipOperation("shipCalc.ShipName")}}</b>
                    <select name="selectShips" class="selectShipCtrl">
                        @foreach($shipList as $ship)
                            <option value="{{ $ship->RegNo }}" {{ $shipID != $ship->RegNo ? '' : 'selected' }}>{{ $ship->shipName_En }} | {{ $ship->shipName_Cn }}</option>
                        @endforeach
                    </select>
                    &nbsp;&nbsp;
                    <b>{{transShipOperation("shipCalc.Voy")}}:</b>
                    <select name="selectVoys" class="selectVoysCtrl">
                        @foreach($voyList as $voy)
                        <option value="{{ $voy->id }}" @if($voyId == $voy->id) selected @endif }}>{{ $voy->Voy_No }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm print_stand" style="float: right; width :80px;"><i class="icon-print"></i>인쇄</button>
                </h5>
            </div>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            @if($voyId != 0)
                <div class="row">
                    <div class="widget-box">
                        <div class="widget-header widget-header-flat">
                            <div class="col-md-4" style="text-align: center">{{transShipOperation("shipCalc.CalcDate")}}:&nbsp;
                                <input type="text" value="{{ $data->CP_Date }}"></div>
                            <div class="col-md-4" style="text-align: center;">
                                {{transShipOperation("shipCalc.CpType")}}:&nbsp;
                                <select name="" id="" style="width: 60%;margin-top:4px">
                                    <option value="1" @if($data->CP_kind == 1) selected="selected" @endif>航次租船계약</option>
                                    <option value="2" @if($data->CP_kind == 2) selected="selected" @endif>期租船계약</option>
                                </select></div>
                            <div class="col-md-4" style="text-align: center;padding-top: 10px;">{{transShipOperation("shipCalc.Ref No")}}:&nbsp;
                                <span id="">{{ $data->CP_No }}</span></div>
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
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->Cargo_Name }} / {{ $data->Cgo_Qtty }}"> {{$data->Unit}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp")}} / {{transShipOperation("shipCalc.Dp")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;white-space: nowrap;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->L_Port }}"> / <input type="text" style="width: 250px;text-align: right;" value="{{ $data->D_Port }}"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Freight Fee")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input  type="text" style="text-align: right" value="{{ $data->Freight }}"> $ / {{$data->Unit}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Good Day")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: right" value="{{ $data->LayCan_Date1 }}">&nbsp;~&nbsp;<input type="text" value="{{ $data->LayCan_Date2 }}"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp&DpCondition")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: right" value="{{ $data->L_Rate }}"> / <input type="text" value="{{ $data->D_Rate }}"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Demurrage")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: right"  value="{{ $data->Demurrage }}"> $ / {{transShipOperation("shipCalc.Day")}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Brokerage")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="text-align: right" value="{{ $data->Brokerage }}"> %</td>
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
                                            <td style="width: 80%;padding-left: 10% !important;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->Cargo_Name }} / {{ $data->Cgo_Qtty }}"> {{$data->Unit}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Lp")}} / {{transShipOperation("shipCalc.Dp")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;white-space: nowrap;"><input type="text" style="width: 250px;text-align: left" value="{{ $data->L_Port }}"> / <input type="text" style="width: 250px;text-align: right;" value="{{ $data->D_Port }}"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right;white-space: nowrap;">{{transShipOperation("shipCalc.Brokerage Fee")}}:</td>
                                            <td style="width: 80%;padding-left: 10% !important;"><input  type="text" style="text-align: right" value="{{ $data->Freight }}"> $ / {{$data->Unit}}</td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-striped table-bordered" style="font-size: 13px">
                        <thead>
                            <tr class="black br-hblue">
                                <th>{{transShipOperation("shipCalc.Income/Expense Content")}}</th>
                                <th>{{transShipOperation("shipCalc.Income Money")}}</th>
                                <th>{{transShipOperation("shipCalc.Expense Money")}}</th>
                                <th>{{transShipOperation("shipCalc.Status")}}</th>
                                <th>{{transShipOperation("shipCalc.CmpltDate")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $incomeSum = 0; $expenseSum = 0; ?>
                            @foreach($invoiceList as $invoice)
                                <?php
                                    if($invoice->C_D == 'Credit') $incomeSum += $invoice->Amount;
                                    if($invoice->C_D == 'Debit')  $expenseSum += $invoice->Amount; ?>
                            <tr>
                                <td style="text-align: left">{{ $invoice->Discription }}</td>
                                <td style="text-align: right">@if($invoice->C_D == 'Credit'){{ \App\Http\Controllers\Util::getNumberFt($invoice->Amount) }}@endif</td>
                                <td style="text-align: right">@if($invoice->C_D == 'Debit'){{ \App\Http\Controllers\Util::getNumberFt($invoice->Amount) }}@endif</td>
                                <td class="center"><input type="checkbox" {{ $invoice->Recipt == 1 ? 'checked' : '' }}></td>
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


    <script>
        jQuery(function(e){

            $('.selectShipCtrl').on('change', function(){
                var ship = $(this).val();

                location.href = 'shipCalc?shipId=' + ship;
            });

            $('.selectVoysCtrl').on('change', function(){
                var voy = $(this).val();
                var ship = $('.selectShipCtrl').val();

                location.href = 'shipCalc?shipId=' + ship + '&voyId=' + voy;
            });

            $('.print_stand').on('click', function() {
                var voy = $('.selectVoysCtrl').val();
                var ship = $('.selectShipCtrl').val();
                window.open('shipCalcPrint?shipId=' + ship + '&voyId=' + voy);
            });

            $('#btn-add-view').on('click',function(){
                $('.add-movement-box').fadeIn();
                $(this).toggle();
            });
            $('#btn-close').on('click',function(){
                $('.add-movement-box').fadeOut();
                $('#btn-add-view').toggle();
            });


        });
    </script>

@stop
