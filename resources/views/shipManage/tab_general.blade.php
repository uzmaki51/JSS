<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
<style>
    .table tbody > tr > td {
        font-size: 12px!important;
        padding: 0px;
    }
    .table tbody > tr > .custom-td-label1 {
        padding: 2px!important;
        border: unset!important;
        width: 150px!important;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: block;
    }
    .first-input {
        width:48%;
        display: inline-block!important;
    }
    .second-input {
        width: 50%;
        display: inline-block!important;
    }
    .two-input {
        padding: 0!important;
        width: 50%;
    }
    .table tbody > tr > .custom-td-label1:after {
        content: '........................................................................................................';
        overflow: hidden;
        width: 165px;
    }
    .table .keel-date {
        width: 40% !important;
    }
    .table tbody > tr > .custom-td-report-text, .table tbody > tr > .custom-td-dec-text {
        padding: 0!important;
        width: 80%;
    }

    .form-control {
        padding: 0 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .chosen-single {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .input-group-addon {
        font-size: 12px!important;
        padding: 0 4px!important;
        border: unset!important;
    }
    .general tr >td:first-child {
        width: 20%;
    }
    .general {
        border: unset!important;
    }
    .sub-title {
        padding-left: 20px;
    }
    .small-title {
        text-align: right!important;
        padding: 0 4px 0 0!important;
        font-weight: normal;
        border: unset!important;
    }

</style>
<div class="row">
    <div class="col-md-12">
        <div class="space-6"></div>
        <form role="form" method="POST"
              action="{{url('shipManage/saveShipGenaralData')}}" enctype="multipart/form-data" id="general-form">
            <input type="hidden" name="shipId"
                   value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="_tabName" value="#general">
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered general" style="font-weight: bold">
                        <tbody>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" colspan="2">
                                {{ transShipManager('General.ShipName') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 80%" colspan="2">
                                <input type="text" name="shipName_Cn" class="form-control" placeholder="中文" style="width:100%" value="@if(isset($shipInfo['shipName_Cn'])){{$shipInfo['shipName_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" colspan="2">
                                {{ transShipManager('General.ShipName_Cn') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 80%" colspan="2">
                                <input type="text" name="shipName_En" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="no-padding custom-td-label1" style="text-align: left;width:20%" colspan="2">
                                {{ transShipManager('General.NickName') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 80%" colspan="2">
                                <input type="text" name="NickName" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['NickName'])){{$shipInfo['NickName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="text-danger">{{ transShipManager('General.Class') }}*</span>
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <select type="text" name="Class" class="form-control d-in-block" style="width:48%;border-right: 1px solid #cccccc!important;"  required>
                                    <option value="CLASS A" {{ isset($shipInfo['Class']) && $shipInfo['Class'] == "CLASS A" ? 'selected' : ''}}>CLASS A</option>
                                    <option value="CLASS B" {{ isset($shipInfo['Class']) && $shipInfo['Class'] == "CLASS B" ? 'selected' : ''}}>CLASS B</option>
                                </select>
                                <input type="text" name="IMO_No" class="form-control d-in-block" style="width:50%" value="@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif" required minlength="7" maxlength="7">
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
                                {{ transShipManager('General.OfficeNo') }}
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
                                <select class="form-control" id="RegStatus" name="RegStatus">
                                    @if(strcasecmp($shipInfo['RegStatus'], '临时登记(PRO)') == 0)
                                        <option value="临时登记(PRO)" selected>临时登记(PRO)</option>
                                    @else<option value="临时登记(PRO)">临时登记(PRO)</option>
                                    @endif
                                    @if(strcasecmp($shipInfo['RegStatus'], '完全登记(PER)') == 0)
                                        <option value="完全登记(PER)" selected>完全登记(PER)</option>
                                    @else<option value="完全登记(PER)">完全登记(PER)</option>
                                    @endif
                                    @if(strcasecmp($shipInfo['RegStatus'], '删掉登记(DEL)') == 0)
                                        <option value="删掉登记(DEL)" selected>删掉登记(DEL)</option>
                                    @else<option value="删掉登记(DEL)">删掉登记(DEL)</option>
                                    @endif
                                </select>
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
                                {{ transShipManager('General.OriginalName') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="OriginalShipName" class="form-control" style="width:100%" value="@if(isset($shipInfo['OriginalShipName'])){{$shipInfo['OriginalShipName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="sub-title">{{ transShipManager('General.FormerName1') }}</span>
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="FormerShipName" class="form-control" style="width:100%" value="@if(isset($shipInfo['FormerShipName'])){{$shipInfo['FormerShipName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="sub-title">{{ transShipManager('General.FormerName2') }}</span>
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="SecondFormerShipName" class="form-control" style="width:100%" value="@if(isset($shipInfo['SecondFormerShipName'])){{$shipInfo['SecondFormerShipName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                <span class="sub-title">{{ transShipManager('General.FormerName3') }}</span>
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="ThirdFormerShipName" class="form-control" style="width:100%" value="@if(isset($shipInfo['ThirdFormerShipName'])){{$shipInfo['ThirdFormerShipName']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.Flag') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%" colspan="2">
                                <input type="text" name="Flag" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: left" colspan="2">
                                {{ transShipManager('General.port of Reg') }}
                            </td>
                            <td class="custom-td-report-text" style="width: 25%" colspan="2">
                                <input type="text" name="PortOfRegistry" class="form-control" placeholder="英文" style="width:100%" value="@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" style="text-align: center" rowspan="4">
                                {{ transShipManager('General.owner') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="Owner_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('General.Owner_Address_Cn') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="OwnerAddress_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerAddress_Cn'])){{$shipInfo['OwnerAddress_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('General.Tel No') }} / {{ transShipManager('General.Fax No') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="OwnerTelnumber" class="form-control first-input" value="@if(isset($shipInfo['OwnerTelnumber'])){{$shipInfo['OwnerTelnumber']}}@endif" style="border-right: 1px solid #cccccc!important;">
                                <input type="text" name="OwnerFax" class="form-control second-input" value="@if(isset($shipInfo['OwnerFax'])){{$shipInfo['OwnerFax']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('General.Email Address') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="email" name="OwnerEmail" class="form-control" style="width:100%" value="@if(isset($shipInfo['OwnerEmail'])){{$shipInfo['OwnerEmail']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-td-label1" rowspan="4">
                                {{ transShipManager('General.ISM') }}
                            </td>
                            <td class="custom-td-report-text" colspan="3">
                                <input type="text" name="ISM_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISM_Cn'])){{$shipInfo['ISM_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('General.Address_Cn') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="ISMAddress_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['ISMAddress_Cn'])){{$shipInfo['ISMAddress_Cn']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
                                {{ transShipManager('General.Tel No') }} / {{ transShipManager('General.Fax No') }}
                            </td>
                            <td class="custom-td-report-text" colspan="2">
                                <input type="text" name="ISMTelnumber" class="form-control first-input" style="border-right: 1px solid #cccccc!important;" value="@if(isset($shipInfo['ISMTelnumber'])){{$shipInfo['ISMTelnumber']}}@endif">
                                <input type="text" name="ISMFax" class="form-control second-input" value="@if(isset($shipInfo['ISMFax'])){{$shipInfo['ISMFax']}}@endif">
                            </td>
                        </tr>
                        <tr>
                            <td class="small-title">
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
            <div class="col-md-8">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered general" style="font-weight: bold">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" colspan="1" style="text-align: left; width: 20%;">
                                        {{ transShipManager('General.Ship Type') }}
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <select class="form-control" id="ShipType" name="ShipType">
                                            @foreach($shipType as $type)
                                                <option value="{{$type['id']}}" @if($type['id'] == $shipInfo['ShipType']) selected @endif>{{$type['ShipType_Cn']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
                                    <span class="text-pink">{{ transShipManager('General.Hull') }}</span>
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Hull" class="form-control" style="width:100%" value="@if(isset($shipInfo['Hull'])){{$shipInfo['Hull']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small-title">
                                        {{ transShipManager('General.Notation') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <textarea type="text" name="HullNotation" class="form-control" rows="2">{{ isset($shipInfo['HullNotation']) ? $shipInfo['HullNotation'] : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">
                                        <span class="text-pink">{{ transShipManager('General.Machinery') }}</span>
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Machinery" class="form-control" style="width:100%" value="@if(isset($shipInfo['Machinery'])){{$shipInfo['Machinery']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small-title">
                                        {{ transShipManager('General.Notation') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <textarea type="text" name="MachineryNotation" class="form-control" rows="2">{{ isset($shipInfo['MachineryNotation']) ? $shipInfo['MachineryNotation'] : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
                                        <span class="text-pink">{{ transShipManager('General.Refrigerater') }}</span>
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Refrigerater" class="form-control" style="width:100%" value="@if(isset($shipInfo['Refrigerater'])){{$shipInfo['Refrigerater']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small-title">
                                        {{ transShipManager('General.Notation') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <textarea type="text" name="RefrigeraterNotation" class="form-control" rows="2">{{ isset($shipInfo['RefrigeraterNotation']) ? $shipInfo['RefrigeraterNotation'] : '' }}</textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-bordered general">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 40%;">
                                        {{ transShipManager('General.Registeration Date') }}
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="RegDate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($shipInfo['RegDate'])){{$shipInfo['RegDate']}}@endif">
                                            <span class="input-group-addon">
                                                    <i class="icon-calendar "></i>
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
                                                    <i class="icon-calendar "></i>
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
                                                    <i class="icon-calendar "></i>
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
                                                    <i class="icon-calendar "></i>
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
                                                    <i class="icon-calendar "></i>
                                                </span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table table-bordered general">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
                                        {{ transShipManager('General.GrossTon') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="GrossTon" class="form-control" style="width:100%" value="@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.NetTon') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="NetTon" class="form-control" style="width:100%" value="@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(DeadWeight)mt') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Deadweight" class="form-control" style="width:100%" value="@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Displacement)mt') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Displacement" class="form-control" style="width:100%" value="@if(isset($shipInfo['Displacement'])){{$shipInfo['Displacement']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Ballast)㎥') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Ballast" class="form-control" style="width:100%" value="@if(isset($shipInfo['Ballast'])){{$shipInfo['Ballast']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(FuelBunkers)㎥') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="FuelBunker" class="form-control" style="width:100%" value="@if(isset($shipInfo['FuelBunker'])){{$shipInfo['FuelBunker']}}@endif">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table table-bordered general">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
                                        {{ transShipManager('General.(LOA)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="LOA" class="form-control" style="width:100%" value="@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(LBP)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="LBP" class="form-control" style="width:100%" value="@if(isset($shipInfo['LBP'])){{$shipInfo['LBP']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Lconvention)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Length" class="form-control" style="width:100%" value="@if(isset($shipInfo['Length'])){{$shipInfo['Length']}}@endif">
                                    </td>                                    </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(BM)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="BM" class="form-control" style="width:100%" value="@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(DM)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="DM" class="form-control" style="width:100%" value="@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Draught)m') }}
                                    </td>
                                    <td class="custom-td-report-text">
                                        <input type="text" name="Draught" class="form-control" style="width:100%" value="@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div class="table-responsive">
                            <table class="table table-bordered general"
                                   style="font-weight: bold">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
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
                                    <td class="custom-td-report-text" colspan="3">
                                        <input type="text" name="BuildPlace_Cn" class="form-control" style="width:100%" value="@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(KeelDate)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <div class="input-group">
                                            <input class="form-control date-picker"
                                                   name="KeelDate"
                                                   type="text" data-date-format="yyyy-mm-dd"
                                                   value="@if(isset($shipInfo['KeelDate'])){{$shipInfo['KeelDate']}}@endif">
                                            <span class="input-group-addon">
                                        <i class="icon-calendar "></i>
                                    </span>
                                        </div>
                                    </td>
                                    <td class="custom-td-label1" style="text-align: left; width: 20%;">
                                        {{ transShipManager('General.(Bridge)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <input type="text" name="DeckErection_B" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_B'])){{$shipInfo['DeckErection_B']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(LaunchDate)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <div class="input-group">
                                            <input class="form-control date-picker"
                                                   name="LaunchDate"
                                                   type="text" data-date-format="yyyy-mm-dd"
                                                   value="@if(isset($shipInfo['LaunchDate'])){{$shipInfo['LaunchDate']}}@endif">
                                            <span class="input-group-addon">
                                        <i class="icon-calendar "></i>
                                    </span>
                                        </div>
                                    </td>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Forecastle)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <input type="text" name="DeckErection_F" class="form-control" style="width:100%" value="@if(isset($shipInfo['DeckErection_F'])){{$shipInfo['DeckErection_F']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(DeliveryDate)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <div class="input-group">
                                            <input class="form-control date-picker"
                                                   name="DeliveryDate"
                                                   type="text" data-date-format="yyyy-mm-dd"
                                                   value="@if(isset($shipInfo['DeliveryDate'])){{$shipInfo['DeliveryDate']}}@endif">
                                            <span class="input-group-addon">
                                        <i class="icon-calendar "></i>
                                    </span>
                                        </div>
                                    </td>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Poop)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <input type="text" name="DeckErection_P" class="form-control" value="@if(isset($shipInfo['DeckErection_P'])){{$shipInfo['DeckErection_P']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(ConversionDate)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <div class="input-group">
                                            <input class="form-control date-picker"
                                                   name="ConversionDate"
                                                   type="text" data-date-format="yyyy-mm-dd"
                                                   value="@if(isset($shipInfo['ConversionDate'])){{$shipInfo['ConversionDate']}}@endif">
                                            <span class="input-group-addon">
                                        <i class="icon-calendar "></i>
                                    </span>
                                        </div>
                                    </td>
                                    <td class="custom-td-label1" style="text-align: left">
                                        {{ transShipManager('General.(Deckhouse)m') }}
                                    </td>
                                    <td class="custom-td-report-text keel-date">
                                        <input type="text" name="DeckErection_H" class="form-control" value="@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="space-4"></div>