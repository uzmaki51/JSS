<div class="modal-header no-padding">
    <div class="table-header" style="font-size: 18px">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        @if(isset($type['id']))
            修改船舶类型
        @else
            添加
        @endif
    </div>
</div>

<div class="modal-body">
    <form class="form-horizontal">
        <div class="form-group">
            <label class="col-md-3 control-label no-padding-right">船舶类型名称(中文):</label>
            <div class="col-md-8"><input type="text" id="ship_type_Cn" class="form-control" value="{{$type['ShipType_Cn']}}"></div>
        </div>
        <div class="space-2"></div>
        <div class="form-group">
            <label class="col-md-3 control-label no-padding-right">船舶类型名称(英文):</label>
            <div class="col-md-8"><input type="text" id="ship_type_en" class="form-control" value="{{$type['ShipType']}}"></div>
        </div>
    </form>
</div>
<div class="modal-footer no-margin-top">
    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal" id="modify-dialog-close">
        <i class="icon-remove"></i>
        보관
    </button>
</div>