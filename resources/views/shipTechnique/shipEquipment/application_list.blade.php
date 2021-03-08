<table id="tbl_app" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th  class="center" colspan="9">배신청(Application)</th>
        {{--<th  class="center" colspan="5">가격제안(Quotation)</th>
        <th  class="center" colspan="3">공급지시(Instruction)</th>--}}
    </tr>
    <tr>
        <td rowspan="2"></td>
        <th class="center">배이름(Ship)</th>
        <th rowspan="2" class="center">No</th>
        <th class="center">부문(Dept)</th>
        <th class="center">설비(Equipment)</th>
        <th class="center">부속자재(Part/Issa/Others)</th>
        <th class="center">수량</th>
        <th class="center">단위</th>
        <th class="center">Y/N</th>

    </tr>
    <tr>
        <th class="center">신청항차(Voy)</th>
        <th class="center">구분(Kind)</th>
        <th class="center">계렬번호(SN)</th>
        <th class="center">PartNo/IssaCodeNo/Special</th>
        <th colspan="3" class="center">비고</th>

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