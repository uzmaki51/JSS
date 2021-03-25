<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <input class="hidden" name="_token" value="{{csrf_token()}}">
        <input class="hidden" name="memberId" value="{{$memberId}}">
        <div class="col-md-8">
            <div class="space-4"></div>
                <table class="table table-bordered">
                    <tbody>
                        <tr style="background-color: #d0e6dd">
                            <td class="center td-header" style="width:40%" colspan="2">{{transShipMember("TrainRegister.CertName")}}</td>
                            <td class="center td-header" style="width:15%">{{transShipMember("TrainRegister.Cert No")}}</td>
                            <td class="center td-header" style="width:15%">{{transShipMember("TrainRegister.Issue")}}</td>
                            <td class="center td-header" style="width:15%">{{transShipMember("TrainRegister.Expiry")}}</td>
                            <td class="center td-header">{{transShipMember("TrainRegister.IssuedBy")}}</td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd" colspan="2">
                                {{transShipMember("TrainRegister.Basic")}}
                            </td>
                            <td class="center no-padding">
                                <input type="text" class="form-control" name="TCBNo" style="width: 100%;text-align: center" value="{{$training['TCBNo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" style="text-align: center"
                                           name="TCBIssuedDate" value="{{$training['TCBIssuedDate']}}">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCBExpiryDate"
                                           value="{{$training['TCBExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="TCB_Remark"
                                       value="{{$training['TCB_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd" colspan="2">
                                {{transShipMember("TrainRegister.Special")}}
                            </td>
                            <td class="center no-padding">
                                <input type="text" class="form-control" name="TCSNo" style="width: 100%;text-align: center" value="{{$training['TCSNo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCSIssuedDate"
                                           value="{{$training['TCSIssuedDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCSExpiryDate"
                                           value="{{$training['TCSExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input class="form-control" type="text" name="TCS_Remark"
                                       value="{{$training['TCS_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd" colspan="2">
                                {{transShipMember("TrainRegister.Oil tank")}}
                            </td>
                            <td class="center no-padding">
                                <input class="form-control" type="text" name="TCTNo" style="width: 100%;text-align: center" value="{{$training['TCTNo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCTIssuedDate"
                                           value="{{$training['TCTIssuedDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCTExpiryDate"
                                           value="{{$training['TCTExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input class="form-control" type="text" name="TCT_Remark"
                                       value="{{$training['TCT_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd;width:20%" >
                                {{transShipMember("TrainRegister.Able Seafarer")}}
                            </td>
                            <td class="no-padding" style="width:20%">
                                <select class="form-control" name="ASD_typeID">
                                    <option value="" @if(empty($training['ASD_typeID'])) selected @endif>&nbsp;</option>
                                    <option value="1" @if($training['ASD_typeID'] == 1) selected @endif>{{transShipMember("captions.deck")}}</option>
                                    <option value="2" @if($training['ASD_typeID'] == 2) selected @endif>{{transShipMember("captions.mast")}}</option>
                                </select>
                            </td class="no-padding">
                            <td class="center no-padding">
                                <input class="form-control" type="text" name="ASDNo" style="width: 100%;text-align: center" value="{{$training['ASDNo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="ASDIssuedDate"
                                           value="{{$training['ASDIssuedDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="ASDExpiryDate"
                                           value="{{$training['ASDExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input class="form-control" type="text" name="ASD_Remark"
                                       value="{{$training['ASD_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd" >
                                {{transShipMember("TrainRegister.Security")}}
                            </td>
                            <td class="no-padding">
                                <select class="form-control" name="TCP_certID">
                                    <option value="" @if(empty($training['TCP_certID'])) selected @endif>&nbsp;</option>
                                    @if($security != null)
                                    @foreach($security as $cert)
                                        <option value="{{$cert['id']}}" @if(($training['TCP_certID'] == $cert['id'])) selected @endif>{{$cert['title']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="center no-padding">
                                <input class="form-control" type="text" name="TCPNo" style="width: 100%;text-align: center" value="{{$training['TCPNo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCPIssuedDate"
                                           value="{{$training['TCPIssuedDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="TCPExpiryDate"
                                           value="{{$training['TCPExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input class="form-control" type="text" name="TCP_Remark"
                                       value="{{$training['TCP_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                        <tr>
                            <td class="center td-header" style="background-color: #d0e6dd" >
                                {{transShipMember("TrainRegister.SSO")}}
                            </td>
                            <td class="no-padding">
                                <select class="form-control" name="SSO_certID">
                                    <option value="" @if(empty($training['SSO_certID'])) selected @endif>&nbsp;</option>
                                    @if($security != null)
                                    @foreach($security as $cert)
                                        <option value="{{$cert['id']}}" @if(($training['SSO_certID'] == $cert['id'])) selected @endif>{{$cert['title']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="center no-padding">
                                <input class="form-control" type="text" name="SSONo" style="width: 100%;text-align: center" value="{{$training['SSONo']}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="SSOIssuedDate"
                                           value="{{$training['SSOIssuedDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="SSOExpiryDate"
                                           value="{{$training['SSOExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input class="form-control" type="text" name="SSO_Remark"
                                       value="{{$training['TCP_Remark']}}" style="width: 100%;text-align: center">
                            </td>
                        </tr>
                    </tbody>
                </table>    
            </div>
    </div>
</div>

