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
            .list-body {
                background-color: #ffffff;
            }
            .list-body:hover {
                background-color: #e0edff;
                cursor: pointer;
            }
        </style>
        <div class="page-content">
        <form id="books-form" action="updateWageSendInfo" role="form" method="POST" enctype="multipart/form-data">
            <div class="space-4"></div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs ship-register" id="memberTab">
                            <li class="active">
                                <a data-toggle="tab" href="#wage_ship">
                                    记账簿
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#wage_member">
                                    流水账
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="wage_ship" class="tab-pane active">
                            <div class="page-header">
                                <div class="col-sm-3">
                                    <h4><b>记账簿管理</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <select name="select-year" id="select-year" style="font-size:13px">
                                            @for($i=$start_year;$i<=date("Y");$i++)
                                            <option value="{{$i}}" @if(($year==$i)||(($year=='')&&($i==date("Y")))) selected @endif>{{$i}}年</option>
                                            @endfor
                                        </select>
                                        <select name="select-month" id="select-month" style="font-size:13px">
                                            @if($year==date("Y"))
                                                @for($i=1;$i<=date("m");$i++)
                                                <option value="{{$i}}" @if(($month==$i)||(($month=='')&&($i==date("m")))) selected @endif>{{$i}}月</option>
                                                @endfor
                                            @else
                                                @for($i=1;$i<=12;$i++)
                                                <option value="{{$i}}" @if(($month==$i)||(($month=='')&&($i==date("m")))) selected @endif>{{$i}}月</option>
                                                @endfor
                                            @endif
                                        </select>
                                        <a class="btn btn-sm btn-danger refresh-btn-over" type="button" onclick="init()">
                                            <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">初期化
                                        </a>
                                        <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="search_info"></span>记账簿</strong>
                                    </div>
                                    <div class="col-md-5" style="padding:unset!important">
                                        <div class="btn-group f-right">
                                            <a id="btnSave" class="btn btn-sm btn-success" style="width: 80px">
                                                <i class="icon-save"></i>{{ trans('common.label.save') }}
                                            </a>
                                            <a onclick="javascript:fnExcelReport();" class="btn btn-warning btn-sm excel-btn">
                                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:4px;margin-left:18px;">
                                    <div id="item-manage-dialog" class="hide"></div>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="head-fix-div common-list" id="crew-table" style="">
                                        <table id="table-books-list" style="table-layout:fixed;">
                                            <thead class="">
                                                <th class="text-center style-normal-header" style="width: 10%;height:35px;"><span>{{ trans('decideManage.table.no') }}</span></th>
                                                <th class="text-center style-normal-header" style="width: 40%;"><span>{{ trans('decideManage.table.type') }}</span></th>
                                                <th class="text-center style-normal-header" style="width: 40%;"><span>{{ trans('decideManage.table.type') }}</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>详细</span></th>
                                            </thead>
                                            <tbody class="" id="list-ship-wage">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="wage_member" class="tab-pane">
                            <div class="page-header">
                                <div class="col-sm-3">
                                    <h4><b>流水账管理</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7" style="align-content: flex-end;display: flex;">
                                        <label class="custom-label d-inline-block" style="padding: 6px;"><b>姓名: </b></label><input type="text" class="typeahead" id="search-name" autocomplete="off"/>
                                        <select class="custom-select d-inline-block" name="select-member-year" id="select-member-year" style="font-size:13px;margin-left:2px;">
                                            @for($i=$start_year;$i<=date("Y");$i++)
                                            <option value="{{$i}}" @if((isset($year) && ($year == $i)) || (date("Y")==$i))selected @endif>{{$i}}年</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7" style="margin-top:4px;margin-left:18px;">
                                    <div class="" style="height:80px!important;" id="crew-table">
                                        <table id="" style="table-layout:fixed;">
                                            <thead class="">
                                                <th class="text-center style-normal-header" style="width: 10%;height:35px;"><span>姓名</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>船名</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>职务</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>币类</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>合约薪资</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>上班日期</span></th>
                                                <th class="text-center style-normal-header" style="width: 10%;"><span>下班日期</span></th>
                                            </thead>
                                            <tbody class="list-body">
                                                <tr class="member-item odd" role="row">
                                                    <td class="text-center style-search-header"">&nbsp;</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-8" style="margin-left:10px;">
                                    <div class="head-fix-div common-list" id="crew-table" style="">
                                        <table id="table-memberwage-list" style="table-layout:fixed;">
                                            <thead class="">
                                                <th class="text-center style-normal-header" style="width: 5%;height:35px;"><span>月份</span></th>
                                                <th class="text-center style-normal-header" style="width: 15%;"><span>支付日期</span></th>
                                                <th class="text-center style-normal-header" style="width: 20%;"><span>家汇款(¥)</span></th>
                                                <th class="text-center style-normal-header" style="width: 20%;"><span>家汇款($)</span></th>
                                                <th class="text-center style-normal-header" style="width: 38%;"><span>银行账号</span></th>
                                                <th class="text-center style-normal-header" style="width: 7%;"><span>详细</span></th>
                                            </thead>
                                            <tbody class="" id="list-ship-wage">
                                            </tbody>
                                        </table>
                                    </div>
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
    
    <script>
        var token = '{!! csrf_token() !!}';
        var year = '';
        var month = '';

        var listTable = null;
        function initTable() {
            listTable = $('#table-books-list').DataTable({
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
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }

        year = $("#select-year option:selected").val();
        month = $("#select-month option:selected").val();
        $('#search_info').html(year + '年' + month + '月份');
        initTable();

        function selectInfo()
        {
            year = $("#select-year option:selected").val();
            month = $("#select-month option:selected").val();
            $('#search_info').html(year + '年' + month + '月份');

            if (listTable == null) {
                initTable();
            }
            else
            {
                listTable.column(3).search(year, false, false);
                listTable.column(4).search(month, false, false);
            }
        }

        function changeYear() {
            year = $("#select-year option:selected").val();
            var months = "";
            if (year == now_year) {
                for(var i=1;i<=now_month;i++)
                {
                    months += '<option value="' + i + '" ' + ((now_month==i)?'selected>':'>') +  i + '月</option>';
                }
            }
            else if (year == start_year) {
                for(var i=start_month;i<=12;i++)
                {
                    months += '<option value="' + i + '" ' + ((start_year==now_year && now_month==i)?'selected>':'>') +  i + '月</option>';
                }
            }
            else
            {
                for(var i=1;i<=12;i++) {
                    months += '<option value="' + i + '" >' + i + '月</option>';
                }
            }
            $('#select-month').html(months);
            origForm = "";
            selectInfo();
        }

        $('#select-year').on('change', function() {
            var prevYear = $('#select-year').val();
            $('#select-year').val(year);
            var newForm = $form.serialize();
            if ((newForm !== origForm) && !submitted) {
                var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
                bootbox.confirm(confirmationMessage, function (result) {
                    if (!result) {
                        return;
                    }
                    else {
                        $('#select-year').val(prevYear);
                        changeYear();
                    }
                });
            }
            else {
                $('#select-year').val(prevYear);
                changeYear();
            }
        });

        function changeMonth() {
            month = $("#select-month option:selected").val();
            origForm = "";
            selectInfo();
        }

        $('#select-month').on('change', function() {
            var prevMonth = $('#select-month').val();
            $('#select-month').val(month);
            var newForm = $form.serialize();
            if ((newForm !== origForm) && !submitted) {
                var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
                bootbox.confirm(confirmationMessage, function (result) {
                    if (!result) {
                        return;
                    }
                    else {
                        $('#select-month').val(prevMonth);
                        changeMonth();
                    }
                });
            }
            else {
                $('#select-month').val(prevMonth);
                changeMonth();
            }
        });

        var submitted = false;
        $("#btnSave").on('click', function() {
            //origForm = $form.serialize();
            submitted = true;
            if ($('.member-item').length > 0) {
                $('#books-form').submit();
                $('td[style*="display: none;"]').remove();
            }
        });

        var $form = $('form');
        var origForm = "";
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
            var newForm = $form.serialize();
            if ((newForm !== origForm) && !submitted) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });

        function fnExcelReport()
        {
            /*
            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-shipmember-list');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='14' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#search_info').html() + "份工资单</td></tr>";
            for(var j = 0 ; j < tab.rows.length ; j++) 
            {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.width = '100px';
                        tab.rows[j].childNodes[i].style.backgroundColor = '#c9dfff';
                    }
                    tab.rows[j].childNodes[1].style.width = '140px';
                    tab.rows[j].childNodes[2].style.width = '60px';
                    tab.rows[j].childNodes[3].style.width = '40px';
                    tab.rows[j].childNodes[6].style.width = '80px';
                    tab.rows[j].childNodes[13].style.width = '300px';
                    tab.rows[j].childNodes[14].remove();
                }
                else if(j == (tab.rows.length -1))
                {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.height = "30px";
                        tab.rows[j].childNodes[i].style.fontWeight = "bold";
                        tab.rows[j].childNodes[i].style.backgroundColor = '#ebf1de';
                    }
                    tab.rows[j].childNodes[9].colSpan="1";
                }
                else
                {
                    var info = real_tab.rows[j].childNodes[4].childNodes[0].value;
                    tab.rows[j].childNodes[4].innerHTML = info;
                    info = real_tab.rows[j].childNodes[8].childNodes[0].value;
                    tab.rows[j].childNodes[8].innerHTML = info;
                    info = real_tab.rows[j].childNodes[11].childNodes[0].childNodes[0].value;
                    tab.rows[j].childNodes[11].innerHTML = info;
                    info = real_tab.rows[j].childNodes[12].childNodes[0].value;
                    tab.rows[j].childNodes[12].innerHTML = info;
                }
                
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");
            tab_text= tab_text.replace(/<img[^>]*>/gi,"");
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

            var filename = $("#select-ship option:selected").html() + '_' + year + '_' + month + '_工资单';
            exportExcel(tab_text, filename, year + '_' + month + '_工资单');
            */
            return 0;
        }
        
    </script>

@endsection
