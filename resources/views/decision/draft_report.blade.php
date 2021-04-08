@extends('layout.sidebar')

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('assets/css/datatables.min.css') }}" rel="stylesheet"/>
    <style>
        [v-cloak] { display: none; }
    </style>
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{transDecideManage("title.Drafted List")}}</b></h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
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
                                <th style="width: 4%;">{{ trans('decideManage.table.type') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.date') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.shipName') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.voy_no') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.profit_type') }}</th>
                                <th style="width: 30%;">{{ trans('decideManage.table.content') }}</th>
                                <th style="width: 4%;">{{ trans('decideManage.table.currency') }}</th>
                                <th style="width: 10%;">{{ trans('decideManage.table.amount') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.reporter') }}</th>
                                <th style="width: 4%;">{{ trans('decideManage.table.attachment') }}</th>
                                <th style="width: 4%;">{{ trans('decideManage.table.state') }}</th>
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
        $(function() {
            listTable = $('#report_info_table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/decide/draft',
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
                    $('td', row).eq(11).html('').append(
                        '<a href="/decision/redirect?id=' + data['id'] + '"><i class="icon-edit"></i></a>'
                    );
                },
            });

            $('.dataTables_length').hide();
            $('.dataTables_info').hide();
        });

        function doSearch() {
            let shipName = $('#ship_name').val();
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();

            listTable.column(0).search(shipName, false, false);
            listTable.column(1).search(fromDate, false, false);
            listTable.column(2).search(toDate, false, false);
            listTable.draw();
        }

    </script>

@stop
