@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ cAsset('assets/js/chartjs/flot.css') }}">
    
    
    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/flot.js') }}"></script>
@endsection

@section('content')
    <div class="main-content">
        <style>
            .cost-item-odd {
                background-color: #f5f5f5;
            }

            .cost-item-even:hover {
                background-color: #ffe3e082;
            }

            .cost-item-odd:hover {
                background-color: #ffe3e082;
            }
        </style>
        <div class="page-content">
            <div class="space-4"></div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <ul class="nav nav-tabs ship-register" id="importTab">
                            <li class="active">
                                <a data-toggle="tab" href="#tab_graph">
                                    GRAPH
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab_table">
                                    TABLE
                                </a>
                            </li>
                            <li id="li_soa">
                                <a data-toggle="tab" href="#tab_soa">
                                    SOA
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="tab_graph" class="tab-pane active">
                            <div class="page-header">
                                <div class="col-sm-3">
                                    <h4><b>GRAPH</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">??????:</label>
                                        <select class="custom-select d-inline-block" id="select-graph-ship" style="width:80px">
                                            <?php $index = 0 ?>
                                            @foreach($shipList as $ship)
                                                <?php $index ++ ?>
                                                <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{$ship['NickName']}}</option>
                                            @endforeach
                                        </select>
                                        <select name="select-graph-year" id="select-graph-year" style="font-size:13px">
                                            @for($i=date("Y");$i>=$start_year;$i--)
                                            <option value="{{$i}}" @if($i==date("Y")) selected @endif>{{$i}}???</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-5" style="padding:unset!important">
                                        <div class="btn-group f-right">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:4px;">
                                    <div class="row" style="text-align:center;">
                                        <strong style="font-size: 16px; padding-top: 6px;">1. ????????????</strong>
                                        <div class="space-8"></div>
                                        <div class="card">
                                            <div class="space-8"></div>
                                            <strong><span id="graph-first-title"style="font-size: 14px; padding-top: 6px;"></span></strong>
                                            <div class="space-8"></div>
                                            <div class="card" id="graph_first">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="space-10"></div>
                                    <div class="row" style="text-align:center;">
                                        <strong style="font-size: 16px; padding-top: 6px;">2. ????????????</strong>
                                        <div class="space-8"></div>
                                        <div class="card">
                                            <div class="space-8"></div>
                                            <strong><span id="graph-second-title"style="font-size: 14px; padding-top: 6px;"></span></strong>
                                            <div class="space-8"></div>
                                            <div class="card" id="graph_second">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="space-10"></div>
                                    <div class="row" style="text-align:center;">
                                        <strong style="font-size: 16px; padding-top: 6px;">3. ????????????</strong>
                                        <div class="space-8"></div>
                                        <div class="card">
                                            <div class="space-8"></div>
                                            <strong><span id="graph-third-title"style="font-size: 14px; padding-top: 6px;"></span></strong>
                                            <div class="space-8"></div>
                                            <div class="" id="graph_third" style="margin: 0px auto;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="space-10"></div>
                                </div>    
                            </div>
                        </div>
                        <div id="tab_table" class="tab-pane">
                            <div class="page-header">
                                <div class="col-sm-3">
                                    <h4><b>???????????????</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">??????:</label>
                                        <select class="custom-select d-inline-block" id="select-table-ship" style="width:80px">
                                            <!--option value="" selected></option-->
                                            <?php $index = 0 ?>
                                            @foreach($shipList as $ship)
                                                <?php $index ++ ?>
                                                <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{$ship['NickName']}}</option>
                                            @endforeach
                                        </select>
                                        <select name="select-table-year" id="select-table-year" style="font-size:13px">
                                            @for($i=date("Y");$i>=$start_year;$i--)
                                            <option value="{{$i}}" @if($i==date("Y")) selected @endif>{{$i}}???</option>
                                            @endfor
                                        </select>
                                        <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="table_info"></span>???????????????</strong>
                                    </div>
                                    <div class="col-md-5" style="padding:unset!important">
                                        <div class="btn-group f-right">
                                            <a onclick="javascript:fnExcelTableReport();" class="btn btn-warning btn-sm excel-btn">
                                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:4px;">
                                    <div id="item-manage-dialog" class="hide"></div>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="row">
                                        <div class="table-head-fix-div" id="div-income-expense" style="height: 700px">
                                            <table id="table-income-expense-list" style="max-width:unset!important;table-layout:fixed;width:1500px!important;">
                                                <thead class="">
                                                <tr>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 2.5%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 2.5%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 4.5%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 3%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 5.5%;"><span>??????($)</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 5.5%;"><span>??????($)</span></th>
                                                    <th class="text-center style-normal-header" rowspan="2" style="width: 5.5%;"><span>??????($)</span></th>
                                                    <th class="text-center style-normal-header right-border" rowspan="2" style="width: 7%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" colspan="13"><span>???????????? ($)</span></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center style-red-header" style="width: 4%;"><span>??????</span></th>
                                                    <th class="text-center style-red-header" style="width: 4%;"><span>??????</span></th>
                                                    <th class="text-center style-red-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-red-header" style="width: 4%;"><span>CTM</span></th>
                                                    <th class="text-center style-red-header right-border" style="width: 4%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>?????????</span></th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="table-income-expense-body">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="keep_list" name="keep_list"></input>
                        </div>
                        <div id="tab_soa" class="tab-pane">
                            <div class="page-header">
                                <div class="col-sm-3">
                                    <h4><b>SOA</b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">??????:</label>
                                        <select class="custom-select d-inline-block" id="select-soa-ship" style="width:80px">
                                            <?php $index = 0 ?>
                                            @foreach($shipList as $ship)
                                                <?php $index ++ ?>
                                                <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{$ship['NickName']}}</option>
                                            @endforeach
                                        </select>
                                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">??????:</label>
                                        <select name="select-soa-contract" id="select-soa-contract" style="font-size:13px;width:80px;">
                                        </select>
                                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">??????:</label>
                                        <select name="select-soa-currency" id="select-soa-currency" style="font-size:13px">
                                            <option value="CNY" selected>??</option>
                                            <option value="USD" selected>$</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5" style="padding:unset!important">
                                        <div class="btn-group f-right">
                                            <a onclick="javascript:gotoDetailPage();" class="btn btn-success btn-sm excel-btn">
                                                <i class="icon-forward"></i>????????????
                                            </a>
                                            <a onclick="javascript:fnExcelSOAReport();" class="btn btn-warning btn-sm excel-btn">
                                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:4px;">
                                    <div class="space-10"></div>
                                    <div class="row" style="text-align:center;">
                                        <strong style="font-size: 16px; padding-top: 6px;"><span id="soa_title"></span>????????????</strong>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="row">
                                        <table id="table-soa-general-info" style="table-layout:fixed;">
                                            <thead class="">
                                                <tr>
                                                    <th class="text-center style-normal-header" style="width: 8%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 9%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 15%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 7%;"><span>??????(??????)</span></th>
                                                    <th class="text-center style-normal-header" style="width: 19%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 19%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 19%;"><span>?????????(?????????)</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>????????????</span></th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="table-soa-general-body">
                                                    <tr>
                                                        <td class="text-center" id="contract_type" style="height:20px;"></td>
                                                        <td class="text-center" id="contract_date"></td>
                                                        <td class="text-center" id="contract_content"></td>
                                                        <td class="text-center" id="contract_amount"></td>
                                                        <td class="text-center" id="contract_signon_port"></td>
                                                        <td class="text-center" id="contract_signoff_port"></td>
                                                        <td class="text-center" id="contract_unit"></td>
                                                        <td class="text-center" id="contract_attachment"></td>
                                                    </tr>
                                                </tbody>
                                        </table>
                                    </div>
                                    <div class="space-10"></div>
                                    <div class="row" style="text-align:center;">
                                        <strong class="text-center" style="font-size: 16px; padding-top: 6px;"><span id="soa_info"></span>SOA</strong>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="row">
                                        <div class="head-fix-div" id="div-income-expense" style="height: 600px;">
                                            <table id="table-soa-list" style="table-layout:fixed;">
                                                <thead class="">
                                                <tr>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>No</span></th>
                                                    <th class="text-center style-normal-header" style="width: 9%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 34%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 5%;"><span>????????????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 18%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 18%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 8%;"><span>??????</span></th>
                                                    <th class="text-center style-normal-header" style="width: 4%;"><span>????????????</span></th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="table-soa-list-body">
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
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    
    <?php
	echo '<script>';
    echo 'var start_year = ' . $start_year . ';';
    echo 'var now_year = ' . date("Y") . ';';
    echo 'var FeeTypeData = ' . json_encode(g_enum('FeeTypeData')) . ';';
	echo '</script>';
	?>

    <script>
        var token = '{!! csrf_token() !!}';

        var year_graph;
        var year_table;
        var year_soa;

        var shipid_graph;
        var shipid_table;
        var shipid_soa;

        var listTable = null;
        var table_sums = [];
        var dest_obj;

        year_graph = $("#select-graph-year option:selected").val();
        shipid_graph = $("#select-graph-ship").val();
        var graph_title = $("#select-graph-ship option:selected").attr('data-name') + ' ' + year_graph + '??? ';
        $('#graph-first-title').html(graph_title + '??????&????????????')
        $('#graph-second-title').html(graph_title + '??????&??????')
        $('#graph-third-title').html(graph_title + '??????')
        initGraph();
        
        function drawFirstGraph(labels,datasets) {
            $('#graph_first').html('');
            $('#graph_first').append('<canvas id="first-chart" height="250" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("first-chart"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                    }
                }
            });
        }

        var color_table = ['#73b7ff','#ff655c','#50bc16','#ffc800','#9d00ff','#ff0000','#795548','#3f51b5','#00bcd4','#e91e63','#0000ff','#00ff00','#0d273a'];
        function drawFirstGraph(labels,datasets) {
            $('#graph_first').html('');
            $('#graph_first').append('<canvas id="first-chart" height="250" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("first-chart"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }

        function drawSecondGraph(labels,datasets) {
            $('#graph_second').html('');
            $('#graph_second').append('<canvas id="second-chart" height="250" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("second-chart"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                    }
                }
            });
        }
        
        function drawThirdGraph(datasets) {
            $('#graph_third').html('');
            $('#graph_third').append('<canvas id="third-chart" style="width:300px;height:300px;" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("third-chart"), {
                type: 'doughnut',
                data: {
                    labels: ['??????','??????','?????????','CTM','??????','??????','?????????','?????????','?????????','?????????','?????????','?????????','?????????'],
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',

                        },
                    }
                }
            });
            $('#third-chart').attr('style','width:600px;height:600px;')
        }
        function initGraph() {
            var datasets1 = [];
            var labels1 = [];

            var datasets2 = [];
            var labels2 = [];

            datasets1[0] = {};
            datasets1[1] = {}
            datasets1[0].data = [];
            datasets1[0].label = '????????????';
            //73b7ff','#ff655c','#50bc16','#ffc800','#9d00ff','#ff0000','#795548','#3f51b5','#00bcd4','#e91e63','#0000ff','#00ff00','#0d273a'];
            datasets1[0].borderColor = '#3f51b5';
            datasets1[0].backgroundColor = 'cyan';
            datasets1[0].fill = true;
            datasets1[0].type = 'line';
            datasets1[0].order = 1;
            datasets1[1].data = [];
            datasets1[1].label = '??????($)';
            datasets1[1].borderColor = 'black';
            datasets1[1].backgroundColor = '#735ff1';
            datasets1[1].order = 0;


            datasets2[0] = {};
            datasets2[1] = {}
            datasets2[0].data = [];
            datasets2[0].label = '??????($)';
            datasets2[0].borderColor = 'black';
            datasets2[0].backgroundColor = '#305bfc';
            datasets2[1].data = [];
            datasets2[1].label = '??????($)';
            datasets2[1].borderColor = 'black';
            datasets2[1].backgroundColor = '#f23e00';

            var datasets3 = [];
            datasets3[0] = {};
            datasets3[0].data = [];
            for (var i=0;i<13;i++) datasets3[0].data[i] = 0;
            datasets3[0].backgroundColor = ['#73b7ff','#ff655c','#50bc16','#ffc800','#9d00ff','#ff0000','#795548','#3f51b5','#00bcd4','#e91e63','#0000ff','#00ff00','#0d273a'];

            var debit_total_sum = 0;
            $.ajax({
                url: BASE_URL + 'ajax/operation/listByShip',
                type: 'post',
                data: {'year':year_graph, 'shipId':shipid_graph},
                success: function(data, status, xhr) {
                    for(var index=0;index<data.length;index++) {
                        labels1[index] = data[index]['Voy_No'];
                        labels2[index] = data[index]['Voy_No'];
                        datasets1[0].data[index] = data[index]['total_profit'];
                        datasets1[1].data[index] = data[index]['profit_sum'];
                        datasets2[0].data[index] = data[index]['credit_sum'];
                        datasets2[1].data[index] = data[index]['debit_sum'];
                        debit_total_sum += data[index]['debit_sum'];
                        for (var i=0;i<13;i++)
                        {
                            if (i == 0) offset = 2;
                            else if (i == 1) offset = 1;
                            else if (i == 2) offset = 6;
                            else if (i == 3) offset = 4;
                            else if (i == 4) offset = 15;
                            else if (i == 5) offset = 3;
                            else if (i == 6) offset = 5;
                            else if (i == 7) offset = 7;
                            else if (i == 8) offset = 8;
                            else if (i == 9) offset = 9;
                            else if (i == 10) offset = 10;
                            else if (i == 11) offset = 11;
                            else if (i == 12) offset = 12;
                            if (data[index]['debit_list'][offset] != undefined) {
                                datasets3[0].data[i] += data[index]['debit_list'][offset];
                            }
                        }
                    }
                    for (var i=0;i<13;i++) {
                        if (debit_total_sum != 0) {
                            datasets3[0].data[i] = prettyValue(datasets3[0].data[i] / debit_total_sum * 100)
                        }
                    }
                    drawFirstGraph(labels1, datasets1);
                    drawSecondGraph(labels2,datasets2);
                    drawThirdGraph(datasets3);
                }
            });
        }

        $('#select-graph-year').on('change', function() {
            selectGraphInfo();
        });

        $('#select-graph-ship').on('change', function() {
            selectGraphInfo();
        });

        function selectGraphInfo()
        {
            year_graph = $("#select-graph-year option:selected").val();
            shipid_graph = $("#select-graph-ship").val();
            var graph_title = $("#select-graph-ship option:selected").attr('data-name') + ' ' + year_graph + '??? ';
            $('#graph-first-title').html(graph_title + '??????&????????????');
            $('#graph-second-title').html(graph_title + '??????&??????');
            $('#graph-third-title').html(graph_title + '??????');

            initGraph();
        }

        function initTable() {
            listTable = $('#table-income-expense-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                bAutoWidth: false, 
                ajax: {
                    url: BASE_URL + 'ajax/operation/listByShip',
                    type: 'POST',
                    data: {'year':year_table, 'shipId':shipid_table},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: 'Voy_No', className: "text-center"},
                    {data: 'CP_kind', className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    if ((index%2) == 0)
                        $(row).attr('class', 'cost-item-even');
                    else
                        $(row).attr('class', 'cost-item-odd');

                    $('td', row).eq(0).attr('class', 'text-center td_voy_no');
                    $('td', row).eq(0).attr('style', 'cursor:pointer;background:linear-gradient(#fff, #d9f8fb);');
                    if (data['max_date'] == false) {
                        $('td', row).eq(2).html('-');
                    } else {
                        $('td', row).eq(2).html(data['max_date'].Voy_Date);
                        if (data['min_date'] != false) {
                            var start_date = data['min_date'].Voy_Date + ' ' + data['min_date'].Voy_Hour + ':' + data['min_date'].Voy_Minute;
                            var end_date = data['max_date'].Voy_Date + ' ' + data['max_date'].Voy_Hour + ':' + data['max_date'].Voy_Minute;

                            var sail_time = __getTermDay(start_date, end_date, data['min_date'].GMT, data['max_date'].GMT);
                            $('td', row).eq(3).html(sail_time.toFixed(2));
                        }
                    }
                    if (data['max_date'] == false) {
                        $('td', row).eq(3).html('-');
                    }

                    $('td', row).eq(4).attr('class', 'style-blue-input text-right');
                    $('td', row).eq(4).attr('style', 'padding-right:5px!important;');
                    $('td', row).eq(4).html(data['credit_sum']==0?'':prettyValue(data['credit_sum']));

                    $('td', row).eq(5).attr('class', 'text-right');
                    $('td', row).eq(5).attr('style', 'padding-right:5px!important;')
                    $('td', row).eq(5).html(data['debit_sum']==0?'':prettyValue(data['debit_sum']));

                    if (data['profit_sum'] > 0) {
                        $('td', row).eq(6).attr('class', 'style-blue-input text-right');
                    } else {
                        $('td', row).eq(6).attr('class', 'style-red-input text-right');
                    }
                    $('td', row).eq(6).attr('style', 'padding-right:5px!important;')
                    $('td', row).eq(6).html(data['profit_sum']==0?'':prettyValue(data['profit_sum']));

                    if (data['total_profit'] > 0) {
                        $('td', row).eq(7).attr('class', 'style-blue-input text-right right-border');
                    } else {
                        $('td', row).eq(7).attr('class', 'style-red-input text-right right-border');
                    }
                    $('td', row).eq(7).attr('style', 'padding-right:5px!important;')
                    //$('td', row).eq(7).html(data['total_profit']==0?'':prettyValue(data['total_profit']));
                    $('td', row).eq(7).html(data['profit_sum']==0?'':(data['total_profit']==0?'':prettyValue(data['total_profit'])))

                    for (var i=1;i<16;i++)
                    {
                        if (i == 2) {   // ??????
                            dest_obj = $('td', row).eq(8);
                        }
                        else if (i == 1) { // ??????
                            dest_obj = $('td', row).eq(9);
                        }
                        else if (i == 6) { // ?????????
                            dest_obj = $('td', row).eq(10);
                        }
                        else if (i == 4) { //CTM
                            dest_obj = $('td', row).eq(11);
                        }
                        else if (i == 15) { //??????
                            dest_obj = $('td', row).eq(12);
                        }
                        else if (i == 3) { //??????
                            dest_obj = $('td', row).eq(13);
                        }
                        else if (i == 5) { //?????????
                            dest_obj = $('td', row).eq(14);
                        }
                        else if (i == 7) { // ?????????
                            dest_obj = $('td', row).eq(15);
                        }
                        else if (i == 8) { // ?????????
                            dest_obj = $('td', row).eq(16);
                        }
                        else if (i == 9) { // ?????????
                            dest_obj = $('td', row).eq(17);
                        }
                        else if (i == 10) { // ?????????
                            dest_obj = $('td', row).eq(18);
                        }
                        else if (i == 11) { // ?????????
                            dest_obj = $('td', row).eq(19);
                        }
                        else if (i == 12) { // ?????????
                            dest_obj = $('td', row).eq(20);
                        }
                        else {
                            dest_obj = null;
                        }

                        if (i == 15) {
                            $(dest_obj).attr('class', 'text-right right-border');
                        }

                        if (data['debit_list'][i] != undefined)
                        {
                            if (i == 15) {
                                $(dest_obj).attr('class', 'text-right right-border');
                            } else {
                                $(dest_obj).attr('class', 'text-right');
                            }
                            
                            $(dest_obj).attr('style', 'padding-right:5px!important;')
                            /*
                            if ((i==1) || (i==2) || (i==4)|| (i==6) || (i==15)) {
                                $(dest_obj).attr('style', 'padding-right:5px!important;color:#9c9c9c!important')
                            } else {
                                $(dest_obj).attr('style', 'padding-right:5px!important;')
                            }
                            */

                            $(dest_obj).html(prettyValue(data['debit_list'][i]));
                        }
                        else {
                            if (dest_obj != null) $(dest_obj).html('');
                        }
                    }
                },
                drawCallback: function (response) {
                    setEvents();
                    if (response.json.data.length <= 0) return;
                    var tab = document.getElementById('table-income-expense-body');
                    var i,j;
                    for (i=0;i<18;i++) table_sums[i] = 0;
                    for(var j=0; j<tab.rows.length; j++)
                    {
                        for (var i=0;i<18;i++)
                        {
                            var value_str = tab.rows[j].childNodes[3+i].innerHTML;
                            if ((value_str != "") && (value_str != "-"))
                            {
                                if (i == 4) {
                                    table_sums[i] = parseFloat(value_str.replace(",",""));
                                }
                                else {
                                    table_sums[i] += parseFloat(value_str.replace(",",""));
                                }
                            }
                        }
                    }
                    
                    var report_html = "";
                    report_html = "<tr style='height:30px;'><td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer style-normal-header sub-small-header text-center disable-td'>" + response.json.data.length + "</td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer sub-small-header disable-td'></td><td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer sub-small-header disable-td'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer style-normal-header sub-small-header text-center'>" + (table_sums[0]==0?'':prettyValue(table_sums[0])) + "</td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right style-blue-input'>" + (table_sums[1]==0?'':prettyValue(table_sums[1])) + "</td>";
                    for(i=2;i<18;i++)
                    {
                        if (i==3 || i==4)
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right " + (table_sums[i]>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (table_sums[i]==0?'':prettyValue(table_sums[i])) + "</td>";
                        else if (i>=5&&i<=9)
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header style-red-header text-right " + (table_sums[i]>=0?'':'style-red-input') + "' style='padding:5px!important;'>" + (table_sums[i]==0?'':prettyValue(table_sums[i])) + "</td>";
                        else
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right " + (table_sums[i]>=0?'':'style-red-input') + "' style='padding:5px!important;'>" + (table_sums[i]==0?'':prettyValue(table_sums[i])) + "</td>";
                    }
                    report_html += "</tr>";
                    $('#table-income-expense-body').append(report_html);
                }
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }

        function changeTableShip() {
            shipid_table = $('#select-table-ship').val();
            selectTableInfo();
        }

        $('#select-table-year').on('change', function() {
            selectTableInfo();
        });

        $('#select-table-ship').on('change', function() {
            selectTableInfo();
        });

        function selectTableInfo()
        {
            year_table = $("#select-table-year option:selected").val();
            shipid_table = $("#select-table-ship").val();
            $('#table_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '" ' + year_table + '??? ');

            if (listTable == null) {
                initTable();
            }
            else
            {
                listTable.column(1).search(year_table, false, false);
                listTable.column(2).search(shipid_table, false, false).draw();
            }
        }

        function prettyValue(value)
        {
            return parseFloat(value).toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,");
        }

        const DAY_UNIT = 1000 * 3600;
        const COMMON_DECIMAL = 2;

        function __getTermDay(start_date, end_date, start_gmt = 8, end_gmt = 8) {
            let currentDate = moment(end_date).valueOf();
            let currentGMT = DAY_UNIT * end_gmt;
            let prevDate = moment(start_date).valueOf();
            let prevGMT = DAY_UNIT * start_gmt;
            let diffDay = 0;
            currentDate = BigNumber(currentDate).minus(currentGMT).div(DAY_UNIT);
            prevDate = BigNumber(prevDate).minus(prevGMT).div(DAY_UNIT);
            diffDay = currentDate.minus(prevDate);
            return parseFloat(diffDay.div(24));
        }


        // SOA
        var voyNo_soa;
        var voyType_soa;
        var currency_soa;
        var voyID_soa;
        var listSOATable = null;
        var SOA_credit_sum = 0;
        var SOA_debit_sum = 0;

        function initSOATable() {
             listSOATable = $('#table-soa-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: BASE_URL + 'ajax/operation/listBySOA',
                    type: 'POST',
                    data: {'shipId':shipid_soa, 'voy_no':voyNo_soa, 'currency':currency_soa},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: null, className: "text-center"},
                    {data: 'date', className: "text-center"},
                    {data: 'content', className: ""},
                    {data: null, className: "text-center"},
                    {data: 'credit', className: "text-center"},
                    {data: 'debit', className: "text-center"},
                    {data: 'rate', className: "text-center"},
                    {data: 'attachment', className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    $('td', row).eq(0).attr('style', 'height:25px;')
                    if ((index%2) == 0)
                        $(row).attr('class', 'cost-item-even');
                    else
                        $(row).attr('class', 'cost-item-odd');

                    $('td', row).eq(2).attr('style', 'padding-left:2px!important;');
                    $('td', row).eq(4).attr('class', 'style-blue-input text-right');
                    $('td', row).eq(4).attr('style', 'padding-right:5px!important;');
                    $('td', row).eq(5).attr('class', 'text-right');
                    $('td', row).eq(5).attr('style', 'padding-right:5px!important;');
                        
                    $('td', row).eq(0).html('').append(index + 1);
                    $('td', row).eq(3).html('').append(FeeTypeData[data['flowid']][data['profit_type']]);
                    $('td', row).eq(4).html('').append(data['credit']=='' ? '':prettyValue(data['credit']));
                    $('td', row).eq(5).html('').append(data['debit']=='' ? '':prettyValue(data['debit']));

                    if (data['credit'] != '') SOA_credit_sum += parseFloat(data['credit']);
                    if (data['debit'] != '') SOA_debit_sum += parseFloat(data['debit']);

                    var link_html = '<label><a href="' + data['attachment'] + '" target="_blank" class="' + (data['attachment']==null ? 'visible-hidden':'') + '"><img src="' + "{{ cAsset('assets/images/document.png') }}" + '"' + ' width="15" height="15" style="cursor: pointer;"></a></label>';
                    $('td', row).eq(7).html('').append(link_html);
                },
                drawCallback: function (response) {
                    //console.log(response.json.voy_info);
                    var currency = (currency_soa=='USD'?'$':'??');
                    var report_row = '<tr class="tr-report" style="height:30px;border:2px solid black;">';
                    report_row += '<td class="sub-small-header style-normal-header"></td><td class="sub-small-header style-normal-header"></td><td class="sub-small-header style-normal-header text-center">??????</td>';
                    report_row += '<td class="sub-small-header style-normal-header text-center">' + currency + '</td>';
                    report_row += '<td style="padding-right:5px!important;" class="style-normal-header text-right ' + (SOA_credit_sum >= 0 ? 'style-blue-input':'style-red-input') + '">' + currency + ' ' + prettyValue(SOA_credit_sum) + '</td>';
                    report_row += '<td style="padding-right:5px!important;" class="style-normal-header text-right ' + (SOA_debit_sum >= 0 ? '':'style-red-input') + '">' + currency + ' ' + prettyValue(SOA_debit_sum) + '</td>';
                    var total_sum = SOA_credit_sum - SOA_debit_sum;
                    report_row += '<td colspan="2" style="padding-right:5px!important;" class="style-normal-header text-right ' + (total_sum >= 0 ? 'style-blue-input':'style-red-input') + '">' + currency + ' ' + prettyValue(total_sum) + '</td>';
                    report_row += '</tr>';

                    $('#table-soa-list-body').append(report_row);
                    voyID_soa = response.json.voy_info.id;
                    voyType_soa = response.json.voy_info.CP_kind.substr(0,1);
                    $('#contract_type').html(response.json.voy_info.CP_kind);
                    $('#contract_date').html(response.json.voy_info.CP_Date);
                    $('#contract_content').html(response.json.Cargo);
                    $('#contract_amount').html(response.json.voy_info.Cgo_Qtty);
                    $('#contract_signon_port').html(response.json.LPort);
                    $('#contract_signoff_port').html(response.json.DPort);
                    $('#contract_unit').html(response.json.voy_info.Freight);   //total_Freight zxc
                    if (response.json.voy_info.is_attachment == 1) {
                        $('#contract_attachment').html('<a href="' + response.json.voy_info.attachment_url + '" target="_blank" ><img src="' + "{{ cAsset('assets/images/document.png') }}" + '" width="15" height="15"></a>');
                    }
                }
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }

        $(function () {
            $('ul li a[data-toggle=tab]').click(function(){
                $nowTab = $(this).attr("href");
                if ($nowTab == '#tab_graph') {
                }
                else if ($nowTab == '#tab_table') {
                    year_table = $("#select-table-year option:selected").val();
                    shipid_table = $("#select-table-ship").val();
                    $('#table_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '" ' + year_table + '??? ');
                    if (listTable == null) initTable();
                }
                else if ($nowTab == '#tab_soa') {
                    shipid_soa = $('#select-soa-ship').val();
                    currency_soa = $('#select-soa-currency').val();
                    if (listSOATable == null){
                        getVoyList(shipid_soa);
                    }
                }
            });
        });

        $('#select-soa-ship').on('change', function() {
            shipid_soa = $('#select-soa-ship').val();
            voyNo_soa = null;
            clearSOAInfo();
            getVoyList(shipid_soa);
        });

        $('#select-soa-contract').on('change', function() {
            voyNo_soa = $('#select-soa-contract').val();
            selectSOAInfo();
        });

        $('#select-soa-currency').on('change', function() {
            currency_soa = $('#select-soa-currency').val();
            selectSOAInfo();
        });

        function gotoDetailPage()
        {
            if (voyNo_soa == null) return;
            //window.open(BASE_URL + 'business/contract?shipId=' + shipid_soa + '&voy_id=' + voyID_soa, '_blank');
            window.open(BASE_URL + 'shipManage/dynamicList', '_blank');
        }

        function clearSOAInfo()
        {
            $('#contract_type').html('');
            $('#contract_date').html('');
            $('#contract_content').html('');
            $('#contract_amount').html('');
            $('#contract_signon_port').html('');
            $('#contract_signoff_port').html('');
            $('#contract_unit').html('');
            $('#contract_attachment').html('');
            $('#table-soa-list-body').html('');
        }

        function selectSOAInfo()
        {
            clearSOAInfo();
            $('#soa_title').html($("#select-soa-ship option:selected").attr('data-name') + ' ' + $("#select-soa-contract option:selected").val() + '??? ');
            $('#soa_info').html($("#select-soa-ship option:selected").attr('data-name') + ' ' + $("#select-soa-contract option:selected").val() + '??? ');

            SOA_credit_sum = 0;
            SOA_debit_sum = 0;
            if (listSOATable == null) {
                initSOATable();
            }
            else {
                if (voyNo_soa != null) {
                    listSOATable.column(1).search(shipid_soa, false, false);
                    listSOATable.column(2).search(voyNo_soa, false, false);
                    listSOATable.column(3).search(currency_soa, false, false).draw();
                }
            }
        }

        var clicked_voyno = null;
        function setEvents() {
            $('.td_voy_no').on('click', function(e) {
                alertAudio();
                var voyNo = e.target.innerHTML;
                if (voyNo != "")
                {
                    if ($('#select-soa-ship').val() == shipid_table)
                    {
                        voyNo_soa = voyNo;
                        $('#select-soa-contract').val(voyNo_soa);
                        selectSOAInfo();
                    }
                    else
                    {
                        clicked_voyno = voyNo;
                        $('#select-soa-ship').val(shipid_table);
                        getVoyList(shipid_table);
                        if (listSOATable == null) {
                            shipid_soa = shipid_table
                            voyNo_soa = voyNo;
                            currency_soa = $('#select-soa-currency').val();
                            initSOATable();
                        }
                    }
                    
                    $('a[href="#tab_soa"]').trigger('click');
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
                    var select_html = "";
                    for (var i=0;i<data['voyList'].length;i++)
                        select_html += '<option value="' + data['voyList'][i].Voy_No + '" data-id="' + data['voyList'][i].id + '" data-id="' + data['voyList'][i].id + '">' + data['voyList'][i].Voy_No + '</option>';
                    $('#select-soa-contract').html(select_html);

                    if (clicked_voyno != null) {
                        voyNo_soa = clicked_voyno;
                        $('#select-soa-contract').val(voyNo_soa);
                        clicked_voyno = null;
                        selectSOAInfo();
                    }
                    else
                    {
                        if (data['voyList'].length > 0) {
                            voyNo_soa = data['voyList'][0].Voy_No;
                            selectSOAInfo();
                        }
                    }
                }
            });
        }
        
        function alertAudio() {
            document.getElementById('warning-audio').play();
        }

        function fnExcelTableReport()
        {
            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-income-expense-list');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='21' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#soa_title').html() + "????????????</td></tr>";
            for(var j = 0; j < tab.rows.length ; j++)
            {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        if (i == 8)
                            tab.rows[j].childNodes[i].style.width = '1500px';
                        else
                            tab.rows[j].childNodes[i].style.width = '100px';
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                    tab.rows[j].childNodes[0].style.width = '80px';
                    tab.rows[j].childNodes[1].style.width = '80px';
                }
                else if (j == 1) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                }
                else if (j == (tab.rows.length - 1))
                {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.height = "30px";
                        tab.rows[j].childNodes[i].style.fontWeight = "bold";
                        tab.rows[j].childNodes[i].style.backgroundColor = '#ebf1de';
                    }
                }
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";
            
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");
            tab_text= tab_text.replace(/<img[^>]*>/gi,"");
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

            var filename = $('#table_info').html() + '_' + year_table + '_???????????????';
            exportExcel(tab_text, filename, filename);
            
            return 0;
        }

        function fnExcelSOAReport()
        {
            if (voyNo_soa == null) return;

            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-soa-general-info');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='7' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#soa_title').html() + "????????????</td></tr>";
            for(var j = 0; j < tab.rows.length ; j++)
            {
                if (j == 0) {
                    for (var i=1; i<tab.rows[j].childElementCount*2;i+=2) {
                        tab.rows[j].childNodes[i].style.width = '100px';
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                    tab.rows[j].childNodes[5].style.width = '200px';
                    tab.rows[j].childNodes[9].style.width = '300px';
                    tab.rows[j].childNodes[11].style.width = '300px';
                    tab.rows[j].childNodes[13].style.width = '300px';
                }
                tab.rows[j].childNodes[15].remove();
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";
            var total_text = tab_text;

            tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            real_tab = document.getElementById('table-soa-list');
            tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='8' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#soa_info').html() + "SOA</td></tr>";
            for(var j = 0 ; j < tab.rows.length ; j++)
            {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.width = '100px';
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                    tab.rows[j].childNodes[2].style.width = '400px';
                    tab.rows[j].childNodes[3].style.width = '60px';
                    tab.rows[j].childNodes[4].style.width = '300px';
                    tab.rows[j].childNodes[5].style.width = '300px';
                }
                if (j < (tab.rows.length-1)) tab.rows[j].childNodes[7].remove();
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";
            total_text += tab_text;

            total_text= total_text.replace(/<A[^>]*>|<\/A>/g, "");
            total_text= total_text.replace(/<img[^>]*>/gi,"");
            total_text= total_text.replace(/<input[^>]*>|<\/input>/gi, "");

            var filename = $('#select-soa-ship option:selected').text() + '_' + voyType_soa + voyNo_soa + '_SOA';
            exportExcel(total_text, filename, filename);
            
            return 0;
        }

    </script>

@endsection
