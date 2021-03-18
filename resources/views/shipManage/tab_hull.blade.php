<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
<style>
    .table tbody > tr > td {
        font-size: 12px!important;
        padding: 0px!important;
    }
    .table tbody > tr > .custom-td-label1 {
        padding: 0 4px!important;
    }
    .table tbody > tr > .custom-td-report-text, .table tbody > tr > .custom-td-dec-text {
        padding: 0!important;
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
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="space-6"></div>
                <input type="hidden" name="shipId"
                       value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="_tabName" value="#hull">

                <div class="col-md-8 col-md-offset-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                            <tr>
                                <td class="no-padding custom-td-label1" style="text-align: left;width:40%">
                                    {{ transShipManager('Hull.HullNo') }}
                                </td>
                                <td class="custom-td-report-text" style="width: 60%">
                                    <input type="text" name="HullNo" class="form-control" style="width:100%" value="@if(isset($shipInfo['HullNo'])){{$shipInfo['HullNo']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td class="no-padding custom-td-label1" style="text-align: left;width:40%">
                                    {{ transShipManager('Hull.Decks') }}
                                </td>
                                <td class="custom-td-report-text" style="width: 60%">
                                    <input type="text" name="Decks" class="form-control" style="width:100%" value="@if(isset($shipInfo['Decks'])){{$shipInfo['Decks']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td class="no-padding custom-td-label1" style="text-align: left;width:40%">
                                    {{ transShipManager('Hull.Bulkheads') }}
                                </td>
                                <td class="custom-td-report-text" style="width: 60%">
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
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" rowspan="3">
                                {{ transShipManager('Hull.Hold') }}
                            </td>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.Number') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="NumberOfHolds" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.(Grain/Bale)㎥') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="CapacityOfHoldsG" class="form-control" style="width:100%" value="@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif">
                            </td>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:10%">
                                <div class="col-md-6">/</div>
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="CapacityOfHoldsB" class="form-control" style="width:100%" value="@if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.Details') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                    <textarea name="HoldsDetail" class="form-control" style="width:100%">@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif
                                    </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" rowspan="2">
                                {{ transShipManager('Hull.HatchWays') }}
                            </td>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.Number') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="NumberOfHatchways" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.Size') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="SizeOfHatchways" class="form-control" style="width:100%" value="@if(isset($shipInfo['SizeOfHatchways'])){{$shipInfo['SizeOfHatchways']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" rowspan="2">
                                {{ transShipManager('Hull.Containers') }}
                            </td>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.On Deck') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="ContainerOnDeck" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerOnDeck'])){{$shipInfo['ContainerOnDeck']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%">
                                {{ transShipManager('Hull.In Hold (TEU)') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="ContainerInHold" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerInHold'])){{$shipInfo['ContainerInHold']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-report-text" style="width: 40%" colspan="2">
                                {{ transShipManager('Hull.Lifting Device') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="LiftingDevice" class="form-control" style="width:100%" value="@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="space-6"></div>
        <input type='hidden' value = '{{ isset($freeBoard['id']) ? $freeBoard['id'] : 0 }}' name='freeId'>
        <table class="table table-bordered">
            <tr>
                <td class="no-padding custom-td-label1">
                    Type of Ship
                </td>
                <td colspan="2" class="custom-td-report-text">
                    <select class="form-control" name="ship_type">
                        @foreach(g_enum('ShipTypeData') as $key => $item)
                            <option value="{{ $key }}" {{ isset($freeBoard['ship_type']) && $freeBoard['ship_type'] == $key ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">
                    <label class="form-control-label" for="isNewShip">
                        NewShip
                    </label>
                    <input type="checkbox" id="isNewShip" name="new_ship" class="v-middle" {{ isset($freeBoard['new_ship']) && $freeBoard['new_ship'] == 1 ? 'checked' : '' }}>
                </td>
                <td class="custom-td-report-text" style="height: 21px;">
                    <span>FreeBoard(mm)</span>
                </td>
                <td class="custom-td-report-text">
                    <span>Load Line(mm)</span>
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Tropical</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_free_tropical" value="{{ isset($freeBoard['new_free_tropical']) ? $freeBoard['new_free_tropical'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_load_tropical" value="{{ isset($freeBoard['new_load_tropical']) ? $freeBoard['new_load_tropical'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Summer</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="new_free_summer" value="{{ isset($freeBoard['new_free_summer']) ? $freeBoard['new_free_summer'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Winter</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_free_winter" value="{{ isset($freeBoard['new_free_winter']) ? $freeBoard['new_free_winter'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_load_winter" value="{{ isset($freeBoard['new_load_winter']) ? $freeBoard['new_load_winter'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">WinterNorthAtlantic</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_free_winteratlantic" value="{{ isset($freeBoard['new_free_winteratlantic']) ? $freeBoard['new_free_winteratlantic'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="new_load_winteratlantic" value="{{ isset($freeBoard['new_load_winteratlantic']) ? $freeBoard['new_load_winteratlantic'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">FW_Allowance</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="new_free_fw" value="{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}">
                </td>
            </tr>
        </table>
        <div class="space-6"></div>
        <table class="table table-bordered">
            <tr>
                <td colspan="3" style="text-align: left!important;">
                    <input type="checkbox" id="isTimber" name="timber" class="v-middle" {{ isset($freeBoard['timber']) && $freeBoard['timber'] == 1 ? 'checked' : '' }}>
                    <label class="form-control-label" for="isTimber">
                        Timber
                    </label>
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Tropical</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_free_tropical" disabled value="{{ isset($freeBoard['timber_free_tropical']) ? $freeBoard['timber_free_tropical'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_load_tropical" disabled value="{{ isset($freeBoard['timber_load_tropical']) ? $freeBoard['timber_load_tropical'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Summer</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="timber_free_summer" disabled value="{{ isset($freeBoard['timber_free_summer']) ? $freeBoard['timber_free_summer'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Winter</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_free_winter" disabled value="{{ isset($freeBoard['timber_free_winter']) ? $freeBoard['timber_free_winter'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_load_winter" disabled value="{{ isset($freeBoard['timber_load_winter']) ? $freeBoard['timber_load_winter'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">WinterNorthAtlantic</td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_free_winteratlantic" disabled value="{{ isset($freeBoard['timber_free_winteratlantic']) ? $freeBoard['timber_free_winteratlantic'] : '' }}">
                </td>
                <td class="custom-td-report-text">
                    <input type="text" class="form-control" name="timber_load_winteratlantic" disabled value="{{ isset($freeBoard['timber_load_winteratlantic']) ? $freeBoard['timber_load_winteratlantic'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">FW_Allowance</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="timber_free_fw" disabled value="{{ isset($freeBoard['timber_free_fw']) ? $freeBoard['timber_free_fw'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">DeckLine</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" style="width: 88%; display: inline-block;" name="deck_line_amount" value="{{ isset($freeBoard['deck_line_amount']) ? $freeBoard['deck_line_amount'] : '' }}">mm<br>
                    <textarea class="form-control" name="deck_line_content">{{ isset($freeBoard['deck_line_content']) ? $freeBoard['deck_line_content'] : '' }}</textarea>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php
    $isTimber = isset($freeBoard['timber']) && $freeBoard['timber'] == 1 ? true : false;
?>
<div class="space-4"></div>

<script>
    var isTimber = '{{ $isTimber }}';
    $(function() {
        if(isTimber == true) {
            $('[name^=timber]').removeAttr('disabled');
        } else {
            $('[name^=timber]').attr('disabled', 'disabled');
            $('[name=timber]').removeAttr('disabled');
        }

    })
    $('[name=timber]').on('change', function() {
        if($(this).prop('checked') == true) {
            $('[name^=timber]').removeAttr('disabled');
        } else {
            $('[name^=timber]').attr('disabled', 'disabled');
            $(this).removeAttr('disabled');
        }

    });
</script>