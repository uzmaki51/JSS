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
        <form id="wage-form" action="updateWageSendInfo" role="form" method="POST" enctype="multipart/form-data">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>工资汇款</b></h4>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="col-md-12" style="margin-top:4px;">
                <div id="calc_wage" class="tab-pane active">
                    <div class="space-4"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-7">
                                <label class="custom-label d-inline-block" style="padding: 6px;">船名:</label>
                                <select class="custom-select d-inline-block" name="select-ship" id="select-ship" style="width:80px">
                                    @foreach($shipList as $ship)
                                        <option value="{{ $ship['IMO_No'] }}" @if(isset($shipId) && ($shipId == $ship['IMO_No'])) selected @endif>{{$ship['shipName_En']}}</option>
                                    @endforeach
                                </select>
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
                                <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="search_info"></span>份工资汇款单</strong>
                            </div>
                            <div class="col-md-5" style="padding:unset!important">
                                <div class="btn-group f-right">
                                    <a id="btnSave" class="btn btn-sm btn-info" style="width: 80px">
                                        <i class="icon-save"></i>{{ trans('common.label.save') }}
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
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>职务</span></th>
                                            <th class="text-center style-normal-header" style="width: 10%;"><span>家汇款<br>(¥)</span></th>
                                            <th class="text-center style-normal-header" style="width: 10%;"><span>实发款<br>(¥)</span></th>
                                            <th class="text-center style-normal-header" style="width: 10%;"><span>实发款<br>($)</span></th>
                                            <th class="text-center style-normal-header" style="width: 9%;"><span>支付日期</span></th>
                                            <th class="text-center style-normal-header" style="width: 6%;"><span>出款银行</span></th>
                                            <th class="text-center style-normal-header" style="width: 28%;"><span>银行账户</span></th>
                                            <th class="text-center style-normal-header" style="width: 12%;"><span>备注</span></th>
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

    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    
    <?php
	echo '<script>';
	echo 'var CurrencyLabel = ' . json_encode(g_enum('CurrencyLabel')) . ';';
    echo 'var BankInfo = ' . json_encode(g_enum('BankData')) . ';';
	echo '</script>';
	?>
    <script>
        var token = '{!! csrf_token() !!}';
        var shipName = '';
        var year = '';
        var month = '';
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
                    url: BASE_URL + 'ajax/shipMember/wage/send',
                    type: 'POST',
                    data: { 'year':year, 'month':month, 'shipId':shipId},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: null, className: "text-center"},
                    {data: 'name', className: "text-center"},
                    {data: 'rank', className: "text-center"},
                    {data: 'cashR', className: "text-center"},
                    {data: 'sendR', className: "text-center"},
                    {data: 'sendD', className: "text-center"},
                    {data: 'purchdate', className: "text-center"},
                    {data: 'sendbank', className: "text-center"},
                    {data: 'remark', className: "text-center"},
                    {data: 'bankinfo', className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    $(row).attr('class', 'member-item disable-tr');
                    $(row).attr('data-index', data['no']);
                    $('td', row).eq(0).attr('class', 'text-center disable-td add-no');
                    $('td', row).eq(1).attr('class', 'text-center disable-td');
                    $('td', row).eq(2).attr('class', 'text-center disable-td');
                    $('td', row).eq(3).attr('class', 'text-center disable-td');
                    $('td', row).eq(8).attr('class', 'text-center disable-td');
                    $('td', row).eq(8).attr('style', 'word-wrap:break-word');

                    $('td', row).eq(0).html('').append('<label>' + (pageInfo.page * pageInfo.length + index + 1)+ '</label><input type="hidden" name="MemberId[]" value="' + data['no'] + '">');
                    $('td', row).eq(1).html('<label>' + data['name'] + '</label><input type="hidden" name="Names[]" value="' + data['name'] + '">');
                    $('td', row).eq(2).html('<label>' + data['rank'] + '</label><input type="hidden" name="Rank[]" value="' + data['rank'] + '">');
                    $('td', row).eq(3).html('<label>' + data['cashR'] + '</label><input type="hidden" name="CashR[]" value="' + data['cashR'] + '">');
                    $('td', row).eq(4).html('<input type="text" class="form-control add-sendR" name="SendR[]" value="' + data['sendR'] + '" style="width: 100%;text-align: center" autocomplete="off">');
                    $('td', row).eq(5).html('<input type="text" class="form-control add-sendD" name="SendD[]" value="' + data['sendD'] + '" style="width: 100%;text-align: center" autocomplete="off">');
                    $('td', row).eq(6).html('<div class="input-group"><input class="form-control add-trans-date date-picker" name="PurchDate[]" type="text" data-date-format="yyyy-mm-dd" value="' + (data['purchdate'] == null ? "": data['purchdate'].substring(0,10)) + '"><span class="input-group-addon"><i class="icon-calendar "></i></span></div>');
                    var bank_info = '<select class="form-control" name="SendBank[]">';
                    for (var i=0;i<BankInfo.length;i++)
                        bank_info += '<option value="'+i+'"' + ((i==data['sendbank'])?'selected':'') + '>'+BankInfo[i]+'</option>';
                    bank_info += '</select>';
                    $('td', row).eq(7).html(bank_info);
                    $('td', row).eq(8).html('<label>' + data['bankinfo'] + '</label><input type="hidden" name="BankInfo[]" value="' + data['bankinfo'] + '">');
                    $('td', row).eq(9).html('<input type="text" class="form-control" name="Remark[]" value="' + data['remark'] + '" style="width: 100%;text-align: center" autocomplete="off">');
                },
                drawCallback: function (response) {
                    original = response.json.original;
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
        shipId = $("#select-ship").val();
        initTable();

        function setValue(e, v) {
            e.closest("td").firstElementChild.innerHTML = v;
            e.value = v;
        }
        function calcReport()
        {
            var CashR = $('input[name="CashR[]"]');
            var SendR = $('input[name="SendR[]"]');
            var SendD = $('input[name="SendD[]"]');
            var No = $('.add-no');
            
            var sum_R = 0;
            var sum_D = 0;
            var sum_P = 0;
            for (var i=0;i<CashR.length;i++) {
                setValue(No[i], i + 1);
                var _R = CashR[i].value;
                var _D = SendR[i].value;
                var _P = SendD[i].value;

                setValue(CashR[i], _R);
                setValue(SendR[i], _D);
                setValue(SendD[i], _P);

                sum_R += parseFloat(_R);
                sum_D += parseFloat(_D);
                sum_P += (_P=='')?0:parseFloat(_P);
            }
            if ($('#list-body tr:last').attr('class') == 'tr-report') {
                $('#list-body tr:last').remove();
            }
            $('#list-body').append('<tr class="tr-report" style="height:30px;border:2px solid black;"><td class="sub-small-header style-normal-header text-center">' + ($('.member-item').length) + '</td><td class="sub-small-header style-normal-header" colspan="2"></td><td class="style-normal-header disable-td text-center">¥ ' + sum_R.toFixed(2) + '</td><td class="style-normal-header text-center disable-td">¥ ' + sum_D.toFixed(2) + '</td><td class="style-normal-header text-center disable-td">$ ' + sum_P.toFixed(2)+ '</td><td class="sub-small-header style-normal-header" colspan="4"></td></tr>');
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
            if (shipName == "") return;
            $('#search_info').html('"' + shipName + '" ' + year + '年' + month + '月');

            if (listTable == null) {
                initTable();
            }
            else
            {
                listTable.column(3).search(year, false, false);
                listTable.column(4).search(month, false, false);
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

        function setEvents()
        {
            $('.add-sendR').on('change', function() {
                calcReport();
            });

            $('.add-sendD').on('change', function() {
                calcReport();
            });
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
