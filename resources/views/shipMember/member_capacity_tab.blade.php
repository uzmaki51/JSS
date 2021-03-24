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
                                <td class="center td-header" style="width:15%">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center td-header" style="width:30%">{{transShipMember("CapacityData.Capacity Function")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center td-header">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td class="no-padding">
                                    <input type="text" class="form-control" name="ItemNo" value="{{$capacity['ItemNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td class="no-padding">
                                    <select class="form-control" name="CapacityID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                        <option value="{{$type['id']}}" @if($capacity['CapacityID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="no-padding">
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
                                <td class="no-padding">
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
                                <td class="no-padding">
                                    <input type="text" class="form-control" name="COC_Remarks" value="{{$capacity['COC_Remarks']}}" style="width: 100%;text-align: center">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="space-2"></div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="background-color: #d0e6dd">
                                <td class="center td-header" style="width:15%">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center td-header" style="width:30%">{{transShipMember("CapacityData.Capacity Function(GMDSS)")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center td-header">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td class="no-padding">
                                    <input class="form-control" type="text" name="GMDSS_NO" value="{{$capacity['GMDSS_NO']}}" style="width: 100%;text-align: center">
                                </td>
                                <td class="no-padding">
                                    <select class="form-control" name="GMDSSID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['GMDSSID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
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
                        </tbody>
                    </table>
                    <div class="space-2"></div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="background-color: #d0e6dd">
                                <td class="center td-header" style="width:15%">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center td-header" style="width:30%">{{transShipMember("CapacityData.Capacity Function(COE)")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center td-header">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td class="no-padding">
                                    <input type="text" class="form-control" name="COENo" value="{{$capacity['COENo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td class="no-padding">
                                    <select class="form-control" name="COEId">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['COEId'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
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
                        </tbody>
                    </table>
                    <div class="space-2"></div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="background-color: #d0e6dd">
                                <td class="center td-header" style="width:15%">{{transShipMember("CapacityData.Cert No")}}</td>
                                <td class="center td-header" style="width:30%">{{transShipMember("CapacityData.Capacity Function(GOC)")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center td-header">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td class="no-padding">
                                    <input type="text" class="form-control" name="COE_GOCNo" value="{{$capacity['COE_GOCNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td class="no-padding">
                                    <select class="form-control" name="COE_GOCId">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['COE_GOCId'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
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
                            </tbody>
                    </table>
                    <div class="space-2"></div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="background-color: #d0e6dd">
                                <td class="center td-header" style="width:15%">{{transShipMember("CapacityData.Watching No")}}</td>
                                <td class="center td-header" style="width:30%">{{transShipMember("CapacityData.Watching Rating")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Issue")}}</td>
                                <td class="center td-header" style="width:13%">{{transShipMember("CapacityData.Expiry")}}</td>
                                <td class="center td-header">{{transShipMember("CapacityData.Issuing Authority")}}</td>
                            </tr>
                            <tr>
                                <td class="no-padding">
                                    <input type="text" class="form-control" name="WatchNo" value="{{$capacity['WatchNo']}}" style="width: 100%;text-align: center">
                                </td>
                                <td class="no-padding">
                                    <select class="form-control" name="WatchID">
                                        <option value="0">&nbsp;</option>
                                        @foreach($capacityList as $type)
                                            <option value="{{$type['id']}}" @if($capacity['WatchID'] == $type['id'])) selected @endif>{{$type['Capacity']}}</option>
                                        @endforeach
                                    </select>
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
            
            <div class="col-md-8">
                <div class="blue td-header">School Career</div>
                <table class="table table-bordered">
                    <tbody id="school_table">
                    <tr style="background-color: #fce6d4">
                        <td class="center td-header no-padding" style="width:15%">{{transShipMember("CapacityData.From")}}</td>
                        <td class="center td-header no-padding" style="width:15%">{{transShipMember("CapacityData.To")}}</td>
                        <td class="center td-header no-padding" style="width:30%">{{transShipMember("CapacityData.School")}}</td>
                        <td class="center td-header no-padding" style="width:6%">{{transShipMember("CapacityData.Major")}}</td>
                        <td class="center td-header no-padding" style="width:6%">{{transShipMember("CapacityData.Grade")}}</td>
                        <td class="center td-header no-padding" style="width:6%">{{transShipMember("CapacityData.Capacity")}}</td>
                        <td class="center td-header no-padding">{{transShipMember("CapacityData.Remark")}}</td>
                    </tr>
                    @if($schoolList != null)
                    @foreach($schoolList as $school)
                        <tr>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="FromDate[]"
                                           value="{{$school['FromDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="ToDate[]"
                                           value="{{$school['ToDate']}}">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="SchoolName[]" 
                                       value="{{$school['SchoolName']}}" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="Major[]"
                                       value="{{$school['Major']}}" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="Grade[]"
                                       value="{{$school['Grade']}}" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="TechQualification[]"
                                       value="{{$school['TechQualification']}}" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="School_Remarks[]"
                                       value="{{$school['Remarks']}}" style="width: 100%;text-align: center">
                            </td>
                            <td class="center no-padding">
                                <div class="action-buttons">
                                    <a class="red" href="javascript:deleteSchool(this)">
                                        <i class="icon-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                        <!--tr>
                            <td class="hidden">{{$index}}</td>
                            <td class="hidden">
                                <input type="text" name="school_{{$index}}" value="{{$index}}">
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="FromDate[]"
                                           value="">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <div class="input-group">
                                    <input class="form-control date-picker" style="width: 100%;text-align: center"
                                           type="text" data-date-format="yyyy/mm/dd"
                                           name="ToDate[]"
                                           value="">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="SchoolName[]"
                                       value="" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="Major[]"
                                       value="" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="Grade[]"
                                       value="" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="TechQualification[]"
                                       value="" style="width: 100%;text-align: center">
                            </td>
                            <td class="no-padding">
                                <input type="text" class="form-control" name="Remarks[]"
                                       value="" style="width: 100%;text-align: center">
                            </td>
                            <td class="center no-padding">
                                <div class="action-buttons">
                                    <a class="red" href="javascript:addSchool()">
                                        <i class="icon-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr-->
                    </tbody>
                </table>
            </div>
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

    addSchool(null);
    
    function deleteSchool(e)
    {
        if ($('#school_table tr').length > 2 && !$(e).closest("tr").is(":last-child")) {
            if (confirm("Are you sure to delete?")) {
                $(e).closest("tr").remove();
            }
        }
    }

    function addSchool(e)
    {
        console.log($(e).closest("tr"));
        if (e == null || $(e).closest("tr").is(":last-child")) {
            $("#school_table").append('<tr><td class="no-padding"><div class="input-group"><input onchange="addSchool(this)" class="form-control date-picker" style="width: 100%;text-align: center" type="text" data-date-format="yyyy/mm/dd"name="FromDate[]"value=""><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td><td class="no-padding"><div class="input-group"><input onchange="addSchool(this)" class="form-control date-picker" style="width: 100%;text-align: center"type="text" data-date-format="yyyy/mm/dd"name="ToDate[]"value=""><span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td><td class="no-padding"><input type="text" onchange="addSchool(this)" class="form-control" name="SchoolName[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onchange="addSchool(this)" class="form-control" name="Major[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onchange="addSchool(this)" class="form-control" name="Grade[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onchange="addSchool(this)" class="form-control" name="TechQualification[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onchange="addSchool(this)" class="form-control" name="School_Remarks[]"value="" style="width: 100%;text-align: center"></td><td class="center no-padding"><div class="action-buttons"><a class="red" onclick="javascript:deleteSchool(this)"><i class="icon-trash"></i></a></div></td></tr>');
            setDatePicker();
        }
    }

    function setDatePicker() {
        $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });
    }
</script>