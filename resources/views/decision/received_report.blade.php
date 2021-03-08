@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transDecideManage("title.ReceivedDoc")}}</b>
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
                    <div class="col-sm-2 no-padding">
                        <label class="search-label">{{transDecideManage("captions.approver")}}:</label>
                        <div class="col-md-8" style="padding-left:5px">
                            <input type="text" class="form-control" id="search_creator_name" style="width:100%" value="{{$creator}}">
                        </div>
                    </div>
                    <div class="col-sm-3 no-padding" style="width:30%">
                        <label class="search-label">{{transDecideManage("captions.draftDate")}}:</label>
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
                    <div style="float:left">
                        <button class="btn btn-sm btn-primary no-radius" type="button" onclick="showDecisionReportList()" style="width: 80px">
                            <i class="icon-search"></i>{{transDecideManage("captions.search")}}
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <div class="table-responsive" id="received_list_table">
                        <table id="received_info_table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th style="text-align: center;width: 60px">{{transDecideManage("captions.no")}}</th>
                                <th class="center" style="width:70px">{{transDecideManage("captions.attachFile")}}</th>
                                <th class="center" style="width:80px">{{transDecideManage("captions.processState")}}</th>
                                <th class="center">{{transDecideManage("captions.approveName")}}</th>
                                <th class="center" style="width:15%">{{transDecideManage("captions.approveProcessName")}}</th>
                                <th class="center" style="width:100px">{{transDecideManage("captions.approver")}}</th>
                                <th class="center" style="width:125px">{{transDecideManage("captions.approveDate")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($list) > 0)
                                <?php $index = ($page - 1) * $perPage + 1; ?>
                                @foreach ($list as $reportinfo)
                                    <tr>
                                        <td class="center">{{$index++}}</td>
                                        <td class="center">
                                            @if(!empty($reportinfo->file1))
                                                <a href="/fileDownload?type=report&path={{$reportinfo->file1}}&filename={{$reportinfo->fileName1}}" class="hide-option" title=" {{$reportinfo->fileName1}} ">
                                                    <i class="icon-file bigger-125"></i>
                                                </a>
                                            @endif
                                            @if(!empty($reportinfo->file2))
                                                <a href="/fileDownload?type=report&path={{$reportinfo->file2}}&filename={{$reportinfo->fileName2}}" class="hide-option" title=" {{$reportinfo->fileName2}} ">
                                                    <i class="icon-file bigger-125"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="center">
                                            <div class="progress progress-striped" data-percent="{{$reportinfo->decideCount}}/{{$reportinfo->totalCount}}">
                                                <?php $rate = ($reportinfo->decideCount / $reportinfo->totalCount) * 100; ?>
                                                <div class="progress-bar progress-bar-success" style="width:{{$rate}}%;"></div>
                                            </div>
                                        </td>
                                        <td><a href="decideShow?reportId={{$reportinfo->id}}">{{ $reportinfo->title }}</a></td>
                                        <td class="center">{{$reportinfo->flowTitle}}</td>
                                        <td class="center">{{ $reportinfo->realname}}</td>
                                        <td class="center">{!! convert_datetime($reportinfo->draftDate) !!}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $pageHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        var token = '<?php echo csrf_token() ?>';
        var page = '{!! $page !!}' * 1;

        function showDecisionReportList() {
            var decide_name = $("#search_decide_name").val();
            var flow_type = $("#search_flow_type").val();
            var creator = $("#search_creator_name").val();
            var from_Date = $("#fromDate").val();
            var to_Date = $("#toDate").val();

            var param = '';
            if(decide_name.length > 0)
                param = '?d_name=' + decide_name;
            if(flow_type != "")
                param = (param == '' ? '?' : param + '&') + 'flow=' + flow_type;
            if(creator.length > 0)
                param = (param == '' ? '?' : param + '&') + 'creator=' + creator;
            if(from_Date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'from_date=' + from_Date;
            if(to_Date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'to_date=' + to_Date;

            location.href = 'receivedReport' + param;
        }

        function onReceivedReport(reportId) {

            location.href = '{{url('decision/decideShow')}}' + '?reportId=' + reportId;
        }

        function showReceiveReportPage(pageNum) {
            var decide_name = $("#search_decide_name").val();
            var flow_type = $("#search_flow_type").val();
            var creator = $("#search_creator_name").val();
            var from_Date = $("#fromDate").val();
            var to_Date = $("#toDate").val();

            var param = '';
            if(decide_name.length > 0)
                param = '?d_name=' + decide_name;
            if(flow_type.length > 0)
                param = (param == '' ? '?' : param + '&') + 'flow=' + flow_type;
            if(creator.length > 0)
                param = (param == '' ? '?' : param + '&') + 'creator=' + creator;
            if(from_Date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'from_date=' + from_Date;
            if(to_Date.length > 0)
                param = (param == '' ? '?' : param + '&') + 'to_date=' + to_Date;
            if(pageNum > 0)
                param = (param == '' ? '?' : param + '&') + 'page=' + pageNum;
            location.href = 'receivedReport' + param;

        }

        $(function() {

            $('.btn-success').on('click', function () {
                $("#search_decide_name").val('');
                $("#search_flow_type").val('');
                $("#search_creator_name").val('');
                $("#fromDate").val('');
                $("#toDate").val('');
            });

            $('.prev').on('click', function () {
                page--;
                showReceiveReportPage(page);
            });

            $('.page').on('click', function () {
                page = $(this).html();
                showReceiveReportPage(page);
            });

            $('.next').on('click', function () {
                page;
                showReceiveReportPage(page);
            });

        });
    </script>

@stop
