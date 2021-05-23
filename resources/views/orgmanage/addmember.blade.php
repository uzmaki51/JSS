@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
    <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/-->
@endsection

@section('content')
    <div class="main-content">
        <style>
            .add-td-label {
                font-size:14px!important;
                background-color:#c9dfff !important;
                text-align: left!important;
                padding:10px!important;
            }

            .add-td-text {
                background-color: #FFFFFF;
                font-weight: normal;
                vertical-align: middle;
            }

            .add-td-input {
                font-size:14px!important;
                margin-left:10px;
            }

            .add-td-select {
                font-size:14px!important;
                margin-left:5px;
                margin-right:10px;
            }
            
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
                        <button class="btn btn-danger btn-sm excel-btn" onclick="javascript:deleteMember('{{ $userid }}')"><i class="icon-remove"></i>{{transOrgManage("captions.delete")}}</button>
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
                            <table id="sample-table-1" class="table-bordered" style="margin-left:auto;margin-right:auto;">
                                <tbody>
                                <tr>
                                    <td class="add-td-label">{{transOrgManage("captions.name")}}<span class="require">*</span>:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="name" id="name" value="@if(isset($userinfo)){{$userinfo['realname']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" >{{transOrgManage("captions.loginID")}}<span class="require">*</span>:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="account" id="account" value="@if(isset($userinfo)){{$userinfo['account']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.officePosition")}}:</td>
                                    <!--td class="add-td-text">
                                        <select class="form-control add-td-select" id="pos" name="pos">
                                            <option value="-1" selected></option>
                                            @foreach($pos as $post)
                                                <option value="{{$post['id']}}" @if ((isset($userinfo))&&($userinfo['pos']==$post['id'])) selected @endif >{{$post['title']}}</option>
                                            @endforeach
                                            <option value="{{ IS_SHAREHOLDER }}" {{ $userinfo['pos'] == IS_SHAREHOLDER ? 'selected' : '' }}>{{ transOrgManage("captions.stockholder") }}</option>
                                        </select>
                                    </td-->
                                    <?php $sel = "";
                                    $sel_id = 0;
                                    ?>
                                    @foreach ($pos as $type)
                                        @if ($type->id == $userinfo['pos'])
                                        <?php $sel = $type->title; 
                                        $sel_id = $type->id;
                                        ?>
                                        @endif
                                    @endforeach
                                    <td class="custom-td-report-text" style="width: 40%">
                                        <div class="dynamic-select-wrapper">
                                            <div class="dynamic-select" style="color:#12539b">
                                                <input type="hidden"  name="pos" value="{{$sel_id}}"/>
                                                <div class="dynamic-select__trigger"><input type="text" id="position" class="form-control dynamic-select-span" style="background:white!important;height:30px;margin-left:10px;" value="{{$sel}}" readonly>
                                                    <div class="arrow"></div>
                                                </div>
                                                <div class="dynamic-options">
                                                    <div class="dynamic-options-scroll">
                                                        @if ($userinfo['pos'] == "")
                                                        <span class="dynamic-option selected" data-value="" data-text="">&nbsp;</span>
                                                        @else
                                                        <span class="dynamic-option" data-value="" data-text="">&nbsp;</span>
                                                        @endif
                                                        @foreach ($pos as $item)
                                                            @if ($item->id == $userinfo['pos'])
                                                            <span class="dynamic-option selected" data-value="{{$item->id}}" data-text="{{$item->title}}">{{$item->title}}</span>
                                                            @else
                                                            <span class="dynamic-option" data-value="{{$item->id}}" data-text="{{$item->title}}">{{$item->title}}</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div>
                                                        <span class="edit-list-btn" id="edit-list-btn" onclick="javascript:openPosList('pos')">
                                                            <img src="{{ cAsset('assets/img/list-edit.png') }}" alt="Edit List Items">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.phoneNumber")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input type="tel" id="rantel" name="phone" class="form-control add-td-input" value="@if(isset($userinfo)){{trim($userinfo['phone'])}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.enterDate")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker add-td-input" name="enterdate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['entryDate']}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.missDate")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker add-td-input" name="releaseDate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['releaseDate']}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.remark")}}:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="remark" id="remark" value="@if(isset($userinfo)){{$userinfo['remark']}}@endif" required>
                                    </td>
                                </tr>
                                @if(isset($userinfo))
                                    <tr>
                                        <td class="add-td-label" >{{transOrgManage("captions.resetPass")}}:</td>
                                        <td class="add-td-text" style="">
                                            <div class="input-group">
                                                <input type="checkbox" class="form-control add-td-input" style="width: fit-content; margin-right: 10px; margin-left: 10px;margin-bottom:5px;" name="password_reset" id="password_reset">
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
            <div id="modal-pos-list" class="modal modal-draggable" aria-hidden="true" style="display: none; margin-top: 15%;">
                <div class="dynamic-modal-dialog">
                    <div class="dynamic-modal-content" style="border: 0;">
                        <div class="dynamic-modal-header" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                <h4 style="padding-top:10px;font-style:italic;">Position List</h4>
                            </div>
                        </div>
                        <div id="modal-pos-content" class="dynamic-modal-body step-content">
                            <div class="row" style="">
                                <div class="head-fix-div col-md-12" style="height:300px;">
                                    <table class="table-bordered pos-table">
                                        <thead>
                                        <tr style="background-color: #c9dfff;height:18px;">
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:10%">OrderNo</th>
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;width:80%">Name</th>
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #c9dfff;"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="pos-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="btn-group f-right mt-20 d-flex">
                                        <button type="button" class="btn btn-success small-btn ml-0" onclick="javascript:dynamicPosSubmit('pos')">
                                            <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                        </button>
                                        <div class="between-1"></div>
                                        <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>
    <script>
        var menuId = 10;
        function submit() {
            if ($('#name').val() == '') {
                $('#name').focus();
                return;
            }
            
            if ($('#account').val() == '') {
                $('#account').focus();
                return;
            }

            if ($('#position').val() == 0) {
                alert("Please select position!");
                return;
            }
            
            $('#validation-form').submit();
        }

        function alertAudio() {
            document.getElementById('warning-audio').play();
        }

        function deleteMember(userid) {
            alertAudio();
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

        $('body').on('click', function(e) {
            var current = null;
            if ($(event.target).attr('class') == 'form-control dynamic-select-span' || $(event.target).attr('class') == 'dynamic-select__trigger') {
                current = $(event.target).closest('.dynamic-select-wrapper');
            }
            for (const selector of document.querySelectorAll(".dynamic-select-wrapper")) {
                if (current == null || selector != current[0])
                    selector.firstElementChild.classList.remove('open');
            }
        });
    </script>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>
@stop
