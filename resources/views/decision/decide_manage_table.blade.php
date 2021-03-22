<table id="decide_info_table" class="table table-bordered">
    <thead>
    <tr class="black br-hblue">
        <th>{{ trans('decideManage.table.no') }}</th>
        <th>{{ trans('decideManage.table.type') }}</th>
        <th>{{ trans('decideManage.table.date') }}</th>
        <th>{{ trans('decideManage.table.shipName') }}</th>
        <th>{{ trans('decideManage.table.voy_no') }}</th>
        <th>{{ trans('decideManage.table.profit_type') }}</th>
        <th>{{ trans('decideManage.table.content') }}</th>
        <th>{{ trans('decideManage.table.currency') }}</th>
        <th>{{ trans('decideManage.table.amount') }}</th>
        <th>{{ trans('decideManage.table.reporter') }}</th>
        <th>{{ trans('decideManage.table.attachment') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @if (count($list) > 0)
		<?php $index = 1?>
        @foreach ($list as $reportinfo)
            <tr>
                <td class="center">{{$index++}}</td>
                <td class="center">
                    <span class="badge badge-{{ g_enum('ReportTypeLabelData')[$reportinfo->flowid][1] }}">{{ $reportinfo->flowTitle }}</span>
                </td>
                <td class="center">
                    {{ _convertDateFormat($reportinfo->draftDate, 'Y-m-d') }}
                </td>
                <td class="center">
                    <a href="decideShow?reportId={{$reportinfo->id}}">{{ $reportinfo->shipName }}</a>
                </td>
                <td class="center">
                    {{ $reportinfo->voyNo }}
                </td>
                <td class="center">
                    {{ $reportinfo->profit_type }}
                </td>
                <td class="center">
                    {{ $reportinfo->content }}
                </td>
                <td class="center">
                    {{ $reportinfo->currency }}
                </td>
                <td class="center">
                    {{ $reportinfo->amount }}
                </td>
                <td class="center">
                    {{ $reportinfo->realname }}
                </td>
                <td class="center">
                    @if(!empty($reportinfo->file1))
                        <a href="/fileDownload?type=report&path={{$reportinfo->file1}}&filename={{$reportinfo->fileName1}}" class="hide-option"
                           @if(!empty($reportinfo->fileName1)) title="{{$reportinfo->fileName1}}" @endif>
                            <i class="icon-file bigger-125"></i>
                        </a>
                    @endif
                </td>
                <td class="center">
                    <i class="text-info icon-ok bigger-125 accept-btn" title="Accept"></i>
                    <i class="text-danger icon-remove bigger-125 reject-btn" title="Reject"></i>
                </td>

            </tr>
        @endforeach
    @endif
    </tbody>
</table>
{!! $paginate !!}

