<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <form role="form" method="POST" enctype="multipart/form-data" action="updateMemberCapacityData">
            <input class="hidden" name="_token" value="{{csrf_token()}}">
            <input class="hidden" name="memberId" value="{{$memberId}}">
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <div class="space-4"></div>
                <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td colspan="10">
                                    <span style="float: left;padding-top:7px"><b>{{transShipMember("CapacityData.Capacity Reg")}}</b></span>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: #f5f5f5">
                                <td class="center" style="width:10%">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center" style="width:20%">{{transShipMember("CapacityData.Capacity Function")}}</td>
                                <td class="center" style="width:10%">{{transShipMember("CapacityData.Copy")}}</td>
                                <td class="center" style="width:5%">{{transShipMember("CapacityData.download")}}</td>
                                <td class="center" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="ItemNo" value="{{$capacity['ItemNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="CapacityID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                        <option value="{{$type['id']}}" @if($capacity['CapacityID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="input-file" name="GOC"/>
                                </td>
                                <td>
                                    @if(!empty($capacity['GOC']))
                                        <?php   $tmp = explode('.', $capacity['GOC']);
                                                $ext = $tmp[count($tmp) - 1];
                                                $filename = $info['realname'].'_'.transShipMember('captions.licenseCopy').'1(GOC).'.$ext; ?>
                                        <a href="/fileDownload?type=capacity&path={{$capacity['GOC']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                            <i class="icon-file bigger-140"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="width: 100%;text-align: center"
                                               type="text" data-date-format="yyyy/mm/dd"
                                               name="IssuedDate"
                                               value="{{$capacity['IssuedDate']}}">
                                                                <span class="input-group-addon">
                                                                    <i class="icon-calendar bigger-110"></i>
                                                                </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control date-picker" style="width: 100%;text-align: center"
                                               type="text" data-date-format="yyyy/mm/dd"
                                               name="ExpiryDate"
                                               value="{{$capacity['ExpiryDate']}}">
                                                                <span class="input-group-addon">
                                                                    <i class="icon-calendar bigger-110"></i>
                                                                </span>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="COC_Remarks" value="{{$capacity['COC_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                            <tr style="background-color: #f5f5f5">
                                <td class="center">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Capacity Function(GMDSS)")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Copy")}}</td>
                                <td class="center" style="width:5%">{{transShipMember("CapacityData.download")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="GMDSS_NO" value="{{$capacity['GMDSS_NO']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="GMDSSID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['GMDSSID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="input-file" name="GMDSS_Scan"/>
                                </td>
                                <td>
                                    @if(!empty($capacity['GMDSS_Scan']))
                                        <?php   $tmp = explode('.', $capacity['GMDSS_Scan']);
                                        $ext = $tmp[count($tmp) - 1];
                                        $filename = $info['realname'].'_'.transShipMember('captions.licenseCopy').'2(GMDSS).'.$ext; ?>
                                        <a href="/fileDownload?type=capacity&path={{$capacity['GMDSS_Scan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                            <i class="icon-file bigger-140"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
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
                                <td>
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
                                <td>
                                    <input type="text" name="GMD_Remarks" value="{{$capacity['GMD_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                            <tr style="background-color: #f5f5f5">
                                <td class="center">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Capacity Function(COE)")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Copy")}}</td>
                                <td class="center" style="width:5%">{{transShipMember("CapacityData.download")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="COENo" value="{{$capacity['COENo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="COEId">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['COEId'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="input-file" name="COE_Scan" style="width: 100%;text-align: center"/>
                                </td>
                                <td>
                                    @if(!empty($capacity['COE_Scan']))
                                        <?php   $tmp = explode('.', $capacity['COE_Scan']);
                                                $ext = $tmp[count($tmp) - 1];
                                                $filename = $info['realname'].'_'.transShipMember('captions.licenseCopy').'3(COE).'.$ext; ?>
                                        <a href="/fileDownload?type=capacity&path={{$capacity['COE_Scan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                            <i class="icon-file bigger-140"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
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
                                <td>
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
                                <td>
                                    <input type="text" name="COE_Remarks" value="{{$capacity['COE_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                            <tr style="background-color: #f5f5f5">
                                <td class="center">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Capacity Function(GOC)")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Copy")}}</td>
                                <td class="center" style="width:5%">{{transShipMember("CapacityData.download")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="COE_GOCNo" value="{{$capacity['COE_GOCNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="COE_GOCId">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['COE_GOCId'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="input-file" name="COE_GOC_Scan"/>
                                </td>
                                <td>
                                    @if(!empty($capacity['COE_GOC_Scan']))
                                        <?php   $tmp = explode('.', $capacity['COE_GOC_Scan']);
                                                $ext = $tmp[count($tmp) - 1];
                                                $filename = $info['realname'].'_'.transShipMember('captions.licenseCopy').'4(COE_GOC).'.$ext; ?>
                                        <a href="/fileDownload?type=capacity&path={{$capacity['COE_GOC_Scan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                            <i class="icon-file bigger-140"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
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
                                <td>
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
                                <td>
                                    <input type="text" name="COE_GOC_Remarks" value="{{$capacity['COE_GOC_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                            <tr style="background-color: #f5f5f5">
                                <td class="center">{{transShipMember("CapacityData.Watching No")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Watching Rating")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Copy")}}</td>
                                <td class="center" style="width:5%">{{transShipMember("CapacityData.download")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="WatchNo" value="{{$capacity['WatchNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td>
                                    <select class="form-control chosen-select" name="WatchID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['WatchID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="input-file" name="Watch_Scan"/>
                                </td>
                                <td>
                                    @if(!empty($capacity['Watch_Scan']))
                                        <?php   $tmp = explode('.', $capacity['Watch_Scan']);
                                                $ext = $tmp[count($tmp) - 1];
                                                $filename = $info['realname'].'_'.transShipMember('captions.licenseCopy').'5('.transShipMember('captions.watchCopy').').'.$ext; ?>
                                        <a href="/fileDownload?type=capacity&path={{$capacity['Watch_Scan']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                            <i class="icon-file bigger-140"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
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
                                <td>
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
                                <td>
                                    <input type="text" name="Watch_Remarks" value="{{$capacity['Watch_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="space-2"></div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td colspan="10" class="center">
                                <span style="float: left;padding-top:7px"><b>{{transShipMember("CapacityData.Capacity Career")}}</b></span>
                                <a class="btn btn-sm btn-primary" style="float: right;width :80px;" href="javascript:newCapacityRow()"><i class="icon-plus-sign-alt"></i>添加</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="center" style="width:15%">{{transShipMember("CapacityData.RegNo")}}</td>
                            <td class="center" style="width:30%">{{transShipMember("CapacityData.Capacity")}}</td>
                            <td class="center" style="width:20px">{{transShipMember("CapacityData.GOC")}}</td>
                            <td class="center" style="width:20%">{{transShipMember("CapacityData.RegBase")}}</td>
                            <td class="center">{{transShipMember("CapacityData.Remark")}}</td>
                            <td class="center" style="width:30px"></td>
                        </tr>
                    </thead>
                    <tbody id="capacity_table">
                    <?php $index = 0; ?>
                    @foreach($capacity_career as $career)
                        <tr>
                            <td class="hidden">{{$index}}</td>
                            <td class="hidden">
                                <input type="text" style="width: 100%;text-align: center" name="capacity_{{$index}}" value="{{$index}}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="RegDate_{{$index}}"
                                           value="{{$career['RegDate']}}">
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar bigger-110"></i>
                                                            </span>
                                </div>
                            </td>
                            <td>
                                <select class="form-control chosen-select" name="CapacityID_{{$index}}">
                                    @foreach($capacityList as $type)
                                        <option value="{{$type['id']}}" @if($career['CapacityID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="center">
                                <input type="checkbox" name="GOC_{{$index}}" style="width: 16px;height:16px" @if($career['GOC'] == 1) checked @endif>
                            </td>
                            <td>
                                <input type="text" name="RegReason_{{$index}}" style="width: 100%;text-align: center"
                                       value="{{$career['RegReason']}}" style="width: 100%">
                            </td>
                            <td>
                                <input type="text" name="Remarks_Career_{{$index}}"
                                       value="{{$career['Remarks']}}" style="width: 100%">
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" href="javascript:onCapacityDelete({{$index}})">
                                        <i class="icon-trash bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $index++ ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-2"></div>
            <div class="row" style="margin-left: 10px;margin-right: 10px">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td colspan="10" class="center">
                                <span style="float: left;padding-top:7px"><b>{{transShipMember("CapacityData.School Career")}}  </b></span>
                                <a class="btn btn-sm btn-primary" style="float: right; width :80px;" href="javascript:newSchoolRow()"><i class="icon-plus-sign-alt"></i>添加</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="center" style="width:10%">{{transShipMember("CapacityData.From")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("CapacityData.To")}}</td>
                            <td class="center" style="width:20%">{{transShipMember("CapacityData.School")}}</td>
                            <td class="center" style="width:5%">{{transShipMember("CapacityData.Major")}}</td>
                            <td class="center" style="width:5%">{{transShipMember("CapacityData.Marks")}}</td>
                            <td class="center" style="width:10%">{{transShipMember("CapacityData.Capacity")}}</td>
                            <td class="center" style="width:10%;">{{transShipMember("CapacityData.Copy")}}</td>
                            <td class="center" style="width:5%;">{{transShipMember("CapacityData.download")}}</td>
                            <td class="center">{{transShipMember("CapacityData.Remark")}}</td>
                            <td class="center" style="width:30px"></td>
                        </tr>
                    </thead>
                    <tbody id="school_table">
                    <?php $index = 0; ?>
                    @foreach($schoolList as $school)
                        <tr>
                            <td class="hidden">{{$index}}</td>
                            <td class="hidden">
                                <input type="text" name="school_{{$index}}" value="{{$index}}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="FromDate_{{$index}}"
                                           value="{{$school['FromDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="ToDate_{{$index}}"
                                           value="{{$school['ToDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="SchoolName_{{$index}}"
                                       value="{{$school['SchoolName']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="Major_{{$index}}"
                                       value="{{$school['Major']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="Grade_{{$index}}"
                                       value="{{$school['Grade']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="text" name="TechQualification_{{$index}}"
                                       value="{{$school['TechQualification']}}" style="width: 100%;text-align: center">
                            </td>
                            <td>
                                <input type="file" class="input-file" name="school_goc_{{$index}}"/>
                            </td>
                            <td>
                                @if(!empty($school['GOC']))
                                    <?php   $tmp = explode('.', $school['GOC']);
                                            $ext = $tmp[count($tmp) - 1];
                                            $filename = $info['realname'].'_'.$school['SchoolName'].'_'.transShipMember('captions.diplomaCopy').$ext; ?>
                                    <a href="/fileDownload?type=school&path={{$school['GOC']}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                        <i class="icon-file bigger-140"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="Remarks_{{$index}}"
                                       value="{{$school['Remarks']}}" style="width: 100%">
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" href="javascript:onSchoolDelete({{$index}})">
                                        <i class="icon-trash bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $index++ ?>
                    @endforeach
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
        </form>
    </div>
</div>

<script>
    var capacityList = new Array();
    var cIndex = 0;
    @foreach($typeList as $type)
        var capacity = new Object();
        capacity.value = '{{$type['id']}}';
        capacity.text = '{{$type['Capacity']}}';
        capacityList[cIndex] = capacity;
        cIndex++;
    @endforeach

</script>