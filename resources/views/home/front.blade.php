@extends('layout.header')

@section('sidebar')
@section('content')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link href="{{ cAsset('assets/css/slides.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/css/home.css') }}" rel="stylesheet"/>


    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script src="{{ cAsset('js/dashboards_dashboard-1.js') }}"></script>

    <link href="{{ cAsset('assets/css/slides.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ cAsset('assets/css/koala.min.1.5.js') }}"></script>

    <style>
        embed:scroll-bar{display:none}

        #currency-list tr td {
            padding: 0 4px!important;
        }
        .c3 path {
            stroke-width: 3px;
        }

        #chartist-h-bars .ct-series-a line {
            stroke: #81afe4;
            /*stroke-width: 5px;
            stroke-dasharray: 10px 20px;*/
        }

        #chartist-h-bars .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars-02 .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-a line {
            stroke: #81afe4;
        }

        .ship-item:hover {
            background-color: #ffe3e082;
        }

        .c3-legend-item text {
            font-size:14px;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-9">
            <div class="row" style="padding-top: 12px;">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">                                
                                <div class="card-body">
                                    <div id="fsD1" class="focus" style="margin-left:0px">  
                                        <div id="D1pic1" class="fPic">  
                                            <div class="fcon" style="display: none;">
                                                <a target="_blank" href="http://www.cnshipnet.com/news/14/67739.html"><img src="{{ cAsset('assets/img/1.jpg') }}" alt="江龙船艇30米级钢铝交通船试航" style="opacity: 1;"></a>
                                                <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67739.html">江龙船艇30米级钢铝交通船试航</a></h3></span>
                                            </div><div class="fcon" style="display: none;">
                                                <a target="_blank" href="http://www.cnshipnet.com/news/14/67193.html"><img src="{{ cAsset('assets/img/2.jpg') }}" alt="启东中远海运海工风电安装船按计划完工" style="opacity: 1;"></a>
                                                <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67193.html">启东中远海运海工风电安装船按计划完工</a></h3></span>
                                            </div><div class="fcon" style="display: none;">
                                                <a target="_blank" href="http://www.cnshipnet.com/news/14/67089.html"><img src="{{ cAsset('assets/img/3.jpg') }}" alt="重庆中江船业130米集散货船顺利开航离港" style="opacity: 1;"></a>
                                                <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67089.html">重庆中江船业130米集散货船顺利开航离港</a></h3></span>
                                            </div><div class="fcon" style="display: block;">
                                                <a target="_blank" href="http://www.cnshipnet.com/news/14/66776.html"><img src="{{ cAsset('assets/img/4.jpg') }}" alt="达门新推双体深绞吸式挖泥船DCSD500" style="opacity: 0.91;"></a>
                                                <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/66776.html">达门新推双体深绞吸式挖泥船DCSD500</a></h3></span>
                                            </div>    
                                        </div>
                                        <div class="fbg">  
                                        <div class="D1fBt" id="D1fBt">  
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>1</i></a>  
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>2</i></a>  
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>3</i></a>  
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class="current"><i>4</i></a>  
                                        </div>  
                                        </div>  
                                        <span class="prev" style="opacity: 0.3;"></span>   
                                        <span class="next" style="opacity: 0.3;"></span>    
                                    </div>
                        <div class="card mb-4">
                            <div class="card-header decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">等待批准</span>





































































                                </div>
                                
                                <div class="card-body">
                                    <div class="advertise">
                                        <div style="width: 10%; padding-left: 16px;">
                                            <h5 style="font-weight: bold;">重要公告 : </h5>
                                        </div>
                                        <div class="sign_list slider" style="width: 85%;">
                                            @if(isset($reportList) && count($reportList) > 0)
                                                @foreach ($reportList as $item)
                                                    <div style="height: auto; outline: unset;">
                                                        <h5>
                                                            <a href="/decision/receivedReport?reportId={{$item->id}}" style="color: white; outline: unset;" target="_blank">
                                                                从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.
                                                            </a>
                                                        </h5>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span>{{ trans('home.message.no_data') }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right" style="width: 5%; padding-right: 16px;">
                                            <a href="/decision/receivedReport" style="color: white; text-decoration: underline;" target="_blank">更多</a>
                                        </div>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body decide-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                    @foreach($reportList as $item)
                                        @if($item->obj_type == 1)
                                        <li><a target="_blank" href="https://www.totoprayogo.com/#"><div class="arrow-navi"><span class="visually-hidden">Read More</span></div>从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                        @else
                                        <li><a target="_blank" href="https://www.totoprayogo.com/#"><div class="arrow-navi"><span class="visually-hidden">Read More</span></div>从[{{ $item->realname }}]收到了[{{ $item->obj_name }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header no-attachment-decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">等待凭证</span>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body no-attachment-decide-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                    @foreach($reportList as $item)
                                        @if($item->attachment == '' || $item->attachment == null)
                                            @if($item->obj_type == 1)
                                            <li><a target="_blank" href="https://www.totoprayogo.com/#"><div class="arrow-navi"><span class="visually-hidden">Read More</span></div>从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                            @else
                                            <li><a target="_blank" href="https://www.totoprayogo.com/#"><div class="arrow-navi"><span class="visually-hidden">Read More</span></div>从[{{ $item->realname }}]收到了[{{ $item->obj_name }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header expired-cert-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">证书到期</span>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body expired-cert-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                @if(isset($expired_data['ship']) && count($expired_data['ship']) > 0)
                                    @foreach($expired_data['ship'] as $item)
                                        <li><a target="_blank" href="#">{{ $item->ship_name . ' - ' . $item->cert_name }}</a></li>
                                    @endforeach
                                @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-body d-none">
                                <div id="fsD1" class="focus" style="margin-left:0px">
                                    <div id="D1pic1" class="fPic">
                                        <div class="fcon" style="display: none;">
                                            <a target="_blank" href="http://www.cnshipnet.com/news/14/67739.html"><img src="{{ cAsset('assets/img/1.jpg') }}" alt="江龙船艇30米级钢铝交通船试航" style="opacity: 1;"></a>
                                            <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67739.html">江龙船艇30米级钢铝交通船试航</a></h3></span>
                                        </div><div class="fcon" style="display: none;">
                                            <a target="_blank" href="http://www.cnshipnet.com/news/14/67193.html"><img src="{{ cAsset('assets/img/2.jpg') }}" alt="启东中远海运海工风电安装船按计划完工" style="opacity: 1;"></a>
                                            <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67193.html">启东中远海运海工风电安装船按计划完工</a></h3></span>
                                        </div><div class="fcon" style="display: none;">
                                            <a target="_blank" href="http://www.cnshipnet.com/news/14/67089.html"><img src="{{ cAsset('assets/img/3.jpg') }}" alt="重庆中江船业130米集散货船顺利开航离港" style="opacity: 1;"></a>
                                            <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/67089.html">重庆中江船业130米集散货船顺利开航离港</a></h3></span>
                                        </div><div class="fcon" style="display: block;">
                                            <a target="_blank" href="http://www.cnshipnet.com/news/14/66776.html"><img src="{{ cAsset('assets/img/4.jpg') }}" alt="达门新推双体深绞吸式挖泥船DCSD500" style="opacity: 0.91;"></a>
                                            <span class="shadow"><h3><a target="_blank" href="http://www.cnshipnet.com/news/14/66776.html">达门新推双体深绞吸式挖泥船DCSD500</a></h3></span>
                                        </div>
                                    </div>
                                    <div class="fbg">
                                        <div class="D1fBt" id="D1fBt">
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>1</i></a>
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>2</i></a>
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>3</i></a>
                                            <a href="javascript:void(0)" hidefocus="true" target="_self" class="current"><i>4</i></a>
                                        </div>
                                    </div>
                                    <span class="prev" style="opacity: 0.3;"></span>
                                    <span class="next" style="opacity: 0.3;"></span>
                                </div>
                            </div>


                            <div class="card-body p-0" style="box-shadow: 0px 0px 8px 4px #d2d2d2;">
                                <div class="advertise">
                                    <div style="padding-left: 16px;">
                                        <h5 style="font-weight: bold;">重要公告 : </h5>
                                    </div>
                                    <div class="sign_list slider" style="width: auto; min-width: 100px;">
                                        @if(isset($reportList) && count($reportList) > 0)
                                            @foreach ($reportList as $item)
                                                <div style="height: auto; outline: unset;">
                                                    <h5>
                                                        <a href="/decision/receivedReport?reportId={{$item->id}}" style="color: white; outline: unset;" target="_blank">
                                                            @if($item->obj_type == 1)
                                                            从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.
                                                            @else
                                                            从[{{ $item->realname }}]收到了[{{ $item->obj_name }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.
                                                            @endif
                                                        </a>
                                                    </h5>
                                                </div>
                                            @endforeach
                                        @else
                                            <span>{{ trans('home.message.no_data') }}</span>
                                        @endif
                                    </div>
                                    <div class="text-right" style="padding-right: 16px; margin-left: auto;">
                                        <a href="/decision/receivedReport" style="color: white; text-decoration: underline;" target="_blank">更多</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div style="height: 210px; width: 100%;" class="mb-4">
                                        <canvas id="statistics-chart-1"></canvas>
                                    </div>
                                    <div class="w-100" style="height: 200px;">
                                        <canvas id="statistics-chart-3"></canvas>
                                    </div>
                                    <div class="table-responsive" id="decidemanage_list_table">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr class="black br-hblue">
                                                <th style="width:5%">{{ trans('home.table.no') }}</th>
                                                <th style="width: 30%">{{ trans('home.table.ship_name') }}</th>
                                                <th style="width:10%">{{ trans('home.table.ship_member_cnt') }}</th>
                                                <th style="width: 20%">{{ trans('home.title.profit') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($shipList) && count($shipList) > 0)
                                                @foreach ($shipList as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->shipName_En }}({{ $item->shipName_Cn }})</td>
                                                        <td>{{ $item->userCount }}</td>
                                                        <td>{{ $key * 133 }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                    <div class="row default-style main-panel">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title f-center">
                                    <h4 style="text-align:center;">
                                    <select id="year" style="font-size:13px">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021" selected>2021</option>
                                    </select> 利润累计</h4>
                                </div>
                                <div class="card-body">
                                    <div id="c3-graph" style="height: 250px"></div>
                                </div>
                            </div>

                            <hr class="dot-hr"/>
                            <div class="card-body">
                                <div class="card-title f-center">
                                    <h4 style="text-align:center;">
                                    <select id="year" style="font-size:13px">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021" selected>2021</option>
                                    </select> 收支累计</h4>
                                </div>
                                <div id="chartist-h-bars" style="height: 200px"></div>
                                <div class="table-responsive" id="decidemanage_list_table">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th class="style-normal-header" style="width:5%">{{ trans('home.table.no') }}</th>
                                            <th class="style-normal-header" style="width: 30%">{{ trans('home.table.ship_name') }}</th>
                                            <th class="style-normal-header" style="width:10%">{{ trans('home.table.ship_member_cnt') }}</th>
                                            <th class="style-normal-header" style="width: 20%">{{ trans('home.title.profit') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($shipList) && count($shipList) > 0)
                                            @foreach ($shipList as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->shipName_En }}({{ $item->shipName_Cn }})</td>
                                                    <td>{{ $item->userCount }}</td>
                                                    <td>{{ $key * 133 }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">{{ trans('home.message.no_data') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="dot-hr"/>
                            <div class="card-body">
                                <div class="card-title f-center">
                                    <h4 style="text-align:center;">
                                    <select id="year" style="font-size:13px">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021" selected>2021</option>
                                    </select> 支出分数累计</h4>
                                </div>
                                <div id="chartist-h-bars-02" style="height: 250px"></div>
                            </div>
                            {{--<hr class="dot-hr"/>--}}
                            <div class="card-body">
                                <div class="w-100 d-none" style="height: 200px;">
                                    <canvas id="statistics-chart-3"></canvas>
                                </div>
                                <div class="table-responsive" id="decidemanage_list_table">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th class="style-normal-header" style="width:5%">{{ trans('home.table.no') }}</th>
                                            <th class="style-normal-header" style="width: 30%">{{ trans('home.table.ship_name') }}</th>
                                            <th class="style-normal-header" style="width:10%">{{ trans('home.table.ship_member_cnt') }}</th>
                                            <th class="style-normal-header" style="width: 20%">{{ trans('home.title.profit') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($shipList) && count($shipList) > 0)
                                            @foreach ($shipList as $key => $item)
                                                <tr>
                                                    <td colspan="8">{{ trans('home.message.no_data') }}</td>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->shipName_En }}({{ $item->shipName_Cn }})</td>
                                                    <td>{{ $item->userCount }}</td>
                                                    <td>{{ $key * 133 }}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">{{ trans('home.message.no_data') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                <div class="col-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="bigger-150">{{ trans('home.title.notice') }}</span>
                        <div class="card-header caption-title">
                            <div class="card-title front-span">
                                <span class="bigger-120">货币汇率</span>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ trans('home.message.no_data') }}
                        <div class="caption-border">
                            <table id="currency-list" v-cloak>
                                <tbody>
                                    <tr v-for="(item, index) in list">
                                        <td><img v-bind:src="item.image" alt="item.name" width="24" height="24"></td>
                                        <td>@{{ item.name }}</td>
                                        <td>@{{ item.current_price }} CNY</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.page-content -->
    </div><!-- /.main-content -->
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script>
        var currencyListObj = new Vue({
            el: '#currency-list',
            data: {
                list: []
            },
        });

    <script>
        getCurrencyRate();
        setInterval(getCurrencyRate, 3000);
        function getCurrencyRate() {
            $.ajax({
                url: "https://api.coingecko.com/api/v3/coins/markets?vs_currency=cny&order=market_cap_desc&per_page=20&page=1&sparkline=true",
                type: "GET",
                success: function (data, status, xhr) {
                    currencyListObj.list = data;
                    currencyListObj.$forceUpdate();
                    console.log(data);
                }
            });
        }

        $(".sign_list").slick({
            dots: false,
            vertical: true,
            centerMode: false,
            autoplay: true,
            prevArrow: false,
            nextArrow: false,
            autoplaySpeed: 2000,
            swipe: false,
            slidesToShow: 1,
            slidesToScroll: 1
        });

        $(function() {
            var chart1 = new Chart(document.getElementById('statistics-chart-1').getContext("2d"), {
                type: 'line',
                data: {
                    labels: ['2020-08', '2020-09', '2020-10', '2020-11', '2020-12', '2021-01', '2021-02', '2021-03'],
                    datasets: [{
                        label: '收益',
                        data: [93, 25, 95, 59, 46, 68, 4, 41],
                        borderWidth: 1,
                        backgroundColor: 'rgba(28,180,255,.05)',
                        borderColor: 'rgba(28,180,255,1)'
                    }],
                },
                options: {
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#aaa'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#aaa'
                            }
                        }]
                    },
    </script>
    <script type="text/javascript">
        Qfast.add('widgets', { path: "{{ cAsset('assets/css/terminator.js') }}", type: "js", requires: ['fx'] });
        Qfast(false, 'widgets', function () {
            K.tabs({
                id: 'fsD1',   //焦点图包裹id
                conId: "D1pic1",  //** 大图域包裹id
                tabId:"D1fBt",
                tabTn:"a",
                conCn: '.fcon', //** 大图域配置class
                auto: 1,   //自动播放 1或0
                effect: 'fade',   //效果配置
                eType: 'click', //** 鼠标事件
                pageBt:true,//是否有按钮切换页码
                bns: ['.prev', '.next'],//** 前后按钮配置class
                interval: 3000  //** 停顿时间
            })
        });

                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        c3.generate({
            bindto: '#c3-graph',
            color: { pattern: [ '#00BCD4', '#607D8B', 'red', '#f7931a' ] },
            bar: {
                width: 40
            },
            point: {
              r: 5
            },
            data: {
                columns: [
                    [ '船舶1', 5000, 4800, 4900, 4700, 4500, 4000, 3500, 3900, 4000, 4200, 4500, 4800 ],
                    [ '船舶2', 4500, 4000, 3500, 3900, 4000, 4200, 4500, 4800, 5000, 4800, 4900, 4700 ],
                    [ '船舶3', 4900, 4700, 4500, 4000, 3500, 3900, 4000, 4200, 4500, 4800, 5000, 4800 ],
                    [ '船舶4', 4700, 4500, 4400, 3500, 3900, 4000, 4200, 4500, 4800, 5000, 4800, 4900 ],
                ],
            }
        });

            var chart3 = new Chart(document.getElementById('statistics-chart-3').getContext("2d"), {
                type: 'bar',
                data: {
                    datasets: [{
                        data: [24, 92, 77, 90, 91, 78, 28, 49, 23, 81],
                        borderWidth: 1,
                        barPercentage: 0.5,
                        barThickness: 6,
                        maxBarThickness: 8,
                        minBarLength: 2,
                        backgroundColor: '#82ccff',
                    }],
                    labels: ['船舶1', '船舶2', '船舶3', '船舶4', '船舶5', '船舶6', '船舶7', '船舶8', '船舶9', '船舶10']
                },
        new Chartist.Bar('#chartist-h-bars', {
            labels: ['', ''],
            series: [
                [ 5, -5 ],
                [ 5, -5 ],
                [ 1, -7 ] ]
        }, {
            seriesBarDistance: 10,
            reverseData:       true,
            horizontalBars:    true,
            verticalBars:       false,
            axisY:             { offset: 10 }
        });

                options: {
                    scales: {
                        xAxes: [{
                            barPercentage: 0.5,
                            barThickness: 30,
                            maxBarThickness: 30,
                            minBarLength: 2,
                            display: true,
                        }],
                        yAxes: [{
                            display: false
                        }]
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        new Chartist.Bar('#chartist-h-bars-02', {
            labels: ['', '', '', ''],
            series: [
                [ 1, 4, 3, 7 ],
                [ 6, 2, 1, 4 ],
                [ 1, 7, 3, 6 ] ]
        }, {
            seriesBarDistance: 10,
            reverseData:       true,
            horizontalBars:    true,
            verticalBars:       false,
            axisY:             { offset: 10 }
        });
    </script>
    <script type="text/javascript">
        Qfast.add('widgets', { path: "{{ cAsset('assets/css/terminator.js') }}", type: "js", requires: ['fx'] });  
        Qfast(false, 'widgets', function () {
        K.tabs({
        id: 'fsD1',   //焦点图包裹id  
        conId: "D1pic1",  //** 大图域包裹id  
        tabId:"D1fBt",  
        tabTn:"a",
        conCn: '.fcon', //** 大图域配置class       
        auto: 1,   //自动播放 1或0
        effect: 'fade',   //效果配置
        eType: 'click', //** 鼠标事件
        pageBt:true,//是否有按钮切换页码
        bns: ['.prev', '.next'],//** 前后按钮配置class                          
        interval: 3000  //** 停顿时间  
        }) 
        }) 
    </script>


@endsection