@extends('layout.sidebar')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-6">
                <h4><b>{{transDecideManage("title.Outbox")}}</b>
                </h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="space-6"></div>
                <div class="col-sm-2 no-padding">
                    <label class="search-label">{{transDecideManage("captions.approveName")}}:</label>
                    <div class="col-md-8" style="padding-left:5px">
                        <input type="text" class="form-control" id="search_decide_name">
                    </div>
                </div>
                <div class="col-sm-2 no-padding" style="width:20%">
                    <label class="search-label">{{transDecideManage("captions.approveProcessName")}}:</label>
                    <div class="col-md-7" style="padding-left:5px">
                        <input type="text" class="form-control" id="search_flow_name" style="width:100%">
                    </div>
                </div>
                <div class="col-sm-4 no-padding">
                    <label class="search-label">{{transDecideManage("captions.createDate")}}:</label>
                    <div class=" input-group col-md-4" style="padding-left:5px;width:40%">
                        <input class="form-control date-picker" id="fromDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="{{$from_date}}">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px"></i>
                        </span>
                    </div>
                    <label class="search-label" style="padding-top: 5px"> ~</label>
                    <div class=" input-group col-md-4" style="width:40%">
                        <input class="form-control date-picker" id="toDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="{{$to_date}}">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px"></i>
                        </span>
                    </div>
                </div>
                <div style="float:left;padding-left:15px">
                    <button class="btn btn-sm btn-primary no-radius" type="button" onclick="showDecisionReportList()" style="width: 80px">
                        <i class="icon-search"></i>
                        搜索
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="space-10"></div>
                <div class="table-responsive" id="draftReport_list_table">
                    <table id="decidemanage_info_table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th class="center" style="width: 70px">{{transDecideManage("captions.no")}}</th>
                            <th class="center">{{transDecideManage("captions.approveName")}}</th>
                            <th class="center" style="width:15%">{{transDecideManage("captions.approveProcessName")}}</th>
                            <th class="center" width="125px">{{transDecideManage("captions.createDate")}}</th>
                            <th class="center" style="width: 60px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($list) > 0)
                            <?php $index = ($list->currentPage() - 1) * $list->perPage() + 1; ?>
                            @foreach ($list as $reportinfo)
                                <tr>
                                    <td data-id="{{$reportinfo['id']}}">{{$index++}}</td>
                                    <td>
                                        <a href="Reportedit?reportId={{$reportinfo['id']}}">{{ $reportinfo['decide_name'] }}</a>
                                    </td>
                                    <td>
                                        {{ $reportinfo['flow_name'] }}
                                    </td>
                                    <td class="center">
                                        {!! convert_datetime($reportinfo['update_at']) !!}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="red del-btn">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $index++?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! $list->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var token = '<?php echo csrf_token() ?>';

    function showDraftReportList() {
        var decide_name = $("#search_decide_name").val();
        var flow_name = $("#search_flow_name").val();
        var from_date = $("#fromDate").val();
        var to_date = $("#toDate").val();

        var param = '';
        if(decide_name.length > 0)
            param = '?d_name=' + decide_name;
        if(flow_name.length > 0)
            param = (param == '' ? '?' : param + '&') + 'flow=' + flow_name;
        if(from_date.length > 0)
            param = (param == '' ? '?' : param + '&') + 'from_date=' + from_date;
        if(to_date.length > 0)
            param = (param == '' ? '?' : param + '&') + 'to_date=' + to_date;

        location.href = 'draftReport' + param;
    }


    $(function() {

        $('.del-btn').on('click', function () {
            var obj = $(this).closest('tr').children();
            var reportId = obj.eq(0).data('id');
            var reportName = obj.eq(3).text();

            bootbox.confirm('[' + reportName + ']  确定要删除批准文件吗?', function(result) {
                if(result) {
                    $.post('ReportDelete', {'_token': token, 'reportId': reportId}, function(data){
                        var returnCode = parseInt(data);
                        if (returnCode > 0) {
                            window.location.reload();
                        } else if(returnCode == -1)
                            alert("错误, 数据库中未找到批准文件。");
                        else if(returnCode == -2)
                            alert("错误, 没有删掉批准文件的权限。");
                        else if(returnCode == -2)
                            alert("错误, 已进入批准阶段，不能删除。");
                    });
                }
            });
        });
    });


</script>

@stop
