<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered general" style="font-weight: bold">
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered general" style="font-weight: bold">
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--div class="col-md-12">
            <input class="hidden" name="_token" value="{{csrf_token()}}">
            <input class="hidden" name="memberId" value="{{$info['id']}}">

            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td style="width: 10%">{{transShipMember("RegisterData.ShipName(Sign On)")}}</td>
                            <td style="width: 15%">
                                <select name="ShipId" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($shipList as $ship)
                                        <option value="{{$ship['RegNo']}}" @if($info['ShipId'] == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center" style="width: 10%">{{transShipMember("RegisterData.ShipName(Sign On)")}}</td>
                            <td style="width: 15%">
                                <select name="Duty" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($posList as $pos)
                                        <option value="{{$pos['id']}}" @if($info['Duty'] == $pos['id'])) selected @endif>{{$pos['Duty']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center" style="width: 10%">{{transShipMember("RegisterData.Sign On/Off")}}</td>
                            <td style="width: 15%">
								<div style="float:left; width:40%">
									<select name="sign_on_off" class="chosen-select" style="float: left; width:80px; height:34px">
										<option value="上船" @if($info['sign_on_off'] == '上船') selected @endif>上船</option>
										<option value="下船" @if($info['sign_on_off'] == '下船') selected @endif>下船</option>
									</select>
								</div>
                            </td>
                            <td class="center" style="width: 10%">{{transShipMember("RegisterData.Engaged On")}}</td>
                            <td style="width: 15%">
                                <div class="input-group" style="width:200px;float: left;">
                                    <input class="form-control date-picker" name="DateOnboard" type="text" data-date-format="yyyy-mm-dd"
                                           value="{{$info['DateOnboard']}}">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px">{{transShipMember("RegisterData.ShipName(Saefarer`s passport)")}}</td>
                            <td>
                                <select name="ShipID_Book" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($shipList as $ship)
                                        <option value="{{$ship['RegNo']}}" @if($info['ShipID_Book'] == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center" style="width:100px">{{transShipMember("RegisterData.ShipName(Saefarer`s passport)")}}</td>
                            <td>
                                <select name="DutyID_Book" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($posList as $pos)
                                        <option value="{{$pos['id']}}" @if($info['DutyID_Book'] == $pos['id'])) selected @endif>{{$pos['Duty']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center">{{transShipMember("RegisterData.Issue")}}</td>
                            <td>
                                <div class="input-group" style="width:200px;float: left">
                                    <input class="form-control date-picker" name="IssuedDate" type="text" data-date-format="yyyy-mm-dd"
                                           value="{{$info['IssuedDate']}}">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            
                                </div>
                            </td>
                            <td class="center">{{transShipMember("RegisterData.Expiry Date")}}</td>
                            <td>
                                <div class="input-group" style="width:200px;float: left">
                                    <input class="form-control date-picker" name="ExpiryDate" type="text" data-date-format="yyyy-mm-dd"
                                           value="{{$info['ExpiryDate']}}">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px">{{transShipMember("RegisterData.ShipName(structure)")}}</td>
                            <td>
                                <select name="ShipID_organization" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($ksList as $ship)
                                        <option value="{{$ship['id']}}" @if($info['ShipID_organization'] == $ship['id'])) selected @endif>{{$ship['name']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center">{{transShipMember("RegisterData.Duty(structure)")}}</td>
                            <td>
                                <select name="pos" class="form-control chosen-select">
                                    <option value="">&nbsp;</option>
                                    @foreach($posList as $pos)
                                        <option value="{{$pos['id']}}" @if($info['pos'] == $pos['id'])) selected @endif>{{$pos['Duty']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center">{{transShipMember("RegisterData.Copy")}}</td>
                            <td>
                                @if(!empty($info['scanPath']))
                                    <?php   $tmp = explode('.', $info['scanPath']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember('captions.marinCertCopy').$ext; ?>
                                    <a href="/fileDownload?type=crewCard&path={{$info['scanPath']}}&filename={{$filename}}"  class="hide-option" title="{{$filename}}" style="float: left;padding-top:6px">
                                        <i class="icon-file bigger-140"></i>
                                    </a>
                                @endif
                                <div class="col-md-8"><input type="file" class="input-file" name="crewCard"/></div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{transShipMember("RegisterData.Remark")}}</td>
                            <td colspan="5">
                                <input type="text" class="form-control" name="Remarks" value="{{$info['Remarks']}}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <div class="space-2"></div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td colspan="10" style="padding-top: 5px;padding-bottom: 2px">
                                <h5 style="float: left">{{transShipMember("RegisterData.BoardingCareer")}}</h5>
                                <a class="btn btn-sm btn-primary" style="float: right; width :80px" href="javascript:newrow()"><i class="icon-plus-sign-alt"></i>添加</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="center" style="width:10%">{{transShipMember("RegisterData.From")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("RegisterData.To")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("RegisterData.ShipName(Sign On)")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("RegisterData.Duty(Sign On)")}}</td>
                            <td class="center" style="width:15%">{{transShipMember("RegisterData.ShipType")}}</td>
                            <td class="center" style="width:5%">{{transShipMember("RegisterData.GT")}}</td>
                            <td class="center" style="width:5%">{{transShipMember("RegisterData.Power")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("RegisterData.Sail Area")}}</td>
                            <td class="center">{{transShipMember("RegisterData.Remark")}}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody id="history_table">
                        <?php $index = 0; ?>
                        
                        @if($historyList != null)
                        @foreach($historyList as $career)
                            <tr>
                                <td class="hidden">{{$index}}</td>
                                <td class="hidden">
                                    <input type="text" name="index_{{$index}}" value="{{$index}}">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="text-align: center"
                                               type="text" data-date-format="yyyy-mm-dd"
                                               name="FromDate_{{$index}}"
                                               value="{{$career['FromDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="text-align: center"
                                               type="text" data-date-format="yyyy-mm-dd"
                                               name="ToDate_{{$index}}"
                                               value="{{$career['ToDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="Ship_{{$index}}" style="width: 100%;text-align: center"
                                           value="{{$career['Ship']}}">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="DutyID_{{$index}}">
                                        @foreach($posList as $pos)
                                            <option value="{{$pos['id']}}" @if($career['DutyID'] == $pos['id']) selected @endif>{{$pos['Duty']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="ShipType_{{$index}}">
                                        @foreach($typeList as $type)
                                            <option value="{{$type['id']}}" @if($career['ShipType'] == $type['id']) selected @endif>{{$type['ShipType_Cn']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="GrossTonage_{{$index}}" style="width: 100%;text-align: center"
                                           value="{{$career['GrossTonage']}}">
                                </td>
                                <td>
                                    <input type="text" name="Power_{{$index}}" style="width: 100%;text-align: center"
                                           value="{{$career['Power']}}">
                                </td>
                                <td>
                                    <input type="text" name="SailArea_{{$index}}" style="width: 100%;text-align: center"
                                           value="{{$career['SailArea']}}">
                                </td>
                                <td>
                                    <input type="text" name="Remarks_{{$index}}" style="width: 100%;"
                                           value="{{$career['Remarks']}}">
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="red" href="javascript:onDelete({{$index}})">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $index++ ?>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-5">
                    <button class="btn btn-inverse btn-sm" type="submit" style="width: 80px">
                        <i class="icon-save"></i>{{transShipMember('captions.register')}}
                    </button>
                </div>
            </div>
    </div-->
</div>