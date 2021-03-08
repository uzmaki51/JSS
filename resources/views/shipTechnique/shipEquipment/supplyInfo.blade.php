<?php
if(isset($excel)) $header = 'excel-header';
else $header = 'sidebar';
?>
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@extends('layout.'.$header)

@section('content')

@if(!isset($excel))

    <div class="main-content">
        <style>
            .chosen-container.chosen-container-single a {
                height: 26px;
            }
        </style>
        <div class="page-content">
            <div class="page-year-view">
                <div class="page-header">
                    <div class="col-md-3">
                        <h4><b>설비부속자재</b>
                            <small>
                                <i class="icon-double-angle-right"></i>
                                공급정형열람
                            </small>
                        </h4>
                    </div>
                </div>
                <div class="col-md-12" style="float: left">
                    <div class="col-md-3 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">배이름</label>

                        <div class="col-sm-8">
                            <select id="shipId" name="shipId" class="form-control chosen-select"
                                    style="height: 25px" onchange="shipChange(this.value)">
                                @foreach($shipInfos as $shipInfo)
                                    @if(!$isHolder)
                                        <option value="{{$ship['RegNo']}}"
                                                @if(isset($shipId) && ($shipId == $shipInfo['RegNo'])) selected @endif>{{ $shipInfo['shipName_Cn'] .' | ' .$shipInfo['shipName_En']}}
                                        </option>
                                    @elseif(in_array($shipInfo->shipID, $ships))
                                        <option value="{{$shipInfo['RegNo']}}"
                                                @if(isset($shipId) && ($shipId == $shipInfo['RegNo'])) selected @endif>{{ $shipInfo['shipName_Cn'] .' | ' .$shipInfo['shipName_En']}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">구분</label>

                        <div class="col-sm-8" id="kindList">
                            <select id="kind" name="kind" class="form-control chosen-select"
                                    style="height: 25px" onchange="kindChange(this.value)">
                                @foreach($kinds as $kind)
                                    <option value="{{$kind['id']}}"
                                            @if (isset($kindId)) @if ($kind['id']==$kindId) selected @endif @endif>{{$kind['Kind_Cn']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">설비명</label>

                        <div class="col-sm-8" id="equipList">
                            <select name="equipment" id="equipment" class="form-control chosen-select"
                                    @if($kindId != 1 && $kindId != 2) disabled @endif
                                    style="height: 25px" onchange="equipmentChange(this.value)">
                                @foreach($equipInfos as $equipInfo)
                                    <option value="{{$equipInfo['id']}}"
                                            @if (isset($equip)) @if ($equipInfo['id']==$equip) selected <?php $temp = $equipInfo ?>@endif @endif>{{$equipInfo['Euipment_Cn']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1" style="float: right">
                        <div style="float:right; padding:0">
                                <span class="input-group-btn" style="padding: 0px 5px;">
                                    <button class="btn btn-xs btn-info" type="button" onclick="onSearch()" style="width: 80px">
                                        <i class="icon-search"></i>
                                        검색
                                    </button>
                                </span>
                                <span class="input-group-btn" style="padding: 0px 5px;">
                                    <button class="btn btn-xs btn-warning" type="button" onclick="onExcel()" style="width: 80px">
                                        <i class="icon-table"></i>
                                        Excel
                                    </button>
                                </span>
                                {{--<span class="input-group-btn">--}}
                                    {{--<button class="btn btn-xs btn-info no-radius" type="button" onclick="onDetailSearch()" style="width: 80px">--}}
                                        {{--<i class="icon-eye-open"></i>--}}
                                        {{--상세검색--}}
                                    {{--</button>--}}
                                {{--</span>--}}
                        </div>
                    </div>
                    <div class="col-md-12 form-horizontal" style="margin-top: 10px; float:left;">

                        <label class="control-label no-padding-right" style="float: left;">자호</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="equipLabel" disabled
                                   @if(isset($temp)) value="{{$temp['Label']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['Label']}}" @endif>
                        </div>
                        <label class="control-label no-padding-right" style="float: left;">형(Type)</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="equipType" disabled
                                   @if(isset($temp)) value="{{$temp['Type']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['Type']}}" @endif>
                        </div>
                        <label class="control-label no-padding-right" style="float: left;">계렬번호(SN)</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="equipSn" disabled
                                   @if(isset($temp)) value="{{$temp['SN']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['SN']}}" @endif>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="space-10"></div>
                    <div class="row" style="width: 100%;">
                        <div style="width: 100%; overflow-y: scroll">
@else
    @include('layout.excel-style')
@endif
                            <table id="tbl_app" class="table table-striped table-bordered table-hover" style="margin-bottom: 0px;">
                                <thead>
                                <tr class="black br-hblue">
                                    <th class="center" style="width: 12%; word-break: break-all;">{{transShipTech("supplyInfo.SS")}}</th>
                                    <th class="center" style="width: 8%; word-break: break-all;">{{transShipTech("supplyInfo.SS PartNo")}}</th>
                                    <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("supplyInfo.Object")}}</th>
                                    <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("supplyInfo.ApplVoy")}}</th>
                                    <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("supplyInfo.SuplVoy")}}</th>
                                    <th class="center" style="width: 11%; word-break: break-all;">{{transShipTech("supplyInfo.Place")}}</th>
                                    <th class="center" style="width: 16%; word-break: break-all;">{{transShipTech("supplyInfo.Supplier")}}</th>
                                    <th class="center" style="width: 6%; word-break: break-all;">{{transShipTech("supplyInfo.SupplyDate")}}</th>
                                    <th class="center" style="width: 5%; word-break: break-all;">{{transShipTech("supplyInfo.Qtty")}}</th>
                                    <th class="center" style="width: 3%; word-break: break-all;">{{transShipTech("supplyInfo.Unit")}}</th>
                                    <th class="center" style="width: 5%; word-break: break-all;">{{transShipTech("supplyInfo.Price")}}($)</th>
                                    <th class="center" style="width: 5%; word-break: break-all;">{{transShipTech("supplyInfo.Amount")}}($)</th>
                                    <th class="center" style="width: 5%; word-break: break-all;">{{transShipTech("supplyInfo.Total")}}($)</th>
                                    <th class="center" style="width: 5%; word-break: break-all;">{{transShipTech("supplyInfo.Check")}}</th>
                                </tr>

                                </thead>
                            </table>
