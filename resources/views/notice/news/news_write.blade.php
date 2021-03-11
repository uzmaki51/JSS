@extends('layout.sidebar')

@section('content')

    <script src="/KindEditor/kindeditor.js"></script>
    <script src="/KindEditor/lang/zh_CN.js"></script>
    <script src="/KindEditor/plugins/code/prettify.js"></script>

    <div class="main-content" xmlns="http://www.w3.org/1999/html">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-6">
                    <h4><b>公告栏</b>
                        <small>
                            <i class="icon-double-angle-right"></i>各讨论场合
                            <i class="icon-double-angle-right"></i>做成新消息
                        </small>
                    </h4>
                </div>
                <div class="col-md-6">
                    <h5 style="float: right"><a href="javascript:history.back();"><strong>上一个页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="widget-header" style="min-height: 70px">
                        <div class="col-md-5">
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <strong>讨论场合:</strong>
                                </div>
                                <div class="col-sm-6">
                                    <strong> {{ $tema['tema'] }}</strong>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <strong>做成日期:</strong>
                                </div>
                                <div class="col-sm-6">
                                    <strong> {{ $tema['create_at'] }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7"  style="margin-top: 10px">
                            <div><strong>说明</strong></div>
                            <div style="padding-top:5px">{{ $tema['descript'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="widget-box">
                        <div class="widget-header" style="min-height: 30px">
                            <h5>@if(empty($news)) 添加消息 @else 修改消息 @endif</h5>
                            <div class="widget-toolbar">
                                <button class="btn btn-grey btn-xs radius-3" onclick="submitNewsContent()" style="width: 80px"><i class="icon-save"></i>登记</button>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main" style="padding:20px 10%">
                                <form class="form-horizontal" role="form" method="POST" action="/notice/createNewsContent"
                                      enctype="multipart/form-data" id="validation-form">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="temaId" value="{{$tema['id']}}">
                                    <input type="hidden" name="newsId" value="@if(isset($news)){{$news->id}}@endif">
                                    <input type="hidden" name="deletedFile" value="0">

                                    <div class="form-group" style="height: 35px">
                                        <label class="col-md-1 control-label no-padding-right">题目:</label>
                                        <div class="col-md-10"><input type="text" name="newstitle" class="form-control" value="@if(isset($news)){{$news->title}}@endif"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-1 control-label no-padding-right">内容:</label>
                                        <div class="col-md-10"><textarea id="news-content" name="newscontent" class="form-control" style="height:210px">@if(isset($news)){{$news->content}}@endif</textarea></div>
                                    </div>
                                    <div class="form-group"  style="height: 20px">
                                        <label class="col-md-1 control-label no-padding-right">附件:</label>
                                        @if(isset($news) && (!empty($news->fileName)))
                                        <div id="news_file" style="padding-top: 5px">
                                            <label class="col-md-3">
                                                <a href="/fileDownload?type=news&path={{$news['filePath']}}" style="font-size: 14px">{{ $news->fileName }}</a>
                                                <a href="javascript:void(0)" class="btn btn-minier del_file btn-yellow" style="border-radius: 12px;"><i class="red icon-remove bigger-120"></i></a>
                                            </label>
                                        </div>
                                        @endif
                                        <div class="col-md-4">
                                            <input type="file" id="input-file" name="addfile" value="@if(isset($news)){{$news->fileName}}@endif">
                                        </div>
                                    </div>
                                    <button id="submit-btn" type="submit" style="display: none"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-2"></div>
        </div>
    </div>

    <script type="text/javascript">
        function submitNewsContent() {
            $('#submit-btn').trigger('click');
        }

        jQuery(function($) {

            $('#input-file').ace_file_input({
                no_file:'选择文件 ...',
                btn_choose:'选择',
                btn_change:'修改',
                droppable:false,
                onchange:'selectFile',
                thumbnail:false //| true | large
                //whitelist:'gif|png|jpg|jpeg'
                //blacklist:'exe|php'
                //onchange:''
                //
            });

            editor = KindEditor.create('textarea[name="newscontent"]', {
                cssPath : '/KindEditor/plugins/code/prettify.css',
                newlineTag : 'br',
                allowPreviewEmoticons : false,
                allowImageUpload : false,
                items : ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'link']
            });

            $("#validation-form").validate({
                rules: {
                    newstitle: "required",
                    newscontent: "required",
                },
                messages: {
                    newstitle: "请输入题目。",
                    newscontent: "请填写内容。",
                }
            });

            $('.del_file').on('click', function () {
                $('#news_file').html('');
                $('[name=deletedFile]').val(1);
            })

        });

        function selectFile() {

        }
    </script>
@stop