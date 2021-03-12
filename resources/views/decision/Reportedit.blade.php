@extends('layout.sidebar')

@section('content')
    <div class="main-content">

        <script src="/KindEditor/kindeditor.js"></script>
        <script src="/KindEditor/lang/zh_CN.js"></script>
        <script src="/KindEditor/plugins/code/prettify.js"></script>

        <style>
            /* .page-content * {
                border: unset!impor
            } */
            .table tr {
                height: 24px;
            }
            .table tbody > tr > td {
                font-size: 13px!important;
            }
            .table tbody > tr > .custom-td-label1 {
                padding: 0 4px!important;
                height: auto!important;
            }
            .table tbody > tr > .custom-td-text1, .table tbody > tr > .custom-td-dec-text {
                padding: 0!important;
            }

            .form-control {
                padding: 4px!important;
                border-radius: 0!important;
                border: unset!important;
                font-size: 14px!important;
                line-height: 1!important;
            }
            .chosen-single {
                padding: 4px!important;
                border-radius: 0!important;
                border: unset!important;
                font-size: 14px!important;
                line-height: 1!important;
            }
            .input-group-addon {
                font-size: 14px!important;
                padding: 0 4px!important;
                border: unset!important;
                line-height: 1!important;
            }
            .btn {
                height: 25px!important;
                font-size: 13px;
            }
        </style>

        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-6">
                    <h4><b>{{transDecideManage("title.Drafting")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            @if(isset($reportinfo))
                                修改起草书
                            @else
                                做成新起草书
                            @endif
                        </small>
                    </h4>
                </div>
                <div class="col-sm-6">
                    <h5 style="float: right"><a href="javascript: history.back()"><strong>{{transDecideManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-8">
                        <form role="form" method="POST" action="{{url('decision/Reportsave')}}" enctype="multipart/form-data" id="validation-form">
                            <input type="submit" class="hidden" id="submit">
                            <input type="text" name="reportId" class="hidden" value="@if(isset($reportinfo)) {{$reportinfo['id']}} @else 0 @endif">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="tempBox">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">
                                        起草
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="flowid" class="form-control width-100">
                                            <option value="">请选择起草。</option>
                                            @foreach($flows as $key => $item)
                                                <option value="{{ $item['id'] }}" {{ isset($reportinfo) && $reportinfo['flowid'] == $item['id'] ? "selected" : "" }}>{{ $item['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">收支分类</td>
                                        <td class="custom-td-text1">
                                            <select name="fee_type" class="form-control width-100">
                                            <option value="">请选择收支分类。</option>
                                            @if(isset($reportinfo) && $reportinfo['flowid'] != 1)
                                                @foreach($acList as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($reportinfo) &&  $item->id == $reportinfo['profit_type'] ? "selected" : "" }}>{{ $item->AC_Item_En }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">
                                        金额
                                    </td>
                                    <td class="custom-td-text1" colspan="3">
                                        <div style="display: flex;">
                                            <input type="text" name="amount" style="display: inline-block;" class="form-control" value="{{ isset($reportinfo['amount']) ? $reportinfo['amount'] : '' }}">
                                            <select name="currency" class="form-control width-auto"  style="display: inline-block;">
                                                <option value="CNY" {{ isset($reportinfo) && $reportinfo['currency'] == "CNY" ? "selected" : "" }}>￥</option>
                                                <option value="USD" {{ isset($reportinfo) && $reportinfo['currency'] == "USD" ? "selected" : "" }}>$</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">
                                        船名
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="shipNo" class="form-control width-100">
                                            <option value="">请选择船舶。</option>
                                            @foreach($shipList as $key => $item)
                                                <option value="{{ $item['attributes']['shipID'] }}" {{ isset($reportinfo) && $item['attributes']['shipID'] == $reportinfo['shipNo'] ? "selected" : "" }}>{{ $item['attributes']['shipName_En'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">
                                        항차번호
                                    </td>
                                    <td class="custom-td-text1">
                                        <select name="shipNo" class="form-control width-100">
                                            <option value="">항차번호를 선택하십시오.</option>
                                            @foreach($shipList as $key => $item)
                                                <option value="{{ $item['attributes']['shipID'] }}" {{ isset($reportinfo) && $item['attributes']['shipID'] == $reportinfo['shipNo'] ? "selected" : "" }}>{{ $item['attributes']['shipName_En'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                    <td class="custom-td-label1" style="width: 15%">
                                    {{transDecideManage("captions.docNumber")}}
                                    </td>
                                    <td class="custom-td-text1 center" colspan="3">
                                        @if(isset($reportinfo))
                                            {{ $reportinfo['docNo'] }}
                                        @else
                                            {{ $report_num }}
                                        @endif
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr>
                                    <td class="custom-td-label1">
                                    {{transDecideManage("captions.departName")}}
                                    </td>
                                    <td class="custom-td-text1" colspan="3">
                                        <label class="form-control">{{$userInfo1['unit']}}</label>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr>
                                    <td class="custom-td-label1">
                                    {{transDecideManage("captions.approver")}}
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">{{$user['realname']}}</label>
                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">{{$userInfo1['pos']}}</label>

                                    </td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">@if($user['isAdmin']==1) 管理者 @else
                                                一般使用者 @endif</label>
                                    </td>
                                </tr>
                                -->
                                <tr>
                                    <td class="custom-td-label1">{{transDecideManage("captions.draftDate")}}</td>
                                    <td class="custom-td-dec-text">
                                        <label class="form-control">@if(isset($reportinfo)){{$reportinfo['draftDate']}}@else{!! date('Y/m/d') !!}@endif</label>
                                    </td>
                                    <!--
                                    <td class="custom-td-dec-text" style="text-align: right;">{{transDecideManage("captions.savePeriod")}} <span class="require">*</span></td>
                                    
                                    <td class="custom-td-dec-text">
                                        <select id="saveYears" name="saveYears">
                                            @for($i=1; $i<23; $i++)
                                                <option value="{{$i}}" @if(isset($reportinfo) && ($i == $reportinfo['storage'])) selected @elseif(empty($reportinfo) && ($i == 13)) selected @endif>@if($i<13){{$i}}月@else{{$i-12}}年@endif</option>
                                            @endfor
                                        </select>
                                    </td>
                                    -->
                                </tr>
                                <tr>
                                    <td class="custom-td-label1">{{transDecideManage("captions.approveName")}} <span class="require">*</span></td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="text" name="decTitle" id="decTitle" class="form-control" style="width: 100%"
                                            value="@if(isset($reportinfo)){{$reportinfo['title']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-dec-text" colspan="6">
                                        <div class="alert alert-info" id="decidealert" style="height: 20px;display: none">
                                        {{transDecideManage("captions.input_content_msg")}}
                                        </div>
                                        <textarea name="comment" class="form-control" style="height:160px">@if(isset($reportinfo)){{$reportinfo['content']}}@endif</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">{{transDecideManage("captions.attachFile")}}1</td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="file" name="attachFile1" id="attachFile1" style="display: none;">
                                        <button type="button" id="openFile1" class="btn btn-info"><i class="icon-folder-open-alt"></i>{{transDecideManage("captions.selectFile")}}</button>
                                        <label style="margin-left:10px" id="labFile1">@if(isset($reportinfo)){{$reportinfo['fileName1']}}@endif</label>
                                        <input class="hidden" id="fileName1" name="fileName1" value="@if(isset($reportinfo)){{$reportinfo['fileName1']}}@endif">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-td-label1" style="width:15%">{{transDecideManage("captions.attachFile")}}2</td>
                                    <td class="custom-td-dec-text" colspan="5">
                                        <input type="file" name="attachFile2" id="attachFile2" style="display: none">
                                        <button type="button" id="openFile2" class="btn btn-warning"><i class="icon-folder-open-alt"></i>{{transDecideManage("captions.selectFile")}}</button>
                                        <label style="margin-left:10px" id="labFile2">@if(isset($reportinfo)){{$reportinfo['fileName2']}}@endif</label>
                                        <input class="hidden" id="fileName2" name="fileName2" value="@if(isset($reportinfo)){{$reportinfo['fileName2']}}@endif">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm" id="btnPut2Flow" style="float: right"><i class="icon-inbox"></i>
                        {{transDecideManage("captions.put_to_process_msg")}}
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-default btn-sm" id="btnPut2Tmp" style="float: left"><i class="icon-inbox"></i>
                        {{transDecideManage("captions.save_to_draft_msg")}}
                        </button>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 40px;">
                    <div class="space-10"></div>
                </div>
            </div>
            <div id="modal-dialog" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header no-padding">
                            <div class="table-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                {{transDecideManage("captions.select_approve_process")}}
                            </div>
                        </div>
                        <div class="modal-body no-padding">
                            <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top flow-table">
                                <thead>
                                <tr>
                                    <th style="width:50px"></th>
                                    <th>{{transDecideManage("captions.approveProcessName")}}</th>
                                    <th>{{transDecideManage("captions.authorizer")}}</th>
                                    <th>{{transDecideManage("captions.receiver")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($flows as $flow)
                                    <tr>
                                        <td><input type="radio" name="flowSelect" value="{{$flow['id']}}"></td>
                                        <td>{{$flow['title']}}</td>
                                        <td>{{$flow['deciders']}}</td>
                                        <td>{{$flow['receivers']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer no-margin-top">
                            <button class="btn btn-sm btn-danger pull-left btn-confirm" data-dismiss="modal">
                                <i class="icon-check"></i>
                                {{transDecideManage("captions.confirm")}}
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            </div>
        </div>
    </div>
    <?php
    echo '<script>';
    echo 'var FeeTypeData = ' . json_encode(g_enum("FeeTypeData")) . ';';
    echo '</script>';
    ?>
    <script type="text/javascript">

        var report_num = '{!! $report_num !!}';
        var token = '{!! csrf_token() !!}';
        var stampCount = '@if(isset($reportinfo) && isset($reportinfo['deciders'])){!!count($reportinfo['deciders'])!!}@endif' * 1;
        var state = '{!! $state !!}';

        $(function () {
            if(state == 'error') {
                var msg = '{!! $msg !!}';
                $.gritter.add({
                    title: '错误',
                    text: msg,
                    class_name: 'gritter-error'
                });
            }

            $('select[name=flowid]').on('change', function() {
                if($(this).val() == 1) {
                    $('select[name=fee_type]').attr('disabled', 'disabled');
                    $('select[name=currency]').attr('disabled', 'disabled');
                    $('input[name=amount]').attr('disabled', 'disabled');
                } else {
                    $('select[name=fee_type]').removeAttr('disabled', 'disabled');
                    $('select[name=currency]').removeAttr('disabled', 'disabled');
                    $('input[name=amount]').removeAttr('disabled', 'disabled');
                }
            });

            if(stampCount > 1) {
                var width = 140 * stampCount + 10;
                var td_width = $(".td-stamp").width();
                $('.td-stamp').css('width', td_width + 'px');
                if(td_width < width) {
                    var attr = width + 'px';
                    $('#stamp-list').css('width', attr);
                }
            }

            // editor = KindEditor.create('textarea[name="comment"]', {
            //     cssPath: '/KindEditor/plugins/code/prettify.css',
            //     newlineTag: 'br',
            //     allowPreviewEmoticons: false,
            //     language: 'cn',
            //     allowImageUpload: false,
            //     items: ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            //         'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            //         'insertunorderedlist', '|', 'emoticons', 'link']
            // });

            $("#validation-form").validate({
                rules: {
                    decTitle : "required",
                    flowid : "required",
                    shipNo : "required",
                },
                messages: {
                    decTitle: "请输入批准名称。",
                    flowid: "请选择起草。",
                    shipNo: "请选择船舶。",
                }
            });

            $('td input:radio').on('click' , function() {
                var that = this;
                if(that.checked == true) {
                    var flowId = $(this).val();
                    $('[name=flowid]').val(flowId);
                    $.post('getDecidersStamp', {'_token':token, 'flowId':flowId}, function (data) {
                        if(data) {
                            var stamp = jQuery.parseJSON(data);
                            $('#stamp-list').html(stamp.html);
                            var width = 140 * stamp.count + 10;
                            var td_width = $("#td-stamp").width();
                            if(td_width < width) {
                                var attr = width + 'px';
                                $('#stamp-list').css('width', attr);
                            }
                        }
                    });
                }
            });


            $('#btnPut2Flow').click(function () {
                var flowId = $('[name=flowid]').val();
                if (flowId == "") {
                    alert("请选择批准流程。");
                } else {
                    if(flowId != 1) {
                        if(!validateForm())
                            return;
                    }
                    $('[name=tempBox]').val('flow');
                    $('#submit').trigger('click');
                }
            });

            $('#btnPut2Tmp').click(function () {
                var flowId = $('[name=flowid]').val();
                if (flowId == "") {
                    alert("请选择批准流程。");
                } else {
                    $('[name=tempBox]').val('temp');
                    $('#submit').trigger('click');
                }
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


        $('[name=flowid]').on('change', function() {
            let value = $(this).val();
            $.ajax({
                url: BASE_URL + '/decision/getACList',
                type: 'POST',
                data: {
                    type: value
                },
                success: function(result, status, xhr) {
                    let data = result;
                    $('[name=fee_type]').empty();
                    data.forEach(function(value, key) {
                        $('[name=fee_type]').append('<option value=' + value['id'] + '>' + value['AC_Item_En'] + '</option>')
                    })
                    console.log(result);
                }
            })

        });

        function validateForm() {
            let attachFile1 = $('#attachFile1').val();
            let attachFile2 = $('#attachFile2').val();
            let fee_type = $('[name=fee_type]').val();
            if(attachFile1 == "" && attachFile2 == "") {
                alert('请选择附件。');
                return false;
            }


            if(fee_type == "") {
                alert('请选择收支分类。');
                return false;
            }

            return true;
        }
    </script>
@stop