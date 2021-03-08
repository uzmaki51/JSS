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
                    <h4><b>{{transOrgManage("title.PermissionManage")}}</b>
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
                    <h5 style="float: right"><a href="userPrivilege"><strong>{{transOrgManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="col-md-12" style="border: 1px solid #cccccc">
                                <form role="form" method="POST"
                                      action="@if(isset($userid)>0) {{url('/org/storePrivilege')}} @else{{url('/org/memberadder')}}@endif"
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
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td class="custom-td-label" colspan="3"><h4>{{transOrgManage("captions.permissionManage")}}</h4></td>
                                                    </tr>
													<?php $index = 0; $cflag = true; ?>
                                                    @foreach($pmenus as $pmenu)
                                                        @if(isset($userid))
                                                            @if(in_array($pmenu['id'], explode(',', $userinfo['menu'])))
																<?php $cflag = true; ?>
                                                            @else
																<?php $cflag = false; ?>
                                                            @endif

                                                            <tr id="{{'row'.$index}}">
                                                                @if($pmenu['parentId'] == 0)
                                                                    <td class="custom-td-label">
                                                                        {{$pmenu['title']}}
                                                                    </td>
                                                                @endif
                                                                <td class="custom-td-text"
                                                                    style="width: 3%; text-align: center">
                                                                    <input type="checkbox" onclick="check({{$index}})"
                                                                           id="{{'group'.$index}}"
                                                                           name="{{'group'.$index}}"
                                                                           @if ($cflag==true) checked="true" @endif>
                                                                    <input type="checkbox" id="{{$pmenu['id']}}"
                                                                           name="{{$pmenu['id']}}"
                                                                           style="display: none"
                                                                           @if ($cflag==true) checked="true" @endif>
                                                                </td>
                                                        @else
                                                            <tr id="{{'row'.$index}}">
                                                                @if($pmenu['parentId']==0)
                                                                    <td class="custom-td-label">
                                                                        {{$pmenu['title']}}
                                                                    </td>
                                                                @endif
                                                                <td class="custom-td-text" style="width: 3%; text-align: center">
                                                                    <input type="checkbox" onclick="check({{$index}})"
                                                                           id="{{'group'.$index}}"
                                                                           name="{{'group'.$index}}">
                                                                    <input type="checkbox" id="{{$pmenu['id']}}"
                                                                           name="{{$pmenu['id']}}"
                                                                           style="display: none">
                                                                </td>
                                                                @endif
                                                                <td class="custom-td-text" style="width: 77%">
                                                                    <div class="row">

                                                                        @foreach($cmenus[$index] as $menu)
																			<?php $flag1 = false ?>
                                                                            @if(isset($userid))
                                                                                @if(in_array($menu['id'], explode(',',$userinfo['menu'])))
																					<?php $flag1 = true ?>
                                                                                @endif
                                                                            @endif
                                                                            <div class="col-md-2">&nbsp
                                                                                <input type="checkbox"
                                                                                       class="{{'row'.$index}}"
                                                                                       onclick="checkchild({{$index}}, this)"
                                                                                       id="{{'row'.$menu['id']}}"
                                                                                       name="{{'row'.$menu['id']}}"
                                                                                       @if(($cflag==true) || ($flag1==true)) checked="true" @endif>
                                                                                <input type="checkbox"
                                                                                       id="{{$menu['id']}}"
                                                                                       name="{{$menu['id']}}"
                                                                                       style="display: none"
                                                                                       @if (($cflag==false) && ($flag1==true)) checked="true" @endif>
                                                                                &nbsp{{$menu['title']}}
                                                                                @if($menu['id'] == 37 || $menu['id'] == 38)
                                                                                    <label><input name="switch_{{$menu['id']}}" class="ace ace-switch ace-switch-6" type="checkbox" @if(isset($userid) && (($menu['id'] == 37 && $profile['attend_admin']) || ($menu['id'] == 38 &&  $profile['report_admin']))) checked @endif><span class="lbl" style="width:10px"></span></label>
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            </tr>
															<?php $index++?>
                                                            @endforeach
                                                            @if($userinfo['attributes']['isAdmin'] == IS_SHAREHOLDER)
                                                                <tr>
                                                                    <td class="custom-td-label" colspan="3"><h4>选船(*只有持股者才能显示.)</h4></td>
                                                                </tr>
                                                                <tr>
                                                                    <td  colspan="3" style="text-align: left!important;">
                                                                        <?php $registerList = explode(',', $userinfo['attributes']['shipList']);?>
                                                                        <select multiple="multiple" class="chosen-select form-control width-100" name="shipList[]" data-placeholder="Choose a Ship...">
                                                                            @foreach($shipList as $key => $item)
                                                                                <option value="{{ $item['attributes']['shipID'] }}" {{ in_array($item['attributes']['shipID'], $registerList) ? 'selected' : '' }}>{{ $item['attributes']['shipName_En'] }}</option>
                                                                            @endforeach
                                                                        </select>
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
                } else{
                    return this.optional(element) || /^\d{2}-\d{3}\-\d{4}( x\d{1,6})?$/.test(value);
                }
            }, "집전화번호형식에 맞게 입력하십시오. 례:045-12-3421, 02-357-2415(평양)");

            jQuery.validator.addMethod("phone", function (value, element) {
                return this.optional(element) || /^\d{3}-\d{3}\-\d{4}( x\d{1,6})?$/.test(value);
            }, "손전화번호형식에 맞게 입력하십시오.례: 191-222-9988");

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
                    account: "请填写识别号码。",
                    name: "请填写名称。",
                    email: "请填写符合电子邮件形式(比如: bss@www.com)",
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

        function msg() {
            $.gritter.add({
                title: '错误',
                text: '清闲保存用户信息。',
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

        function bindFamilyButtonAction() {
            $('.family-edit').click(familyEdit);
            $('.family-cancel').click(familyCancel);
            $('.family-add').click(familyAdd);
            $('.family-save').click(familySave);
            $('.family-delete').click(familyDelete);
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

        function unbindFamilyButtonAction() {
            $('.family-edit').unbind('click', familyEdit);
            $('.family-cancel').unbind('click', familyCancel);
            $('.family-add').unbind('click', familyAdd);
            $('.family-save').unbind('click', familySave);
            $('.family-delete').unbind('click', familyDelete);
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
                    text: '要对学历项目进行输入。',
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
                            text: '输入的学历重复了。',
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
                    text: '要对学历项目进行输入。',
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
                            text: '输入的学历重复了。',
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

            bootbox.confirm("[" + school_name + "] 要删掉吗?", function (result) {
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

        function familyEdit() {
            var obj = $(this).closest('tr').children();

            var relation = obj.eq(1).data('relation');
            var relationName = obj.eq(1).html();
            obj.eq(1).html(relationHtml);
            obj.eq(1).find('select').val(relation);
            obj.eq(1).find('select').attr('data-old', relation);
            obj.eq(1).find('select').attr('data-text', relationName);

            obj.eq(2).html('<input type="text" class="form-control" data-old="' + obj.eq(2).text() + '" value="' + obj.eq(2).text() +'">');

            var sex = obj.eq(3).data('sex');
            var selectHtml = '<select class=form-control data-old="' + sex + '"><option value="0">男</option><option value="1">녀자</option></select>';
            obj.eq(3).html(selectHtml);
            obj.eq(3).find('select').val(sex);
            if(sex == 0)
                obj.eq(3).find('select').attr('data-text', '男');
            if(sex == 1)
                obj.eq(3).find('select').attr('data-text', '女');

            var party = obj.eq(4).data('party');
            selectHtml = '<select class=form-control data-old="' + party + '"><option value="0">&nbsp;</option><option value="1">로동당</option></select>';
            obj.eq(4).html(selectHtml);
            obj.eq(4).find('select').val(party);
            if(party == 0)
                obj.eq(4).find('select').attr('data-text', '');
            if(party == 1)
                obj.eq(4).find('select').attr('data-text', '!!');

            var dateStr = obj.eq(5).html();
            var dateHtml = '<div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"  data-old="' + dateStr + '" value="' + dateStr + '">' +
                '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div>';
            obj.eq(5).html(dateHtml);

            obj.eq(6).html('<input type="text" class="form-control" data-old="' + obj.eq(6).text() + '" value="' + obj.eq(6).text() +'">');
            obj.eq(7).html('<input type="text" class="form-control" data-old="' + obj.eq(7).text() + '" value="' + obj.eq(7).text() +'">');
            obj.eq(8).html('<input type="text" class="form-control" data-old="' + obj.eq(8).text() + '" value="' + obj.eq(8).text() +'">');
            obj.eq(9).find('.row_edit').hide();
            obj.eq(9).find('.row_apply').show();
            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
        }

        function familyCancel() {
            var obj = $(this).closest('tr').children();

            obj.eq(1).html(obj.eq(1).find('select').data('text'));
            obj.eq(2).html(obj.eq(2).find('input').data('old'));
            obj.eq(3).html(obj.eq(3).find('select').data('text'));
            obj.eq(4).html(obj.eq(4).find('select').data('text'));
            obj.eq(5).html(obj.eq(5).find('input').data('old'));
            obj.eq(6).html(obj.eq(6).find('input').data('old'));
            obj.eq(7).html(obj.eq(7).find('input').data('old'));
            obj.eq(8).html(obj.eq(8).find('input').data('old'));
            obj.eq(9).find('.row_edit').show();
            obj.eq(9).find('.row_apply').hide();
        }

        function familyAdd() {
            var obj = $(this).closest('tr').children();
            var relation = obj.eq(1).find('select').val();
            var name = obj.eq(2).find('input').val();
            if(name.length < 1) {
                $.gritter.add({
                    title: '错误',
                    text: '请填写名称。',
                    class_name: 'gritter-error'
                });
                return;
            }

            var userId = $('#userid').val();
            $.post('updateMemberFamily',
                {   '_token':token,
                    'userId':userId,
                    'id' : 0,
                    'relation' : obj.eq(1).find('select').val(),
                    'name' : name,
                    'sex' : obj.eq(3).find('select').val(),
                    'isParty' : obj.eq(4).find('select').val(),
                    'birthday' : obj.eq(5).find('input').val(),
                    'pos' : obj.eq(6).find('input').val(),
                    'address' : obj.eq(7).find('input').val(),
                    'remark' : obj.eq(8).find('input').val(),
                }, function (data) {
                    if(data) {
                        var result = jQuery.parseJSON(data);
                        obj.eq(0).attr('data-id', result.id);
                        obj.eq(1).html(result.relName);
                        obj.eq(0).attr('data-relation', result.relation);
                        obj.eq(2).html(result.name);
                        obj.eq(3).html(result.sexName);
                        obj.eq(3).attr('data-sex', result.sexName);
                        obj.eq(4).html(result.partyName);
                        obj.eq(4).attr('data-party', result.isParty);

                        obj.eq(5).html(result.birthday);
                        obj.eq(6).html(result.pos);
                        obj.eq(7).html(result.address);
                        obj.eq(8).html(result.remark);

                        var actionHtml = '<div class="row_edit"><a href="javascript:void(0)" class="blue family-edit"><i class="icon-edit bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="red school-delete" style="margin-left:10px"><i class="icon-trash bigger-130"></i></a></div>' +
                            '<div class="row_apply" style="display: none"><a href="javascript:void(0)" class="blue family-save"><i class="icon-save bigger-130"></i></a>' +
                            '<a href="javascript:void(0)" class="red school-cancel" style="margin-left:10px"><i class="icon-remove bigger-130"></i></a></div>';
                        obj.eq(9).html(actionHtml);

                        var tbody = document.getElementById('family-table');
                        var newtr = document.createElement('tr');
                        var leng = tbody.children.length;
                        if(leng < 1)
                            index = 1;
                        else
                            index = Math.floor(tbody.children[leng - 1].children[0].innerText) + 1;
                        var newHtml = '<td data-id="0">' + index + '</td>' +
                            '<td data-relation="0">' + relationHtml + '</td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td data-sex="0"><select class="form-control chosen-select"><option value="0">남자</option><option value="1">녀자</option></select></td>' +
                            '<td data-party="0"><select class="form-control chosen-select"><option value="">&nbsp;</option><option value="1">로동당</option></select></td>' +
                            '<td style="width:150px"><div class="input-group"><input class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">' +
                            '<span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span></div></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td><input type="text" class="form-control"></td>' +
                            '<td class="action-buttons"><a href="javascript:void(0)" class="red family-add"><i class="icon-plus bigger-130"></i></a></td>';
                        newtr.innerHTML = newHtml;
                        tbody.appendChild(newtr);

                        $("#family-table").find('.chosen-select').chosen();

                        unbindFamilyButtonAction();
                        bindFamilyButtonAction();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: '输入的学历重复了。',
                            class_name: 'gritter-error'
                        });
                    }
                })
        }

        function familySave() {
            var obj = $(this).closest('tr').children();
            var id = obj.eq(0).data('id');
            var relation = obj.eq(1).find('select').val();
            var name = obj.eq(2).find('input').val();
            if(name.length < 1) {
                $.gritter.add({
                    title: '错误',
                    text: '请填写名称。',
                    class_name: 'gritter-error'
                });
                return;
            }

            var userId = $('#userid').val();
            $.post('updateMemberFamily',
                {   '_token':token,
                    'userId':userId,
                    'id' : id,
                    'relation' : obj.eq(1).find('select').val(),
                    'name' : name,
                    'sex' : obj.eq(3).find('select').val(),
                    'isParty' : obj.eq(4).find('select').val(),
                    'birthday' : obj.eq(5).find('input').val(),
                    'pos' : obj.eq(6).find('input').val(),
                    'address' : obj.eq(7).find('input').val(),
                    'remark' : obj.eq(8).find('input').val(),
                }, function (data) {
                    if(data) {
                        var result = jQuery.parseJSON(data);
                        obj.eq(0).attr('data-id', result.id);
                        obj.eq(1).html(result.relName);
                        obj.eq(0).attr('data-relation', result.relation);
                        obj.eq(2).html(result.name);
                        obj.eq(3).html(result.sexName);
                        obj.eq(3).attr('data-sex', result.sexName);
                        obj.eq(4).html(result.partyName);
                        obj.eq(4).attr('data-party', result.isParty);

                        obj.eq(5).html(result.birthday);
                        obj.eq(6).html(result.pos);
                        obj.eq(7).html(result.address);
                        obj.eq(8).html(result.remark);

                        obj.eq(9).find('.row_edit').show();
                        obj.eq(9).find('.row_apply').hide();
                    } else {
                        $.gritter.add({
                            title: '错误',
                            text: '输入的学历重复了。',
                            class_name: 'gritter-error'
                        });
                    }
                })
        }

        function familyDelete() {
            var row = $(this).closest('tr').children();
            var tbody = document.getElementById('family-table');
            var len = tbody.children.length;
            var index = row.eq(0).html();
            var id = row.eq(0).data('id');
            var family_name = row.eq(2).html();
            var family_relation = row.eq(1).html();

            bootbox.confirm("[" + family_relation + " : " + family_name + "] 的删掉缺人吗?", function (result) {
                if (result) {
                    $.post('deleteMemberFamily', {'_token': token, 'id': id}, function (data) {
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
                        text: '请填写关系名称。',
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
                bootbox.confirm("[" + relationName + "] 的关系要删掉吗?", function (result) {
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
                                var errMsg = '[' + relationName + '] 关系是 ' + result.status;
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
                        text: '请填写关系名称。',
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
