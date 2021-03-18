<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
<style>
    .table tbody > tr > td {
        font-size: 12px!important;
    }
    .table tbody > tr > td {
        padding: 0!important;
    }
    .table tbody > tr > .custom-td-report-text, .table tbody > tr > .custom-td-dec-text {
        padding: 0!important;
    }
    .table .custom-td-label1 {
        width: 40%;
    }

    .form-control {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .chosen-single {
        padding: 4px!important;
        border-radius: 0!important;
        border: unset!important;
        font-size: 12px!important;
    }
    .input-group-addon {
        font-size: 12px!important;
        padding: 0 4px!important;
        border: unset!important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" name="shipId"
               value="@if(isset($shipInfo['id'])) {{$shipInfo['id']}} @else 0 @endif">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_tabName" value="#remarks">

        <textarea class="form-control" name="Remarks" rows="20">{{ isset($shipInfo['Remarks']) ? $shipInfo['Remarks'] : '' }}</textarea>
    </div>
</div>
