@extends('layout.header')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>公告栏</b>
                        <small>
                            <i class="icon-double-angle-right"></i>个讨论场合
                        </small>
                    </h4>
                </div>
                <div class="col-md-6">
                    <h5 style="float: right"><a href="javascript:history.back();"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" >
                    <div class="widget-header" style="min-height: 70px;color:blue;">
                        <div class="col-md-5">
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <strong>讨论场合:</strong>
                                </div>
                                <div class="col-sm-6">
                                    <strong> {{ $tema['tema'] }}</strong>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <strong>做成日期:</strong>
                                </div>
                                <div class="col-sm-6">
                                    <strong> {{ $tema['create_at'] }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7"  style="margin-top: 10px">
                            <div><strong>说明</strong></div>
                            <div style="padding-top:5px">{{ $tema['descript'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="space-4"></div>
                <div class="row">
                    <div style="float: right;margin-bottom: 10px">
                        <button class="btn btn-primary btn-sm" onclick="showCreateNewsPage({{$tema['id']}})" style="width: 80px"><i class="icon-plus-sign-alt"></i>添加</button>
                    </div>
                </div>
                <div class="row">
                    <div id="news-table">
                        <table class="table table-hover table-bordered" id="news-data-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center">No</th>
                                <th style="width:40%;text-align: center">제<span style="padding-left: 70px"></span>목</th>
                                <th class="center">附件</th>
                                <th class="center">投稿人</th>
                                <th class="center">回复</th>
                                <th class="center">阅览数</th>
                                <th class="center">点赞数</th>
                                <th class="center">最近回复日期</th>
                                <th class="center">投稿日期</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($list) > 0)
                                <?php
                                $index = ($list->currentPage() - 1) * $list->perPage() + 1; ?>
                                @foreach ($list as $news)
                                    <tr>
                                        <td class="center">{{$index}}</td>
                                        <td>
                                            <div class="table_div">
                                                <a href="showNewsDetail/{{$news['id']}}.htm">
                                                    <i class="icon-bell"></i>
                                                    {{ $news['title'] }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="center">
                                            @if (!empty($news['filePath']))
                                                <a href="/fileDownload?type=news&path={{$news['filePath']}}&filename={{$news['fileName']}}"  class="hide-option" @if(!empty($news['fileName'])) title="{{$news['fileName']}} @endif">
                                                    <i class="icon-file"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="center">{{ $news['newsUser']['name'] }}</td>
                                        <td class="center">{{ $news['response'] }}</td>
                                        <td class="center">{{ $news['view'] }}</td>
                                        <td class="center">{{ $news['recommend'] }}</td>
                                        <td class="center">{{ convert_date($news['create_at']) }}</td>
                                        <td class="center">{{ convert_date($news['update_at']) }}</td>
                                    </tr>
                                    <?php $index++; ?>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function showCreateNewsPage(temaId) {
            window.location.href = 'createNewsPage?tema=' + temaId;
        }

    </script>
@stop