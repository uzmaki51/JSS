<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4 col-sm-offset-4">
            <select class="col-md-3" id="cur_year_week" value="{{$cur_date['year']}}" onchange="ShowLists('week')">
                @for($i=2017; $i<2025; $i++)
                    <option value="{{$i}}" @if($i==$cur_date['year']) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
            <select class="col-md-2" id="cur_month_week" value="{{$cur_date['month']}}" onchange="ShowLists('week')">
                @for($i=1; $i<13; $i++)
                    <option value="{{$i}}" @if($i==$cur_date['month']) selected @endif >{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
        </div>
        <button class="btn btn-xs btn-warning no-radius" style="float: right;margin-right: 30px; width :80px" onclick="ShowListsExcel('week')">
            <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
        </button>
    </div>
    <div class="col-md-12">
        <div class="space-10"></div>
        <div class="row" style="margin-left: -1px; margin-right: 2px">
            <div id="UnitWeekReport">
                @include('business.plan.unit_week_read_table', with(['list'=>$list]))
            </div>
        </div>
    </div>
</div>