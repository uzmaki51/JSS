@extends('layout.sidebar')

@section('content')
    <script>
        var menuId = 13;
        function deleteMember(userid) {
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
    </script>
    <div class="main-content">

        <style>
            .col-md-2{width: 18.6666%;}
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
                @if(!isset($userid))
                    <div class="col-md-6"></div>
                @else
                    <div class="col-md-6"></div>
                @endif
                <div class="col-md-3" style="text-align: right">
                    <h5 style="float: right"><a href="userInfoListView"><strong>{{transOrgManage("captions.prevPage")}}</strong></a></h5>
                    @if(isset($userid))
                        <h5 style="float: right; margin-right: 20px;"><a href="javascript:deleteMember('{{ $userid }}')"><strong class="text-danger">{{transOrgManage("captions.delete")}}</strong></a></h5>
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="col-md-12" style="border: 1px solid #cccccc">
                                <form role="form" method="POST"
                                      action="@if(isset($userid)>0) {{url('/org/memberupdate')}} @else{{url('/org/memberadder')}}@endif"
                                      enctype="multipart/form-data" id="validation-form">
                                    <div class="col-md-12" style="margin-bottom: 5px; margin-top: 10px">
                                        <div style="float: right">
                                            <button type="submit" style="font-weight: bold; width :80px;"
                                                    class="btn btn-inverse btn-sm">
                                                <i class="icon-save"></i>@if(!isset($userid)){{transOrgManage("captions.register")}}@else {{transOrgManage("captions.save")}} @endif
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="userid" id="userid"
                                           value="@if(isset($userid)){{$userid}} @endif">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="sample-table-1" class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td class="custom-td-label">{{transOrgManage("captions.loginID")}}<span class="require">*</span></td>
                                                        <td class="custom-td-text">
                                                            <input type="text" class="form-control" name="account" id="account"
                                                                   value="@if(isset($userinfo)){{$userinfo['account']}}@endif">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="custom-td-label">{{transOrgManage("captions.name")}} <span class="require">*</span></td>
                                                        <td class="custom-td-text">
                                                            <input type="text" class="form-control" name="name"
                                                                   id="name"
                                                                   value="@if(isset($userinfo)){{$userinfo['realname']}}@endif">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="custom-td-label" colspan="1">
                                                            部门
                                                        </td>
                                                        <td class="custom-td-text">
                                                            <select class="form-control chosen-select" id="unit" name="unit">
                                                                @foreach($units as $unit)
                                                                    <option value="{{$unit['id']}}"
                                                                            @if ((isset($userinfo))&&($userinfo['unit']==$unit['id'])) selected @endif >
                                                                        {{$unit['title']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="custom-td-label" colspan="1">
                                                            职位
                                                        </td>
                                                        <td class="custom-td-text">
                                                            <select class="form-control chosen-select" id="pos" name="pos">
                                                                <option value="-1" selected></option>
                                                                @foreach($pos as $post)
                                                                    <option value="{{$post['id']}}"
                                                                            @if ((isset($userinfo))&&($userinfo['pos']==$post['id'])) selected @endif >
                                                                        {{$post['title']}}
                                                                    </option>
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
                                                                <input type="tel" id="rantel" name="phone"
                                                                       class="form-control"
                                                                       value="@if(isset($userinfo)){{trim($userinfo['phone'])}}@endif">
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
                                                                <input class="form-control date-picker" name="enterdate"
                                                                       type="text" data-date-format="yyyy-mm-dd"
                                                                       value="@if(isset($userinfo)){{$userinfo['entryDate']}}@endif">
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
                                                                <input class="form-control date-picker" name="releaseDate"
                                                                       type="text" data-date-format="yyyy-mm-dd"
                                                                       value="@if(isset($userinfo)){{$userinfo['releaseDate']}}@endif">
                                                                <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @if(isset($userinfo))
                                                        <tr>
                                                            <td class="custom-td-label">{{transOrgManage("captions.resetPass")}}<span class="require">*</span></td>
                                                            <td class="custom-td-text" style="display: flex; width: 100%; align-items: center;">
                                                                <input type="checkbox" class="form-control" style="width: fit-content; margin-right: 10px;" name="password_reset"
                                                                       id="password_reset">
                                                                <span>* 使用密码初始化功能，可将该职员的密码改为 {{ DEFAULT_PASS }}。</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var cindex = 0;
        var findex = 0;

        var curSeltrs = Array();
        var token = '{!! csrf_token() !!}';
        var relationHtml = '';

        $(function(){
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

            bindSchoolButtonAction();
            bindFamilyButtonAction();
            bindRelationButtonAction();

            jQuery.validator.addMethod("telephone", function (value, element) {
                var flag = this.optional(element) || /^\d{3}-\d{2}\-\d{4}( x\d{1,6})?$/.test(value);
                if (flag) {
                    return flag;
                } else {
                    return this.optional(element) || /^\d{2}-\d{3}\-\d{4}( x\d{1,6})?$/.test(value);
                }
            }, "");

            $("#validation-form").validate({
                rules: {
                    account : 'required',
                    id: "required",
                    name: "required",
                    email: {
                        email: true
                    },
                    hometel: {
                        telephone: 'required'
                    },
                    orgtel: {
                        telephone: 'required'
                    },
                    rantel: {
                        phone: 'required'
                    },
                },
                messages: {
                    account: "请输入ID。",
                    name: "请输入名称。",
                    email: "比如: bss@www.com",
                }
            });

            $('#photopath').ace_file_input({
                style: 'well',
                btn_choose: '请选择照片。',
                btn_change: null,
                no_icon: 'icon-cloud-upload',
                droppable: true,
                thumbnail: 'small',//large | fit
                preview_error: function (filename, error_code) {
                }
            }).on('change', function () {
            });

            $('#myTab a:first').tab('show');

            $("#relation-table").dataTable( {
                "aoColumns": [null, { "bSortable": false }, { "bSortable": false }],
                "iDisplayLength":10
            });

            $("input[name=isAdmin]").on("change", function(){
                if($("input[name=isAdmin]").val() == 1){
                    $("input[name=isAdmin]").val(0);
                }else{
                    $("input[name=isAdmin]").val(1);
                }
            });

        });

        function check(id) {
            var allcheck = document.getElementById('group' + id);
            var checks = document.getElementsByClassName('row' + id);

            for (var i = 0; i < checks.length; i++) {
                if (allcheck.checked == true) {
                    allcheck.nextElementSibling.checked = true;
                    checks[i].checked = true;
                } else {
                    allcheck.nextElementSibling.checked = false;
                    checks[i].checked = false;
                }
                checks[i].nextElementSibling.checked = false;
            }
        }

        function checkchild(id, checkObj) {
            var allcheck = document.getElementById('group' + id);
            var checks = document.getElementsByClassName('row' + id);
            checkObj.nextElementSibling.checked = checkObj.checked;

            var flag = true;
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked == true) {
                    continue;
                } else {
                    flag = false;
                    break;
                }
            }
            if (flag == true) {
                allcheck.checked = true;
                allcheck.nextElementSibling.checked = true;
                for (var i = 0; i < checks.length; i++)
                    checks[i].nextElementSibling.checked = false;
            } else {
                allcheck.checked = false;
                allcheck.nextElementSibling.checked = false;
                for (var i = 0; i < checks.length; i++)
                    checks[i].nextElementSibling.checked = checks[i].checked;
            }
        }

        function msg(){
            $.gritter.add({
                title: '错误',
                text: '请先保存用户信息。',
                class_name: 'gritter-error'
            });
        }


        function bindSchoolButtonAction() {
            $('.school-edit').click(schoolEdit);
            $('.school-cancel').click(schoolCancel);
            $('.school-add').click(schoolAdd);
            $('.school-save').click(schoolSave);
            $('.school-delete').click(schoolDelete);
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        function unbindSchoolButtonAction() {
            $('.school-edit').unbind('click', schoolEdit);
            $('.school-cancel').unbind('click', schoolCancel);
            $('.school-add').unbind('click', schoolAdd);
            $('.school-save').unbind('click', schoolSave);
            $('.school-delete').unbind('click', schoolDelete);
        }


        function schoolEdit() {
            var obj = $(this).closest('tr').children();
            var editState = obj.eq(1).find('text');
            if(!editState)
                return;

            var id = obj.eq(0).data('id');
            var dateStr = obj.eq(1).html();
            var dateHtml = '<div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"  data-old="' + dateStr + '" value="' + dateStr + '">' +
                '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div>';
            obj.eq(1).html(dateHtml);

            dateStr = obj.eq(2).html();
            dateHtml = '<div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"  data-old="' + dateStr + '" value="' + dateStr + '">' +
                '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div>';
            obj.eq(2).html(dateHtml);

            obj.eq(3).html('<input type="text" class="form-control" data-old="' + obj.eq(3).text() + '" value="' + obj.eq(3).text() +'">');
            obj.eq(4).html('<input type="text" class="form-control" data-old="' + obj.eq(4).text() + '" value="' + obj.eq(4).text() +'">');
            obj.eq(5).html('<input type="text" class="form-control" data-old="' + obj.eq(5).text() + '" value="' + obj.eq(5).text() +'">');
            obj.eq(6).html('<input type="text" class="form-control" data-old="' + obj.eq(6).text() + '" value="' + obj.eq(6).text() +'">');
            obj.eq(7).html('<input type="text" class="form-control" data-old="' + obj.eq(7).text() + '" value="' + obj.eq(7).text() +'">');
            obj.eq(8).find('.row_edit').hide();
            obj.eq(8).find('.row_apply').show();
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        function schoolCancel() {
            var obj = $(this).closest('tr').children();

            obj.eq(1).html(obj.eq(1).find('input').data('old'));
            obj.eq(2).html(obj.eq(2).find('input').data('old'));
            obj.eq(3).html(obj.eq(3).find('input').data('old'));
            obj.eq(4).html(obj.eq(4).find('input').data('old'));
            obj.eq(5).html(obj.eq(5).find('input').data('old'));
            obj.eq(6).html(obj.eq(6).find('input').data('old'));
            obj.eq(7).html(obj.eq(7).find('input').data('old'));
            obj.eq(8).find('.row_edit').show();
            obj.eq(8).find('.row_apply').hide();
        }

        function schoolAdd() {
            var obj = $(this).closest('tr').children();
            var startDate = obj.eq(1).find('input').val();
            var endDate = obj.eq(2).find('input').val();
            var schoolName = obj.eq(3).find('input').val();
            if((startDate.length < 1) && (endDate.length < 1) && (schoolName.length < 1)) {
                $.gritter.add({
                    title: '错误',
                    text: '',
                    class_name: 'gritter-error'
                });
                return;
            }

            var userId = $('#userid').val();
            $.post('updateMemberSchoolCarrer',
                {   '_token':token,
                    'userId':userId,
                    'id' : 0,
                    'startDate' : startDate,
                    'endDate' : endDate,
                    'school' : schoolName,
                    'special' : obj.eq(4).find('input').val(),
                    'endMark' : obj.eq(5).find('input').val(),
                    'capacity' : obj.eq(6).find('input').val(),
                    'remark' : obj.eq(7).find('input').val(),
                }, function (data) {
                    var returnCode = parseInt(data);
                    if(returnCode > 0) {
                        obj.eq(0).attr('data-id', returnCode);
                        obj.eq(1).html(startDate);
                        obj.eq(2).html(endDate);
                        obj.eq(3).html(obj.eq(3).find('input').val());
                        obj.eq(4).html(obj.eq(4).find('input').val());
                        obj.eq(5).html(obj.eq(5).find('input').val());
                        obj.eq(6).html(obj.eq(6).find('input').val());
                        obj.eq(7).html(obj.eq(7).find('input').val());
                        var actionHtml = '<div class="row_edit"><a href="javascript:void(0)" class="blue school-edit"><i class="icon-edit bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="red school-delete" style="margin-left:10px"><i class="icon-trash bigger-130"></i></a></div>' +
                            '<div class="row_apply" style="display: none"><a href="javascript:void(0)" class="blue school-save"><i class="icon-save bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="red school-cancel" style="margin-left:10px"><i class="icon-remove bigger-130"></i></a></div>';
                        obj.eq(8).html(actionHtml);

                        var tbody = document.getElementById('school-table');
                        var newtr = document.createElement('tr');
                        var leng = tbody.children.length;
                        if(leng < 1)
                            index = 1;
                        else
                            index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
                        var newHtml = '<td data-id="0">' + index + '</td>' +
                            '<td style="width:150px"><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">' +
                            '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                            '<td style="width:150px"><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">' +
                            '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td class="action-buttons"><a href="javascript:void(0)" class="red school-add"><i class="icon-plus bigger-130"></i></a></td>';
                        newtr.innerHTML = newHtml;
                        tbody.appendChild(newtr);

                        unbindSchoolButtonAction();
                        bindSchoolButtonAction();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: '',
                            class_name: 'gritter-error'
                        });
                    }
                })
        }

        function schoolSave() {
            var obj = $(this).closest('tr').children();
            var startDate = obj.eq(1).find('input').val();
            var endDate = obj.eq(2).find('input').val();
            var schoolName = obj.eq(3).find('input').val();
            if((startDate.length < 1) && (endDate.length < 1) && (schoolName.length < 1)) {
                $.gritter.add({
                    title: '错误',
                    text: '',
                    class_name: 'gritter-error'
                });
                return;
            }

            var userId = $('#userid').val();
            $.post('updateMemberSchoolCarrer',
                {   '_token':token,
                    'userId':userId,
                    'id' : obj.eq(0).data('id'),
                    'startDate' : startDate,
                    'endDate' : endDate,
                    'school' : schoolName,
                    'special' : obj.eq(4).find('input').val(),
                    'endMark' : obj.eq(5).find('input').val(),
                    'capacity' : obj.eq(6).find('input').val(),
                    'remark' : obj.eq(7).find('input').val(),
                }, function (data) {
                    var returnCode = parseInt(data);
                    if(returnCode > 0) {
                        obj.eq(1).html(startDate);
                        obj.eq(2).html(endDate);
                        obj.eq(3).html(obj.eq(3).find('input').val());
                        obj.eq(4).html(obj.eq(4).find('input').val());
                        obj.eq(5).html(obj.eq(5).find('input').val());
                        obj.eq(6).html(obj.eq(6).find('input').val());
                        obj.eq(7).html(obj.eq(7).find('input').val());
                        obj.eq(8).find('.row_edit').show();
                        obj.eq(8).find('.row_apply').hide();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: '',
                            class_name: 'gritter-error'
                        });
                    }
                })
        }

        function schoolDelete() {
            var row = $(this).closest('tr').children();
            var tbody = document.getElementById('school-table');
            var len = tbody.children.length;
            var index = row.eq(0).html();
            var id = row.eq(0).data('id');
            var school_name = row.eq(3).html();

            bootbox.confirm("[" + school_name + "] 真要删除吗?", function (result) {
                if (result) {
                    $.post('deleteSchoolCarrer', {'_token': token, 'id': id}, function (data) {
                        if (data = 'success') {
                            var selRow = -1;
                            var rowIndex = 0;
                            for (var i = 0; i < len; i++) {
                                var tds = tbody.children[i];
                                var selIndex = tds.children[0].innerText;
                                if (selIndex == index) {
                                    selRow = i;
                                    continue;
                                }
                                tbody.children[i].children[0].innerText = rowIndex+1;
                                rowIndex++;
                            }
                            if (selRow > -1)
                                tbody.deleteRow(selRow);
                        }
                    });
                }
            });
        }

        function bindRelationButtonAction() {
            bindRelationEditAction();
            bindRelationCancelAction();
            bindRelationSaveAction();
            bindRelationDeleteAction();
            bindRelationAddAction();
        }

        function unbindRelationButtonAction() {
            $('.relation-edit').off('click');
            $('.relation-cancel').off('click');
            $('.relation-save').off('click');
            $('.relation-delete').off('click');
            $('.relation-add').off('click');
        }

        function bindRelationEditAction() {
            $('.relation-edit').on('click', function () {
                var row = $(this).closest('tr');

                var relationName = row.find('.relation').text();
                row.find('.relation').html('<input type="text" class="form-control" data-org="' + relationName + '" value="' + relationName + '">');
                row.find('.edit-buttons').hide();
                row.find('.save-buttons').show();
            });
        }
        function bindRelationCancelAction() {
            $('.relation-cancel').on('click', function () {
                var row = $(this).closest('tr');

                var relationName = row.find('input').data('org');
                row.find('.relation').html(relationName);
                row.find('.edit-buttons').show();
                row.find('.save-buttons').hide();
            });
        }
        function bindRelationSaveAction() {
            $('.relation-save').on('click', function () {
                var row = $(this).closest('tr');
                var relationName = row.find('input').val();
                if (relationName.length < 1) {
                    $.gritter.add({
                        title: '错误',
                        text: '',
                        class_name: 'gritter-error'
                    });
                    return;
                }
                var relationId = row.find('.relation').data('id');
                $.post('updateRelationItem', {'_token': token, 'relationId': relationId, 'name': relationName}, function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        relationHtml = result.html;

                        row.find('.relation').html(relationName);
                        row.find('.edit-buttons').show();
                        row.find('.save-buttons').hide();

                        var selectTag = $('#family-table').find('.new-relation');
                        selectTag.html(relationHtml);

                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: result.status,
                            class_name: 'gritter-error'
                        });
                    }
                });
            });
        }
        function bindRelationDeleteAction() {
            $('.relation-delete').on('click', function () {
                var row = $(this).closest('tr').children();
                var relationName = row.eq(1).html();
                var relationId = row.eq(1).data('id');
                bootbox.confirm("[" + relationName + "] 真要删除吗?", function (result) {
                    if (result) {
                        $.post('deleteRelationItem', {'_token': token, 'relationId': relationId}, function (data) {
                            var result = jQuery.parseJSON(data);
                            if (result.status == 'success') {
                                relationHtml = result.html;

                                var tbody = document.getElementById('relation-body');
                                var len = tbody.children.length;
                                var index = row.eq(0).html();
                                var selRow = -1;
                                var rowIndex = 0;
                                for (var i = 0; i < len; i++) {
                                    var selIndex = tbody.children[i].children[0].innerText;
                                    if (selIndex == index) {
                                        selRow = i;
                                        continue;
                                    }
                                    tbody.children[i].children[0].innerText = rowIndex + 1;
                                    rowIndex++;
                                }
                                if (selRow > -1)
                                    tbody.deleteRow(selRow);

                                var selectTag = $('#family-table').find('.new-relation');
                                selectTag.html(relationHtml);
                            } else {
                                var errMsg = '[' + relationName + ']  ' + result.status;
                                $.gritter.add({
                                    title: '错误',
                                    text: errMsg,
                                    class_name: 'gritter-error'
                                });
                                return;

                            }
                        });
                    }
                });
            });
        }
        function bindRelationAddAction() {
            $('.relation-add').on('click', function () {
                var row = $(this).closest('tr');
                var relationName = row.find('input').val();
                if (relationName.length < 1) {
                    $.gritter.add({
                        title: '错误',
                        text: '',
                        class_name: 'gritter-error'
                    });
                    return;
                }

                $.post('updateRelationItem', {'_token':token, 'relationId':'', 'name':relationName}, function (data) {
                    var result = jQuery.parseJSON(data);
                    if(result.status == 'success') {
                        row.find('.relation').attr('data-id', result.relationId);
                        row.find('.relation').html(relationName);
                        var btnHtml = '<div class="edit-buttons">' +
                            '<a href="javascript:void(0)" class="relation-edit blue"><i class="icon-edit bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="relation-delete red" style="margin-left:10px"><i class="icon-trash bigger-130"></i></a></div>' +
                            '<div class="save-buttons" style="display: none">' +
                            '<a href="javascript:void(0)" class="relation-save blue"><i class="icon-save bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="relation-cancel red" style="margin-left:10px"><i class="icon-remove bigger-130"></i></a></div>';
                        row.find('.action-buttons').html(btnHtml);

                        var body = document.getElementById('relation-body');
                        var leng = body.children.length;
                        var index = 0;
                        if(leng < 1)
                            index = 1;
                        else
                            index = Math.floor(body.children[leng - 1].children[0].innerText) + 1;
                        var rowHtml = '<td>' + index + '</td>' +
                            '<td data-id="" class="center"><input type="text" class="form-control"></td>' +
                            '<td class="action-buttons">' +
                            '<div class="add-buttons">' +
                            '<a href="javascript:void(0)" class="relation-add red"><i class="icon-plus bigger-130"></i></a>' +
                            '</div></td>';
                        var newtr = document.createElement('tr');
                        newtr.innerHTML = rowHtml;
                        body.appendChild(newtr);

                        unbindRelationButtonAction();
                        bindRelationButtonAction();

                        relationHtml = result.html;
                        var selectTag = $('#family-table').find('.new-relation');
                        selectTag.html(relationHtml);
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: result.status,
                            class_name: 'gritter-error'
                        });
                    }
                })
            });
        }

    </script>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>
@stop
