<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<div class="row">
    <div class="col-md-12">
        <div class="text-danger m-1">*必要填写的</div>
        <div class="space-6"></div>
        <form role="form" method="POST"
              action="{{url('shipManage/saveShipGenaralData')}}" enctype="multipart/form-data" id="validation-form">
            <input type="hidden" name="shipId"
                   value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="_tabName" value="#general">
            <div class="col-md-7" style="padding-left: 6px;padding-right: 0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="font-weight: bold">
                        <tbody>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left;width:20%" colspan="2">
                                {{ transShipManager('General.ShipName') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="shipName_Cn" class="form-control" placeholder="中文" style="width:100%" value="@if(isset($shipInfo['shipName_Cn'])){{$shipInfo['shipName_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="shipName_En" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="text-danger">*</span> {{ transShipManager('General.Class') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="Class" class="form-control" style="width:100%" value="@if(isset($shipInfo['Class'])){{$shipInfo['Class']}}@endif" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.RegNo') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="RegNo" class="form-control" style="width:100%" value="@if(isset($shipInfo['RegNo'])){{$shipInfo['RegNo']}}@endif">
                            </td>

                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.RegType') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <select class="form-control chosen-select" id="RegStatus" name="RegStatus">
                                    @if(strcasecmp($shipInfo['RegStatus'], '临时登录(PRO)') == 0)
                                        <option value="临时登录(PRO)" selected>临时登录(PRO)</option>
                                    @else<option value="临时登录(PRO)">临时登录(PRO)</option>
                                    @endif
                                    @if(strcasecmp($shipInfo['RegStatus'], '完全登录(PER)') == 0)
                                        <option value="完全登录(PER)" selected>完全登录(PER)</option>
                                    @else<option value="完全登录(PER)">完全登录(PER)</option>
                                    @endif
                                    @if(strcasecmp($shipInfo['RegStatus'], '删掉登录(DEL)') == 0)
                                        <option value="删掉登录(DEL)" selected>删掉登录(DEL)</option>
                                    @else<option value="删掉登录(DEL)">删掉登录(DEL)</option>
                                    @endif
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.SerialNo') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="SerialNo" class="form-control" style="width:100%" value="@if(isset($shipInfo['SerialNo'])){{$shipInfo['SerialNo']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.CallSign') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="CallSign" class="form-control" style="width:100%" value="@if(isset($shipInfo['CallSign'])){{$shipInfo['CallSign']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.MMSI') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="MMSI" class="form-control" style="width:100%" value="@if(isset($shipInfo['MMSI'])){{$shipInfo['MMSI']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="text-danger">*</span> {{ transShipManager('General.IMO_no') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="IMO_No" class="form-control" style="width:100%" value="@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.INMARSAT') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="INMARSAT" class="form-control" style="width:100%" value="@if(isset($shipInfo['INMARSAT'])){{$shipInfo['INMARSAT']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.OriginalName') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="OriginalShipName" class="form-control" style="width:100%" value="@if(isset($shipInfo['OriginalShipName'])){{$shipInfo['OriginalShipName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.Flag') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="Flag_Cn" class="form-control" placeholder="中文" style="width:100%" value="@if(isset($shipInfo['Flag_Cn'])){{$shipInfo['Flag_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="Flag" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.port of Reg') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="PortOfRegistry_Cn" class="form-control" placeholder="中文" style="width:100%" value="@if(isset($shipInfo['PortOfRegistry_Cn'])){{$shipInfo['PortOfRegistry_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text" style="width: 25%">
                                <input type="text" name="PortOfRegistry" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: center" rowspan="4">
                                {{ transShipManager('General.owner') }}
                            </td>
                            <td style="text-align: right;font-weight: normal;">{{ transShipManager('General.Owner_Cn') }}</td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Owner_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Owner_En" class="form-control" style="width:100%" value="@if(isset($shipInfo['Owner_En'])){{$shipInfo['Owner_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Owner_Address_Cn') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="OwnerAddress_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerAddress_Cn'])){{$shipInfo['OwnerAddress_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="OwnerAddress_En" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerAddress_En'])){{$shipInfo['OwnerAddress_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Tel No') }},{{ transShipManager('General.Fax No') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="OwnerTelnumber" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerTelnumber'])){{$shipInfo['OwnerTelnumber']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="OwnerFax" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerFax'])){{$shipInfo['OwnerFax']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Email Address') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="OwnerEmail" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerEmail'])){{$shipInfo['OwnerEmail']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: center" rowspan="4">
                                {{ transShipManager('General.ISM') }}
                            </td>
                            <td style="text-align: right;font-weight: normal;">{{ transShipManager('General.ISM Company_kn') }}</td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISM_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISM_Cn'])){{$shipInfo['ISM_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISM_En" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISM_En'])){{$shipInfo['ISM_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Address_Cn') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISMAddress_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMAddress_Cn'])){{$shipInfo['ISMAddress_Cn']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISMAddress_En" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMAddress_En'])){{$shipInfo['ISMAddress_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Tel No') }},{{ transShipManager('General.Fax No') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISMTelnumber" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMTelnumber'])){{$shipInfo['ISMTelnumber']}}@endif">
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="ISMFax" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMFax'])){{$shipInfo['ISMFax']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: right">
                                {{ transShipManager('General.Email Address') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="ISMEmail" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMEmail'])){{$shipInfo['ISMEmail']}}@endif">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="table-responsive">
                    <table class="table table-bordered"
                           style="font-weight: bold">
                        <tbody>
                        <tr>
                            <td class="custom-td-label1" colspan="1" style="text-align: left">
                                {{ transShipManager('General.Ship Type') }}
                            </td>
                            <td class="custom-td-dec-text" colspan="3">
                                <select class="chosen-select" id="ShipType" name="ShipType" style="width:70%">
                                    @foreach($shipType as $type)
                                        <option value="{{$type['id']}}" @if($type['id'] == $shipInfo['ShipType']) selected @endif>{{$type['ShipType_Cn']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.GrossTon') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="GrossTon" class="form-control" style="width:100%" value="@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(LOA)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="LOA" class="form-control" style="width:100%" value="@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.NetTon') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="NetTon" class="form-control" style="width:100%" value="@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(LBP)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="LBP" class="form-control" style="width:100%" value="@if(isset($shipInfo['LBP'])){{$shipInfo['LBP']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(DeadWeight)mt') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Deadweight" class="form-control" style="width:100%" value="@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Lconvention)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Length" class="form-control" style="width:100%" value="@if(isset($shipInfo['Length'])){{$shipInfo['Length']}}@endif">
                            </td>                                    </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Displacement)mt') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Displacement" class="form-control" style="width:100%" value="@if(isset($shipInfo['Displacement'])){{$shipInfo['Displacement']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(BM)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="BM" class="form-control" style="width:100%" value="@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Ballast)㎥') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Ballast" class="form-control" style="width:100%" value="@if(isset($shipInfo['Ballast'])){{$shipInfo['Ballast']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(DM)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="DM" class="form-control" style="width:100%" value="@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(FuelBunkers)㎥') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="FuelBunker" class="form-control" style="width:100%" value="@if(isset($shipInfo['FuelBunker'])){{$shipInfo['FuelBunker']}}@endif">
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Draught)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="Draught" class="form-control" style="width:100%" value="@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(ShipBuilder)m') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="ShipBuilder" class="form-control" style="width:100%" value="@if(isset($shipInfo['ShipBuilder'])){{$shipInfo['ShipBuilder']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.BuildDate/Place') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3" style="width: 25%">
                                <input type="text" name="BuildPlace_Cn" class="form-control" placeholder="地方" style="width:100%" value="@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(KeelDate)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="KeelDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['KeelDate'])){{$shipInfo['KeelDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Bridge)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="DeckErection_B" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_B'])){{$shipInfo['DeckErection_B']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(LaunchDate)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="LaunchDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['LaunchDate'])){{$shipInfo['LaunchDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Forecastle)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="DeckErection_F" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_F'])){{$shipInfo['DeckErection_F']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(DeliveryDate)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="DeliveryDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['DeliveryDate'])){{$shipInfo['DeliveryDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Poop)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="DeckErection_P" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_P'])){{$shipInfo['DeckErection_P']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(ConversionDate)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="ConversionDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['ConversionDate'])){{$shipInfo['ConversionDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.(Deckhouse)m') }}
                            </td>
                            <td class="custom-td-report-text">
                                <input type="text" name="DeckErection_H" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="table-responsive">
                    <table id="sample-table-1"
                           class="table table-bordered"
                           style="font-weight: bold">
                        <tbody>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.Registeration Date') }}
                            </td>
                            <td class="custom-td-dec-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="RegDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['RegDate'])){{$shipInfo['RegDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.Renewal Date') }}
                            </td>
                            <td class="custom-td-dec-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="RenewDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['RenewDate'])){{$shipInfo['RenewDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.Expiry Date') }}
                            </td>
                            <td class="custom-td-dec-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="KCExpiryDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['KCExpiryDate'])){{$shipInfo['KCExpiryDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left;white-space: nowrap">
                                {{ transShipManager('General.Conditional Date') }}
                            </td>
                            <td class="custom-td-dec-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="ConditionalDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['ConditionalDate'])){{$shipInfo['ConditionalDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left">
                                {{ transShipManager('General.Deletion Date') }}
                            </td>
                            <td class="custom-td-dec-text">
                                <div class="input-group">
                                    <input class="form-control date-picker"
                                           name="DelDate"
                                           type="text" data-date-format="yyyy-mm-dd"
                                           value="@if(isset($shipInfo['DelDate'])){{$shipInfo['DelDate']}}@endif">
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="col-md-12">
                <div class="row">
                    @if(!$isHolder)
                        <div class="col-md-6">
                            <button type="submit" id="btnRegister" class="btn btn-sm btn-inverse" style="float: right; width :80px;">

                                @if(isset($shipInfo['id']))
                                    <i class="icon-edit"></i>修改
                                @else
                                    <i class="icon-save"></i>登录
                                @endif

                            </button>
                        </div>
                    @endif
                    <div class="col-md-6 d-none">
                        <button type="button" class="btn btn-sm btn-inverse" style="float: left; width: 80px" onclick="onCancel()">
                            <i class="icon-remove"></i>取消</button>
                    </div>
                    <div class="space-4"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="space-6"></div>
<script type="text/javascript">
    let submitted = false;

    $('form').submit(function() {
        submitted = null;
    });

    window.onbeforeunload = function() {
        return submitted;
    };
    function onCancel() {
        submitted = null;
        history.back();
    }

</script>
