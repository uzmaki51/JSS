<label style="padding-top: 7px;padding-bottom: 4px">{{ transShipManager('title.tech_assistance') }}</label>
<table class="table table-striped table-bordered" id="property-table">
    <thead>
    <tr>
        <th rowspan="2">No</th>
        <th class="center">{{ transShipManager('captions.item(cn)') }}</th>
        <th class="center" rowspan="2">{{ transShipManager('captions.particular') }}<br>Particular</th>
        <th class="center" rowspan="2">{{ transShipManager('captions.remark') }}</th>
    </tr>
    <tr>
        <th>{{ transShipManager('captions.item(en)') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php $index = ($propertys->currentPage() - 1) * 5 + 1; ?>
    @foreach($propertys as $property)
        <tr>
            <td rowspan="2">{{$index++}}</td>
            <td class="center">{{$property['Items_Cn']}}</td>
            <td rowspan="2">{{$property['Particular']}}</td>
            <td rowspan="2">{{$property['Remark']}}</td>
        </tr>
        <tr>
            <td class="center">{{$property['Items_En']}}&nbsp;</td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! $propertyPaginate !!}
