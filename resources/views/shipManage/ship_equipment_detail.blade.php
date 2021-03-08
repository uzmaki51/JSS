<div class="modal-dialog" style="width:90%;padding-top:6%">
    <div class="modal-content">
        <div class="modal-header no-padding">
            <div class="table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="white">&times;</span>
                </button>
                「{{ $equipName }}」的履历
            </div>
        </div>
        <div class="modal-body no-padding">
            <div class="col-md-12 no-padding" style="background:white">
                <div class="col-md-6 property-table">
                    <label style="padding-top: 7px;padding-bottom: 4px">{{ transShipManager('EquipmentDetail.supply_history') }}</label>
                    <div class="col-md-12" style="overflow-y: scroll; width: 100%">
                        <table class="arc-std-table table table-bordered" style="font-size: 12px" id="equipement_table_body">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" rowspan="2" style="width:5%">No</th>
                                <th class="center" rowspan="2" style="width:15%">{{transShipManager("EquipmentManage.supplied_at")}}</th>
                                <th class="center" style="width:35%">{{transShipManager("EquipmentManage.Equipment_Cn")}}</th>
                                <th class="center" rowspan="2" style="width:8%">{{ transShipManager("EquipmentTypeManage.type")}}</th>
                                <th class="center" rowspan="2" style="width:12%">{{transShipManager("EquipmentManage.IssaCode")}}</th>
                                <th class="center" rowspan="2" style="width:15%">{{transShipManager("EquipmentManage.Qty")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{transShipManager("EquipmentManage.unit")}}</th>
                            </tr>
                            <tr class="black br-hblue">
                                <th class="center" style="width:15%">{{ transShipManager("EquipmentManage.Equipment_en")}} </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-12" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:25vh;">
                        <table class="table table-bordered table-striped" style="border-top:0">
                            <tbody id="register_table">
							<?php
							    $index = 1;
							?>
                            @if(isset($registeredList) && count($registeredList) > 0)
                                @foreach($registeredList as $equipment)
                                    <tr data-index-1="{{ $equipment['id'] }}">
                                        <td class="center" rowspan="2" style="width:5%">{{$index++}}</td>
                                        <td class="center" rowspan="2" style="width:15%">
                                            {{ isset($equipment['supplied_at']) ? _convertDateFormat($equipment['supplied_at'], 'Y-m-d') : transShipManager("EquipmentManage.not_registered") }}
                                        </td>
                                        <td class="center" style="width:35%">{{$equipment['Euipment_Cn']}}</td>
                                        <td class="center" rowspan="2" style="width:8%">{{ isset($kindLabelList[$equipment['KindOfEuipmentId']]) ? $kindLabelList[$equipment['KindOfEuipmentId']] : '未定' }}</td>
                                        <td class="center" rowspan="2" style="width: 12%;">{{$equipment['IssaCodeNo']}}</td>
                                        <td class="center" rowspan="2" style="width:15%"><span class="badge badge-primary">{{ $equipment['Qty'] }}</span></td>
                                        <td class="center" rowspan="2" style="width:10%">{{ $equipment['Unit'] }}</td>
                                    </tr>
                                    <tr data-index-2="{{ $equipment['id'] }}">
                                        <td>{{$equipment['Euipment_En']}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        {{ trans('common.message.no_data') }}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="col-md-6 part-table">
                    <label style="padding-top: 7px;padding-bottom: 4px">{{ transShipManager('EquipmentDetail.diligence_history') }}</label>

                    <div class="col-md-12" style="overflow-y: scroll; width: 100%">

                        <table class="arc-std-table table table-bordered" style="font-size: 12px" id="equipement_table_body">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" rowspan="2" style="width:5%">No</th>
                                <th class="center" rowspan="2" style="width:15%">{{transShipManager("EquipmentManage.diligence_at")}}</th>
                                <th class="center" style="width:30%">{{transShipManager("EquipmentManage.Equipment_Cn")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{transShipManager("EquipmentManage.IssaCode")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{ transShipManager("EquipmentTypeManage.type")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{transShipManager("EquipmentManage.remain_count")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{transShipManager("EquipmentManage.unit")}}</th>
                                <th class="center" rowspan="2" style="width:10%">{{transShipManager("EquipmentManage.status")}}</th>
                            </tr>
                            <tr class="black br-hblue">
                                <th class="center" style="width:15%">{{ transShipManager("EquipmentManage.Equipment_en")}} </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-12" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:25vh;">
                        <table class="table table-bordered table-striped table-hover" style="border-top:0">

                            <tbody id="device_table">
							<?php
							    $index = 1;
							?>
                            @if(isset($diligenceList) && count($diligenceList) > 0)
                                @foreach($diligenceList as $equipment)
                                    <tr diligence-index-1="{{ $equipment['id'] }}">
                                        <td class="center" rowspan="2" style="width:5%">{{$index++}}</td>
                                        <td class="center" rowspan="2" style="width:15%">{{ _convertDateFormat($equipment['diligence_at'], 'Y-m-d') }}</td>
                                        <td class="center" style="width:30%"><a href="javascript:showEquepmentDetail('{{$equipment['id']}}', 1)">{{$equipment['Euipment_Cn']}}</a></td>
                                        <td class="center" rowspan="2" style="width: 10%;">{{$equipment['IssaCodeNo']}}</td>
                                        <td class="center" rowspan="2" style="width: 10%">{{ isset($kindLabelList[$equipment['KindOfEuipmentId']]) ? $kindLabelList[$equipment['KindOfEuipmentId']] : '未定' }}</td>
                                        <td class="center" rowspan="2" style="width: 10%"><span class="badge badge-primary">{{ $equipment['remain_count'] }}</span></td>
                                        <td class="center" rowspan="2" style="width: 10%">{{ $equipment['Unit'] }}</td>
                                        <td class="center" rowspan="2" style="width: 10%"><span class="badge badge-{{ g_enum('InventoryStatusData')[$equipment['Status']][1] }}">{{ g_enum('InventoryStatusData')[$equipment['Status']][0] }}</span></td>
                                    </tr>
                                    <tr diligence-index-2="{{ $equipment['id'] }}">
                                        <td>{{$equipment['Euipment_En']}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        {{ trans('common.message.no_data') }}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>