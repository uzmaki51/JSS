@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <style>
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4><b>{{transOrgManage("title.MemberInfo")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            @if(!isset($userid)){{transOrgManage("captions.add")}}@else {{transOrgManage("captions.change")}} @endif
                        </small>
                    </h4>
                </div>
            </div>
            <div class="row col-md-12" style="margin-bottom: 4px;">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="f-right">
                        <button class="btn btn-report-search btn-sm search-btn" onclick="javascript:goBack()"><< {{transOrgManage("captions.prevPage")}}</button>
                        <button class="btn btn-warning btn-sm excel-btn" onclick="javascript:deleteMember('{{ $userid }}')"><i class="icon-remove"></i>{{transOrgManage("captions.delete")}}</button>
                        <button class="btn btn-success btn-sm excel-btn" onclick="javascript:submit()"><i class="icon-save"></i>@if(!isset($userid)){{transOrgManage("captions.register")}}@else {{transOrgManage("captions.save")}} @endif</button>
                    </div>
                </div>
            </div>
            
            @if(isset($userid)>0)
            <form id="validation-form" action="memberupdate" role="form" method="POST" enctype="multipart/form-data">
            @else
            <form id="validation-form" action="memberadder" role="form" method="POST" enctype="multipart/form-data">
            @endif
            <div style="margin-top:8px;">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="userid" id="userid" value="@if(isset($userid)){{$userid}} @endif">
                <div class="col-md-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label">{{transOrgManage("captions.loginID")}}<span class="require">*</span></td>
                                    <td class="custom-td-text">
                                        <input type="text" class="form-control" name="account" id="account" value="@if(isset($userinfo)){{$userinfo['account']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label">{{transOrgManage("captions.name")}}<span class="require">*</span></td>
                                    <td class="custom-td-text">
                                        <input type="text" class="form-control" name="name" id="name" value="@if(isset($userinfo)){{$userinfo['realname']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" colspan="1">
                                        部门
                                    </td>
                                    <td class="custom-td-text">
                                        <select class="form-control" id="unit" name="unit">
                                            @foreach($units as $unit)
                                                <option value="{{$unit['id']}}" @if ((isset($userinfo))&&($userinfo['unit']==$unit['id'])) selected @endif >{{$unit['title']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" colspan="1">
                                        职位
                                    </td>
                                    <td class="custom-td-text">
                                        <select class="form-control" id="pos" name="pos">
                                            <option value="-1" selected></option>
                                            @foreach($pos as $post)
                                                <option value="{{$post['id']}}" @if ((isset($userinfo))&&($userinfo['pos']==$post['id'])) selected @endif >{{$post['title']}}</option>
                                            @endforeach
                                            <option value="{{ IS_SHAREHOLDER }}" {{ $userinfo['pos'] == IS_SHAREHOLDER ? 'selected' : '' }}>{{ transOrgManage("captions.stockholder") }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" colspan="1">
                                        {{transOrgManage("captions.phoneNumber")}}
                                    </td>
                                    <td class="custom-td-text">
                                        <div class="input-group">
                                            <input type="tel" id="rantel" name="phone" class="form-control" value="@if(isset($userinfo)){{trim($userinfo['phone'])}}@endif">
                                            <span class="input-group-addon"><i class="icon-phone"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" colspan="1">
                                        {{transOrgManage("captions.enterDate")}}
                                    </td>
                                    <td class="custom-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="enterdate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['entryDate']}}@endif">
                                            <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label" colspan="1">
                                        {{transOrgManage("captions.missDate")}}
                                    </td>
                                    <td class="custom-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="releaseDate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['releaseDate']}}@endif">
                                            <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                @if(isset($userinfo))
                                    <tr>
                                        <td class="custom-td-label">{{transOrgManage("captions.resetPass")}}<span class="require">*</span></td>
                                        <td class="custom-td-text" style="">
                                            <div class="input-group">
                                                <input type="checkbox" class="form-control" style="width: fit-content; margin-right: 10px; margin-left: 10px;margin-bottom:5px;" name="password_reset" id="password_reset">
                                                <span>* 使用密码初始化功能，可将该职员的密码改为 {{ DEFAULT_PASS }}。</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <script>
        var menuId = 10;
        function submit() {
            if ($('#account').val() == '') {
                $('#account').focus();
                return;
            }
            if ($('#name').val() == '') {
                $('#name').focus();
                return;
            }
            $('#validation-form').submit();
        }
        function deleteMember(userid) {
            bootbox.confirm("Are you sure you want to delete?", function (result) {
                if (result) {
                    $.ajax({
                        url: BASE_URL + 'org/memberInfo/delete',
                        type: 'post',
                        data: {
                            userid: userid,
                        },
                        success: function(result, status, xhr) {
                            console.log(result);
                            if(result == 1) {
                                location.href = BASE_URL + "org/userInfoListView?menuId=" + menuId;
                            } else {

                            }
                        }
                    })
                }
            });
        }
        function goBack() {
            location.href="org/userInfoListView";
        }
    </script>

    <script type="text/javascript">
        var token = '{!! csrf_token() !!}';
        $(function() {
            @if(isset($state))
            var state = '{!! $state !!}';
            if(state == 'success') {
                $.gritter.add({
                    title: '成功',
                    text: '员工信息已正确保存。',
                    class_name: 'gritter-success'
                });
            } else {
                $.gritter.add({
                    title: '错误',
                    text: state,
                    class_name: 'gritter-error'
                });
            }
            @endif
        });
    </script>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>
@stop
