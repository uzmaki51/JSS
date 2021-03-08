@extends('layout.sidebar')
<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>{{ transShipManager('title.equipment') }}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>国际船用品规格격
                        </small>

                    </h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-12">
                        <div class="col-md-2">
                            <label style="float: left;padding-top: 5px">{{ transShipManager('IssaCode.Index') }}:</label>
                            <select class="form-control" id="code_type" style="float:left;margin-left:10px;width:80%">
                                <option value="">全部</option>
                                @foreach($codeList as $type)
                                    <option value="{{$type['id']}}" @if(isset($code) && ($code == $type['id'])) selected @endif>{{$type['Code']}} | {{$type['Code_Cn']}} | {{$type['Code_En']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label style="float: left;padding-top: 5px">{{ transShipManager('IssaCode.ISSA Code') }}:</label>
                            <input type="text" class="form-control" style="width:50%;float:left;" placeholder="-- -- --" id="codeNo" value="@if(isset($codeNo)){{$codeNo}}@endif">
                        </div>
                        <div class="col-md-4">
                            <label style="float: left;padding-top: 5px">{{ transShipManager('IssaCode.Content') }}:</label>
                            <input type="text" class="form-control" style="width:50%;float:left;margin-left:10px" id="codeContent" value="@if(isset($content)){{$content}}@endif">
                            <button class="btn btn-info btn-sm search_btn" style="float: left;margin-left:20px; width :80px"><i class="icon-search"></i>搜索</button>
                        </div>
                        <div class="col-md-2" style="float:right;text-align: right">
                            @if(!$isHolder)
                                <button class="btn btn-sm btn-info no-radius new_btn" style="width: 80px"><i class="icon-plus-sign-alt"></i>追加</button>
                            @endif
                            <div id="dialog_issacode" class="hide">
                                <form class="form-horizontal" method="post" action="updateIssaCode" id="issacode-form">
                                    <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="text" class="hidden" name="codeId">
                                    <button type="submit" class="hidden" id="submit_btn"></button>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('IssaCode.Index Code') }}</label>
                                        <div class="col-md-7" id="sel_type">
                                            <select name="sel_type" class="form-control chosen-select">
                                                <option value="">&nbsp;</option>
                                                @foreach($codeList as $type)
                                                    <option value="{{$type['id']}}">{{$type['Code']}} | {{$type['Code_Cn']}} | {{$type['Code_En']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('IssaCode.ISSA Code') }}</label>
                                        <div class="col-md-7"><input type="text" name="CodeNo" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('IssaCode.Content_Cn') }}</label>
                                        <div class="col-md-7"><input type="text" name="Content_Cn" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('IssaCode.Content_en') }}</label>
                                        <div class="col-md-7"><input type="text" name="Content_En" class="form-control"></div>
                                    </div>
                                    <div class="space-2"></div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right">{{ transShipManager('IssaCode.Special') }}</label>
                                        <div class="col-md-7"><input type="text" name="Capacity" class="form-control"></div>
                                    </div>
                                </form>
                            </div><!-- #dialog-message -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                    <div style="width: 100%">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th class="center" style="width:4%">No</th>
                                <th class="center" style="width:8%">{{ transShipManager('IssaCode.Index Code') }}</th>
                                <th class="center" style="width:20%">{{ transShipManager('IssaCode.Index') }}</th>
                                <th class="center" style="width:7%">{{ transShipManager('IssaCode.ISSA Code') }}</th>
                                <th class="center" style="width:35%">{{ transShipManager('IssaCode.Content') }}</th>
                                <th class="center" style="width:20%">{{ transShipManager('IssaCode.Special') }}</th>
                                @if(!$isHolder)
                                    <th class="center" style="width:6%"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody id="issacode_table">
							<?php $index = 1 + ($list->currentPage() - 1) * $list->perPage() ; ?>
                            @foreach ($list as $code)
                                <tr style="height:30px">
                                    <td class="center" rowspan="2" style="width:4%">{{$index++}}</td>
                                    <td class="hidden">{{$code['id']}}</td>
                                    <td class="hidden">{{$code['Code']}}</td>
                                    <td class="center" rowspan="2" style="width:8%">{{$code['CodeId']}}</td>
                                    <td class="center" rowspan="2" style="width:20%">{{$code['Code_Cn']}}</td>
                                    <td class="center" rowspan="2" style="width:7%">{{$code['CodeNo']}}</td>
                                    <td class="center" style="width:20%">{{$code['Content_Cn']}}</td>
                                    <td rowspan="2" class="center" style="width:35%">{{$code['Capacity']}}</td>
                                    <td class="hidden">{{$code['Content_En']}}</td>
                                    @if(!$isHolder)
                                        <td rowspan="2" style="width:6%" class="action-buttons" nowrap="nowrap">
                                            <a class="blue edit_btn">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                            <a class="red del_btn">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                <tr style="height:30px">
                                    <td class="hidden">0</td>
                                    <td class="center" style="width:20%">{{$code['Content_En']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.main-content -->

    <script>

        var token = '{!! csrf_token() !!}';

        function editISSACode() {
            var id = $('[name=codeId]').val();
            var title = '追加';
            if(id.length > 0) {
                title = '修改';
            }

            var dialog = $( "#dialog_issacode" ).removeClass('hide').dialog({
                modal: true,
                title: title,
                title_html: true,
                buttons: [
                    {
                        text: "取消",
                        "class" : "btn btn-xs",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "保存",
                        "class" : "btn btn-primary btn-xs",
                        click: function() {
                            $('#submit_btn').click();
                        }
                    }
                ]
            });
        }

        $(function() {

            $("#issacode-form").validate({
                rules: {
                    sel_type: "required",
                    CodeNo: "required",
                    Content_Cn: "required",
                    Content_En: "required",
                },
                messages: {
                    sel_type: "请选择分类。",
                    CodeNo: "请输入Issa Code。",
                    Content_Cn: "请输入名称。",
                    Content_En: "请输入英文名称。",
                }
            });

            $('.search_btn').on('click', function () {
                var code = $("#code_type").val();
                var codeNo = $('#codeNo').val();
                var content = $('#codeContent').val();
                var param = '';

                if(code.length > 0)
                    param = '?code=' + code;
                if(codeNo.length > 0)
                    param += param.length > 0 ? '&codeNo=' + codeNo : '?codeNo=' + codeNo;
                if(content.length > 0)
                    param += param.length > 0 ? '&content=' + content : '?content=' + content;

                location.href = 'shipISSACodeManage' + param;

            });

            $('.new_btn').on('click', function (){
                $('[name=codeId]').val('');
                $('[name=sel_type]').val('');
                $('[name=CodeNo]').val('');
                $('[name=Content_Cn]').val('');
                $('[name=Content_En]').val('');
                $('[name=Capacity]').val('');
                $('#sel_type .chosen-container span').text('');

                editISSACode();
            });

            $('.edit_btn').on('click', function () {
                var obj = $(this).closest('tr').children();

                $('[name=codeId]').val(obj.eq(1).text());
                $('[name=sel_type]').val(obj.eq(2).text());
                $('[name=CodeNo]').val(obj.eq(5).text());
                $('[name=Content_Cn]').val(obj.eq(6).text());
                $('[name=Content_En]').val(obj.eq(8).text());
                $('[name=Capacity]').val(obj.eq(7).text());
                $('#sel_type .chosen-container span').text($('[name=sel_type] option[value=' + $('[name=sel_type]').val() + ']').text());

                editISSACode();
            });

            $('.del_btn').on('click', function () {
                var obj = $(this).closest('tr').children();
                var codeId = obj.eq(1).text() * 1;
                var codeName = obj.eq(6).text();

                bootbox.confirm(codeName + " ISSA代码 真要删掉吗?", function (result) {
                    if (result) {
                        $.post('deleteIssaCode', {'_token':token, 'codeId':codeId}, function (result) {
                            var code = parseInt(result);
                            if (code > 0) {
                                var tbody = document.getElementById('issacode_table');
                                var len = tbody.children.length;
                                var row = 0;
                                for (; row < len; row++) {
                                    var tds = tbody.children[row];
                                    var rowCodeId = Math.floor(tds.children[1].innerText);
                                    if(codeId == rowCodeId)
                                        break;
                                }
                                tbody.deleteRow(row);
                                tbody.deleteRow(row+0.5);
                                $.gritter.add({
                                    title: '成功',
                                    text: codeName + ' 删掉成功!',
                                    class_name: 'gritter-success'
                                });
                            } else {
                                $.gritter.add({
                                    title: '错误',
                                    text: codeName + ' 是已经被删掉的。',
                                    class_name: 'gritter-error'
                                });
                            }
                        });
                    }
                });
            })
        });

    </script>
@stop
