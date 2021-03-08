<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transShipMember("title.Member Estimate")}}</b>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="input-group" style="float: left;width: 100%">
                        <div class="col-sm-2 form-group" style="padding: 0">
                            <label style="float:left;padding:5px 0">{{transShipMember("shipMember.ShipName")}}:</label>
                            <div class="col-md-8" style="padding-left:5px">
                                <select class="form-control" id="ship_name" style="width:130px">
                                    <option value="">{{transShipMember("captions.total")}}</option>
                                    @foreach($shipList as $ship)
                                        @if(!empty($ship['Class']))
                                            <option value="{{$ship['RegNo']}}" @if($shipId == $ship['RegNo']) selected @endif>{{$ship['shipName_Cn']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 form-group" style="padding: 0">
                            <label style="float:left;padding:5px 0">{{transShipMember("RealAblility.Ref No")}}:</label>
                            <div class="col-md-8" style="padding-left:5px">
                                <select class="form-control" id="ExamCode" style="width:130px">
                                    <option value="">{{transShipMember("captions.total")}}</option>
                                    @foreach($examCodes as $codes)
                                        <option value="{{$codes['ExamCode']}}" @if($paramExamCode == $codes['ExamCode']) selected @endif>{{$codes['ExamCode']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1" style="float:right;">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm btn-primary no-radius search_btn" style="width: 80px">
                                        <i class="icon-search"></i>
                                        {{transShipMember("captions.search")}}
                                    </button>
                                    <button class="btn btn-sm btn-warning no-radius excel_btn" style="margin-left: 1px; width :80px">
                                        <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                                    </button>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <div class="col-md-8 no-padding">
@else
    @include('layout.excel-style')
@endif
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr><th colspan="5" class="black br-hblue">{{transShipMember("totalShipMember.Member Information")}}</th></tr>
                            <tr class="black br-hblue">
                                <th class="center" style="width:7%">No</th>
                                <th class="center" style="width:10%;white-space: nowrap;">{{transShipMember("shipMember.ShipName")}}</th>
                                <th class="center" style="width:10%">{{transShipMember("totalShipMember.Duty")}}</th>
                                <th class="center" style="width:10%">{{transShipMember("totalShipMember.Name")}}</th>
                                <th class="center" style="white-space: nowrap;">{{transShipMember("CapacityData.Capacity Function")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $index = 1;
                            ?>
                            @foreach($memberList as $member)
                                <tr>
                                    <td class="center">{{$index}}</td>
                                    <td class="center" style="white-space: nowrap">{{$member['shipName_Cn']}}</td>
                                    <td class="center" style="white-space: nowrap">{{$member['Duty']}}</td>
                                    <td class="center" style="white-space: nowrap">{{$member['realname']}}</td>
                                    <td class="center" style="white-space: nowrap">{{$member['Capacity']}}</td>
                                </tr>
                                <?php $index++; ?>
                            @endforeach
                            </tbody>
                        </table>
@if(!isset($excel))
                    </div>
                    <div class="col-md-4 no-padding" style="overflow-x: auto;">
@else
     <br>
@endif
                        <table class="table table-striped table-bordered table-hover" style="min-width: 100%; width: auto; margin-bottom: 0px;">
                            <thead>
                            <tr class="black br-hblue">
                                @foreach($examingList as $examing)
                                    <th colspan="{{count($examing['subjects'])}}" class="center" style="white-space: nowrap;">{{$examing['ExamCode']}}</th>
                                @endforeach
                            </tr>
                            <tr class="black br-hblue">
                                @foreach($examingList as $examing)
                                    @foreach($examing['subjects'] as $subject)
                                        <th class="center" style="width: 40px;white-space: nowrap;">{{$subject['Subject']}}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($memberList as $member)
                                <tr>
                                    @foreach($examingList as $examing)
                                        @foreach($examing['subjects'] as $subject)
                                            @if(isset($member['mark'][$examing['ExamCode']][$subject['Subject']]))
                                                <td class="center" style="width: 40px">{{$member['mark'][$examing['ExamCode']][$subject['Subject']]}}</td>
                                            @else
                                                <td>&nbsp;</td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
@if(!isset($excel))
                    </div>
                </div>
                {{--<div class="row">--}}
                    {{--{!! $memberList->render() !!}--}}
                {{--</div>--}}
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('.search_btn').on('click', function () {
                var shipRegNo = $('#ship_name').val();
                var ExamCode = $('#ExamCode').val();
                if(shipRegNo != '' && ExamCode != ''){
                    location.href = 'integretedMemberExaming?shipId=' + shipRegNo + '&ExamCode=' + ExamCode;
                }else if (shipRegNo != '' && ExamCode == ''){
                    location.href = 'integretedMemberExaming?shipId=' + shipRegNo;
                }else if (shipRegNo == '' && ExamCode != ''){
                    location.href = 'integretedMemberExaming?' + '&ExamCode=' + ExamCode;
                }else
                    location.href = 'integretedMemberExaming';
            })

            $('.excel_btn').on('click', function () {
                var shipRegNo = $('#ship_name').val();
                var ExamCode = $('#ExamCode').val();
                if(shipRegNo != '' && ExamCode != ''){
                    location.href = 'integretedMemberExamingExcel?shipId=' + shipRegNo + '&ExamCode=' + ExamCode;
                }else if (shipRegNo != '' && ExamCode == ''){
                    location.href = 'integretedMemberExamingExcel?shipId=' + shipRegNo;
                }else if (shipRegNo == '' && ExamCode != ''){
                    location.href = 'integretedMemberExamingExcel?' + '&ExamCode=' + ExamCode;
                }else
                    location.href = 'integretedMemberExamingExcel';
            })
        })

    </script>
@endif
@endsection
