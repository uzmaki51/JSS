<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 col-sm-offset-3">
            <select class="col-md-2" id="week_year" onchange="ShowLists('week')">
                @for($i=2017; $i<2025; $i++)
                    <option value="{{$i}}" @if($i==$cur_date['year']) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
            <select class="col-md-2" id="week_month" onchange="ShowLists('week')">
                @for($i=1; $i<13; $i++)
                    <option value="{{$i}}" @if($i==$cur_date['month']) selected @endif >{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
            <select class="col-md-4" id="cur_week" onchange="ShowLists('week')">
                @foreach($weekList as $week)
                    <option value="{{$week['week']}}" @if($week['week'] == $cur_date['week']) selected @endif>{{$week['title']}}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-xs btn-warning no-radius" style="float: right;margin-right: 30px; width :80px" onclick="ShowListsExcel('week')">
            <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
        </button>
    </div>
    <div class="col-md-12">
        <div class="space-10"></div>
        <div class="row" style="margin-left: -1px; margin-right: 2px">
            <div id="UnitWeekReport">
                @include('business.plan.per_unit_week_table', with(['list'=>$list]))
            </div>
        </div>
    </div>
</div>