{{--Quarter Manage Page--}}
@extends('layout.sidebar')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{transOrgManage("title.QuarterManage")}}</b>
                    </h4>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row" id="quarterstructure">
                    <div class="col-md-8 col-md-offset-2">

                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="lighter smaller col-md-4">{{transOrgManage("captions.officeStructure")}}</h4>
                                @if(count($units) == 0)
                                    <div class="widget-toolbar action-buttons">
                                        <button class="btn btn-xs btn-primary" id="id-btn-adddialog">
                                            <i class="icon-plus-sign bigger-110"></i>
                                            {{transOrgManage("captions.add")}}
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="widget-body">
                                <div class="widget-main padding-8">
                                    <div class="dd" id="unitList">
                                        <ol class="dd-list">
                                            <?php $endOl = 0; $parentId = 0; ?>
                                            @foreach($units as $unit)
                                                @if(isset($unit->countChild))
                                                    @if($unit->parentId == $parentId)
                                                        <?php $endOl++; ?>
                                                        <li class="dd-item">
                                                            <div class="dd-handle" data-id="{{$unit->id}}">{{$unit->title}}
                                                                <div class="pull-right action-buttons">
                                                                    <a class="blue" href="javascript:addUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-plus bigger-130"></i></a>
                                                                    <a class="blue" href="javascript:editUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-edit bigger-130"></i></a>
                                                                    <a class="red" href="javascript:deleteUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-trash bigger-130"></i></a>
                                                                </div>
                                                            </div>
                                                            <ol class="dd-list">
                                                    @elseif($unit->parentId == 0)
                                                        @for(;$endOl > 0;$endOl--)
                                                            </ol></li>
                                                        @endfor
                                                        <?php $endOl = 1; ?>
                                                            <li class="dd-item">
                                                                <div class="dd-handle" data-id="{{$unit->id}}">{{$unit->title}}
                                                                    <div class="pull-right action-buttons">
                                                                        <a class="blue" href="javascript:addUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-plus bigger-130"></i></a>
                                                                        <a class="blue" href="javascript:editUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-edit bigger-130"></i></a>
                                                                        <a class="red" href="javascript:deleteUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-trash bigger-130"></i></a>
                                                                    </div>
                                                                </div>
                                                                <ol class="dd-list">
                                                    @else
                                                        <?php if($endOl > 1) {
                                                                echo '</ol></li>';
                                                                $endOl--;
                                                               }
                                                               $endOl++;         ?>
                                                        <li class="dd-item">
                                                            <div class="dd-handle" data-id="{{$unit->id}}">{{$unit->title}}
                                                                <div class="pull-right action-buttons">
                                                                    <a class="blue" href="javascript:addUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-plus bigger-130"></i></a>
                                                                    <a class="blue" href="javascript:editUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-edit bigger-130"></i></a>
                                                                    <a class="red" href="javascript:deleteUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-trash bigger-130"></i></a>
                                                                </div>
                                                            </div>
                                                            <ol class="dd-list">
                                                    @endif
                                                    <?php $parentId = $unit->id; ?>
                                                @else
                                                     <?php
                                                         if($unit->parentId == 0) {
                                                            for(;$endOl > 0;$endOl--)
                                                                echo '</ol></li>';
                                                         } elseif($unit->parentId != $parentId) {
                                                             if($endOl > 1) {
                                                                echo '</ol></li>';
                                                                $endOl--;
                                                             }
                                                         } ?>

                                                    <li class="dd-item">
                                                        <div class="dd-handle" data-id="{{$unit->id}}">{{$unit->title}}
                                                            <div class="pull-right action-buttons">
                                                                <a class="blue" href="javascript:addUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-plus bigger-130"></i></a>
                                                                <a class="blue" href="javascript:editUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-edit bigger-130"></i></a>
                                                                <a class="red" href="javascript:deleteUnit('{{$unit->title}}', '{{$unit->id}}')"><i class="icon-trash bigger-130"></i></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalback" class="in"></div>
    <div id="modal-adddlg" class="hide">
        <div class="row" style="padding: 10px">
            <label style="padding:6px 0;float:left">{{transOrgManage("captions.higerDepartment")}}</label>
            <input class="form-control" style="margin-left:20px;width:60%;float: left" type="text" name="parent" value="" disabled/>
        </div>
        <div class="row" style="padding: 10px">
            <label class="form-field-username" style="padding:6px 0;float:left">{{transOrgManage("captions.departmentName")}}</label>
            <input class="form-control" style="margin-left:20px;width:60%;float: left" type="text" name="unitName" placeholder="部门名称" value=""/>
            <input type="hidden" name="unitId">
        </div>
    </div>
    <div id="modal-changedlg" class="hide">
        <div class="form-group">
            <label style="padding:6px 0;float:left">{{transOrgManage("captions.departmentName")}}</label>
            <input class="form-control"  style="margin-left:20px;width:60%;float: left" type="text" name="changename"
                   placeholder="部门名称" value=""/>
        </div>

    </div>

    <script src="{{asset('/assets/js/jquery.nestable.min.js') }}"></script>

    <script>

        var token = '{!! csrf_token() !!}';

        $(function () {

            $('.dd').nestable();

            $('.dd-handle a').on('mousedown', function (e) {
                e.stopPropagation();
            });

            $('#id-btn-adddialog').on('click', function () {
                $('[name=parent]').removeAttrs('disabled');
                $('[name=unitName]').attr('disabled', 'disabled');
                $('[name=parent]').val('');
                $('[name=unitName]').val('');

                var dialog = $("#modal-adddlg").removeClass('hide').dialog({
                    modal: true,
                    title: "添加部门",
                    title_html: true,
                    buttons: [
                        {
                            text: "取消",
                            "class": "btn btn-xs",
                            click: function () {
                                $(this).dialog("close");
                            }
                        },
                        {
                            text: "确认",
                            "class": "btn btn-primary btn-xs",
                            click: function () {
                                $(this).dialog("close");
                                var unitName = $("[name=parent]").val();
                                $.post('quarterregister', { '_token':token, 'unitName':unitName, 'parentId':0}, function (result) {
                                    if (result == 'success') {
                                        location.reload();
                                    } else {
                                        $.gritter.add({
                                            title: '错误',
                                            text: '部门名称重复了。',
                                            class_name: 'gritter-error '
                                        });
                                    }
                                });
                            }
                        }]
                });
            });

        });

        function editUnit(title, unitId) {
            $('[name=changename]').val(title);

            var dialog = $("#modal-changedlg").removeClass('hide').dialog({
                modal: true,
                title: "变更部门名称",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "确认",
                        "class": "btn btn-primary btn-xs",
                        click: function () {
                            $(this).dialog("close");
                            var unitName = $("[name=changename]").val();
                            $.post('quarterupdate', { '_token':token, 'unitName':unitName, 'unitId':unitId}, function (result) {
                                if (result == 'success') {
                                    location.reload();
                                } else {
                                    $.gritter.add({
                                        title: '错误',
                                        text: '['+ unitName + ']' + ' 部门已被删掉了。',
                                        class_name: 'gritter-error '
                                    });
                                }

                            });
                        }
                    }]
            });
        }

        function deleteUnit(title, unitId) {
            bootbox.confirm("[ " + title + " ] 要删掉部门吗?", function (result) {
                if (result) {
                    //确认단추를 눌렀을 때의 처리
                    $.post('quarterdel', {'_token': token, unitId: unitId}, function (result) {
                        if (result == 'success') {
                            location.reload();
                        } else {
                            $.gritter.add({
                                title: '错误',
                                text: '['+ title + ']' + ' 部门已经被删掉了。',
                                class_name: 'gritter-error '
                            });
                        }
                    });
                }
            });
        }

        function addUnit(parentName, unitId) {
            $('[name=parent]').attr('disabled', 'disabled');
            $('[name=unitName]').removeAttrs('disabled');

            $('[name=parent]').val(parentName);
            $('[name=unitName]').val('');

            var dialog = $("#modal-adddlg").removeClass('hide').dialog({
                modal: true,
                title: "添加部门",
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class": "btn btn-xs",
                        click: function () {
                            $(this).dialog("close");
                        }
                    },
                    {
                        text: "确认",
                        "class": "btn btn-primary btn-xs",
                        click: function () {
                            $(this).dialog("close");
                            var unitName = $("[name=unitName]").val();
                            $.post('quarterregister', { '_token':token, 'unitName':unitName, 'parentId':unitId}, function (result) {
                                if (result == 'success') {
                                    location.reload();
                                } else {
                                    $.gritter.add({
                                        title: '错误',
                                        text: '部门名称重复了。',
                                        class_name: 'gritter-error '
                                    });
                                }
                            });
                        }
                    }]
            });

        }

    </script>

@endsection