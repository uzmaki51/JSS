<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>

<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="space-6"></div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered general">
                            <tbody>
                            <tr>
                                <td class="no-padding custom-td-label1">
                                    {{ transShipManager('Hull.HullNo') }}
                                </td>
                                <td class="custom-td-report-text">
                                    <input type="text" name="HullNo" class="form-control" style="width:100%" value="@if(isset($shipInfo['HullNo'])){{$shipInfo['HullNo']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td class="no-padding custom-td-label1">
                                    {{ transShipManager('Hull.Decks') }}
                                </td>
                                <td class="custom-td-report-text">
                                    <input type="text" name="Decks" class="form-control" style="width:100%" value="@if(isset($shipInfo['Decks'])){{$shipInfo['Decks']}}@endif">
                                </td>
                            </tr>
                            <tr>
                                <td class="no-padding custom-td-label1">
                                    {{ transShipManager('Hull.Bulkheads') }}
                                </td>
                                <td class="custom-td-report-text">
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
                <div class="col-md-12">
                    <table class="table table-bordered general">
                        <tbody>
                        <tr>
                            <td class="no-padding custom-td-label1" rowspan="3">
                                <span class="text-pink">{{ transShipManager('Hull.Hold') }}: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ transShipManager('Hull.Number') }}</span>
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="NumberOfHolds" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('Hull.(Grain/Bale)㎥') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="CapacityOfHoldsG" class="form-control first-input" value="@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif">/<input type="text" name="CapacityOfHoldsB" class="form-control second-input" value="@if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('Hull.Size') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                    <textarea name="HoldsDetail" class="form-control" style="width:100%">@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif
                                    </textarea>
                            </td>
                        </tr>
                        <tr class="no-border">
                            <td></td>
                            <td colspan="2"><div class="space-2"></div></td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" rowspan="2">
                                <span class="text-pink">{{ transShipManager('Hull.HatchWays') }}</span>
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="NumberOfHatchways" class="form-control" style="width:100%" value="@if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('Hull.Size') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <textarea type="text" name="SizeOfHatchways" class="form-control" rows="2">{{ isset($shipInfo['SizeOfHatchways']) ? $shipInfo['SizeOfHatchways'] : '' }}</textarea>
                            </td>
                        </tr>
                        <tr class="no-border">
                            <td></td>
                            <td colspan="2"><div class="space-2"></div></td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" rowspan="2">
                                <span class="text-pink">{{ transShipManager('Hull.Containers') }}: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ transShipManager('Hull.On Deck') }}</span>
                            </td>
                            <td class="custom-td-report-text" style="width: 60%" colspan="3">
                                <input type="text" name="ContainerOnDeck" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerOnDeck'])){{$shipInfo['ContainerOnDeck']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('Hull.In Hold (TEU)') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="ContainerInHold" class="form-control" style="width:100%" value="@if(isset($shipInfo['ContainerInHold'])){{$shipInfo['ContainerInHold']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class=" custom-td-label1" colspan="2">
                                {{ transShipManager('Hull.Lifting Device') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="LiftingDevice" class="form-control" style="width:100%" value="@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('Hull.Max PERMISSBLE') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="TK_TOP" class="form-control width-30 first-input" value="@if(isset($shipInfo['TK_TOP'])){{$shipInfo['TK_TOP']}}@endif">/<input type="text" name="ON_DECK" class="form-control width-30 second-input" value="@if(isset($shipInfo['ON_DECK'])){{$shipInfo['ON_DECK']}}@endif">/<input type="text" name="H_COVER" class="form-control width-30 second-input" value="@if(isset($shipInfo['H_COVER'])){{$shipInfo['H_COVER']}}@endif">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="space-6"></div>
    <div class="col-md-4 border-div">
        <input type='hidden' value = '{{ isset($freeBoard['id']) ? $freeBoard['id'] : 0 }}' name='freeId'>
        <table class="table table-bordered general">
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
                    <label class="form-control-label custom-label" for="isNewShip">
                        NewShip
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="isNewShip" name="new_ship" class="v-middle" {{ isset($freeBoard['new_ship']) && $freeBoard['new_ship'] == 1 ? 'checked' : '' }}>
                </td>
                <td class="custom-td-report-text" colspan="2">
                    <span class="first-input text-center">FreeBoard(mm)</span>
                    <span class="second-input text-center">Load Line(mm)</span>
                </td>
            </tr>
            <tr>
                <td class="sub-title custom-td-label1"><span class="sub-title">Tropical</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="new_free_tropical" value="{{ isset($freeBoard['new_free_tropical']) ? $freeBoard['new_free_tropical'] : '' }}">|<input type="text" class="form-control second-input" name="new_load_tropical" value="{{ isset($freeBoard['new_load_tropical']) ? $freeBoard['new_load_tropical'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1"><span class="sub-title">Summer</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="new_free_summer" value="{{ isset($freeBoard['new_free_summer']) ? $freeBoard['new_free_summer'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1"><span class="sub-title">Winter</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="new_free_winter" value="{{ isset($freeBoard['new_free_winter']) ? $freeBoard['new_free_winter'] : '' }}">|<input type="text" class="form-control second-input" name="new_load_winter" value="{{ isset($freeBoard['new_load_winter']) ? $freeBoard['new_load_winter'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1"><span class="sub-title">WinterNorthAtlantic</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="new_free_winteratlantic" value="{{ isset($freeBoard['new_free_winteratlantic']) ? $freeBoard['new_free_winteratlantic'] : '' }}">|<input type="text" class="form-control second-input" name="new_load_winteratlantic" value="{{ isset($freeBoard['new_load_winteratlantic']) ? $freeBoard['new_load_winteratlantic'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1"><span class="sub-title">FW_Allowance</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="new_free_fw" value="{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}">
                </td>
            </tr>
        </table>
        <div class="space-6"></div>
        <table class="table table-bordered general">
            <tr>
                <td colspan="3" style="text-align: left!important; border: unset!important;">
                    <input type="checkbox" id="isTimber" name="timber" class="v-middle" {{ isset($freeBoard['timber']) && $freeBoard['timber'] == 1 ? 'checked' : '' }}>
                    <label class="form-control-label custom-label" for="isTimber">
                        <span class="text-pink" style="font-weight: bold;">Timber</span>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Tropical</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="timber_free_tropical" disabled value="{{ isset($freeBoard['timber_free_tropical']) ? $freeBoard['timber_free_tropical'] : '' }}">|<input type="text" class="form-control second-input" name="timber_load_tropical" disabled value="{{ isset($freeBoard['timber_load_tropical']) ? $freeBoard['timber_load_tropical'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Summer</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="timber_free_summer" disabled value="{{ isset($freeBoard['timber_free_summer']) ? $freeBoard['timber_free_summer'] : '' }}">|<input type="text" class="form-control second-input" name="timber_load_summer" disabled value="{{ isset($freeBoard['timber_load_summer']) ? $freeBoard['timber_load_summer'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">Winter</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="timber_free_winter" disabled value="{{ isset($freeBoard['timber_free_winter']) ? $freeBoard['timber_free_winter'] : '' }}">|<input type="text" class="form-control second-input" name="timber_load_winter" disabled value="{{ isset($freeBoard['timber_load_winter']) ? $freeBoard['timber_load_winter'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">WinterNorthAtlantic</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control first-input" name="timber_free_winteratlantic" disabled value="{{ isset($freeBoard['timber_free_winteratlantic']) ? $freeBoard['timber_free_winteratlantic'] : '' }}">|<input type="text" class="form-control second-input" name="timber_load_winteratlantic" disabled value="{{ isset($freeBoard['timber_load_winteratlantic']) ? $freeBoard['timber_load_winteratlantic'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1">FW_Allowance</td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" name="timber_free_fw" disabled value="{{ isset($freeBoard['timber_free_fw']) ? $freeBoard['timber_free_fw'] : '' }}">
                </td>
            </tr>
            <tr>
                <td class="no-padding custom-td-label1"><span class="sub-title">DeckLine</span></td>
                <td class="custom-td-report-text" colspan="2">
                    <input type="text" class="form-control" style="width: 88%; display: inline-block;border-bottom: 1px solid #333333!important;" name="deck_line_amount" value="{{ isset($freeBoard['deck_line_amount']) ? $freeBoard['deck_line_amount'] : '' }}">mm<br>
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