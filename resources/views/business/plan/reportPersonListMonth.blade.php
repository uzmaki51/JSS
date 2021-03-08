@if(!isset($excel))

<div class="space-6"></div>
<div>
<div class="col-md-6" style="padding:0px; padding-left:5px">
    <select class="col-md-2 select-date" id="cur_year_week" onchange="ShowLists('month', 0)">
        @for($i=2014; $i < date('Y') + 5; $i++)
            <option value="{{$i}}" @if($i == $cur_date['selYear']) selected @endif>{{$i}}</option>
        @endfor
    </select>
    <span class="col-md-2" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
</div>
<div style="padding-right:5px; float:right;">
    <button class="btn btn-xs btn-warning no-radius" style="width :80px" onclick="ShowListsExcel('month', '{{date('Y')}}')">
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
    <thead>
        <tr class="black br-hblue">
            <th class="center" style="width: 5%">{{transBusinessManage("captions.everyweek")}}</th>
            <th class="center" style="width: 5%">{{transBusinessManage("captions.inputtime")}}</th>
            <th class="center" style="width: 40%">{{transBusinessManage("captions.plain")}}</th>
            <th class="center" style="width: 50%">{{transBusinessManage("captions.report")}}</th>
@if(!isset($excel))
            <th class="center" style="width: 40px"></th>
@endif
        </tr>
    </thead>
    <tbody>
        @foreach($main_plans as $plan)
            <tr style="height: 30px; @if(($cur_date['month'] == $plan['planMonth']) && ($cur_date['year'] == $plan['planYear'])) background-color: #b8d6ff; @endif">
@if(!isset($excel))
                <input type="hidden" class="plan_id" value="{{$plan['id']}}">
                <input type="hidden" class="plan_year" value="{{$plan['planYear']}}">
                <input type="hidden" class="plan_month" value="{{$plan['planMonth']}}">
                <input type="hidden" class="plan_summary" value="{{$plan['plan']}}">
                <input type="hidden" class="report_summary" value="{{$plan['report']}}">
@endif
                <td class="center">{{$plan['planYear']}}{{transBusinessManage("captions.year")}}  {{$plan['planMonth']}}{{transBusinessManage("captions.month")}}</td>
                <td class="center">{!! convert_datetime($plan['update_at']) !!}</td>
                <td class="plan" style="vertical-align: top;">
                    {!! nl2br($plan['plan']) !!}
                </td>
                <td class="report" style="vertical-align: top;">
                    {!! nl2br($plan['report']) !!}
                </td>
@if(!isset($excel))
                <td class="center control action-buttons">
                    <a class="month-action-button" href="javascript:void(0);">
                        <i class="blue icon-edit bigger-130"></i>
                    </a>
                    <a class="month-save-button" href="javascript:void(0);" style="display:none;">
                        <i class="red icon-save bigger-130"></i>
                    </a>
                </td>
@endif
            </tr>
        @endforeach
    </tbody>
</table>

@if(!isset($excel))
<script type="text/javascript">
    jQuery (function($){
        $("th").addClass("table-head");

        // 월보...
        $('.month-action-button').on('click', function(){
            var row = $(this).closest('tr');
            var selectedRow = $('.selectedMonth');
            var childCount = selectedRow.children().length;
            if(childCount > 0) {
                selectedRow.find('.plan').text(selectedRow.find('.plan_summary').val());
                selectedRow.find('.report').text(selectedRow.find('.report_summary').val());
                selectedRow.find('.month-action-button').css('display', 'block');
                selectedRow.find('.month-save-button').css('display', 'none');
                selectedRow.removeClass('selectedMonth');
            }
            row.addClass('selectedMonth');
            row.find('.plan').html("<textarea rows='10' class='plan_input' style='width:100%;'>"+ row.find('.plan_summary').val() +"</textarea>");
            row.find('.report').html("<textarea rows='10' class='report_input' style='width:100%;'>"+ row.find('.report_summary').val() +"</textarea>");
            row.find('.month-action-button').css('display','none');
            row.find('.month-save-button').css('display','block');
        });
        $('.month-save-button').on('click', function(){
            var row = $(this).closest('tr');
            var newplan = row.find('.plan_input').val();
            var newreport = row.find('.report_input').val();
            var id = row.find('.plan_id').val();
            var planYear = row.find('.plan_year').val();
            var planMonth = row.find('.plan_month').val();

            $.post('reportPersonUpdateMonthList', {
                '_token': token, 'plan': newplan, 'report': newreport,'month':planMonth,'year':planYear,'reportId':id
            }, function (data) {
                $('#ReportPersonMonth').html(data);
                $.gritter.add({
                    title: '成功',
                    text: '登录月报成功！',
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