<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<style>
    .table tbody > tr > td {
        font-size: 12px!important;
    }
    .table tbody > tr > td {
        padding: 0!important;
    }
    .table tbody > tr > .custom-td-report-text, .table tbody > tr > .custom-td-dec-text {
        padding: 0!important;
    }
    .table .custom-td-label1 {
        width: 40%;
    }

    .form-control {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .chosen-single {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .input-group-addon {
        font-size: 12px!important;
        padding: 0 4px!important;
        border: unset!important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="space-6"></div>
        <input type="hidden" name="shipId"
               value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_tabName" value="#machiery">

        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.No/Type Engine') }}</td>
                    <td>
                        <input type="text" name="No_TypeOfEngine" class="form-control" style="width:100%" value="@if(isset($shipInfo['No_TypeOfEngine'])){{$shipInfo['No_TypeOfEngine']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Cylinder Bore/Stroke') }}
                    </td>
                    <td>
                        <input type="text" name="Cylinder_Bore_Stroke" class="form-control" style="width:100%" value="@if(isset($shipInfo['Cylinder_Bore_Stroke'])){{$shipInfo['Cylinder_Bore_Stroke']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Power') }}
                    </td>
                    <td>
                        <input type="text" name="Power" class="form-control" style="width:100%" value="@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.rpm') }}
                    </td>
                    <td>
                        <input type="text" name="rpm" class="form-control" style="width:100%" value="@if(isset($shipInfo['rpm'])){{$shipInfo['rpm']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Manufacturer') }}
                    </td>
                    <td>
                        <input type="text" name="EngineManufacturer" class="form-control" style="width:100%" value="@if(isset($shipInfo['EngineManufacturer'])){{$shipInfo['EngineManufacturer']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.AddressEngMaker') }}
                    </td>
                    <td>
                        <input type="text" name="AddressEngMaker" class="form-control" style="width:100%" value="@if(isset($shipInfo['AddressEngMaker'])){{$shipInfo['AddressEngMaker']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.EngineDate') }}
                    </td>
                    <td>
                        <input type="text" name="EngineDate" class="form-control" style="width:100%" value="@if(isset($shipInfo['EngineDate'])){{$shipInfo['EngineDate']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Speed') }}
                    </td>
                    <td>
                        <input type="text" name="Speed" class="form-control" style="width:100%" value="@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif">
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.Generator Set') }}</td>
                    <td>
                        <input type="text" name="PrimeMover" class="form-control" style="width:100%" value="@if(isset($shipInfo['PrimeMover'])){{$shipInfo['PrimeMover']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Output') }}
                    </td>
                    <td>
                        <input type="text" name="GeneratorOutput" class="form-control" style="width:100%" value="@if(isset($shipInfo['GeneratorOutput'])){{$shipInfo['GeneratorOutput']}}@endif">
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.Boiler Type & Number') }}</td>
                    <td>
                        <input type="text" name="Boiler" class="form-control" style="width:100%" value="@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Boiler * Pressure * HeatingSurface') }}<br>{{ transShipManager('Machinery.Boiler Heating') }}
                    </td>
                    <td>
                        <input type="text" name="BoilerPressure" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerPressure'])){{$shipInfo['BoilerPressure']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.BManufacturer') }}
                    </td>
                    <td>
                        <input type="text" name="BoilerManufacturer" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerManufacturer'])){{$shipInfo['BoilerManufacturer']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.AddressBoilerMaker') }}
                    </td>
                    <td>
                        <input type="text" name="AddressBoilerMaker" class="form-control" style="width:100%" value="@if(isset($shipInfo['AddressBoilerMaker'])){{$shipInfo['AddressBoilerMaker']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.BoilerDate') }}
                    </td>
                    <td>
                        <input type="text" name="BoilerDate" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerDate'])){{$shipInfo['BoilerDate']}}@endif">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" style="border-top: 1px solid #c5d0dc">
                            {{ transShipManager('Machinery.Fuel Consumption') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane in active">
                        <div style="margin: 10px;">
                            <h5>{{ transShipManager('Machinery.Summer') }}</h5>
                            <table class="table table-bordered">
                                <tbody>
                                <tr style="height: 21px">
                                    <td class="custom-td-label1 center" style="width:25%">{{ transShipManager('Machinery.Fuel') }}/{{ transShipManager('Machinery.Cond') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Sail') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Works_in_port') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Idle') }}</td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.FO') }}</td>
                                    <td><input type="text" name="FOSailCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOSailCons_S'])){{$shipInfo['FOSailCons_S']}}@endif"></td>
                                    <td><input type="text" name="FOL/DCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOL/DCons_S'])){{$shipInfo['FOL/DCons_S']}}@endif"></td>
                                    <td><input type="text" name="FOIdleCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOIdleCons_S'])){{$shipInfo['FOIdleCons_S']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.DO') }}</td>
                                    <td><input type="text" name="DOSailCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOSailCons_S'])){{$shipInfo['DOSailCons_S']}}@endif"></td>
                                    <td><input type="text" name="DOL/DCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOL/DCons_S'])){{$shipInfo['DOL/DCons_S']}}@endif"></td>
                                    <td><input type="text" name="DOIdleCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOIdleCons_S'])){{$shipInfo['DOIdleCons_S']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.LO') }}</td>
                                    <td><input type="text" name="LOSailCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOSailCons_S'])){{$shipInfo['LOSailCons_S']}}@endif"></td>
                                    <td><input type="text" name="LOL/DCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOL/DCons_S'])){{$shipInfo['LOL/DCons_S']}}@endif"></td>
                                    <td><input type="text" name="LOIdleCons_S" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOIdleCons_S'])){{$shipInfo['LOIdleCons_S']}}@endif"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="margin: 10px;">
                            <h5>{{ transShipManager('Machinery.Winter') }}</h5>
                            <table class="table table-bordered">
                                <tbody>
                                <tr style="height: 21px">
                                    <td class="custom-td-label1 center" style="width:25%">{{ transShipManager('Machinery.Fuel') }}/{{ transShipManager('Machinery.Cond') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Sail') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Works_in_port') }}</td>
                                    <td class="custom-td-label1" style="width:25%">{{ transShipManager('Machinery.Idle') }}</td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.FO') }}</td>
                                    <td><input type="text" name="FOSailCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOSailCons_W'])){{$shipInfo['FOSailCons_W']}}@endif"></td>
                                    <td><input type="text" name="FOL/DCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOL/DCons_W'])){{$shipInfo['FOL/DCons_W']}}@endif"></td>
                                    <td><input type="text" name="FOIdleCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FOIdleCons_W'])){{$shipInfo['FOIdleCons_W']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.DO') }}</td>
                                    <td><input type="text" name="DOSailCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOSailCons_W'])){{$shipInfo['DOSailCons_W']}}@endif"></td>
                                    <td><input type="text" name="DOL/DCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOL/DCons_W'])){{$shipInfo['DOL/DCons_W']}}@endif"></td>
                                    <td><input type="text" name="DOIdleCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DOIdleCons_W'])){{$shipInfo['DOIdleCons_W']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td class="center">{{ transShipManager('Machinery.LO') }}</td>
                                    <td><input type="text" name="LOSailCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOSailCons_W'])){{$shipInfo['LOSailCons_W']}}@endif"></td>
                                    <td><input type="text" name="LOL/DCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOL/DCons_W'])){{$shipInfo['LOL/DCons_W']}}@endif"></td>
                                    <td><input type="text" name="LOIdleCons_W" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['LOIdleCons_W'])){{$shipInfo['LOIdleCons_W']}}@endif"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-6"></div>
        </div>
    </div>
</div>