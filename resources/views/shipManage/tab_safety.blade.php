<?php
    $isHolder = Session::get('IS_HOLDER');
    $shipList = Session::get('shipList');
?>
<form role="form" method="POST"
      action="{{url('shipManage/saveShipSafetyData')}}" enctype="multipart/form-data">
    <input type="hidden" name="_tabName" value="#safety">
    <div class="row">
        <div class="col-md-12">
            <div class="space-10"></div>
            <div class="col-md-8 col-md-offset-2">
                <table class="table table-bordered table-striped table-hover" id="ship_position_table">
                    <thead>
                        <tr>
                            <th class="center" style="width: 80px">{{ transShipManager('registerShipData.OrderNo') }}</th>
                            <th class="center">{{ transShipManager('registerShipData.Duty') }}</th>
                            <th class="center">{{ transShipManager('registerShipData.STCW code') }}</th>
                            <th class="center" style="width:70px">{{ transShipManager('registerShipData.Persons') }}</th>
                            <th class="center" style="width:70px"></th>
                        </tr>
                    </thead>
                    <tbody id="pos_table">
                        <?php $maxPriority = 0; ?>
                        @foreach($posList as $pos)
                            <tr>
                                <td class="hidden">{{$pos['id']}}</td>
                                <td class="hidden">{{$pos['DutyID']}}</td>
                                <td class="hidden">{{$pos['STCWRegCodeID']}}</td>
                                <td class="center">{{$pos['Priority']}}</td>
                                <td class="center">{{ $pos['Duty'] }} &nbsp;|&nbsp;{{ $pos['Duty_En'] }}</td>
                                <td class="center">{{$pos['STCWRegCode']}}</td>
                                <td class="center">{{$pos['PersonNum']}}</td>
                                <td class="center action-buttons">
                                    <div class="row_edit">
                                        <a class="blue duty_edit"><i class="icon-edit bigger-130"></i></a>
                                        <a class="red duty_delete"><i class="icon-trash bigger-130"></i></a>
                                    </div>
                                    <div class="row_apply" style="display: none">
                                        <a class="blue duty_save"><i class="icon-save bigger-130"></i></a>
                                        <a class="red duty_cancel"><i class="icon-remove bigger-130"></i></a>
                                    </div>
                                </td>
                                @if($maxPriority < $pos['Priority'])
                                    <?php $maxPriority = $pos['Priority']; ?>
                                @endif
                            </tr>
                        @endforeach
                            <tr>
                                <td class="hidden"></td>
                                <td class="hidden"></td>
                                <td class="hidden"></td>
                                <td class="center">
                                    <select class="form-control">
                                        @for($i=1;$i<$maxPriority+2;$i++)
                                            <option class="center" value="{{$i}}" @if($i == ($maxPriority+1)) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="center">
                                    <select class="form-control chosen-select">
                                        <option value="0"></option>
                                        @foreach($shipPos as $position)
                                            <option value="{{$position['id']}}">{{ $position['Duty'] }} &nbsp;|&nbsp;{{$position['Duty_En']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="center">
                                    <select class="form-control chosen-select">
                                        <option value="0"></option>
                                        @foreach($codeList as $code)
                                            <option value="{{$code['id']}}">{{$code['STCWRegCode']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="center">
                                    <input type="number" style="width:100%">
                                </td>
                                <td class="action-buttons">
                                    <a class="green add_duty">
                                        <i class="icon-plus bigger-130"></i>
                                    </a>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="space-4"></div>

</form>

<script>

    var maxPriority = '{!!$maxPriority!!}' * 1;
    var positionList = new Array();
    var index = 0;
    @foreach($shipPos as $position)
        var pos = new Object();
        pos.id = '{!! $position['id'] !!}';
        pos.duty = "{!! $position['Duty'] !!}" + "&nbsp;|&nbsp;" + "{!! $position['Duty_En'] !!}";
        positionList[index] = pos;
        index++;
    @endforeach

    var codeList = new Array();
    index = 0;
    @foreach($codeList as $code)
       var code = new Object();
        code.id = '{!! $code['id'] !!}';
        code.regCode = '{!! $code['STCWRegCode'] !!}';
        codeList[index] = code;
        index++;
    @endforeach

    $(function() {
         bindButtonAction();
    });

</script>