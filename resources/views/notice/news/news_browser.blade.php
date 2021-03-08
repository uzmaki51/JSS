@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>公告栏</b>
                        <small>
                            <i class="icon-double-angle-right"></i>讨论问题
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-6">
                        <label class="col-md-2" style="padding-top:7px">讨论场合 :</label>
                        <input type="text" class="form-control" id="search_tema" style="float:left;width:60%" value="@if(isset($keyword)){{$keyword}}@endif">
                        <button class="btn btn-sm btn-info no-radius col-md-1" type="button" style="margin-left: 20px; float: left; width: 80px;" onclick="filterByTemaKeyword()">
                            <i class="icon-search"></i>搜索
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div class="table-responsive" id="tema_list_div">
                        <table class="table table-striped table-bordered table-hover" id="news-tema-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center">讨论场合</th>
                                <th class="center">消息</th>
                                <th class="center">回复数</th>
                                <th class="center" style="width:150px;">最后消息信息</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($list) > 0)
                                @foreach ($list as $temaInfo)
                                    <tr>
                                        <td style="text-align: left">
                                            <div class="table_div">
                                                <a href="../notice/showNewsListForTema?temaId={{$temaInfo['id']}}">
                                                    <i class="icon-bullhorn red"></i>
                                                    <span style="color: #1f1bbd">
                                                        {{ $temaInfo['tema'] }}
                                                    </span>
                                                    <span style="color: rgba(73, 74, 72, 0.49)">
                                                        ({{ $temaInfo['create'].'  开始了' }})
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="center">
                                            {{ $temaInfo['news'] }}
                                        </td>
                                        <td class="center">
                                            {{ $temaInfo['response'] }}
                                        </td>
                                        <td class="center">
                                            {!! $temaInfo['update'] !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>
        var token = '<?php echo csrf_token() ?>';

        function filterByTemaKeyword() {
            var keyword = $("#search_tema").val();
            if(keyword.length > 0)
                location.href = 'recommendNews?keyword=' + keyword;
            else
                location.href = 'recommendNews';
        }

        function viewNewsListForTema(temaId) {
            window.location.href = 'showNewsListForTema?temaId='+temaId;
        }

        $(function() {
        });


    </script>
@stop
