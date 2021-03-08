@if(isset($excel))
    @include('layout.excel-header-include')
    @include('layout.excel-style')
@else
    <div class="space-10"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-5 col-sm-offset-4">
                <select class="col-md-2" id="cur_year" onchange="ShowLists('month')">
                    @for($i=2017; $i<2025; $i++)
                        <option value="{{$i}}" @if($i == $cur_date['year']) selected @endif>{{$i}}</option>
                    @endfor
                </select>
                <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
                <select class="col-md-2" id="cur_month" onchange="ShowLists('month')">
                    @for($i=1; $i<13; $i++)
                        <option value="{{$i}}" @if($i == $cur_date['month']) selected @endif>{{$i}}</option>
                    @endfor
                </select>
                <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
            </div>
            <button class="btn btn-xs btn-warning no-radius" style="float: right;margin-right: 30px; width :80px" onclick="ShowListsExcel('month')">
                <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
            </button>
        </div>
        <div class="col-md-12">
            <div class="space-10"></div>
            <div class="row" style="margin-left: -1px; margin-right: 2px">
@endif
            <table class="table table-bordered table-hover">
                <thead class="table-head">
                <tr class="black br-hblue">
                    <th class="center" style="width: 2%;">{{transBusinessManage("captions.month")}}No</th>
                    <th class="center" style="width: 7%;">{{transBusinessManage("captions.departmentName")}}</th>
                    <th class="center" style="width: 6%;">{{transBusinessManage("captions.duty")}}</th>
                    <th class="center" style="width: 5%;">{{transBusinessManage("captions.name")}}</th>
                    <th class="center" style="width: 6%;">{{transBusinessManage("captions.inputtime")}}</th>
                    <th class="center" style="width: 35%">{{transBusinessManage("captions.plain")}}</th>
                    <th class="center" style="">{{transBusinessManage("captions.report")}}</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($list as $report)
                    <tr style="height: 34px;">
                        <td class="center">{{ $index++ }}</td>
                        <td class="center">{{ $report->title }}</td>
                        <td class="center">{{ $report->posName }}</td>
                        <td class="center">{{ $report->realname }}</td>
                        <td class="center">{!! convert_datetime($report->update_at) !!}</td>
                        <td style="vertical-align: top;">
                            {!! nl2br($report->plan) !!}
                        </td>
                        <td style="vertical-align: top;">
                            {!! nl2br($report->report) !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
@if(isset($excel))
    </body>
</html>
@else
            </div>
        </div>
    </div>
@endif
