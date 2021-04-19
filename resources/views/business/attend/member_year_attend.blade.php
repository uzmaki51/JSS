@extends('layout.header')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <style>
                .rest{background-color: #ff9e8b}
                td>a{color:#333}
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
                            {{$unitName.' '.$info->position->title. ' ' .$info->realname}} {{transBusinessManage("title.YearAttendPrint")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col-md-2">
                        <select class="col-md-8" id="selyear">
                            @for($i=2017;$i<=2019;$i++)
                                <option value="{{$i}}" @if($i == $year)) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                        <label class="col-md-2 control-label">{{transBusinessManage("captions.year")}}</label>
                    </div>
                    <div class="col-md-4 form-horizontal">
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary btn-sm search-btn" style="width: 80px"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                            <button style="float: right; width: 80px" type="button" class="btn btn-primary btn-sm print-year-attend"><i class="icon-print"></i>{{transBusinessManage("captions.print")}}</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center" colspan="2">{{transBusinessManage("captions.date")}}</th>
                            <th class="center" colspan="2">1{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">2{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">3{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">4{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">5{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">6{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">7{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">8{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">9{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">10{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">11{{transBusinessManage("captions.month")}}</th>
                            <th class="center" colspan="2">12{{transBusinessManage("captions.month")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for($index = 0; $index<16; $index++)
                                <?php $fday = $index;
                                    $sday = $index + 15;
                                ?>
                                <tr>
                                    <td style="width:3.8%">@if($fday != 15) {{ $fday + 1}}{{transBusinessManage("captions.day")}} @endif</td>
                                    <td style="width:3.8%;text-align: center;">{{ $sday + 1}}{{transBusinessManage("captions.day")}}</td>
                                    @for($month = 1;$month<13;$month++)
                                        <?php
                                        $firstAttendStatus = '';
                                        $firstAttendMemo = '';
                                        $secondAttendStatus = '';
                                        $secondAttendMemo = '';

                                        $isfRest = 0;
                                        $issRest = 0;
                                        if(isset($list[$month.''])) {
                                            if(count($list[$month.'']['data']) > 0) {
                                                $fattend = $list[$month.'']['data'][$fday];
                                                $firstAttendStatus = $fattend['name'];
                                                $firstAttendMemo = $fattend['memo'];
                                                $isfRest = $fattend['rest'];

                                                if(isset($list[$month.'']['data'][$sday])) {
                                                    $sattend = $list[$month.'']['data'][$sday];
                                                    $secondAttendStatus = $sattend['name'];
                                                    $secondAttendMemo = $sattend['memo'];
                                                    $issRest = $sattend['rest'];
                                                }
                                            }
                                        }
                                        ?>
                                        <td style="width:3.8%;text-align: center" @if($isfRest) class="rest" @endif>
                                            @if($fday != 15)
                                                <a @if(!empty($firstAttendMemo)) class="hide-option" title="{{ $firstAttendMemo }}" @endif>
                                                    <?php $firstUrl = \App\Http\Controllers\Util::getStampUrl($firstAttendStatus, $signPath, 1); ?>
                                                    @if($firstUrl != null)
                                                        <img src="{{$firstUrl}}" style="width: 30px;height: 20px;">
                                                    @else
                                                        {{$firstAttendStatus}}
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td style="width:3.8%;text-align: center" @if($issRest) class="rest" @endif>
                                            <a @if(!empty($secondAttendMemo)) class="hide-option" title="{{ $secondAttendMemo }}" @endif>
                                                <?php $secondUrl = \App\Http\Controllers\Util::getStampUrl($secondAttendStatus, $signPath, 1); ?>
                                                @if($secondUrl != null)
                                                    <img src="{{$secondUrl}}" style="width: 30px;height: 20px;">
                                                @else
                                                    {{$secondAttendStatus}}
                                                @endif
                                            </a>
                                        </td>
                                    @endfor

                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="black br-hblue">
                                <th class="center" rowspan="2">{{transBusinessManage("captions.year_days")}}</th>
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
                location.href = 'memberYearReport?userId='+ memberId +'&year='+selyear;
            });

            $('.print-year-attend').on('click', function () {
                $token = '{!! csrf_token() !!}';
                $year = $("#selyear").val();
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
                $param = "_token="+$token+"&userId="+$userId+"&year="+$year+
                        "&days="+$days+"&rest="+$rest+"&work="+$work+
                        "&attend="+$attend+"&absence="+$absence+"&type_2="+$type_2+"&type_3="+$type_3+$typeUrl;
                $url = "memberYearReportPrint";
                submitData($url, $param, "POST", 'target="print"');
            });
        });

    </script>
@endsection