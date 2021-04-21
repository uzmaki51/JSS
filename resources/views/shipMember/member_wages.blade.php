@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="main-content">
        <style>
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>工资管理</b></h4>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs ship-register" id="memberTab">
                    <li class="active">
                        <a data-toggle="tab" href="#calc_wage">
                            工资计算
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#transfer_wage">
                            工资汇款
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="calc_wage" class="tab-pane active">
                    <div class="space-4"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <label class="custom-label d-inline-block" style="padding: 6px;">船名:</label>
                                <select class="custom-select d-inline-block" id="select-ship" style="width:80px">
                                    <option value="" selected></option>
                                    @foreach($shipList as $ship)
                                        <option value="{{ $ship['IMO_No'] }}">{{$ship['shipName_En']}}</option>
                                    @endforeach
                                </select>
                                <label class="custom-label d-inline-block" style="padding: 6px;">减少天数:</label>
                                <input type="number" id="minus-days" value="0.5" step="0.5" min="0" autocomplete="off" style="width:60px;margin-right:0px;"/>
                                <label class="custom-label d-inline-block" style="padding: 6px;">汇率:</label>
                                <input type="number" id="rate" value="6.5" min="0" step="0.1" autocomplete="off" style="width:80px;margin-right:0px;"/>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="col-md-7">
                                <label class="custom-label d-inline-block" style="padding: 6px;">月份:</label>
                                <select id="select-year" style="font-size:13px">
                                    @for($i=2020;$i<2025;$i++)
                                    <option value="{{$i}}" @if(date("Y")==$i)selected @endif>{{$i}}年</option>
                                    @endfor
                                </select>
                                <select id="select-month" style="font-size:13px">
                                    @for($i=1;$i<13;$i++)
                                    <option value="{{$i}}" @if(date("m")==$i)selected @endif>{{$i}}月</option>
                                    @endfor
                                </select>
                                <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="search_info"></span>份工资单</strong>
                            </div>
                            <div class="col-md-5" style="padding:unset!important">
                                <div class="btn-group f-right">
                                    <a href="/shipMember/registerShipMember" class="btn btn-sm btn-primary btn-add" style="width: 80px">
                                        <i class="icon-plus"></i>{{ trans('common.label.add') }}
                                    </a>
                                    <button type="submit" id="btnRegister" class="btn btn-sm btn-info" style="width: 80px">
                                        <i class="icon-save"></i>{{ trans('common.label.save') }}
                                    </button>
                                    <button type="submit" id="btnRegister" class="btn btn-sm btn-success" style="width: 80px">
                                        <img src="http://192.168.3.214/assets/images/send_report.png" class="report-label-img">{{ trans('common.label.request') }}
                                    </button>
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
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>上船日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>下船/截止日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 4%;"><span>在船天数</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>扣款</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>家汇款<br>(¥)</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>家汇款<br>($)</span></th>
                                            <th class="text-center style-normal-header" style="width: 4%;"><span>支付日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 14%;"><span>备注</span></th>
                                            <th class="text-center style-normal-header" style="width: 24%;"><span>银行账户</span></th>
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
        $(function () {
            $.fn.editable.defaults.mode = 'inline';
            $.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
            $.fn.editableform.buttons = '';
        });
            
        function setDatePicker() {
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        var listTable = null;
        function initTable() {
            console.log("initTable is called");
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/wage/list',
                    type: 'POST',
                    data: {'year':year,'month':month},
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
                    $(row).attr('class', 'member-item');
                    $('td', row).eq(0).html('').append((pageInfo.page * pageInfo.length + index + 1));
                    //$('td', row).eq(0).html(index/2+1);
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }
        //initTable();

        function selectInfo()
        {
            shipName = $("#select-ship option:selected").text();
            year = $("#select-year option:selected").val();
            month = $("#select-month option:selected").val();
            if (shipName == "") return;

            if (listTable == null) initTable();
            $('#search_info').html('"' + shipName + '" ' + year + '年' + month + '月');
            listTable.column(2).search($("#select-ship").val(), false, false).draw();
        }
        $('#select-ship').on('change', function() {
            selectInfo();
        });

        $('#select-year').on('change', function() {
            selectInfo();
        });

        $('#select-month').on('change', function() {
            selectInfo();
        });

        $('.excel-btn').on('click', function() {
           $('td[style*="display: none;"]').remove();
           fnExcelReport();
		});

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
    </script>

@endsection
