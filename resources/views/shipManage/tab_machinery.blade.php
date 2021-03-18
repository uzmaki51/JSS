<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>

<div class="row">
    <div class="col-md-12">
        <div class="space-6"></div>
        <div class="col-md-4">
        <div class="col-md-12">
            <table class="table table-bordered general">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.No/Type Engine') }}</td>
                    <td class="custom-td-report-text">
                        <textarea name="No_TypeOfEngine" class="form-control" rows="2">{{ isset($shipInfo['No_TypeOfEngine']) ? $shipInfo['No_TypeOfEngine'] : '' }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Cylinder Bore/Stroke') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="Cylinder_Bore_Stroke" class="form-control" style="width:100%" value="@if(isset($shipInfo['Cylinder_Bore_Stroke'])){{$shipInfo['Cylinder_Bore_Stroke']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Power') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="Power" class="form-control" style="width:100%" value="@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.rpm') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="rpm" class="form-control" style="width:100%" value="@if(isset($shipInfo['rpm'])){{$shipInfo['rpm']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Manufacturer') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="EngineManufacturer" class="form-control" style="width:100%" value="@if(isset($shipInfo['EngineManufacturer'])){{$shipInfo['EngineManufacturer']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.AddressEngMaker') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="AddressEngMaker" class="form-control" style="width:100%" value="@if(isset($shipInfo['AddressEngMaker'])){{$shipInfo['AddressEngMaker']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.EngineDate') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="EngineDate" class="form-control" style="width:100%" value="@if(isset($shipInfo['EngineDate'])){{$shipInfo['EngineDate']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Speed') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="Speed" class="form-control" style="width:100%" value="@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="space-6"></div>
            <table class="table table-bordered general">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.Generator Set') }}</td>
                    <td class="custom-td-report-text">
                        <textarea name="PrimeMover" class="form-control" style="width:100%">{{ isset($shipInfo['PrimeMover']) ? $shipInfo['PrimeMover'] : '' }}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.Output') }}
                    </td>
                    <td class="custom-td-report-text">
                        <textarea name="GeneratorOutput" class="form-control" style="width:100%">{{ isset($shipInfo['GeneratorOutput']) ? $shipInfo['GeneratorOutput'] : '' }}</textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="space-6"></div>
            <table class="table table-bordered general">
                <tbody>
                <tr>
                    <td class="custom-td-label1 center">{{ transShipManager('Machinery.Boiler Type & Number') }}</td>
                    <td class="custom-td-report-text">
                        <input type="text" name="Boiler" class="form-control" style="width:100%" value="@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1" style="text-align: left!important;">
                        {{ transShipManager('Machinery.Boiler * Pressure * HeatingSurface') }}<br>{{ transShipManager('Machinery.Boiler Heating') }}
                    </td>
                    <td class="custom-td-report-text">
                        <textarea name="BoilerPressure" class="form-control" style="width:100%">{{ isset($shipInfo['BoilerPressure']) ? $shipInfo['BoilerPressure'] : ''}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.BManufacturer') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="BoilerManufacturer" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerManufacturer'])){{$shipInfo['BoilerManufacturer']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.AddressBoilerMaker') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="AddressBoilerMaker" class="form-control" style="width:100%" value="@if(isset($shipInfo['AddressBoilerMaker'])){{$shipInfo['AddressBoilerMaker']}}@endif">
                    </td>
                </tr>
                <tr>
                    <td class="custom-td-label1 center">
                        {{ transShipManager('Machinery.BoilerDate') }}
                    </td>
                    <td class="custom-td-report-text">
                        <input type="text" name="BoilerDate" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerDate'])){{$shipInfo['BoilerDate']}}@endif">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
        <div class="col-md-4">
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
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Fuel') }}/{{ transShipManager('Machinery.Cond') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Sail') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Works_in_port') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Idle') }}</td>
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
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Fuel') }}/{{ transShipManager('Machinery.Cond') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Sail') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Works_in_port') }}</td>
                                    <td style="width:25%; text-align: center;">{{ transShipManager('Machinery.Idle') }}</td>
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