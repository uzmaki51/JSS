<div class="space-10"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-5 col-sm-offset-4">
            <select class="col-md-3" id="cur_year" onchange="ShowLists('month')">
                @for($i=2017; $i<2025; $i++)
                    <option value="{{$i}}" @if($i == $curDate['year']) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.year")}}</span>
            <select class="col-md-2" id="cur_month" onchange="ShowLists('month')">
                @for($i=1; $i<13; $i++)
                    <option value="{{$i}}" @if($i == $curDate['month']) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <span class="col-md-1" style="padding-top:5px">{{transBusinessManage("captions.month")}}</span>
        </div>
        <button class="btn btn-xs btn-warning no-radius" style="float: right;margin-right: 30px; width :80px" onclick="ShowListsExcel('month')">
            <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
        </button>
    </div>
    <div class="col-md-12">
        <div class="space-10"></div>
        <div class="row" style="margin-left: -1px; margin-right: 2px">
            <div id="UnitMonthReport">
                @include('business.plan.per_unit_week_table', with(['list'=>$list]))
            </div>
        </div>
    </div>
</div>