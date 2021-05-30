@extends('layout.header')

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
    <style>
        [v-cloak] { display: none; }
    </style>
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>审批文件</b></h4>
                </div>
            </div>
            <div class="space-6"></div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <select class="custom-select d-inline-block" id="year" style="width: auto;">
                            <option value="">全部</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}年</option>
                            @endforeach
                        </select>
                        <select class="custom-select d-inline-block" id="month">
                            <option value="">全部</option>
                            <option value="1">1月</option>
                            <option value="2">2月</option>
                            <option value="3">3月</option>
                            <option value="4">4月</option>
                            <option value="5">5月</option>
                            <option value="6">6月</option>
                            <option value="7">7月</option>
                            <option value="8">8月</option>
                            <option value="9">9月</option>
                            <option value="10">10月</option>
                            <option value="11">11月</option>
                            <option value="12">12月</option>
                        </select>
                        <label style="margin-left: 8px;">对象</label>              
                        <select type="text" class="custom-select d-inline-block" id="ship_name" style="width:80px">
                            <option value="">全部</option>
                            <option value="OBJ">个体</option>
                            @if(isset($shipList))
                                @foreach($shipList as $key => $item)
                                    <option value="{{ $item->IMO_No }}" {{ isset($shipId) && $shipId == $item->IMO_No ?  "selected" : "" }}>
                                        {{ $item->NickName == '' ? $item->shipName_En : $item->NickName }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group f-right">
                            @if(!Auth::user()->isAdmin)
                                <a href="#modal-wizard" class="btn btn-sm btn-success no-radius show-modal" role="button" data-toggle="modal">
                                    <img src="{{ cAsset('assets/images/submit.png') }}" class="report-label-img">起草
                                </a>
                            @endif
                            <a class="btn btn-sm btn-warning refresh-btn-over" type="button" onclick="refresh()">
                                <i class="icon icon-table"></i>{{ trans('common.label.excel') }}
                            </a>                            
                            <a href="#modal-wizard" class="only-modal-show d-none" role="button" data-toggle="modal"></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-2"></div>
                    <div class="table-responsive head-fix-div common-list">
                        <table id="report_info_table" class="table table-bordered">
                            <thead>
                            <tr class="br-hblue">
                                <th class="text-center style-normal-header" style="width: 5%;">{!! trans('decideManage.table.no') !!}</th>
                                <th style="width: 5%;">{!! trans('decideManage.table.type') !!}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.date') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.shipName') }}</th>
                                <th style="width: 7%;">{{ trans('decideManage.table.voy_no') }}</th>
                                <th style="width: 7%;">{!! trans('decideManage.table.profit_type') !!}</th>
                                <th style="width: 25%;">{{ trans('decideManage.table.content') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.currency') }}</th>
                                <th style="width: 10%;">{{ trans('decideManage.table.amount') }}</th>
                                <th style="width: 5%;">{{ trans('decideManage.table.reporter') }}</th>
                                <th style="width: 5%;">涉及<br>部门</th>
                                <th style="width: 5%;">{!! trans('decideManage.table.attachment') !!}</th>
                                <th style="width: 5%;">{!! trans('decideManage.table.state') !!}</th>
                                <th class="{{ Auth::user()->isAdmin == SUPER_ADMIN ? '' : 'd-none' }}"></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="modal-wizard" class="modal modal-draggable" aria-hidden="true" style="display: none; margin-top: 15%;">
                    <div class="dynamic-modal-dialog">
                        <div class="dynamic-modal-content" style="border: 0;width:400px!important;">
                            <div class="dynamic-modal-header" data-target="#modal-step-contents">
                                <div class="table-header">
                                    <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                        <span class="white">&times;</span>
                                    </button>
                                    <h4 style="padding-top:10px;font-style:italic;">草稿</h4>
                                </div>
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">
                                <div class="row">
                                    <form role="form" method="POST" action="{{url('decision/report/submit')}}" enctype="multipart/form-data" id="report-form">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="reportId" value="">
                                        <input type="hidden" name="reportType" value="0">
                                        <input type="hidden" name="draftId" value="{{ $draftId }}">
                                        <div class="table-responsive" id="report_div" v-cloak>
                                            <input type="hidden" name="object_type" v-model="object_type">
                                            <table class="table table-bordered" style="table-layout: fixed">
                                                <tbody>
                                                <tr>
                                                    <td class="d-flex" colspan="2">
                                                        <label for="obj_type_ship" class="d-inline-block">船舶</label>
                                                        <input type="radio" name="obj_type" id="obj_type_ship" class="form-control d-inline-block mt-0" checked value="{{ OBJECT_TYPE_SHIP }}" @change="changeObjType">
                                                        <label for="obj_type_person" class="d-inline-block">个体</label>
                                                        <input type="radio" name="obj_type" id="obj_type_person" class="form-control d-inline-block mt-0" value="{{ OBJECT_TYPE_PERSON }}" @change="changeObjType">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label">
                                                        申请日期
                                                    </td>
                                                    <td class="custom-modal-td-text1">
                                                        <input type="text" name="report_date" style="display: inline-block;" class="form-control white-bg date-picker" v-model="report_date" @click="dateModify($event)" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label" >
                                                        文件种类
                                                    </td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="flowid" class="form-control width-100" :class="reportTypeCls(currentReportType)" @change="onGetProfit($event)" required v-model="currentReportType">
                                                            <option v-for="(item, index) in reportType" v-bind:value="index" :class="reportTypeCls(index)">@{{ item }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr v-show="object_type == 1">
                                                    <td class="custom-modal-td-label">对象</td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="shipNo" class="form-control width-100" @change="onGetVoyNoList($event)" required v-model="currentShipNo">
                                                            <option v-for="(item, index) in shipList" v-bind:value="item.IMO_No">@{{ item.NickName }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr v-show="object_type == 1">
                                                    <td class="custom-modal-td-label">
                                                        航次
                                                    </td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="voyNo" class="form-control width-100" required v-model="currentVoyNo">
                                                            <option v-for="(item, index) in voyNoList" v-bind:value="item.Voy_No">@{{ item.Voy_No }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr v-show="object_type == 2">
                                                    <td class="custom-modal-td-label">对象</td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="obj_no" class="form-control width-100" required v-model="currentObjectNo">
                                                            <option v-for="(item, index) in objectList" v-bind:value="item.id">@{{ item.person }}</option>
                                                        </select>
                                                    </td>                                                    
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label">收支种类</td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="profit_type" class="form-control width-100 transparent-input" :class="reportTypeCls(currentReportType)" required v-model="currentProfitType">
                                                            <option v-for="(item, index) in profitType" v-bind:value="index">@{{ item }}</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="custom-modal-td-label">币类</td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="currency" class="form-control width-100 font-weight-bold" v-model="currentCurrency" :class="currencyCls(currentCurrency)">
                                                            <option v-for="(item, index) in currency" v-bind:value="index" class="font-weight-bold" :class="currencyCls(index)">@{{ item }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label" >
                                                        金额
                                                    </td>
                                                    <td class="custom-modal-td-text1">
                                                        <my-currency-input v-model="amount" :class="reportTypeCls(currentReportType)" class="form-control transparent-input" :class="creditClass(item.credit)" name="amount" v-bind:prefix="''" v-bind:fixednumber="2" v-bind:type="'credit'" required></my-currency-input>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="custom-modal-td-label">申请人</td>
                                                    <td class="custom-modal-td-text1">
                                                        <input type="text" name="decTitle" id="decTitle" class="form-control transparent-input" style="width: 100%" v-bind:value="reporter" disabled>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label">涉及部门</td>
                                                    <td class="custom-modal-td-text1">
                                                        <select name="depart_id" class="form-control width-100" v-model="currentDepartment">
                                                            <option v-for="(item, index) in department" v-bind:value="item.id">@{{ item.title }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label">摘要</td>
                                                    <td class="custom-modal-td-text1" colspan="2">
                                                        <textarea name="content" class="form-control" rows="2">@{{ content }}</textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="custom-modal-td-label" >凭证文件</td>
                                                    <td class="custom-td-dec-text" colspan="2">
                                                        <div class="attachment-div d-flex">
                                                            <label for="attach" class="ml-1 blue contract-attach d-flex">
                                                                <span style="width: 186px;" class="text-ellipsis">@{{ fileName }}</span>
                                                                <button type="button" class="btn btn-danger p-0" style="min-width: 30px;" @click="removeFile"><i class="icon-remove mr-0"></i></button>
                                                            </label>
                                                            <input type="file" id="attach" name="attachment" class="d-none" @change="onFileChange">
                                                            <input type="hidden" name="file_remove" id="file_remove" value="0">
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div  v-show="reportStatus">
                                                <div class="btn-group f-left mt-20 d-flex">
                                                    <button type="button" class="btn btn-success small-btn ml-0" @click="reportSubmit($event)">
                                                        <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">{{ trans('decideManage.button.submit') }}
                                                    </button>
                                                    <div class="between-1"></div>
                                                    <button type="button" class="btn btn-warning small-btn save-draft" @click="saveDraft($event)">
                                                        <img src="{{ cAsset('assets/images/draft.png') }}" class="report-label-img">{{ trans('decideManage.button.draft') }}
                                                    </button>
                                                    <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>{{ trans('decideManage.button.cancel') }}</a>
                                                </div>
                                            </div>
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
        var draftId = '{!! $draftId !!}';
        var isAdmin = '{!! Auth::user()->isAdmin !!}';
        var REPORT_TYPE_EVIDENCE_IN = '{!! REPORT_TYPE_EVIDENCE_IN !!}';
        var REPORT_TYPE_EVIDENCE_OUT = '{!! REPORT_TYPE_EVIDENCE_OUT !!}';
        var DEFAULT_CURRENCY = '{!! CNY_LABEL !!}';
        var OBJECT_TYPE_SHIP = '{!! OBJECT_TYPE_SHIP !!}';

        $(function() {
            initialize();
        });


        // Object events
        $('#report_info_table').on('click', 'tr', function(evt) {
            let cell = $(evt.target).closest('td');
            let reportId = $(this).attr('data-index');
            let reportStatus = $(this).attr('data-status');
            let isAttach = $(this).attr('is-attach');
            if(reportId == undefined) return false;
            if(cell.index() == 12) {
                if(reportStatus != 0) return false;
                if(isAdmin != 1) return false;
                decideReport(reportId, reportStatus);
            } else if(cell.index() != 11 && cell.index() != 13 && isAdmin != 1) {
                $(this).addClass('selected');
                $('[name=draftId]').val(-1);
                showReportDetail(reportId);
            } else if(cell.index() == 11) {
                if(isAttach == 0) {
                    $(this).addClass('selected');
                    $('[name=draftId]').val(-1);
                    showReportDetail(reportId);
                }
            }


            return true;
        });
        Vue.component('my-currency-input', {
            props: ["value", "fixednumber", 'prefix', 'type', 'index'],
            template: `
                    <input type="text" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true; $event.target.select()" v-on:keyup="keymonitor" />
                `,
            data: function() {
                return {
                    isInputActive: false
                }
            },

            computed: {
                displayValue: {
                    get: function() {
                        if (this.isInputActive) {
                            if(isNaN(this.value))
                                return 0;

                            return this.value == 0 ? '' : this.value;
                        } else {
                            let fixedLength = 2;
                            let prefix = '';
                            if(this.fixednumber != undefined)
                                fixedLength = this.fixednumber;

                            if(this.prefix != undefined)
                                prefix = this.prefix + '';
                            
                            if(this.value == 0 || this.value == undefined || isNaN(this.value))
                                return '';
                            
                            return number_format(this.value, fixedLength);
                        }
                    },
                    set: function(modifiedValue) {
                        if (modifiedValue == 0 || modifiedValue == undefined || isNaN(modifiedValue)) {
                            modifiedValue = 0
                        }
                        
                        this.$emit('input', parseFloat(modifiedValue));
                    },
                },
            },
            methods: {
                keymonitor: function(e) {
                    if(e.keyCode == 9 || e.keyCode == 13)
                        $(e.target).select()
                },
                setValue: function() {

                }
            },
            watch: {
                setFocus: function(e) {
                    $(e.target).select();
                }
            }
        });
        function decideReport(reportId, status) {
            let decideType = 0;
            let message = '';
            bootbox.dialog({
                title: '审批文件',
                message: '你确定要审批吗?',
                size: 'large',
                onEscape: true,
                backdrop: true,
                buttons: {
                    fee: {
                        label: '承认',
                        className: 'btn-success',
                        callback: function(){
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
                        }
                    },
                    fi: {
                        label: '拒绝',
                        className: 'btn-info',
                        callback: function() {
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
                        }
                    }
                }
            })
        }

        function showReportDetail(reportId) {
            $.ajax({
                url: BASE_URL + 'ajax/report/detail',
                type: 'post',
                data: {
                    reportId: reportId
                },
                success: function(data, status, xhr) {
                    $('[name=reportId]').val(reportId);
                    let result = data['list'];
                    let attach = data['attach'];
                    reportObj.object_type = result['obj_type'];
                    if(result['obj_type'] == OBJECT_TYPE_SHIP) {
                        $('#obj_type_person').prop('checked', false);
                        $('#obj_type_ship').prop('checked', true);
                        getVoyList(result['shipNo'], result['voyNo']);
                    } else {
                        $('#obj_type_person').prop('checked', true);
                        $('#obj_type_ship').prop('checked', false);
                        getObject(result['obj_no']);
                    }
                    
                    reportObj.report_date = result['report_date'];
                    reportObj.currentReportType = result['flowid'];
                    reportObj.currentShipNo = result['shipNo'];
                    reportObj.amount = result['amount'];
                    reportObj.currentCurrency = result['currency'];
                    reportObj.content = result['content'];
                    
                    disableProfit(result['flowid'], result['profit_type']);

                    if(attach != null && attach != undefined) 
                        reportObj.fileName = attach['file_name'];
                    else 
                        reportObj.fileName = '添加附件';

                    if(result['state'] == '{!! REPORT_STATUS_REQUEST !!}' || result['state'] == '{!! REPORT_STATUS_DRAFT !!}') {
                        reportObj.reportStatus = true;
                    } else {
                        reportObj.reportStatus = false;
                    }
                    
                    if($('[name=draftId]').val() == -1)
                        $('.save-draft').attr('disabled', 'disabled');

                    $('.only-modal-show').click();
                },
                error: function(error) {
                }
            });

            $('.show-modal').on('click', function() {
                reportObj.init();
            });
        }

        $('.show-modal').on('click', function() {
            $('[name=reportId]').val('');
            if($('[name=draftId]') != -1)
                $('.save-draft').removeAttr('disabled');

            reportObj.init();
        });

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
                    else {
                        if(data['voyList'] != undefined && data['voyList'].length > 0)
                            reportObj.currentVoyNo = data['voyList'][0].Voy_No;
                    }
                        
                }
            });
        }

        function getObject(obj_no = '') {
            $.ajax({
                url: BASE_URL + 'ajax/object',
                type: 'post',
                success: function(data, status, xhr) {
                    reportObj.objectList = data;
                    if(obj_no != '') {
                        reportObj.currentObjectNo = obj_no;
                    }
                }
            });
        }

        function getProfit(profitType, selected = false) {
            reportObj.profitType = FeeTypeData[profitType];
            reportObj.currentProfitType = 1;
            if(selected != false)
                reportObj.currentProfitType = selected;
        }

        function disableProfit(type, selected) {
            if(type == 'Contract') {
                reportObj.currentProfitType = '';
                $('[name=profit_type]').attr('disabled', 'disabled');
                $('[name=amount]').attr('disabled', 'disabled');
                $('[name=currency]').attr('disabled', 'disabled');
            } else if(type == 'Other') {
                $('[name=profit_type]').attr('disabled', 'disabled');
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

        function initialize() {
            $.ajax({
                url: BASE_URL + 'ajax/getDepartment',
                type: 'post',
                success: function(data, status, xhr) {
                    reportObj.department = data;
                    if(data != undefined && data != null && data.length > 0)
                        reportObj.currentDepartment = data[0].id;
                },
                error: function(error) {
                    console.log(error)
                }
            });
            getObject();

            // Create new Vue obj.
            reportObj = new Vue({
                el: '#report_div',
                data: {
                    report_date: '',
                    object_type: OBJECT_TYPE_SHIP,
                    reportType: ReportTypeData,
                    shipList: [],
                    voyNoList: [],
                    profitType: [],
                    objectList: [],
                    amount: 10,
                    currency: CurrencyLabel,
                    reporter: reportName,
                    department: '',
                    content: '',
                    fileName: '添加附件',

                    currentReportType: REPORT_TYPE_EVIDENCE_IN,
                    currentShipNo: '',
                    currentProfitType: '',
                    currentVoyNo: '',
                    currentObjectNo: '',
                    currentCurrency: DEFAULT_CURRENCY,
                    currentDepartment: '',
                    currentAmount: '',
                    currentContent: '',

                    reportStatus: 1,
                },
                filters: {

                },
                methods: {
                    init() {
                        this.voyNoList = [];
                        this.profitType = [];
                        this.amount = 0;
                        this.report_date = this.getToday('-');
                        this.currency = CurrencyLabel;
                        this.reporter = reportName;
                        this.content = '';
                        reportObj.attachments = [];

                        this.currentReportType = REPORT_TYPE_EVIDENCE_IN;
                        if(this.shipList.length > 0)
                            this.currentShipNo = this.shipList[0].IMO_No;
                        getVoyList(this.currentShipNo);
                        
                        if(this.profitType.length > 0)
                            this.currentProfitType = this.profitType[0];

                        this.currentVoyNo = '';
                        this.currentCurrency = DEFAULT_CURRENCY;

                        this.reportStatus = 1;

                        getProfit(REPORT_TYPE_EVIDENCE_IN);
                        this.getDepartment();
                    },
                    onGetProfit(event) {
                        let type = event.target.value;
                        disableProfit(type, false);
                    },
                    onGetVoyNoList(event) {
                        getVoyList(event.target.value);
                    },
                    getDepartment() {

                    },
                    getToday: function(symbol) {
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + symbol + mm + symbol + dd;

                        return today;
                    },
                    onFileChange(e) {
                        var files = e.target.files || e.dataTransfer.files;
                        let fileName = files[0].name;
                        this.fileName = fileName;
                        $('#file_remove').val(0);
                    },
                    removeFile() {
                        this.fileName = '添加附件';
                        $('#contract_attach').val('');
                        $('#file_remove').val(1);
                    },
                    reportTypeCls: function(item) {
                        return item == 'Credit' ? 'text-profit font-weight-bold' : 'text-black';
                    },
                    currencyCls: function(item) {
                        let className = '';
                        if(item == 'CNY')
                            className = 'text-danger';
                        else if(item == 'USD') {
                            className = 'text-profit';
                        } else {
                            className = 'text-black';
                        }

                        return className;
                    },
                    changeObjType: function(e) {
                        let value = $(e.target).val();
                        this.object_type = value;
                    },
                    dateModify(e) {console.log(e)
                        $(e.target).on("change", function() {
                            reportObj.report_date = $(this).val();
                        });
                    },
                    reportSubmit(e) {
                        $('[name=reportType]').val(0);
                        let obj_type = reportObj.object_type;
                        let shipNo = 'required';
                        let voyNo = 'required';
                        let profit_type = 'required';
                        let amount = 'required';
                        let currency = 'required';
                        let content = 'required';
                        let obj_no = 'required';

                        let shipNoMsg = '请选择对象。';
                        let obj_noMsg = '请选择对象。';
                        let voyNoMsg = '请选择航次号码。';
                        let profit_typeMsg = '请选择收支种类。';
                        let amountMsg = '请输入金额。';
                        let currencyMsg = '请选择币类。'
                        let contentMsg = '请输入摘要。';

                        if(obj_type != OBJECT_TYPE_SHIP) {
                            shipNo = '';
                            voyNo = '';
                        }

                        if($('[name=flowid]').val() == 'Contract' || $('[name=flowid]').val() == 'Other') {
                            profit_type = '';
                            amount = '';
                            currency = '';
                        }

                        let validateParams = {
                            rules: {
                                shipNo : shipNo,
                                voyNo: voyNo,
                                profit_type: profit_type,
                                currency: currency,
                                amount: amount,
                                content: content,
                                obj_no: obj_no,
                            },
                            messages: {
                                shipNo : shipNoMsg,
                                voyNo: voyNoMsg,
                                profit_type: profit_typeMsg,
                                currency: currencyMsg,
                                amount: amountMsg,
                                content: contentMsg,
                                obj_no: obj_noMsg,
                            }
                        };

                        if($('#report-form').validate(validateParams)) {
                            $('#report-form').submit();
                            return true;
                        } else 
                            return false;
                        
                    },
                    saveDraft(e) {
                        $('[name=reportType]').val(3);
                        $('#report-form').submit();

                        return true;
                    }
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
                "ordering": false,
                "pageLength": 500,
                columnDefs: [{
                    targets: [2],
                    orderable: false,
                    searchable: false
                }],
                columns: [
                    {data: 'report_id', className: "text-center each"},
                    {data: 'flowid', className: "text-center each"},
                    {data: 'report_date', className: "text-center each"},
                    {data: 'shipName', className: "text-center each"},
                    {data: 'voyNo', className: "text-center each"},
                    {data: 'profit_type', className: "text-center each"},
                    {data: 'content', className: "text-left each"},
                    {data: 'currency', className: "text-center each"},
                    {data: 'amount', className: "text-right each"},
                    {data: 'realname', className: "text-center each"},
                    {data: 'depart_name', className: "text-center each"},
                    {data: 'attachment', className: "text-center each"},
                    {data: 'state', className: "text-center"},
                    {data: null, className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('data-index', data['id']);
                    $(row).attr('data-status', data['state']);
                    if(data['attachment'] == 1)
                        $(row).attr('is-attach', 1);
                    else
                        $(row).attr('is-attach', 0);
                    $('td', row).eq(1).html('').append(
                        '<span data-index="' + data['id'] + '" class="' + (data['flowid'] == "Credit" ? "text-profit" : "") + '">' + ReportTypeData[data['flowid']] + '</span>'
                    );

                    $('td', row).eq(2).html('').append(
                        '<span>' + data['report_date'] + '</span>'
                    );

                    if(data['obj_type'] == OBJECT_TYPE_SHIP) {
                        $('td', row).eq(3).html('').append(
                            '<span>' + data['shipName'] + '</span>'
                        );
                    } else {
                        $('td', row).eq(3).html('').append(
                            '<span>' + data['obj_name'] + '</span>'
                        );
                    }
                    
                    if(data['flowid'] != 'Contract' &&  data['flowid'] != 'Other') {
                        $('td', row).eq(5).html('').append(
                            '<span class="' + (data['flowid'] == "Credit" ? "text-profit" : "") + '">' + FeeTypeData[data['flowid']][data['profit_type']] + '</span>'
                        );  
                    } else {
                        $('td', row).eq(5).html('').append(
                            ''
                        );  
                    }

                    if(data['currency'] != '') {
                        if(data['currency'] == 'CNY') {
                            $('td', row).eq(7).html('').append(
                                '<span class="text-danger">' + CurrencyLabel[data['currency']] + '</span>'
                            );
                        } else if(data['currency'] == 'USD') {
                            $('td', row).eq(7).html('').append(
                                '<span class="text-profit">' + CurrencyLabel[data['currency']] + '</span>'
                            );
                        } else {
                            $('td', row).eq(7).html('').append(
                                '<span>' + CurrencyLabel[data['currency']] + '</span>'
                            );
                        }
                    }

                    if(data['amount'] != 0)
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
                            '</a>' + 
                            '<img src="{{ cAsset('assets/images/cancel.png') }}" onclick="deleteAttach(' + data['id'] + ')" width="10" height="10"></div>'
                        );
                    } else {
                        $('td', row).eq(11).html('').append(
                            '<img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15" style="margin: 2px 4px">'
                        );
                    }

                    let status = '';
                    if (data['state'] == 0) {
                        $('td', row).eq(12).css({'background': '#ffb871'});
                        status = '<div class="report-status"><span>' + ReportStatusData[data['state']][0] + '</span></div>';
                    } else if (data['state'] == 1) {
                        $('td', row).eq(12).css({'background': '#ccffcc'});
                        status = '<div class="report-status"><span><i class="icon-ok"></i></span></div>';
                    } else if (data['state'] == 2) {
                        $('td', row).eq(12).css({'background': '#ff7c80'});
                        status = '<div class="report-status"><span><i class="icon-remove"></i></span></div>';
                    }
                    $('td', row).eq(12).html('').append(status);
                    if(isAdmin == 1)
                        $('td', row).eq(13).html('').append(
                                '<div class="action-buttons"><a class="red" onclick="deleteItem(' + data['id'] + ')"><i class="icon-trash"></i></a></div>'
                        );
                    else
                    $('td', row).eq(13).addClass('d-none');
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');

            $('[name=currency]').on('change', function() {return false;})
            $.ajax({
                url: BASE_URL + 'ajax/report/getData',
                type: 'post',
                success: function(data) {
                    reportObj.shipList = data['shipList'];
                    if(data['shipList'] != undefined && data['shipList'] != null && data['shipList'].length > 0)
                        reportObj.currentShipNo = data['shipList'][0].IMO_No;
                }
            });

            getProfit(REPORT_TYPE_EVIDENCE_IN);
        }

        $('.close-modal').on('click', function() {
            $('table tr').removeClass('selected');
        })

        $(document).mouseup(function(e) {
            var container = $(".report-modal");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('table tr').removeClass('selected');
            }
        });

        function deleteAttach(index) {
            __alertAudio();
            bootbox.confirm("Are you sure you want to delete this attachment?", function (result) {
                if (result) {
                    $.ajax({
                        url: BASE_URL + 'ajax/report/attachment/delete',
                        type: 'post',
                        data: {
                            id: index
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

        $('#year').on('change', function() {
            let year = $(this).val();
            let month = $('#month').val();
            let obj = $('#ship_name').val();

            listTable.column(0).search(year, false, false);
            listTable.column(1).search(month, false, false);
            listTable.column(2).search(obj, false, false);
            listTable.draw();
        });

        $('#month').on('change', function() {
            let month = $(this).val();
            let year = $('#year').val();
            let obj = $('#ship_name').val();

            listTable.column(0).search(year, false, false);
            listTable.column(1).search(month, false, false);
            listTable.column(2).search(obj, false, false);
            listTable.draw();            
        });
        $('#ship_name').on('change', function() {
            let obj = $(this).val();
            let year = $('#year').val();
            let month = $('#month').val();

            listTable.column(0).search(year, false, false);
            listTable.column(1).search(month, false, false);
            listTable.column(2).search(obj, false, false);
            listTable.draw();            
        });

        $('input').attr('autocomplete', 'off');

        $("#modal-wizard").on("hidden.bs.modal", function () {
            if(draftId != -1)
                location.href = "/decision/receivedReport";
        });

    </script>

@stop
