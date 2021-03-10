<table id="tbl_app" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th  class="center" colspan="9">船舶申请(Application)</th>
        {{--<th  class="center" colspan="5">价格提案(Quotation)</th>
        <th  class="center" colspan="3">更急指示(Instruction)</th>--}}
    </tr>
    <tr>
        <td rowspan="2"></td>
        <th class="center">船舶名称</th>
        <th rowspan="2" class="center">No</th>
        <th class="center">部门</th>
        <th class="center">设备</th>
        <th class="center">配件(Part/Issa/Others)</th>
        <th class="center">数量</th>
        <th class="center">单位</th>
        <th class="center">Y/N</th>

    </tr>
    <tr>
        <th class="center">申请航次(Voy)</th>
        <th class="center">区分(Kind)</th>
        <th class="center">编号 (SN)</th>
        <th class="center">PartNo/IssaCodeNo/Special</th>
        <th colspan="3" class="center">备注</th>

    </tr>

    </thead>
    <tbody>
    <?php $index=1;?>
    @foreach($supplyInfos as $supplyInfo)
        <tr>
            <td class="center" rowspan="2">{{$index}}</td>
            <td class="center">{{$supplyInfo['shipName_Cn']}}</td>
            <td rowspan="2" class="center">{{$supplyInfo['No']}}</td>
            <td class="center">{{$supplyInfo['Dept_Cn']}}</td>
            <td class="center">{{$supplyInfo['Equipment_Cn']}}</td>
            <td class="center">{{$supplyInfo['PartName_Cn']}}</td>
            <td class="center">{{$supplyInfo['ApplQtty']}}</td>
            <td class="center">{{$supplyInfo['Unit_Cn']}}</td>
            <td class="center">{{$supplyInfo['ApplCheck']}}</td>

        </tr>
        <tr>
            <td class="center">{{$supplyInfo['ApplicationVoy']}}</td>
            <td class="center">{{$supplyInfo['Kind_Cn']}}</td>
            <td class="center">{{$supplyInfo['SN']}}</td>
            <td class="center">{{$supplyInfo['Content_Cn']}}</td>
            <td colspan="3" class="center">{{$supplyInfo['AppBigo']}}</td>

        </tr>
        <?php $index++;?>
        @endforeach
    </tbody>
</table>
{!! $supplyInfos->render() !!}