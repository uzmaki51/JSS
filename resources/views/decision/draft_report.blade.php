@extends('layout.header')

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
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
                    <div class="space-2"></div>
                    <div class="table-responsive">
                        <table id="report_info_table" class="table table-bordered">
                            <thead>
                            <tr class="br-hblue">
                                <th style="width: 4%;">号码</th>
                                <th style="width: 4%;">{!! trans('decideManage.table.type') !!}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.date') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.shipName') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.voy_no') }}</th>
                                <th style="width: 7%;">{!! trans('decideManage.table.profit_type') !!}</th>
                                <th style="width: 30%;">{{ trans('decideManage.table.content') }}</th>
                                <th style="width: 4%;">{{ trans('decideManage.table.currency') }}</th>
                                <th style="width: 10%;">{{ trans('decideManage.table.amount') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.reporter') }}</th>
                                <th style="width: 5%;">涉及<br>部门</th>
                                <th style="width: 4%;">{!! trans('decideManage.table.attachment') !!}</th>
                                <th style="width: 2%;"></th>
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

    <script src="{{ cAsset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
	<?php
	echo '<script>';
	echo 'var ReportTypeLabelData = ' . json_encode(g_enum('ReportTypeLabelData')) . ';';
	echo 'var ReportTypeData = ' . json_encode(g_enum('ReportTypeData')) . ';';
	echo 'var ReportStatusData = ' . json_encode(g_enum('ReportStatusData')) . ';';
    echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
    echo 'var FeeTypeData = ' . json_encode(g_enum('FeeTypeData')) . ';';
	echo '</script>';
	?>
    <script>
        var listTable = null;
        var reportObj = null;
        var reportList = null;
        var reportName = '{!! Auth::user()->realname !!}';
        var OBJECT_TYPE_SHIP = '{!! OBJECT_TYPE_SHIP !!}';

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
                    {data: 'content', className: "text-left each"},
                    {data: 'currency', className: "text-center each"},
                    {data: 'amount', className: "text-right each"},
                    {data: 'depart_name', className: "text-center each"},
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
                        '<span data-index="' + data['id'] + '" class="' + (data['flowid'] == "Credit" ? "text-profit" : "") + '">' + __parseStr(ReportTypeData[data['flowid']]) + '</span>'
                    );

                    $('td', row).eq(2).html('').append(
                        '<span>' + data['report_date'] + '</span>'
                    );

                    if(data['obj_type'] == OBJECT_TYPE_SHIP) {
                        $('td', row).eq(3).html('').append(
                            '<span>' + __parseStr(data['shipName']) + '</span>'
                        );
                    } else {
                        $('td', row).eq(3).html('').append(
                            '<span>' + __parseStr(data['obj_name']) + '</span>'
                        );
                    }
                    
                    if(data['flowid'] != 'Contract' &&  data['flowid'] != 'Other') {
                        $('td', row).eq(5).html('').append(
                            '<span class="' + (data['flowid'] == "Credit" ? "text-profit" : "") + '">' + __parseStr(FeeTypeData[data['flowid']][data['profit_type']]) + '</span>'
                        );  
                    } else {
                        $('td', row).eq(5).html('').append(
                            ''
                        );  
                    }

                    if(data['currency'] != '') {
                        if(data['currency'] == 'CNY') {
                            $('td', row).eq(7).html('').append(
                                '<span class="text-danger">' + __parseStr(CurrencyLabel[data['currency']]) + '</span>'
                            );
                        } else if(data['currency'] == 'USD') {
                            $('td', row).eq(7).html('').append(
                                '<span class="text-profit">' + __parseStr(CurrencyLabel[data['currency']]) + '</span>'
                            );
                        } else {
                            $('td', row).eq(7).html('').append(
                                '<span>' + __parseStr(CurrencyLabel[data['currency']]) + '</span>'
                            );
                        }
                    }

                    if(data['amount'] != 0 && data['amount'] != null)
                        $('td', row).eq(8).html('').append(
                            '<span class="' + (data['flowid'] == "Credit" ? "text-profit" : "") + '">' + number_format(data['amount'], 2) + '</span>'
                        );
                    else 
                        $('td', row).eq(8).html('').append(
                            ''
                        );
                        $('td', row).eq(8).attr('style', 'padding-right:5px!important;')


                    if(data['attachment']  == 1) {
                        $('td', row).eq(11).html('').append(
                            '<div class="report-attachment">' + 
                            '<a href="' + data['attach_link'] + '" target="_blank">' +
                                '<img src="{{ cAsset('assets/images/document.png') }}" width="15" height="15">' +
                            '</a></div>'
                        );
                    } else {
                        $('td', row).eq(11).html('').append();
                    }

                    $('td', row).eq(12).html('').append(
                        '<div class="action-buttons"><a class="red" onclick="deleteItem(' + data['id'] + ')"><i class="icon-trash"></i></a></div>'
                    );
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        });

        $('#report_info_table').on('click', 'tr', function(evt) {
            let cell = $(evt.target).closest('td');
            let reportId = $(this).attr('data-index');
            let reportStatus = $(this).attr('data-status');
            if(reportId == undefined) return false;
            if(cell.index() == 12) {
            } else if(cell.index() != 11 && cell.index() != 12) {
                $(this).addClass('selected');
                location.href = '/decision/redirect?id=' + reportId;
            }

            return true;
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

        
        function deleteItem(id) {
            __alertAudio();
            bootbox.confirm("Are you sure you want to delete?", function (result) {
                if (result) {
                    $.ajax({
                        url: BASE_URL + 'ajax/report/delete',
                        type: 'post',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            listTable.draw();
                        }
                    });
                } else {
                    return true;
                }
            });
        }

    </script>

@stop
