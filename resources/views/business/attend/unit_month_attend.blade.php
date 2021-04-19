@extends('layout.header')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-4">
                    <h4>
                        <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.UnitMonthAttend")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-5">
                    {{--<h4 class="blue"><strong> {{$unitName}} {{transBusinessManage("title.MemberMonthAttendPrint")}}</strong></h4>--}}
                    <h4 class="blue"><strong>{{transBusinessManage("title.MemberMonthAttendPrint")}}</strong></h4>
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
                            <button type="button" class="btn btn-primary btn-sm search-btn" style="width: 80px"><i class="icon-search"></i>{{transBusinessManage("captions.search")}}</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class=" table-responsive" id="table_container">
                        <table id="month_show_table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th rowspan="2" class="center">{{transBusinessManage("captions.no")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.duty")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.name")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.month_days")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.restdays")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.legal_workdays")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.enterdays")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.absencecount")}}</th>
                                <th colspan="11" class="center">{{transBusinessManage("captions.among")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.late")}}</th>
                                <th rowspan="2" class="center">{{transBusinessManage("captions.earlyleave")}}</th>
                            </tr>
                            <tr class="black br-hblue">
                                @foreach($typeList as $type)
                                    @if($type['id'] > 3)
                                        <th class="center">{{$type['name']}}</th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <?php $index = ($page - 1) * 15 + 1;?>
                            @foreach($list as $member)
                                <tr>
                                    <td class="celldata center">{{$index++}}</td>
                                    <td class="celldata center">{{$member['pos']}}</td>
                                    <td class="celldata center">
                                        <a id="unitMonthAttend" style="cursor: pointer;" data-userid="{{$member['id']}}" data-year="{{$year}}" data-month="{{$month}}">{{$member['realName']}}</a>
                                    </td>
                                    <td class="celldata center" data-days="{{$dates['days']}}">{{$dates['days']}}</td>
                                    <td class="celldata center" data-rest="{{$dates['rest']}}">{{$dates['rest']}}</td>
                                    <td class="celldata center" data-work="{{$dates['work']}}">{{$dates['work']}}</td>
                                    <td class="celldata center" data-attend="{{$member['attend']}}">{{$member['attend']}}</td>
                                    <td class="celldata center" data-absence="{{$member['absence']}}">{{$member['absence']}}</td>
                                    @foreach($typeList as $type)
                                        @if($type['id'] > 3)
                                            <td class="celldata center" data-type_{{$type['id']}}="{{$member['type_'.$type['id']]}}" >{{$member['type_'.$type['id']]}}</td>
                                        @endif
                                    @endforeach
                                    <td class="celldata center" data-type_2="{{$member['type_2']}}">{{$member['type_2']}}</td>
                                    <td class="celldata center" data-type_3="{{$member['type_3']}}">{{$member['type_3']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $pageHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var page = '{{$page}}' * 1;
        function clearZeroData(){
            var celldata = $(".celldata");
            for (var i = 0; i < celldata.length; i++) {
                if (celldata[i].textContent == '0') {
                    celldata[i].textContent = '';
                }
            }
        }

        $(function () {

            $('.search-btn').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                location.href = 'unitAttendMonthShow?selYear='+selyear + '&selMonth=' + selmonth;
            });

            $('.prev').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                page--;
                location.href = 'unitAttendMonthShow?selYear='+selyear + '&selMonth=' + selmonth + '&page=' + page;
            });

            $('.next').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                page++;
                location.href = 'unitAttendMonthShow?selYear='+selyear + '&selMonth=' + selmonth + '&page=' + page;
            });

            $('.page').on('click', function () {
                var selyear = $("#selyear").val();
                var selmonth = $("#selmonth").val();
                page = $(this).html();
                location.href = 'unitAttendMonthShow?selYear='+selyear + '&selMonth=' + selmonth + '&page=' + page;
            })
            clearZeroData();
        });

        $("a#unitMonthAttend").on("click", function(){
            $token = '{!! csrf_token() !!}';
            $thisObj = $(this);
            $userId = $thisObj.data("userid");
            $year = $thisObj.data("year");
            $month = $thisObj.data("month");
            $parentObj = $thisObj.parent("td").parent("tr").children("td");
            $days = $parentObj.eq(3).data("days");
            $rest = $parentObj.eq(4).data("rest");
            $work = $parentObj.eq(5).data("work");
            $attend = $parentObj.eq(6).data("attend");
            $absence = $parentObj.eq(7).data("absence");
            $typeUrl = "";
            for($i=4; $i<=14; $i++){
                $type_$i = $parentObj.eq(4+$i).data("type_"+$i);
                $typeUrl += "&type_"+$i+"="+$type_$i;
            }
            $type_2 = $parentObj.eq(19).data("type_2");
            $type_3 = $parentObj.eq(20).data("type_3");
            $param = "_token="+$token+"&userId="+$userId+"&year="+$year+"&month="+$month+
                    "&days="+$days+"&rest="+$rest+"&work="+$work+
                    "&attend="+$attend+"&absence="+$absence+"&type_2="+$type_2+"&type_3="+$type_3+$typeUrl;

            $url = "memberMonthAttend";
            submitData($url, $param, "POST", "");
        });

    </script>
@endsection