<table id="decide_info_table" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th style="text-align: center;width: 60px">{{transDecideManage("captions.no")}}</th>
        <th class="center" style="width:70px">{{transDecideManage("captions.attachFile")}}</th>
        <th class="center" style="width:80px">{{transDecideManage("captions.processState")}}</th>
        <th class="center">{{transDecideManage("captions.approveName")}}</th>
        <th class="center" style="width:15%">{{transDecideManage("captions.approveProcessName")}}</th>
        <th class="center" style="width:100px">{{transDecideManage("captions.approver")}}</th>
        <th class="center" style="width:125px">{{transDecideManage("captions.approveDate")}}</th>
    </tr>
    </thead>
    <tbody>
    @if (count($list) > 0)
        <?php $index = ($page - 1) * 10 + 1; ?>
        @foreach ($list as $reportinfo)
            <tr>
                <td class="center">{{$index++}}</td>
                <td class="center">
                    @if(!empty($reportinfo->file1))
                        <a href="/fileDownload?type=report&path={{$reportinfo->file1}}&filename={{$reportinfo->fileName1}}" class="hide-option" title=" {{$reportinfo->fileName1}} ">
                            <i class="icon-file bigger-125"></i>
                        </a>
                    @endif
                    @if(!empty($reportinfo->file2))
                        <a href="/fileDownload?type=report&path={{$reportinfo->file2}}&filename={{$reportinfo->fileName2}}" class="hide-option" title=" {{$reportinfo->fileName2}} ">
                            <i class="icon-file bigger-125"></i>
                        </a>
                    @endif
                </td>
                <td class="center">
                    <div class="progress progress-striped" data-percent="{{$reportinfo->decideCount}}/{{$reportinfo->totalCount}}">
                        <?php $rate = ($reportinfo->decideCount / $reportinfo->totalCount) * 100; ?>
                        <div class="progress-bar progress-bar-success" style="width:{{$rate}}%;"></div>
                    </div>
                </td>
                <td>
                    <a href="decideShow?reportId={{$reportinfo->id}}">{{$reportinfo->title}}</a>
                </td>
                <td class="text-center">
                    {{$reportinfo->flowTitle}}
                </td>
                <td class="center">
                    {{$reportinfo->realname}}
                </td>
                <td class="center">
                    {!! convert_datetime($reportinfo->draftDate) !!}
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
{!! $paginate !!}

