@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transDecideManage("title.Drafting")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transDecideManage("title.Drafted List")}}
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-2 no-padding">
                        <label class="search-label">{{transDecideManage("captions.approveName")}}:</label>
                        <div class="col-md-8" style="padding-left:5px">
                            <input type="text" class="form-control" id="search_decide_name" value="{{$d_name}}">
                        </div>
                    </div>
                    <div class="col-sm-2 no-padding" style="width:20%">
                        <label class="search-label">{{transDecideManage("captions.approveProcessName")}}:</label>
                        <div class="col-md-7" style="padding-left:5px">
                            <select class="form-control" id="search_flow_type">
                                <option value="">请选择</option>
                                @foreach($decisionFlowList as $key => $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $flow ? "selected" : "" }}>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 no-padding">
                        <label class="search-label">{{transDecideManage("captions.draftDate")}}:</label>
                        <div class=" input-group col-md-4" style="padding-left:5px;width:40%">
                            <input class="form-control date-picker" id="fromDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="{{$from_date}}">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px;"></i>
                        </span>
                        </div>
                        <label class="search-label" style="padding-top: 5px"> ~</label>
                        <div class=" input-group col-md-4" style="width:40%">
                            <input class="form-control date-picker" id="toDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="{{$to_date}}">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px;"></i>
                        </span>
                        </div>
                    </div>
                    <div style="float:left;padding-left:15px">
                        <button class="btn btn-sm btn-primary no-radius" type="button" onclick="showDecisionReportList()" style="width: 80px">
                            <i class="icon-search"></i>
                            {{transDecideManage("captions.search")}}
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <div class="table-responsive" id="reportview_list_table">
                        <table id="reportview_table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th style="text-align: center;width: 60px">{{transDecideManage("captions.no")}}</th>
                                <th class="center" style="width:70px">{{transDecideManage("captions.attachFile")}}</th>
                                <th class="center" style="width:80px">{{transDecideManage("captions.processState")}}</th>
                                <th class="center">{{transDecideManage("captions.approveName")}}</th>
                                <th class="center" style="width:15%">{{transDecideManage("captions.approveProcessName")}}</th>
                                <th class="center" style="width:125px">{{transDecideManage("captions.approveDate")}}</th>
                                <th class="center" style="width:80px">{{transDecideManage("captions.state")}}</th>
                                <th class="center" style="width:60px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($list) > 0)
                                <?php $index = ($list->currentPage() - 1) * $list->perPage() + 1; ?>
                                @foreach ($list as $reportinfo)
                                    <tr>
                                        <td class="center" data-id="{{$reportinfo['id']}}">{{$index++}}</td>
                                        <td class="center">
                                            @if(!empty($reportinfo['file1']))
                                                <a href="/fileDownload?type=report&path={{$reportinfo['file1']}}&filename={{$reportinfo->fileName1}}" class="hide-option" title=" {{$reportinfo->fileName1}} ">
                                                    <i class="icon-file bigger-125"></i>
                                                </a>
                                            @endif
                                            @if(!empty($reportinfo['file2']))
                                                <a href="/fileDownload?type=report&path={{$reportinfo['file2']}}&filename={{$reportinfo->fileName2}}" class="hide-option" title=" {{$reportinfo->fileName2}} ">
                                                    <i class="icon-file bigger-125"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="center">
                                            <div class="progress progress-striped" data-percent="{{$reportinfo['decideCount']}}/{{$reportinfo['totlCount']}}">
                                                <?php $rate = ($reportinfo['decideCount']/$reportinfo['totlCount']) * 100; ?>
                                                <div class="progress-bar progress-bar-success" style="width:{{$rate}}%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($reportinfo['isEdit'] == 1)
                                                <a href="Reportedit?reportId={{$reportinfo['id']}}">{{ $reportinfo['decide_name'] }}</a>
                                            @else
                                                <a href="decideShow?reportId={{$reportinfo['id']}}">{{ $reportinfo['decide_name'] }}</a>
                                            @endif
                                        </td>
                                        <td class="center">{{ $reportinfo['flow_name'] }}</td>
                                        <td class="center">{!! convert_datetime($reportinfo['draftDate']) !!}</td>
                                        <td class="center" style="color: red;width:80px">
                                            @if($reportinfo->eject == 1)
                                                <img src="/img/status_reject.png" style="padding-right:5px">{{transDecideManage("captions.rejected")}}
                                            @elseif($reportinfo->eject == 2)
                                                <img src="/img/status_reserve.png" style="padding-right:5px">{{transDecideManage("captions.deferred")}}
                                            @elseif($reportinfo->flowState == 1)
                                                <img src="/img/status_success.png" style="padding-right:5px">{{transDecideManage("captions.completed")}}
                                            @elseif($reportinfo->flowState == 0)
                                                <img src="/img/status_approve.png" style="padding-right:5px">{{transDecideManage("captions.ongoing")}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($reportinfo['isEdit'] == 1)
                                                <button class="btn btn-danger del-btn">
                                                    <i class="icon-trash bigger-120"></i>
                                                    {{transDecideManage("captions.delete")}}
                                                </button>
                                            @elseif($reportinfo['isEdit'] == 2)
                                                <a href="updateSuspendReport?rId={{$reportinfo['id']}}" class="btn btn-xs btn-success">
                                                    <i class="icon-edit bigger-120"></i>
                                                    {{transDecideManage("captions.change")}}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
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
        var pageNum = 0;
        var token = '<?php echo csrf_token() ?>';

        $(function () {

            $('.btn-success').on('click', function () {
                $("#search_decide_name").val('');
                $("#search_flow_type").val('');
                $("#fromDate").val('');
                $("#toDate").val('');
            });

            $('.del-btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var reportId = obj.eq(0).data('id');
                var reportName = obj.eq(3).text();

                bootbox.confirm('[' + reportName + ']  确定要删除批准文件吗?', function (result) {
                    if (result) {
                        $.post('ReportDelete', {'_token': token, 'reportId': reportId}, function (data) {
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                window.location.reload();
                            } else if (returnCode == -1)
                                alert("错误, 数据库中未找到批准文件。");
                            else if (returnCode == -2)
                                alert("错误, 没有删掉批准文件的权限。");
                            else if (returnCode == -2)
                                alert("错误, 已进入批准阶段，不能删除。");
                        });
                    }
                });
            });
        });

        function showDecisionReportList() {
            var decide_name = $("#search_decide_name").val();
            var flow_type = $("#search_flow_type").val();
            var from_date = $("#fromDate").val();
            var to_date = $("#toDate").val();

            var param = '';
            if (decide_name.length > 0)
                param = '?d_name=' + decide_name;
            if (flow_type != "")
                param = (param == '' ? '?' : param + '&') + 'flow=' + flow_type;
            if (from_date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'from_date=' + from_date;
            if (to_date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'to_date=' + to_date;

            location.href = 'Reportview' + param;
        }

    </script>

@stop
