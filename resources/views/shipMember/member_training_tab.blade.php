<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
            <input class="hidden" name="_token" value="{{csrf_token()}}">
            <input class="hidden" name="memberId" value="{{$memberId}}">
            <div class="space-4"></div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td class="center" style="width:10%">{{transShipMember("TrainRegister.CertName")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("TrainRegister.Cert No")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("TrainRegister.CertKind")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("TrainRegister.Issue")}}</td>
                        <td class="center" style="width:10%">{{transShipMember("TrainRegister.Expiry")}}</td>
                        <td class="center" style="width:5%">{{transShipMember("TrainRegister.Copy")}}</td>
                        <td class="center" style="width:5%">{{transShipMember("TrainRegister.download")}}</td>
                        <td class="center">{{transShipMember("TrainRegister.Remark")}}</td>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Basic")}}
                            </td>
                            <td class="center">
                                <input type="text" name="TCBNo" style="width: 100%;text-align: center" value="{{$training['TCBNo']}}">
                            </td>
                            <td></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" type="text" data-date-format="yyyy/mm/dd" style="text-align: center"
                                           name="TCBIssuedDate" value="{{$training['TCBIssuedDate']}}">
                                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                </div>
                            </td>
                            <td>
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
                            <td>
                                <input type="file" style="text-align: center" name="TCBScan" class="input-file" >
                            </td>
                            <td>
                                @if (!empty($training['TCBScan']))
                                    <?php   $tmp = explode('.', $training['TCBScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.basicTrain").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['TCBScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="TCB_Remark"
                                       value="{{$training['TCB_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Special")}}
                            </td>
                            <td class="center">
                                <input type="text" name="TCSNo" style="width: 100%;text-align: center" value="{{$training['TCSNo']}}">
                            </td>
                            <td></td>
                            <td>
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
                            <td>
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
                            <td>
                                <input type="file" name="TCSScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['TCSScan']))
                                    <?php   $tmp = explode('.', $training['TCSScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.proTrain").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['TCSScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="TCS_Remark"
                                       value="{{$training['TCS_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Oil tank")}}
                            </td>
                            <td class="center">
                                <input type="text" name="TCTNo" style="width: 100%;text-align: center" value="{{$training['TCTNo']}}">
                            </td>
                            <td></td>
                            <td>
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
                            <td>
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
                            <td>
                                <input type="file" name="TCTScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['TCTScan']))
                                    <?php   $tmp = explode('.', $training['TCTScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.tankerTrainCopy").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['TCTScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="TCT_Remark"
                                       value="{{$training['TCT_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Security")}}
                            </td>
                            <td class="center">
                                <input type="text" name="TCPNo" style="width: 100%;text-align: center" value="{{$training['TCPNo']}}">
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="TCP_certID">

                                    <option value="" @if(empty($training['TCP_certID'])) selected @endif>&nbsp;</option>
                                    @if($security != null)
                                    @foreach($security as $cert)
                                        <option value="{{$cert['id']}}" @if(($training['TCP_certID'] == $cert['id']))  @endif>{{$cert['title']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
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
                            <td>
                            </td>
                            <td>
                                <input type="file" name="TCPScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['TCPScan']))
                                    <?php   $tmp = explode('.', $training['TCPScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.safeCertCopy").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['TCPScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="TCP_Remark"
                                       value="{{$training['TCP_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.SSO")}}
                            </td>
                            <td class="center">
                                <input type="text" name="SSONo" style="width: 100%;text-align: center" value="{{$training['SSONo']}}">
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="SSO_certID">
                                    <option value="" @if(empty($training['SSO_certID'])) selected @endif>&nbsp;</option>
                                    @if($security != null)
                                    @foreach($security as $cert)
                                        <option value="{{$cert['id']}}" @if(($training['SSO_certID'] == $cert['id']))  @endif>{{$cert['title']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
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
                            <td>
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
                            <td>
                                <input type="file" name="SSOScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['SSOScan']))
                                    <?php   $tmp = explode('.', $training['SSOScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.safeKeepCertCopy").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['SSOScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="SSO_Remark"
                                       value="{{$training['TCP_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Able Seafarer")}}
                            </td>
                            <td class="center">
                                <input type="text" name="ASDNo" style="width: 100%;text-align: center" value="{{$training['ASDNo']}}">
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="ASD_typeID">
                                    <option value="" @if(empty($training['ASD_typeID'])) selected @endif>&nbsp;</option>
                                    <option value="1" @if($training['ASD_typeID'] == 1) selected @endif>transShipMember("captions.deck")</option>
                                    <option value="2" @if($training['ASD_typeID'] == 2) selected @endif>transShipMember("captions.mast")</option>
                                </select>
                            </td>
                            <td>
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
                            <td>
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
                            <td>
                                <input type="file" name="ASDScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['ASDScan']))
                                    <?php   $tmp = explode('.', $training['ASDScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.transShipMember("captions.deck-mastCertCopy").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['ASDScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="ASD_Remark"
                                       value="{{$training['ASD_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="center">
                                {{transShipMember("TrainRegister.Health")}}
                            </td>
                            <td class="center">
                                <input type="text" name="MCS_No" style="width: 100%;text-align: center" value="{{$training['MCS_No']}}">
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="MCS_ExpiryDate"
                                           value="{{$training['MCS_ExpiryDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="file" name="MCSScan" class="input-file">
                            </td>
                            <td>
                                @if (!empty($training['MCSScan']))
                                    <?php   $tmp = explode('.', $training['MCSScan']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].transShipMember("captions.healthCert").$ext; ?>
                                    <a href="/fileDownload?type=training&path={{$training['MCSScan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-130"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="MCS_Remark"
                                       value="{{$training['MCS_Remark']}}" style="width: 100%">
                            </td>
                        </tr>
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
    </div>
</div>

