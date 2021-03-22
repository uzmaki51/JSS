@extends('layout.sidebar')

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('assets/css/datatables.min.css') }}" rel="stylesheet"/>
@endsection

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
                            <input type="text" class="form-control" id="search_decide_name">
                        </div>
                    </div>
                    <div class="col-sm-2 no-padding" style="width:20%">
                        <label class="search-label">{{transDecideManage("captions.approveProcessName")}}:</label>
                        <div class="col-md-7" style="padding-left:5px">
                            <select class="form-control" id="search_flow_type">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 no-padding">
                        <label class="search-label">{{transDecideManage("captions.approver")}}:</label>
                        <div class="col-md-8" style="padding-left:5px">
                            <input type="text" class="form-control" id="search_creator_name" style="width:100%">
                        </div>
                    </div>
                    <div class="col-sm-3 no-padding" style="width:35%">
                        <label class="search-label">{{transDecideManage("captions.draftDate")}}:</label>
                        <div class=" input-group col-md-4" style="padding-left:5px;width:40%">
                            <input class="form-control date-picker" id="fromDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%">
                            <span class="input-group-addon" style="float: right;width:30%;">
                            <i class="icon-calendar bigger-110" style="padding-top: 4px"></i>
                        </span>
                        </div>
                        <label class="search-label" style="padding-top: 5px"> ~</label>
                        <div class=" input-group col-md-4" style="width:40%">
                            <input class="form-control date-picker" id="toDate" type="text" data-date-format="yyyy/mm/dd" style="width:70%">
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
                        <table id="report_info_table" class="table table-bordered">
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
                                <th>{{ trans('decideManage.table.state') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form role="form" method="POST" action="{{url('decision/report/submit')}}" enctype="multipart/form-data" id="report-form">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="reportId" value="">
                            <input type="hidden" name="reportType" value="0">
                            <table class="table table-bordered" id="report_div">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label" >
                                        文件种类
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="flowid" class="form-control width-100" @change="onGetProfit($event)" required>
                                            <option value="">请选择起草。</option>
                                            <option v-for="(item, index) in reportType" v-bind:value="index">@{{ item }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" >
                                        船名
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="shipNo" class="form-control width-100" @change="onGetVoyNoList($event)" required>
                                            <option value="">请选择船舶。</option>
                                            <option v-for="(item, index) in shipList" v-bind:value="item.shipID">@{{ item.shipName_Cn }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" >
                                        {{ trans('decideManage.captions.voy_no') }}
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="voyNo" class="form-control width-100" required>
                                            <option value="">请选择航次号码。</option>
                                            <option v-for="(item, index) in voyNoList" v-bind:value="item.id">@{{ item.CP_ID }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label">收支分类</td>
                                    <td class="custom-td-text1">
                                        <select name="profit_type" class="form-control width-100" required>
                                            <option value="">请选择收支分类。</option>
                                            <option v-for="(item, index) in profitType" v-bind:value="item.id">@{{ item.AC_Item_Cn }}</option>
                                            {{--@if(isset($reportinfo) && $reportinfo['flowid'] != 1)--}}
                                            {{--@foreach($acList as $key => $item)--}}
                                            {{--<option value="{{ $item->id }}" {{ isset($reportinfo) &&  $item->id == $reportinfo['profit_type'] ? "selected" : "" }}>{{ $item->AC_Item_En }}</option>--}}
                                            {{--@endforeach--}}
                                            {{--@endif--}}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" >
                                        金额
                                    </td>
                                    <td class="custom-td-text1" colspan="3">
                                        <div style="display: flex;">
                                            <input type="text" name="amount" style="display: inline-block;" class="form-control" value="{{ isset($reportinfo['amount']) ? $reportinfo['amount'] : '' }}">
                                            <select name="currency" class="form-control width-auto"  style="display: inline-block;">
                                                <option v-for="(item, index) in currency" v-bind:value="index">@{{ item }}</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label">{{transDecideManage("captions.approveName")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="text" name="decTitle" id="decTitle" class="form-control" style="width: 100%" v-bind:value="reporter" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label">{{transDecideManage("captions.content")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text" colspan="6">
                                        <input name="content" class="form-control">{{--@if(isset($reportinfo)){{$reportinfo['content']}}@endif--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" >{{transDecideManage("captions.attachFile")}}1</td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <label style="margin-left:10px" id="labFile1">请选择文件</label>
                                        <input class="hidden" id="fileName1" name="" value="">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-info" id="report-submit">{{ trans('decideManage.button.submit') }}</button>
                            <button type="button" class="btn btn-warning" id="save-draft">{{ trans('decideManage.button.draft') }}</button>
                            <a class="btn btn-warning" href="">{{ trans('decideManage.button.cancel') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
	<?php
	echo '<script>';
	echo 'var ReportTypeLabelData = ' . json_encode(g_enum('ReportTypeLabelData')) . ';';
	echo 'var ReportTypeData = ' . json_encode(g_enum('ReportTypeData')) . ';';
	echo 'var ReportStatusData = ' . json_encode(g_enum('ReportStatusData')) . ';';
	echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
	echo '</script>';
	?>
    <script>
        var listTable = null;
        var reportObj = null;
        var reportList = null;
        var reportName = '{!! Auth::user()->realname !!}';
        $(function() {
            // Create new Vue obj.
            reportObj = new Vue({
                el: '#report_div',
                data: {
                    reportType: ReportTypeData,
                    shipList: [],
                    voyNoList: [],
                    profitType: [],
                    amount: 0,
                    currency: CurrencyLabel,
                    reporter: reportName,
                    content: '',
                    attachment: [],

                },
                filters: {
                    // Balance: function(balance, currency) {
                    //     return removeTrailingZero(number_format(balance, balanceDecimals[currency]));
                    // }
                },
                methods: {
                    onGetProfit(event) {
                        let type = event.target.value;
                        if(type == 'Contract') {
                            $('[name=profit_type]').attr('disabled', 'disabled');
                            $('[name=amount]').attr('disabled', 'disabled');
                            $('[name=currency]').attr('disabled', 'disabled');
                        } else {
                            $('[name=profit_type]').removeAttr('disabled');
                            $('[name=amount]').removeAttr('disabled');
                            $('[name=currency]').removeAttr('disabled');
                            getProfit(event.target.value);
                        }
                    },
                    onGetVoyNoList(event) {
                        getVoyList(event.target.value);
                    }
                },
                computed: {
                    {{--isMainCurrency: function() {--}}
                    {{--return this.coin_list.map(function(coin) {--}}
                    {{--return {--}}
                    {{--'main-currency': coin.currency == '{{ MAIN_CURRENCY }}',--}}
                    {{--'font-weight-bold': coin.currency == '{{ MAIN_CURRENCY }}',--}}
                    {{--}--}}
                    {{--});--}}
                    {{--}--}}
                }
            });

            listTable = $('#report_info_table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/decide/receive',
                    type: 'POST',
                },
                "order": [[ 2, "desc" ]],
                columnDefs: [{
                    targets: [2],
                    orderable: true,
                    searchable: false
                }],
                columns: [
                    {data: 'id', className: "text-center each"},
                    {data: 'flowid', className: "text-center each"},
                    {data: 'create_at', className: "text-center each"},
                    {data: 'shipName', className: "text-center each"},
                    {data: 'voyNo', className: "text-center each"},
                    {data: 'profit_type', className: "text-center each"},
                    {data: 'content', className: "text-center each"},
                    {data: 'currency', className: "text-center each"},
                    {data: 'amount', className: "text-center each"},
                    {data: 'realname', className: "text-center each"},
                    {data: 'attachment', className: "text-center each"},
                    {data: 'state', className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('data-index', data['id']);
                    $('td', row).eq(0).html('').append(
                        '<span>' + (pageInfo.page * pageInfo.length + index + 1) + '</span>'
                    );
                    $('td', row).eq(1).html('').append(
                        '<span data-index="' + data['id'] + '">' + ReportTypeData[data['flowid']] + '</span>'
                    );

                    $('td', row).eq(2).html('').append(
                        '<span>' + _convertDate(data['create_at']) + '</span>'
                    );

                    if(data['attachment']  == 1) {
                        $('td', row).eq(10).html('').append(
                            '<span><i class="icon-file bigger-125"></i></span>'
                        );
                    } else {
                        $('td', row).eq(10).html('').append(
                            '<span></span>'
                        );
                    }

                    $('td', row).eq(11).html('').append(
                        '<div class="report-status"><span class="badge badge-'+ ReportStatusData[data['state']][1] + '">' + ReportStatusData[data['state']][0] + '</span></div>'
                    );

                    reportList = data;
                },
            });

            $.ajax({
                url: BASE_URL + 'ajax/report/getData',
                type: 'post',
                success: function(data) {
                    reportObj.shipList = data['shipList'];
                }
            });
        });

        $(document).on('click', 'tr', function(evt) {
            let cell = $(evt.target).closest('td');
            let reportId = $(this).attr('data-index');
            if(reportId == undefined) return false;
            if(cell.index() == 11)
                decideReport(reportId);
            else
                showReportDetail(reportId);

            return true;
        });

        function decideReport(reportId) {
            let decideType = 0;
            bootbox.confirm("결재를 승인하시려면 승인단추, 부결하려면 부결단추를 눌러주세요.", function (result) {
                if (result) {
                    decideType = 1;
                    $.ajax({
                        url: BASE_URL + 'ajax/report/decide',
                        type: 'post',
                        data: {
                            reportId: reportId,
                            decideType: decideType
                        },
                        success: function(data, status, xhr) {

                        },
                        error: function(error, status) {

                        }
                    });
                    listTable.draw();
                } else {
                    decideType = 2;
                    $.ajax({
                        url: BASE_URL + 'ajax/report/decide',
                        type: 'post',
                        data: {
                            reportId: reportId,
                            decideType: decideType
                        },
                        success: function(data, status, xhr) {

                        },
                        error: function(error, status) {

                        }
                    });
                    listTable.draw();
                }
            });
        }

        function showReportDetail(reportId) {
            $.ajax({
                url: BASE_URL + 'ajax/report/detail',
                type: 'post',
                data: {
                    reportId: reportId
                },
                success: function(data, status, xhr) {
                },
                error: function(error) {
                }
            });
        }

        function getVoyList(shipId) {
            $.ajax({
                url: BASE_URL + 'ajax/report/getData',
                type: 'post',
                data: {
                    shipId: shipId
                },
                success: function(data, status, xhr) {
                    reportObj.voyNoList = data['voyList'];
                }
            });
        }

        function getProfit(profitType) {
            $.ajax({
                url: BASE_URL + 'ajax/profit/list',
                type: 'post',
                data: {
                    profitType: profitType
                },
                success: function(data, status, xhr) {
                    reportObj.profitType = data;
                }
            })
        }

        $('#report-submit').on('click', function() {
            $('[name=reportType]').val(0);
            if($('#report-form').validate())
                $('#report-form').submit();
            else
                return false;
        });

        $('#save-draft').on('click', function() {
            $('[name=reportType]').val(3);
            if($('#report-form').validate())
                $('#report-form').submit();
            else
                return false;
        });
    </script>

@stop
