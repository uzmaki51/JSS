@extends('layout.sidebar')
@extends('layout.header')
<?php
    $isHolder = Session::get('IS_HOLDER');
$isHolder = Session::get('IS_HOLDER');

?>


@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
@endsection









@section('content')
<div class="main-content">
    <style>
        .filter_cell {
            background-color: #45f7ef;
        }
        .filter_show_cell {
            background-color: #4af780;
        }
        .red {
            color: red;
        }
        .chosen-drop {
            width: 300px !important;
        }
    </style>
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-3">
                <h4>
                    <b>{{transShipMember("title.Member Cert")}}</b>
                </h4>
    <div class="main-content">
        <style>
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>{{transShipMember("title.Member Cert")}}</b>
                    </h4>
                </div>








            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <label style="float:left;padding:5px 0">{{transShipMember("memberCertList.ShipName")}}:</label>
                    <div class="col-md-7" style="padding-left:5px;float:left;">
                        <select class="form-control" id="sel_ship">
                            <option value=""></option>
                            @foreach($shipList as $ship)
                                <option value="{{$ship['RegNo']}}" @if(isset($shipId) && ($shipId == $ship['RegNo'])) selected @endif>@if(!empty($ship['name'])){{$ship['name']}} | @endif{{$ship['shipName_Cn']}}</option>
                            @endforeach
                        </select>
                    </div>
            <div class="row col-md-12" style="margin-bottom: 4px;">
                <div class="col-md-6">
                    <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                    <select class="custom-select d-inline-block" id="select-ship" style="max-width: 100px;">
                        <option value="" selected></option>
                        @foreach($shipList as $ship)
                            <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}</option>
                        @endforeach
                    </select>
                    <strong class="f-right" style="font-size: 16px; padding-top: 6px;align-content: flex-end;display: flex;"><span id="ship_name" class="list-header"></span> CREW CERTIFICATES LIST</strong>







                </div>
                <div class="col-md-2">
                    <label style="float:left;padding:5px 0">{{transShipMember("memberCertList.Duty")}}:</label>
                    <div class="col-md-7" style="padding-left:5px;float:left;">
                        <select class="form-control" id="sel_pos">
                            <option value=""></option>
                            @foreach($posList as $pos)
                                <option value="{{$pos['id']}}" @if(isset($posId) && ($posId == $pos['id'])) selected @endif>{{$pos['Duty']}} | {{$pos['Duty_En']}}</option>
                            @endforeach
                        </select>

                </div>
                <div class="col-md-6">
                    <div class="f-right">
                        <label class="font-bold">提前:</label>
                        <!--input type="number" min="0" step="1" class="text-center" style="width: 60px;" name="expire_date" id="expire-date" value="0"-->
                        <select id="expire-date" style="width: 60px;">
                            <option value="0" selected>All</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="180">180</option>
                        </select>


                        <label>天</label>
                        <!--button class="btn btn-report-search btn-sm search-btn" onclick="" id="btn-search"><i class="icon-search"></i>搜索</button-->
                        <!--a class="btn btn-sm btn-danger refresh-btn-over" type="button" onclick="javascript:refresh()">
                            <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                        </a-->
                        <button class="btn btn-warning btn-sm excel-btn" onclick=""><i class="icon-table"></i>{{ trans('common.label.excel') }}</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label style="float:left;padding:5px 0">{{transShipMember("memberCertList.Capacity")}}:</label>
                    <div class="col-md-7" style="padding-left:5px;float:left;">
                        <select class="form-control chosen-select" id="sel_capacity">
                            <option value="">&nbsp;</option>
                            @foreach($capacityList as $capacity)
                                <option value="{{$capacity['id']}}" @if(isset($capacityId) && ($capacityId == $capacity['id'])) selected @endif>{{$capacity['Capacity']}}</option>
                            @endforeach
                        </select>
                    </div>












            </div>
            <div class="" style="margin-top:8px;">
                <div id="item-manage-dialog" class="hide"></div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div>
                    <table id="table-shipmember-list" class="custom-table-striped">
                        <thead>
                        <tr class="black br-hblue" style="height:45px;">
                            <th class="text-center style-header" style="width: 3%;"><span>No</span></th>
                            <th class="text-center style-header" style="width: 8%;"><span>Name</span></th>
                            <th class="text-center style-header" style="width: 7%;"><span>Rank</span></th>
                            <th class="text-center style-header" style="width: 2%;"><span>DOC No</span></th>
                            <th class="text-center style-header" style="width: 15%;"><span>Type of certificates</span></th>
                            <th class="text-center style-header" style="width: 7%;"><span>Certificates No.</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Issued Date</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Expire Date</span></th>
                            <th class="text-center style-header" style="width: 6%;"><span>Issued by</span></th>
                        </tr>
                        </thead>
                        <tbody class="" id="list-body">
                        </tbody>
                    </table>













                </div>
                <div class="col-md-2">
                    <div class="form-group" style="padding: 0">
                        <label style="float:left;padding:5px 0">{{transShipMember("memberCertList.Expiry Date")}}:</label>
                        <div class="col-md-7" style="padding-left:5px;float:left">
                            <select class="form-control" id="sel_expire">
                                <option value=""></option>
                                @for($month=1;$month<17;$month++)
                                    @if($month < 13)
                                        <option value="{{$month}}" @if(isset($expire) && ($expire == $month)) selected @endif>{{$month}}{{transShipMember("captions.months")}}</option>
                                    @else
                                        <option value="{{$month}}" @if(isset($expire) && ($expire == $month)) selected @endif>{{$month-12}}{{transShipMember("captions.year")}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div style="padding:0; float: right">
					<button class="btn btn-sm btn-primary no-radius search-btn" style="width: 80px">
						<i class="icon-search"></i>
						{{transShipMember("captions.search")}}
					</button>

					<button class="btn btn-sm btn-warning no-radius excel-btn" style="width: 80px">
                        <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
					</button>
                </div>
                <div id="test">
                </div>
            </div>
            <div class="row">
                <div class="space-4"></div>
				<div style="width:100%;overflow-y: scroll;overflow-x: hidden;">
                <table class="table table-bordered table-hover" style="font-size: 11px;margin-bottom: 0px;">
                    <thead>
                    <tr class="black br-hblue">
                        <th class="center" rowspan="3" style="width: 1%;">No</th>
                        <th class="center" rowspan="3" style="width: 3%;">{{transShipMember("memberCertList.Name")}}</th>
                        <th class="center" colspan="1">{{transShipMember("memberCertList.Sign On/Off")}}</th>
                        <th class="center" colspan="3">{{transShipMember("memberCertList.Seafarer`s passport")}}</th>
                        <th class="center" colspan="1">{{transShipMember("memberCertList.Graduate")}}</th>
                        <th class="center" colspan="12">{{transShipMember("memberCertList.Capacity Data")}}</th>
                        <th class="center" colspan="6">{{transShipMember("memberCertList.Train and Cert")}}</th>
                    </tr>
                    <tr class="black br-hblue">
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.ShipName")}}</th>
                        <th class="center" rowspan="2" style="width: 4%;">{{transShipMember("memberCertList.No")}}</th>
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.ShipName")}}</th>
                        <th class="center" rowspan="2" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" rowspan="2" style="width: 5%;">{{transShipMember("memberCertList.SchoolName")}}</th>
                        <th class="center" colspan="2">{{transShipMember("memberCertList.Capacity")}}</th>
                        <th class="center" colspan="2">{{transShipMember("memberCertList.GOC")}}</th>
                        <th class="center" colspan="3">{{transShipMember("memberCertList.COE")}}</th>
                        <th class="center" colspan="3">{{transShipMember("memberCertList.COE(GOC)")}}</th>
                        <th class="center" colspan="2">{{transShipMember("memberCertList.Watching Rating")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Basic")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Special")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Security")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Health")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Security Cert")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Able Seafarer")}}</th>
                    </tr>
                    <tr class="black br-hblue">
                        <th class="center">{{transShipMember("memberCertList.Duty")}}</th>
                        <th class="center">{{transShipMember("memberCertList.Duty")}}</th>
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.Class")}}</th>
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 2%;">{{transShipMember("memberCertList.Class")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.Class")}}</th>
                        <th class="center" style="width: 5%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Issuing Authority")}}</th>
                        <th class="center" style="width: 2%;">{{transShipMember("memberCertList.Class")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Issuing Authority")}}</th>
                        <th class="center" style="width: 2%;">{{transShipMember("memberCertList.Class")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Issue Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                        <th class="center" style="width: 3%;">{{transShipMember("memberCertList.Expiry Date")}}</th>
                    </tr>
                    </thead>
                </table>
                </div>
                <div style="overflow-x:hidden; overflow-y: scroll; width:100%; height:55vh; border-bottom: 1px solid #eee">
                <table class="table table-bordered table-hover" id="ship_member_table" style="font-size: 11px">
                    <tbody id="member-cert-table">
                    <?php $index = 1; ?>
                    @foreach($list as $member)
                    <tr>
                        <td class="center" rowspan="2" style="width: 1%;">{{$index++}}</td>
                        <td class="center" rowspan="2" style="width: 3%;word-break: break-all;"><a href="registerShipMember?memberId={{$member->crewId}}">{{$member->realname}}</a></td>
                        <td class="center" style="width: 5%;word-break: break-all;">{{$member->shipName_Cn}}</td>
                        <td class="center" rowspan="2" style="width: 4%;word-break: break-all;">{{$member->crewNum}}</td>
                        <td class="center" style="width: 5%;word-break: break-all;">
                            @if(!empty($member->scanPath))
                                <?php   $tmp = explode('.', $member->scanPath);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember('captions.marinCertCopy').$ext; ?>
                                <a href="/fileDownload?type=crewCard&path={{$member->scanPath}}&filename={{$filename}}"  class="hide-option" title="{{transShipMember('captions.certCopy')}}" style="float: left;padding-top:6px;width: 100%;">
                                    {{$member->book_ship}}
                                </a>
                            @else
                                {{$member->book_ship}}
                            @endif</td>
                        <td class="center expire" rowspan="2" style="width: 3%;word-break: break-all;">{{convert_date($member->ship_ExpiryDate)}}</td>
                        <td class="center" rowspan="2" style="width: 5%;word-break: break-all;">
                            @if(!empty($member->school_path))
                                <?php
                                $tmp = explode('.', $member->school_path);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.certCopy").$ext; ?>
                                <a href="/fileDownload?type=school&path={{$member->school_path}}&filename={{$filename}}"  class="hide-option" title="{{transShipMember('captions.diplomaCopy')}}" style="float: left;padding-top:6px">
                                    {{$member->school}}
                                </a>
                            @else
                                {{$member->school}}
                            @endif</td>
                        <td class="center" style="width: 10%;word-break: break-all;">
                            @if(!empty($member->GOC))
                                <?php
                                $tmp = explode('.', $member->GOC);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.licenseCopy").'1(GOC).'.$ext; ?>
                                <a href="/fileDownload?type=capacity&path={{$member->GOC}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{$member->generalCapacity}}
                                </a>
                            @else
                                {{$member->generalCapacity}}
                            @endif
                        </td>
                        <td class="center" style="width: 5%;word-break: break-all;">
                            @if(!empty($member->GMDSS_Scan))
                                <?php
                                $tmp = explode('.', $member->GMDSS_Scan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.licenseCopy").'2(GMDSS).'.$ext; ?>
                                <a href="/fileDownload?type=capacity&path={{$member->GMDSS_Scan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{$member->GOC_capacity}}
                                </a>
                            @else
                                {{$member->GOC_capacity}}
                            @endif
                        </td>
                        <td class="center" style="width: 10%;word-break: break-all;">
                            @if(!empty($member->COE_Scan))
                                <?php
                                $tmp = explode('.', $member->COE_Scan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.licenseCopy").'3(COE).'.$ext; ?>
                                <a href="/fileDownload?type=capacity&path={{$member->COE_Scan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{$member->COE_capacity}}
                                </a>
                            @else
                                {{$member->COE_capacity}}
                            @endif
                        </td>
                        <td class="center" rowspan="2" style="width: 3%;word-break: break-all;">{{$member->COE_Remarks}}</td>
                        <td class="center" style="width: 5%;word-break: break-all;">
                            @if(!empty($member->COE_GOC_Scan))
                                <?php
                                $tmp = explode('.', $member->COE_GOC_Scan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.licenseCopy").'4(COE_GOC).'.$ext; ?>
                                <a href="/fileDownload?type=capacity&path={{$member->COE_GOC_Scan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{$member->COE_GOC_capacity}}
                                </a>
                            @else
                                {{$member->COE_GOC_capacity}}
                            @endif
                        </td>
                        <td class="center" rowspan="2" style="width: 3%;word-break: break-all;">{{$member->COE_GOC_Remarks}}</td>
                        <td class="center" style="width: 5%;word-break: break-all;">
                            @if(!empty($member->Watch_Scan))
                                <?php
                                $tmp = explode('.', $member->Watch_Scan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.licenseCopy").'4('.transShipMember("captions.watchCopy").$ext; ?>
                                <a href="/fileDownload?type=capacity&path={{$member->Watch_Scan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{$member->Watch_capacity}}
                                </a>
                            @else
                                {{$member->Watch_capacity}}
                            @endif
                        </td>
                        <td class="center expire" rowspan="2" style="width: 3%;word-break: break-all;">
                            @if (!empty($member->TCBScan))
                                <?php   $tmp = explode('.', $member->TCBScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.basicTrain").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->TCBScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->TCBExpiryDate)}}
                                </a>
                            @else
                                {{convert_date($member->TCBExpiryDate)}}
                            @endif
                        </td>
                        <td class="center expire" rowspan="2" style="width: 3%;word-break: break-all;">
                            @if (!empty($member->TCSScan))
                                <?php   $tmp = explode('.', $member->TCSScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.proTrain").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->TCSScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->TCSExpiryDate)}}
                                </a>
                            @else
                                {{convert_date($member->TCSExpiryDate)}}
                            @endif
                        </td>
                        <td class="center">{{$member->securityItem}}</td>
                        <td class="center expire" rowspan="2" style="width: 3%;word-break: break-all;">
                            @if (!empty($member->MCSScan))
                                <?php   $tmp = explode('.', $member->MCSScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.healthCert").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->MCSScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->MCS_ExpiryDate)}}
                                </a>
                            @else
                                {{convert_date($member->MCS_ExpiryDate)}}
                            @endif
                        </td>
                        <td class="center">{{$member->securityTrain}}</td>
                        <td class="center">{{$member->ASDType}}</td>
                    </tr>
                    <tr>
                        <td class="center">{{$member->ship_duty}}</td>
                        <td class="center" style="word-break: break-all;">{{$member->book_duty}}</td>
                        <td class="center expire" style="word-break: break-all;">{{convert_date($member->general_expireDate)}}</td>
                        <td class="center expire" style="word-break: break-all;">{{convert_date($member->GMD_ExpiryDate)}}</td>
                        <td class="center expire" style="word-break: break-all;">{{convert_date($member->COE_ExpiryDate)}}</td>
                        <td class="center expire" style="word-break: break-all;">{{convert_date($member->COE_GOC_ExpiryDate)}}</td>
                        <td class="center expire" style="word-break: break-all;">{{convert_date($member->Watch_ExpiryDate)}}</td>
                        <td class="center" style="word-break: break-all;width: 3%;">
                            @if (!empty($member->TCPScan))
                                <?php   $tmp = explode('.', $member->TCPScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.safeCertCopy").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->TCPScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->TCPIssuedDate)}}
                                </a>
                            @else
                                {{convert_date($member->TCPIssuedDate)}}
                            @endif
                        </td>
                        <td class="center expire book" data-id="{{ $member->book_id }}" style="width: 3%;word-break: break-all;">
                            @if (!empty($member->SSOScan))
                                <?php   $tmp = explode('.', $member->SSOScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.safeCert").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->SSOScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->SSOExpiryDate)}}
                                </a>
                            @else
                                {{convert_date($member->SSOExpiryDate)}}
                            @endif
                        </td>
                        <td class="center expire" style="width: 3%;word-break: break-all;">
                            @if (!empty($member->ASDScan))
                                <?php   $tmp = explode('.', $member->ASDScan);
                                $ext = $tmp[count($tmp) - 1];
                                $filename = $member->realname.'_'.transShipMember("captions.practiceCertCopy").$ext; ?>
                                <a href="/fileDownload?type=training&path={{$member->ASDScan}}&filename={{$filename}}" class="hide-option" title="{{$filename}}">
                                    {{convert_date($member->ASDExpiryDate)}}
                                </a>
                            @else
                                {{convert_date($member->ASDExpiryDate)}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
				</div>
            </div>
        </div>
    </div>
</div>

<script>


    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    <?php
	echo '<script>';
	echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
	echo '</script>';
	?>
    <script>
        var certList = new Array();
        var cIndex = 0;
        
        @foreach($security as $type)
            var cert = new Object();
            cert.value = '{{$type["title"]}}';
            certList[cIndex] = cert;
            cIndex++;
        @endforeach

	var page = '{{$page}}' * 1;







        var token = '{!! csrf_token() !!}';
        var shipName = '';
        $(function () {
            $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
            $.fn.editableform.buttons = '';
        });
            
        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

	$(function () {
        @if(isset($expire))
            showExpireDate({{ $expire }});
        @endif
        var listTable = null;
        function initTable() {
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/cert/list',
                    type: 'POST',
                    data: {'type' : 'crew'},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                order: [[2, 'asc']],
                columns: [
                    {data: 'no', className: "text-center"},
                    {data: 'name', className: "text-center"},
                    {data: 'rank', className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: '_no', className: "text-center"},
                    {data: '_issue', className: "text-center"},
                    {data: '_expire', className: "text-center"},
                    {data: '_by', className: "text-center"},
                ],
                rowsGroup: [0, 1, 2],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('class', 'member-item');
                    var cert_index = data['index'];
                    $('td', row).eq(3).html('').append(data['count']);
                    $('td', row).eq(4).attr('class', 'text-center style-bold-italic');
                    if (cert_index == 0) {
                        $('td', row).eq(4).html('').append('Seamanbook');
                    }
                    else if (cert_index == 1) {
                        $('td', row).eq(4).html('').append('Passport');
                    }
                    else if (cert_index == 2 || cert_index == 3) {
                        if (data['_type'] != '' ) {
                            $('td', row).eq(4).html('').append(data['_type']);
                        }
                        else
                        {
                            if (cert_index == 2) $('td', row).eq(4).html('').append('COC: Certificate of Competency');
                            else if (cert_index == 3) $('td', row).eq(4).html('').append('COE: Certificate of Endorsement');
                        }
                    }
                    else if (cert_index == 4) {
                        $('td', row).eq(4).html('').append('GOC: GMDSS general operator');
                    }
                    else if (cert_index == 5) {
                        $('td', row).eq(4).html('').append('GOC Endorsement');
                    }
                    else
                    {
                        $('td', row).eq(4).html('').append(certList[cert_index-6].value);
                    }
                },
            });

		$('.search-btn').on('click', function() {
			var shipId = $("#sel_ship").val();
			var posId = $("#sel_pos").val();
			var capacityId = $("#sel_capacity").val();
			var expire = $("#sel_expire").val();

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }










			var urlParam = '';
			if(shipId.length > 0)
				urlParam += '?ship=' + shipId;
			if(posId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&pos=' + posId : '?pos=' + posId;

			if(capacityId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&capacity=' + capacityId : '?capacity=' + capacityId;

			if(expire.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&month=' + expire : '?month=' + expire;

			location.href = 'memberCertList' + urlParam;
		});

		$('.excel-btn').on('click', function() {
			var shipId = $("#sel_ship").val();
			var posId = $("#sel_pos").val();
			var capacityId = $("#sel_capacity").val();
			var expire = $("#sel_expire").val();

			var urlParam = '';
			if(shipId.length > 0)
				urlParam += '?ship=' + shipId;
			if(posId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&pos=' + posId : '?pos=' + posId;

			if(capacityId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&capacity=' + capacityId : '?capacity=' + capacityId;

			if(expire.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&month=' + expire : '?month=' + expire;

			location.href = 'memberCertListExcel' + urlParam;
		});

		$('.prev_page').on('click', function() {
			page--;
			if(page == 0)
				return;
			gotopage(page);
		});

		$('.next_page').on('click', function() {
			page++;

			gotopage(page);
		});

		$('.page').on('click', function() {
			page = $(this).html();
			gotopage(page);
		});

		$('#sel_expire').on('change', function () {
			var expireMonth = $(this).val() * 1;

			var startDate = new Date();
			var year = startDate.getFullYear();
			var day = startDate.getDate();
			var month = startDate.getMonth();
            if(expireMonth < 13) {
                month += expireMonth + 1;
                if(month > 12) {
                    year++;
                    month = month - 12;
                }

                if(month < 10)
                    month = '0' + month;
                if(day < 10)
                    day = '0' + day;
            } else {
                year = year + expireMonth - 12;
            }
			var expireDate = year + '/' + month + '/' + day;

			year = startDate.getFullYear();
			day = startDate.getDate();
			month = startDate.getMonth() + 1;
			if(month > 12) {
				year++;
				month = month - 12;
			}

			if(month < 10)
				month = '0' + month;
			if(day < 10)
				day = '0' + day;
			startDate = year + '/' + month + '/' + day;
			
			var tds = $('#member-cert-table').find('.expire');
			for(var index = 0; index<tds.length; index++) {
				var tdObj = tds.eq(index);

				var dateStr = tdObj.text();
                dateStr = dateStr.trim();
                if(tdObj.hasClass('book')) {
                    var book_duty = tdObj.data('id');
                    switch (book_duty) {
                        case 1:case 2:case 6: break;
                        default : continue;
                    }
        function doSearch() {
            if (shipName == "") return;
            if (listTable == null) initTable();
            $('#ship_name').html('"' + shipName + '"');
            listTable.column(2).search($("#select-ship" ).val(), false, false);
            listTable.column(3).search($("#expire-date").val(), false, false).draw();
        }

        $('#select-ship').on('change', function() {
            shipName = $(this).find(':selected').attr('data-name');
            $('#ship_name').html('"' + shipName + '"');
            doSearch();
        });

        $('#expire-date').on('change', function() {
            doSearch();
        });

        $('.excel-btn').on('click', function() {
           $('td[style*="display: none;"]').remove();
           fnExcelReport();
		});

        function fnExcelReport()
        {
            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-shipmember-list');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='9' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + '"' + shipName + '"' + "CREW CERTIFICATES LIST</td></tr>";
            for(var j = 0 ; j < tab.rows.length ; j++) 
            {
                if (j == 0) {
                    console.log(tab.rows[j]);
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        if (i == 0) {
                        }
                        else if (i == 1) {
                            tab.rows[j].childNodes[i].style.width = '240px';
                        }
                        else if (i == 2) {
                        }
                        else if (i == 3) {
                        }
                        else if (i == 4) {
                            tab.rows[j].childNodes[i].style.width = '340px';
                        }
                        else if (i == 5) {
                            tab.rows[j].childNodes[i].style.width = '240px';
                        }
                        else if (i == 8) {
                            tab.rows[j].childNodes[i].style.width = '240px';
                        }
                        tab.rows[j].childNodes[i].style.backgroundColor = '#c9dfff';
                    }
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                }
				if((dateStr.length > 0) && (dateStr < expireDate)) {
					tdObj.addClass('filter_cell');
                    var trs = tdObj.closest('tr').children();
                    if(trs.length < 12) {
                        tdObj.closest('tr').prev().eq(1).find('a').css('color','red');
                    } else {
                        trs.eq(1).find('a').css('color','red');
                    }
				} else {
					tdObj.removeClass('filter_cell');
				}
			}
            var trs = $('#member-cert-table').children();
            for(var index = 0; index<tds.length; index++) {
                var trCount = trs.eq(index).children().length;
                var tdObjs = trs.eq(index).find('.filter_cell');
                if(tdObjs.length < 1) {
                    if(trCount < 12) {
                        tdObjs = trs.eq(index-1).find('.filter_cell');
                        if(tdObjs.length < 1)
                            trs.eq(index-1).children().eq(1).find('a').css('color','');
                    } else {
                        tdObjs = trs.eq(index+1).find('.filter_cell');
                        if(tdObjs.length < 1)
                            trs.eq(index).children().eq(1).find('a').css('color','');
                    }
                }
            }

		});
	});

	function gotopage(page) {
			var shipId = $("#sel_ship").val();
			var posId = $("#sel_pos").val();
			var capacityId = $("#sel_capacity").val();
			var expire = $("#sel_expire").val();

			var urlParam = '';
			if(shipId.length > 0)
				urlParam += '?ship=' + shipId;
			if(posId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&pos=' + posId : '?pos=' + posId;

			if(capacityId.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&capacity=' + capacityId : '?capacity=' + capacityId;

			if(expire.length > 0)
				urlParam = urlParam.length > 0 ? urlParam + '&month=' + expire : '?month=' + expire;
			
			urlParam = urlParam.length > 0 ? urlParam + '&page=' + page : '?page=' + page;

			location.href = 'memberCertList' + urlParam;
	}

    function showExpireDate(expireMonth) {

        var startDate = new Date();
        var year = startDate.getFullYear();
        var day = startDate.getDate();
        var month = startDate.getMonth();
        if(expireMonth < 13) {
            month += expireMonth + 1;
            if(month > 12) {
                year++;
                month = month - 12;
            }

            if(month < 10)
                month = '0' + month;
            if(day < 10)
                day = '0' + day;
        } else {
            year = year + expireMonth - 12;
                else
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }

            tab_text=tab_text+"</table>";
            //tab_text='<table border="2px" style="text-align:center;vertical-align:middle;"><tr><th class="text-center sorting_disabled" style="width: 78px;text-align:center;vertical-align:center;" rowspan="1" colspan="1"><span>No</span></th></tr><tr style="width: 78px;text-align:center;vertical-align:middle;"><td class="text-center sorting_disabled" rowspan="2" style="">你好</td></tr></table>';
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            //document.getElementById('test').innerHTML = tab_text;
            var filename = 'CREW CERTIFICATES LIST(' + shipName + ')';
            exportExcel(tab_text, filename, 'CREW CERTIFICATES LIST');
            return 0;
        }
        var expireDate = year + '/' + month + '/' + day;

        year = startDate.getFullYear();
        day = startDate.getDate();
        month = startDate.getMonth() + 1;
        if(month > 12) {
            year++;
            month = month - 12;
        }
        /*
        function refresh() {
            $('#expire-date').val('0');
            doSearch();
        }

        if(month < 10)
            month = '0' + month;
        if(day < 10)
            day = '0' + day;
        startDate = year + '/' + month + '/' + day;

        var tds = $('#member-cert-table').find('.expire');
        for(var index = 0; index<tds.length; index++) {
            var tdObj = tds.eq(index);

            var dateStr = tdObj.text();
            dateStr = dateStr.trim();
            if(tdObj.hasClass('book')) {
                var book_duty = tdObj.data('id');
                switch (book_duty) {
                    case 1:case 2:case 6: break;
                    default : continue;
                }
            }
            if((dateStr.length > 0) && (dateStr < expireDate)) {
                tdObj.addClass('filter_show_cell');
            }
        }
    }

    $('.init-btn').on('click', function() {

        location.href = 'memberCertList';
    });
</script>
@endsection
        $('#btn-search').on('click', function() {
            doSearch();
        });
        */
        
    </script>

@endsection









