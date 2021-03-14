@extends('layout.header')

@section('sidebar')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link href="{{ cAsset('assets/css/home.css') }}" rel="stylesheet"/>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script src="{{ cAsset('js/dashboards_dashboard-1.js') }}"></script>

    <link href="{{ cAsset('assets/css/slides.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ cAsset('assets/css/koala.min.1.5.js') }}"></script>

    <style>
        embed:scroll-bar{display:none}
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-9">
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
                                                            <a href="/decision/decideShow?reportId={{$item->id}}" style="color: white; outline: unset;" target="_blank">
                                                                <span>从[{{ $item->realname }}]收到了[{{ $shipForDecision[$item->shipNo] }}]号的{{ g_enum('ReportTypeLabelData')[$item->flowid][0] }}审批文件.
                                                            </a>
                                                        </h5>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span>{{ trans('home.message.no_data') }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right" style="width: 5%; padding-right: 16px;">
                                            <a href="/decision/decidemanage" style="color: white; text-decoration: underline;" target="_blank">更多</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <span class="bigger-200" style="vertical-align: middle;">{{ trans('home.title.no_attach_sign_off') }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr class="black br-hblue">
                                            <th style="text-align: center;width: 60px">{{ trans('home.table.no') }}</th>
                                            <th class="center" style="width:70px">{{ trans('home.table.attach') }}</th>
                                            <th class="center" style="width:80px">{{ trans('home.table.percentage') }}</th>
                                            <th class="center">{{ trans('home.table.sign_name') }}</th>
                                            <th class="center" style="width:15%">{{ trans('home.table.sign_type') }}</th>
                                            <th class="center" style="width:100px">{{ trans('home.table.sign_sender') }}</th>
                                            <th class="center" style="width:125px">{{ trans('home.table.sign_at') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($reportList) && count($reportList) > 0)
											<?php $index = 1?>
                                            @foreach ($reportList as $reportinfo)
                                                @if(!isset($reportinfo->fileName1) || ($reportinfo->fileName1 == '' && $reportinfo->fileName2 == ''))
                                                    <tr>
                                                        <td class="center">{{$index++}}</td>
                                                        <td class="center">
                                                            @if(!empty($reportinfo->file1))
                                                                <a href="/fileDownload?type=report&path={{$reportinfo->file1}}" class="hide-option"
                                                                   @if(!empty($reportinfo->fileName1)) title="{{$reportinfo->fileName1}}" @endif>
                                                                    <i class="icon-file bigger-125"></i>
                                                                </a>
                                                            @endif
                                                            @if(!empty($reportinfo->file2))
                                                                <a href="/fileDownload?type=report&path={{$reportinfo->file2}}" class="hide-option"
                                                                   @if(!empty($reportinfo->fileName2)) title="{{$reportinfo->fileName2}}" @endif>
                                                                    <i class="icon-file bigger-125"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="center">
                                                            <div class="progress progress-striped" data-percent="{{$reportinfo->decideCount}}/{{$reportinfo->totalCount}}">
																<?php $rate = ($reportinfo->decideCount / $reportinfo->totalCount) * 100; ?>
                                                                <div class="progress-bar progress-bar-success" style="width:{{$rate}}%;"></div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="/decision/decideShow?reportId={{$reportinfo->id}}">{{$reportinfo->title}}</a>
                                                        </td>
                                                        <td class="center">
                                                            {{$reportinfo->flowTitle}}
                                                        </td>
                                                        <td class="center">
                                                            {{$reportinfo->realname}}
                                                        </td>
                                                        <td class="center">
                                                            {!! convert_datetime($reportinfo->draftDate) !!}
                                                        </td>
                                                    </tr>
                                                @endif
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
                    </div -->
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
                                                        <td>{{ $item->shipName_Cn }}({{ $item->shipName_En }})</td>
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
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="bigger-150">{{ trans('home.title.notice') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ trans('home.message.no_data') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.page-content -->
    </div><!-- /.main-content -->

    <script>
        var token = '{!! csrf_token() !!}';
        var attendStatus = '{{$attendStatus}}';

        $(".sign_list").slick({
            dots: false,
            vertical: true,
            centerMode: false,
            autoplay: true,
            prevArrow: false,
            nextArrow: false,
            autoplaySpeed: 30000,
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

                    responsive: true,
                    maintainAspectRatio: false
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
