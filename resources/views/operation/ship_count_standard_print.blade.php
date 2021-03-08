@extends('layout.header-print')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="col-md-12">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">1. Charter Party Terms</h5>
                        </div>
                        <div class="panel-body">
                            <table class="arc-std-table table table-bordered table-hover">
                                <tr class="black br-hblue">
                                    <td style="text-align: right">ShipName</td>
                                    <td class="center">{{$voyInfo['shipName']['shipName_En']}}</td>
                                    <td style="text-align: right">Voy</td>
                                    <td class="center">{{$voyInfo['Voy_No']}}</td>
                                    <td style="text-align: right">CP Date</td>
                                    <td class="center">{{convert_date($voyInfo['CP_Date'])}}</td>
                                    <td style="text-align: right">CP No</td>
                                    <td class="center">{{$voyInfo['CP_No']}}</td>
                                    <td style="text-align: right">Cargo</td>
                                    <td class="center">{{$voyInfo->carGoName()}}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">L/P</td>
                                    <td class="center">{{$voyInfo->lPortName()}}</td>
                                    <td style="text-align: right">D/P</td>
                                    <td class="center">{{$voyInfo->dPortName()}}</td>
                                    <td style="text-align: right">Lay Date</td>
                                    <td class="center">{{convert_date($voyInfo['LayCan_Date1'])}}</td>
                                    <td style="text-align: right">Can Date</td>
                                    <td class="center">{{convert_date($voyInfo['LayCan_Date2'])}}</td>
                                    <td style="text-align: right">CgoQtty[MT]</td>
                                    <td class="center">{{$voyInfo['Cgo_Qtty']}}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">L/Rate</td>
                                    <td class="center">{{$voyInfo['L_Rate']}}</td>
                                    <td style="text-align: right">D/Rate</td>
                                    <td class="center">{{$voyInfo['D_Rate']}}</td>
                                    <td style="text-align: right">Freight</td>
                                    <td class="center">{{$voyInfo['Freight']}}</td>
                                    <td style="text-align: right">Broker</td>
                                    <td class="center">{{$voyInfo['Brokerage']}}</td>
                                    <td style="text-align: right">Demurrage</td>
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
                        $dunnage_pro = Round($planInfo['DUNNAGE'] * $interval_pro / $voyDay, 2);
                        $other_pro = Round($planInfo['OTHERS'] * $interval_pro / $voyDay, 2);
                        $prac_exp_fo = Round($proInfo['Prac_Fo'] * $proInfo['Fo_Unit'], 2);
                        $prac_exp_do = Round($proInfo['Prac_Do'] * $proInfo['Do_Unit'], 2);
                        $prac_exp_lo = Round($proInfo['Prac_Lo'] * $proInfo['Lo_Unit'], 2);
                        $ss_prac = Round($planInfo['SS'] * $sailInfo->DateInteval / $voyDay, 2);
                        $ctm_prac = Round($planInfo['CTM'] * $sailInfo->DateInteval / $voyDay, 2);
                        $telcom_prac = Round($planInfo['TELCOM'] * $sailInfo->DateInteval / $voyDay, 2);
                        $insurance_prac = Round($planInfo['INSURANCE'] * $sailInfo->DateInteval / $voyDay, 2);
                        $oap_prac = Round($planInfo['OAP'] * $sailInfo->DateInteval / $voyDay, 2);
                        $dunnage_prac = Round($planInfo['DUNNAGE'] * $sailInfo->DateInteval / $voyDay, 2);
                        $other_prac = Round($planInfo['OTHERS'] * $sailInfo->DateInteval / $voyDay, 2);

                        $pro_tt = Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] + $proInfo['Pro_AddIn'] - ($voyInfo['Cgo_Qtty'] * $voyInfo['Freight']*$voyInfo['Brokerage'])/100, 2);
                        $pro_daily_income = Round($pro_tt / $interval_pro, 2);
                        $pro_tt_expense = $pro_exp_fo + $pro_exp_do + $pro_exp_lo + $ss_pro + $proInfo['PDA_Prog'] + $ctm_pro + $insurance_pro + $oap_pro + $telcom_pro + $dunnage_pro + $other_pro;
                        $pro_daily_expense = Round($pro_tt_expense / $interval_pro, 2);
                        $pro_daily_profit = $pro_daily_income - $pro_daily_expense;
                        $pro_tt_profit = $pro_tt - $pro_tt_expense;
                        $prac_tt = $incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage;
                        $prac_daily_income = Round($prac_tt / $dateInteval, 2);
                        $prac_tt_expense = $prac_exp_fo + $prac_exp_do + $prac_exp_lo + $ss_prac + $incomeInfo->Pda + $ctm_prac + $insurance_prac + $oap_prac + $telcom_prac + $dunnage_prac + $other_prac;
                        $prac_daily_expense = Round($prac_tt_expense / $dateInteval, 2);
                        $prac_daily_profit = $prac_daily_income - $prac_daily_expense;
                        $prac_tt_profit = $prac_tt - $prac_tt_expense;
                    ?>

                    <div class="col-md-8" style="padding-left:0">
                        <div class="panel panel-default no-padding">
                            <div class="panel-heading">
                                <h5 class="panel-title">2. Voyage Qantitative Analysis</h5>
                            </div>
                            <div class="panel-body no-padding">
                                <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr class="black br-hblue">
                                            <td class="center" rowspan="2">Distance<br>[Mile]</td>
                                            <td class="center" rowspan="2">Speed<br>[Kn]</td>
                                            <td rowspan="2"></td>
                                            <td class="center" colspan="4">Round Trip</td>
                                            <td class="center" colspan="3">Bunker Consumption</td>
                                        </tr>
                                        <tr>
                                            <td class="center">Sail Day</td>
                                            <td class="center">LD Day</td>
                                            <td class="center">Idle Day</td>
                                            <td class="center">Duration</td>
                                            <td class="center">FO [MT]</td>
                                            <td class="center">DO [MT]</td>
                                            <td class="center">LO [Kg]</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr >
                                            <td class="center" rowspan="3">{{$proInfo['PracDistance']}}</td>
                                            <td class="center" rowspan="3">{{$proInfo['Speed']}}</td>
                                            <td class="center">Programe</td>
                                            <td class="center">{{$sail_day}}</td>
                                            <td class="center">{{$proInfo['LD_Day']}}</td>
                                            <td class="center">{{$proInfo['Idle_Day']}}</td>
                                            <td class="center">{{$sail_day + $proInfo['LD_Day'] + $proInfo['Idle_Day']}}</td>
                                            <td class="center">{{$pro_fo}}</td>
                                            <td class="center">{{$pro_do}}</td>
                                            <td class="center">{{$pro_lo}}</td>
                                        </tr>
                                        <tr >
                                            <td class="center">Practice</td>
                                            <td class="center">{{$sail_time}}</td>
                                            <td class="center">{{$ld_time}}</td>
                                            <td class="center">{{$idle_time}}</td>
                                            <td class="center">{{$sailInfo->DateInteval}}</td>
                                            <td class="center">{{$proInfo['Prac_Fo']}}</td>
                                            <td class="center">{{$proInfo['Prac_Do']}}</td>
                                            <td class="center">{{$proInfo['Prac_Lo']}}</td>
                                        </tr>
                                        <tr >
                                            <td class="center">Difference</td>
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
                                <h5 class="panel-title">3. Income</h5>
                            </div>
                            <div class="panel-body no-padding">
                                <table class="arc-std-table table table-bordered table-hover">
                                    <thead>
                                        <tr class="black br-hblue">
                                            <td class="center">Cp [MT]</td>
                                            <td class="center">B/L [MT]</td>
                                            <td class="center">Freight</td>
                                            <td></td>
                                            <td class="center">Freight [$]</td>
                                            <td class="center">Add In [$]</td>
                                            <td class="center">Commi [$]</td>
                                            <td class="center">Income [$]</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="center" rowspan="3">{{$voyInfo['Cgo_Qtty']}}</td>
                                            <td class="center" rowspan="3">{{$voyInfo['B_L']}}</td>
                                            <td class="center" rowspan="3">{{$voyInfo['Freight']}}</td>
                                            <td class="center">Programe</td>
                                            <td class="center">{{Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'], 2)}}</td>
                                            <td class="center">{{$proInfo['Pro_AddIn']}}</td>
                                            <td class="center">{{Round(($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] * $voyInfo['Brokerage'])/100, 2)}}</td>
                                            <td class="center">{{$pro_tt}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center">Practice</td>
                                            <td class="center">{{$incomeInfo->Frt}}</td>
                                            <td class="center">{{$incomeInfo->Add_In}}</td>
                                            <td class="center">{{$incomeInfo->Brokerage}}</td>
                                            <td class="center">{{$prac_tt}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center">Difference</td>
                                            <td class="center">{{Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'], 2) - $incomeInfo->Frt}}</td>
                                            <td class="center">{{$proInfo['Pro_AddIn'] - $incomeInfo->Add_In}}</td>
                                            <td class="center">{{Round(($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] * $voyInfo['Brokerage'])/100, 2) - $incomeInfo->Brokerage}}</td>
                                            <td class="center">{{Round($voyInfo['Cgo_Qtty'] * $voyInfo['Freight'] + $proInfo['Pro_AddIn'] - ($voyInfo['Cgo_Qtty'] * $voyInfo['Freight']*$voyInfo['Brokerage'])/100, 2) - ($incomeInfo->Frt + $incomeInfo->Add_In - $incomeInfo->Brokerage)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default col-md-4 no-padding">
                        <div class="panel-heading">
                            <h5 class="panel-title">5. Voyage Analysis</h5>
                        </div>
                        <div class="panel-body">
                            <h5 style="margin-top: 0">Daily</h5>
                            <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="black br-hblue">
                                        <td></td>
                                        <td class="center">Income[$]</td>
                                        <td class="center">Expense[$]</td>
                                        <td class="center">Profit[$]</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="center">Programe</td>
                                        <td class="center">{{$pro_daily_income}}</td>
                                        <td class="center">{{$pro_daily_expense}}</td>
                                        <td class="center">{{$pro_daily_profit}}</td>
                                    </tr>
                                    <tr >
                                        <td class="center">Practice</td>
                                        <td class="center">{{$prac_daily_income}}</td>
                                        <td class="center">{{$prac_daily_expense}}</td>
                                        <td class="center">{{$prac_daily_profit}}</td>
                                    </tr>
                                    <tr >
                                        <td class="center">Difference</td>
                                        <td class="center">{{$pro_daily_income - $prac_daily_income}}</td>
                                        <td class="center">{{$pro_daily_expense - $prac_daily_expense}}</td>
                                        <td class="center">{{$pro_daily_profit - $prac_daily_profit}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5>全部</h5>
                            <table class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="black br-hblue">
                                        <td></td>
                                        <td class="center">Income[$]</td>
                                        <td class="center">Expense[$]</td>
                                        <td class="center">Profit[$]</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="center">Programe</td>
                                        <td class="center">{{$pro_tt}}</td>
                                        <td class="center">{{$pro_tt_expense}}</td>
                                        <td class="center">{{$pro_tt_profit}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">Practice</td>
                                        <td class="center">{{$prac_tt}}</td>
                                        <td class="center">{{$prac_tt_expense}}</td>
                                        <td class="center">{{$prac_tt_profit}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">Difference</td>
                                        <td class="center">{{$pro_tt - $prac_tt}}</td>
                                        <td class="center">{{$pro_tt_expense - $prac_tt_expense}}</td>
                                        <td class="center">{{$pro_tt_profit - $prac_tt_profit}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">4. Expense</h5>
                        </div>
                        <div class="panel-body no-padding">
                            <table id="" class="arc-std-table table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="black br-hblue">
                                        <td></td>
                                        <td class="center">FO [$]</td>
                                        <td class="center">DO [$]</td>
                                        <td class="center">LO [$]</td>
                                        <td class="center">PDA [$]</td>
                                        <td class="center">SS [$]</td>
                                        <td class="center">CTM [$]</td>
                                        <td class="center">TELCOM [$]</td>
                                        <td class="center">INSURCE [$]</td>
                                        <td class="center">OAP [$]</td>
                                        <td class="center">DUNAG [$]</td>
                                        <td class="center">OTHERS [$]</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="center">Programe</td>
                                        <td class="center">{{$pro_exp_fo}}</td>
                                        <td class="center">{{$pro_exp_do}}</td>
                                        <td class="center">{{$pro_exp_lo}}</td>
                                        <td class="center">{{$proInfo['PDA_Prog']}}</td>
                                        <td class="center">{{$ss_pro}}</td>
                                        <td class="center">{{$ctm_pro}}</td>
                                        <td class="center">{{$telcom_pro}}</td>
                                        <td class="center">{{$insurance_pro}}</td>
                                        <td class="center">{{$oap_pro}}</td>
                                        <td class="center">{{$dunnage_pro}}</td>
                                        <td class="center">{{$other_pro}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">Practice</td>
                                        <td class="center">{{$prac_exp_fo}}</td>
                                        <td class="center">{{$prac_exp_do}}</td>
                                        <td class="center">{{$prac_exp_lo}}</td>
                                        <td class="center">{{$incomeInfo->Pda}}</td>
                                        <td class="center">{{$ss_prac}}</td>
                                        <td class="center">{{$ctm_prac}}</td>
                                        <td class="center">{{$telcom_prac}}</td>
                                        <td class="center">{{$insurance_prac}}</td>
                                        <td class="center">{{$oap_prac}}</td>
                                        <td class="center">{{$dunnage_prac}}</td>
                                        <td class="center">{{$other_prac}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center">Difference</td>
                                        <td class="center">{{$pro_exp_fo - $prac_exp_fo}}</td>
                                        <td class="center">{{$pro_exp_do - $prac_exp_do}}</td>
                                        <td class="center">{{$pro_exp_lo - $prac_exp_lo}}</td>
                                        <td class="center">{{$proInfo['PDA_Prog'] - $incomeInfo->Pda}}</td>
                                        <td class="center">{{$ss_pro - $ss_prac}}</td>
                                        <td class="center">{{$ctm_pro - $ctm_prac}}</td>
                                        <td class="center">{{$telcom_pro - $telcom_prac}}</td>
                                        <td class="center">{{$insurance_pro - $insurance_prac}}</td>
                                        <td class="center">{{$oap_pro - $oap_prac}}</td>
                                        <td class="center">{{$dunnage_pro - $dunnage_prac}}</td>
                                        <td class="center">{{$other_pro - $other_prac}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
