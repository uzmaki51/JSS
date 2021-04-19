@extends('layout.header')

@section('content')
    <div class="main-content">
        <style>
            .chosen-container.chosen-container-single a {
                height: 26px;
            }
        </style>
        <div class="page-content">
            <div class="page-year-view">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4><b>设备,配件,材料</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                攻击计划业绩综合
                            </small>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <form method="get" action >
                    <div class="col-md-3 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">年</label>

                        <div class="col-sm-8">
                            <select id="yearly" name="yearly" class="form-control chosen-select" style="height: 25px">
                                @foreach($years as $year)
                                    <option value="{{ $year['Yearly'] }}"
                                            @if($this_year == $year['Yearly']) selected @endif>{{ $year['Yearly'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9" style="text-align: right">
                        <div>
                                <span class="input-group-btn">
                                    <button class="btn btn-xs btn-primary no-radius" type="submit" style="width: 80px">
                                        <i class="icon-search"></i>
                                        搜索
                                    </button>
                                </span>
                        </div>
                    </div>
                    <div class="space-10" style="margin-bottom: 40px;"></div>
                    </form>
                </div>
                @if(array_sum($plans) > 0 && array_sum($supplies) > 0)
                <div class="col-md-12" style="overflow-x: auto;">
                    <div class="row" style="font-size: 12px" id="content">
                        <table id="tbl_app" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="black br-hblue">
                                <th colspan="3" style="width: 150px;"></th>
                                <?php $shipNumber = 0; ?>
                                @foreach($ships as $ship)
                                    <th class="center shipNumber{{ $shipNumber++ }}" colspan="3" style="width: 300px">{{ $ship['shipName_Cn'] }}
                                         &nbsp;@if(!empty($ship['name']))({{ $ship['name'] }})@endif</th>
                                @endforeach
                                <td class="center" colspan="3" style="width: 300px">综合</td>
                            </tr>
                            <tr class="black br-hblue">
                                <th class="center" style="width: 50px;">年</th>
                                <th class="center" style="width: 50px;">月</th>
                                <th class="center" style="width: 50px;">区分</th>
                                <?php $shipNumber = 0; ?>
                                @foreach($ships as $ship)
                                    <th class="center shipNumber{{ $shipNumber }}" style="width: 100px">供给计划[$]</th>
                                    <th class="center shipNumber{{ $shipNumber }}" style="width: 100px">供给业绩[$]</th>
                                    <th class="center shipNumber{{ $shipNumber++ }}" style="width: 100px">完成率[%]</th>
                                @endforeach
                                <th class="center" style="width: 100px">供给计划[$]</th>
                                <th class="center" style="width: 100px">供给业绩[$]</th>
                                <th class="center" style="width: 100px">完成率[%]</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $index = 0; $col = array(); $ship_index = 0;
                                    foreach($ships as $ship) {
                                        $col[$ship_index] = array(0, 0);
                                        $ship_index++;
                                    }
                                    $ship_index = 0;
                            ?>
                            @for($month = 1; $month < 13; $month++)
                                <?php $dept_index = 0; $monthly_report = array();?>
                                @foreach($deptInfos as $deptInfo)
                                    <tr>
                                        <?php
                                            $row = [0,0];
                                            $ship_index = 0;
                                        ?>
                                        @if($dept_index == 0)
                                            @if($month == 1)
                                                <td class="center" rowspan="48">{{ $this_year }}</td>
                                            @endif
                                            <td class="center" rowspan="3"> {{ $month }}</td>
                                        @endif
                                        <td class="center">{{ $deptInfo['Dept_Cn'] }}</td>
                                        <?php $shipNumber = 0; ?>
                                        @foreach($ships as $ship)
                                            <td class="shipNumber{{ $shipNumber }}" style="text-align: right">
                                                @if(!empty($plans[$index])){{ \App\Http\Controllers\Util::getNumberFt($plans[$index]) }}@endif
                                            </td>
                                            <td class="shipNumber{{ $shipNumber }}" style="text-align: right">
                                                @if(!empty($supplies[$index])){{ \App\Http\Controllers\Util::getNumberFt($supplies[$index]) }}@endif
                                            </td>
                                            <td class="shipNumber{{ $shipNumber++ }}" style="text-align: right">
                                                @if(!empty($supplies[$index]))
                                                    @if($plans[$index] == 0)
                                                        {{ round($supplies[$index], 2) }}
                                                    @else
                                                        {{ round($supplies[$index] / $plans[$index] * 100, 2) }}
                                                    @endif
                                                @endif
                                            </td>
                                            <?php
                                                if(empty($monthly_report[$ship_index][0])) $monthly_report[$ship_index][0] = 0;
                                                $monthly_report[$ship_index][0] += $plans[$index];
                                                if(empty($monthly_report[$ship_index][1])) $monthly_report[$ship_index][1] = 0;
                                                $monthly_report[$ship_index][1] += $supplies[$index];
                                                $row[0] += $plans[$index];
                                                $row[1] += $supplies[$index];
                                                $col[$ship_index][0] += $plans[$index];
                                                $col[$ship_index][1] += $supplies[$index];

                                                $index++; $ship_index++; $dept_index++;
                                            ?>
                                        @endforeach
                                            <td class="" style="text-align: right;">
                                                @if(!empty($row[0])){{ \App\Http\Controllers\Util::getNumberFt($row[0]) }}@endif
                                            </td>
                                            <td class="" style="text-align: right;">
                                                @if(!empty($row[1])){{ \App\Http\Controllers\Util::getNumberFt($row[1]) }}@endif
                                            </td>
                                            <td class="" style="text-align: right;">
                                                @if(!empty($row[0]))
                                                    @if($row[0] == 0)
                                                        {{ round($row[1], 2) }}
                                                    @else
                                                        {{ round($row[1] / $row[0] * 100, 2) }}
                                                    @endif
                                                @endif
                                            </td>
                                            <?php
                                                if(!isset($monthly_report[$ship_index][0])) $monthly_report[$ship_index][0] = 0;
                                                $monthly_report[$ship_index][0] += $row[0];
                                                if(!isset($monthly_report[$ship_index][1])) $monthly_report[$ship_index][1] = 0;
                                                $monthly_report[$ship_index][1] += $row[1];
                                            ?>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="center" colspan="2">计</td>
                                    <?php $ship_index = 0; $shipNumber = 0; ?>
                                    @foreach($ships as $ship)
                                        <td class="shipNumber{{ $shipNumber }}" style="text-align: right;">
                                            @if(!empty($monthly_report[$ship_index][0]))
                                                {{ \App\Http\Controllers\Util::getNumberFt($monthly_report[$ship_index][0]) }}
                                            @endif
                                        </td>
                                        <td class="shipNumber{{ $shipNumber }}" style="text-align: right;">
                                            @if(!empty($monthly_report[$ship_index][1]))
                                                {{ \App\Http\Controllers\Util::getNumberFt($monthly_report[$ship_index][1]) }}
                                            @endif
                                        </td>
                                        <td class="shipNumber{{ $shipNumber++ }}" style="text-align: right;">
                                            @if(!empty($monthly_report[$ship_index][1]))
                                                @if($monthly_report[$ship_index][0] == 0)
                                                    {{ round($monthly_report[$ship_index][1], 2) }}
                                                @else
                                                    {{ round($monthly_report[$ship_index][1]/$monthly_report[$ship_index][0] * 100, 2) }}
                                                @endif
                                            @endif
                                        </td>
                                        <?php $ship_index++; ?>
                                    @endforeach
                                    <td class="" style="text-align: right;">
                                        @if(!empty($monthly_report[$ship_index][0]))
                                            {{ \App\Http\Controllers\Util::getNumberFt($monthly_report[$ship_index][0]) }}
                                        @endif
                                    </td>
                                    <td class="" style="text-align: right;">
                                        @if(!empty($monthly_report[$ship_index][1]))
                                            {{ \App\Http\Controllers\Util::getNumberFt($monthly_report[$ship_index][1]) }}
                                        @endif
                                    </td>
                                    <td class="" style="text-align: right;">
                                        @if(!empty($monthly_report[$ship_index][1]))
                                            @if($monthly_report[$ship_index][0] == 0)
                                                {{ round($monthly_report[$ship_index][1], 2) }}
                                            @else
                                                {{ round($monthly_report[$ship_index][1]/$monthly_report[$ship_index][0], 2) }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endfor

                            <tr>
                                <td colspan="3">综合</td>
                                <?php $ship_index = 0; $row[0] = 0; $row[1] = 0; $shipNumber = 0;?>
                                @foreach($ships as $ship)
                                    <th class="shipNumber{{ $shipNumber }}" style="text-align: right;">
                                        @if(!empty($col[$ship_index][0]))
                                            {{ \App\Http\Controllers\Util::getNumberFt($col[$ship_index][0]) }}
                                        @endif
                                    </th>
                                    <th class="shipNumber{{ $shipNumber }}" style="text-align: right;">
                                        @if(!empty($col[$ship_index][1]))
                                            {{ \App\Http\Controllers\Util::getNumberFt($col[$ship_index][1]) }}
                                        @endif
                                    </th>
                                    <th class="shipNumber{{ $shipNumber++ }}" style="text-align: right;">
                                        @if(!empty($col[$ship_index][1]))
                                            @if($col[$ship_index][0] == 0)
                                                {{ round($col[$ship_index][1], 2) }}
                                            @else
                                                {{ round($col[$ship_index][1] / $col[$ship_index][0] * 100, 2) }}
                                            @endif
                                        @endif
                                    </th>
                                    <?php
                                        $row[0] += $col[$ship_index][0];
                                        $row[1] += $col[$ship_index][1];
                                    $ship_index++;
                                    ?>
                                @endforeach
                                <th class="" style="text-align: right;">
                                    @if(!empty($row[0])){{ \App\Http\Controllers\Util::getNumberFt($row[0]) }}@endif
                                </th>
                                <th class="" style="text-align: right;">
                                    @if(!empty($row[1])){{ \App\Http\Controllers\Util::getNumberFt($row[1]) }}@endif
                                </th>
                                <th class="" style="text-align: right;">
                                    @if(!empty($row[1]))
                                        @if($row[0] == 0)
                                            {{ round($row[1], 2) }}
                                        @else
                                            {{ round($row[1] / $row[0] * 100, 2) }}
                                        @endif
                                    @endif
                                </th>
                            </tr>
                            </tbody>


                        </table>
                    </div>

                </div>
                @else
                    <div class="col-md-12 alert alert-danger" style="text-align: center;">
                        没有供给计划的业绩资料。
                    </div>
                @endif

            </div>
        </div>
    </div>
    <script type="text/javascript">
        var token = '<?php echo csrf_token() ?>';
        <?php $ship_index = 0; $shipNumber = 0; $removeCount = 0; ?>
        @foreach($ships as $ship)
            @if(empty($col[$ship_index][0]) && empty($col[$ship_index][1]))
                $('.shipNumber{{ $shipNumber}}').remove();
                <?php $removeCount ++;?>
            @endif
            <?php $ship_index++; $shipNumber++; ?>
        @endforeach
        var realyCount = <?php print_r(count($ships) - $removeCount); ?>;
        var width = (realyCount + 1) * 300 + 150;
        $("#content").width(width);
    </script>

@endsection