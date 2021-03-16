@extends('layout.sidebar')
<style>
    .table tbody > tr > td {
        font-size: 12px!important;
    }
    .table tbody > tr > td {
        padding: 4px!important;
    }
    .table tbody > tr > .custom-td-report-text, .table tbody > tr > .custom-td-dec-text {
        padding: 0!important;
    }
    .table .custom-td-label1 {
        width: 40%;
    }

    .form-control {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .chosen-single {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .input-group-addon {
        font-size: 12px!important;
        padding: 0 4px!important;
        border: unset!important;
    }
</style>
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-6">
                <h4><b>{{transDecideManage("title.ApproveDoc")}}</b>
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
                        <input type="text" class="form-control" id="search_creator_name" style="width:100%">
                    </div>
                </div>
                <div class="col-sm-4 no-padding" style="width: 35%;">
                    <label class="search-label">{{transDecideManage("captions.approveDate")}}:</label>
                    <div class=" input-group col-md-4" style="padding-left:5px;width:40%">
                        <input class="form-control date-picker" id="fromDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="@if(isset($from_date)){{$from_date}}@endif">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px;"></i>
                        </span>
                    </div>
                    <label class="search-label" style="padding-top: 5px"> ~</label>
                    <div class=" input-group col-md-4" style="width:40%">
                        <input class="form-control date-picker" id="toDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%" value="@if(isset($to_date)){{$to_date}}@endif">
                        <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px;"></i>
                        </span>
                    </div>
                </div>
                <div style="float:left">
                    <button class="btn btn-sm btn-primary no-radius" type="button" onclick="showDecisionReportList(1)" style="width: 80px">
                        <i class="icon-search"></i>{{transDecideManage("captions.search")}}
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="space-4"></div>
                <div class="table-responsive" id="decidemanage_list_table">
                    <table id="decide_info_table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="black br-hblue">
                            <th>{{ trans('decideManage.table.no') }}</th>
                            <th>{{ trans('decideManage.table.type') }}</th>
                            <th>{{ trans('decideManage.table.date') }}</th>
                            <th>{{ trans('decideManage.table.shipName') }}</th>
                            <th>{{ trans('decideManage.table.voy_no') }}</th>
                            <th>{{ trans('decideManage.table.profit_type') }}</th>
                            <th>{{ trans('decideManage.table.content') }}</th>
                            <th>{{ trans('decideManage.table.currency') }}</th>
                            <th>{{ trans('decideManage.table.amount') }}</th>
                            <th>{{ trans('decideManage.table.reporter') }}</th>
                            <th>{{ trans('decideManage.table.attachment') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($list) > 0)
                            <?php $index = 1?>
                            @foreach ($list as $reportinfo)
                                <tr>
                                    <td class="center">{{$index++}}</td>
                                    <td class="center">
                                        <span class="badge badge-{{ g_enum('ReportTypeLabelData')[$reportinfo->flowid][1] }}">{{ $reportinfo->flowTitle }}</span>
                                    </td>
                                    <td class="center">
                                        {{ _convertDateFormat($reportinfo->draftDate, 'Y-m-d') }}
                                    </td>
                                    <td class="center">
                                        <a href="decideShow?reportId={{$reportinfo->id}}">{{ $reportinfo->shipName }}</a>
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->voyNo }}
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->profit_type }}
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->content }}
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->currency }}
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->amount }}
                                    </td>
                                    <td class="center">
                                        {{ $reportinfo->realname }}
                                    </td>
                                    <td class="center">
                                        @if(!empty($reportinfo->file1))
                                            <a href="/fileDownload?type=report&path={{$reportinfo->file1}}&filename={{$reportinfo->fileName1}}" class="hide-option"
											@if(!empty($reportinfo->fileName1)) title="{{$reportinfo->fileName1}}" @endif>
                                                <i class="icon-file bigger-125"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="center">
                                        <i class="text-info icon-ok bigger-125 accept-btn" title="Accept"></i>
                                        <i class="text-danger icon-remove bigger-125 reject-btn" title="Reject"></i>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! $paginate !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var token = '<?php echo csrf_token() ?>';
    var pageNum = '{{ $page }}' * 1;

    function showDecisionReportList(pageNo) {
        var decide_name = $("#search_decide_name").val();
        var flow_type = $("#search_flow_type").val();
        var creator = $("#search_creator_name").val();
        var from_date = $("#fromDate").val();
        var to_date = $("#toDate").val();

        $.post("decideReportList", {_token:token, decide_name:decide_name,flow_type:flow_type, creator:creator, from_date:from_date, to_date:to_date, page:pageNo}, function(data) {
            if(data) {
                $("#decidemanage_list_table").html(data);
                bindPaginate();
                $( ".hide-option" ).tooltip({
                    hide: {
                        effect: "slideDown",
                        delay: 250
                    }
                });
            }
        });
    }

    function bindPaginate() {
        $('.prev').on('click', function () {
            pageNum--;
            showDecisionReportList(pageNum);
        });

        $('.next').on('click', function () {
            pageNum++;
            showDecisionReportList(pageNum);
        });
        $('.page').on('click', function () {
            pageNum = $(this).html();
            showDecisionReportList(pageNum);
        });
    }

    $(function() {

        bindPaginate();
        $('.btn-success').on('click', function () {
            $("#search_decide_name").val('');
            $("#search_flow_type").val('');
            $("#search_creator_name").val('');
            $("#fromDate").val('');
            $("#toDate").val('');
        });

    });
</script>

@stop