@if(!isset($excel))
                        </div>
                        <div style="width: 100%; overflow-y: scroll; height: 65vh;">
@endif
                            <table id="tbl_app" class="table table-striped table-bordered table-hover">
                                <tbody>

                                <?php
                                $totalReciptQtty = 0;
                                $avgPrice = 0;
                                $cntPrice = 0;
                                $totalAmount = 0;
                                $totalTotalAmount = 0;
                                $cnt = 0;
                                $suppleCnt = 0;
                                ?>
                            @foreach($supplyInfos as $supplyInfo)
                                <tr data-id="{{ $supplyInfo->id }}">
                                    <td class="center" style="width: 12%; word-break: break-all;">
                                        @if($kindId == 1 || $kindId == 2)
                                            {{$supplyInfo['PartName_Cn']}}
                                        @elseif($kindId == 3)
                                            {{$supplyInfo['Content_Cn']}}
                                        @else
                                            {{$supplyInfo['Others_Cn']}}
                                        @endif
                                    </td>
                                    <td class="center" style="width: 8%; word-break: break-all;">
                                        @if($kindId == 1 || $kindId == 2)
                                            {{$supplyInfo['PartNo']}}
                                        @elseif($kindId == 3)
                                            {{$supplyInfo['CodeNo']}}
                                        @else
                                            {{$supplyInfo['Special']}}
                                        @endif
                                    </td>
                                    <td class="center" style="width: 6%; word-break: break-all;">
                                        {{$supplyInfo['QuotObject']}}</td>
                                    <td class="center" style="width: 6%; word-break: break-all;">
                                        @if(!empty($supplyInfo['Application']['Voy_No'])) {{$supplyInfo['Application']['Voy_No']}} @endif</td>
                                    <td class="center" style="width: 6%; word-break: break-all;">
                                        @if(!empty($supplyInfo['Recipt']['Voy_No'])) {{$supplyInfo['Recipt']['Voy_No']}} @endif</td>
                                    <td class="center" style="width: 11%; word-break: break-all;">
                                        {{$supplyInfo['ReciptPlace']}}</td>
                                    <td class="center" style="width: 16%; word-break: break-all;">
                                        {{$supplyInfo['Supplier']}}</td>
                                    <td class="center" style="width: 6%; word-break: break-all;">
                                        {{$supplyInfo['ReciptDate']}}</td>
                                    <td class="center" style="width: 5%; word-break: break-all;">
                                        {{$supplyInfo['ReciptQtty']}}</td>
                                    <td class="center" style="width: 3%; word-break: break-all;">
                                        {{$supplyInfo['Unit_Cn']}}</td>
                                    <td class="center" style="width: 5%; word-break: break-all;">
                                        {{$supplyInfo['ReciptPrice']}}</td>
                                    <td class="center" style="width: 5%; word-break: break-all;">
                                        {{$supplyInfo['Amount']}}</td>
                                    <td class="center" style="width: 5%; word-break: break-all;">
                                        {{$supplyInfo['TotalAmount']}}</td>
                                    <?php
                                    $totalReciptQtty += $supplyInfo['ReciptQtty'];
                                    if(!empty($supplyInfo['ReciptPrice'])) {
                                        $avgPrice += $supplyInfo['ReciptPrice'];
                                        $cntPrice++;
                                    }
                                    $totalAmount += $supplyInfo['Amount'];
                                    $totalTotalAmount += $supplyInfo['TotalAmount'];
                                    $cnt++;
                                    if($supplyInfo['ReciptCheck'] == 1)
                                        $suppleCnt++;
                                    ?>
                                    <td class="center" style="width: 5%; word-break: break-all;">
@if(!isset($excel))
                                            <input type="checkbox" disabled @if($supplyInfo['ReciptCheck']==1) checked @endif>
