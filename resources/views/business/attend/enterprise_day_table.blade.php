<table id="attend_info_table" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th rowspan="2" class="center number_td">{{transBusinessManage("captions.no")}}</th>
        <th rowspan="2" class="center">{{transBusinessManage("captions.departmentName")}}</th>
        <th rowspan="2" class="center number_td">{{transBusinessManage("captions.departmentcount")}}</th>
        <th rowspan="2" class="center number_td">{{transBusinessManage("captions.entercount")}}</th>
        <th colspan="12" class="center">{{transBusinessManage("captions.absencecount")}}</th>
        <th rowspan="2" class="center number_td">{{transBusinessManage("captions.late")}}</th>
        <th rowspan="2" class="center number_td">{{transBusinessManage("captions.earlyleave")}}</th>
    </tr>
    <tr>
        <th class="center number_td">{{transBusinessManage("captions.sum")}}</th>
        @foreach($typeList as $type)
            @if($type['id'] > 3)
                <th class="center number_td">{{$type['name']}}</th>
            @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
    <?php $index = 1;?>
    @foreach($units as $unit)
        <tr>
            <td class="center">{{$index++}}</td>
            <td class="center">{{$unit['title']}}</td>
            <td class="celldata center">{{$unit['userCount']}}</td>
            <td class="celldata center">{{$unit['attend']}}</td>
            <td class="celldata center">{{$unit['absence']}}</td>
            <?php $count = count($unit->valueList) + 1; ?>
            @for($i=4;$i<$count;$i++)
                <td class="celldata center">{{$unit->valueList[$i]}}</td>
            @endfor
            <td class="celldata center">{{$unit->valueList[2]}}</td>
            <td class="celldata center">{{$unit->valueList[3]}}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="center">{{transBusinessManage("captions.totalsum")}}</td>
        @for($i=0;$i<16;$i++)
            <td class="center" id="total{{$i}}"></td>
        @endfor
    </tr>

    </tbody>
</table>
