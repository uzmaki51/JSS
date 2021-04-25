@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="main-content">
        <style>
        </style>
        <div class="page-content">
        <form id="wage-form" action="updateWageCalcInfo" role="form" method="POST" enctype="multipart/form-data">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>工资计算</b></h4>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="col-md-12" style="margin-top:4px;">
                <div id="calc_wage" class="tab-pane active">
                    <div class="space-4"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <label class="custom-label d-inline-block" style="padding: 6px;">船名:</label>
                                <select class="custom-select d-inline-block" name="select-ship" id="select-ship" style="width:80px">
                                    <!--option value="" selected></option-->
                                    <?php $index = 0 ?>
                                    @foreach($shipList as $ship)
                                        <?php $index ++ ?>
                                        <option value="{{ $ship['IMO_No'] }}" @if(isset($shipId) && ($shipId == $ship['IMO_No'])) selected @endif>{{$ship['shipName_En']}}</option>
                                    @endforeach
                                </select>
                                <label class="custom-label d-inline-block" style="padding: 6px;">减少天数:</label>
                                <input type="number" name="minus-days" id="minus-days" value="0.5" step="0.5" min="0" autocomplete="off" style="width:60px;margin-right:0px;"/>
                                <label class="custom-label d-inline-block" style="padding: 6px;">汇率:</label>
                                <input type="number" name="rate" id="rate" value="6.5" min="0" step="0.1" autocomplete="off" style="width:80px;margin-right:0px;"/>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="col-md-7">
                                <label class="custom-label d-inline-block" style="padding: 6px;">月份:</label>
                                <select name="select-year" id="select-year" style="font-size:13px">
                                    @for($i=2020;$i<2025;$i++)
                                    <option value="{{$i}}" @if((isset($year) && ($year == $i)) || (date("Y")==$i))selected @endif>{{$i}}年</option>
                                    @endfor
                                </select>
                                <select name="select-month" id="select-month" style="font-size:13px">
                                    @for($i=1;$i<13;$i++)
                                    <option value="{{$i}}" @if((isset($month) && ($month == $i)) || (date("m")==$i))selected @endif>{{$i}}月</option>
                                    @endfor
                                </select>
                                <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="search_info"></span>份工资单</strong>
                            </div>
                            <div class="col-md-5" style="padding:unset!important">
                                <div class="btn-group f-right">
                                    <a onclick="javascript:openAddPage();" class="btn btn-sm btn-primary btn-add" style="width: 80px" data-toggle="modal">
                                        <i class="icon-plus"></i>{{ trans('common.label.add') }}
                                    </a>
                                    <a id="btnSave" class="btn btn-sm btn-info" style="width: 80px">
                                        <i class="icon-save"></i>{{ trans('common.label.save') }}
                                    </a>
                                    <a id="btnRegister" class="btn btn-sm btn-success" style="width: 80px">
                                        <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">{{ trans('common.label.request') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:4px;">
                            <div id="item-manage-dialog" class="hide"></div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="head-fix-div common-list" id="crew-table" style="">
                                    <table id="table-shipmember-list" style="table-layout:fixed;">
                                        <thead class="">
                                            <th class="text-center style-normal-header" style="width: 3%;"><span>No</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>姓名</span></th>
                                            <th class="text-center style-normal-header" style="width: 5%;"><span>职务</span></th>
                                            <th class="text-center style-normal-header" style="width: 2%;"><span>币类</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>合约薪资</span></th>
                                            <th class="text-center style-normal-header" style="width: 7%;"><span>上船日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 7%;"><span>下船/截止日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 4%;"><span>在船天数</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>扣款</span></th>
                                            <th class="text-center style-normal-header" style="width: 8%;"><span>家汇款<br>(¥)</span></th>
                                            <th class="text-center style-normal-header" style="width: 8%;"><span>家汇款<br>($)</span></th>
                                            <th class="text-center style-normal-header" style="width: 8%;"><span>支付日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 9%;"><span>备注</span></th>
                                            <th class="text-center style-normal-header" style="width: 19%;"><span>银行账户</span></th>
                                            <th class="text-center" style=""></th>
                                        </thead>
                                        <tbody class="" id="list-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
    <div id="modal-add-wage" class="modal" aria-hidden="true" style="display: none; margin-top: 15%;">
        <div class="dynamic-modal-dialog">
            <div class="dynamic-modal-content" style="border: 0;width:400px!important;">
                <div class="dynamic-modal-header" data-target="#modal-step-contents">
                    <div class="table-header">
                        <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                            <span class="white">&times;</span>
                        </button>
                        <h4 style="padding-top:10px;font-style:italic;">添加海员</h4>
                    </div>
                </div>
                <div id="modal-body-content" class="modal-body step-content">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" style="table-layout: fixed">
                                <tbody>
                                <tr>
                                    <td class="custom-modal-td-label" style="width:111px!important;">姓名*:</td>
                                    <td><input type="text" name="decTitle" id="add-name" class="form-control" value="缺员" style="width: 100%"></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">职员:</td>
                                    <td>
                                        <select id="add-rank" class="form-control" style="padding-left:unset!important;color:#1565C0!important;">
                                            <option value="" selected>&nbsp;</option>
                                            @foreach($posList as $pos)
                                                <option value="{{$pos['Abb']}}" >{{$pos['Duty_En']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">币类:</td>
                                    <td>
                                        <select id="add-currency" class="form-control" style="padding-left:unset!important;color:#1565C0!important;">
                                            <option value="0">¥</option>
                                            <option value="1">$</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">合约薪资*:</td>
                                    <td><input type="text" id="add-wage" class="form-control" style="width: 100%"></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">上船日期*:</td>
                                    <td><div class="input-group"><input id="add-signon-date" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"><span class="input-group-addon"><i class="icon-calendar "></i></span></div></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">下船/截止日期*:</td>
                                    <td><div class="input-group"><input id="add-signoff-date" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"><span class="input-group-addon"><i class="icon-calendar "></i></span></div></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">扣款:</td>
                                    <td><input type="text" id="add-minus-money" value="0" class="form-control" style="width: 100%"></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">支付日期:</td>
                                    <td><div class="input-group"><input id="add-purchase-date" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"><span class="input-group-addon"><i class="icon-calendar "></i></span></div></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">备注:</td>
                                    <td><textarea type="text" id="add-remark" class="form-control" style="resize:none;" rows="2"></textarea></td>
                                </tr>
                                <tr>
                                    <td class="custom-modal-td-label">银行账号:</td>
                                    <td><textarea type="text" id="add-bank-info" class="form-control" style="resize:none;" rows="2"></textarea></td>
                                </tr>
                                </tbody>
                            </table>
                            <div>
                                <div class="btn-group f-right mt-20 d-flex">
                                    <button data-bb-handler="cancel" type="button" class="btn btn-sm btn btn-success close-modal" data-dismiss="modal">Cancel</button>
                                    <button data-bb-handler="confirm" type="button" class="btn btn-sm btn btn-danger" onclick="addWage();">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    
    <?php
	echo '<script>';
	echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
	echo '</script>';
	?>
    <script>
        var token = '{!! csrf_token() !!}';
        var shipName = '';
        var year = '';
        var month = '';
        var minus_days = 0;
        var rate = 1;
        var shipId;
        var original, info;
        $(function () {
            $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
            $.fn.editableform.buttons = '';
        });
            
        var listTable = null;
        function initTable() {
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/wage/list',
                    type: 'POST',
                    data: { 'year':year, 'month':month, 'minus_days':minus_days, 'rate':rate, 'shipId':shipId},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: null, className: "text-center"},
                    {data: 'name', className: "text-center"},
                    {data: 'rank', className: "text-center"},
                    {data: 'WageCurrency', className: "text-center"},
                    {data: 'Salary', className: "text-center"},
                    {data: 'DateOnboard', className: "text-center"},
                    {data: 'DateOffboard', className: "text-center"},
                    {data: 'SignDays', className: "text-center"},
                    {data: 'MinusCash', className: "text-center"},
                    {data: 'TransInR', className: "text-center"},
                    {data: 'TransInD', className: "text-center"},
                    {data: 'TransDate', className: "text-center"},
                    {data: 'Remark', className: "text-center"},
                    {data: 'BankInformation', className: "text-center"},
                    {data: null, className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('class', 'member-item disable-tr');
                    $(row).attr('data-index', data['no']);
                    
                    $('td', row).eq(0).attr('class', 'text-center disable-td add-no');
                    $('td', row).eq(1).attr('class', 'text-center disable-td');
                    $('td', row).eq(2).attr('class', 'text-center disable-td');
                    if (data['WageCurrency'] == 0)
                        $('td', row).eq(3).html('¥');
                    else
                        $('td', row).eq(3).html('$');
                    $('td', row).eq(3).attr('class', 'text-center disable-td add-currency');
                    $('td', row).eq(4).attr('class', 'text-center disable-td add-salary');
                    $('td', row).eq(5).attr('class', 'text-center disable-td');
                    $('td', row).eq(6).attr('class', 'text-center disable-td');
                    $('td', row).eq(7).attr('class', 'text-center disable-td add-signondays');
                    $('td', row).eq(9).attr('class', 'text-center disable-td add-transR');
                    $('td', row).eq(10).attr('class', 'text-center disable-td add-transD');
                    $('td', row).eq(13).attr('class', 'text-center disable-td add-bankinfo');
                    $('td', row).eq(13).attr('style', 'word-wrap:break-word');
                    $('td', row).eq(14).html('').append('<div class="action-buttons"><a class="red" onclick="javascript:deleteItem(this)"><i class="icon-trash"></i></a></div>');

                    $('td', row).eq(0).html('').append('<label>' + (pageInfo.page * pageInfo.length + index + 1)+ '</label><input type="hidden" name="MemberId[]" value="' + data['no'] + '">');
                    $('td', row).eq(1).html('<label>' + data['name'] + '</label><input type="hidden" name="Names[]" value="' + data['name'] + '">');
                    $('td', row).eq(2).html('<label>' + data['rank'] + '</label><input type="hidden" name="Rank[]" value="' + data['rank'] + '">');
                    $('td', row).eq(3).html('<label>' + ((data['WageCurrency'] == 0)?'¥':'$') + '</label><input type="hidden" name="Currency[]" value="' + data['WageCurrency'] + '">');
                    $('td', row).eq(4).html('<label>' + data['Salary'] + '</label><input type="hidden" name="Salary[]" value="' + data['Salary'] + '">');
                    $('td', row).eq(5).html(data['DateOnboard'] + '<input type="hidden" name="DateOnboard[]" value="' + data['DateOnboard'] + '">');
                    $('td', row).eq(6).html(data['DateOffboard'] + '<input type="hidden" name="DateOffboard[]" value="' + data['DateOffboard'] + '">');
                    $('td', row).eq(7).html('<label>' + data['SignDays'] + '</label><input type="hidden" name="SignDays[]" value="' + data['SignDays'] + '">');
                    $('td', row).eq(8).html('<input type="text" class="form-control add-minus" name="MinusCash[]" value="' + data['MinusCash'] + '" style="width: 100%;text-align: center" autocomplete="off">');
                    $('td', row).eq(9).html('<label>' + data['TransInR'] + '</label><input type="hidden" name="TransInR[]" value="' + data['TransInR'] + '">');
                    $('td', row).eq(10).html('<label>' + data['TransInD'] + '</label><input type="hidden" name="TransInD[]" value="' + data['TransInD'] + '">');
                    $('td', row).eq(11).html('<div class="input-group"><input class="form-control add-trans-date date-picker" name="TransDate[]" type="text" data-date-format="yyyy-mm-dd" value="' + data['TransDate'] + '"><span class="input-group-addon"><i class="icon-calendar "></i></span></div>');
                    $('td', row).eq(12).html('<input type="text" class="form-control" name="Remark[]" value="' + data['Remark'] + '" style="width: 100%;text-align: center" autocomplete="off">');
                    $('td', row).eq(13).html('<label>' + data['BankInformation'] + '</label><input type="hidden" name="BankInfo[]" value="' + data['BankInformation'] + '">');

                    //$('td', row).eq(11).attr('name', 'TransDate[]');
                },
                drawCallback: function (response) {
                    original = response.json.original;
                    if (!original) {
                        info = response.json.info;
                        $('#rate').val(info.rate);
                        $('#minus-days').val(info.minus_days);
                    }
                    setEvents();
                    calcReport();
                }
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }
        year = $("#select-year option:selected").val();
        month = $("#select-month option:selected").val();
        minus_days = $("#minus-days").val();
        rate = $("#rate").val();
        shipId = $("#select-ship").val();
        initTable();

        function setValue(e, v) {
            e.closest("td").firstElementChild.innerHTML = v;
            e.value = v;
        }
        function calcReport()
        {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            
            var calc_date;
            if (original)
                calc_date = today;
            else {
                calc_date = info.report_date.substr(0, 10);
                if (origForm != "" && origForm != $form.serialize()) {
                    calc_date = today;
                }
            }
            

            var TransInR = $('input[name="TransInR[]"]');
            var TransInD = $('input[name="TransInD[]"]');
            var TransDate = $('input[name="TransDate[]"]');

            var salary = $('input[name="Salary[]"]');
            var days = $('input[name="SignDays[]"]');
            var minus = $('input[name="MinusCash[]"]');
            var currency = $('input[name="Currency[]"]');
            var rate = $('#rate').val();
            var No = $('.add-no');
            
            var sum_R = 0;
            var sum_D = 0;
            var sum_pre = 0;
            var year = $("#select-year option:selected").val();
            var month = $("#select-month option:selected").val();
            var td = daysInMonth(month, year);
            for (var i=0;i<TransInR.length;i++) {
                setValue(No[i], i + 1);
                var dd = parseFloat(days[i].value);
                var m = parseFloat(minus[i].value);
                var s = parseFloat(salary[i].value);

                var _R = 0;
                var _D = 0;
                if (currency[i].value == 0) {
                    var r = s * dd / td - m;
                    _R = r.toFixed(2);
                    _D = (r / rate).toFixed(2);
                }
                else {
                    var d = s * dd / td - m;
                    _D = d.toFixed(2);
                    _R = (d * rate).toFixed(2);
                }
                setValue(TransInR[i], _R);
                setValue(TransInD[i], _D);

                sum_R += parseFloat(_R);
                sum_D += parseFloat(_D);
                if (TransDate[i].value != '') {
                    sum_pre += parseFloat(_R);
                }
            }
            var sum_Real = sum_R - sum_pre;
            if ($('#list-body tr:last').attr('class') == 'tr-report') {
                $('#list-body tr:last').remove();
            }
            $('#list-body').append('<tr class="tr-report" style="height:30px;border:2px solid black;"><td class="sub-small-header style-normal-header text-center">' + ($('.member-item').length) + '</td><td class="sub-small-header style-normal-header" colspan="3"></td><td colspan="2" class="sub-small-header style-normal-header text-center">计算日期</td><td class="disable-td text-center">' + calc_date + '<input type="hidden" name="report_date" value="' + calc_date + '"></td><td colspan="2" class="sub-small-header style-normal-header text-center">合计</td><td class="style-normal-header disable-td text-center">¥ ' + sum_R.toFixed(2) + '</td><td class="style-normal-header text-center disable-td">$ ' + sum_D.toFixed(2) + '</td><td class="sub-small-header style-normal-header text-center">实发工资</td><td class="style-normal-header text-center disable-td">' + sum_Real.toFixed(2) + '</td><td class="sub-small-header style-normal-header" colspan="2"></td></tr>');
            setDatePicker();
            if (origForm == "")
                origForm = $form.serialize();
        }

        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        function selectInfo()
        {
            shipName = $("#select-ship option:selected").text();
            year = $("#select-year option:selected").val();
            month = $("#select-month option:selected").val();
            minus_days = $("#minus-days").val();
            rate = $("#rate").val();
            if (shipName == "") return;
            $('#search_info').html('"' + shipName + '" ' + year + '年' + month + '月');

            if (listTable == null) {
                initTable();
            }
            else
            {
                listTable.column(3).search(year, false, false);
                listTable.column(4).search(month, false, false);
                listTable.column(5).search(minus_days, false, false);
                listTable.column(6).search(rate, false, false);
                listTable.column(2).search($("#select-ship").val(), false, false).draw();
            }
        }
        $('#select-ship').on('change', function() {
            shipId = $('#select-ship').val();
            origForm = "";
            selectInfo();
        });

        $('#select-year').on('change', function() {
            year = $("#select-year option:selected").val();
            origForm = "";
            selectInfo();
        });

        $('#select-month').on('change', function() {
            month = $("#select-month option:selected").val();
            origForm = "";
            selectInfo();
        });

        $('#minus-days').on('change', function() {
            minus_days = $("#minus-days").val();
            selectInfo();
        });

        $('#rate').on('change', function() {
            rate = $("#rate").val();
            calcReport();
        });

        $('.excel-btn').on('click', function() {
           $('td[style*="display: none;"]').remove();
           fnExcelReport();
		});

        $('body').on('keydown', 'input', function(e) {
            //if (e.target.id == "search-name") return;
            if (e.key === "Enter") {
                var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
                focusable = form.find('input').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                }
                return false;
            }
        });

        $("#rate").on('change', function() {
            calcReport();
        });

        function setEvents()
        {
            $('.add-minus').on('change', function() {
                calcReport();
            });

            $('.add-trans-date').on('change', function() {
                calcReport();
            });
        }

        function deleteItem(e)
        {
            bootbox.confirm("Are you sure you want to delete?", function (result) {
                if (result) {
                    $(e).closest("tr").remove();
                    calcReport();
                }
            });
        }

        function openAddPage()
        {
            $("#modal-add-wage").modal("show");
            $('#add-rank').val("");
            $('#add-currency').val(0);
            $('#add-wage').val('');
            $('#add-signon-date').val('');
            $('#add-signoff-date').val('');
            $('#add-minus-money').val('0');
            $('#add-purchase-date').val('');
            $('#add-remark').val('');
            $('#add-bank-info').val('');
        }

        function addWage()
        {
            var add_name = $('#add-name').val();
            if (add_name == '') { $('#add-name').focus(); return; }
            var add_rank = $('#add-rank').val();
            var add_currency = $('#add-currency').val();
            var add_wage = parseFloat($('#add-wage').val());
            if ($('#add-wage').val() == '') { $('#add-wage').focus(); return; }
            var add_signon_date = $('#add-signon-date').val();
            if (add_signon_date == '') { $('#add-signon-date').focus(); return; }
            var add_signoff_date = $('#add-signoff-date').val();
            if (add_signoff_date == '') { $('#add-signoff-date').focus(); return; }
            var add_minus_money = parseFloat($('#add-minus-money').val());
            var add_purchase_date = $('#add-purchase-date').val();
            var add_remark = $('#add-remark').val();
            var add_bank_info = $('#add-bank-info').val();
            $("#modal-add-wage").modal("hide");

            var rate = $("#rate").val();
            var add_money_R = 0;
            var add_money_D = 0;

            var diff = new Date(new Date(add_signoff_date) - new Date(add_signon_date));
            var signon_days = diff/1000/60/60/24+1;
            
            var year = $("#select-year option:selected").val();
            var month = $("#select-month option:selected").val();
            var minus_days = $("#minus-days").val();
            if (add_currency == 0) {
                add_money_R = add_wage * daysInMonth(month, year) / signon_days - add_minus_money;
                add_money_D = add_money_R * rate;
            } else {
                add_money_D = add_wage * daysInMonth(month, year) / signon_days - add_minus_money;
                add_money_R = add_money_D / rate;
            }
            var new_row = '<tr class="member-item disable-tr" role="row"><td class="text-center disable-td add-no new-member"><label>' + ($('.member-item').length+1) +
            '</label><input type="hidden" name="MemberId[]" value="new_' + ($('.new-member').length) + '">' +
            '</td><td class="text-center disable-td"><label>' + add_name + '</label><input type="hidden" name="Names[]" value="' + add_name + '">' + 
            '</td><td class="text-center disable-td"><label>' + add_rank + '</label><input type="hidden" name="Rank[]" value="' + add_rank + '">'+
            '</td><td class="text-center disable-td add-currency"><label>' + ((add_currency == 0)?'¥':'$') + '</label><input type="hidden" name="Currency[]" value="' + add_currency + '">' +
            '</td><td class="text-center disable-td add-salary"><label>' + add_wage.toFixed(2) + '</label><input type="hidden" name="Salary[]" value="' + add_wage.toFixed(2) + '">'+ 
            '</td><td class="text-center disable-td"><label>' + add_signon_date + '</label><input type="hidden" name="DateOnboard[]" value="' + add_signon_date + '">'+
            '</td><td class="text-center disable-td"><label>' + add_signoff_date + '</label><input type="hidden" name="DateOffboard[]" value="' + add_signoff_date + '">'+
            '</td><td class="text-center disable-td add-signondays"><label>' + signon_days + '</label><input type="hidden" name="SignDays[]" value="' + signon_days + '">' +
            '</td><td class="text-center add-minus"><input type="text" class="form-control" name="MinusCash[]" value="'+ add_minus_money +
            '" style="width: 100%;text-align: center" autocomplete="off"></td><td class="text-center disable-td add-transR"><label>' + add_money_R.toFixed(2) + '</label><input type="hidden" name="TransInR[]" value="' + add_money_R.toFixed(2) + '">' +
            '</td><td class="text-center disable-td add-transD"><label>' + add_money_D.toFixed(2) + '</label><input type="hidden" name="TransInD[]" value="' + add_money_D.toFixed(2) + '">' +
            '</td><td class=" text-center""><div class="input-group"><input class="form-control add-trans-date date-picker" name="TransDate[]" type="text" data-date-format="yyyy-mm-dd" value="' + add_purchase_date + '"><span class="input-group-addon"><i class="icon-calendar "></i></span></div></td><td class=" text-center"><input type="text" class="form-control" name="Remark[]" value="'+ add_remark + '" style="width: 100%;text-align: center" autocomplete="off"></td><td class="text-center disable-td add-bankinfo" style="word-wrap:break-word"><label>'+ add_bank_info + '</label><input type="hidden" name="BankInfo[]" value="' + add_bank_info + '">' +
            '</td><td class=" text-center"><div class="action-buttons"><a class="red" onclick="javascript:deleteItem(this)"><i class="icon-trash"></i></a></div></td></tr>';
            $('.member-item').last().after(new_row);
            setDatePicker();
            setEvents();
            calcReport();
            /*
            if ($('#list-body tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#port-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_En[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addPort(this)" class="form-control" name="Port_Cn[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deletePort(this)"><i class="icon-trash"></i></a></div></td></tr>');
                }
            }*/
        }

        function fnExcelReport()
        {
            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-shipmember-list');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='9' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>CREW LIST</td></tr>";
            tab_text=tab_text+"<tr><td colspan='4' style='font-size:18px;border-bottom:hidden;'>1.Name of Ship</td><td colspan='2'style='font-size:18px;border-bottom:hidden;text-align:center;'>2.Port of Arrival</td><td colspan='3' style='font-size:18px;border-bottom:hidden;text-align:center;'>3.Date of arrival</td></tr>";
            tab_text=tab_text+"<tr><td colspan='4' style='font-size:18px;'>&nbsp;&nbsp;" + shipName + "</td><td colspan='2'style='font-size:18px;text-align:center;'>&nbsp;&nbsp;ZHENJIANG</td><td colspan='3' style='font-size:18px;text-align:center;'>&nbsp;&nbsp;2020-12-</td></tr>";
            tab_text=tab_text+"<tr><td colspan='4' style='font-size:18px;border-bottom:hidden;'>4.Nationality of Ship</td><td colspan='2'style='font-size:18px;border-bottom:hidden;text-align:center;'>5.LAST Port of Call</td><td colspan='3' style='font-size:18px;border-bottom:hidden;'></td></tr>";
            tab_text=tab_text+"<tr><td colspan='4' style='font-size:18px;'>&nbsp;&nbsp;CHINA</td><td colspan='2'style='font-size:18px;text-align:center;'>&nbsp;&nbsp;DONGHAE</td><td colspan='3' style='font-size:18px;'></td></tr>";
            for(var j = 0 ; j < tab.rows.length ; j++) 
            {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        if (i == 0) {
                        }
                        else if (i == 1) {
                            tab.rows[j].childNodes[i].style.width = '140px';
                        }
                        else if (i == 2) {
                            tab.rows[j].childNodes[i].style.width = '60px';
                        }
                        else if (i == 4) {
                            tab.rows[j].childNodes[i].style.width = '160px';
                        }
                        else if (i == 5) {
                            tab.rows[j].childNodes[i].style.width = '200px';
                        }
                        else if (i == 6) {
                            tab.rows[j].childNodes[i].style.width = '200px';
                        }
                        else
                        {
                            tab.rows[j].childNodes[i].style.width = '100px';
                        }
                        tab.rows[j].childNodes[i].style.backgroundColor = '#c9dfff';
                    }
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                }
                else
                {
                    tab.rows[j].childNodes[4].innerHTML = '="' + tab.rows[j].childNodes[4].innerHTML + '"';
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                }
            }

            tab_text=tab_text+"</table>";
            //tab_text='<table border="2px" style="text-align:center;vertical-align:middle;"><tr><th class="text-center sorting_disabled" style="width: 78px;text-align:center;vertical-align:center;" rowspan="1" colspan="1"><span>No</span></th></tr><tr style="width: 78px;text-align:center;vertical-align:middle;"><td class="text-center sorting_disabled" rowspan="2" style="">你好</td></tr></table>';
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            //document.getElementById('test').innerHTML = tab_text;
            var filename = 'CREW LIST(' + shipName + ')';
            exportExcel(tab_text, filename, 'CREW LIST');
            return 0;
        }

        var submitted = false;
        $("#btnSave").on('click', function() {
            //origForm = $form.serialize();
            submitted = true;
            if ($('.member-item').length > 0) {
                $('#wage-form').submit();
            }
        });

        var $form = $('form');
        var origForm = "";
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
            var newForm = $form.serialize();
            newForm = newForm.replace("editable-image-input-hidden=&", "");
            if ((newForm !== origForm) && !submitted) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });

    </script>

@endsection
