@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <style>
            td {
                height : 38px;
            }
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.ShipAttendRegister")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-6 alert alert-block alert-info center" style="font-size: 16px">
					@if(!empty($shipName))
	                    <strong>【&nbsp;{{$shipName->name}}({{$shipName->shipName_Cn}})&nbsp;】号&nbsp;({{ $shipName->shipName_En }}) {{transBusinessManage("captions.enterstate")}}</strong>
					@else
	                    <strong>【&nbsp;{{transBusinessManage("transBusinessManage("captions.companyname")}}&nbsp;】&nbsp;   {{transBusinessManage("captions.waitmember_enterstate")}}</strong>
					@endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <label style="float: left; padding-top: 5px">{{transBusinessManage("captions.date")}} :</label>
                        <div class="input-group" style="padding-left:10px">
                            <input class="form-control date-picker" id="search-date" type="text" style="float: left;"
                                   data-date-format="yyyy/mm/dd" value="@if(isset($date)){{convert_date($date)}}@endif">
                            <span class="input-group-addon">
                            <i class="icon-calendar bigger-110"></i>
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm search-btn" style="float: left">{{transBusinessManage("captions.search")}}</button>
                </div>
                <div class="space-6"></div>
                <div class="row">
                    <div style="@if(count($attendMembers) > 10) overflow-y: scroll;@endif width: 100%">
                        <table class="table table-striped table-bordered table-hover arc-std-table">
                            <thead>
                            <tr>
                                <th class="center" style="width:6%">{{transBusinessManage("captions.no")}}No</th>
                                <th class="center" style="width:10%">{{transBusinessManage("captions.shipname")}}</th>
                                <th class="center" style="width:10%">{{transBusinessManage("captions.duty")}}</th>
                                <th class="center" style="width:8%">{{transBusinessManage("captions.name")}}</th>
                                <th class="center" style="width:15%">{{transBusinessManage("captions.enterstate")}}</th>
                                <th class="center">{{transBusinessManage("captions.remark")}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="overflow-x:hidden; overflow-y: auto; width:100%; height:67vh; border-bottom: 1px solid #eee">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <?php $index = 1;?>
                                @foreach($attendMembers as $member)
                                    <?php if(empty($shipName) && (!empty($member->shipName) || $member->RegStatus == 0)) continue; ?>
                                    <tr data-id="{{$member->id}}">
                                        <td class="center" style="width:6%">{{$index++}}</td>
                                        <td class="center" style="width:10%">{{$member->shipName}}</td>
                                        <td class="center" style="width:10%">
                                                {{$member->Duty}}
                                        </td>
                                        <td class="center" style="width:8%">{{$member->realname}}</td>
                                        <td class="center" style="width:15%">{{ $member->statusName }}</td>
                                        <td>{{$member->memo}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="hr hr-18 dotted hr-double"></div>
                    <div class="center">
                        <span id="restAlarm" class="red" style="display: none">&nbsp;&nbsp;&nbsp;今天是休息日。</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var shipId = '{!! $shipId !!}';
        $(function () {


            $("#search-date").bind("change", function () {
                var selDate = $("#search-date").val();
                if(selDate.length < 1) {
                    bootbox.confirm("请选择日子。", function(result) {});
                    return;
                }
                location.href = 'shipAttendDayPage?ship=' + shipId + '&selDate=' + selDate;
            });

            var isRest = '{!! $isRest !!}' * 1;
            showRestAlarmString(isRest);
        });

        function showRestAlarmString(isRest) {
            if(isRest)
                document.getElementById('restAlarm').style.display = '-webkit-inline-box';
            else
                document.getElementById('restAlarm').style.display = 'none';
        }
    </script>

@endsection