@else
                                            @if($supplyInfo['ReciptCheck'] == 1) 공급 @endif
@endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="text-align: right;width: 74%; word-break: break-all;font-weight: bold;" colspan="8">계(총 {{ $cnt }} 건 중 / 공급 {{ $suppleCnt }} 건)</td>
                                    <td class="center" style="width: 5%; word-break: break-all;font-weight: bold;">{{ $totalReciptQtty }}</td>
                                    <td style="width: 3%; word-break: break-all;font-weight: bold;"></td>
                                    <td class="center" style="width: 5%;"></td>
                                    <td class="center" style="width: 5%; word-break: break-all;font-weight: bold;">{{ $totalAmount }}</td>
                                    <td class="center" style="width: 5%; word-break: break-all;font-weight: bold;">{{ $totalTotalAmount }}</td>
                                    <td style="width: 3%; word-break: break-all;"></td>
                                </tr>

                                </tbody>

                            </table>
@if(!isset($excel))
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        var token = '<?php echo csrf_token() ?>';
        var myWindow;

        function onSearch() {
            var curShipId = $('#shipId').val();
            var voy = $('#kind').val();
            var equipId = $('#equipment').val();

            if (curShipId == '-1') {
                alert('배이름을 선택하십시오.');
            } else {
                location.href = '{{url('shipTechnique/showSupplyInfo')}}' + '?shipId=' + curShipId + '&kind=' + voy + '&equip=' + equipId;
            }

        }

        function onExcel() {
            var curShipId = $('#shipId').val();
            var voy = $('#kind').val();
            var equipId = $('#equipment').val();

            if (curShipId == '-1') {
                alert('배이름을 선택하십시오.');
            } else {
                location.href = '{{url('shipTechnique/showSupplyInfoExcel')}}' + '?shipId=' + curShipId + '&kind=' + voy + '&equip=' + equipId;
            }

        }

        function shipChange(value) {
            var curShip = $('#shipId').val();
            $.post('/shipTechnique/getEquipmentKindInfo', {
                _token: token,
                shipId: curShip
            }, function (data) {
                var temp = data.split('@');
                $('#kindList').html(temp[0]);
                //$('#kind').next().find("ul").html(temp[1]);
                //$('#kind').next().find("a span").text(temp[2]);
                $('#equipList').html(temp[3]);
                //$('#equipment').next().find("ul").html('');
                //$('#equipment').next().find("a span").text(temp[5]);
                $('#equipLabel').val(temp[6]);
                $('#equipType').val(temp[7]);
                $('#equipSn').val(temp[8]);
                $('.chosen-select').chosen('destroy').chosen();
            });
        }

        function kindChange(value) {
            var curShip = $('#shipId').val();
            var voy = $('#kind').val();
            $.post('/shipTechnique/getEquipmentInfo', {
                _token: token,
                shipId: curShip,
                kind: voy
            }, function (data) {
                console.log(data);
                var temp = data.split('@');
                //console.log(temp[0]);
                $('#equipList').html(temp[0]);
                //$('#equipment').next().find("ul").html('');
                //$('#equipment').next().find("a span").text(temp[2]);
                $('#equipLabel').val(temp[3]);
                $('#equipType').val(temp[4]);
                $('#equipSn').val(temp[5]);
                $('.chosen-select').chosen();
            });
        }

        function equipmentChange(value) {
            var equipId = $('#equipment').val();
            $.post('/shipTechnique/getEquipmentDetailInfo', {
                _token: token,
                equipId: equipId,
            }, function (data) {
                console.log(data);
                var temp = data.split('@');
                $('#equipLabel').val(temp[0]);
                $('#equipType').val(temp[1]);
                $('#equipSn').val(temp[2]);
            });
        }
        function onDetailSearch(){
            var curShipId = $('#shipId').val();
            var voy = $('#kind').val();
            var equipId = $('#equipment').val();
            var width = screen.width * 0.6;
            var height = screen.height * 0.6;
            var left = (screen.width - width) / 2;
            var attr = "width=" + width + ",height=" + height + ",left="+ left +"px,top=160px";
            myWindow = window.open('{{url('shipTechnique/detailSupplyInfo')}}' + '?shipId=' + curShipId + '&kind=' + voy + '&equip=' + equipId,
                    "설비부속자재 공급정형 상세검색", attr);
        }

        function onDetailSearchResult(shipId, kind, equip, part, QuotObject, ApplicationVoy, ReciptVoy, ReciptPlace, Supplier, ReciptDate) {
            window.location.href = "{{ url('shipTechnique/showDetailSupplyInfo') }}?shipId=" + shipId + "&kind=" + kind + "&equip=" + equip +
                                    "&part=" + part + '&QuotObject=' + QuotObject +
                                    '&ApplicationVoy=' + ApplicationVoy + '&ReciptVoy=' + ReciptVoy + '&ReciptPlace=' + ReciptPlace +
                                    '&Supplier=' + Supplier + '&ReciptDate=' + ReciptDate;
            myWindow.close();
        }
    </script>
@endif

@endsection

