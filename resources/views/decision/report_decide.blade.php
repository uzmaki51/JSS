@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>


        <style>
            .stamp_view {
                width:450px;
            }

            table td {
                height : 34px;
            }
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4><b>{{transDecideManage("title.ElectronicApprove")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transDecideManage("title.DocApprove")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-offset-6 col-md-3" style="height: 30px">
                    <h5 style="float: right"><a href="javascript: history.back();"><strong>{{transDecideManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
        		<form action='submitDecideState' method="POST" id="decide-form">
        		<input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
        		<input type="text" class="hidden" name="reportId" value="{{$reportInfo['id']}}">
                <input type="submit" class="hidden" id="submit_btn">
                <div class="row">
                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.approveName")}}</td>
                                <td class="custom-td-text1" colspan="5">{{$reportInfo['title']}}</td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1" style="width: 15%">{{transDecideManage("captions.docNumber")}}</td>
                                <td style="width: 35%" colspan="3">{{$reportInfo['docNo']}}</td>
                                <td class="custom-td-label1" style="width: 20px" rowspan="4">{{transDecideManage("captions.auth")}}</td>
                                <td rowspan="4" class="stamp_view">
                                    <div class="stamp_view">
                                        <div class="navbar-header navbar-left stamp-view">
                                        <ul id="stamp-list" class="nav ace-nav" style="display: block">
                                            @foreach($decidedInfos as $decidedInfo)
                                                <li style="height: 110px; width: 140px">
                                                    <div class="stamp-item" style="border:1px solid #959f9f;margin:3px;text-align: center">
                                                        <div style="width:100%;border-bottom: 1px solid #959f9f;padding:3px">
                                                            <span>{{transDecideManage("captions.authorizer")}}</span>
                                                        </div>
                                                        <div style="width:100%;border-bottom: 1px solid #959f9f;padding:3px;">
                                                            @if(empty($decidedInfo['agentUser']))
                                                                <span>{{$decidedInfo['pos']}} {{$decidedInfo['name']}}</span>
                                                            @else
                                                                <span style="font-size: 11px">{{$decidedInfo['pos']}} {{$decidedInfo['name']}}/{{$decidedInfo['agentPos']}} {{$decidedInfo['agentUser']}}</span>
                                                            @endif
                                                        </div>
                                                        <div style="padding:3px;height:65px">
                                                            @if(!empty($decidedInfo['stamp']))
                                                                <img src="/uploads/stamp/{{$decidedInfo['stamp']}}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.departName")}}</td>
                                <td class="custom-td-text1" colspan="3">{{$creator['unit']}}</td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.approver")}}</td>
                                <td style="width: 15%"><label class="form-control">{{$creator['name']}}</label></td>
                                <td class="custom-td-text1" style="width: 10%"><label class="form-control">{{$creator['pos']}}</label></td>
                                <td class="custom-td-text1" style="width: 10%">
                                    <label class="form-control">@if($creator['isAdmin']==1) 管理者 @else 一般使用者 @endif</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.draftDate")}}</td>
                                <td class="custom-td-text1 center">{{$reportInfo['draftDate']}}</td>
                                <td class="custom-td-text1">{{transDecideManage("captions.savePeriod")}}</td>
                                <td class="custom-td-text1">
                                    @if($reportInfo['storage'] < 13)
                                        {{$reportInfo['storage']}}月
                                    @else
                                        {{$reportInfo['storage'] - 12}}年
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.receiver")}}</td>
                                <td class="custom-td-text1" colspan="6">{{$reportInfo['recvUser']}}</td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.sendDepartment")}}</td>
                                <td class="custom-td-text1" colspan="6">{{$reportInfo['submitUnit']}}</td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.authContent")}}</td>
                                <td class="custom-td-text1" colspan="5">
                                    <textarea class="form-control" name="decide-content" style="height:210px">{{$reportInfo['content']}}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.attachFile")}}1</td>
                                <td class="custom-td-text1" colspan="5">
                                    @if(!empty($reportInfo['file1']))
                                        <a href="/fileDownload?type=report&path={{$reportInfo['file1']}}&filename={{$reportInfo->fileName1}}" style="margin-left:10px">
                                            <i class="icon-file bigger-125"></i>
                                            <span style="margin-left:5px">{{$reportInfo['fileName1']}}</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.attachFile")}}2</td>
                                <td class="custom-td-text1" colspan="5">
                                    @if(!empty($reportInfo['file2']))
                                        <a href="/fileDownload?type=report&path={{$reportInfo['file2']}}&filename={{$reportInfo->fileName2}}" style="margin-left:10px">
                                            <i class="icon-file bigger-125"></i>
                                            <span style="margin-left:5px">{{$reportInfo['fileName2']}}</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="black br-hblue">
                                <th class="center">{{transDecideManage("captions.no")}}</th>
                                <th class="center">{{transDecideManage("captions.authorizer")}}</th>
                                <th class="center">{{transDecideManage("captions.authorState")}}</th>
                                <th class="center">{{transDecideManage("captions.authorDate")}}</th>
                                <th class="center">{{transDecideManage("captions.authorOpinion")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $index = 1; ?>
                            @foreach($decidedInfos as $decider)
                                @if($decider['isDecide'] == 1)
                                    <tr class="custom-td-text1">
                                        <td class="center">{{$index}}</td>
                                        <td class="center">
                                            @if(!empty($decider['agentUser']))
                                                {{$decider['pos']}} {{$decider['name']}} / {{$decider['agentPos']}} {{$decider['agentUser']}}
                                            @else
                                                {{$decider['name']}}
                                            @endif
                                        </td>
                                        <td class="center">{{$decider['state']}}</td>
                                        <td class="center">{!! convert_datestr($decider['stampDate']) !!}</td>
                                        <td>{!! nl2br($decider['note']) !!}</td>
                                    </tr>
                                    <?php $index++ ?>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <table class="table table-bordered">
                            <tbody>
                            <tr class="custom-td-label1">
                                <td colspan="2">{{transDecideManage("captions.auth")}}</td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1" style="width: 15%;">{{transDecideManage("captions.process")}}</td>
                                <td class="custom-td-text1">
                                    <input type="radio" name="decideRadio" data-state="0" value="0" @if($reportInfo['eject'] == 0) checked @endif>auth
                                    <input type="radio" name="decideRadio" data-state="1" value="1" @if($reportInfo['eject'] == 1) checked @endif>reject
                                    <input type="radio" name="decideRadio" data-state="2" value="2" @if($reportInfo['eject'] == 2) checked @endif>defer
                                </td>
                            </tr>
                            <tr class="custom-td-label1">
                                <td class="custom-td-text1">{{transDecideManage("captions.opinion")}}</td>
                                <td class="custom-td-text1">
                                        <textarea name="decide_note" class="form-control"
                                                  style="height:80px">{{$reportInfo['note']}}</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <a class="btn btn-primary btn-sm btn-start" style="float: right; width :80px;"><i class="icon-save"></i>{{transDecideManage("captions.register")}}</a>
                            </div>
                            <div class="col-md-6">
                                <a href="decidemanage" class="btn btn-default btn-sm" style="float: left; width :80px;"><i class="icon-reply"></i>{{transDecideManage("captions.cancel")}}</a>
                            </div>
                        </div>
                    </div>
                 </form>
                <div class="space-10"></div>
            </div>
        </div>
    </div>

    <script>

        var token = '<?php echo csrf_token() ?>';
        var reportId = '{!! $reportInfo['id'] !!}';
        var decideState = 0;
        var state = '{!! $state !!}';

        function setDeideState(state) {
            decideState = state;
        }

        function showNoteDetail(id) {
            var tag = document.getElementById('show_detail' + id);
            var content = tag.getAttribute("note");

            bootbox.alert("意见:&nbsp;&nbsp;" + content, function(result) {
            });
        }

        $(function() {
            var count = '{!! count($decidedInfos) !!}' * 1;
            var width = 140 * count + 10;
            var td_width = $(".stamp_view").width();
            $('.stamp_view').css('width', td_width + 'px');
            if(td_width < width) {
                var attr = width + 'px';
                $('#stamp-list').css('width', attr);
            }

            editor = KindEditor.create('textarea[name="decide-content"]', {
                cssPath: '/KindEditor/plugins/code/prettify.css',
                newlineTag: 'br',
                allowPreviewEmoticons: false,
                allowImageUpload: false,
                items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'link']
            });

            $('[name=decideRadio]').on('click', function () {
                decideState = $(this).data('state');
            });

            $('.btn-start').on('click', function () {
                var message = '批准文件，前批准人不可以批准。\n要继续马?';
                if(decideState == 1)
                    message = '批准文件，前批准人不可以否决。\n要继续马?';
                else if(decideState == 2)
                    message = '批准文件，前批准人不可以保留。\n要继续马?';
                bootbox.confirm(message, function(result) {
                    if(result) {
                        $('#submit_btn').click();
                    }
                });
            });

            if(state == 'error') {
            	var msg = '{!! $msg !!}';
            	$.gritter.add({
                    title: '错误',
                    text: msg,
                    class_name: 'gritter-error'
                });

            }


        });

    </script>

@stop