<div class="modal-content">
    <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
            <thead>
            <tr>
                <th class="center">No</th>
                <th class="center">种类(中文)</th>
                <th class="center">种类(英文)</th>
                <th class="center"></th>
            </tr>
            </thead>

            <tbody>
                <?php $index = 1; ?>
                @foreach($list as $type)
                    <tr>
                        <td class="center">{{$index}}</td>
                        <td class="center">{{$type['ShipType_Cn']}}</td>
                        <td class="center">{{$type['ShipType']}}</td>
                        <td>
                            <div class="action-buttons">
                                <a class="blue" href="javascript:modifyShipItem('ShipType', '{{$type['id']}}')">
                                    <i class="icon-edit bigger-130"></i>
                                </a>

                                <a class="red" href="javascript:deleteShipType({{$type['id']}})">
                                    <i class="icon-trash bigger-130"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php $index++ ?>
                @endforeach
            </tbody>
        </table>
    </div>
</div>