<table class="table table-bordered table-hover">
    <thead class="table-head">
    <tr class="black br-hblue">
        <th class="center" style="width: 150px;">{{transBusinessManage("captions.everweek")}}</th>
        <th class="center" style="width: 100px;">{{transBusinessManage("captions.inputtime")}}</th>
        <th class="center">{{transBusinessManage("captions.plain")}}</th>
        <th class="center">{{transBusinessManage("captions.report")}}</th>
        <th class="center" style="width: 40px"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $report)
        <tr style="height: 34px;@if ($report['select'] == 1 ) background-color: #b8d6ff; @endif">
            <input type="hidden" class="plan-id" value="{{$report['id']}}">
            <input type="hidden" class="plan-year" value="{{$report['planYear']}}">
            <input type="hidden" class="plan-week" value="{{$report['planWeek']}}">
            <input type="hidden" class="plan-plan" value="{{$report['plan']}}">
            <input type="hidden" class="plan-report" value="{{$report['report']}}">
            <td class="center">{{$report['selDate']}}</td>
            <td class="center">{!! convert_datetime($report['update_at']) !!}</td>
            <td class="plan" style="vertical-align: top;">{!! nl2br($report['plan']) !!}</td>
            <td class="report" style="vertical-align: top;">{!! nl2br($report['report']) !!}</td>
            <td class="action-buttons">
                <a href="javascript:void(0)" class="week-edit">
                    <i class="blue icon-edit bigger-130"></i>
                </a>
                <a href="javascript:void(0)" class="week-save" style="display: none">
                    <i class="red icon-save bigger-130"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
