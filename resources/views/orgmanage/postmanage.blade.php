@extends('layout.header')

@section('styles')
    <link href="{{ cAsset('assets/css/no-padding.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transOrgManage("title.OfficePostManage")}}</b>
                    </h4>
                </div>
            </div>
            <div id="modalback" class="in"></div>
            <div class="col-md-8 col-md-offset-2">
                <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" data-target="#modal-step-contents">
                                {{transOrgManage("captions.notice")}}
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-box">
                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <table id="sample-table-1" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 70px">{{transOrgManage("captions.priority")}}</th>
                                    <th class="center">{{transOrgManage("captions.officePositionName")}}</th>
                                    <th class="hidden-480"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($posts as $post)
                                    <tr id="{{'row'.$post->id}}">
                                        <td>
                                            <div>
                                                <select class="form-control" value="{{$post->orderNum}}" oldvalue="{{$post->orderNum}}">
                                                    @for($i=1;$i<$maxLevel+1;$i++)
                                                        <option value="{{$i}}" @if($i==$post->orderNum) selected @endif>{!! $i !!}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" placeholder="请填写职位名称…" style="width:100%;"
                                                   value="{{$post->title}}" oldvalue="{{$post->title}}" class="form-control">
                                        </td>
                                        <td style="text-align: center">
                                            <a href="javascript:onUpdate({{$post->id}})" class="primary">
                                                <i class="icon-save bigger-130"></i>
                                            </a>
                                            <a href="javascript:onDelete({{$post->id}})" class="red">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <div>
                                            <select class="form-control" id="postorderNum" required="required" value="{{$post->orderNum}}" oldvalue="{{$post->orderNum}}">
                                                @for($i=1;$i<$maxLevel+1;$i++)
                                                    <option value="{{$i}}" @if($i==$maxLevel) selected @endif>{!! $i !!}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" id="postname" placeholder="请填写职位名称…" style="width:100%;">
                                    </td>
                                    <td style="text-align: center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                            <a href="javascript:onAddPost()" class="plus">
                                                <i class="icon-plus bigger-130"></i>
                                            </a>
                                        </div>

                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function onUpdate(id) {
            var tr = document.getElementById('row' + id);
            var childs = tr.children;
            var orderNum = childs[0].children[0].children[0].value;
            var oldorderNum = childs[0].children[0].children[0].getAttribute("oldvalue");
            var oldtitle = childs[1].children[0].getAttribute("oldvalue");
            var title = childs[1].children[0].value;
            console.log(orderNum);
            if (oldtitle == title && oldorderNum == orderNum) {
                alert("职位名未变更。");
                return;
            }
            console.log(orderNum);
            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在修改职位信息。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();
            $.getJSON('postUpdate', {orderNum: orderNum, title: title, id: id}, function (data, status, xhr) {
                if (status == 'success') {//AJAX요청이 성공한 경우의 처리
                    if (data['result'] == 'success') {
                        location.reload();
                    }
                }
                else if (status == 'timeout') {
                    //봉사기에 대한 요청이 시간초과인 경우의 처리
                }
            })
        }
        function onDelete(id) {
            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在删除职位信息。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();
            $.getJSON('postDel', {id: id}, function (data, status, xhr) {
                if (status == 'success') {//AJAX요청이 성공한 경우의 처리
                    if (data['result'] == 'success') {
                        location.reload();
                    }
                }
                else if (status == 'timeout') {
                    //봉사기에 대한 요청이 시간초과인 경우의 처리
                }
            })

        }
        function onAddPost() {
            var orderNum = $("#postorderNum").val();
            var title = $("#postname").val();
            console.log(orderNum);
            console.log(title);
            if (title == "" || orderNum == "") {
                alert("请输入准确的职位名和优先顺序。")
                return;
            }
            $.getJSON('postAdd', {orderNum: orderNum, title: title}, function (data, status, xhr) {
                if (status == 'success') {//AJAX요청이 성공한 경우의 처리
                    if (data['result'] == 'success') {
                        location.reload();
                    }
                }
                else if (status == 'timeout') {
                    //시간초과인 경우의 처리
                }
            })
        }
    </script>

@endsection