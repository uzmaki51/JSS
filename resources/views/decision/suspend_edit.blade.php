@extends('layout.sidebar')

@section('content')
    <div class="main-content">

        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>

        <style>
            .td-stamp {
                width : 450px;
            }

        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-6">
                    <h4><b>{{transDecideManage("title.Drafting")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            {{transDecideManage("title.Draft Change")}}
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h5 style="float: right"><a href="javascript: history.back()"><strong>{{transDecideManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <form role="form" method="POST" action="{{url('decision/Reportsave')}}" enctype="multipart/form-data" id="validation-form">
                        <input type="submit" class="hidden" id="submit">
                        <input type="text" name="reportId" class="hidden" value="{{$reportinfo['id']}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="text" class="hidden" name="tempBox">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">
                                    {{transDecideManage("captions.docType")}}
                                    </td>
                                    <td class="custom-td-text1" colspan="3">
                                        <label class="form-control" style="float: left;width:70%">{{transDecideManage("captions.proposed")}}</label>
                                    </td>
                                    <td class="custom-td-label1" rowspan="5" style="width:20px">
                                    {{transDecideManage("captions.auth")}}
                                    </td>
                                    <td class="custom-td-text1 td-stamp" rowspan="5" style="width: 450px">
                                        <div class="td-stamp">
                                            <div class="navbar-header navbar-left stamp-view">
                                                <ul id="stamp-list" class="nav ace-nav" style="display: block">
													<?php $index = 0; ?>
													@foreach($decidedInfos as $decidedInfo)
														<li style="height: 110px; width: 140px">
															<div class="stamp-item" style="border:1px solid #eaeaea;margin:3px;text-align: center">
																<div style="width:100%;border-bottom: 1px solid #eee;padding:3px">
																	<span>{{transDecideManage("captions.authorizer")}}</span>
																</div>
																<div style="width:100%;border-bottom: 1px solid #eaeaea;padding:3px;">
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
                                    <td class="custom-td-label1" style="width: 15%">
                                    {{transDecideManage("captions.docNumber")}}
                                    </td>
                                    <td class="custom-td-text1 center" colspan="3">
                                            {{ $reportinfo['docNo'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">
                                    {{transDecideManage("captions.departName")}}
                                    </td>
                                    <td class="custom-td-text1" colspan="3">
                                        <label class="form-control">{{$userInfo['unit']}}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">
                                    {{transDecideManage("captions.approver")}}
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">{{$user['name']}}</label>
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">{{$userInfo['pos']}}</label>

                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">@if($user['isAdmin']==1) 管理者 @else
                                                一般使用者 @endif</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">{{transDecideManage("captions.draftDate")}}</td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control"{{$reportinfo['draftDate']}}</label>
                                    </td>
                                    <td class="custom-td-dec-text" style="text-align: right;">{{transDecideManage("captions.savePeriod")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text">
                                        <select id="saveYears" name="saveYears">
                                            @for($i=1; $i<23; $i++)
                                                <option value="{{$i}}" @if($i == $reportinfo['storage']) selected @endif>@if($i<13){{$i}}월@else{{$i-12}}년@endif</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">{{transDecideManage("captions.sendDepartment")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text" colspan="6">
                                        <input type="text" name="sendOrgans" class="form-control" style="width:100%"
                                               value="{{$reportinfo['submitUnit']}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">{{transDecideManage("captions.approveName")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="text" name="decTitle" id="decTitle" class="form-control" style="width: 100%"
                                               value="{{$reportinfo['title']}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-dec-text" colspan="6">
                                        <div class="alert alert-info" id="decidealert" style="height: 20px;display: none">
                                        {{transDecideManage("captions.input_content_msg")}}
                                        </div>
                                        <textarea name="comment" class="form-control" style="height:310px">
                                            {{$reportinfo['content']}}
                                        </textarea>
                                    </td>
                               </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">{{transDecideManage("captions.attachFile")}}1</td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="file" name="attachFile1" id="attachFile1" style="display: none;">
                                        <button type="button" id="openFile1"><i class="icon-folder-open-alt"></i>{{transDecideManage("captions.selectFile")}}</button>
                                        <label style="margin-left:10px" id="labFile1">{{$reportinfo['fileName1']}}</label>
                                        <input class="hidden" id="fileName1" name="fileName1" value="{{$reportinfo['fileName1']}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">{{transDecideManage("captions.attachFile")}}2</td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="file" name="attachFile2" id="attachFile2" style="display: none">
                                        <button type="button" id="openFile2"><i class="icon-folder-open-alt"></i>{{transDecideManage("captions.selectFile")}}</button>
                                        <label style="margin-left:10px" id="labFile2">{{$reportinfo['fileName2']}}</label>
                                        <input class="hidden" id="fileName2" name="fileName2" value="{{$reportinfo['fileName2']}}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm btn-save" style="float:right"><i class="icon-inbox"></i>
                        {{transDecideManage("captions.put_to_process_msg")}}
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="space-10"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var token = '{!! csrf_token() !!}';
        var stampCount = "{{count($decidedInfos)}}" * 1;

        $(function () {

            if(stampCount > 1) {
                var width = 140 * stampCount + 10;
                var td_width = $(".td-stamp").width();
                $('.td-stamp').css('width', td_width + 'px');
                if(td_width < width) {
                    var attr = width + 'px';
                    $('#stamp-list').css('width', attr);
                }
            }

            editor = KindEditor.create('textarea[name="comment"]', {
                cssPath: '/KindEditor/plugins/code/prettify.css',
                newlineTag: 'br',
                allowPreviewEmoticons: false,
                allowImageUpload: false,
                items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'link']
            });

            $("#validation-form").validate({
                rules: {
                    sendOrgans: "required",
                    decTitle  : "required",
                    comment:    "required",
                },
                messages: {
                    sendOrgans: "请输入发送部门",
                    decTitle: "请输入批准名称。",
                    comment: "请输入批准内容。",
                }
            });

			$('.btn-save').on('click', function() {
				$('#submit').trigger('click');
			});

            $('#openFile1').click(function () {
                $('#attachFile1').trigger('click');
            });

            $('#openFile2').click(function () {
                $('#attachFile2').trigger('click');
            });

            $('#attachFile2').change(function () {
                $('#labFile2').html($(this).val());
                $('#fileName2').val($(this).val());
            });

            $('#attachFile1').change(function () {
                $('#labFile1').html($(this).val());
                $('#fileName1').val($(this).val());
            });
        });


    </script>
@stop