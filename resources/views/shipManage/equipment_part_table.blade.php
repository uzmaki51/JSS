<label style="padding-top: 7px;padding-bottom: 4px">{{ transShipManager('title.part') }}</label>
<table class="table table-striped table-bordered" id="part-table">
    <thead>
    <tr>
        <th rowspan="2">No</th>
        <th class="center">{{ transShipManager('captions.product_name(cn)') }}</th>
        <th class="center">{{ transShipManager('captions.product_no') }}</th>
        <th class="center" colspan="2">{{ transShipManager('captions.special') }}</th>
        <th class="center" rowspan="2">{{ transShipManager('captions.remark') }}</th>
    </tr>
    <tr>
        <th class="center">{{ transShipManager('captions.unit') }}</th>
        <th class="center">{{ transShipManager('captions.qtty') }}</th>
        <th class="center">{{ transShipManager('captions.special') }}</th>
        <th class="center">{{ transShipManager('captions.remark') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php $index = ($parts->currentPage() - 1) * 5 + 1; ?>
    @foreach($parts as $part)
        <tr>
            <td rowspan="2">{{$index++}}</td>
            <td class="center">{{$part['PartName_Cn']}}</td>
            <td class="center">{{$part['PartNo']}}</td>
            <td class="center" colspan="2">{{$part['Special']}}</td>
            <td class="center" rowspan="2">{{$part['Remark']}}</td>
        </tr>
        <tr>
            <td class="center">{{$part['PartName_En']}}</td>
            <td class="center">{{$part['IssaCodeNo']}}</td>
            <td class="center">{{$part['Unit_En']}}</td>
            <td class="center">{{$part['Qtty']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! $partPaginate !!}
