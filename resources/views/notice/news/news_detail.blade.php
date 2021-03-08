@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>公告栏</b>
                        <small>
                            <i class="icon-double-angle-right"></i>各讨论场所
                            <i class="icon-double-angle-right"></i>仔细
                        </small>
                    </h4>
                </div>
                <div class="col-md-6">
                    <h5 style="float: right"><a href="javascript:history.back()"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-sm-10 col-sm-offset-1">
                <div class="well">
                    <h3 class="header smaller lighter center red">{{$news['title']}}</h3>
                    <div>
                        <div class="col-sm-4">
                            <strong>投稿日子 : </strong>{{$news['create_date']}}
                        </div>
                        <div class="col-sm-4 center">
                            <strong>阅览数 : </strong>{{$news['view']}}
                        </div>
                        <div class="col-sm-4" style="text-align: right">
                            <strong>投稿人 : </strong>{{$news['creator']}}
                        </div>
                    </div>
                    <div class="space-30"></div>
                    <div>{!!$news['content']!!}</div>
                    @if (!empty($news['filePath']))
                        <div class="space-20"></div>
                        <div style="text-align: center;font-size: 16px;">
                            <a href="/fileDownload?type=news&path={{$news['filePath']}}">
                                <i class="icon-file"></i> {{$news['fileName']}}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="space-10"></div>
                <div class="center">
                    @if ($news['isRecommend'] == 0)
                        <button id="btn-recommend" class="btn btn-app btn-warning btn-xs" onclick="recommendNews({{$news['id']}})">
                            <i class="icon-hand-up bigger-180"></i>
                            <span id="recommend-count">({{$news['recommend']}})</span>
                        </button>
                        <span id="text-recommend" style="color: #aaa">点赞</span>
                    @else
                        <button id="btn-recommend" class="btn btn-app btn-warning btn-xs disabled">
                            <i class="icon-hand-up bigger-180"></i>
                            ({{$news['recommend']}})
                        </button>
                        <span id="text-recommend" style="color: #aaa">已点赞了</span>
                    @endif
                </div>
                <div class="widget-box transparent">
                    <div class="widget-header widget-header-small">
                        <h4 class="blue smaller"><i class="icon-rss orange"></i>回复</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main padding-10" id="response-list">
                            @foreach($list as $response)
                                <div class="profile-activity clearfix">
                                    <div>
                                        <Strong style="color: #FB6941">{{$response['user']}} : </Strong>
                                        {{ $response['content'] }}
                                        <div class="time">
                                            <i class="icon-time bigger-110" style="padding-right: 10px"></i>{{ $response['datetime'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="hr hr2 hr-double"></div>
                        <form>
                            <div class="form-actions" style="border:1px solid #e5e5e5">
                                <div class="input-group">
                                    <input placeholder="기사에 대한 응답문을 입력하시오." type="text" class="form-control" id="message">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-info no-radius" type="button" onclick="responseForNews({{$news['id']}})">
                                            <i class="icon-share-alt"></i>
                                            回复
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = '<?php echo csrf_token() ?>';
        var recommend = '<?php echo $news['recommend'] ?>';
        var page = 0;

        function recommendNews(newsId) {
            $.post("newsRecommend", {_token:token, newsId:newsId}, function(data) {
                var code = parseInt(data);
                if(code > 0 ) {
                    $("#btn-recommend").addClass('disabled');
                    $("#text-recommend").html('已点赞了!');
                    var count = parseInt(recommend) + 1;
                    $("#recommend-count").html('('+ count + ')');
                } else {
                    $("#btn-recommend").addClass('disabled');
                    $("#text-recommend").html('已点赞了!');
                }
            });
        }

        function responseForNews(newsId) {
            var content = $('#message').val();
            if(content.length < 1) {
                alert("请填写回复!");
                return;
            }
            $.post("newsResponse", {_token:token, newsId:newsId, message:content}, function(data) {
                if(data) {
                    $('#message').val('');
                    $('#response-list').html(data);
                    page = 0;
                }
            });


        }

    </script>
@stop