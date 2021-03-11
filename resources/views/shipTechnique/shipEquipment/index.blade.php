@extends('layout.bss')
@section('style')
    <style>
        .ace-nav > li.active > a {
            background-color: #7DB0D2;
        }
        .nav-tabs>li.active>a, .nav-tabs>li>a:hover,.nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus{
            cursor: pointer;
        }
        .padding-left{
            padding-left: 12px;;
        }
    </style>
@stop

@section('topmenu')
    {{--우의 메뉴--}}
    @include('layout.topbanner',array('topmenus' => $topmenus, 'currenttopmenu' => $currenttopmenu))
@stop

@section('content')
    {{--왼쪽의 부분메뉴에--}}
    @include('partials.sidebar.menus',array('menus' => $menus,'current' => $current))
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
            <div class="tabbable">
                <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                    <li class="active">
                        <a data-toggle="tab" href="#planinput">计划输入</a>
                    </li>

                    <li class="">
                        <a data-toggle="tab" href="#plancollection">计划综合</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="planinput" class="tab-pane active">
                        <div class="space-10"></div>
                        <div class="row">
                            <div class="col-md-3 form-horizontal">
                                <label class="control-label no-padding-right padding-left" style="float: left;">年</label>

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
                            <div class="col-md-4" style="text-align: right">
                                <button class="btn btn-xs btn-primary" id="id-btn-adddialog">
                                    <i class="icon-plus-sign bigger-50"></i>
                                    添加
                                </button>
                            </div>

                        </div>
                        <div class="space-10"></div>
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
                                            <td>{{$supplyplan->Yearly}}</td>
                                            <td>{{$supplyplan->Monthly}}</td>
                                            <td>{{$supplyplan->shipName_Cn}}</td>
                                            <td>{{$supplyplan->Dept_Cn}}</td>
                                            <td>{{$supplyplan->PlanContent}}</td>
                                            <td>{{$supplyplan->Amount}}</td>
                                            <td>{{$supplyplan->Remark}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="plancollection" class="tab-pane">
                        <div class="space-10"></div>
                        <div class="row">
                            <div class="col-md-3 form-horizontal">
                                <label class="control-label no-padding-right padding-left" style="float: left;">年</label>

                                <div class="col-sm-8">
                                    <select style="width:100%" onchange="yearChange_Collection()" id="yearCol">
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
                                    <select style="width:100%" onchange="shipNameChange_Collection()" id="shipNameCol">
                                        <option value="{{0}}" @if(!isset($shipName['name'])) selected @endif></option>
                                        @foreach($shipList as $ship)
                                            <option value="{{ $ship['ShipName'] }}"
                                                    @if(isset($shipName['name']) && ($shipName['name'] == $ship['ShipName'])) selected @endif>{{ $ship['shipName_Cn'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="space-10"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="ship_supplyplancollection_table">
                                    <thead>
                                    <tr>
                                        <td class="center">年</td>
                                        <td class="center">船舶名称</td>
                                        <td class="center">部门</td>
                                        <td class="center">1月</td>
                                        <td class="center">2月</td>
                                        <td class="center">3月</td>
                                        <td class="center">4月</td>
                                        <td class="center">5月</td>
                                        <td class="center">6月</td>
                                        <td class="center">7月</td>
                                        <td class="center">8月</td>
                                        <td class="center">9月</td>
                                        <td class="center">10月</td>
                                        <td class="center">11月</td>
                                        <td class="center">12月</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($supplyplancollectionlist as $supplyplancollection)
                                        <tr>
                                            <td>{{$supplyplancollection->Yearly}}</td>
                                            <td>{{$supplyplancollection->Monthly}}</td>
                                            <td>{{$supplyplancollection->shipName_Cn}}</td>
                                            <td>{{$supplyplancollection->Dept_Cn}}</td>
                                            <td>{{$supplyplancollection->Plancontent}}</td>
                                            <td>{{$supplyplancollection->Amount}}</td>
                                            <td>{{$supplyplancollection->Remark}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        function yearChange_Collection() {
            var curyear = $('#yearCol').val();
//            location.href = 'supplyplancollection?topmenuId=7&menuId=80&submenu=87&year=' + curyear;
            $("#plancollection").empty();
        }
        function shipNameChange_Collection() {

            var curyear = $('#year').val();
            var shipname = $("#shipNameCol").val();
//            location.href = 'supplyplancollection?topmenuId=7&menuId=80&submenu=87&year=' + curyear + '&shipName=' + shipname;
            $("#plancollection").empty();
            console.log(shipname);
        }
    </script>
@endsection