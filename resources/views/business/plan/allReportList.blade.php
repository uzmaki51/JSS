@if(isset($excel))
    @include('layout.excel-header-include')
    @include('layout.excel-style')
@endif
<table id="AllPersonTable" class="table table-bordered table-hover">
    <thead class="table-head">
    <tr>
        <th class="center" style="width: 50px">{{transBusinessManage("captions.no")}}No</th>
        <th class="center">{{transBusinessManage("captions.department")}}</th>
        <th class="center">{{transBusinessManage("captions.duty")}}</th>
        <th class="center">{{transBusinessManage("captions.name")}}</th>
        <th class="center" style="width:90px;">{{transBusinessManage("captions.inputtime")}}</th>
        <th class="center">{{transBusinessManage("captions.scheduleitem")}}</th>
        <th class="center">{{transBusinessManage("captions.task")}}</th>
        <th class="center">{{transBusinessManage("captions.processstate")}}</th>
        <th class="center">{{transBusinessManage("captions.plain")}}계획</th>
        <th class="center">{{transBusinessManage("captions.report")}}</th>
    </tr>
    </thead>
    <tbody id="All_Person_Report">
    <?php $index = 0; ?>
    @foreach($list as $report)
        <tr>
            <td class="center num">{{ $index + 1}}</td>
            <td class="center unit">{{ $report['title'] }}</td>
            <td class="center pos">{{ $report['pos'] }}</td>
            <td class="center realname">{{ $report['realname'] }}</td>
            <td class="center">{!! convert_datetime($report['update_at']) !!}</td>
            <td class="center">{{ $report['mainPlan'] }}</td>
            <td class="center">{{ $report['task'] }}</td>
            <td class="center">{{ $report['rate'] }}</td>
            <td style="vertical-align: top;">{!! nl2br($report['plan']) !!}</td>
            <td style="vertical-align: top;">{!! nl2br($report['report']) !!}</td>
        </tr>
        <?php $index++ ?>
    @endforeach
    </tbody>
</table>
@if(isset($excel))
    </body>
</html>
@endif