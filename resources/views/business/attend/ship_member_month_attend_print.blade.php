@extends('layout.header-print')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <style>
                .rest{background-color: #ff9e8b}
            </style>
            <div class="page-header">
                <div class="col-md-8">
                    <h4>
                        <small>
                            {{$info->getShipNameAndPos().' ' .$info->realname}} {{$year}}年 {{$month}}{{transBusinessManage("title.MemberMonthAttendPrint")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;text-align: center">
                                <table class="table table-bordered table-hover" style="width: 98%">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center" style="width: 10%">{{transBusinessManage("captions.date")}}</th>
                                        <th class="center" style="width: 20%">{{transBusinessManage("captions.enterstate")}}</th>
                                        <th class="center">{{transBusinessManage("captions.remark")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $day = 0;?>
                                    @for(;$day<15;$day++)
                                        <?php $attend = $list[$day] ?>
                                        <tr @if($attend['rest'] == 1) class="rest" @endif>
                                            <td class="center">{{$attend['day']}}{{transBusinessManage("captions.day")}}</td>
                                            @if($attend['rest'] == 1)
                                                <td class="center">{{$attend['name']}}</td>
                                                <td class="center">{{$attend['memo']}}</td>
                                            @else
                                                <td class="center">
                                                    <?php $attendName = \App\Http\Controllers\Util::getStampUrl($attend['name'], $signPath, 1); ?>
                                                    @if($attendName != null)
                                                        <img src="{{$attendName}}" style="width: 45px;height: 15px;">
                                                    @else
                                                        {{$attend['name']}}
                                                    @endif
                                                </td>
                                                <td class="center">{{$attend['memo']}}</td>
                                            @endif
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 50%;">
                                <table class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                    <tr class="black br-hblue">
                                        <th class="center" style="width: 10%">{{transBusinessManage("captions.date")}}</th>
                                        <th class="center" style="width: 20%">{{transBusinessManage("captions.enterstate")}}</th>
                                        <th class="center">{{transBusinessManage("captions.remark")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $day = 15;?>
                                    @for(;$day<$monthDay;$day++)
                                        <?php $attend = $list[$day] ?>
                                        <tr @if($attend['rest'] == 1) class="rest" @endif>
                                            <td class="center">{{$attend['day']}}{{transBusinessManage("captions.day")}}</td>
                                            @if($attend['rest'] == 1)
                                                <td class="center">{{$attend['name']}}</td>
                                                <td class="center">{{$attend['memo']}}</td>
                                            @else
                                                <td class="center">
                                                    <?php $attendName = \App\Http\Controllers\Util::getStampUrl($attend['name'], $signPath, 1); ?>
                                                    @if($attendName != null)
                                                        <img src="{{$attendName}}" style="width: 45px;height: 15px;">
                                                    @else
                                                        {{$attend['name']}}
                                                    @endif
                                                </td>
                                                <td class="center">{{$attend['memo']}}</td>
                                            @endif
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th rowspan="2">{{transBusinessManage("captions.month_days")}}</th>
                            <th rowspan="2">{{transBusinessManage("captions.restdays")}}</th>
                            <th rowspan="2">{{transBusinessManage("captions.legal_workdays")}}</th>
                            <th rowspan="2">{{transBusinessManage("captions.enterdays")}}</th>
                            <th rowspan="2">{{transBusinessManage("captions.absencecount")}}</th>
                            <th colspan="11">{{transBusinessManage("captions.among")}}그중</th>
                            <th rowspan="2">{{transBusinessManage("captions.late")}}지각</th>
                            <th rowspan="2">{{transBusinessManage("captions.earlyleave")}}조퇴</th>
                        </tr>
                        <tr class="black br-hblue">
                            <th class="center">{{transBusinessManage("captions.noconfirm")}}</th>
                            <th class="center">{{transBusinessManage("captions.intertrip")}}</th>
                            <th class="center">{{transBusinessManage("captions.abroadtrip")}}</th>
                            <th class="center">{{transBusinessManage("captions.practice")}}</th>
                            <th class="center">{{transBusinessManage("captions.school")}}</th>
                            <th class="center">{{transBusinessManage("captions.lesson")}}</th>
                            <th class="center">{{transBusinessManage("captions.rally")}}</th>
                            <th class="center">{{transBusinessManage("captions.rest")}}</th>
                            <th class="center">{{transBusinessManage("captions.hospital")}}</th>
                            <th class="center">{{transBusinessManage("captions.sick")}}</th>
                            <th class="center">{{transBusinessManage("captions.privacy")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: center;">@if($totalData["days"] != 0) {{$totalData["days"]}}@endif</td>
                            <td style="text-align: center;">@if($totalData["rest"] != 0) {{$totalData["rest"]}}@endif</td>
                            <td style="text-align: center;">@if($totalData["work"] != 0) {{$totalData["work"]}}@endif</td>
                            <td style="text-align: center;">@if($totalData["attend"] != 0) {{$totalData["attend"]}}@endif</td>
                            <td style="text-align: center;">@if($totalData["absence"] != 0) {{$totalData["absence"]}}@endif</td>
                            <?php for($i=4; $i<=14; $i++) { ?>
                            <td style="text-align: center;">@if($totalData["type_{$i}"] != 0) {{$totalData["type_{$i}"]}}@endif</td>
                            <?php }?>
                            <td style="text-align: center;">@if($totalData["type_2"] != 0) {{$totalData["type_2"]}}@endif</td>
                            <td style="text-align: center;">@if($totalData["type_3"] != 0) {{$totalData["type_3"]}}@endif</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection