@extends('layout.sidebar')

@section('content')
    <div class="main-content">

        <style>
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
                            {{transDecideManage("title.DocReading")}}
                        </small>
                    </h4>
                </div>
                <div class="col-md-offset-6 col-md-3" style="height: 30px">
                    <h5 style="float: right"><a href="javascript: history.back()"><strong>{{transDecideManage("captions.prevPage")}}</strong></a></h5>
                </div>
            </div>

            <?php if ($reportInfo['flowState'] == 1): ?>
                <div class="row-fluid">
                    <a href="{{ url("/decision/decideShow?reportId={$reportInfo['id']}&download") }}" class="btn btn-xs btn-default" style="display: none;">
                        <i class="icon-download-alt"></i>{{transDecideManage("captions.receiveDoc")}}
                    </a>
                </div>
                <div class="space-2"></div>
            <?php endif; ?>

            <div class="col-md-12">
                <div class="row">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="custom-td-label1" style="width: 15%">
                                {{transDecideManage("captions.docNumber")}}
                                </td>
                                <td style="width: 35%" colspan="3">
                                    {{$reportInfo['docNo']}}
                                </td>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.approveKind")}}
                                </td>
                                <td class="custom-td-text1" colspan="3">
                                    {{ g_enum('ReportTypeLabelData')[$reportInfo['flowid']][0] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.departName")}}
                                </td>
                                <td class="custom-td-text1" colspan="3">
                                    {{$creator['unit']}}
                                </td>
                                <td class="custom-td-label1" style="width: 15%">
                                    收支分类
                                </td>
                                <td style="width: 35%" colspan="3">
                                    {{$reportInfo['acName']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.approver")}}
                                </td>
                                <td style="width: 15%">
                                    <label class="form-control">{{$creator['name']}}</label>
                                </td>
                                <td class="custom-td-text1" style="width: 10%">
                                    <label class="form-control">{{$creator['pos']}}</label>

                                </td>
                                <td class="custom-td-text1" style="width: 10%">
                                    <label class="form-control">@if($creator['isAdmin']==1) 管理者 @else
                                            一般使用者 @endif</label>
                                </td>
                                <td class="custom-td-label1" style="width: 15%">
                                {{transDecideManage("captions.no")}}船名
                                </td>
                                <td style="width: 35%" colspan="3">
                                    {{$reportInfo['shipName']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.draftDate")}}
                                </td>
                                <td class="custom-td-text1 center">
                                    {!! convert_datetime($reportInfo['create_at']) !!}
                                </td>
                                <td class="custom-td-text1">
                                {{transDecideManage("captions.savePeriod")}}
                                </td>
                                <td class="custom-td-text1">
                                    @if($reportInfo['storage'] < 13)
                                        {{$reportInfo['storage']}}月
                                    @else
                                        {{$reportInfo['storage'] - 12}}年
                                    @endif
                                </td>
                                <td class="custom-td-label1" style="width: 15%">
                                {{transDecideManage("captions.no")}}金额
                                </td>
                                <td style="width: 35%" colspan="3">
                                    {{$reportInfo['amount']}} {{ g_enum('CurrencyLabel')[$reportInfo['currency']] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.receiver")}}
                                </td>
                                <td class="custom-td-text1" colspan="6">
                                    {{$reportInfo['recvUser']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">
                                {{transDecideManage("captions.approveName")}}
                                </td>
                                <td class="custom-td-text1" colspan="5">
                                    {{$reportInfo['title']}}
                                </td>
                            </tr>
                            <tr>
                                <td class="custom-td-label1">{{transDecideManage("captions.authContent")}}</td>
                                <td class="custom-td-text1" colspan="5">
                                    <div style="padding:10px">{!! $reportInfo['content'] !!}</div>

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
                <div class="row">
                    <table id="decide-note-table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="custom-td-label1">
                            <td class="center">{{transDecideManage("captions.no")}}</td>
                            <td class="center">{{transDecideManage("captions.authorizer")}}</td>
                            <td class="center">{{transDecideManage("captions.authorState")}}</td>
                            <td class="center">{{transDecideManage("captions.authorDate")}}</td>
                            <td class="center">{{transDecideManage("captions.authorOpinion")}}</td>
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
            </div>
        </div>
    </div>

    <script>

        function showNoteDetail(id) {
            var tag = document.getElementById('show_detail' + id);
            var content = tag.getAttribute("note");

            bootbox.alert("意见:&nbsp;&nbsp;" + content, function(result) {
            });
        }

        $(function() {
            var count = '{!! count($decidedInfos) !!}' * 1;
            var width = 140 * count + 10;
            var td_width = $("#stamp_view").width();
            if(td_width < width) {
                var attr = width + 'px';
                $('#stamp-list').css('width', attr);
            }
        });

    </script>
@stop