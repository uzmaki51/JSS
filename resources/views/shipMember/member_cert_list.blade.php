@extends('layout.sidebar')
<?php
    $isHolder = Session::get('IS_HOLDER');
?>
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

	var page = '{{$page}}' * 1;

	$(function () {
        @if(isset($expire))
            showExpireDate({{ $expire }});
        @endif

		$('.search-btn').on('click', function() {
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
