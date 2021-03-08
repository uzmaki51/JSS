@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header" style="margin-bottom: -9px">
                <div class="row" style="height: 60px">
                    <div class="col-md-3">
                        <h4><b>{{transDecideManage("title.ElectronicApprove")}}</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                {{transDecideManage("title.ElectronicApprove")}}
                            </small>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <form role="form" method="POST" action="{{url('/decision/envRegister')}}" enctype="multipart/form-data" id="validation-form">
                        <div class="col-md-12" style="margin-bottom: 5px; margin-top: 10px; text-align: right">
                                <button type="button" class="btn btn-default btn-sm" onclick="onRegister()" style="width: 80px">
                                    <i class="icon-save"></i>{{transDecideManage("captions.register")}}
                                </button>
                                <input type="submit" id="submit" style="display: none">
                        </div>

                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="sample-table-1"
                                           class="table table-bordered" style="font-weight: bold">
                                        <tbody>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.approveStamp")}}
                                            </td>

                                            <td class="custom-td-dec-text">
                                                  <div class="col-md-12 input-group">
                                                      <div class="col-md-1">
                                                          <img src="@if(isset($content) && !empty($content['signPath']))/uploads/stamp/{{$content['signPath']}} @endif" alt="无" style="width: 58px; height: 58px; float: right">
                                                      </div>
                                                      <div class="col-md-4"><input  multiple="" type="file" id="stamp" name="stamp" style="display: none"/></div>
                                                  </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.mailNotification")}}
                                            </td>
                                            <td class="custom-td-dec-text">
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isMailUse" value="1" @if(isset($content) && ($content['mailUse'] == 1)) checked @endif>使用
                                                </div>
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isMailUse" value="0" @if(isset($content) || ($content['mailUse'] == 0)) checked @endif>无使用
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.leftFunction")}}
                                            </td>
                                            <td class="custom-td-dec-text">
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isNofunc" value="1" @if(isset($content) && ($content['absFunc'] == 1)) checked @endif>使用
                                                </div>
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isNofunc" value="0" @if(empty($content) || ($content['absFunc'] == 0)) checked @endif>无使用
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.leftPeriod")}}
                                            </td>
                                            <td class="custom-td-dec-text">
                                                <div class="row">
                                                    <div class="col-md-4 input-group">
                                                        <input class="form-control date-picker" id="sDate" name="sDate"
                                                               type="text" data-date-format="yyyy-mm-dd" value="@if(isset($content)) {{$content['startDate']}} @endif">
                                                        <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                    </div>
                                                    <span style="float: left;padding-top:7px">~</span>
                                                    <div class="col-md-4 input-group">
                                                        <input class="form-control date-picker" id="eDate" name="eDate"
                                                               type="text" data-date-format="yyyy-mm-dd" value="@if(isset($content)) {{$content['endDate']}} @endif">
                                                        <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                                                    </div>

                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.autoResponse")}}
                                            </td>
                                            <td class="custom-td-dec-text">

                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isAuto" value="1" @if(isset($content) && ($content['autoResp'] == 1)) checked @endif>发送自动回复
                                                </div>
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isAuto" value="0" @if(empty($content) || ($content['autoResp'] == 0)) checked @endif>不回复
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.autoResponseMsg")}}
                                            </td>
                                            <td class="custom-td-dec-text">
                                                <textarea id="news-content" name="autoreplyContent" class="form-control" style="height:210px">@if(isset($content)) {!! $content['respContent'] !!} @endif</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.insteadResponse")}}
                                            </td>
                                            <td class="custom-td-dec-text">
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isUseAgent" class="isUseAgent" value="0" @if(empty($content) || ($content['agentFunc'] == 0)) checked @endif>无使用
                                                </div>
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isUseAgent" class="isUseAgent" value="1" @if(isset($content) && ($content['agentFunc'] == 1)) checked @endif>使用代替批准
                                                </div>
                                                <div class="col-md-3 input-group">
                                                    <input type="radio" name="isUseAgent" class="isUseAgent" value="2" @if(isset($content) && ($content['agentFunc'] == 2)) checked @endif>不批准通过
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="custom-td-dec-label">
                                            {{transDecideManage("captions.insteadApprover")}}
                                            </td>
                                            <input type="hidden" name="agentId" value="@if (isset($agentInfo)) {{$agentInfo['id']}} @endif">
                                            <td class="custom-td-dec-text">
                                                <label id="labAgentName"> @if(isset($agentInfo)) {{$agentInfo['unit'].' '.$agentInfo['name']}} @endif </label>
                                                <button type="button" id="showMemTree" data-target="#myModal" data-toggle="modal">
                                                    <i class="icon-folder-open-alt"></i>{{transDecideManage("captions.select")}}
                                                </button>
                                            </td>
                                        </tr>
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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 class="modal-title"><b>{{transDecideManage("captions.selectInsteadApprover")}}</b></h5>
                </div>
                <div class="modal-body">
                    {!! $unitMember !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnCancel">{{transDecideManage("captions.cancel")}}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnSelect">{{transDecideManage("captions.select")}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script src="/KindEditor/kindeditor.js"></script>
    <script src="/KindEditor/lang/zh_CN.js"></script>
    <script src="/KindEditor/plugins/code/prettify.js"></script>

    <script type="text/javascript">

        var agentUse = @if(isset($content) && ($content['absFunc'] == 1)) 1; @else 0; @endif
        var agentDecide = @if(isset($content) && ($content['agentFunc'] == 1)) 1; @else 0; @endif
        var state = '{!! $state !!}';
        var message = '{!! $msg !!}';

        function onCancel() {
            history.back();
        }

        $(function () {
            if(state == 'success') {
                $.gritter.add({
                    title: '成功',
                    text: '保存批准环境成功了。',
                    class_name: 'gritter-success'
                });
            } else if(state == 'error'){
                $.gritter.add({
                    title: '错误',
                    text: message,
                    class_name: 'gritter-success'
                });
            }

            $('#stamp').ace_file_input({
                style: 'well',
                btn_choose: '请选择印章',
                btn_change: null,
                no_icon: 'icon-cloud-upload',
                droppable: true,
                thumbnail: 'small',//large | fit
                preview_error: function (filename, error_code) {
                }
            }).on('change', function () {
            });

            $("#tree").treeview({//나무보기구조를 구축한다.
                toggle: function () {
                }
            });

            $('#btnSelect').on('click', function(){
                var radios = document.getElementsByName('radio');
                for(var i=0; i<radios.length; i++)
                {
                    if(radios[i].checked===true){
                        var agentId = radios[i].parentElement.id;
                        $('[name=agentId]').val(agentId);

                        var subUnit = radios[i].parentElement.parentElement.parentElement;
                        var unitName = subUnit.children[0].innerText;
                        var agent = radios[i].nextElementSibling;
                        var agentName = agent.innerText;
                        $('#labAgentName').html(unitName + '  '+ agentName);
                        $('#myModal').modal('hide');
                        break;
                    }
                }
            });

            $('[name=isNofunc]').on('change', function () {
               agentUse = $('[name=isNofunc]:checked').val();
                setAgentFunctionUse();
            });

            $('[name=isUseAgent]').on('change', function () {
                agentDecide = $('[name=isUseAgent]:checked').val();
                setAgentDecideUse();
            });

            setAgentFunctionUse();

            editor = KindEditor.create('textarea[name="autoreplyContent"]', {
                cssPath : '/KindEditor/plugins/code/prettify.css',
                newlineTag : 'br',
                allowPreviewEmoticons : false,
                allowImageUpload : false,
                items : ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'link']
            });

        });

        $('.isUseAgent').click(function(){
            if($(this).val()==1){
                document.getElementById('showMemTree').disabled=false;
            }else{
                document.getElementById('showMemTree').disabled=true;
            }

        });

        function setAgentFunctionUse() {
            if(agentUse == 0) {
                $('[name=sDate]').attr('disabled', 'disabled');
                $('[name=eDate]').attr('disabled', 'disabled');
                $('[name=isAuto]').attr('disabled', 'disabled');
                $('[name=autoreplyContent]').attr('disabled', 'disabled');
                $('[name=isUseAgent]').attr('disabled', 'disabled');
                $('#showMemTree').attr('disabled', 'disabled');
            } else {
                $('[name=sDate]').removeAttrs('disabled');
                $('[name=eDate]').removeAttrs('disabled');
                $('[name=isAuto]').removeAttrs('disabled');
                $('[name=autoreplyContent]').removeAttrs('disabled');
                $('[name=isUseAgent]').removeAttrs('disabled');
                $('#showMemTree').removeAttrs('disabled');
                setAgentDecideUse();
            }
        }

        function setAgentDecideUse() {
            if((agentUse == 1) && (agentDecide == 1)) {
                $('#showMemTree').removeAttrs('disabled');
            } else {
                $('#showMemTree').attr('disabled', 'disabled');
            }
        }

        function onRegister()
        {

            $('#submit').trigger('click');
        }
    </script>
@stop