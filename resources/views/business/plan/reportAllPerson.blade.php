<style>
    label > a {
        color : #4f99c6;
    }
    label > a:hover, label > a:focus {
        color: #1a659a;
        text-decoration: underline;
    }
</style>
<div>
    <div class="space-6"></div>
    <div>
        <table width="100%" border="0">
        <tr>
        <td width="20%" style="padding: 0px !important; padding-left:5px">
            <div style="text-align:left">
                <label style="padding:5px">{{transBusinessManage("captions.departmentName")}}:</label>
                <select id="search_unit" style="font-size:12px">
                    <option value="">{{transBusinessManage("captions.all")}}</option>
                    <option value="{{transBusinessManage('captions.companyname'}}">{{transBusinessManage("captions.companyname")}}</option>
                    <option value="{{transBusinessManage('captions.operate'}}">{{transBusinessManage("captions.operate")}}</option>
                    <option value="{{transBusinessManage('captions.tech'}}">{{transBusinessManage("captions.tech")}}</option>
                    <option value="{{transBusinessManage('captions.transport'}}">{{transBusinessManage("captions.transport")}}</option>
                </select>
                {{--<input type="text" class="form-control" id="search_unit" style="width:100%" placeholder="全部" value="@if(!empty($unit)) {{$unit}} @endif">--}}

            </div>
        </td>
        <td width="25%" style="padding: 0px !important;">
            <div>
                <label style="float:left;padding:5px">{{transBusinessManage("captions.date")}}:</label>
                <div class="input-group col-md-8" style="padding-left:5px; text-align:left;">
                    <input class="form-control date-picker" id="search-date" type="text" data-date-format="yyyy-mm-dd" value="@if(empty($selDate)) {{date('Y-m-d')}} @else {{ $selDate }} @endif">
                    <span class="input-group-addon"><i class="icon-calendar bigger-110"></i></span>
                </div>
            </div>
        </td>
        <td align="center" style="padding: 0px !important;width:250px">
            <span style="text-align: center;padding-top: 5px;">
                <label>
                    <a href="javascript:showReportPrevDate()">
                        <i class="icon-chevron-left"></i>
                        <i class="icon-chevron-left"></i>&nbsp;&nbsp;{{transBusinessManage("captions.prev")}}&nbsp;&nbsp;|
                    </a>

                    <a href="javascript:showReportCurrDate()">&nbsp;&nbsp;{{transBusinessManage("captions.today")}}&nbsp;&nbsp;
                    </a>
                    <a href="javascript:showReportNextDate()">|&nbsp;&nbsp;{{transBusinessManage("captions.next")}}&nbsp;&nbsp;
                        <i class="icon-chevron-right"></i>
                        <i class="icon-chevron-right"></i>
                    </a>
                </label>
            </span>
        </td>
        <td align="right" width="10%" style="padding: 0px !important;">
            <div style="padding-right:5px">
            <span class="input-group-btn">
                <button class="btn btn-sm btn-primary no-radius" type="button" onclick="showAllReportList()" style="width: 80px">
                    <i class="icon-search"></i>
                    {{transBusinessManage("captions.search")}}
                </button>
                <button class="btn btn-sm btn-warning no-radius" type="button" onclick="showAllReportListExcel()" style="margin-left: 5px; width :80px">
                    <i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b>
                </button>
            </span>
            </div>
        </td>
        </tr>
        </table>
    </div>
    <div class="space-6"></div>
    <div id="allPersonTableDiv">
        <table id="AllPersonTable" class="table table-bordered table-hover">
            <thead class="table-head">
            <tr class="black br-hblue">
                <th class="center" style="width: 5%">{{transBusinessManage("captions.no")}}No</th>
                <th class="center" style="width: 10%">{{transBusinessManage("captions.department")}}</th>
                <th class="center" style="width: 5%">{{transBusinessManage("captions.duty")}}</th>
                <th class="center" style="width: 5%">{{transBusinessManage("captions.name")}}</th>
                <th class="center" style="width: 8%">{{transBusinessManage("captions.inputtime")}}</th>
                <th class="center" style="width: 12%">{{transBusinessManage("captions.scheduleitem")}}</th>
                <th class="center" style="width: 10%">{{transBusinessManage("captions.task")}}</th>
                <th class="center" style="width: 5%">{{transBusinessManage("captions.processstate")}}</th>
                <th class="center" style="width: 18%">{{transBusinessManage("captions.plain")}}</th>
                <th class="center" style="width: 22%">{{transBusinessManage("captions.report")}}</th>
            </tr>
            </thead>
            <tbody id="All_Person_Report">
            <?php $index = 0; ?>
            @foreach($list as $report)
                <tr>
                    <td class="center num">{{ $index + 1}}</td>
                    <td class="center unit">{{ $report['title'] }}</td>
                    <td class="center pos">{{ $report['pos'] }}</td>
                    <td class="center name realname">{{ $report['realname'] }}</td>
                    <td class="center">{!! convert_datetime($report['update_at']) !!}</td>
                    <td class="center">{{ $report['mainPlan'] }}</td>
                    <td class="center">{{ $report['task'] }}</td>
                    <td class="center">{{ $report['rate'] }}</td>
                    <td style="vertical-align: top;">{!! nl2br($report['plan']) !!}</td>
                    <td style="vertical-align: top;">{!! nl2br($report['report']) !!}</td>
                </tr>
                <?php $index++ ?>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
