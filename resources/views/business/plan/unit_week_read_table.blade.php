@if(isset($excel))
    @include('layout.excel-header-include')
    @include('layout.excel-style')
@endif
<table class="table table-bordered table-hover">
    <thead class="table-head">
    <tr class="black br-hblue">
        <th class="center" style="width: 150px;">{{transBusinessManage("captions.everyweek")}}</th>
        <th class="center" style="width: 100px;">{{transBusinessManage("captions.inputtime")}}</th>
        <th class="center">{{transBusinessManage("captions.plain")}}</th>
        <th class="center">{{transBusinessManage("captions.report")}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $report)
        <tr style="height: 34px;@if ($report['select'] == 1 ) background-color: #b8d6ff; @endif">
            <td class="center">{{$report['selDate']}}</td>
            <td class="center">{!! convert_datetime($report['update_at']) !!}</td>
            <td style="vertical-align: top;">{!! nl2br($report['plan']) !!}</td>
            <td style="vertical-align: top;">{!! nl2br($report['report']) !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@if(isset($excel))
    </body>
</html>
@endif