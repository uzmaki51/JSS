@extends('layout.sidebar')

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('assets/css/datatables.min.css') }}" rel="stylesheet"/>
    <style>
        [v-cloak] { display: none; }
    </style>
@endsection

@section('content')
    <div class="main-content" style="width: 1000px!important;">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transDecideManage("title.ReceivedDoc")}}</b></h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-lg-4 form-group pl-0 mb-0">
                        <div class="btn-group f-left">
                            <a class="btn btn-sm btn-warning right-no-radius refresh-btn-over" type="button" onclick="refresh()">
                                <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">{{ transDecideManage("captions.refresh") }}
                            </a>
                            <a href="#modal-wizard" class="btn btn-sm btn-report-search left-no-radius report-btn-over show-modal" role="button" data-toggle="modal">
                                <img src="{{ cAsset('assets/images/submit.png') }}" class="report-label-img">{{ trans("common.label.add") }}
                            </a>
                            <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group d-flex search-div mb-0">
                        <label class="search-label">{{ transDecideManage("captions.ship_name") }}:</label>
                        <input type="text" class="search-input" id="ship_name">
                    </div>
                    <div class="col-lg-4 form-group d-flex search-div mb-0">
                        <label class="search-label">{{transDecideManage("captions.draftDate")}}:</label>
                        <input class="search-input date-picker" id="fromDate" type="text" data-date-format="yyyy/mm/dd">
                        <i class="icon-calendar bigger-110 search-calendar"></i>
                        <label class="search-label">&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;</label>
                        <input class="search-input date-picker" id="toDate" type="text" data-date-format="yyyy/mm/dd">
                        <i class="icon-calendar bigger-110 search-calendar"></i>

                    </div>
                    <div class="col-lg-2 form-group pr-0 mb-0">
                        <div class="btn-group f-right">
                            <button class="btn btn-sm btn-report-search no-radius" type="button" onclick="doSearch()">
                                <i class="icon-search"></i>{{transDecideManage("captions.search")}}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <div class="table-responsive">
                        <table id="report_info_table" class="table table-bordered">
                            <thead>
                            <tr class="br-hblue">
                                <th style="width: 4%;">{{ trans('decideManage.table.no') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.type') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.date') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.shipName') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.voy_no') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.profit_type') }}</th>
                                <th style="width: 30%;">{{ trans('decideManage.table.content') }}</th>
                                <th style="width: 2%;">{{ trans('decideManage.table.currency') }}</th>
                                <th style="width: 10%;">{{ trans('decideManage.table.amount') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.reporter') }}</th>
                                <th style="width: 2%;">{{ trans('decideManage.table.attachment') }}</th>
                                @if(Auth::user()->isAdmin == SUPER_ADMIN)
                                    <th style="width: 2%;">{{ trans('decideManage.table.state') }}</th>
                                @else
                                    <th class="d-none"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
                    <div class="modal-dialog report-modal">
                        <div class="modal-content">
                            <div class="modal-header" data-target="#modal-step-contents">
                                通知
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">
                                <div class="row">
                                    <div class="table-responsive">
                                        <form role="form" method="POST" action="{{url('decision/report/submit')}}" enctype="multipart/form-data" id="report-form">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="hidden" name="reportId" value="">
                                            <input type="hidden" name="reportType" value="0">
                                            <table class="table table-bordered" id="report_div" v-cloak>
                                                <tbody>
                                                <tr>
                                                    <td class="custom-td-label" >
                                                        文件种类
                                                    </td>
                                                    <td class="custom-td-text1">
                                                        <select name="flowid" class="form-control width-100" @change="onGetProfit($event)" required v-model="currentReportType">
                                                            <option value="">请选择起草。</option>
                                                            <option v-for="(item, index) in reportType" v-bind:value="index">@{{ item }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label" >船名</td>
                                                    <td class="custom-td-text1">
                                                        <select name="shipNo" class="form-control width-100" @change="onGetVoyNoList($event)" required v-model="currentShipNo">
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
                                                        <select name="voyNo" class="form-control width-100" required v-model="currentVoyNo">
                                                            <option value="">请选择航次号码。</option>
                                                            <option v-for="(item, index) in voyNoList" v-bind:value="item.id">@{{ item.CP_ID }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label">收支分类</td>
                                                    <td class="custom-td-text1">
                                                        <select name="profit_type" class="form-control width-100" required v-model="currentProfitType">
                                                            <option value="">请选择收支分类。</option>
                                                            <option v-for="(item, index) in profitType" v-bind:value="item.id">@{{ item.AC_Item_Cn }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label" >
                                                        金额
                                                    </td>
                                                    <td class="custom-td-text1">
                                                        <div style="display: flex;">
                                                            <input type="text" name="amount" style="display: inline-block;" class="form-control" v-bind:value="amount">
                                                            <select name="currency" class="form-control width-auto"  style="display: inline-block;" v-model="currentCurrency">
                                                                <option v-for="(item, index) in currency" v-bind:value="index">@{{ item }}</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label">{{transDecideManage("captions.approveName")}} <span class="require">*</span></td>
                                                    <td class="custom-td-dec-text">
                                                        <input type="text" name="decTitle" id="decTitle" class="form-control" style="width: 100%" v-bind:value="reporter" disabled>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label">{{transDecideManage("captions.content")}} <span class="require">*</span></td>
                                                    <td class="custom-td-dec-text">
                                                        <input name="content" class="form-control" v-bind:value="content">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-td-label" >{{transDecideManage("captions.attachFile")}}</td>
                                                    <td class="custom-td-dec-text">
                                                        <div class="form-group mb-0">
                                                            <input type="file" name="attachments[]" style="display: none;" @change="onFileChange" multiple="multiple" id="file_name"/>
                                                            <label for="file_name" class="upload-btn"><img src="{{ cAsset('assets/images/upload.png') }}" class="report-label-img">请选择文件。</label>
                                                        </div>
                                                        <ul class="attach-list">
                                                            <li class="item" v-for="(item, index) in attachments" v-show="item[2]">
                                                                <div>
                                                                    <input type="hidden" name="is_update[]" v-bind:value="item[1] + '_' + item[3]">
                                                                    <span class="name">@{{ item[0] }}</span>
                                                                    <button type="button" class="btn btn-danger p-0" @click="removeItem(index)"><i class="icon-remove"></i></button>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="btn-group f-right mt-2 d-flex">
                                                <button type="button" class="btn btn-info small-btn ml-0" id="report-submit"><img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">{{ trans('decideManage.button.submit') }}</button>
                                                <div class="between-1"></div>
                                                <button type="button" class="btn btn-warning small-btn" id="save-draft"><img src="{{ cAsset('assets/images/draft.png') }}" class="report-label-img">{{ trans('decideManage.button.draft') }}</button>
                                                <a class="btn btn-danger small-btn" data-dismiss="modal"><i class="icon-remove"></i>{{ trans('decideManage.button.cancel') }}</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
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
        var draftId = '{!! $draftId !!}';
        var isAdmin = '{!! Auth::user()->isAdmin !!}';
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
                    attachments: [],

                    currentReportType: '',
                    currentShipNo: '',
                    currentProfitType: '',
                    currentVoyNo: '',
                    currentCurrency: '',
                    // current
                },
                filters: {
                    formatBytes(a, b) {
                        if (0 == a) return "0 Bytes";
                        var c = 1024,
                            d = b || 2,
                            e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
                            f = Math.floor(Math.log(a) / Math.log(c));
                        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
                    }
                },
                methods: {
                    init() {
                        this.reportType = ReportTypeData;
                        // this.shipList = [];
                        this.voyNoList = [];
                        this.profitType = [];
                        this.amount = 0;
                        this.currency = CurrencyLabel;
                        this.reporter = reportName;
                        this.content = '';
                        this.attachments = [];

                        this.currentReportType = '';
                        this.currentShipNo = '';
                        this.currentProfitType = '';
                        this.currentVoyNo = '';
                        this.currentCurrency = '';
                    },
                    onGetProfit(event) {
                        let type = event.target.value;
                        disableProfit(type, false);
                    },
                    onGetVoyNoList(event) {
                        getVoyList(event.target.value);
                    },
                    onFileChange(e) {
                        var files = e.target.files || e.dataTransfer.files;
                        if(files) {
                            for (var index = 0; index < files.length; index++) {
                                this.attachments.push([files[index].name, 'insert', true, 0]);
                            }
                        }
                    },
                    removeItem(index) {
                        reportObj.attachments[index][1] = 'remove';
                        reportObj.attachments[index][2] = false;
                        this.$forceUpdate();
                    },
                }
            });

            if(draftId != -1)
                showReportDetail(draftId);

            listTable = $('#report_info_table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/decide/receive',
                    type: 'POST',
                },
                // "order": [[ 2, "desc" ]],
                "ordering": false,
                columnDefs: [{
                    targets: [2],
                    orderable: false,
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
                    $(row).attr('data-status', data['state']);
                    $('td', row).eq(0).html('').append(
                        '<span>' + (pageInfo.page * pageInfo.length + index + 1) + '</span>'
                    );
                    $('td', row).eq(1).html('').append(
                        '<span data-index="' + data['id'] + '">' + ReportTypeData[data['flowid']] + '</span>'
                    );

                    $('td', row).eq(2).html('').append(
                        '<span>' + _convertDate(data['create_at']) + '</span>'
                    );

                    if(data['currency'] != '') {
                        $('td', row).eq(7).html('').append(
                            '<span>' + CurrencyLabel[data['currency']] + '</span>'
                        );
                    }


                    if(data['attachment']  == 1) {
                        $('td', row).eq(10).html('').append(
                            '<span><i class="icon-file bigger-125"></i></span>'
                        );
                    } else {
                        $('td', row).eq(10).html('').append();
                    }

                    if(isAdmin != 1) {
                        $('td', row).eq(11).remove();
                    } else {
                        let status = '';
                        if (data['state'] == 0) {
                            $('td', row).eq(11).css({'background': '#ffb871'});
                            status = '<div class="report-status"><span>' + ReportStatusData[data['state']][0] + '</span></div>';
                        } else if (data['state'] == 1) {
                            $('td', row).eq(11).css({'background': '#ccffcc'});
                            status = '<div class="report-status"><span><i class="icon-ok"></i></span></div>';
                        } else if (data['state'] == 2) {
                            $('td', row).eq(11).css({'background': '#ff7c80'});
                            status = '<div class="report-status"><span><i class="icon-remove"></i></span></div>';
                        }
                        $('td', row).eq(11).html('').append(status);
                        // $('td', row).eq(11).append(
                        //     '<div class="report-status"><span class="badge badge-'+ ReportStatusData[data['state']][1] + '">' + ReportStatusData[data['state']][0] + '</span></div>'
                        // );
                    }
                },
            });

            $('.dataTables_length').hide();
            $('.dataTables_info').hide();
            $.ajax({
                url: BASE_URL + 'ajax/report/getData',
                type: 'post',
                success: function(data) {
                    reportObj.shipList = data['shipList'];
                }
            });
        });

        $('#report_info_table').on('click', 'tr', function(evt) {
            let cell = $(evt.target).closest('td');
            let reportId = $(this).attr('data-index');
            let reportStatus = $(this).attr('data-status');
            if(reportId == undefined) return false;
            if(cell.index() == 11) {
                if(reportStatus != 0) return false;
                decideReport(reportId, reportStatus);
            } else {
                showReportDetail(reportId);
            }

            return true;
        });

        function decideReport(reportId, status) {
            let decideType = 0;
            let message = '';
            bootbox.confirm("결재를 승인하겠습니까?", function (result) {
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
                            listTable.draw();
                        },
                        error: function(error, status) {
                            listTable.draw();
                        }
                    });
                } else if(result == false) {
                    decideType = 2;
                    $.ajax({
                        url: BASE_URL + 'ajax/report/decide',
                        type: 'post',
                        data: {
                            reportId: reportId,
                            decideType: decideType
                        },
                        success: function(data, status, xhr) {
                            listTable.draw();
                        },
                        error: function(error, status) {
                            listTable.draw();
                        }
                    });
                } else {
                    return false;
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
                success: function(data, status, xhr) {console.log(data);
                    $('[name=reportId]').val(reportId);
                    let result = data['list'];
                    let attach = data['attach'];
                    reportObj.currentReportType = result['flowid'];
                    reportObj.currentShipNo = result['shipNo'];
                    reportObj.amount = result['amount'];
                    reportObj.currentCurrency = result['currency'];
                    reportObj.content = result['content'];
                    getVoyList(result['shipNo'], result['voyNo']);
                    disableProfit(result['flowid'], result['profit_type']);
                    reportObj.attachments = [];
                    if(attach != undefined && attach.length != 0)
                        attach.forEach(function(value, key) {
                            reportObj.attachments.push([value['file_name'], 'keep', true, value['id']]);
                        });
                    else
                        reportObj.attachments = [];

                    $('.only-modal-show').click();
                },
                error: function(error) {
                }
            });

            $('.show-modal').on('click', function() {
                reportObj.init();
            })
        }

        function getVoyList(shipId, selected = false) {
            $.ajax({
                url: BASE_URL + 'ajax/report/getData',
                type: 'post',
                data: {
                    shipId: shipId
                },
                success: function(data, status, xhr) {
                    reportObj.voyNoList = data['voyList'];
                    if(selected != false)
                        reportObj.currentVoyNo = selected;
                }
            });
        }

        function getProfit(profitType, selected) {
            $.ajax({
                url: BASE_URL + 'ajax/profit/list',
                type: 'post',
                data: {
                    profitType: profitType
                },
                success: function(data, status, xhr) {
                    reportObj.profitType = data;
                    console.log(selected);
                    if(selected != false)
                        reportObj.currentProfitType = selected;

                }
            })
        }

        $('#report-submit').on('click', function() {
            $('[name=reportType]').val(0);

            $('#report-form').validate({
                rules: {
                    flowid : "required",
                    shipNo : "required",
                    voyNo: "required",
                },
                messages: {
                    flowid: "请选择文件种类。",
                    shipNo: "请选择船名。",
                    voyNo: "请选择航次号码。",
                }
            });
            if($('[name=flowid]').val() != 'Contract')
                $('#report-form').validate({
                    rules: {
                        profit_type : "required",
                    },
                    messages: {
                        profit_type: "请选择收支分类。",
                    }
                });

            $('#report-form').submit();

            return true;
        });

        $('#save-draft').on('click', function() {
            $('[name=reportType]').val(3);
            $('#report-form').submit();

            return true;
        });

        function disableProfit(type, selected) {
            if(type == 'Contract') {
                $('[name=profit_type]').attr('disabled', 'disabled');
                $('[name=amount]').attr('disabled', 'disabled');
                $('[name=currency]').attr('disabled', 'disabled');
            } else {
                $('[name=profit_type]').removeAttr('disabled');
                $('[name=amount]').removeAttr('disabled');
                $('[name=currency]').removeAttr('disabled');
                getProfit(type, selected);
            }
        }

        function doSearch() {
            let shipName = $('#ship_name').val();
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();

            listTable.column(0).search(shipName, false, false);
            listTable.column(1).search(fromDate, false, false);
            listTable.column(2).search(toDate, false, false);
            listTable.draw();
        }

        function refresh() {
            if(listTable != null && listTable != undefined)
                listTable.draw();
        }

    </script>

@stop
