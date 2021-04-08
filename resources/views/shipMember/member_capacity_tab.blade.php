<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <input class="hidden" name="_token" value="{{csrf_token()}}">
        <input class="hidden" name="memberId" value="{{$memberId}}">
        <div class="col-md-12">
            <div class="space-4"></div>
            <table class="table table-bordered">
                <tbody>
                    <tr style="background-color: #e4dfec; height:30px;">
                        <td class="center td-header style-header" style="width:3%">No</td>
                        <td class="center td-header style-header" style="width:25%">Type of certificates</td>
                        <td class="center td-header style-header" style="width:25%">Capacity</td>
                        <td class="center td-header style-header" style="width:15%">Certificates No</td>
                        <td class="center td-header style-header" style="width:10%">Issue Date</td>
                        <td class="center td-header style-header" style="width:10%">Expire Date</td>
                        <td class="center td-header style-header" style="">Issued by</td>
                    </tr>
                    <tr>
                        <td class="center" style="background-color: #d0e6dd">
                            1
                        </td>
                        <td class="no-padding style-bold-italic" style="background-color: #d0e6dd">
                            COC: Certificate of Competency (for Officerts only)
                        </td>
                        <td class="no-padding">
                            <?php $cap = "";
                            $capacity_id = 0; ?>
                            @foreach ($capacityList as $type)
                                @if ($type->id == $capacity['CapacityID'])
                                <?php $cap = $type->Capacity_En; 
                                $capacity_id = $type->id;
                                ?>
                                @endif
                            @endforeach
                            <div class="dynamic-select-wrapper">
                                <div class="dynamic-select" style="color:#12539b">
                                    <input type="hidden"  name="CapacityID" value="{{$capacity_id}}"/>
                                    <div class="dynamic-select__trigger"><span class="dynamic-select-span-capacity">{{$cap}}</span>
                                        <div class="arrow"></div>
                                    </div>
                                    <div class="dynamic-options">
                                        <div class="dynamic-options-scroll">
                                            @foreach ($capacityList as $type)
                                                @if ($type->id == $capacity['CapacityID'])
                                                <span class="dynamic-option selected" data-value="{{$type->id}}">{{$type->Capacity_En}}</span>
                                                @else
                                                <span class="dynamic-option" data-value="{{$type->id}}">{{$type->Capacity_En}}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span class="edit-list-btn" id="edit-list-btn" onclick="javascript:openCapacityList('capacity')">
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="ItemNo" value="{{$capacity['ItemNo']}}" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COC_IssuedDate"
                                        value="{{$capacity['COC_IssuedDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COC_ExpiryDate"
                                        value="{{$capacity['COC_ExpiryDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COC_Remarks" value="{{$capacity['COC_Remarks']}}" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="background-color: #d0e6dd">
                            2
                        </td>
                        <td class="no-padding style-bold-italic" style="background-color: #d0e6dd">
                            COE: Certificate of Endorsement (by third Flag only)
                        </td>
                        <td class="no-padding">
                            <?php $cap = "";
                            $capacity_id = 0;
                             ?>
                            @foreach ($capacityList as $type)
                                @if ($type->id == $capacity['COEId'])
                                <?php $cap = $type->Capacity_En; 
                                $capacity_id = $type->id;
                                ?>
                                @endif
                            @endforeach
                            <div class="dynamic-select-wrapper">
                                <div class="dynamic-select" style="color:#12539b">
                                    <input type="hidden"  name="COEId" value="{{$capacity_id}}"/>
                                    <div class="dynamic-select__trigger"><span class="dynamic-select-span-capacity">{{$cap}}</span>
                                        <div class="arrow"></div>
                                    </div>
                                    <div class="dynamic-options">
                                        <div class="dynamic-options-scroll">
                                            @foreach ($capacityList as $type)
                                                @if ($type->id == $capacity['COEId'])
                                                <span class="dynamic-option selected" data-value="{{$type->id}}">{{$type->Capacity_En}}</span>
                                                @else
                                                <span class="dynamic-option" data-value="{{$type->id}}">{{$type->Capacity_En}}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span class="edit-list-btn" id="edit-list-btn" onclick="javascript:openCapacityList('capacity')">
                                                <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--select class="form-control" name="COEId">
                                <option value="0">&nbsp;</option>
                                @foreach($capacityList as $type)
                                <option value="{{$type['id']}}" @if($capacity['COEId'] == $type['id'])) selected @endif>{{$type['Capacity_En']}}</option>
                                @endforeach
                            </select-->
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COENo" value="{{$capacity['COENo']}}" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COE_IssuedDate"
                                        value="{{$capacity['COE_IssuedDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COE_ExpiryDate"
                                        value="{{$capacity['COE_ExpiryDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_Remarks" value="{{$capacity['COE_Remarks']}}" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="background-color: #d0e6dd">
                            3
                        </td>
                        <td class="no-padding style-bold-italic" style="background-color: #d0e6dd" colspan="2">
                            GOC: GMDSS general operator (for Officerts only)
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="GMDSS_NO" value="{{$capacity['GMDSS_NO']}}" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="GMD_IssuedDate"
                                        value="{{$capacity['GMD_IssuedDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="GMD_ExpiryDate"
                                        value="{{$capacity['GMD_ExpiryDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="GMD_Remarks" value="{{$capacity['GMD_Remarks']}}" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="background-color: #d0e6dd">
                            4
                        </td>
                        <td class="no-padding style-bold-italic" style="background-color: #d0e6dd" colspan="2">
                            GOC Endorsement (by third Flag only)
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_GOCNo" value="{{$capacity['COE_GOCNo']}}" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COE_GOC_IssuedDate"
                                        value="{{$capacity['COE_GOC_IssuedDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="COE_GOC_ExpiryDate"
                                        value="{{$capacity['COE_GOC_ExpiryDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="COE_GOC_Remarks" value="{{$capacity['COE_GOC_Remarks']}}" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                    <tr>
                        <td class="center" style="background-color: #d0e6dd">
                            5
                        </td>
                        <td class="no-padding" style="background-color: #d0e6dd" colspan="2">
                            <select class="form-control style-bold-italic" name="WatchID" style="padding:0px!important;background-color:#d0e6dd!important;margin:-4px!important;">
                                <option value="0" @if($capacity['WatchID'] == 0)) selected @endif>Navigation watch rating</option>
                                <option value="1" @if($capacity['WatchID'] == 1)) selected @endif>Engineroom watch rating</option>
                            </select>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="WatchNo" value="{{$capacity['WatchNo']}}" style="width: 100%;text-align: center">
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="Watch_IssuedDate"
                                        value="{{$capacity['Watch_IssuedDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <div class="input-group">
                                <input class="form-control date-picker" style="width: 100%;text-align: center"
                                        type="text" data-date-format="yyyy/mm/dd"
                                        name="Watch_ExpiryDate"
                                        value="{{$capacity['Watch_ExpiryDate']}}">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                            </div>
                        </td>
                        <td class="no-padding">
                            <input type="text" class="form-control" name="Watch_Remarks" value="{{$capacity['Watch_Remarks']}}" style="width: 100%;text-align: center">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="space-2"></div>
        </div>
    </div>
</div>