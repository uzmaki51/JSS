<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<form role="form" method="POST"
      action="{{url('shipManage/saveShipMahcineryData')}}" enctype="multipart/form-data" id="validation-form">
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
                        <td class="width-30 custom-td-label1 center" rowspan="8">
                            {{ transShipManager('Machinery.ME TYPE') }}
                        </td>
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
                    <tr>
                        <td class="width-35 custom-td-label1 center" rowspan="2">
                            {{ transShipManager('Machinery.AE TYPE') }}
                        </td>
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
                    <tr>
                        <td class="width-3 custom-td-label1 center" rowspan="3">
                            {{ transShipManager('Machinery.Anchorage Engine') }}
                        </td>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Machinery.No/Type Engine') }}
                        </td>
                        <td>
                            <input type="text" name="AnchorageType" class="form-control" style="width:100%" value="@if(isset($shipInfo['AnchorageType'])){{$shipInfo['AnchorageType']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Machinery.Power') }}
                        </td>
                        <td>
                            <input type="text" name="AnchoragePower" class="form-control" style="width:100%" value="@if(isset($shipInfo['AnchoragePower'])){{$shipInfo['AnchoragePower']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Machinery.rpm/volte') }}
                        </td>
                        <td>
                            <input type="text" name="AnchorageRPM" class="form-control" style="width:100%" value="@if(isset($shipInfo['AnchorageRPM'])){{$shipInfo['AnchorageRPM']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="width-3 custom-td-label1 center" rowspan="5">
                            {{ transShipManager('Machinery.Boiler') }}
                        </td>
                        <td class="custom-td-label1 center">{{ transShipManager('Machinery.Boiler Type & Number') }}</td>
                        <td>
                            <input type="text" name="Boiler" class="form-control" style="width:100%" value="@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Machinery.Boiler * Pressure * HeatingSurface') }}
                        </td>
                        <td>
                            <input type="text" name="BoilerPressure" class="form-control" style="width:100%" value="@if(isset($shipInfo['BoilerPressure'])){{$shipInfo['BoilerPressure']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Machinery.Manufacturer') }}
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
                            <table class="table table-bordered" style="margin-bottom: 0">
                                <tbody>
                                    <tr style="background-color: #FCFAE3;height: 35px">
                                        <td></td>
                                        <td class="custom-td-label1 center" style="width:145px">{{ transShipManager('Machinery.Fuel') }}\{{ transShipManager('Machinery.Cond') }}</td>
                                        <td class="custom-td-label1">{{ transShipManager('Machinery.Sail') }}</td>
                                        <td class="custom-td-label1">{{ transShipManager('Machinery.L/D') }}</td>
                                        <td class="custom-td-label1">{{ transShipManager('Machinery.Idle') }}</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3" class="center custom-td-label1" style="width:100px">{{ transShipManager('Machinery.Summer') }}</td>
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
                                    <tr>
                                        <td rowspan="3" class="center custom-td-label1">{{ transShipManager('Machinery.Winter') }}</td>
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
                <div class="space-6"></div>
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" style="border-top: 1px solid #c5d0dc">
                                {{ transShipManager('Machinery.FuelTank') }}
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane in active">
                            <table class="table table-bordered" style="margin-bottom: 0">
                                <tbody>
                                <tr style="height: 35px">
                                    <td class="custom-td-label1">No</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.Kind') }}</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.Capacity') }}</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.Descript') }}</td>
                                </tr>
                                <tr style="background-color: #FCFAE3;height: 35px">
                                    <td>1</td>
                                    <td class="custom-td-label1" style="width:145px">{{ transShipManager('Machinery.FOT') }}</td>
                                    <td><input type="text" name="FO_tank_capacity" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FO_tank_capacity'])){{$shipInfo['FO_tank_capacity']}}@endif"></td>
                                    <td><input type="text" name="FO_tank_desc" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FO_tank_desc'])){{$shipInfo['FO_tank_desc']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.DOT') }}</td>
                                    <td><input type="text" name="DO_tank_capacity" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DO_tank_capacity'])){{$shipInfo['DO_tank_capacity']}}@endif"></td>
                                    <td><input type="text" name="DO_tank_desc" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['DO_tank_desc'])){{$shipInfo['DO_tank_desc']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.BWT') }}</td>
                                    <td><input type="text" name="BW_tank_capacity" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['BW_tank_capacity'])){{$shipInfo['BW_tank_capacity']}}@endif"></td>
                                    <td><input type="text" name="BW_tank_desc" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['BW_tank_desc'])){{$shipInfo['BW_tank_desc']}}@endif"></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="custom-td-label1">{{ transShipManager('Machinery.FWT') }}</td>
                                    <td><input type="text" name="FW_tank_capacity" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FW_tank_capacity'])){{$shipInfo['FW_tank_capacity']}}@endif"></td>
                                    <td><input type="text" name="FW_tank_desc" class="form-control" style="width:100%;text-align: center" value="@if(isset($shipInfo['FW_tank_desc'])){{$shipInfo['FW_tank_desc']}}@endif"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$isHolder)
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 col-md-offset-6" >
                <button type="submit" id="btnRegister" class="btn btn-sm btn-inverse" style="width: 80px">
                    <i class="icon-save"></i>登记
                </button>
            </div>
        </div>
    </div>
    @endif
    <div class="space-4"></div>

</form>