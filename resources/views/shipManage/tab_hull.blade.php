<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<form role="form" method="POST"
      action="{{url('shipManage/saveShipHullData')}}" enctype="multipart/form-data" id="validation-form">
    <div class="row">
        <div class="col-md-12">
            <div class="space-6"></div>
            <input type="hidden" name="shipId"
                   value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="_tabName" value="#hull">

            <div class="col-md-8 col-md-offset-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="font-weight: bold">
                        <tbody>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" colspan="2">
                                {{ transShipManager('General.ShipName') }}({{ trans("common.label.chinese") }})
                            </td>
                            <td class="custom-td-report-text" style="width: 80%" colspan="2">
                                <input type="text" name="shipName_Cn" class="form-control" placeholder="中文" style="width:100%" value="@if(isset($shipInfo['shipName_Cn'])){{$shipInfo['shipName_Cn']}}@endif">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <td style="text-align: center;">
                                    {{ transShipManager('Hull.HullNo') }}
                                </td>
                                <td>
                                    <input type="text" name="HullNo" class="form-control" style="width:100%" value="@if(isset($shipInfo['HullNo'])){{$shipInfo['HullNo']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    {{ transShipManager('Hull.Decks') }}
                                </td>
                                <td>
                                    <input type="text" name="Decks" class="form-control" style="width:100%" value="@if(isset($shipInfo['Decks'])){{$shipInfo['Decks']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    {{ transShipManager('Hull.Bulkheads') }}
                                </td>
                                <td>
                                    <input type="text" name="Bulkheads" class="form-control" style="width:100%" value="@if(isset($shipInfo['Bulkheads'])){{$shipInfo['Bulkheads']}}@endif">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="space-4"></div>
            <div class="col-md-8 col-md-offset-2">
                <table class="table table-striped table-bordered table-hover" style="border: 1px solid #ddd;background-color: #fafafa">
                    <tbody>
                    <tr>
                        <td class="custom-td-label1 center" rowspan="3">
                            {{ transShipManager('Hull.Hold') }}
                        </td>
                        <td class="center">{{ transShipManager('Hull.Number') }}</td>
                        <td colspan="3">
                            <input type="text" name="NumberOfHolds" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Hull.(Grain/Bale)㎥') }}
                        </td>
                        <td class="center">
                            <input type="text" name="CapacityOfHoldsG" class="form-control" style="width:100%" value="@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif">
                        </td>
                        <td style="width:10px">
                            <div class="col-md-6">/</div>
                        </td>
                        <td>
                            <input type="text" name="CapacityOfHoldsB" class="form-control" style="width:100%" value="@if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Hull.Details') }}
                        </td>
                        <td colspan="3">
                            <textarea name="HoldsDetail" class="form-control" style="width:100%">@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif
                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center" rowspan="2">
                            {{ transShipManager('Hull.HatchWays') }}
                        </td>
                        <td class="center">{{ transShipManager('Hull.Number') }}</td>
                        <td colspan="3">
                            <input type="text" name="NumberOfHatchways" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Hull.Size') }}
                        </td>
                        <td colspan="3">
                            <input type="text" name="SizeOfHatchways" class="form-control" style="width:100%" value="@if(isset($shipInfo['SizeOfHatchways'])){{$shipInfo['SizeOfHatchways']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1" rowspan="2">
                            {{ transShipManager('Hull.Containers') }}
                        </td>
                        <td class="center">{{ transShipManager('Hull.On Deck') }}</td>
                        <td colspan="3">
                            <input type="text" name="ContainerOnDeck" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerOnDeck'])){{$shipInfo['ContainerOnDeck']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center">
                            {{ transShipManager('Hull.In Hold (TEU)') }}
                        </td>
                        <td colspan="3">
                            <input type="text" name="ContainerInHold" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerInHold'])){{$shipInfo['ContainerInHold']}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-td-label1 center" colspan="2">
                            {{ transShipManager('Hull.Lifting Device') }}
                        </td>
                        <td colspan="3">
                            <input type="text" name="LiftingDevice" class="form-control" style="width:100%" value="@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif">
                        </td>
                    </tr>
                    </tbody>
                </table>
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