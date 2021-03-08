<table class="table table-striped table-bordered" id="property-table">
    <thead>
    <tr>
        <th class="center" style="width:60px">No</th>
        <th class="center">{{ transShipManager('captions.item(cn)') }}</th>
        <th class="center">{{ transShipManager('captions.item(en)') }}</th>
        <th class="center">{{ transShipManager('captions.particular') }}</th>
        <th class="center">{{ transShipManager('captions.remark') }}</th>
        <th class="center" style="width:70px"></th>
    </tr>
    </thead>
    <tbody id="property_table">
    <?php $index = ($propertys->currentPage() - 1) * 5 + 1; ?>
    @foreach($propertys as $property)
        <tr>
            <td class="center">{{$index++}}</td>
            <td class="hidden">{{$property['id']}}</td>
            <td class="center">{{$property['Items_Cn']}}</td>
            <td class="center">{{$property['Items_En']}}</td>
            <td class="center">{{$property['Particular']}}</td>
            <td class="center">{{$property['Remark']}}</td>
            <td class="center action-buttons">
                <div class="row_edit">
                    <a class="blue property_edit"><i class="icon-edit bigger-130"></i></a>
                    <a class="red property_delete"><i class="icon-trash bigger-130"></i></a>
                </div>
                <div class="row_apply" style="display: none">
                    <a class="blue property_save"><i class="icon-save bigger-130"></i></a>
                    <a class="red property_cancel"><i class="icon-remove bigger-130"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <td class="center">{{$index}}</td>
        <td class="hidden"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="center"><input type="text" class="form-control"></td>
        <td class="action-buttons">
            <a class="blue add_property">
                <i class="icon-plus bigger-130"></i>
            </a>
        </td>
    </tr>
    </tbody>
</table>
{!! $propertyPaginate !!}
