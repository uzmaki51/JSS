@extends('layout.sidebar')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <style>
                .rest{background-color: #ff9e8b}
            </style>
            <div class="page-header">
                <div class="col-md-8">
                    <h4>
                        <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                        <?php $unitName = $info->unitName->title;
                        if($info->unit > 29)
                            $unitName .= '科';
                        ?>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{$unitName.' '.$info->position->title. ' ' .$info->realname}} {{transBusinessManage("title.MemberMonthAttendPrint")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="input-group col-md-3">
                        <div class="form-group col-md-7">
                            <select class="col-md-8" id="selyear">
                                @for($i=2015;$i<=2020;$i++)
                                    <option value="{{$i}}" @if($i == $year)) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <label class="col-md-2 control-label">{{transBusinessManage("captions.year")}}</label>
                        </div>
                        <div class="form-group col-md-5">
                            <select class="col-md-9" id="selmonth">
                                @for ($i=1;$i<13;$i++)
                                    <option value="{{$i}}" @if($i == $month)selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <label class="col-md-1 control-label">{{transBusinessManage("captions.month")}}</label>
                        </div>
                    </div>
                    <div class="col-md-4 form-horizontal">
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary btn-sm search-btn" style="width: 80px"><i class="icon-search"></i>{{transBusinessManage("captions.exittime")}}搜索</button>
                            <button style="float: right; width: 80px" type="button" class="btn btn-primary btn-sm print-year-attend"><i class="icon-print"></i>{{transBusinessManage("captions.exittime")}}打印</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="black br-hblue">
                                    <th class="center" style="width: 10%">{{transBusinessManage("captions.date")}}</th>
                                    <th class="center" style="width: 20%">{{transBusinessManage("captions.enterstate")}}</th>
                                    <th class="center">{{transBusinessManage("captions.remark")}}备注</th>
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
                                                <img src="{{$attendName}}" style="width: 50px;height: 20px;">
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
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-hover">
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
                                                <img src="{{$attendName}}" style="width: 50px;height: 20px;">
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
                    </div>
                </div>
                <div class="row">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center" rowspan="2">{{transBusinessManage("captions.month_days")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.restdays")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.legal")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.enterdays")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.absencecount")}}</th>
                            <th class="center" colspan="11">{{transBusinessManage("captions.among")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.late")}}</th>
                            <th class="center" rowspan="2">{{transBusinessManage("captions.earlyleave")}}</th>
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

    <script>

        var memberId = "{{ $info->id }}";
        var totalData = '<?php print_r(json_encode($totalData));?>';

        $(function () {

            $('.search-btn').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                location.href = 'memberMonthAttend?userId='+ memberId +'&year='+selyear + '&month=' + selmonth;
            });

            $('.print-year-attend').on('click', function () {
                $token = '{!! csrf_token() !!}';
                $year = $("#selyear").val();
                $month = $("#selmonth").val();
                $userId = memberId;
                $Data = jQuery.parseJSON(totalData);
                $days = $Data.days;
                $rest = $Data.rest;
                $work = $Data.work;
                $attend = $Data.attend;
                $absence = $Data.absence;
                $type_2 = $Data.type_2;
                $type_3 = $Data.type_3;
                $typeUrl = "";
                for($i=4; $i<=14; $i++){
                    $type_$i = $Data['type_'+$i];
                    $typeUrl += "&type_"+$i+"="+$type_$i;
                }
                $param = "_token="+$token+"&userId="+$userId+"&year="+$year+"&month="+$month+
                        "&days="+$days+"&rest="+$rest+"&work="+$work+
                        "&attend="+$attend+"&absence="+$absence+"&type_2="+$type_2+"&type_3="+$type_3+$typeUrl;
                $url = "memberMonthAttendPrint";
                submitData($url, $param, "POST", 'target="print"');
            });
        });

    </script>
@endsection