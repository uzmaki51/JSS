@extends('layout.header')
@section('content')
    <div class="main-content">
        <style>
            td {
                height : 38px;
            }
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-4">
                    <h4>
                        <b>{{transBusinessManage("title.EnterpriseDayAttend")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transBusinessManage("title.UnitAttendBrowser")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-5">
                    <h4 class="blue"><strong> {{$unitName}} {{transBusinessManage("title.UnitAttendBrowser_Small")}}</strong></h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="input-group col-md-3">
                        <input class="form-control date-picker" id="search-date" name="search-date" type="text"
                               data-date-format="yyyy/mm/dd" value="{{convert_date($date)}}">
                        <span class="input-group-addon">
                            <i class="icon-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div class=" table-responsive" id="table-container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:60px">{{transBusinessManage("captions.no")}}</th>
                                <th class="center">{{transBusinessManage("captions.duty")}}</th>
                                <th class="center">{{transBusinessManage("captions.name")}}</th>
                                <th class="center">{{transBusinessManage("captions.enterstate")}}</th>
                                <th class="center" style="width:200px">{{transBusinessManage("captions.registertime")}}</th>
                                <th class="center" style="width: 50%">{{transBusinessManage("captions.memo")}}</th>
                            </tr>
                            </thead>
                            <tbody id="attend_list_table">
                            <?php $index = ($paginate->currentPage() - 1) * $paginate->perPage() + 1;?>
                            @foreach($attendUsers as $member)
                                <tr>
                                    <td class="center">{{$index++}}</td>
                                    <td class="center">{{$member->title}}</td>
                                    <td class="center">{{$member->realname}}</td>
                                    <td class="center">{{$member->statusName}}</td>
                                    <td class="center">{{convert_datestr($member->regDate)}}</td>
                                    <td>{{$member->memo}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $paginate->render() !!}
                    </div>
                    <div class="hr hr-18 dotted hr-double"></div>
                    <div class="center">
                        <span id="restAlarm" class="red" style="display: none">&nbsp;&nbsp;&nbsp;今天是休息日。</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>

        var unitId = '{!! $unitId !!}';
        $(function () {

            $("#search-date").bind("change", function () {
                var selDate = $("#search-date").val();
                if(selDate.length < 10)
                    return;
                location.href = 'unitAttendDayPage?unit=' + unitId + '&selDate=' + selDate;
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