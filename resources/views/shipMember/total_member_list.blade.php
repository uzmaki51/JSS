@extends('layout.sidebar')
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
                    <h4><b>Crew List</b></h4>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-6">
                    <label class="custom-label d-inline-block" style="padding: 6px;">船名:</label>
                    <select class="custom-select d-inline-block" id="select-ship" style="width:80px">
                        <option value="" selected></option>
                        @foreach($shipList as $ship)
                            <option value="{{ $ship['IMO_No'] }}">{{$ship['shipName_En']}}</option>
                        @endforeach
                    </select>
                    <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="ship_name"></span> CREW LIST</strong>
                </div>
                <div class="col-md-6" style="padding:unset!important">
                    <div class="btn-group f-right">
                        <button class="btn btn-warning btn-sm excel-btn"><i class="icon-table"></i>{{ trans('common.label.excel') }}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:4px;">
                <div id="item-manage-dialog" class="hide"></div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="row">
                    <div class="head-fix-div" id="crew-table" style="height:500px!important;">
                        <table id="table-shipmember-list" style="table-layout:fixed;">
                            <thead class="">
                                <th class="text-center style-header" style="width: 3%;"><span>No</span></th>
                                <th class="text-center style-header" style="width: 12%;"><span>Family Name, Given Name</span></th>
                                <th class="text-center style-header" style="width: 11%;"><span>Rank</span></th>
                                <th class="text-center style-header" style="width: 9%;"><span>Nationality</span></th>
                                <th class="text-center style-header" style="width: 14%;"><span>Chinese ID No.</span></th>
                                <th class="text-center style-header" style="width: 12%;"><span>Date and place of birth</span></th>
                                <th class="text-center style-header" style="width: 12%;"><span>Date and place of embarkation</span></th>
                                <th class="text-center style-header" style="width: 12%;"><span>Seaman's Book No and Expire Date</span></th>
                                <th class="text-center style-header" style="width: 12%;"><span>Passport's No and Expire Date</span></th>
                            </thead>
                            <tbody class="" id="list-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="test">
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
            listTable = $('#table-shipmember-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/shipMember/search',
                    type: 'POST',
                    data: {'type' : 'crew'},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: 'no', className: "text-center"},
                    {data: 'name', className: "text-center"},
                    {data: 'rank', className: "text-center"},
                    {data: 'nationality', className: "text-center"},
                    {data: 'cert-id', className: "text-center"},
                    {data: 'birth-and-place', className: "text-center"},
                    {data: 'date-and-embarkation', className: "text-center"},
                    {data: 'bookno-expire', className: "text-center"},
                    {data: 'passport-expire', className: "text-center"},
                ],
                rowsGroup: [0, 2, 3, 4],
                createdRow: function (row, data, index) {
                    var pageInfo = listTable.page.info();
                    
                    $(row).attr('class', 'member-item');
                    //$('td', row).eq(0).html('').append((pageInfo.page * pageInfo.length + index + 1));
                    $('td', row).eq(0).html(index/2+1);
                },
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }
        //initTable();

        $('#select-ship').on('change', function() {
            shipName = $("#select-ship option:selected").text();
            if (shipName == "") return;
            if (listTable == null) initTable();
            $('#ship_name').html('"' + shipName + '"');
            listTable.column(2).search($("#select-ship" ).val(), false, false).draw();
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
                            tab.rows[j].childNodes[i].style.width = '100px';
                        }
                        else if (i == 4) {
                            tab.rows[j].childNodes[i].style.width = '140px';
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
                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
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
