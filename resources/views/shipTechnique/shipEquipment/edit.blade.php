@extends('layout.sidebar')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>设备,配件,材料</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            供给计划登记
                        </small>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 form-horizontal">
                    <label class="control-label no-padding-right" style="float: left;">年</label>

                    <div class="col-sm-8">
                        <select style="width:100%" onchange="yearChange()" id="year">
                            <option value="{{0}}" @if(!isset($year)) selected @endif>全部</option>
                            @for( $i=$year_range->min;$i<=$year_range->max;$i++)
                                <option value="{{$i}}" @if(isset($year)&&($year==$i)) selected @endif>{{$i}}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4 form-horizontal">
                    <label class="control-label no-padding-right" style="float: left;">船舶名称</label>

                    <div class="col-sm-8">
                        <select style="width:100%" onchange="shipNameChange()" id="shipName">
                            <option value="{{0}}" @if(!isset($shipName['name'])) selected @endif></option>
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['ShipName'] }}"
                                        @if(isset($shipName['name']) && ($shipName['name'] == $ship['ShipName'])) selected @endif>{{ $ship['shipName_Cn'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="text-align: right">
                    <a href="http://www.bss.master/shipManage/registerShipData" data-toggle="modal"
                       class="btn btn-primary" style="border-radius: 3px;width :80px;"><i class="icon-plus-sign-alt"></i><b>追加</b></a>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="ship_supplyplan_table">
                        <thead>
                        <tr>
                            <td class="center">年</td>
                            <td class="center">月</td>
                            <td class="center">船舶名称</td>
                            <td class="center">部门</td>
                            <td class="center">计划内容</td>
                            <td class="center">金额[$]</td>
                            <td class="center">详细内容</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($supplyplanlist as $supplyplan)
                            <tr>
                                <td class="center">{{$supplyplan->Yearly}}</td>
                                <td class="center">{{$supplyplan->Monthly}}</td>
                                <td class="center">{{$supplyplan->shipName_Cn}}</td>
                                <td class="center">{{$supplyplan->Dept_Cn}}</td>
                                <td class="center">{{$supplyplan->Plancontent}}</td>
                                <td class="center">{{$supplyplan->Amount}}</td>
                                <td class="center">{{$supplyplan->Remark}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function yearChange() {
            var curyear = $('#year').val();
            location.href = 'supplyplan?topmenuId=7&menuId=80&submenu=87&year=' + curyear;
        }
        function shipNameChange() {
            var curyear = $('#year').val();
            var shipname = $("#shipName").val();
            location.href = 'supplyplan?topmenuId=7&menuId=80&submenu=87&year=' + curyear + '&shipName=' + shipname;
            console.log(shipname);
        }
    </script>
@endsection