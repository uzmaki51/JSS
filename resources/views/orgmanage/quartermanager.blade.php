@extends('layout.header')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transOrgManage("title.OfficeBoss")}}</b>
                    </h4>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div id="modal-wizard" class="modal" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" data-target="#modal-step-contents">
                            {{ transOrgManage("captions.notice") }}
                            </div>
                            <div id="modal-body-content" class="modal-body step-content">

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-bordered">
                            <thead>
                            <tr class="black br-hblue">
                                <th>{{transOrgManage("captions.office")}}</th>
                                <th>{{transOrgManage("captions.boss")}}</th>
                                <th style="width:180px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--添加--}}
                            @foreach($units as $unit)
                                <tr id="{{'row'.$unit->id}}">
                                    <td>
                                        <div>
                                            <div>
                                                <i @if($unit->childcount > 0) class="icon-folder-close blue" style="float: left; padding-left: {!! strlen($unit->orderkey)*5 !!}px; margin-right: 10px"
                                                   @else class="icon-hdd blue" style="padding-left: {!! strlen($unit->orderkey)*5 !!}px; float: left; margin-right: 10px" @endif ></i>
                                                <div class="tree-folder-name">{{$unit->title}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-manager-container">
                                            <select class="chosen-select">
                                                <option value="">{{$unit->manager}}</option>
                                                @foreach($users as $user)
                                                    @if ($unit->manager != $user->realname)
                                                    <option value="{{$user->id}}">{{$user->realname }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                            <a href="javascript:onUpdate({{$unit->id}})" class="info">
                                                <i class="icon-save bigger-130"></i>
                                            </a>
                                            <a href="javascript:onDelete({{$unit->id}})" class="red">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /span -->
            </div>
        </div>
    </div>

    <script type="text/javascript">
        {{--var $assets = "assets";//this will be used in fuelux.tree-sampledata.js--}}
        $(document).ready(function () {
            $(".chosen-select").chosen();

        });
        function onUpdate(id) {
            var tr = document.getElementById('row' + id);
            var childs = tr.children;
            var managerName = childs[1].children[0].children[1].children[0].children[0].innerHTML;
            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '正在修改部门负责人。';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();

            $.getJSON('managerUpdate', {manager: managerName, id: id}, function (data, status, xhr) {
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
            var tr = document.getElementById('row' + id);
            var childs = tr.children;
            var managerName = childs[1].children[0].children[1].children[0].children[0].innerHTML;
            if (managerName == "选择项目") {
                alert("部门负责人未分配。");
                return;
            }
            console.log(managerName);
            $("#modal-wizard").attr('aria-hidden', 'false');
            $("#modal-wizard").addClass('in');
            $("body").addClass('modal-open');
            $("#modalback").addClass('modal-backdrop ');
            var htm = '<i class="' + 'icon-spinner icon-spin orange bigger-500"' + '></i>' + '부서책임자를 삭제하고있습니다.';
            $("#modal-body-content").html(htm);
            $("#modal-wizard").show();
            $.getJSON('managerDel', {id: id}, function (data, status, xhr) {
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

    </script>

@endsection