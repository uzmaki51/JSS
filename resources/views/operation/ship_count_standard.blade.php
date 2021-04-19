<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'header';
?>

<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-6">
                        <h4><b>航次盘算</b>
                            <small>
                                <i class="icon-double-angle-right"></i>标准
                            </small>
                        </h4>
                    </div>
                    @if(!$isHolder)
                    <div class="col-sm-6">
                        <div style="float:right">
                            <button class="btn btn-primary btn-sm calc_btn">
                                {{transShipOperation("standard.Input Voyage data")}}&nbsp;
                                <i class="icon-double-angle-right"></i>
                            </button>
                        </div>
                    </div>
                        @endif
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label style="float: left;padding-top:7px">{{transShipOperation("standard.ShipName")}}:</label>
                            <select class="form-control ship_select" style="width:70%;float: left;margin-left:10px">
                                @foreach($shipList as $ship)
                                    <option value="{{$ship['RegNo']}}" @if($ship['RegNo'] == $shipId) selected @endif>{{$ship['shipName_En']}} | {{$ship['shipName_Cn']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label style="float: left;padding-top:7px">航次:</label>
                            <select class="form-control" style="width:70%;float:left;margin-left:10px" id="voy_select">
                                @foreach($voyList as $voy)
                                    <option value="{{$voy['id']}}" @if($voy['id'] == $voyId) selected @endif>{{$voy['Voy_No']}} | {{$voy['CP_No']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-sm search_stand" style="width: 80px"><i class="icon-search"></i>搜索</button>
                            <button class="btn btn-warning btn-sm excel_stand" style="width: 80px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                            <button class="btn btn-primary btn-sm print_stand" style="width: 80px"><i class="icon-print"></i>打印</button>
                            @if(!$isHolder)
                                <button class="btn btn-sm btn-primary no-radius" style="width: 80px"><i class="icon-save"></i>登记</button>
                            @endif
                        </div>
                    </div>
                    <div class="space-4"></div>
                    @else
                        @include('layout.excel-style')
                    @endif
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">1. {{transShipOperation("standard.Charter Party Terms")}}</h5>
                            </div>
                            <div class="panel-body">
                                <table class="arc-std-table table table-bordered">
                                    <tr>
                                        <td class="label-right">{{transShipOperation("standard.ShipName")}}</td>
                                        <td class="center">{{$voyInfo['shipName']['shipName_En']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.Voy")}}</td>
                                        <td class="center">{{$voyInfo['Voy_No']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.CP Date")}}</td>
                                        <td class="center">{{convert_date($voyInfo['CP_Date'])}}</td>
                                        <td class="label-right">{{transShipOperation("standard.CP No")}}</td>
                                        <td class="center">{{$voyInfo['CP_No']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.Cargo")}}</td>
                                        <td class="center">{{$voyInfo->carGoName()}}</td>
                                    </tr>
                                    <tr>
                                        <td  class="label-right">{{transShipOperation("standard.L/P")}}</td>
                                        <td class="center">{{$voyInfo->lPortName()}}</td>
                                        <td  class="label-right">{{transShipOperation("standard.D/P")}}</td>
                                        <td class="center">{{$voyInfo->dPortName()}}</td>
                                        <td  class="label-right">{{transShipOperation("standard.Lay Date")}}</td>
                                        <td class="center">{{convert_date($voyInfo['LayCan_Date1'])}}</td>
                                        <td  class="label-right">{{transShipOperation("standard.Can Date")}}</td>
                                        <td class="center">{{convert_date($voyInfo['LayCan_Date2'])}}</td>
                                        <td  class="label-right">{{transShipOperation("standard.CgoQtty[MT]")}}</td>
                                        <td class="center">{{$voyInfo['Cgo_Qtty']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-right">{{transShipOperation("standard.L/Rate")}}</td>
                                        <td class="center">{{$voyInfo['L_Rate']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.D/Rate")}}</td>
                                        <td class="center">{{$voyInfo['D_Rate']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.Freight")}}/{{transShipOperation("standard.Lumpsum")}}</td>
                                        <td class="center">{{$voyInfo['Freight']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.Broker")}}</td>
                                        <td class="center">{{$voyInfo['Brokerage']}}</td>
                                        <td class="label-right">{{transShipOperation("standard.Demurrage")}}</td>
                                        <td class="center">{{$voyInfo['Demurrage']}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
						<?php
						$sail_day = $proInfo['Speed'] == 0 ? 0 : Round($proInfo['PracDistance'] / $proInfo['Speed']/24, 2);
						$sail_time = $sailInfo->SailTime;
						$ld_time = $sailInfo->L_Time + $sailInfo->D_Time;
						$idle_time = $sailInfo->DateInteval - ($sailInfo->SailTime + $sailInfo->L_Time + $sailInfo->D_Time);
						$dateInteval = $sailInfo->DateInteval;
						if(is_null($dateInteval) || ($dateInteval == 0))
							$dateInteval = 1;
						$pro_fo = Round($sail_time * $proInfo['DailyPrac_Fo_Sail'] + $ld_time * $proInfo['DailyPrac_Fo_LD'] + $idle_time * $proInfo['DailyPrac_Fo_Idle'], 2);
						$pro_do = Round($sail_time * $proInfo['DailyPrac_Do_Sail'] + $ld_time * $proInfo['DailyPrac_Do_LD'] + $idle_time * $proInfo['DailyPrac_Do_Idle'], 2);
						$pro_lo = Round($sail_time * $proInfo['DailyPrac_Lo_Sail'] + $ld_time * $proInfo['DailyPrac_Lo_LD'] + $idle_time * $proInfo['DailyPrac_Lo_Idle'], 2);
						$pro_exp_fo = Round(($sail_day * $proInfo['DailyPrac_Fo_Sail'] + $proInfo['LD_Day'] * $proInfo['DailyPrac_Fo_LD'] + $proInfo['Idle_Day'] * $proInfo['DailyPrac_Fo_Idle']) * $proInfo['Fo_Unit'], 2);
						$pro_exp_do = Round(($sail_day * $proInfo['DailyPrac_Do_Sail'] + $proInfo['LD_Day'] * $proInfo['DailyPrac_Do_LD'] + $proInfo['Idle_Day'] * $proInfo['DailyPrac_Do_Idle']) * $proInfo['Do_Unit'], 2);
						$pro_exp_lo = Round(($sail_day * $proInfo['DailyPrac_Lo_Sail'] + $proInfo['LD_Day'] * $proInfo['DailyPrac_Lo_LD'] + $proInfo['Idle_Day'] * $proInfo['DailyPrac_Lo_Idle']) * $proInfo['Lo_Unit'], 2);
						$voyDay = $planInfo['YEARLY VOY DAY'];
						if(is_null($voyDay) || ($voyDay == 0))
							$voyDay = 365;
						$interval_pro = $sail_day + $proInfo['LD_Day'] + $proInfo['Idle_Day'];
						$ss_pro = Round($planInfo['SS'] * $interval_pro / $voyDay, 2);
						$ctm_pro = Round($planInfo['CTM'] * $interval_pro / $voyDay, 2);
						$telcom_pro = Round($planInfo['TELCOM'] * $interval_pro / $voyDay, 2);
						$insurance_pro = Round($planInfo['INSURANCE'] * $interval_pro / $voyDay, 2);
						$oap_pro = Round($planInfo['OAP'] * $interval_pro / $voyDay, 2);
						$ism_pro = Round($planInfo['ISM'] * $interval_pro / $voyDay, 2);
						$other_pro = Round($planInfo['OTHERS'] * $interval_pro / $voyDay, 2);
						$prac_exp_fo = Round($proInfo['Prac_Fo'] * $proInfo['Fo_Unit'], 2);
						$prac_exp_do = Round($proInfo['Prac_Do'] * $proInfo['Do_Unit'], 2);
						$prac_exp_lo = Round($proInfo['Prac_Lo'] * $proInfo['Lo_Unit'], 2);
						$ss_prac = Round($planInfo['SS'] * $sailInfo->DateInteval / $voyDay, 2);
						$ctm_prac = Round($planInfo['CTM'] * $sailInfo->DateInteval / $voyDay, 2);
						$telcom_prac = Round($planInfo['TELCOM'] * $sailInfo->DateInteval / $voyDay, 2);
						$insurance_prac = Round($planInfo['INSURANCE'] * $sailInfo->DateInteval / $voyDay, 2);
						$oap_prac = Round($planInfo['OAP'] * $sailInfo->DateInteval / $voyDay, 2);
						$ism_prac = Round($planInfo['ISM'] * $sailInfo->DateInteval / $voyDay, 2);
						$other_prac = Round($planInfo['OTHERS'] * $sailInfo->DateInteval / $voyDay, 2);

						$pro_tt = Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] + $proInfo['Pro_AddIn'] - ($voyInfo['Cgo_Qtty'] * $voyInfo['Freight']*$voyInfo['Brokerage'])/100, 2);
						$pro_daily_income = ($interval_pro == 0) ? 0 : Round($pro_tt / $interval_pro, 2);
						$pro_tt_expense = $pro_exp_fo + $pro_exp_do + $pro_exp_lo + $ss_pro + $proInfo['PDA_Prog'] + $ctm_pro + $insurance_pro + $oap_pro + $telcom_pro + $ism_pro + $other_pro;
						$pro_daily_expense = ($interval_pro == 0) ? 0 : Round($pro_tt_expense / $interval_pro, 2);
						$pro_daily_profit = $pro_daily_income - $pro_daily_expense;
						$pro_tt_profit = $pro_tt - $pro_tt_expense;
						$prac_tt = $incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage;
						$prac_daily_income = Round($prac_tt / $dateInteval, 2);
						$prac_tt_expense = $prac_exp_fo + $prac_exp_do + $prac_exp_lo + $ss_prac + $incomeInfo->Pda + $ctm_prac + $insurance_prac + $oap_prac + $telcom_prac + $ism_prac + $other_prac;
						$prac_daily_expense = Round($prac_tt_expense / $dateInteval, 2);
						$prac_daily_profit = $prac_daily_income - $prac_daily_expense;
						$prac_tt_profit = $prac_tt - $prac_tt_expense;
						?>

                        <div class="col-md-8" style="padding-left:0">
                            <div class="panel panel-default no-padding">
                                <div class="panel-heading">
                                    <h5 class="panel-title">2. {{transShipOperation("standard.Voyage Qantitative Analysis")}}</h5>
                                </div>
                                <div class="panel-body no-padding">
                                    <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th class="center" rowspan="2">{{transShipOperation("standard.Distance")}}<br>[{{transShipOperation("standard.Mile")}}]</th>
                                            <th class="center" rowspan="2">{{transShipOperation("standard.Speed")}}<br>[{{transShipOperation("standard.Kn")}}]</th>
                                            <th rowspan="2"></th>
                                            <th class="center" colspan="4">{{transShipOperation("standard.Round Trip")}}</th>
                                            <th class="center" colspan="3">{{transShipOperation("standard.Demurrage")}}</th>
                                        </tr>
                                        <tr class="black br-hblue">
                                            <th class="center">{{transShipOperation("standard.Sail Day")}}</th>
                                            <th class="center">{{transShipOperation("standard.LD Day")}}</th>
                                            <th class="center">{{transShipOperation("standard.Idle Day")}}</th>
                                            <th class="center">{{transShipOperation("standard.Duration")}}</th>
                                            <th class="center">{{transShipOperation("standard.FO [MT]")}}</th>
                                            <th class="center">{{transShipOperation("standard.DO [MT]")}}</th>
                                            <th class="center">{{transShipOperation("standard.LO [Kg]")}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr >
                                            <td class="center" rowspan="3">{{$proInfo['PracDistance']}}</td>
                                            <td class="center" rowspan="3">{{$proInfo['Speed']}}</td>
                                            <td class="center">{{transShipOperation("standard.Programe")}}</td>
                                            <td class="center">{{$sail_day}}</td>
                                            <td class="center">{{$proInfo['LD_Day']}}</td>
                                            <td class="center">{{$proInfo['Idle_Day']}}</td>
                                            <td class="center">{{$sail_day + $proInfo['LD_Day'] + $proInfo['Idle_Day']}}</td>
                                            <td class="center">{{$pro_fo}}</td>
                                            <td class="center">{{$pro_do}}</td>
                                            <td class="center">{{$pro_lo}}</td>
                                        </tr>
                                        <tr >
                                            <td class="center">{{transShipOperation("standard.Practice")}}</td>
                                            <td class="center">{{$sail_time}}</td>
                                            <td class="center">{{$ld_time}}</td>
                                            <td class="center">{{$idle_time}}</td>
                                            <td class="center">{{$sailInfo->DateInteval}}</td>
                                            <td class="center">{{$proInfo['Prac_Fo']}}</td>
                                            <td class="center">{{$proInfo['Prac_Do']}}</td>
                                            <td class="center">{{$proInfo['Prac_Lo']}}</td>
                                        </tr>
                                        <tr >
                                            <td class="center">{{transShipOperation("standard.Difference")}}</td>
                                            <td class="center">{{Round($sail_day-$sail_time, 2)}}</td>
                                            <td class="center">{{Round($proInfo['LD_Day']-$ld_time, 2)}}</td>
                                            <td class="center">{{Round($proInfo['Idle_Day']-$idle_time, 2)}}</td>
                                            <td class="center">{{Round($sail_day + $proInfo['LD_Day'] + $proInfo['Idle_Day'] - $sailInfo->DateInteval, 2)}}</td>
                                            <td class="center">{{Round($pro_fo-$proInfo['Prac_Fo'], 2)}}</td>
                                            <td class="center">{{Round($pro_do-$proInfo['Prac_Do'], 2)}}</td>
                                            <td class="center">{{Round($pro_lo-$proInfo['Prac_Lo'], 2)}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title">3. {{transShipOperation("standard.Income")}}</h5>
                                </div>
                                <div class="panel-body no-padding">
                                    <table class="arc-std-table table table-bordered table-hover">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th class="center">{{transShipOperation("standard.Cp [MT]")}}</th>
                                            <th class="center">{{transShipOperation("standard.B/L [MT]")}}</th>
                                            <th class="center">{{transShipOperation("standard.Freight")}}</th>
                                            <th></th>
                                            <th class="center">{{transShipOperation("standard.Frt")}} [$]</th>
                                            <th class="center">{{transShipOperation("standard.Add In")}} [$]</th>
                                            <th class="center">{{transShipOperation("standard.Commi")}} [$]</th>
                                            <th class="center">{{transShipOperation("standard.Income")}} [$]</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="center" rowspan="3">{{$voyInfo['Cgo_Qtty']}}</td>
                                            <td class="center" rowspan="3">{{$voyInfo['B_L']}}</td>
                                            <td class="center" rowspan="3">{{$voyInfo['Freight']}}</td>
                                            <td class="center">{{transShipOperation("standard.Programe")}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'])}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($proInfo['Pro_AddIn'])}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ(($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] * $voyInfo['Brokerage'])/100)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.Practice")}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($incomeInfo->Frt)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($incomeInfo->Add_In)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($incomeInfo->Brokerage)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_tt)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center">{{transShipOperation("standard.Difference")}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ(Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'], 2) - $incomeInfo->Frt)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($proInfo['Pro_AddIn'] - $incomeInfo->Add_In)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ(Round(($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] * $voyInfo['Brokerage'])/100, 2) - $incomeInfo->Brokerage)}}</td>
                                            <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ(Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] + $proInfo['Pro_AddIn'] - ($voyInfo['Cgo_Qtty'] * $voyInfo['Freight']*$voyInfo['Brokerage'])/100, 2) - ($incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage))}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default col-md-4 no-padding">
                            <div class="panel-heading">
                                <h5 class="panel-title">5. {{transShipOperation("standard.Voyage Analysis")}}</h5>
                            </div>
                            <div class="panel-body">
                                <h5 style="margin-top: 0">{{transShipOperation("standard.Daily")}}</h5>
                                <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th></th>
                                        <th class="center">{{transShipOperation("standard.Income")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.Expense")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.Profit")}} [$]</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Programe")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_income)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_expense)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_profit)}}</td>
                                    </tr>
                                    <tr >
                                        <td class="center">{{transShipOperation("standard.Practice")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_daily_income)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_daily_expense)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_daily_profit)}}</td>
                                    </tr>
                                    <tr >
                                        <td class="center">{{transShipOperation("standard.Difference")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_income - $prac_daily_income)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_expense - $prac_daily_expense)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_daily_profit - $prac_daily_profit)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h5>{{transShipOperation("standard.Total")}}</h5>
                                <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th></th>
                                        <th class="center">{{transShipOperation("standard.Income")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.Expense")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.Profit")}} [$]</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Programe")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt_expense)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt_profit)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Practice")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_tt)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_tt_expense)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_tt_profit)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Difference")}}</td>
                                        <td class="center" id="cal_income">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt - $prac_tt)}}</td>
                                        <td class="center" id="cal_expense">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt_expense - $prac_tt_expense)}}</td>
                                        <td class="center" id="cal_profit">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_tt_profit - $prac_tt_profit)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">4. {{transShipOperation("standard.Expense")}}</h5>
                            </div>
                            <div class="panel-body no-padding">
                                <table id="" class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th></th>
                                        <th class="center">{{transShipOperation("standard.FO")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.DO")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.LO")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.PDA")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.SS")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.CTM")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.TELCOM")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.INSURCE")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.OAP")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.ISM")}} [$]</th>
                                        <th class="center">{{transShipOperation("standard.OTHERS")}} [$]</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Programe")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_fo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_do)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_lo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($proInfo['PDA_Prog'])}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ss_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ctm_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($telcom_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($insurance_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($oap_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ism_pro)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($other_pro)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Practice")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_exp_fo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_exp_do)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($prac_exp_lo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($incomeInfo->Pda)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ss_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ctm_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($telcom_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($insurance_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($oap_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ism_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($other_prac)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">{{transShipOperation("standard.Difference")}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_fo - $prac_exp_fo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_do - $prac_exp_do)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($pro_exp_lo - $prac_exp_lo)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($proInfo['PDA_Prog'] - $incomeInfo->Pda)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ss_pro - $ss_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ctm_pro - $ctm_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($telcom_pro - $telcom_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($insurance_pro - $insurance_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($oap_pro - $oap_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($ism_pro - $ism_prac)}}</td>
                                        <td class="center">{{\App\Http\Controllers\Util::getNumberFtNZ($other_pro - $other_prac)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(!isset($excel))
                </div>
            </div>
        </div>


        <script>
            var token = '{!! csrf_token() !!}';
            var voyId = '{!! $voyId !!}';
            jQuery(function(e){

                $('.ship_select').on('change', function () {
                    var shipId = $('.ship_select').val();
                    $.post('getVoyList', {'_token':token, 'shipId':shipId}, function(data) {
                        var htmlStr = '';
                        if(data) {
                            var result = jQuery.parseJSON(data);
                            var length = result.length;
                            for(var i=0; i<length; i++) {
                                var voy = result[i];
                                htmlStr += '<option value="' + voy.id + '">' + voy.Voy_No + ' | ' + voy.CP_No + '</option>';
                            }
                        }

                        $("#voy_select").html(htmlStr);
                    });
                });

                $('.search_stand').on('click', function () {
                    var shipId = $('.ship_select').val();
                    var voyId = $('#voy_select').val();
                    location.href = 'shipCountStandard?shipId=' + shipId + '&voy=' + voyId;
                });

                $('.excel_stand').on('click', function () {
                    var shipId = $('.ship_select').val();
                    var voyId = $('#voy_select').val();
                    location.href = 'shipCountStandardExcel?shipId=' + shipId + '&voy=' + voyId;
                });

                $('.save_stand').on('click', function () {
                    var income = $('#cal_income').text();
                    var expense = $('#cal_expense').text();
                    var profit = $('#cal_profit').text();

                    $.post('updateVoyProfit', {'_token':token, 'voyId':voyId, 'income':income, 'expense':expense, 'profit':profit}, function (data) {
                        var returnCode = parseInt(data);
                        if(returnCode == 1) {
                            $.gritter.add({
                                title: '成功',
                                text: '保存成功!',
                                class_name: 'gritter-success'
                            });
                        }
                    })
                })
                $('.calc_btn').on('click', function () {
                    location.href = 'voyCountCalculateInput';
                })

            });

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

        </script>
    @endif
@stop
