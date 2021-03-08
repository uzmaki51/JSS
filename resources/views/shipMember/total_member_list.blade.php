.<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>

<?php
    $isHolder = Session::get('IS_HOLDER');
?>


@extends('layout.'.$header)

@section('content')

    @if(!isset($excel))

        <div class="main-content">
            <style>
                table>tbody>tr>td {
                    padding-top: 8px !important;
                    padding-bottom: 8px !important;
                }
                .chosen-drop {
                    width: 180px !important;
                }
            </style>
            <div class="page-content">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4>
                            <b>{{transShipMember("title.Member List")}}</b>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="input-group col-md-10">
                            <div class="col-sm-3 form-group" style="padding: 0">
                                <label style="float:left;padding:5px 0">{{transShipMember("totalShipMember.ShipName(Seafarer`s passport)")}}:</label>
                                <div class="col-md-8" style="width: 100px;">
                                    <select class="form-control chosen-select" id="ship_name">
                                        <option value="">{{transShipMember("captions.total")}}</option>
                                        @foreach($shipList as $ship)
                                            <option value="{{$ship['RegNo']}}" @if(isset($regShip) && ($regShip == $ship['RegNo'])) selected @endif>
                                                @if(!empty($ship['shipName_Cn']) && !empty($ship['name'])){{$ship['shipName_Cn']}} | @endif{{$ship['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 form-group" style="padding: 0;">
                                <label style="float:left;padding:5px 0">{{transShipMember("totalShipMember.ShipName(Sign On)")}}:</label>
                                <div class="col-md-8" style="width: 100px;">
                                    <select class="form-control chosen-select" id="ship_book">
                                        <option value="">{{transShipMember("captions.total")}}</option>
                                        @foreach($shipList1 as $ship)
                                            <option value="{{$ship['RegNo']}}" @if(isset($bookShip) && ($bookShip == $ship['RegNo'])) selected @endif>{{$ship['shipName_Cn']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 form-group" style="padding: 0;">
                                <label style="float:left;padding:5px 0">{{transShipMember("totalShipMember.ShipName(Structure)")}}:</label>
                                <div class="col-md-8" style="width: 100px;">
                                    <select class="form-control chosen-select" id="ship_orig">
                                        <option value="">{{transShipMember("captions.total")}}</option>
                                        @foreach($ko_ship_list as $ship)
                                            <option value="{{$ship['id']}}" @if(isset($origShip) && ($origShip == $ship['id'])) selected @endif>{{$ship['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 form-group" style="padding: 0;">
                                <label style="float:left;padding:5px 0">{{transShipMember("totalShipMember.Registration Status")}}:</label>
                                <div class="col-md-8" style="width: 100px;">
                                    <select class="form-control chosen-select" id="reg_status">
                                        <option value="">{{transShipMember("captions.total")}}</option>
                                        <option value="2" @if(isset($regStatus) && $regStatus == 2) selected @endif>{{transShipMember("captions.register")}}</option>
                                        <option value="1" @if(isset($regStatus) && $regStatus == 1) selected @endif>{{transShipMember("captions.dismiss")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="padding:0; float: right">
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-primary no-radius" onclick="showSearchByKeyboard()" style="margin-left: 1px; width: 70px">
                                <i class="icon-search"></i>
                                {{transShipMember("captions.search")}}
                            </button>

                            <button class="btn btn-sm btn-warning" onclick="showSearchByKeyboardExcel()"
                                    style="margin-left: 1px; width :70px">
                                <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                            </button>

                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div style="overflow-y: scroll; width: 100%">
                            @else
                                @include('layout.excel-style')
                            @endif
                            <table class="table table-bordered table-striped table-hover arc-std-table">
                                <thead>
                                <tr class="black br-hblue">
                                    <th colspan="10"></th>
                                    <th class="center" colspan="2">{{transShipMember("totalShipMember.Sign On/Off")}}</th>
                                    <th class="center" colspan="3">{{transShipMember("totalShipMember.Seafarer`s passport")}}</th>
                                    <th class="center" colspan="1">{{transShipMember("totalShipMember.Structure")}}</th>
                                    <th></th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th class="center" rowspan="2" style="width:2%">{{transShipMember("totalShipMember.No")}}</th>
                                    <th class="center" rowspan="2" style="width:5%">{{transShipMember("totalShipMember.Name")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Birthday")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Party")}}</th>
                                    <th class="center" style="width:4%">{{transShipMember("totalShipMember.Class")}}</th>
                                    <th class="center" style="width:4%">{{transShipMember("totalShipMember.Sociality")}}</th>
                                    <th class="center" style="width:14%">{{transShipMember("totalShipMember.Birthplace")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.HomePhone")}}</th>
                                    <th class="center" colspan="2" style="width:8%">{{transShipMember("totalShipMember.CitizenShip Card NO")}}</th>
                                    <th class="center" colspan="2" style="width:10%">{{transShipMember("totalShipMember.ShipName")}}</th>
                                    <th class="center" rowspan="2" style="width:6%">{{transShipMember("totalShipMember.Passport No")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.ShipName")}}</th>
                                    <th class="center" style="width:6%">{{transShipMember("totalShipMember.Issue")}}</th>
                                    <th class="center" style="width:6%">{{transShipMember("totalShipMember.ShipName")}}</th>
                                    <th class="center" rowspan="2" style="width:6%;">{{transShipMember("totalShipMember.Dismissal Date")}}</th>
                                </tr>
                                <tr class="black br-hblue">
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Sex")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Standing Date")}}</th>
                                    <th class="center" colspan="2" style="width:8%">{{transShipMember("totalShipMember.Employment Date")}}</th>
                                    <th class="center" style="width:14%">{{transShipMember("totalShipMember.Address")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Mobile Phone")}}</th>
                                    <th class="center" style="width:4%">{{transShipMember("totalShipMember.Tall")}}</th>
                                    <th class="center" style="width:4%">{{transShipMember("totalShipMember.Blood Type")}}</th>
                                    <th class="center" style="width:6%">{{transShipMember("totalShipMember.Duty")}}</th>
                                    <th class="center" style="width:4%">{{transShipMember("totalShipMember.On/Off")}}</th>
                                    <th class="center" style="width:7%">{{transShipMember("totalShipMember.Duty")}}</th>
                                    <th class="center" style="width:6%">{{transShipMember("totalShipMember.Expiry Date")}}</th>
                                    <th class="center" style="width:6%">{{transShipMember("totalShipMember.Duty")}}</th>
                                </tr>
                                </thead>
                                @if(!isset($excel))
                            </table>
                        </div>
                        <div id="div_contents" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:67vh; border-bottom: 1px solid #eee">
                            <table class="table table-bordered table-striped table-hover">
                                @endif
                                <tbody>
								<?php $index = 1; ?>
                                @foreach($list as $member)
                                    <tr>
                                        <td class="center" rowspan="2" style="width:2%;word-break: break-all;">{{$index++}}</td>
                                        <td class="center" rowspan="2" style="width:5%;word-break: break-all;"><a href='registerShipMember?memberId={{$member['id']}}'>{{$member['realname']}}</a></td>
                                        <td class="center" style="width:7%;word-break: break-all;">{{convert_date($member['birthday'])}}</td>
                                        <td class="center" style="width:7%;word-break: break-all;">@if($member['isParty'] == 1) '' @endif</td>
                                        <td class="center" style="width:4%;word-break: break-all;">{{$member['fromOrigin']}}</td>
                                        <td class="center" style="width:4%;word-break: break-all;">{{$member['currOrigin']}}</td>
                                        <td class="center" style="width:14%;word-break: break-all;">{{$member['BirthPlace']}}</td>
                                        <td class="center" style="width:7%; word-break: break-all;">{{$member['tel']}}</td>
                                        <td class="center" colspan="2" style="width:8%;word-break: break-all;">{{$member['cardNum']}}</td>
                                        <td class="center" colspan="2" style="width:10%;word-break: break-all;">{{$member['shipName_Cn']}}</td>
                                        <td class="center" rowspan="2" style="width:6%;word-break: break-all;">{{$member['crewNum']}}</td>
                                        <td class="center" style="width:7%;word-break: break-all;">{{$member['book_ship']}}</td>
                                        <td class="center" style="width:6%;word-break: break-all;">{{convert_date($member['IssuedDate'])}}</td>
                                        <td class="center" style="width:6%;word-break: break-all;">{{$member['origin_ship']}}</td>
                                        <td class="center" rowspan="2" style="width: 6%;word-break: break-all;">{{convert_date($member['DelDate'])}}</td>
                                    </tr>
                                    <tr>
                                        <td class="center" style="width:7%;word-break: break-all;">@if($member['Sex'] ==0){{transShipMember("captions.male")}} @else {{transShipMember("captions.female")}} @endif</td>
                                        <td class="center" style="width:7%;word-break: break-all;">{{convert_date($member['partyDate'])}}</td>
                                        <td class="center" colspan="2" style="width:8%;word-break: break-all;">{{convert_date($member['entryDate'])}}</td>
                                        <td class="center" style="width:14%;word-break: break-all;">{{$member['address']}}</td>
                                        <td class="center" style="width:7%; word-break: break-all;">{{$member['phone']}}</td>
                                        <td class="center" style="width:4%;word-break: break-all;">{{$member['Height']}}</td>
                                        <td class="center" style="width:4%;word-break: break-all;">{{$member['BloodType']}}</td>
                                        <td class="center" style="width:6%;word-break: break-all;">{{$member['Duty']}}</td>
                                        <td class="center" style="width:4%;word-break: break-all;">{{$member['sign_on_off']}}</td>
                                        <td class="center" style="width:7%;word-break: break-all;">{{$member['book_duty']}}</td>
                                        <td class="center" style="width:6%;word-break: break-all;">{{convert_date($member['ExpiryDate'])}}</td>
                                        <td class="center" style="width:6%;word-break: break-all;">{{$member['orgin_duty']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if(!isset($excel))
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var token = '{!! csrf_token() !!}';

            $(document).ready(function () {
            });

            $('.init-btn').on('click', function() {

                location.href = 'totalShipMember';
            });

            function showSearchByKeyboard() {
                var regShip = $("#ship_name").val();
                var bookShip = $("#ship_book").val();
                var origShip = $("#ship_orig").val();
                var regStatus = $("#reg_status").val();

                var urlParam = '';
                if(regShip.length > 0)
                    urlParam += '?regShip=' + regShip;

                if(bookShip.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&'
                    urlParam += 'bookShip=' + bookShip;
                }

                if(origShip.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&'
                    urlParam += 'origShip=' + origShip;
                }

                if(regStatus.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&';
                    urlParam += 'regStatus=' + regStatus;
                }

                location.href = 'totalShipMember' + urlParam;
            }

            function showSearchByKeyboardExcel() {
                var regShip = $("#ship_name").val();
                var bookShip = $("#ship_book").val();
                var origShip = $("#ship_orig").val();
                var regStatus = $("#reg_status").val();

                var urlParam = '';
                if(regShip.length > 0)
                    urlParam += '?regShip=' + regShip;

                if(bookShip.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&'
                    urlParam += 'bookShip=' + bookShip;
                }

                if(origShip.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&'
                    urlParam += 'origShip=' + origShip;
                }

                if(regStatus.length > 0) {
                    urlParam += (urlParam.length < 1) ? '?' : '&';
                    urlParam += 'regStatus=' + regStatus;
                }

                location.href = 'totalShipMemberExcel' + urlParam;
            }

        </script>
    @endif
@endsection
