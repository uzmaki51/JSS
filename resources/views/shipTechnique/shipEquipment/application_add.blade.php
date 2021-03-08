<div style="width:150%;">
    <form role="form" method="POST" action="{{ url('shipTechnique/saveSupplyInfo') }}"
          enctype="multipart/form-data" id="validation-form" name="addNewSupplyInfo">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <table id="tbl_app" class="table table-striped table-bordered table-hover">
            <input type="hidden" name="supplyId" value="{{ $supplyId }}">
            <thead>
            <tr class="black br-hblue">
                <th class="center"
                    colspan="8"
                    style="">{{transShipTech("supplyRecord.Application")}}</th>

                <th class="center"
                    colspan="5"
                    style="">{{transShipTech("supplyRecord.Quotation")}}</th>

                <th class="center"
                    colspan="3"
                    style="">{{transShipTech("supplyRecord.Instruction")}}</th>

                <th class="center" colspan="7"
                    style="">{{transShipTech("supplyRecord.Recipt")}}</th>
            </tr>
            <tr class="black br-hblue">
                <th class="center"
                    style="">{{transShipTech("supplyRecord.Ship")}}</th>
                <th rowspan="2" class="center"
                    style="width: 20px;">{{transShipTech("supplyRecord.No")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Dept")}}</th>
                <th class="center"
                    style="width: 100px;">{{transShipTech("supplyRecord.Equipment")}}</th>
                <th class="center"
                    style="width: 100px;">{{transShipTech("supplyRecord.Part/Issa/Others")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Qtty")}}</th>
                <th class="center"
                    style="width: 80px;">{{transShipTech("supplyRecord.Unit")}}</th>
                <th class="center" style="width: 10px;">Y/N</th>

                <th class="center"
                    style="width: 90px;">{{transShipTech("supplyRecord.Date")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Qtty")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Price")}}</th>
                <th class="center"
                    style="width: 70px;">{{transShipTech("supplyRecord.Money")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Money")}}</th>

                <th class="center"
                    style="width: 80px;">{{transShipTech("supplyRecord.Date")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Qtty")}}</th>
                <th class="center" style="width: 10px;">Y/N</th>

                <th class="center"
                    style="width: 100px;">{{transShipTech("supplyRecord.Voy")}}</th>
                <th class="center"
                    style="width: 120px;">{{transShipTech("supplyRecord.Place")}}</th>
                <th class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Qtty")}}</th>
                <th class="center"
                    style="width: 50px;">{{transShipTech("supplyRecord.Rate")}}</th>
                <th class="center"
                    style="width: 60px">{{transShipTech("supplyRecord.Price")}}</th>
                <th class="center" style="width: 60px;">Y/N</th>
                <th rowspan="2" class="center"
                    style="width: 60px;">{{transShipTech("supplyRecord.Remark")}}</th>
            </tr>
            <tr class="black br-hblue">
                <th class="center">{{transShipTech("supplyRecord.Voy")}}</th>
                <th class="center">{{transShipTech("supplyRecord.Kind")}}</th>
                <th class="center">{{transShipTech("supplyRecord.SN")}}</th>
                <th class="center">PartNo/IssaCodeNo/Special</th>
                <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                <th class="center">{{transShipTech("supplyRecord.Opposit")}}</th>
                <th class="center">{{transShipTech("supplyRecord.NewKind")}}</th>
                <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                <th colspan="3" class="center">{{transShipTech("supplyRecord.Remark")}}</th>

                <th class="center">{{transShipTech("supplyRecord.Date")}}</th>
                <th class="center">{{transShipTech("supplyRecord.Supplier")}}</th>
                <th colspan="2" class="center">{{transShipTech("supplyRecord.Money")}}</th>
                <th class="center">{{transShipTech("supplyRecord.SupplyMoney")}}</th>
                <th class="center">{{transShipTech("supplyRecord.Total")}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="center" style="width: 4%;">
                    <div id="shipName_Cn" style="width: 100px;">
                        <select class="form-control chosen-select" name="shipName_Cn" id="shipNameId"
                                onchange="getVoyInfo()">
                            <option value="">&nbsp;</option>
                            @foreach($shipInfos as $shipInfo)
                                <option value="{{$shipInfo['RegNo']}}"
                                    @if(!empty($supplyInfo) && $supplyInfo->ShipName == $shipInfo['RegNo'])
                                        selected
                                    @endif>{{$shipInfo['shipName_En']}} | {{$shipInfo['shipName_Cn']}}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td rowspan="2" class="center">
                    <input type="text" class="form-control" name="No"
                        @if(!empty($supplyInfo)) value="{{ $supplyInfo->No }}" @endif>
                </td>
                <td class="center">
                    <select class="form-control" name="Dept_Cn">
                        <option value="">&nbsp;</option>
                        @foreach($deptInfos as $deptInfo)
                            <option value="{{ $deptInfo['id'] }}"
                                @if(!empty($supplyInfo) && $supplyInfo->Dept == $deptInfo['id'])
                                    selected
                                @endif>{{ $deptInfo['Dept_Cn'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="center">
                    <div class="Equipment" id="Euipment_Cn" style="width: 100px; display:
                     @if(!empty($supplyInfo) && $supplyInfo->SSkind != 1 && $supplyInfo->SSkind != 2) none @else block @endif;">
                        <select class="form-control chosen-select" name="Euipment_Cn"
                                onchange="getPartInfo();changeValue('equip', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($equipInfos as $equipInfo)
                                <option value="{{ $equipInfo->id }}"
                                    @if(!empty($supplyInfo) && $supplyInfo->Euipment == $equipInfo->id)
                                        selected
                                    @endif>{{ $equipInfo->Euipment_Cn }}({{ $equipInfo->GroupOfEuipment_Cn }})|{{ $equipInfo->Euipment_En }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="center">
                    <div class="Part" id="PartName_Cn" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind != 1 && $supplyInfo->SSkind != 2) none @else block @endif;">
                        <select class="form-control chosen-select" name="PartName_Cn"
                            onchange="changeValue('part', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}"
                                    @if(!empty($supplyInfo) && $supplyInfo->Part == $part->id)
                                        selected
                                    @endif>{{ $part->PartName_Cn }}|{{ $part->PartName_En }}|{{ $part->PartNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="Code" id="Content_Cn" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind == 3) block @else none @endif;">
                        <select class="form-control chosen-select" name="Content_Cn"
                            onchange="changeValue('content', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($issaCodes as $issaCode)
                                <option value="{{ $issaCode->id }}"
                                    @if(!empty($supplyInfo) && $supplyInfo->IssaCodeContent == $issaCode->id)
                                        selected
                                    @endif>{{ $issaCode->Content_Cn }}|{{ $issaCode->Content_En }}|{{ $issaCode->CodeNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="Other" id="Others_Cn" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind == 4) block @else none @endif;">
                        <select class="form-control chosen-select" name="Others_Cn"
                            onchange="changeValue('other', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($others as $other)
                                <option value="{{ $other->OthersId }}"
                                    @if(!empty($supplyInfo) && $supplyInfo->Others == $other->OthersId)
                                        selected
                                    @endif>{{ $other->Others_Cn }}|{{ $other->Others_En }}|{{ $other->Special }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="ApplQtty"
                        @if(!empty($supplyInfo)) value="{{ $supplyInfo->ApplQtty }}" @endif>
                </td>
                <td class="center">
                    <select class="form-control" name="Unit_Cn">
                        <option value="">&nbsp;</option>
                        @foreach($equipUnits as $equipUnit)
                            <option value="{{ $equipUnit['id'] }}"
                                @if(!empty($supplyInfo) && $supplyInfo->Unit == $equipUnit['id'])
                                    selected
                                @endif>{{ $equipUnit['Unit_En'] }}({{ $equipUnit['Unit_Cn'] }})</option>
                        @endforeach
                    </select>
                </td>
                <td class="center">
                    <input type="checkbox" style="width:17px;height:17px" name="ApplCheck" value="1"
                        @if(!empty($supplyInfo) && $supplyInfo->ApplCheck == 1)
                            checked
                        @endif>
                </td>

                <td class="center">
                    <input type="text" class="form-control date-picker" name="QuotDate" data-date-format="yyyy-mm-dd"
                        @if(!empty($supplyInfo)) value="{{ $supplyInfo->QuotDate }}" @endif>
                </td>
                <td class="center">
                    <input type="number" class="form-control" name="QuotQtty"
                        @if(!empty($supplyInfo)) value="{{ $supplyInfo->QuotQtty }}" @endif
                        onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="number" class="form-control" name="QuotPrice"
                        @if(!empty($supplyInfo)) value="{{ $supplyInfo->QuotPrice }}" @endif
                        onchange="calcQuot()">
                </td>
                <td class="center">
                    <select class="form-control" name="Currency">
                        <option value="">&nbsp;</option>
                        <option value="원(내)"
                                @if(!empty($supplyInfo) && $supplyInfo->Currency == '원(내)')
                                    selected
                                @endif>원(내)</option>
                        <option value="원(외)"
                                @if(!empty($supplyInfo) && $supplyInfo->Currency == '원(외)')
                                selected
                                @endif>원(외)</option>
                        <option value="￥"
                                @if(!empty($supplyInfo) && $supplyInfo->Currency == '￥')
                                selected
                                @endif>￥</option>
                        <option value="＄"
                                @if(!empty($supplyInfo) && $supplyInfo->Currency == '＄')
                                selected
                                @endif>＄</option>
                        <option value="€"
                                @if(!empty($supplyInfo) && $supplyInfo->Currency == '€')
                                selected
                                @endif>€</option>
                    </select>
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="QuotAmount"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->QuotAmount }}" @endif
                            onchange="calcQuot()">
                </td>

                <td class="center">
                    <input type="text" class="form-control date-picker"
                            name="SupplyApplDate" data-date-format="yyyy-mm-dd"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->SupplyApplDate }}" @endif>
                </td>
                <td class="center">
                    <input type="number" class="form-control" name="SupplyApplQtty"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->SupplyApplQtty }}" @endif>
                </td>
                <td class="center">
                    <input type="checkbox" style="width:17px;height:17px" name="SupplyApplCheck" value="1"
                            @if(!empty($supplyInfo) && $supplyInfo->SupplyApplCheck == 1) checked @endif>
                </td>

                <td class="center">
                    <select class="form-control" name="Recipt">
                        <option value="">&nbsp;</option>
                        @foreach($cpInfos as $cp)
                            <option value="{{ $cp['id'] }}"
                                @if(!empty($supplyInfo) && $supplyInfo->ReciptVoy == $cp['id'])
                                selected
                                @endif>{{ $cp['Voy_No'] }} | {{ $cp['CP_No'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="center">
                    <div style="width: 120px;">
                        <select class="form-control chosen-select" name="ReciptPlace">
                            <option value="">&nbsp;</option>
                            @foreach($shipPorts as $shipPort)
                                <option value="{{ $shipPort['id'] }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->ReciptPlace == $shipPort['id'])
                                            selected
                                        @endif>{{ $shipPort['Port_Cn'] }}/{{ $shipPort['Port_En'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="center">
                    <input type="number" class="form-control" name="ReciptQtty"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->ReciptQtty }}" @endif
                           onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="MarketCondition_Usd"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->MarketCondition_Usd }}" @endif
                           onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="ReciptPrice"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->ReciptPrice }}" @endif
                           onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="checkbox" style="width:17px;height:17px" name="ReciptCheck" value="1"
                            @if(!empty($supplyInfo) && $supplyInfo->ReciptCheck == 1) checked @endif>
                </td>
                <td rowspan="2" class="center">
                    <input type="text" class="form-control" name="ReciptRemark"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->ReciptRemark }}" @endif>
                </td>
            </tr>
            <tr>
                <td class="center" style="width: 4%;">
                    <div id="ApplicationVoy" style="width: 100px;">
                        <select class="form-control chosen-select" name="ApplicationVoy">
                            <option value="">&nbsp;</option>
                            @foreach($cpInfos as $cp)
                                <option value="{{ $cp['id'] }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->ApplicationVoy == $cp['id'])
                                        selected
                                        @endif>{{ $cp['Voy_No'] }} | {{ $cp['CP_No'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="center">
                    <select class="form-control" name="Kind_Cn"
                            onchange="getEquipmentInfo()">
                        <option value="">&nbsp;</option>
                        @foreach($kinds as $kind)
                            <option value="{{ $kind['id'] }}"
                                    @if(!empty($supplyInfo) && $supplyInfo->SSkind == $kind['id'])
                                        selected
                                    @endif>{{ $kind['Kind_Cn'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="center">
                    <div class="Equipment" id="SN" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind != 1 && $supplyInfo->SSkind != 2) none @else block @endif;">
                        <select class="form-control chosen-select" name="SN"
                                onchange="getPartInfo();changeValue('equip', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($equipInfos as $equipInfo)
                                <option value="{{ $equipInfo->id }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->SN == $equipInfo->id)
                                        selected
                                        @endif>{{ $equipInfo->Label }}|{{ $equipInfo->Type }}|{{ $equipInfo->SN }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="center">
                    <div class="Part" id="PartNo" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind != 1 && $supplyInfo->SSkind != 2) none @else block @endif;">
                        <select class="form-control chosen-select" name="PartNo"
                            onchange="changeValue('part', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->PartNo == $part->id)
                                        selected
                                        @endif>{{ $part->PartNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="Code" id="CodeNo" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind == 3) block @else none @endif;">
                        <select class="form-control chosen-select" name="CodeNo"
                            onchange="changeValue('content', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($issaCodes as $issaCode)
                                <option value="{{ $issaCode->id }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->IssaCodeNo == $issaCode->id)
                                        selected
                                        @endif>{{ $issaCode->CodeNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="Other" id="Special" style="width: 100px; display:
                    @if(!empty($supplyInfo) && $supplyInfo->SSkind == 4) block @else none @endif;">
                        <select class="form-control chosen-select" name="Special"
                            onchange="changeValue('other', this.value);">
                            <option value="">&nbsp;</option>
                            @foreach($others as $other)
                                <option value="{{ $other->OthersId }}"
                                        @if(!empty($supplyInfo) && $supplyInfo->OthersSpecial == $other->OthersId)
                                        selected
                                        @endif>{{ $other->Special }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td colspan="3" class="center">
                    <input type="text" class="form-control" name="AppRemark"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->AppRemark }}" @endif>
                </td>

                <td class="center">
                    <select class="form-control" name="QuotObject">
                        <option value="">&nbsp;</option>
                        <option value="대표부(KSdele)"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotObject == "대표부(KSdele)")
                                    selected
                                @endif>대표부(KSdele)</option>
                        <option value="회사(KS)"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotObject == "회사(KS)")
                                selected
                                @endif>회사(KS)</option>
                        <option value="배(Ship)"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotObject == "배(Ship)")
                                selected
                                @endif>배(Ship)</option>
                    </select>
                </td>
                <td class="center">
                    <select class="form-control" name="QuotState">
                        <option value="">&nbsp;</option>
                        <option value="신"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotState == "신")
                                    selected
                                @endif>신</option>
                        <option value="중"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotState == "중")
                                selected
                                @endif>중</option>
                        <option value="재생"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotState == "재생")
                                selected
                                @endif>재생</option>
                        <option value="재충진"
                                @if(!empty($supplyInfo) && $supplyInfo->QuotState == "재충진")
                                selected
                                @endif>재충진</option>
                    </select>
                </td>
                <td colspan="3" class="center">
                    <input type="text" class="form-control" name="QuotRemark"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->QuotRemark }}" @endif>
                </td>

                <td colspan="3" class="center">
                    <input type="text" class="form-control" name="SupplyRemark"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->SupplyRemark }}" @endif>
                </td>

                <td class="center">
                    <input type="text" class="form-control date-picker" name="ReciptDate" data-date-format="yyyy-mm-dd"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->ReciptDate }}" @endif>
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="Supplier"
                            @if(!empty($supplyInfo)) value="{{ $supplyInfo->Supplier }}" @endif>
                </td>
                <td colspan="2" class="center">
                    <input type="text" class="form-control" name="Amount"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->Amount }}" @endif
                           onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="DeliveryAmount"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->DeliveryAmount }}" @endif
                           onchange="calcQuot()">
                </td>
                <td class="center">
                    <input type="text" class="form-control" name="TotalAmount"
                           @if(!empty($supplyInfo)) value="{{ $supplyInfo->TotalAmount }}" @endif
                           onchange="calcQuot()">
                </td>
            </tr>
            </tbody>
        </table>
        <div class="col-md-12" style="text-align: center;">
            @if($supplyId == 0)
                <a class="btn btn-xs btn-info no-radius" onclick="confirmAdd()">
                    <i class="icon-edit">신청</i>
                </a>
            @else
                <a class="btn btn-xs btn-info no-radius" onclick="confirmAdd()">
                    <i class="icon-edit">편집</i>
                </a>
                &nbsp;&nbsp;&nbsp;
                <a class="btn btn-xs btn-danger no-radius" onclick="deleteSupplyInfo()">
                    <i class="icon-trash">삭제</i>
                </a>
            @endif
        </div>
    </form>
</div>