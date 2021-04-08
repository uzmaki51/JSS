@extends('layout.header')

@section('sidebar')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link href="{{ cAsset('assets/css/slides.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/css/home.css') }}" rel="stylesheet"/>


    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="{{ cAsset('assets/css/koala.min.1.5.js') }}"></script>

    <style>
        embed:scroll-bar{display:none}

        .select-hidden {
            display: none;
            visibility: hidden;
            padding-right: 10px;
        }

        .select {
            cursor: pointer;
            display: inline-block;
            position: relative;
            font-size: 16px;
            color: #fff;
            width: 220px;
            height: 36px;
        }

        .select-styled {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100px;
            border-radius: 20px;
            background-color: transparent;
            color: #007dfb;
            border: 1px solid #007dfb;
            padding: 9px 16px;
            -moz-transition: all 0.2s ease-in;
            -o-transition: all 0.2s ease-in;
            -webkit-transition: all 0.2s ease-in;
            transition: all 0.2s ease-in;
        }
        .select-styled:after {
            content: "";
            width: 0;
            height: 0;
            border: 7px solid transparent;
            border-color: #007dfb transparent transparent transparent;
            position: absolute;
            top: 14px;
            right: 10px;
            color: #007dfb;
        }
        .select-styled:hover {
            background-color: #007dfb;
            color: white;
        }
        .select-styled:hover:after {
            content: "";
            width: 0;
            height: 0;
            border: 7px solid transparent;
            border-color: #fff transparent transparent transparent;
            position: absolute;
            top: 14px;
            right: 10px;
            color: #007dfb;
        }
        .select-styled:active, .select-styled .active {
            color: white;
            background-color: #007dfb;
        }
        .select-styled:active:after, .select-styled.active:after {
            top: 7px;
            border-color: transparent transparent #fff transparent;
        }

        .select-options {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            left: 0;
            z-index: 999;
            width: 100px;
            margin: 0;
            padding: 0;
            list-style: none;
            background-color: #007dfb;
        }
        .select-options li {
            margin: 0;
            padding: 10px 0;
            text-indent: 15px;
            width: 100px;
            border-top: 1px solid #007dfb77;
            -moz-transition: all 0.15s ease-in;
            -o-transition: all 0.15s ease-in;
            -webkit-transition: all 0.15s ease-in;
            transition: all 0.15s ease-in;
            color: white;
        }
        .select-options li:hover {
            color: #007dfb;
            background: #fff;
        }

        .select-options li[rel="hide"] {
            display: none;
        }

        #currency-list tr td {
            padding: 0 4px!important;
        }
        .c3 path {
            stroke-width: 3px;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="row" style="padding-top: 12px;">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header decide-title">
                                <div class="card-title">
                                    <span class="bigger-150">等待批准</span>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body decide-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                    @foreach($reportList as $item)
                                        <li><a target="_blank" href="https://www.totoprayogo.com/#"><div class="arrow"><span class="visually-hidden">Read More</span></div>从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header no-attachment-decide-title">
                                <div class="card-title">
                                    <span class="bigger-150">等待凭证</span>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body no-attachment-decide-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                    @foreach($reportList as $item)
                                        @if($item->attachment == '' || $item->attachment == null)
                                            <li><a target="_blank" href="https://www.totoprayogo.com/#">从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeData')[$item->flowid][0] }}审批文件.</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header expired-cert-title">
                                <div class="card-title">
                                    <span class="bigger-150">证书到期</span>
                                </div>
                                <a href="#" class="more-btn">更多</a>
                            </div>
                            <div class="card-body expired-cert-border" style="padding: 0 8px!important;">
                                <ul class="timeline">
                                    @foreach($expired_data['ship'] as $item)
                                        <li><a target="_blank" href="https://www.totoprayogo.com/#">{{ $item->ship_name . '的' . $item->cert_name }}</a></li>
                                    @endforeach
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
                            </div>
                        </div>
                    </div>

                    <div class="row default-style main-panel">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex">
                                    <h2 style="padding: 2px 4px;text-shadow: 3px 3px 4px black;">利润累计</h2>
                                    <select id="year">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div id="c3-graph" style="height: 250px"></div>
                                </div>
                            </div>

                            <hr class="dot-hr"/>
                            <div class="card-body">
                                <div class="card-title d-flex">
                                    <h2 style="padding: 2px 4px;text-shadow: 3px 3px 4px black;">收支累计</h2>
                                    <select id="year">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                    </select>
                                </div>
                                <div id="chartist-h-bars" style="height: 200px"></div>
                                <div class="table-responsive" id="decidemanage_list_table">
                                    <table class="table table-bordered table-hover">
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
                                <div class="card-title d-flex">
                                    <h2 style="padding: 2px 4px;text-shadow: 3px 3px 4px black;">支出分数累计</h2>
                                    <select id="year">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                    </select>
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
                <div class="col-lg-2">
                    <div class="card">
                        <div class="card-header caption-title">
                            <div class="card-title">
                                <span class="bigger-150">货币汇率</span>
                            </div>
                        </div>
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
            // var chart3 = new Chart(document.getElementById('statistics-chart-3').getContext("2d"), {
            //     type: 'bar',
            //     data: {
            //         datasets: [{
            //             data: [[24, 92, 77, 90, 91, 78, 28, 49, 23, 81]],
            //             borderWidth: 1,
            //             barPercentage: 0.5,
            //             barThickness: 6,
            //             maxBarThickness: 8,
            //             minBarLength: 2,
            //             backgroundColor: '#82ccff',
            //         }],
            //         labels: ['船舶1', '船舶2', '船舶3', '船舶4', '船舶5', '船舶6', '船舶7', '船舶8', '船舶9', '船舶10']
            //     },
            //
            //     options: {
            //         scales: {
            //             xAxes: [{
            //                 barPercentage: 0.5,
            //                 barThickness: 30,
            //                 maxBarThickness: 30,
            //                 minBarLength: 2,
            //                 display: true,
            //             }],
            //             yAxes: [{
            //                 display: false
            //             }]
            //         },
            //         legend: {
            //             display: false
            //         },
            //         responsive: true,
            //         maintainAspectRatio: false
            //     }
            // });
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
        });

        c3.generate({
            bindto: '#c3-graph',
            color: { pattern: [ '#00BCD4', '#607D8B', 'red', 'yellow' ] },
            bar: {
                width: 40
            },
            point: {
              r: 5
            },
            data: {
                columns: [
                    [ '船舶1', 7000, 4800, -4200, 200, -8100, 3500 ],
                    [ '船舶2', 4500, 1200, -3300, 1200, -4300, 7700 ],
                    [ '船舶3', 1300, 2100, -5500, 1800, -1200, 2200 ],
                    [ '船舶4', 6001, 6100, -5100, 9400, -5200, 5500 ]
                ],
            }
        });

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

        $('select').each(function(){
            var $this = $(this), numberOfOptions = $(this).children('option').length;

            $this.addClass('select-hidden');
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

            var $styledSelect = $this.next('div.select-styled');
            $styledSelect.text($this.children('option').eq(0).text() + ' 年');

            var $list = $('<ul />', {
                'class': 'select-options'
            }).insertAfter($styledSelect);

            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text() + ' 年',
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            var $listItems = $list.children('li');

            $styledSelect.click(function(e) {
                e.stopPropagation();
                $('div.select-styled.active').not(this).each(function(){
                    $(this).removeClass('active').next('ul.select-options').hide();
                });
                $(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function(e) {
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
                //console.log($this.val());
            });

            $(document).click(function() {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });



    </script>
@endsection
