@if(!isset($excel))

<div class="space-6"></div>
<div>
<div class="col-md-6" style="padding:0px; padding-left:5px">
    <select class="col-md-2 select-date" id="cur_year_week" value="{{$cur_date['year']}}" onchange="ShowLists('week', 0)">
        @for($i=2014; $i < date('Y') + 5; $i++)
            <option value="{{$i}}" @if($i==$cur_date['year']) selected @endif>{{$i}}</option>
        @endfor
    </select>
    <span class="col-md-2" style="padding-top:5px">{{transBusinessManage("captions.month")}}{{transBusinessManage("captions.year")}}</span>
    <select class="col-md-1 select-date" id="cur_month_week" value="{{$cur_date['month']}}" onchange="ShowLists('week', 0)">
        @for($i=1; $i<13; $i++)
            <option value="{{$i}}" @if($i==$cur_date['month']) selected @endif >{{$i}}</option>
        @endfor
    </select>
    <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
</div>
<div style="padding-right:5px; float:right;">
    <button class="btn btn-xs btn-warning no-radius" style="width :80px" onclick="ShowListsExcel('week', '{{date('Y')}}')">
        <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
    </button>
</div>
</div>
<div class="space-6" style="width:100%"></div>

@else
    @include('layout.excel-header-include')
    @include('layout.excel-style')
@endif

<table id="PersonWeekTable" class="table table-bordered table-hover">
    <thead class="table-head">
        <tr class="black br-hblue">
            <th style="width: 170px;">{{transBusinessManage("captions.everyweek")}}</th>
            <th class="center" style="width: 110px;">{{transBusinessManage("captions.inputtime")}}</th>
            <th class="center">{{transBusinessManage("captions.plain")}}</th>
            <th class="center">{{transBusinessManage("captions.report")}}</th>
@if(!isset($excel))
            <th style="width:40px"></th>
@endif
        </tr>
    </thead>
    <tbody>
        @foreach($main_plans as $plan)
            <tr style="height: 30px; @if(($cur_date['week'] == $plan['planWeek']) && ($cur_date['year'] == $plan['planYear'])) background-color: #b8d6ff; @endif">
@if(!isset($excel))
                <input type="hidden" class="plan_id" value="{{$plan['id']}}">
                <input type="hidden" class="plan_year" value="{{$plan['planYear']}}">
                <input type="hidden" class="plan_month" value="{{$plan['planWeek']}}">
                <input type="hidden" class="plan_summary" value="{{$plan['plan']}}">
                <input type="hidden" class="report_summary" value="{{$plan['report']}}">
@endif
                <td class="center">{{ $plan['dateStr'] }}</td>
                <td class="center">{!! convert_datetime($plan['update_at']) !!}</td>
                <td class="plan" style="vertical-align: top;">
                        {!! nl2br($plan['plan']) !!}
                </td>
                <td class="report" style="vertical-align: top;">
                        {!! nl2br($plan['report']) !!}
                </td>
@if(!isset($excel))
                <td class="center control action-buttons">
                    <a class="week-action-button" href="javascript:void(0);">
                        <i class="blue icon-edit bigger-130"></i>
                    </a>
                    <a class="week-save-button" href="javascript:void(0);" style="display:none;">
                        <i class="red icon-save bigger-130"></i>
                    </a>
                </td>
@endif
            </tr>
        @endforeach
        </tbody>
</table>

@if(!isset($excel))
<script>

    $(function(){
        // 주보...
        $('.week-action-button').on('click', function(){
            var row = $(this).closest('tr');
            var selectedRow = $('.selectedWeek');
            var childCount = selectedRow.children().length;
            if(childCount > 0) {
                selectedRow.find('.plan').text(selectedRow.find('.plan_summary').val());
                selectedRow.find('.report').text(selectedRow.find('.report_summary').val());
                selectedRow.find('.week-action-button').css('display', 'block');
                selectedRow.find('.week-save-button').css('display', 'none');
                selectedRow.removeClass('selectedWeek');
            }
            row.addClass('selectedWeek');
            row.find('.plan').html("<textarea rows='10' class='plan_input' style='width:100%;'>"+ row.find('.plan_summary').val() +"</textarea>");
            row.find('.report').html("<textarea rows='10' class='report_input' style='width:100%;'>"+ row.find('.report_summary').val() +"</textarea>");
            row.find('.week-action-button').css('display','none');
            row.find('.week-save-button').css('display','block');
        });

        $('.week-save-button').on('click', function(){
            var row = $(this).closest('tr');
            var newplan = row.find('.plan_input').val();
            var newreport = row.find('.report_input').val();
            var id = row.find('.plan_id').val();
            var cur_year_week = row.find('.plan_year').val();
            var cur_month_week = row.find('.plan_month').val();

            alert(newreport);
            $.post('reportPersonUpdateWeekList', {
                '_token': token, 'plan': newplan, 'report': newreport,'week':cur_month_week,'year':cur_year_week,'reportId':id
            }, function (data) {
                $('#ReportPersonWeek').html(data);
                $.gritter.add({
                    title: '成功',
                    text: '登记周报成功！',
                    class_name: 'gritter-success'
                });
            });
        });
    });
</script>
@else
    </body>
</html>
@endif
