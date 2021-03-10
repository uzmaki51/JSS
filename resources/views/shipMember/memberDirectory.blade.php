<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
<?php
    $isHolder = Session::get('IS_HOLDER');
?>
@extends('layout.'.$header)

<?php   $locale = config('app.locale'); ?>

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <style>
            .table thead>tr>th{
                padding: 4px 3px;
                line-height: 1.2;
            }
            #posId_chosen .chosen-drop { width:300px }
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transShipMember("title.Register Folder")}}</b>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="input-group" style="float: left;width: 80%">
                        <div class="col-sm-2 form-group" style="padding: 0">
                            <label style="float:left;padding:5px 0">{{transShipmember("shipMember.ShipName")}}:</label>
                            <div class="col-md-8" style="padding-lefy:5px">
                                <select class="form-control chosen-select" id="shipId">
                                    <option value="" @if(empty($ship)) selected @endif>@if($locale=='cn') {{transShipMember("captions.total")}} @else Total @endif</option>
                                    @foreach($shipList as $item)
                                        <option value="{{$item['RegNo']}}" @if($item['RegNo'] == $ship) selected @endif>
                                            {{$item['name']}} | @if($locale == 'cn'){{ $item['shipName_Cn'] }} @else {{$item['shipName_En']}} @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 form-group" style="padding: 0;">
                            <label style="float:left;padding:5px 0">{{transShipmember("shipMember.Duty")}}:</label>
                            <div class="col-md-8" style="padding-left:5px">
                                <select class="form-control chosen-select" id="posId">
                                    <option value="" @if(empty($pos)) selected @endif>@if($locale=='cn') {{transShipMember("captions.total")}} @else Total @endif</option>
                                    @foreach($posList as $item)
                                        <option value="{{$item['id']}}" @if($item['id'] == $pos) selected @endif>
                                            @if($locale == 'cn'){{ $item['Duty'] }}@else{{ $item['Duty_En'] }}@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 form-group" style="padding: 0;">
                            <label style="float:left;padding:5px 0">{{transShipmember("shipMember.Reg State")}}:</label>
                            <div class="col-md-8" style="padding-left:5px">
                                <select class="form-control chosen-select" id="state">
                                    <option value="3" @if($state == 3) selected @endif>{{transShipMember("captions.total")}}</option>
                                    <option value="2" @if($state == 2) selected @endif>{{transShipMember("captions.register")}}</option>
                                    <option value="1" @if($state == 1) selected @endif>{{transShipMember("captions.dismiss")}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 form-group" style="padding: 0;">
                            <label style="float:left;padding:5px 0">{{transShipMember("captions.name")}}:</label>
                            <div class="col-md-8" style="padding-left:5px">
                                <input type="text" class="form-control" id="member_name" style="width:100%" value="@if(isset($name)){{$name}}@endif">
                            </div>
                        </div>
                    </div>
                    <div style="float: right">
                        <button class="btn btn-sm btn-primary no-radius" onclick="filterByMemberKeyword()" style="width: 70px">
                            <i class="icon-search"></i>
                            {{transShipMember("captions.search")}}
                        </button>
                        @if(!$isHolder)
						    <button class="btn btn-sm btn-primary no-radius" onclick="registerNewMember()" style="width: 70px"><i class="icon-plus-sign-alt"></i>{{transShipMember("captions.add")}}</button>
                        @endif
                        <button class="btn btn-sm btn-warning excel-btn" style="width: 70px"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                    </div>
                </div>
                <div class="row">
                    <div style="width:100%;">
                        <div style="overflow-y: scroll; width: 100%">
@else
    @include('layout.excel-style')
@endif
                            <table class="table table-striped table-bordered table-hover" style="width: 100%;margin-bottom: 0px !important;">
                                <thead style="height: 35px">
                                    <tr class="black br-hblue">
                                        <th class="center" style="width:4%;">{{transShipmember("shipMember.No")}}</th>
                                        <th class="center" style="width:7%;">{{transShipmember("shipMember.ShipName")}}</th>
                                        <th class="center" style="width:7%;">{{transShipmember("shipMember.Duty")}}</th>
                                        <th class="center" style="width:7%;">{{transShipmember("shipMember.Name")}}</th>
                                        <th class="center" style="width:7%;">{{transShipmember("shipMember.Birthday")}}</th>
                                        <th class="center" style="width:30%;">{{transShipmember("shipMember.Capacity")}}</th>
                                        <th class="center" style="width:9%;">{{transShipmember("shipMember.telephone")}}</th>
                                        <th class="center" style="width:9%; ">{{transShipmember("shipMember.handphone")}}</th>
                                        <th class="center" style="width:4%;">{{transShipmember("shipMember.Reg State")}}</th>
                                    </tr>
                                </thead>
@if(!isset($excel))
                            </table>
                        </div>
                        <div id="div_contents" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:65vh;">
                            <table class="table table-striped table-bordered table-hover" id="ship_member_table" style="width: 100%;">
@endif
                                <tbody>
                                    <?php $index = 1; ?>
                                    @foreach($list as $member)
                                        <tr>
                                            <td class="center" style="width:4%;">{{$index++}}</td>
                                            <td class="center" style="width:7%;">@if($locale == 'cn'){{ $member['shipName_Cn'] }} @else {{ $member['shipName_En'] }} @endif</td>
                                            <td class="center" style="width:7%;">@if($locale == 'cn'){{$member['Duty']}} @else {{$member['Duty_En']}} @endif</td>
                                            <td class="center" style="width:7%;"><a href='registerShipMember?memberId={{$member['id']}}'>@if($locale == 'cn') {{$member['realname']}} @else {{$member['Surname']}} {{$member['GivenName']}} @endif</a></td>
                                            <td class="center" style="width:7%;">{{convert_date($member['birthday'])}}</td>
                                            <td class="center" style="width:30%;">
                                                @if($locale == 'cn')
                                                    {{ $member['capacity_Cn'] }}
                                                    @if(!empty($member['gmdss_Cn']))
                                                        , {{ $member['gmdss_Cn'] }}
                                                    @endif
                                                @else
                                                    {{ $member['capacity_en'] }}
                                                    @if(!empty($member['gmdss_en']))
                                                        , {{ $member['gmdss_en'] }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="center" style="width:9%;">@if(isset($member['tel'])) {{$member['tel']}} @else {{transShipMember("captions.nodata")}} @endif</td>
                                            <td class="center" style="width:9%;">@if(isset($member['phone'])) {{$member['phone']}} @else {{transShipMember("captions.nodata")}} @endif</td>
                                            <td class="center" style="width:4%;">@if($member['RegStatus'] == 1) {{transShipMember("captions.register")}} @else {{transShipMember("captions.dismiss")}} @endif</td>
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
    </div>

<script>
    var token = '{!! csrf_token() !!}';


    $(function() {
        $('.excel-btn').on('click', function() {
            var shipId = $('#shipId').val();
            var posId = $('#posId').val();
            var memberName = $('#member_name').val();
            var state = $('#state').val();

            if((shipId == 0) && (posId == 0) && (memberName.length < 1) && state == 0) {
                location.href = 'shipMemberExcel';
                return;
            }

            location.href = 'shipMemberExcel?ship=' + shipId + '&pos=' + posId + '&name=' + memberName + '&state=' + state;
        });
    });

    function filterByMemberKeyword() {
        var shipId = $('#shipId').val();
        var posId = $('#posId').val();
        var memberName = $('#member_name').val();
        var state = $('#state').val();

        if((shipId == 0) && (posId == 0) && (memberName.length < 1) && state == 0) {
            location.href = 'shipMember';
            return;
        }
        location.href = 'shipMember?ship=' + shipId + '&pos=' + posId + '&name=' + memberName + '&state=' + state;
    }

    function registerNewMember() {
        location.href = 'registerShipMember';
    }

    function showMemberInfo(id) {
        location.href = 'registerShipMember?memberId='+ id;
    }

    $('.init-btn').on('click', function() {

        location.href = 'shipMember';
    });

</script>

@endif
@endsection
