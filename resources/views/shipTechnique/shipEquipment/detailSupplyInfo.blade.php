@extends('layout.header')

@section('content')
    <div class="main-content">
        <style>
            .chosen-container.chosen-container-single a {
                height: 26px;
            }
            header#header, div.sidebar#sidebar {
                display: none;
            }
            div.main-content {
                margin: 0px;
            }
            div.main-container#main-container {
                margin-top: 5px;
            }
        </style>
        <div class="page-content">
            <div class="page-year-view">
                <div class="col-md-12" style="text-align: center;"><h3>设备配件材料 供给情况仔细搜索</h3></div><br>
                
                <div class="col-md-12">
                <table align="center"  >
                <tr>
                    <td>
                    <div class="col-md-4 form-horizontal">
                        
                        <label class="control-label no-padding-right" style="float: left;">船舶名称</label>
                        
                        <div class="col-sm-8">
                            <input  class="form-control" id="shipId" disabled
                                @foreach($shipInfos as $shipInfo)
                                    @if (isset($shipId) && ($shipInfo['RegNo'] == $shipId))
                                        value="{{$shipInfo['shipName_Cn']}}@if(!empty($shipInfo['name'])) | {{$shipInfo['name']}}@endif"
                                    @endif
                                @endforeach
                            >
                        </div>
                    </div>
                    </td>
                    <td>
                    <div class="col-md-4 form-horizontal">
                        <label class="control-label no-padding-right" style="float: left;">区分</label>
                        
                        
                        <div class="col-sm-8" id="kindList">
                            <input type="text" class="form-control" id="kind" disabled
                                @foreach($kinds as $kind)
                                    @if ($kind['id']==$kindId) value="{{$kind['Kind_Cn']}}" @endif
                                @endforeach
                            >
                        </div>
                        
                    </div>
                    </td>
                    <td>
                    <div class="col-md-4 form-horizontal">
                        
                        <label class="control-label no-padding-right" style="float: left;">设备名称</label>
                        
                        <div class="col-sm-8 " id="equipList">
                            <input type="text" class="form-control" id="equipment" disabled
                                @foreach($equipInfos as $equipInfo)
                                    @if ($equipInfo['id']==$equip) value="{{$equipInfo['Euipment_Cn']}}" <?php $temp = $equipInfo ?>@endif
                                @endforeach
                            >
                        </div>
                    </div>
                    </td>
                </tr>
                <tr>
                    <div class="col-md-12 form-horizontal" style="margin-top: 10px;">

                      <!--   <div class="col-sm-3">
                        </div> -->
                        <td>
                        <label class="control-label no-padding-right" style="float: left;">字号(Label)</label>

                        <div class="col-sm-8">
                            <input  type="text" class="form-control" id="equipLabel" disabled 
                                   @if(isset($temp)) value="{{$temp['Label']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['Label']}}" @endif>
                        </div>
                        </td>
                        <td>
                        <label class="control-label no-padding-right" style="float: left;">形式(Type)</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="equipType" disabled
                                   @if(isset($temp)) value="{{$temp['Type']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['Type']}}" @endif>
                        </div>
                        </td>
                        <td>
                        <label class="control-label no-padding-right" style="float: left;">编号(SN)</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="equipSn" disabled
                                   @if(isset($temp)) value="{{$temp['SN']}}"
                                   @elseif(count($equipInfos) > 0) value="{{$equipInfos[0]['SN']}}" @endif>
                        </div>
                        </td>
                                                
                    </div>
                </tr> 
                 </table>  
                </div>
                <table align="center"  >
                
                <div class="col-md-12">
                    
                    <div class="space-10"></div>
                    <tr>
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                配件(Part/Issa/Others)
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;" id="partName">
                            <select name="partName" class="form-control chosen-select"
                                    style="height: 25px" onchange="changePart(this.value)">
                                <option value="">&nbsp;</option>
                                @foreach($Parts as $part)
                                    @if($kindId == 1 || $kindId == 2)
                                        <option value="{{$part['Part']}}">{{$part['PartName_Cn']}}</option>
                                    @elseif($kindId == 3)
                                        <option value="{{$part['IssaCodeNo']}}">{{$part['Content_Cn']}}</option>
                                    @else
                                        <option value="{{$part['Others']}}">{{$part['Others_Cn']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    <td >
                        <div style="padding-left:1.1cm;"></div>
                    </td>
                    <!-- <div class="space-10"></div> -->
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                PartNo/IssaCodeNo/Special
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;" id="partNo">
                            <select name="partNo" class="form-control chosen-select"
                                    style="height: 25px" onchange="changePart(this.value)">
                                <option value="">&nbsp;</option>
                                @foreach($Parts as $part)
                                    @if($kindId == 1 || $kindId == 2)
                                        <option value="{{$part['Part']}}">{{$part['PartNo']}}</option>
                                    @elseif($kindId == 3)
                                        <option value="{{$part['IssaCodeNo']}}">{{$part['CodeNo']}}</option>
                                    @else
                                        <option value="{{$part['Others']}}">{{$part['Special']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    </tr>
                    <div class="space-10"></div>
                    <tr>
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                提案对象
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="QuotObject" name="QuotObject" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($QuotObjects as $QuotObject)
                                    <option value="{{ $QuotObject->QuotObject }}">{{ $QuotObject->QuotObject }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    <td >
                        <div style="padding-left:1.1cm;"></div>
                    </td>
                    <!-- <div class="space-10"></div> -->
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                申请航次
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="ApplicationVoy" name="ApplicationVoy" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($ApplicationVoys as $ApplicationVoy)
                                    <option value="{{ $ApplicationVoy->ApplicationVoy }}">
                                        @if(!empty($ApplicationVoy->Application->Voy_No))
                                            {{ $ApplicationVoy->Application->Voy_No }} | {{ $ApplicationVoy->Application->CP_No }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    </tr>
                    <div class="space-10"></div>
                    <tr>
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                供给航次
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="ReciptVoy" name="ReciptVoy" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($ReciptVoys as $ReciptVoy)
                                    <option value="{{ $ReciptVoy->ReciptVoy }}">
                                        @if(!empty($ReciptVoy->Recipt->Voy_No))
                                            {{ $ReciptVoy->Recipt->Voy_No }} | {{ $ReciptVoy->Recipt->CP_No }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    <td >
                        <div style="padding-left:1.1cm;"></div>
                    </td>
                    <!-- <div class="space-10"></div> -->
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                地点
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="ReciptPlace" name="ReciptPlace" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($ReciptPlaces as $ReciptPlace)
                                    <option value="{{ $ReciptPlace->ReciptPlace }}">{{ $ReciptPlace->ReciptPlace }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    </tr>
                    <div class="space-10"></div>
                    <tr>
                    <td>
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                供给人
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="Supplier" name="Supplier" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($Suppliers as $Supplier)
                                    <option value="{{ $Supplier->Supplier }}">{{ $Supplier->Supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    <td >
                        <div style="padding-left:1.1cm;"></div>
                    </td>
                    <!-- <div class="space-10"></div> -->
                    <td >
                    <div  class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <label class="control-label no-padding-right" style="height: 25px; padding-top: 7px;">
                                供给日期
                            </label>
                        </div>
                        <div class="col-md-6" style="text-align: left;">
                            <select id="ReciptDate" name="ReciptDate" class="form-control chosen-select"
                                    style="height: 25px">
                                <option value="">&nbsp;</option>
                                @foreach($ReciptDates as $ReciptDate)
                                    <option value="{{ $ReciptDate->ReciptDate }}">{{ $ReciptDate->ReciptDate }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    </td>
                    </tr>
                </div>
                </table>

                <div class="col-md-12">
                    <div class="space-10"></div>
                    <div style="text-align: center; padding:0">
                        <span class="input-group-btn" style="padding: 0px 5px;">
                            <button class="btn btn-xs btn-primary no-radius" type="button" onclick="onSearch()" style="width: 80px">
                                <i class="icon-search"></i>
                                搜索
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var token = '<?php echo csrf_token() ?>';
        function changePart(value) {
            $('[name=partName]').val(value);
            $('#partName .chosen-container a span').text($("[name=partName] option[value=" + value + "]").text());
            $('[name=partNo]').val(value);
            $('#partNo .chosen-container a span').text($("[name=partNo] option[value=" + value + "]").text());
        }
        function onSearch() {
            var shipId = '{{ $shipId }}';
            var kind = '{{ $kindId }}';
            var equip = '{{ $equip }}';
            var part = $("[name=partName]").val();
            var QuotObject = $("[name=QuotObject]").val();
            var ApplicationVoy = $("[name=ApplicationVoy]").val();
            var ReciptVoy = $("[name=ReciptVoy]").val();
            var ReciptPlace = $("[name=ReciptPlace]").val();
            var Supplier = $("[name=Supplier]").val();
            var ReciptDate = $("[name=ReciptDate]").val();
            window.opener.onDetailSearchResult(shipId, kind, equip, part, QuotObject, ApplicationVoy, ReciptVoy, ReciptPlace, Supplier, ReciptDate);
        }
    </script>

@endsection

