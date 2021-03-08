@extends('layout.sidebar')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>公告栏</b>
                        <small>
                            <i class="icon-double-angle-right"></i>讨论问题管理
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="col-md-2" style="padding-top:7px">讨论场合 :</label>
                        <input type="text" class="form-control" id="search_tema" style="float:left;width:60%" value="@if(isset($keyword)){{$keyword}}@endif">
                        <button class="btn btn-sm btn-info no-radius col-md-1" type="button" style="margin-left: 20px; float: left; width: 80px;" onclick="filterByTemaKeyword()">
                            <i class="icon-search"></i>搜索
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <div style="text-align: right">
                            <a href="#" id="btn-add-tema" class="btn btn-sm btn-info no-radius" style="width: 80px"><i class="icon-plus-sign-alt"></i>追加</a>
                            <div id="dialog-add-tema" class="hide">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right"><span class="require">*</span> 讨论场合名称:</label>
                                        <div class="col-md-8"><input type="text" id="tema-name" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right">说明:</label>
                                        <div class="col-md-8"><textarea id="tema-descript" class="form-control"></textarea></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-4"></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="news-tema-table">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:60%;">讨论场合</th>
                                <th class="center">消息</th>
                                <th class="center">回复数</th>
                                <th class="center" style="width:150px;">最后消息信息</th>
                                <th class="center" style="width:40px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($list) > 0)
                                @foreach ($list as $temaInfo)
                                    <tr>
                                        <td style="text-align: left;" data-id="{{$temaInfo['id']}}">
                                            <a href="javascript:void(0);" class="edit-action" style="padding-left:10px">
                                                <i class="icon-bullhorn red"></i>
                                                <span style="color: #1f1bbd">
                                                    {{ $temaInfo['tema'] }}
                                                </span>
                                                <span style="color: rgba(73, 74, 72, 0.49)">
                                                    ({{ $temaInfo['create'].'  开始了' }})
                                                </span>
                                            </a>
                                        </td>
                                        <td class="center">{{ $temaInfo['news'] }}</td>
                                        <td class="center">{{ $temaInfo['response'] }}</td>
                                        <td class="center">{!! $temaInfo['update'] !!}</td>
                                        <td style="width:70px" class="center action-buttons">
                                            <a href="javascript:void(0)" class="red del-action"><i class="icon-trash bigger-130"></i></a>
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
        var selTema = 0;
        var token = '<?php echo csrf_token() ?>';

        $(function () {
            $('.edit-action').on('click', function () {
                var obj = $(this).closest('tr').children();
                var temaId = obj.eq(0).data('id');
                $.get("temaInfo", {'temaId': temaId}, function (data) {
                    if (data) {
                        $("#tema-name").val(data['tema']);
                        $("#tema-descript").val(data['descript']);
                        showTemaEditDiaolg(data['id']);
                    }
                });
            });

            $('.del-action').on('click', function () {
                var obj = $(this).closest('tr').children();
                var temaId = obj.eq(0).data('id');
                bootbox.confirm("真要删掉讨论场合吗?\n 삭제되는 경우 토론마당안의 모든 기사들도 없어집니다.", function(result) {
                    if(result) {
                        $.post('deleteNewsTema', {'_token': token, 'temaId': temaId}, function(data){
                            var returnCode = parseInt(data);
                            if (returnCode > 0) {
                                window.location.reload();
                            } else if(returnCode == -1) {
                                $.gritter.add({
                                    title: '错误',
                                    text: '数据库错误!',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                    }
                });
            });

            $( "#btn-add-tema" ).on('click', function(e) {

                e.preventDefault();

                var dialog = $( "#dialog-add-tema" ).removeClass('hide').dialog({
                    modal: true,
                    title: "追加讨论场合",
                    title_html: true,
                    buttons: [
                        {
                            text: "取消",
                            "class" : "btn btn-xs",
                            click: function() {
                                $("#tema-name").val("");
                                $("#tema-descript").val("");
                                $( this ).dialog( "close" );
                            }
                        },
                        {
                            text: "保存",
                            "class" : "btn btn-primary btn-xs",
                            click: function() {
                                var temaName = $("#tema-name").val();
                                var temaDescript = $("#tema-descript").val();
                                if(temaName.length < 1) {
                                    $.gritter.add({
                                        title: '错误',
                                        text: '请输入讨论场合!',
                                        class_name: 'gritter-error'
                                    });
                                    return;
                                }
                                if(temaDescript.length < 1)
                                    temaDescript = "";

                                $.post("saveNewTema",  {'_token':token, 'name':temaName, 'descript':temaDescript}, function(data) {
                                    var returnCode = parseInt(data);
                                    if (returnCode > 0) {
                                        window.location.reload();
                                    } else if(returnCode == -1){
                                        $.gritter.add({
                                            title: '错误',
                                            text: '讨论场合名称重复了。',
                                            class_name: 'gritter-error'
                                        });
                                    }
                                });
                            }
                        }
                    ]
                });
            });

        });

        function filterByTemaKeyword() {
            var keyword = $("#search_tema").val();
            if(keyword.length > 0)
                location.href = 'newsTemaPage?keyword=' + keyword;
            else
                location.href = 'newsTemaPage';
        }


        function showTemaEditDiaolg(temaId) {
            var dialog = $( "#dialog-add-tema" ).removeClass('hide').dialog({
                modal: true,
                title: "修改讨论场合",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class" : "btn btn-xs",
                        click: function() {
                            $("#tema-name").val("");
                            $("#tema-descript").val("");
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "修改",
                        "class" : "btn btn-primary btn-xs",
                        click: function() {
                            var temaName = $("#tema-name").val();
                            var temaDescript = $("#tema-descript").val();
                            if(temaName.length < 1) {
                                alert('请输入讨论场合的名称!');
                                return;
                            }
                            if(temaDescript.length < 1)
                                temaDescript = "";

                            $.post("saveNewTema",  {'_token':token, 'name':temaName, 'descript':temaDescript, 'temaId':temaId}, function(data) {
                                var returnCode = parseInt(data);
                                if (returnCode > 0) {
                                    window.location.reload();
                                } else if(returnCode == -1) {
                                    $.gritter.add({
                                        title: '错误',
                                        text: '讨论场合的名称重复。',
                                        class_name: 'gritter-error'
                                    });
                                }
                            });
                        }
                    }
                ]
            });
        }


    </script>
@stop
