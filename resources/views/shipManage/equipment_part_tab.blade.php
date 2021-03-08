<table class="table table-striped table-bordered" id="parts-table">
    <thead>
    <tr>
        <th class="center" style="width:60px">No</th>
        <th class="center">{{ transShipManager('captions.product_name(cn)') }}</th>
        <th class="center">{{ transShipManager('captions.product_name(en)') }}</th>
        <th class="center">{{ transShipManager('captions.product_no') }}</th>
        <th class="center">{{ transShipManager('captions.codenumber') }}</th>
        <th class="center" style="width:80px">{{ transShipManager('captions.unit') }}</th>
        <th class="center">{{ transShipManager('captions.qtty') }}</th>
        <th class="center">{{ transShipManager('captions.special') }}</th>
        <th class="center">{{ transShipManager('captions.remark') }}</th>
        <th class="center" style="width:65px"></th>
    </tr>
    </thead>
    <tbody id="patrs_table_body">
    <?php $index = ($parts->currentPage() - 1) * 5 + 1; ?>
    @foreach($parts as $part)
        <tr>
            <td class="hidden">{{$part['id']}}</td>
            <td class="hidden">{{$part['Unit']}}</td>
            <td class="center">{{$index++}}</td>
            <td class="center">{{$part['PartName_Cn']}}</td>
            <td class="center">{{$part['PartName_En']}}</td>
            <td class="center">{{$part['PartNo']}}</td>
            <td class="center">{{$part['IssaCodeNo']}}</td>
            <td class="center">{{$part['Unit_En']}}</td>
            <td class="center">{{$part['Qtty']}}</td>
            <td class="center">{{$part['Special']}}</td>
            <td class="center">{{$part['Remark']}}</td>
            <td class="center action-buttons">
                <div class="row_edit">
                    <a class="blue part_edit"><i class="icon-edit bigger-130"></i></a>
                    <a class="red part_delete"><i class="icon-trash bigger-130"></i></a>
                </div>
                <div class="row_apply" style="display: none">
                    <a class="blue part_save"><i class="icon-save bigger-130"></i></a>
                    <a class="red part_cancel"><i class="icon-remove bigger-130"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <td class="hidden"></td>
        <td class="hidden"></td>
        <td class="center">{{$index}}</td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center">
            <select class="form-control chosen-select">
                <option value=""></option>
                @foreach($units as $unit)
                    <option value="{{$unit['id']}}">{{$unit['Unit_En']}}</option>
                @endforeach
            </select>
        </td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="action-buttons">
            <a class="blue add_part">
                <i class="icon-plus bigger-130"></i>
            </a>
        </td>
    </tr>
    </tbody>
</table>
{!! $partPaginate !!}
